<?php
/**
 * @Copyright Copyright 2011 Hudson Carrano Filho, Brasil.
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
include_once 'Abstract.php';

class CustoContratoDemandaPorMetrica extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_CUSTO_CONTRATO_DEMANDA_POR_METRICA  ;
    protected $_primary  = array('cd_custo_demanda_por_metrica','cd_contrato','ni_mes','ni_ano');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
    
    public function getCustoMetrica($formData)
    {
          $select = $this->select()
                       ->setIntegrityCheck(false);
          
          $select->from(array('cc'=>KT_S_CUSTO_CONTRATO_DEMANDA_POR_METRICA),
                              array('nf_qtd_unidade_metrica'=>new Zend_Db_Expr('SUM(nf_qtd_unidade_metrica)')),
                              $this->_schema);
          
          $select->join(array('pr'=>KT_S_PROJETO),
                      '(cc.cd_projeto = pr.cd_projeto)',
                      array('tx_sigla_projeto'),
                      $this->_schema);
           $select->where('cd_contrato = ? ', $formData['cd_contrato_custo_metrica'], Zend_Db::INT_TYPE)
                ->where('ni_mes = ? ', $formData['ni_mes_custo_metrica'] , Zend_Db::INT_TYPE)        
                ->where('ni_ano = ? ', $formData['ni_ano_custo_metrica'], Zend_Db::INT_TYPE);        

           $select->group('nf_qtd_unidade_metrica')
                  ->group('tx_sigla_projeto');
           
                
//         $select = 'SELECT  pr.tx_sigla_projeto, sum(cc.nf_qtd_unidade_metrica) 
//                FROM oasis.s_custo_demanda_por_metrica as cc
//                join oasis.s_projeto as pr on ( pr.cd_projeto = cc.cd_projeto)
//                WHERE (cc.cd_contrato = '.$cd_contrato.') AND (cc.ni_mes ='.$ni_mes.') AND (cc.ni_ano ='.$ni_ano.') 
//                group by pr.tx_sigla_projeto, cc.nf_qtd_unidade_metrica';

         return $this->fetchAll($select)->toArray();

    }
}