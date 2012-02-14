<?php
class RelatorioGerenciamentoRiscoProjeto extends Base_Db_Table_Abstract
{
    protected $_schema = K_SCHEMA;
    private   $_objAnaliseRisco;

    public function __construct()
    {
        parent::__construct();
        $this->_objAnaliseRisco = new AnaliseRisco();
    }

    public function getAnaliseRisco(Array $arrParam = array())
    {
        $select = $this->_objAnaliseRisco->select()->setIntegrityCheck(false);

        $select->from(array('ar'=>KT_S_ANALISE_RISCO),
                      array('dt_analise_risco' => new Zend_Db_Expr("max(ar.dt_analise_risco)"),
                            'cd_projeto',
                            'cd_proposta',
                            'cd_etapa',
                            'cd_atividade',
                            'cd_item_risco',
                            'dt_fechamento_risco',
                            'tx_cor_impacto_cronog_risco',
                            'tx_cor_impacto_custo_risco',
                            'tx_cor_impacto_tecnico_risco',
                            'tx_cor_impacto_projeto_risco'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      "(ar.cd_projeto = proj.cd_projeto)",
                      array('tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('eta'=>KT_B_ETAPA),
                      "(ar.cd_etapa = eta.cd_etapa)",
                      array('tx_etapa'),
                      $this->_schema);
        $select->join(array('ati'=>KT_B_ATIVIDADE),
                      "(ar.cd_atividade = ati.cd_atividade)",
                      array('tx_atividade'),
                      $this->_schema);
        $select->join(array('ir'=>KT_B_ITEM_RISCO),
                      "(ar.cd_item_risco = ir.cd_item_risco)",
                      array('tx_item_risco'),
                      $this->_schema);
        $select->order(array('ar.cd_projeto',
                             'ar.cd_etapa',
                             'ar.cd_atividade',
                             'ar.cd_item_risco'));
        $select->group(array('ar.cd_projeto',
                             'ar.cd_etapa',
                             'ar.cd_atividade',
                             'ar.cd_item_risco',
                             'proj.tx_sigla_projeto',
                             'ar.cd_proposta',
                             'eta.tx_etapa',
                             'ati.tx_atividade',
                             'ir.tx_item_risco',
                             'ar.dt_fechamento_risco',
                             'ar.tx_cor_impacto_cronog_risco',
                             'ar.tx_cor_impacto_custo_risco',
                             'ar.tx_cor_impacto_tecnico_risco',
                             'ar.tx_cor_impacto_projeto_risco'));

        $this->_mountWhere($arrParam, $select);
        
        return $this->_objAnaliseRisco->fetchAll($select);
    }
}