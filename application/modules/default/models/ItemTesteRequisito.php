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

class ItemTesteRequisito extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_ITEM_TESTE_REQUISITO;
	protected $_primary  = array("cd_item_teste_requisito", "cd_requisito", "dt_versao_requisito", "cd_projeto", "cd_item_teste");
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getGridItemTesteRequisito($arr)
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
                               ->where('b.st_tipo_item_teste = ?', 'R')
                               ->where('b.st_obrigatorio IS NOT NULL')
                               ->where('a.cd_requisito        = ?', $value['cd_requisito'], Zend_Db::INT_TYPE)
                               ->where('a.cd_projeto          = ?', $value['cd_projeto'], Zend_Db::INT_TYPE)
                               ->where('a.dt_versao_requisito = ?', $value['dt_versao_requisito']);

                $res = $this->fetchAll($select);
                
                foreach($res as $v){
                    $st_analise = $v->st_analise;
                    if( is_null($v->st_analise) ){
                        break;
                    }
                }
                foreach($res as $v){
                    $st_solucao = $v->st_solucao;
                    if( is_null($v->st_solucao) ){
                        break;
                    }
                }
                foreach($res as $v){
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

    public function getGridItemTesteRequisitoAll($analise_solucao_homologacao,$cd_requisito,$cd_projeto)
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('it'=>KT_B_ITEM_TESTE),
                      array('st_tipo_item_teste',
                            'ni_ordem_item_teste',
                            'cd_item_teste',
                            'tx_item_teste',
                            'st_obrigatorio'),
                      $this->_schema);
        $select->joinLeft(array('itr'=>$this->_name),
                         "(it.cd_item_teste = itr.cd_item_teste) AND
                          (itr.cd_requisito = {$cd_requisito}) AND
                          (itr.cd_projeto   = {$cd_projeto}) AND
                          (itr.cd_item_teste_requisito = ({$this->select()
                                                                ->setIntegrityCheck(false)
                                                                ->from(array('aux'=>$this->_name),
                                                                       new Zend_Db_Expr('MAX(aux.cd_item_teste_requisito)'),
                                                                       $this->_schema)
                                                                ->where('aux.cd_item_teste = itr.cd_item_teste')
                                                                ->where('aux.cd_requisito = ?', $cd_requisito, Zend_Db::INT_TYPE)
                                                                ->where('aux.cd_projeto   = ?', $cd_projeto, Zend_Db::INT_TYPE)}))",
                         array('dt_versao_requisito',
                               'cd_item_teste_requisito',
                               'st_item_teste_requisito',
                               'st_analise',
                               'tx_analise',
                               'st_solucao',
                               'tx_solucao',
                               'st_homologacao',
                               'tx_homologacao',
                               'cd_requisito',
                               'dt_homologacao'),
                         $this->_schema);

        $select->joinLeft(array('itrd'=>KT_A_ITEM_TESTE_REQUISITO_DOC),
                          "(itr.cd_item_teste           = itrd.cd_item_teste) AND
                           (itr.cd_item_teste_requisito = itrd.cd_item_teste_requisito) AND
                           (itr.cd_requisito            = {$cd_requisito}) AND
                           (itr.cd_projeto              = {$cd_projeto})",
                          array('qtd_arquivo'=>new Zend_Db_Expr('COUNT(itrd.*)')),
                          $this->_schema);

        $select->where('it.st_item_teste      = ?', 'A');
        $select->where('it.st_tipo_item_teste = ?', 'R');
        $select->where("itr.st_{$analise_solucao_homologacao} IS NULL");
        $select->group(array('it.st_tipo_item_teste',
                             'itr.dt_versao_requisito',
                             'itr.cd_item_teste_requisito',
                             'itr.st_item_teste_requisito',
                             'itr.st_analise',
                             'itr.tx_analise',
                             'itr.st_solucao',
                             'itr.tx_solucao',
                             'itr.st_homologacao',
                             'itr.tx_homologacao',
                             'itr.dt_homologacao',
                             'itr.cd_requisito',
                             'it.ni_ordem_item_teste',
                             'it.cd_item_teste',
                             'it.tx_item_teste',
                             'it.st_obrigatorio'));
        $select->order('it.ni_ordem_item_teste');

        if($analise_solucao_homologacao == 'analise'){
            $select->orWhere('itr.st_solucao IS NOT NULL');
            $select->where("itr.st_item_teste_requisito <> 'H' OR itr.st_item_teste_requisito IS NULL");
        } else if($analise_solucao_homologacao == 'homologacao'){
            $select->orWhere('itr.st_analise IS NOT NULL');
            $select->where('itr.st_solucao IS NOT NULL');
            $select->where('itr.st_item_teste_requisito = ?', 'H');
        }

        $res = $this->fetchAll($select)->toArray();

        if( is_null($res) ){
            return false;
        } else {

            /* ESTE FOREACH FOI ADICIONADO PARA ATENDER AO SELECT
             * ORIGINAL QUE COLOCA O cd_requisito E cd_projeto
             * EM CADA REGISTRA QUE A QUERY RETORNA*/
            foreach($res as $key=>$value){
                $res[$key]['cd_requisito_default'] = $cd_requisito;
                $res[$key]['cd_projeto_default']   = $cd_projeto;
            }
            return $res;
        }

       /*SELECT ORIGINAL ANTES DA MUDANÇA PARA ZEND_SELECT*/
