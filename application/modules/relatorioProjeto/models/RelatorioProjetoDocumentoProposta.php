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

class RelatorioProjetoDocumentoProposta extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	
    /**
     * Método que executa a query do relatório de proposta
     * Dados do projeto	 
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getDadosProjeto( array $params )
    {
        $objTable = new Projeto();

        $select = $objTable->select()->setIntegrityCheck(false);

        $select->from(array('proj'=>KT_S_PROJETO),
                      array('cd_projeto',
                            'tx_sigla_projeto',
                            'tx_projeto',
                            'tx_contexto_geral_projeto',
                            'tx_escopo_projeto',
                            'tx_gestor_projeto',
                            'st_impacto_projeto',
                            'desc_impacto'=>new Zend_Db_Expr("case when st_impacto_projeto = 'I' then '".Base_Util::getTranslator('L_SQL_INTERNO')."'
                                                                   when st_impacto_projeto = 'E' then '".Base_Util::getTranslator('L_SQL_EXTERNO')."'
                                                                   when st_impacto_projeto = 'A' then '".Base_Util::getTranslator('L_SQL_INTERNO_EXTERNO')."'
                                                              end"),
                            'ni_mes_inicio_previsto',
                            'ni_ano_inicio_previsto',
                            'ni_mes_termino_previsto',
                            'ni_ano_termino_previsto'),
                      $this->_schema);
        $select->join(array('unid'=>KT_B_UNIDADE),
                      '(proj.cd_unidade = unid.cd_unidade)',
                      array('tx_sigla_unidade'),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(proj.cd_profissional_gerente = prof.cd_profissional)',
                      array('tx_profissional'),
                      $this->_schema);
        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(proj.cd_projeto = prop.cd_projeto)',
                      array('cd_proposta',
                            'tx_objetivo_proposta',
                            'solicitacao'=>new Zend_Db_Expr("prop.ni_solicitacao {$this->concat()}'/'{$this->concat()}prop.ni_ano_solicitacao")),
                      $this->_schema);
                  
        $select->where('prop.cd_proposta = ?', $params['cd_proposta'], Zend_Db::INT_TYPE);
        $select->where('prop.cd_projeto  = ?', $params['cd_projeto' ], Zend_Db::INT_TYPE);
        
        return $objTable->fetchAll($select)->toArray();
    }
    
    /**
     * Método que executa a query do relatório de parcela
     * Cronograma de Execução	 
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
   public function getDadosParcela( array $params )
    {
        $parcela = new Parcela();
        $select = $parcela->select()
                ->where("cd_projeto = ?", $params['cd_projeto' ], Zend_Db::INT_TYPE)
                ->where("cd_proposta = ?", $params['cd_proposta'], Zend_Db::INT_TYPE)
                ->order("ni_parcela");

        if (is_null($params['st_parcela_orcamento'])) {
            $select->where("st_modulo_proposta is null");
        }

        $arrResult = $parcela->fetchAll($select)->toArray();
        return $arrResult;
    }
    
    /**
     * Método que executa a query do relatório Dados Proposta
     * Valor total das métricas
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getDadosProposta( array $params  )
    {
        $objTable = new Parcela();

        $subSelect = $objTable->select()
                              ->from(KT_S_PARCELA,
                                     array('*',
									       'periodo'=>new Zend_Db_Expr("ni_ano_previsao_parcela{$this->concat()}'/'{$this->concat()} {$this->substring("'00' {$this->concat()} ni_mes_previsao_parcela","{$this->length("'00' {$this->concat()} ni_mes_previsao_parcela")}-1","2")}")),
                                     $this->_schema)
                              ->where("cd_projeto  = ?", $params['cd_projeto' ], Zend_Db::INT_TYPE)
                              ->where("cd_proposta = ?", $params['cd_proposta'], Zend_Db::INT_TYPE);


        $select = $objTable->select()
                           ->setIntegrityCheck(false)
                           ->from(array('dados'=>$subSelect),
                                    array('dt_inicio'=> new Zend_Db_Expr("{$this->substring("min(periodo)","{$this->position("/", "min(periodo)")}+1","4")} {$this->concat()} '/' {$this->concat()} {$this->substring("min(periodo)","1","{$this->position("/","min(periodo)")}-1")}"),
										  'dt_fim'   => new Zend_Db_Expr("{$this->substring("max(periodo)","{$this->position("/","max(periodo)")}+1","4")} {$this->concat()} '/' {$this->concat()} {$this->substring("max(periodo)","1","{$this->position("/","max(periodo)")}-1")}")));

		return $objTable->fetchAll($select)->toArray();
    }
    
    /**
     * Método que executa a query do relatório de produto parcela
     * Cronograma de Execução	 
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getDadosProdutoParcela( $parcela )
    {
		$produtoParcela = new ProdutoParcela();
		$select = $produtoParcela->select()
                                 ->where("cd_parcela = ?", $parcela, Zend_Db::INT_TYPE)
                                 ->order("tx_produto_parcela");
    	            
        return $produtoParcela->fetchAll($select)->toArray();
    } 
    
	/**
     * Método que executa a query de dados de Processamento Proposta
     * Data de Fechamento
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getDadosProcessamentoPropostaAtivo( array $params  )
    { 
		$processamentoPropostaAtivo = new ProcessamentoProposta();
		$select = $processamentoPropostaAtivo->select()
                                             ->where("cd_projeto = ?", $params['cd_projeto' ], Zend_Db::INT_TYPE)
                                             ->where("cd_proposta= ?", $params['cd_proposta'], Zend_Db::INT_TYPE)
                                             ->where("st_ativo   = ?", 'S');
    	            
        $arrResult = $processamentoPropostaAtivo->fetchAll($select);
        return $arrResult->toArray();
    }

	/**
     * Método que executa a query de dados de Processamento Proposta
     * Data de Fechamento
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getDadosProcessamentoPropostaAceite( array $params  )
    {
        $objTable = new ProcessamentoProposta();

        $select = $objTable->select()
                           ->from(KT_S_PROCESSAMENTO_PROPOSTA,
                                  array('dt_aceite_proposta_order' => 'dt_aceite_proposta',
                                        'dt_aceite_proposta'       => new Zend_Db_Expr("{$this->to_char('dt_aceite_proposta','DD/MM/YYYY HH24:MI:SS')}")),
                                  $this->_schema)
                           ->where("cd_projeto  = ?", $params['cd_projeto' ], Zend_Db::INT_TYPE)
                           ->where("cd_proposta = ?", $params['cd_proposta'], Zend_Db::INT_TYPE)
                           ->where('st_fechamento_proposta IS NOT NULL')
                           ->order('dt_aceite_proposta_order DESC');

        return $objTable->fetchAll($select)->toArray();
    }
  
    /**
     * Método que executa a query do relatório Dados Proposta
     * Módulos da proposta
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
   public function getDadosModuloProposta( array $params  )
    {
		$objModulo = new Modulo();
        $arrResult = $objModulo->getModuloProposta($params['cd_projeto'], $params['cd_proposta']);

        return $arrResult;
    }	

	public function getUltimoFechamentoProposta(array $params)
	{
        $objTable = new ProcessamentoParcela();

        $select = $objTable->select()
                           ->setIntegrityCheck(false)
                           ->from(KT_S_PROCESSAMENTO_PROPOSTA,
                                  array('dt_fechamento_proposta'=>new Zend_Db_Expr("{$this->to_char('max(dt_fechamento_proposta)','DD/MM/YYYY HH24:MI:SS')}")),
                                  $this->_schema)
                           ->where("cd_projeto  = ?", $params['cd_projeto' ], Zend_Db::INT_TYPE)
                           ->where("cd_proposta = ?", $params['cd_proposta'], Zend_Db::INT_TYPE);

        return $objTable->fetchAll($select)->toArray();
	}
}