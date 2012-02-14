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

class RelatorioProjetoChecklistProjeto extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    public function getChecklistProjeto( array $params )
    {

     $cd_contrato = $params["cd_contrato"];

 $sql="select
             p.cd_projeto,
             p.tx_sigla_projeto,
             case when uc.caso_uso is null then 0 else uc.caso_uso end as caso_uso ,
             case when sp.situacao_projeto is null then 0 else sp.situacao_projeto end as situacao_projeto,
             case when pp.profissional_projeto is null then 0 else pp.profissional_projeto end as profissional_projeto,
             case when pl.planejamento is null then 0 else pl.planejamento end as planejamento,
             case when dm.modelo is null then 0 else dm.modelo end as modelo,
             case when dw.wireframe is null then 0 else dw.wireframe end as wireframe,
             case when co.conhecimento_projeto is null then 0 else co.conhecimento_projeto end as conhecimento_projeto,
             case when it.informacao_tecnica is null then 0 else it.informacao_tecnica end as informacao_tecnica
        from
                {$this->_schema}.".KT_S_PROJETO." as p
        left join
             (select cd_projeto,count(*) as caso_uso
              from {$this->_schema}.".KT_S_CASO_DE_USO."
              group by cd_projeto
             ) as uc
             on uc.cd_projeto = p.cd_projeto
        left join
             (select cd_projeto,count(*) as situacao_projeto
              from {$this->_schema}.".KT_S_SITUACAO_PROJETO."
              group by cd_projeto
             ) as sp
             on sp.cd_projeto = p.cd_projeto
        left join
             (select cd_projeto,count(*) as profissional_projeto
              from {$this->_schema}.".KT_A_PROFISSIONAL_PROJETO."
              group by cd_projeto
             ) as pp
             on pp.cd_projeto = p.cd_projeto
        left join
             (select cd_projeto,count(*) as planejamento
              from {$this->_schema}.".KT_A_PLANEJAMENTO."
              group by cd_projeto
             ) as pl
             on pl.cd_projeto = p.cd_projeto
        left join
             (select cd_projeto ,count(*) as modelo
              from {$this->_schema}.".KT_A_DOCUMENTACAO_PROJETO."
              where cd_tipo_documentacao = 1
              group by cd_projeto
             ) as dm
             on dm.cd_projeto = p.cd_projeto
        left join
             (select cd_projeto,count(*) as wireframe
              from {$this->_schema}.".KT_A_DOCUMENTACAO_PROJETO."
              where cd_tipo_documentacao = 2
              group by cd_projeto
             ) as dw
             on dw.cd_projeto = p.cd_projeto
        left join
             (select cd_projeto,count(*) as conhecimento_projeto
              from {$this->_schema}.".KT_A_CONHECIMENTO_PROJETO."
              group by cd_projeto
             ) as co
             on co.cd_projeto = p.cd_projeto
        left join
             (select cd_projeto,count(*) as informacao_tecnica
              from {$this->_schema}.".KT_A_INFORMACAO_TECNICA."
              group by cd_projeto
             ) as it
             on it.cd_projeto = p.cd_projeto

        where
              p.cd_projeto in  (select cd_projeto from {$this->_schema}.".KT_A_CONTRATO_PROJETO."
                                 where cd_contrato = {$cd_contrato})
        order by
             tx_sigla_projeto";


		$arrResult = $this->getDefaultAdapter()->fetchAll($sql);

        return $arrResult;
    }
    
    private function ultimaProjeto(array $arrParams)
    {
        $objTable = new Projeto();
        $select   = $objTable->select()->setIntegrityCheck(false);

        $select->from(KT_S_Projeto,
                      array('cd_ultima_Projeto'=>new Zend_Db_Expr('max(cd_Projeto)')),
                      $this->_schema);

        $select->where('cd_projeto  = ?', $arrParams['cd_projeto' ], Zend_Db::INT_TYPE)
               ->where('cd_proposta = ?', $arrParams['cd_proposta'], Zend_Db::INT_TYPE)
               ->where('st_modulo_proposta is null');

        $subSelect = $objTable->select()
                              ->from(array('a'=>KT_S_Projeto),
                                     array('data'=>new Zend_Db_Expr("max(a.ni_ano_previsao_Projeto {$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} a.ni_mes_previsao_Projeto","{$this->length("'00' {$this->concat()} a.ni_mes_previsao_Projeto")}-1","2")})")),
                                     $this->_schema)
                              ->where('a.cd_projeto  = ?', $arrParams['cd_projeto' ], Zend_Db::INT_TYPE)
                              ->where('a.cd_proposta = ?', $arrParams['cd_proposta'], Zend_Db::INT_TYPE);

        $select->where(new Zend_Db_Expr("(ni_ano_previsao_Projeto {$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} ni_mes_previsao_Projeto","{$this->length("'00' {$this->concat()} ni_mes_previsao_Projeto")}-1","2")}) = ?"), $subSelect);

		return $objTable->fetchAll($select)->toArray();
    }
}