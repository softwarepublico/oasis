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

class RelatorioDemandaChamadoTecnico extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
    
    public function getDadosChamadoTecnico( array $params )
    {

        $select=$this->getDefaultAdapter()->select();
        
        $select->from(array('sol'=>KT_S_SOLICITACAO),
                      array(                     
                            'ni_solicitacao',
                            'ni_ano_solicitacao',
                            'cd_objeto',
                            'cd_profissional',
                            'cd_unidade',
                            'tx_solicitacao',
                            'st_solicitacao',
                            'tx_justificativa_solicitacao',
                            'dt_justificativa',
                            'st_aceite',
                            'dt_aceite',
                            'tx_obs_aceite',
                            'st_fechamento',
                            'dt_fechamento',
                            'st_homologacao',
                            'dt_homologacao',
                            'tx_obs_homologacao',
                            'ni_dias_execucao',
                            'tx_problema_encontrado',
                            'tx_solucao_solicitacao',
                            'st_grau_satisfacao',
                            'tx_obs_grau_satisfacao',
                            'dt_grau_satisfacao',
                            'dt_leitura_solicitacao',
                            'dt_solicitacao',
                            'tx_solicitante',
                            'tx_sala_solicitante',
                            'tx_telefone_solicitante',
                            'tx_obs_solicitacao',
                            'tx_execucao_solicitacao',
                            'ni_prazo_atendimento',
                            'id',
                            'st_aceite_just_solicitacao',
                            'tx_obs_aceite_just_solicitacao',
                            'cd_inventario',
                            'cd_item_inventariado',
                            'cd_item_inventario'),
                      $this->_schema);

        $select->join(array('dem'=>KT_S_DEMANDA),
                      '(dem.ni_ano_solicitacao  = sol.ni_ano_solicitacao and 
                        dem.ni_solicitacao = sol.ni_solicitacao) and
                        dem.cd_objeto = '.$params['cd_objeto'],
                      array(
                            'cd_demanda',
                            'cd_objeto',
                            'ni_ano_solicitacao',
                            'ni_solicitacao',
                            'dt_demanda',
                            'tx_demanda',
                            'st_conclusao_demanda',
                            'dt_conclusao_demanda',
                            'tx_solicitante_demanda',
                            'cd_unidade',
                            'st_fechamento_demanda',
                            'dt_fechamento_demanda',
                            'st_prioridade_demanda',
                            'id',
                            'cd_status_atendimento'),
                      $this->_schema);
        
        
        $select->where('sol.st_solicitacao      = ?', '6')
               ->where('sol.cd_objeto           = ?', $params['cd_objeto'], Zend_Db::INT_TYPE)
               ->where('sol.ni_solicitacao      = ?', $params['ni_solicitacao'], Zend_Db::INT_TYPE)
               ->where('sol.ni_ano_solicitacao  = ?', $params['ni_ano_solicitacao'], Zend_Db::INT_TYPE);

        
