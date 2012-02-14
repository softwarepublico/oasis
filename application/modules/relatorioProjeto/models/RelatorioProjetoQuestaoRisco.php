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

class RelatorioProjetoQuestaoRisco extends Base_Db_Table_Abstract
{
    protected $_schema = K_SCHEMA;

    public function questaoRisco(array $arrParam)
    {
        $objTable = new ObjetoContrato();

        $select = $objTable->select()
                           ->setIntegrityCheck(false);
        $select->from(array('con'=>KT_S_CONTRATO),
                     array('tx_numero_contrato'),
                     $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(con.cd_contrato = oc.cd_contrato)',
                      array('cd_objeto',
                            'tx_objeto'),
                      $this->_schema);
        $select->join(array('oca'=>KT_A_OBJETO_CONTRATO_ATIVIDADE),
                      '(oc.cd_objeto = oca.cd_objeto)',
                      array(),
                      $this->_schema);
        $select->join(array('eta'=>KT_B_ETAPA),
                      '(oca.cd_etapa = eta.cd_etapa)',
                      array('cd_etapa',
                            'tx_etapa',
                            'ni_ordem_etapa',
                            'tx_descricao_etapa'),
                      $this->_schema);
        $select->joinLeft(array('ati'=>KT_B_ATIVIDADE),
                      '(eta.cd_etapa     = ati.cd_etapa) AND
                       (ati.cd_atividade = oca.cd_atividade) AND
                       (ati.cd_etapa     = oca.cd_etapa)',
                      array('cd_atividade',
                            'tx_atividade',
                            'ni_ordem_atividade',
                            'tx_descricao_atividade'),
                      $this->_schema);
        $select->joinLeft(array('ir'=>KT_B_ITEM_RISCO),
                          '(ati.cd_atividade = ir.cd_atividade)',
                          array('cd_item_risco',
                                'tx_descricao_item_risco',
                                'tx_item_risco'),
                          $this->_schema);
        $select->joinLeft(array('qar'=>KT_B_QUESTAO_ANALISE_RISCO),
                          '(ir.cd_item_risco = qar.cd_item_risco)',
                          array('cd_questao_analise_risco',
                                'tx_questao_analise_risco',
                                'tx_obj_questao_analise_risco',
                                'ni_peso_questao_analise_risco'),
                         $this->_schema);
       $select->order(array("oc.cd_objeto",
                            "eta.tx_etapa",
                            "ati.tx_atividade",
                            "ir.tx_item_risco",
                            "qar.tx_questao_analise_risco"));

      if(count($arrParam) > 0){
          if(!empty($arrParam['cd_objeto'])){
            $select->where('oca.cd_objeto = ?',$arrParam['cd_objeto'],Zend_Db::INT_TYPE);
          }
          if(!empty($arrParam['cd_etapa'])){
            $select->where('eta.cd_etapa = ?',$arrParam['cd_etapa'],Zend_Db::INT_TYPE);
          }
          if(!empty($arrParam['cd_atividade'])){
            $select->where('ati.cd_atividade = ?',$arrParam['cd_atividade'],Zend_Db::INT_TYPE);
          }
      }
        return $this->fetchAll( $select )->toArray();
    }
}