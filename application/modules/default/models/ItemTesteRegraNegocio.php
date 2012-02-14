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

class ItemTesteRegraNegocio extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_ITEM_TESTE_REGRA_NEGOCIO;
	protected $_primary  = array("dt_regra_negocio", "cd_regra_negocio", "cd_item_teste", "cd_projeto_regra_negocio", "cd_item_teste_regra_negocio");
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    public function getGridItemTesteRegraNegocio($arr)
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
                               ->where('b.st_tipo_item_teste = ?', 'N')
                               ->where('b.st_obrigatorio IS NOT NULL')
                               ->where('a.cd_regra_negocio         = ?', $value['cd_regra_negocio'], Zend_Db::INT_TYPE)
                               ->where('a.cd_projeto_regra_negocio = ?', $value['cd_projeto_regra_negocio'], Zend_Db::INT_TYPE)
                               ->where('a.dt_regra_negocio         = ?', $value['dt_regra_negocio']);

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

    public function getGridItemTesteRegraNegocioAll($analise_solucao_homologacao,$cd_regra_negocio,$cd_projeto)
    {
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('it'=>KT_B_ITEM_TESTE),
                      array('st_tipo_item_teste',
                            'ni_ordem_item_teste',
                            'cd_item_teste',
                            'tx_item_teste',
                            'st_obrigatorio'),
                      $this->_schema);
        $select->joinLeft(array('itrn'=>$this->_name),
                          "(it.cd_item_teste              = itrn.cd_item_teste) AND
                           (itrn.cd_regra_negocio         = {$cd_regra_negocio}) AND
                           (itrn.cd_projeto_regra_negocio = {$cd_projeto}) AND
                           (itrn.cd_item_teste_regra_negocio = ( {$this->select()
                                                                       ->setIntegrityCheck(false)
                                                                       ->from(array('aux'=>$this->_name),
                                                                              new Zend_Db_Expr('MAX(aux.cd_item_teste_regra_negocio)'),
                                                                              $this->_schema)
                                                                       ->where('aux.cd_item_teste = itrn.cd_item_teste')
                                                                       ->where('aux.cd_regra_negocio = ?', $cd_regra_negocio, Zend_Db::INT_TYPE)
                                                                       ->where('aux.cd_projeto_regra_negocio = ?', $cd_projeto, Zend_Db::INT_TYPE)}))",
                          array('dt_regra_negocio',
                                'cd_item_teste_regra_negocio',
                                'st_item_teste_regra_negocio',
                                'st_analise',
                                'tx_analise',
                                'st_solucao',
                                'tx_solucao',
                                'st_homologacao',
                                'tx_homologacao',
                                'dt_homologacao',
                                'cd_regra_negocio'),
                          $this->_schema);
        $select->joinLeft(array('itrnd'=>KT_A_ITEM_TESTE_REGRA_NEGOCIO_DOC),
                          "(itrn.cd_item_teste               = itrnd.cd_item_teste) AND
                           (itrn.cd_item_teste_regra_negocio = itrnd.cd_item_teste_regra_negocio) AND
                           (itrn.cd_regra_negocio            = {$cd_regra_negocio}) AND
                           (itrn.cd_projeto_regra_negocio    = {$cd_projeto})",
                          array('qtd_arquivo'=>new Zend_Db_Expr('COUNT(itrnd.*)')),
                          $this->_schema);
        $select->where('it.st_item_teste      = ?', 'A')
               ->where('it.st_tipo_item_teste = ?', 'N')
               ->where("itrn.st_{$analise_solucao_homologacao} IS NULL");
        $select->group(array('it.st_tipo_item_teste',
                            'itrn.dt_regra_negocio',
                            'itrn.cd_item_teste_regra_negocio',
                            'itrn.st_item_teste_regra_negocio',
                            'itrn.st_analise',
                            'itrn.tx_analise',
                            'itrn.st_solucao',
                            'itrn.tx_solucao',
                            'itrn.st_homologacao',
                            'itrn.tx_homologacao',
                            'itrn.dt_homologacao',
                            'itrn.cd_regra_negocio',
                            'it.ni_ordem_item_teste',
                            'it.cd_item_teste',
                            'it.tx_item_teste',
                            'it.st_obrigatorio'));
        $select->order('it.ni_ordem_item_teste');

        if($analise_solucao_homologacao == 'analise'){
            $select->orWhere('itrn.st_solucao IS NOT NULL')
                   ->where("itrn.st_item_teste_regra_negocio <> 'H' OR itrn.st_item_teste_regra_negocio IS NULL");
       } else if($analise_solucao_homologacao == 'homologacao'){
            $select->orWhere('itrn.st_analise IS NOT NULL')
                   ->where('itrn.st_solucao IS NOT NULL')
                   ->where('itrn.st_item_teste_regra_negocio = ?', 'H');
       }

       $res = $this->fetchAll($select)->toArray();

        if( is_null($res) ){
            return false;
        } else {
            /* ESTE FOREACH FOI ADICIONADO PARA ATENDER AO SELECT
             * ORIGINAL QUE COLOCA O cd_regra_negocio E cd_projeto
             * EM CADA REGISTRA QUE A QUERY RETORNA*/
            foreach($res as $key=>$value){
                $res[$key]['cd_regra_negocio_default'] = $cd_regra_negocio;
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
                    itrn.dt_regra_negocio,
                    itrn.cd_item_teste_regra_negocio,
                    itrn.st_item_teste_regra_negocio,
                    itrn.st_analise,
                    itrn.tx_analise,
                    itrn.st_solucao,
                    itrn.tx_solucao,
                    itrn.st_homologacao,
                    itrn.tx_homologacao,
                    itrn.dt_homologacao,
                    itrn.cd_regra_negocio,
                    count(itrnd.*) as qtd_arquivo,

                    '{$cd_regra_negocio}' as cd_regra_negocio_default,
                    '{$cd_projeto}' as cd_projeto_default
                 from
                    {$this->_schema}.".KT_B_ITEM_TESTE." it
                 left join
                    {$this->_schema}.{$this->_name} itrn
                 on  (
                        it.cd_item_teste = itrn.cd_item_teste
                    and
                        itrn.cd_regra_negocio = {$cd_regra_negocio}
                    and
                        itrn.cd_projeto_regra_negocio = {$cd_projeto}
                    and
                        itrn.cd_item_teste_regra_negocio = (
                            select
                                max(aux.cd_item_teste_regra_negocio)
                            from
                                {$this->_schema}.{$this->_name} aux
                            where
                                aux.cd_item_teste = itrn.cd_item_teste
                            and
                                aux.cd_regra_negocio = {$cd_regra_negocio}
                            and
                                aux.cd_projeto_regra_negocio = {$cd_projeto}
                        )
                    )
                 left join
                    {$this->_schema}.".KT_A_ITEM_TESTE_REGRA_NEGOCIO_DOC." itrnd
                 on (
                        itrn.cd_item_teste = itrnd.cd_item_teste
                    and
                        itrn.cd_item_teste_regra_negocio = itrnd.cd_item_teste_regra_negocio
                    and
                        itrn.cd_regra_negocio = {$cd_regra_negocio}
                    and
                        itrn.cd_projeto_regra_negocio = {$cd_projeto}
                    )
                 where
                    it.st_item_teste = 'A'
                 and
                    it.st_tipo_item_teste = 'N'
                 and (
                    itrn.st_{$analise_solucao_homologacao} is null ";
       if($analise_solucao_homologacao == 'analise'){
        $sql .= "or
                    itrn.st_solucao is not null
                and (
                        itrn.st_item_teste_regra_negocio <> 'H'
                    or
                        itrn.st_item_teste_regra_negocio is null
                    ) ";
       } else if($analise_solucao_homologacao == 'homologacao'){
        $sql .= "or
                    itrn.st_analise is not null
                and
                    itrn.st_solucao is not null
                and
                    itrn.st_item_teste_regra_negocio = 'H' ";
       }
        $sql .= ")
                 group by
                    it.st_tipo_item_teste,
                    itrn.dt_regra_negocio,
                    itrn.cd_item_teste_regra_negocio,
                    itrn.st_item_teste_regra_negocio,
                    itrn.st_analise,
                    itrn.tx_analise,
                    itrn.st_solucao,
                    itrn.tx_solucao,
                    itrn.st_homologacao,
                    itrn.tx_homologacao,
                    itrn.dt_homologacao,
                    itrn.cd_regra_negocio,
                    it.ni_ordem_item_teste,
                    it.cd_item_teste,
                    it.tx_item_teste,
                    it.st_obrigatorio
                 order by
                    it.ni_ordem_item_teste";
*/
    }
}