//        ﻿select sol.*, dem.* from oasis.s_solicitacao as sol
//join  oasis.s_demanda as dem on dem.ni_ano_solicitacao = sol.ni_ano_solicitacao
//                                and dem.ni_solicitacao = sol.ni_solicitacao 
//where sol.ni_solicitacao = 2 
//  and sol.ni_ano_solicitacao = 2011 
//  and sol.cd_objeto = 2 
//  and sol.st_solicitacao = '6'
//                                

        $dados = $this->getDefaultAdapter()->fetchAll($select);

        if (count($dados)>0 ) {


            $select1=$this->getDefaultAdapter()->select();

            $select1->from(array('dpro'=>KT_A_DEMANDA_PROFISSIONAL),
                          array('cd_profissional'),
                          $this->_schema);
            $select1->join(array('prof'=>KT_S_PROFISSIONAL),
                          '(dpro.cd_profissional = prof.cd_profissional)',
                          array('tx_profissional'),
                          $this->_schema);
            $select1->where('dpro.cd_demanda         = ?', $dados[0]['cd_demanda'], Zend_Db::INT_TYPE);


    //select prof.tx_profissional from oasis.a_demanda_profissional as dpro
    //join oasis.s_profissional as prof on prof.cd_profissional= dpro.cd_profissional
    //where cd_demanda = 11389        

            $dados1 = $this->getDefaultAdapter()->fetchAll($select1);

            $profissionais = '';
            foreach ($dados1 as $key => $value) {
                $profissionais .= '['.$value['tx_profissional'].']';
            }
            $dados[0]['tx_profissionais'] = $profissionais;

            
            
            $select2=$this->getDefaultAdapter()->select();

            $select2->from(array('iiv'=>KT_A_ITEM_INVENTARIADO),
                          array('tx_item_inventariado'),
                          $this->_schema);
            $select2->where('iiv.cd_item_inventariado         = ?', $dados[0]['cd_item_inventariado'], Zend_Db::INT_TYPE);

            $dados2 = $this->getDefaultAdapter()->fetchAll($select2);

            if (count($dados2)> 0) {
                $dados[0]['tx_item_inventariado'] = $dados2[0]['tx_item_inventariado'];
            }else{
                $dados[0]['tx_item_inventariado'] = '';
            }
        }
        return $dados;
    }

    public function getDadosItemInventariadoChamadoTecnico2( array $params )
    {
        $select=$this->getDefaultAdapter()->select();

        $select->from(array('iiv'=>KT_A_ITEM_INVENTARIADO),
                      array('tx_item_inventariado'),
                      $this->_schema);
        $select->join(array('finv'=>KT_A_FORM_INVENTARIO),
                      '(iiv.cd_item_inventariado = finv.cd_item_inventariado)',
                      array('tx_valor_subitem_inventario'),
                      $this->_schema);
        $select->join(array('sid'=>KT_B_SUBITEM_INV_DESCRI),
                      '(sid.cd_subitem_inv_descri = finv.cd_subitem_inv_descri)',
                      array('tx_subitem_inv_descri'),
                      $this->_schema);
        $select->join(array('siv'=>KT_B_SUBITEM_INVENTARIO),
                      '(siv.cd_subitem_inventario = finv.cd_subitem_inventario)',
                      array('tx_subitem_inventario'),
                      $this->_schema);
        $select->where('finv.cd_item_inventariado = ?', $params['cd_item_inventariado'], Zend_Db::INT_TYPE)
               ->where('finv.cd_item_inventario   = ?', $params['cd_item_inventario'], Zend_Db::INT_TYPE)
               ->where('siv.st_info_chamado_tecnico =  ?', 'S');
        
       $dados = $this->getDefaultAdapter()->fetchAll($select); 

       if (count($dados)>0) {
           foreach($dados as $key => $x){
               $ddd[$x['tx_subitem_inventario']][$x['tx_subitem_inv_descri']] = $x['tx_valor_subitem_inventario'];
            }
           return $ddd;
       }else{
           return $dados;
       }
       
    }
    
    public function getDadosItemInventariadoChamadoTecnico( array $params )
    {
        $select=$this->getDefaultAdapter()->select();
        
        $select->from(array('form'=>KT_A_FORM_INVENTARIO),
                      array(                     
                          'cd_inventario',
                          'cd_item_inventario',
                          'cd_item_inventariado',
                          'cd_subitem_inventario',
                          'cd_subitem_inv_descri',
                          'tx_valor_subitem_inventario',
                          'id'),
                      $this->_schema);

        $select->join(array('sub'=>KT_B_SUBITEM_INVENTARIO),
                      '(sub.cd_item_inventario = form.cd_item_inventario and
                        sub.cd_subitem_inventario = form.cd_subitem_inventario)',
                      array(
                            'cd_item_inventario',
                            'cd_subitem_inventario',
                            'tx_subitem_inventario',
                            'id'),
                      $this->_schema);
        $select->join(array('descr'=>KT_B_SUBITEM_INV_DESCRI),
                      '(descr.cd_item_inventario = form.cd_item_inventario  and
                        descr.cd_subitem_inventario = form.cd_subitem_inventario and
                        descr.cd_subitem_inv_descri = form.cd_subitem_inv_descri)',
                      array(
                            'cd_item_inventario',
                            'cd_subitem_inventario',
                            'cd_subitem_inv_descri',
                            'tx_subitem_inv_descri',
                            'id',
                            'ni_ordem'),
                      $this->_schema);

        $select->where('form.cd_item_inventariado = ?', $params['cd_item_inventariado'], Zend_Db::INT_TYPE)
               ->where('form.cd_item_inventario   = ?', $params['cd_item_inventario'], Zend_Db::INT_TYPE)
               ->where('form.cd_inventario        = ?', $params['cd_inventario'], Zend_Db::INT_TYPE);
      
       
//        ﻿select form.*, sub.*, descr.* from oasis.a_form_inventario as form
//join oasis.b_subitem_inventario as sub on sub.cd_item_inventario = form.cd_item_inventario 
//                                       and sub.cd_subitem_inventario = form.cd_subitem_inventario   
//join oasis.b_subitem_inv_descri as descr on descr.cd_item_inventario = form.cd_item_inventario 
//                                       and descr.cd_subitem_inventario = form.cd_subitem_inventario
//                                       and descr.cd_subitem_inv_descri = form.cd_subitem_inv_descri   
//where form.cd_item_inventariado = 2 
//  and form.cd_item_inventario = 2
//  and form.cd_inventario = 1
        
        return $this->getDefaultAdapter()->fetchAll($select);
        
    }
    
}