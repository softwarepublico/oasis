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

class RelatorioProjetoHistoricoTeste extends Base_Db_Table_Abstract
{
    protected $_schema = K_SCHEMA;

    private $_objTable;

    public function init()
	{
        parent::init();
        $this->_objTable = new ItemTesteCasoDeUso();
    }

    /**
     * Método para recuperar os dados do histórico de teste de Caso de Uso
     * 
     * @param array $params
     * @return <array>
     */
    public function historicoTesteCasoDeUso(array $params)
    {
        $select   = $this->_objTable->select()
                                    ->setIntegrityCheck(false);

        $select->from(array('itcu'=>KT_A_ITEM_TESTE_CASO_DE_USO),
                      array('st_analise',
                            'tx_analise',
                            'dt_analise'=> new Zend_Db_Expr("{$this->to_char('itcu.dt_analise','DD/MM/YYYY')}"),
                            'st_solucao',
                            'tx_solucao',
                            'dt_solucao'=> new Zend_Db_Expr("{$this->to_char('itcu.dt_solucao','DD/MM/YYYY')}"),
                            'st_homologacao',
                            'tx_homologacao',
                            'dt_homologacao'=> new Zend_Db_Expr("{$this->to_char('itcu.dt_homologacao','DD/MM/YYYY')}")),
                      $this->_schema);
        $select->join(array('it'=>KT_B_ITEM_TESTE),
                      '(itcu.cd_item_teste = it.cd_item_teste)',
                      array('tx_item_teste'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(itcu.cd_projeto = proj.cd_projeto)',
                      array('tx_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('md'=>KT_S_MODULO),
                      '(itcu.cd_modulo = md.cd_modulo)',
                      array('tx_modulo'),
                      $this->_schema);
        $select->join(array('cdu'=>KT_S_CASO_DE_USO),
                      '(itcu.cd_caso_de_uso        = cdu.cd_caso_de_uso) and
                       (itcu.dt_versao_caso_de_uso = cdu.dt_versao_caso_de_uso)',
                      array('tx_caso_de_uso',
                            'ni_versao_caso_de_uso'),
                      $this->_schema);
        $select->joinLeft(array('profana'=>KT_S_PROFISSIONAL),
                          '(itcu.cd_profissional_analise = profana.cd_profissional)',
                          array('tx_profissional_analise'=>'tx_profissional'),
                          $this->_schema);
        $select->joinLeft(array('profsol'=>KT_S_PROFISSIONAL),
                          '(itcu.cd_profissional_solucao = profsol.cd_profissional)',
                          array('tx_profissional_solucao'=>'tx_profissional'),
                          $this->_schema);
        $select->joinLeft(array('profhom'=>KT_S_PROFISSIONAL),
                          '(itcu.cd_profissional_homologacao = profhom.cd_profissional)',
                          array('tx_profissional_homologacao'=>'tx_profissional'),
                          $this->_schema);

        $selectComWhere = $this->_montaWhereConsultaHistorico($params,'itcu', $select);

        return $this->_objTable->fetchAll($selectComWhere)->toArray();
    }

    /**
     * Método para recuperar os dados do histórico de teste de Requisito
     *
     * @param array $params
     * @return <array>
     */
    public function historicoTesteRequisito(array $params)
    {
        $select = $this->_objTable->select()
                                  ->setIntegrityCheck(false);
        $select->from(array('itr'=>KT_A_ITEM_TESTE_REQUISITO),
                      array('st_analise',
                            'tx_analise',
                            'dt_analise'=> new Zend_Db_Expr("{$this->to_char('itr.dt_analise','DD/MM/YYYY')}"),
                            'st_solucao',
                            'tx_solucao',
                            'dt_solucao'=> new Zend_Db_Expr("{$this->to_char('itr.dt_solucao','DD/MM/YYYY')}"),
                            'st_homologacao',
                            'tx_homologacao',
                            'dt_homologacao'=> new Zend_Db_Expr("{$this->to_char('itr.dt_homologacao','DD/MM/YYYY')}")),
                      $this->_schema);
        $select->join(array('it'=>KT_B_ITEM_TESTE),
                      '(itr.cd_item_teste = it.cd_item_teste)',
                      array('tx_item_teste'),
                      $this->_schema);
        $select->join(array('rq'=>KT_S_REQUISITO),
                      '(itr.cd_requisito        = rq.cd_requisito) and
                       (itr.dt_versao_requisito = rq.dt_versao_requisito)',
                      array('tx_requisito'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(itr.cd_projeto = proj.cd_projeto)',
                      array('tx_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);
        $select->joinLeft(array('profana'=>KT_S_PROFISSIONAL),
                          '(itr.cd_profissional_analise = profana.cd_profissional)',
                          array('tx_profissional_analise'=>'tx_profissional'),
                          $this->_schema);
        $select->joinLeft(array('profsol'=>KT_S_PROFISSIONAL),
                          '(itr.cd_profissional_solucao = profsol.cd_profissional)',
                          array('tx_profissional_solucao'=>'tx_profissional'),
                          $this->_schema);
        $select->joinLeft(array('profhom'=>KT_S_PROFISSIONAL),
                          '(itr.cd_profissional_homologacao = profhom.cd_profissional)',
                          array('tx_profissional_homologacao'=>'tx_profissional'),
                          $this->_schema);

        $selectComWhere = $this->_montaWhereConsultaHistorico($params, 'itr', $select);

        return $this->_objTable->fetchAll($selectComWhere)->toArray();
    }

    /**
     * Método para recuperar os dados do histórico de teste de Requisito
     *
     * @param array $params
     * @return <array>
     */
    public function historicoTesteRegraNegocio(array $params)
    {
        $select = $this->_objTable->select()
                                  ->setIntegrityCheck(false);
        $select->from(array('itrn'=>KT_A_ITEM_TESTE_REGRA_NEGOCIO),
                      array('st_analise',
                            'tx_analise',
                            'dt_analise'=> new Zend_Db_Expr("{$this->to_char('itrn.dt_analise','DD/MM/YYYY')}"),
                            'st_solucao',
                            'tx_solucao',
                            'dt_solucao'=> new Zend_Db_Expr("{$this->to_char('itrn.dt_solucao','DD/MM/YYYY')}"),
                            'st_homologacao',
                            'tx_homologacao',
                            'dt_homologacao'=> new Zend_Db_Expr("{$this->to_char('itrn.dt_homologacao','DD/MM/YYYY')}")),
                      $this->_schema);
        $select->join(array('rn'=>KT_S_REGRA_NEGOCIO),
                      '(itrn.cd_regra_negocio = rn.cd_regra_negocio) and
                       (itrn.dt_regra_negocio = rn.dt_regra_negocio)',
                      array('tx_regra_negocio'),
                      $this->_schema);
        $select->join(array('it'=>KT_B_ITEM_TESTE),
                      '(itrn.cd_item_teste = it.cd_item_teste)',
                      array('tx_item_teste'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(itrn.cd_projeto_regra_negocio = proj.cd_projeto)',
                      array('tx_projeto',
                            'tx_sigla_projeto'),
                      $this->_schema);
        $select->joinLeft(array('profana'=>KT_S_PROFISSIONAL),
                          '(itrn.cd_profissional_analise = profana.cd_profissional)',
                          array('tx_profissional_analise'=>'tx_profissional'),
                          $this->_schema);
        $select->joinLeft(array('profsol'=>KT_S_PROFISSIONAL),
                          '(itrn.cd_profissional_solucao = profsol.cd_profissional)',
                          array('tx_profissional_solucao'=>'tx_profissional'),
                          $this->_schema);
        $select->joinLeft(array('profhom'=>KT_S_PROFISSIONAL),
                          '(itrn.cd_profissional_homologacao = profhom.cd_profissional)',
                          array('tx_profissional_homologacao'=>'tx_profissional'),
                          $this->_schema);

        $selectComWhere = $this->_montaWhereConsultaHistorico($params, 'itrn', $select);

        return $this->_objTable->fetchAll($selectComWhere)->toArray();
    }

    /**
     * Método que automatiza a construção do where para os select de consulta historico
     * 
     * @param array $params
     * @param Zend_Db_Table_Select $select
     * @return <Zend_Db_Table_Select>
     */
    private function _montaWhereConsultaHistorico(array $params, $aliasTable, Zend_Db_Table_Select $select)
    {
        foreach( $params as $campo=>$valor){
            if($params[$campo] != ""){
                $select->where("{$aliasTable}.{$campo} = ?", new Zend_Db_Expr("{$valor}"));
            }
        }
        return $select;
    }
}