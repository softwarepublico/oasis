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

class RelatorioProjetoDocumentoAceite extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	
    /**
     * Método que executa a query do relatório de proposta
     * Dados do projeto	 
     *
     * @param ARRAY $params
     * @return ARRAY
     */
    public function getDadosProjeto( $cd_projeto )
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
        $select->where("proj.cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);

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
						  ->where("cd_proposta= ?", $params['cd_proposta'], Zend_Db::INT_TYPE)
						  ->where("cd_parcela = ?", $params['cd_parcela' ], Zend_Db::INT_TYPE);
    	            
        return $parcela->fetchAll($select)->toArray();
    }

    /**
     * Método que executa a query do relatório de produto parcela
     * Cronograma de Execução	 
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getDadosProdutoParcela( $params )
    {
		$produtoParcela = new ProdutoParcela();
		$select = $produtoParcela->select()
                                 ->where("cd_projeto = ?", $params['cd_projeto' ], Zend_Db::INT_TYPE)
                                 ->where("cd_proposta= ?", $params['cd_proposta'], Zend_Db::INT_TYPE)
                                 ->where("cd_parcela = ?", $params['cd_parcela' ], Zend_Db::INT_TYPE)
                                 ->order("tx_produto_parcela");

        $arrResult = $produtoParcela->fetchAll($select)->toArray();

        return $arrResult;
    } 
}