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

class HistoricoPedido extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_HISTORICO_PEDIDO;
	protected $_primary  = 'cd_historico_pedido';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
    //protected $_sequence = 'solicitacao.historico_pedido_sq';

    /**
     * Método para retornar as finormações das solicitações
     *
     * @param Array $arrWhere     Opcional Exemplo: array('cd_solicitacao_pedido = ?'=> 5,
     *                                                    'cd_solicitacao_pedido IN (?)'=>array('D','P'))
     * @param String|Array $order Opcional Exemplo: 'cd_solicitacao_pedido DESC' ou array('cd_solicitacao_pedido DESC', 'st_situacao_pedido')
     * @return Zend_Db_Table_Rowset
     */
    public function getHistoricoPedido(Array $arrWhere=array(), $order='')
    {
        $select = $this->select();
        $select->from($this->_name,
                      array('cd_historico_pedido',
                            'cd_solicitacao_historico',
                            'dt_registro_historico',
                            'st_acao_historico',
                            'tx_descricao_historico',
                            'st_acao_historico_desc'=>new Zend_Db_Expr("case st_acao_historico
                                                                          when 'D' then '".Base_Util::getTranslator('L_SQL_ANALIZADO_COMITE'    )."'
                                                                          when 'P' then '".Base_Util::getTranslator('L_SQL_PREENCHIDO'          )."'
                                                                          when 'E' then '".Base_Util::getTranslator('L_SQL_ENCAMINHADO'         )."'
                                                                          when 'A' then '".Base_Util::getTranslator('L_SQL_AUTORIZADO'          )."'
                                                                          when 'M' then '".Base_Util::getTranslator('L_SQL_MODIFICAR'           )."'
                                                                          when 'R' then '".Base_Util::getTranslator('L_SQL_RECUSADO'            )."'
                                                                          when 'J' then '".Base_Util::getTranslator('L_SQL_REJEITADO'           )."'
                                                                          when 'C' then '".Base_Util::getTranslator('L_SQL_COMPLETAR'           )."'
                                                                          when 'V' then '".Base_Util::getTranslator('L_SQL_VALIDADO'            )."'
                                                                          when 'T' then '".Base_Util::getTranslator('L_SQL_COMITE'              )."'
                                                                          when 'S' then '".Base_Util::getTranslator('L_SQL_SOLICITACAO_SERVICO' )."'
                                                                          when 'I' then '".Base_Util::getTranslator('L_SQL_SOLICITACAO_SERVICO_CRIADA' )."'
                                                                          when 'X' then '".Base_Util::getTranslator('L_SQL_RESERVADO'           )."' end")),
                      $this->_schema);
        if(count($arrWhere) > 0 ){
            foreach($arrWhere as $campo=>$valor){
                $select->where($campo,$valor);
            }
        }
        if(!empty($order)){
            $select->order($order);
        }else{
            $select->order('dt_registro_historico DESC');
        }
        return $this->fetchAll($select);
    }

    public function registraHistoricoPedido(array $arrDados=array())
    {
        if(count($arrDados)==0){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_REGISTRAR_HISTORICO_PEDIDO'));
        }
        if($arrDados['cd_solicitacao_pedido'] == ''){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_SEM_CHAVE_DO_PEDIDO_REGISTRAR_HISTORICO'));
        }

        $row = $this->createRow();

        $row->cd_historico_pedido      = $this->getNextValueOfField('cd_historico_pedido');
        $row->cd_solicitacao_historico = $arrDados['cd_solicitacao_pedido' ];
        $row->tx_descricao_historico   = $arrDados['tx_descricao_historico'];
        $row->dt_registro_historico    = $arrDados['dt_registro_historico' ];
        $row->st_acao_historico        = $arrDados['status'                ];
        
        if(!$row->save()){
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_GERAR_HISTORICO_ANDAMENTO_PEDIDO'));
        }
    }

}