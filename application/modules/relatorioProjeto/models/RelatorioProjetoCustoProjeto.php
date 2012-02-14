<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Demanda, Projetos e Serviços de TI.
 *			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 *			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 *			  ou (na sua opnião) qualquer versão.
 *			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 *			  sem uma garantia implicita de ADEQUAÇÃO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 *			  Veja a Licença Pública Geral GNU para maiores detalhes.
 *			  Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 *			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 *			  Fifth Floor, Boston, MA 02110-1301 USA.
 */

class RelatorioProjetoCustoProjeto extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	protected $whereUnidade;
	
    public function __construct()
    {
        parent::__construct();
        $this->whereUnidade = "";
    }
    
    public function custoTodosProjetos( $cd_contrato=null)
    {
        $objTable = new Proposta();

        $select = $objTable->select()
                           ->distinct()
                           ->setIntegrityCheck(false);
        $select->from(array('prop'=>KT_S_PROPOSTA),
                      array('cd_projeto'),
                      $this->_schema);
        $select->join(array('datas'=>$this->_getSubSelectCustoTodosProjetos()),
                      '(prop.cd_projeto = datas.cd_projeto)',
                      array('dt_inicio',
                            'dt_fim',
                            'ni_total_horas_parcela',
                            'valor_total'));
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(datas.cd_projeto = proj.cd_projeto)',
                      array('tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(proj.cd_unidade = uni.cd_unidade)',
                      array('tx_sigla_unidade'),
                      $this->_schema);

        if($this->whereUnidade != ""){
            $select->where($this->whereUnidade);
        }
        $select->order('tx_sigla_projeto')
               ->order('cd_projeto');

		if( !is_null($cd_contrato) ){

            $objContratoProjeto = new ContratoProjeto();
            $select->join(array('cp'=>$objContratoProjeto->select()
                                                     ->from(KT_A_CONTRATO_PROJETO, array('cd_projeto'), $this->_schema)
                                                     ->where('cd_contrato = ?',$cd_contrato, Zend_Db::INT_TYPE)),
                                '(datas.cd_projeto = cp.cd_projeto)',
                                array());
		}

        return $objTable->fetchAll($select)->toArray();
    }

    private function _getSubSelectCustoTodosProjetos()
    {
        $objProposta    = new Proposta();
        $subSelect = $objProposta->select()
                                 ->setIntegrityCheck(false)
                                 ->distinct()
                                 ->from(array('par'=>KT_S_PARCELA),
                                        array('cd_projeto',
                                              'ni_horas_parcela',
                                              'valor_parcela'=>new Zend_Db_Expr("(par.ni_horas_parcela * con.nf_valor_unitario_hora)"),
                                              'periodo'      =>new Zend_Db_Expr("ni_ano_execucao_parcela{$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} ni_mes_execucao_parcela","{$this->length("'00' {$this->concat()} ni_mes_execucao_parcela")}-1","2")}")),
                                        $this->_schema)
                                 ->join(array('pro'=>KT_S_PROPOSTA),
                                        '(par.cd_projeto = pro.cd_projeto) and (par.cd_proposta = pro.cd_proposta)',
                                        array(),
                                        $this->_schema)
                                 ->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                        '(procpar.cd_projeto = par.cd_projeto) and (procpar.cd_proposta = par.cd_proposta) and (procpar.cd_parcela = par.cd_parcela)',
                                        array(),
                                        $this->_schema)
                                 ->join(array('sol'=>KT_S_SOLICITACAO),
                                        '(procpar.ni_solicitacao_execucao = sol.ni_solicitacao) and (procpar.ni_ano_solicitacao_execucao = sol.ni_ano_solicitacao) and (procpar.cd_objeto_execucao = sol.cd_objeto)',
                                        array(),
                                        $this->_schema)
                                 ->join(array('objcon'=>KT_S_OBJETO_CONTRATO),
                                        "(sol.cd_objeto = objcon.cd_objeto) and (objcon.st_objeto_contrato = 'P')",
                                        array(),
                                        $this->_schema)
                                 ->join(array('con'=>KT_S_CONTRATO),
                                        '(objcon.cd_contrato = con.cd_contrato)',
                                        array(),
                                        $this->_schema)
                                 ->where('ni_mes_execucao_parcela is not null')
                                 ->where('ni_ano_execucao_parcela is not null')
                                 ->order('par.cd_projeto');

        $objParcela = new Parcela();
        $select = $objParcela->select()
                             ->distinct()
                             ->setIntegrityCheck(false)
                             ->from(array('dat'=>$subSelect),
                                    array('cd_projeto',
                                          'dt_inicio'               =>new Zend_Db_Expr("{$this->substring("min(periodo)","{$this->position("/", "min(periodo)")}+1","4")} {$this->concat()} '/' {$this->concat()} {$this->substring("min(periodo)","1","{$this->position("/","min(periodo)")}-1")}"),
                                          'dt_fim'                  =>new Zend_Db_Expr("{$this->substring("max(periodo)","{$this->position("/","max(periodo)")}+1","4")} {$this->concat()} '/' {$this->concat()} {$this->substring("max(periodo)","1","{$this->position("/","max(periodo)")}-1")}"),
                                          'ni_total_horas_parcela'  =>new Zend_Db_Expr("sum(dat.ni_horas_parcela)"),
                                          'valor_total'             =>new Zend_Db_Expr("sum(dat.valor_parcela)")))
                             ->group('cd_projeto')
                             ->order('cd_projeto');
        return $select;
    }

    public function custoProjetoPorProjeto($cd_projeto)
    {
        $objProposta    = new Proposta();
        $subSelect = $objProposta->select()
                                 ->setIntegrityCheck(false)
                                 ->from(array('par'=>KT_S_PARCELA),
                                        array('ni_parcela',
                                              'ni_mes_execucao_parcela',
                                              'ni_ano_execucao_parcela',
                                              'ni_horas_parcela',
                                              'valor_parcela'      =>new Zend_Db_Expr("(par.ni_horas_parcela * con.nf_valor_unitario_hora)")),
                                        $this->_schema)
                                 ->join(array('pro'=>KT_S_PROPOSTA),
                                        '(par.cd_projeto = pro.cd_projeto) and (par.cd_proposta = pro.cd_proposta)',
                                        array('cd_proposta'),
                                        $this->_schema)
                                 ->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                        '(procpar.cd_projeto = par.cd_projeto) and (procpar.cd_proposta = par.cd_proposta) and (procpar.cd_parcela = par.cd_parcela)',
                                        array(),
                                        $this->_schema)
                                 ->join(array('sol'=>KT_S_SOLICITACAO),
                                        '(procpar.ni_solicitacao_execucao = sol.ni_solicitacao) and (procpar.ni_ano_solicitacao_execucao = sol.ni_ano_solicitacao) and (procpar.cd_objeto_execucao = sol.cd_objeto)',
                                        array(),
                                        $this->_schema)
                                 ->join(array('objcon'=>KT_S_OBJETO_CONTRATO),
                                        "(sol.cd_objeto = objcon.cd_objeto) and (objcon.st_objeto_contrato = 'P')",
                                        array(),
                                        $this->_schema)
                                 ->join(array('con'=>KT_S_CONTRATO),
                                        '(objcon.cd_contrato = con.cd_contrato)',
                                        array(),
                                        $this->_schema)
                                 ->where('ni_mes_execucao_parcela is not null')
                                 ->where('ni_ano_execucao_parcela is not null')
                                 ->where('par.cd_projeto = ?',$cd_projeto, Zend_Db::INT_TYPE);

        $objParcela = new Parcela();
        $select = $objParcela->select()
                             ->distinct()
                             ->setIntegrityCheck(false)
                             ->from(array('dados'=>$subSelect),array('*'))
                             ->order('dados.cd_proposta');

        return $objParcela->fetchAll($select)->toArray();
    }
	
    public function custoProjeto($cd_contrato, $cd_projeto)
    {
        $objParcela = new Parcela();
        $select     = $objParcela->select()
                                 ->setIntegrityCheck(false)
                                 ->from(array('par'=>KT_S_PARCELA),
                                        array('ni_parcela',
                                              'ni_mes_execucao_parcela',
                                              'ni_ano_execucao_parcela',
                                              'ni_horas_parcela',
                                              'cd_proposta',
                                              'valor_parcela'      =>new Zend_Db_Expr("(par.ni_horas_parcela * con.nf_valor_unitario_hora)")),
                                        $this->_schema)
                                 ->join(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                        '(procpar.cd_projeto = par.cd_projeto) and (procpar.cd_proposta = par.cd_proposta) and (procpar.cd_parcela = par.cd_parcela)',
                                        array(),
                                        $this->_schema)
                                 ->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                                        "(oc.cd_objeto = procpar.cd_objeto_execucao)",
                                        array(),
                                        $this->_schema)
                                 ->join(array('con'=>KT_S_CONTRATO),
                                        '(oc.cd_contrato = con.cd_contrato)',
                                        array(),
                                        $this->_schema)
                                 ->where('con.cd_contrato  = ?' ,$cd_contrato, Zend_Db::INT_TYPE)
                                 ->where('par.cd_projeto   = ?' ,$cd_projeto , Zend_Db::INT_TYPE)
                                 ->where('procpar.st_ativo = ?' ,'S')
                                 ->where('ni_mes_execucao_parcela is not null')
                                 ->where('ni_ano_execucao_parcela is not null')
								 ->order(array('par.cd_proposta','par.ni_parcela'));

        return $objParcela->fetchAll($select)->toArray();
    }
    
    public function custoUnidade($unidade,$contrato)
    {
    	$this->whereUnidade = "uni.cd_unidade = {$this->getDefaultAdapter()->quote($unidade, Zend_Db::INT_TYPE)}";
    	return $this->custoTodosProjetos($contrato);
    }
    
    public function getProjeto($cd_projeto)
    {
        $objTable = new Projeto();

        $select = $objTable->select()
                           ->setIntegrityCheck(false);

        $select->from(array('proj'=>KT_S_PROJETO),
                      array('tx_projeto', 'tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(proj.cd_unidade = uni.cd_unidade)',
                      array('tx_sigla_unidade'),
                      $this->_schema);
        $select->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);

        return $objTable->fetchAll($select)->toArray();
    }
}