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

class ItemTesteCasoDeUso extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_ITEM_TESTE_CASO_DE_USO;
	protected $_primary  = array("cd_item_teste", "cd_modulo", "cd_projeto", "cd_caso_de_uso", "dt_versao_caso_de_uso", "cd_item_teste_caso_de_uso");
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getGridItemTesteCasoDeUso($arr)
    {
        foreach( $arr as $key=>$value ){
                $st_analise     = null;
                $st_solucao     = null;
                $st_homologacao = null;

                $select = $this->select()
                               ->setIntegrityCheck(false)
                               ->from(array('b'=>KT_B_ITEM_TESTE),
                                      array(),
                                      $this->_schema)

                               ->joinLeft(array('a'=>$this->_name),
                                          '(b.cd_item_teste = a.cd_item_teste)',
                                          array('st_analise','st_homologacao','st_solucao'),
                                          $this->_schema)
                               ->where('b.st_item_teste      = ?', 'A')
                               ->where('b.st_tipo_item_teste = ?', 'C')
                               ->where('b.st_obrigatorio IS NOT NULL')
                               ->where('a.cd_caso_de_uso        = ?', $value['cd_caso_de_uso'], Zend_Db::INT_TYPE)
                               ->where('a.cd_projeto            = ?', $value['cd_projeto'], Zend_Db::INT_TYPE)
                               ->where('a.dt_versao_caso_de_uso = ?', $value['dt_versao_caso_de_uso']);

                $rowSet = $this->fetchAll($select);
                foreach($rowSet as $v){
                    $st_analise = $v->st_analise;
                    if( is_null($v->st_analise) ){
                        break;
                    }
                }
                foreach($rowSet as $v){
                    $st_solucao = $v->st_solucao;
                    if( is_null($v->st_solucao) ){
                        break;
                    }
                }
                foreach($rowSet as $v){
                    $st_homologacao = $v->st_homologacao;
                    if( is_null($v->st_homologacao) ){
                        break;
                    }
                }
            $arr[$key]['st_analise']     = $st_analise;
            $arr[$key]['st_solucao']     = $st_solucao;
            $arr[$key]['st_homologacao'] = $st_homologacao;

        }
        return $arr;
    }

    public function getGridItemTesteCasoDeUsoAll($analise_solucao_homologacao,$cd_caso_de_uso,$cd_projeto,$cd_modulo)
    {

        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('it'=>KT_B_ITEM_TESTE),
                      array('st_tipo_item_teste',
                            'ni_ordem_item_teste',
                            'cd_item_teste',
                            'tx_item_teste',
                            'st_obrigatorio'),
                      $this->_schema);
        $select->joinLeft(array('itcdu'=>$this->_name),
                          "(it.cd_item_teste     = itcdu.cd_item_teste) AND
                           (itcdu.cd_caso_de_uso = {$cd_caso_de_uso}) AND
                           (itcdu.cd_projeto     = {$cd_projeto}) AND
                           (itcdu.cd_modulo      = {$cd_modulo}) AND
                           (itcdu.cd_item_teste_caso_de_uso = ({$this->select()
                                                                     ->setIntegrityCheck(false)
                                                                     ->from(array('aux'=>$this->_name),new Zend_Db_Expr('MAX(aux.cd_item_teste_caso_de_uso)'), $this->_schema)
                                                                     ->where('aux.cd_item_teste  = itcdu.cd_item_teste')
                                                                     ->where("aux.cd_caso_de_uso = {$cd_caso_de_uso}")
                                                                     ->where("aux.cd_projeto     = {$cd_projeto}")}))",
                          array('dt_versao_caso_de_uso',
                                'cd_item_teste_caso_de_uso',
                                'st_item_teste_caso_de_uso',
                                'st_analise',
                                'tx_analise',
                                'st_solucao',
                                'tx_solucao',
                                'st_homologacao',
                                'tx_homologacao',
                                'dt_homologacao',
                                'cd_caso_de_uso'),
                          $this->_schema);
        $select->joinLeft(array('itcdud'=>KT_A_ITEM_TESTE_CASO_DE_USO_DOC),
                          "(itcdu.cd_item_teste             = itcdud.cd_item_teste) AND
                           (itcdu.cd_item_teste_caso_de_uso = itcdud.cd_item_teste_caso_de_uso) AND
                           (itcdu.cd_caso_de_uso            = {$cd_caso_de_uso}) AND
                           (itcdu.cd_projeto                = {$cd_projeto}) AND
                           (itcdu.cd_modulo                 = {$cd_modulo})",
                          array('qtd_arquivo'=>new Zend_Db_Expr('COUNT(itcdud.*)')),
                          $this->_schema);
        $select->where('it.st_item_teste      = ?', 'A')
               ->where('it.st_tipo_item_teste = ?', 'C')
               ->where("itcdu.st_{$analise_solucao_homologacao} IS NULL");
        $select->group(array('it.st_tipo_item_teste',
                             'itcdu.dt_versao_caso_de_uso',
                             'itcdu.cd_item_teste_caso_de_uso',
                             'itcdu.st_item_teste_caso_de_uso',
                             'itcdu.st_analise',
                             'itcdu.tx_analise',
                             'itcdu.st_solucao',
                             'itcdu.tx_solucao',
                             'itcdu.st_homologacao',
                             'itcdu.tx_homologacao',
                             'itcdu.dt_homologacao',
                             'itcdu.cd_caso_de_uso',
                             'it.ni_ordem_item_teste',
                             'it.cd_item_teste',
                             'it.tx_item_teste',
                             'it.st_obrigatorio'));
        $select->order('it.ni_ordem_item_teste');

        if($analise_solucao_homologacao == 'analise'){
            $select->orWhere('itcdu.st_solucao IS NOT NULL')
                   ->where("itcdu.st_item_teste_caso_de_uso <> 'H' OR itcdu.st_item_teste_caso_de_uso IS NULL");
       } else if($analise_solucao_homologacao == 'homologacao'){
            $select->orWhere('itcdu.st_analise IS NOT NULL')
                   ->where('itcdu.st_solucao IS NOT NULL')
                   ->where('itcdu.st_item_teste_caso_de_uso = ?', 'H');
       }
        
        $res = $this->fetchAll($select)->toArray();

        if( is_null($res) ){
            return false;
        } else {
            /* ESTE FOREACH FOI ADICIONADO PARA ATENDER AO SELECT
            * ORIGINAL QUE COLOCA OS PARAMETROS
            * EM CADA REGISTRA QUE A QUERY RETORNA*/
            foreach($res as $key=>$value){
                $res[$key]['cd_caso_de_uso_default'] = $cd_caso_de_uso;
                $res[$key]['cd_projeto_default']   = $cd_projeto;
                $res[$key]['cd_modulo_default']   = $cd_modulo;
            }
            return $res;
        }
    }
}
