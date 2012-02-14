<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Demanda, Projetos e Serviços de TI.
 *			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 *			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 *			  ou (na sua opnião) qualquer versão.
 *			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 *			  sem uma garantia implicita de ADEQUAÇÂO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 *			  Veja a Licença Pública Geral GNU para maiores detalhes.
 *			  Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 *			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 *			  Fifth Floor, Boston, MA 02110-1301 USA.
 */

class RelatorioProjetoCasoDeUso extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	
    /**
     * Método que executa a query do relatório
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getCasoDeUso( array $params )
    {
        $objTable = new CasoDeUso();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('cau'=>KT_S_CASO_DE_USO),
                      array('cd_caso_de_uso',
                            'tx_caso_de_uso',
                            'tx_descricao_caso_de_uso',
                            'ni_versao_caso_de_uso'),
                      $this->_schema);

        $select->join(array('pro'=>KT_S_PROJETO),
                      '(cau.cd_projeto = pro.cd_projeto)',
                      array('tx_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);

        $select->join(array('mod'=>KT_S_MODULO),
                      '(cau.cd_modulo = mod.cd_modulo)',
                      array('cd_modulo',
                            'tx_modulo'),
                      $this->_schema);

        $select->where('cau.cd_projeto = ?', $params['cd_projeto'], Zend_Db::INT_TYPE);

        /*if(array_key_exists('cd_proposta',$params)){

            $select->where('cau.cd_modulo in (?)', $objTable->select()
                                                            ->setIntegrityCheck(false)
                                                            ->from(KT_A_PROPOSTA_MODULO,
                                                                   'cd_modulo',
                                                                   $this->_schema)
                                                            ->where('cd_projeto  = ?', $params['cd_projeto' ], Zend_Db::INT_TYPE)
                                                            ->where('cd_proposta = ?', $params['cd_proposta'], Zend_Db::INT_TYPE));
    	} else {*/
            if ($params['cd_modulo'] != 'todos'){
				$select->where('cau.cd_modulo = ?', $params['cd_modulo'], Zend_Db::INT_TYPE);
			}
    	/*}*/
        $select->order('ni_ordem_caso_de_uso');

        return $objTable->fetchAll($select)->toArray();
    }

    /**
     * Método que executa a query do relatório
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getInteracao( array $params )
    {
        $objTable = new Interacao();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('inte'=>KT_S_INTERACAO),
                      array('tx_interacao',
                            'st_interacao'),
                      $this->_schema);

        $select->join(array('cau'=>KT_S_CASO_DE_USO),
                      '(inte.cd_caso_de_uso = cau.cd_caso_de_uso) and (inte.dt_versao_caso_de_uso = cau.dt_versao_caso_de_uso)',
                      array(),
                      $this->_schema);

        $select->join(array('ato'=>KT_S_ATOR),
                      '(inte.cd_ator = ato.cd_ator)',
                      array('tx_ator'),
                      $this->_schema);

        $select->where('inte.cd_projeto     = ?', $params['cd_projeto'    ], Zend_Db::INT_TYPE)
               ->where('inte.cd_modulo      = ?', $params['cd_modulo'     ], Zend_Db::INT_TYPE)
               ->where('inte.cd_caso_de_uso = ?', $params['cd_caso_de_uso'], Zend_Db::INT_TYPE);

        $select->order('ni_ordem_interacao');

        return $objTable->fetchAll($select)->toArray();
    }

    /**
     * Método que executa a query do relatório
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getComplemento( array $params )
    {
        $objTable = new Complemento();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('comp'=>KT_S_COMPLEMENTO),
                      array('tx_complemento',
                            'st_complemento',
                            'ni_ordem_complemento'),
                      $this->_schema);

        $select->join(array('cau'=>KT_S_CASO_DE_USO),
                      '(comp.cd_projeto = cau.cd_projeto) and (comp.cd_caso_de_uso = cau.cd_caso_de_uso)',
                      array(),
                      $this->_schema);

        $select->where('comp.cd_modulo      = ?', $params['cd_modulo'     ], Zend_Db::INT_TYPE)
               ->where('comp.cd_projeto     = ?', $params['cd_projeto'    ], Zend_Db::INT_TYPE)
               ->where('comp.cd_caso_de_uso = ?', $params['cd_caso_de_uso'], Zend_Db::INT_TYPE);

        //seleciona exceções
        if ($params['st_complemento']=='E'){
            $select->where("st_complemento = ?", $params['st_complemento']);
        }
        //seleciona as regras
        if ($params['st_complemento']=='R'){
            $select->where("st_complemento = ?", $params['st_complemento']);
        }
        //seleciona fluxos alternativos
        if ($params['st_complemento']=='F'){
            $select->where("st_complemento = ?", $params['st_complemento']);
        }

        $select->order('ni_ordem_complemento');

        return $objTable->fetchAll($select)->toArray();
    }
    
    public function getCasoDeUsoDependente($cd_projeto)
    {
        $objTable = new RequisitoCasoDeUso();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('rcdu'=>KT_A_REQUISITO_CASO_DE_USO),
                      array(),
                      $this->_schema);

        $select->join(array('reqasc'=>KT_S_REQUISITO),
                      '(rcdu.cd_projeto          = reqasc.cd_projeto)   and
                       (rcdu.cd_requisito        = reqasc.cd_requisito) and
                       (rcdu.dt_versao_requisito = reqasc.dt_versao_requisito)',
                      array('cd_requisito_pai'           =>'cd_requisito',
                            'tx_requisito_pai'           =>'tx_requisito',
                            'ni_ordem_pai'               =>'ni_ordem',
                            'ni_versao_requisito_pai'    =>'ni_versao_requisito',
                            'st_fechamento_requisito_pai'=>'st_fechamento_requisito'),
                      $this->_schema);

        $select->join(array('cdu'=>KT_S_CASO_DE_USO),
                      '(rcdu.cd_projeto             = cdu.cd_projeto)       and
					   (rcdu.cd_modulo              = cdu.cd_modulo)        and
					   (rcdu.cd_caso_de_uso         = cdu.cd_caso_de_uso)   and
					   (rcdu.dt_versao_caso_de_uso  = cdu.dt_versao_caso_de_uso)',
                      array('cd_caso_de_uso_filho'           =>'cd_caso_de_uso',
                            'tx_caso_de_uso_filho'           =>'tx_caso_de_uso',
//                            'ni_ordem_filho'                 =>'ni_ordem',
                            'ni_versao_caso_de_uso_filho'    =>'ni_versao_caso_de_uso',
                            'st_fechamento_caso_de_uso_filho'=>'st_fechamento_caso_de_uso'),
                      $this->_schema);

        $select->where('rcdu.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('rcdu.st_inativo is null');
               
        $select->order('cd_requisito_pai')
               ->order('cd_caso_de_uso_filho');


        return $objTable->fetchAll($select)->toArray();
    }
    
    public function getCasoDeUsoMatrizRastreabilidade( $cd_projeto )
    {
        $objTable = new CasoDeUso();
        $select   = $objTable->select()->setIntegrityCheck(false);

        $select->from(array('cdu'=>KT_S_CASO_DE_USO),
                      array('cd_caso_de_uso',
                            'tx_caso_de_uso',
                            'ni_versao_caso_de_uso',
                            'ni_ordem_caso_de_uso'),
                      $this->_schema);

        $subSelect = $objTable->select()->setIntegrityCheck(false);
        $subSelect->from(KT_S_CASO_DE_USO,
                         array('cd_caso_de_uso',
                               'dt_versao_caso_de_uso'=>new Zend_Db_Expr('max(dt_versao_caso_de_uso)')),
                         $this->_schema)
                  ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                  ->group('cd_caso_de_uso');

        $select->join(array('maxdat'=>$subSelect),
                      '(cdu.cd_caso_de_uso        = maxdat.cd_caso_de_uso) and
                       (cdu.dt_versao_caso_de_uso = maxdat.dt_versao_caso_de_uso)',
                      array());

        $select->order('cdu.cd_caso_de_uso');

        return $objTable->fetchAll($select)->toArray();
    }
}