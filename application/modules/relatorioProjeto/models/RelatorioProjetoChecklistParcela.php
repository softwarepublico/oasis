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

class RelatorioProjetoChecklistParcela extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    public function getChecklistParcela( array $params )
    {

        $objTable = new Parcela();
        $select   = $objTable->select()->setIntegrityCheck(false);

        $select->from(array('par'=>KT_S_PARCELA),
                      array('cd_projeto',
                            'cd_proposta',
                            'cd_parcela',
                            'ni_parcela',
                            'ni_horas_parcela'),
                      $this->_schema);

        $objProcessamentoParcela = new ProcessamentoParcela();
        $subSelect               = $objProcessamentoParcela->select()->setIntegrityCheck(false)
                                                           ->from(array('procpar'=>KT_S_PROCESSAMENTO_PARCELA),
                                                                  array('cd_projeto',
                                                                        'cd_proposta',
                                                                        'cd_parcela',
                                                                        'st_autorizacao_parcela',
                                                                        'st_fechamento_parcela',
                                                                        'st_parecer_tecnico_parcela',
                                                                        'st_aceite_parcela',
                                                                        'st_homologacao_parcela',
                                                                        'st_pendente'),
                                                                  $this->_schema)
                                                           ->join(array('cp'=>KT_A_CONTRATO_PROJETO),
                                                                  '(procpar.cd_projeto = cp.cd_projeto)',
                                                                  'cd_contrato',
                                                                  $this->_schema)
                                                           ->where("procpar.st_ativo = 'S'")
                                                           ->where("cp.cd_contrato = ?", $params['cd_contrato'], Zend_Db::INT_TYPE);

        $select->joinLeft(array('parcelas_ativas'=>$subSelect),
                          '(par.cd_projeto  = parcelas_ativas.cd_projeto)  and
                           (par.cd_proposta = parcelas_ativas.cd_proposta) and
                           (par.cd_parcela  = parcelas_ativas.cd_parcela)',
                          array('st_autorizacao_parcela',
                                'st_fechamento_parcela',
                                'st_parecer_tecnico_parcela',
                                'st_aceite_parcela',
                                'st_homologacao_parcela'));

        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(par.cd_projeto  = prop.cd_projeto) and
                       (par.cd_proposta = prop.cd_proposta)',
                      array(),
                      $this->_schema);

        $select->join(array('procprop'=>KT_S_PROCESSAMENTO_PROPOSTA),
                      '(procprop.cd_projeto  = prop.cd_projeto) and
                       (procprop.cd_proposta = prop.cd_proposta)',
                      array(),
                      $this->_schema);

        $select->join(array('proj'=>KT_S_PROJETO),
                      '(par.cd_projeto = proj.cd_projeto)',
                      array('tx_sigla_projeto'),
                      $this->_schema);

        $select->where('par.st_modulo_proposta is null')
               ->where('procprop.st_ativo = ?', 'S')
               ->where('procprop.st_alocacao_proposta = ?', 'S')
               ->where('par.ni_mes_previsao_parcela   = ?', $params['mes'], Zend_Db::INT_TYPE)
               ->where('par.ni_ano_previsao_parcela   = ?', $params['ano'], Zend_Db::INT_TYPE)
               ->where('parcelas_ativas.cd_contrato   = ? OR parcelas_ativas.cd_contrato is null', $params['cd_contrato'], Zend_Db::INT_TYPE);

        $select->order(array('proj.tx_sigla_projeto',
                             'par.cd_proposta',
                             'par.ni_parcela'));

        $arrResult = $objTable->fetchAll($select)->toArray();

        foreach( $arrResult as $chave => $valor ){
            $param['cd_projeto']   = $valor['cd_projeto'];
            $param['cd_proposta']  = $valor['cd_proposta'];
            $arrResultUltimaParcela = $this->ultimaParcela( $param );
            
            if(count($arrResultUltimaParcela) > 0){
	            if( $arrResultUltimaParcela[0]['cd_ultima_parcela'] == $valor['cd_parcela'] ){
	                $arrResult[$chave]['ni_parcela'] .= ' (*)';
	            }
            }
        }
        
        return $arrResult;
    }
    
    private function ultimaParcela(array $arrParams)
    {
        $objTable = new Parcela();
        $select   = $objTable->select()->setIntegrityCheck(false);

        $select->from(KT_S_PARCELA,
                      array('cd_ultima_parcela'=>new Zend_Db_Expr('max(cd_parcela)')),
                      $this->_schema);

        $select->where('cd_projeto  = ?', $arrParams['cd_projeto' ], Zend_Db::INT_TYPE)
               ->where('cd_proposta = ?', $arrParams['cd_proposta'], Zend_Db::INT_TYPE)
               ->where('st_modulo_proposta is null');

        $subSelect = $objTable->select()
                              ->from(array('a'=>KT_S_PARCELA),
                                     array('data'=>new Zend_Db_Expr("max(a.ni_ano_previsao_parcela {$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} a.ni_mes_previsao_parcela","{$this->length("'00' {$this->concat()} a.ni_mes_previsao_parcela")}-1","2")})")),
                                     $this->_schema)
                              ->where('a.cd_projeto  = ?', $arrParams['cd_projeto' ], Zend_Db::INT_TYPE)
                              ->where('a.cd_proposta = ?', $arrParams['cd_proposta'], Zend_Db::INT_TYPE);

        $select->where(new Zend_Db_Expr("(ni_ano_previsao_parcela {$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} ni_mes_previsao_parcela","{$this->length("'00' {$this->concat()} ni_mes_previsao_parcela")}-1","2")}) = ?"), $subSelect);

		return $objTable->fetchAll($select)->toArray();
    }
}