/*
        $sql  = "select
                    it.st_tipo_item_teste,
                    it.ni_ordem_item_teste,
                    it.cd_item_teste,
                    it.tx_item_teste,
                    it.st_obrigatorio,
                    itr.dt_versao_requisito,
                    itr.cd_item_teste_requisito,
                    itr.st_item_teste_requisito,
                    itr.st_analise,
                    itr.tx_analise,
                    itr.st_solucao,
                    itr.tx_solucao,
                    itr.st_homologacao,
                    itr.tx_homologacao,
                    itr.cd_requisito,
                    to_char(itr.dt_homologacao,'dd/mm/yyyy') as dt_homologacao,
                    count(itrd.*) as qtd_arquivo,
                    '{$cd_requisito}' as cd_requisito_default,
                    '{$cd_projeto}' as cd_projeto_default
                 from
                    {$this->_schema}.".KT_B_ITEM_TESTE." it
                 left join
                    {$this->_schema}.{$this->_name} itr
                 on  (
                        it.cd_item_teste = itr.cd_item_teste
                    and
                        itr.cd_requisito = {$cd_requisito}
                    and
                        itr.cd_projeto = {$cd_projeto}
                    and
                        itr.cd_item_teste_requisito = (
                            select
                                max(aux.cd_item_teste_requisito)
                            from
                                {$this->_schema}.{$this->_name} aux
                            where
                                aux.cd_item_teste = itr.cd_item_teste
                            and
                                aux.cd_requisito = {$cd_requisito}
                            and
                                aux.cd_projeto = {$cd_projeto}
                        )
                    )
                 left join
                    {$this->_schema}.".KT_A_ITEM_TESTE_REQUISITO_DOC." itrd
                 on (
                        itr.cd_item_teste = itrd.cd_item_teste
                    and
                        itr.cd_item_teste_requisito = itrd.cd_item_teste_requisito
                    and
                        itr.cd_requisito = {$cd_requisito}
                    and
                        itr.cd_projeto = {$cd_projeto}
                    )
                 where
                    it.st_item_teste = 'A'
                 and
                    it.st_tipo_item_teste = 'R'
                 and (
                    itr.st_{$analise_solucao_homologacao} is null ";

       if($analise_solucao_homologacao == 'analise'){
            $sql .= "or
                    itr.st_solucao is not null
                and (
                        itr.st_item_teste_requisito <> 'H'
                    or
                        itr.st_item_teste_requisito is null
                    ) ";
       } else if($analise_solucao_homologacao == 'homologacao'){
            $sql .= "or
                        itr.st_analise is not null
                    and
                        itr.st_solucao is not null
                    and
                        itr.st_item_teste_requisito = 'H' ";
       }
        $sql .= ")
                 group by
                    it.st_tipo_item_teste,
                    itr.dt_versao_requisito,
                    itr.cd_item_teste_requisito,
                    itr.st_item_teste_requisito,
                    itr.st_analise,
                    itr.tx_analise,
                    itr.st_solucao,
                    itr.tx_solucao,
                    itr.st_homologacao,
                    itr.tx_homologacao,
                    itr.dt_homologacao,
                    itr.cd_requisito,
                    it.ni_ordem_item_teste,
                    it.cd_item_teste,
                    it.tx_item_teste,
                    it.st_obrigatorio
                 order by
                    it.ni_ordem_item_teste";
*/
    }
}