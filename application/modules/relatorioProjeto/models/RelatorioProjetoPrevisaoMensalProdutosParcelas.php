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
class RelatorioProjetoPrevisaoMensalProdutosParcelas extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
    
    public function getProdutosParcela(array $arrParams)
    {
        $objTable = new Parcela();

        $select = $objTable->select()
                           ->setIntegrityCheck(false);
        $select->from(array('parc'=>KT_S_PARCELA),
                      array('cd_parcela',
                            'ni_parcela',
                            'ni_horas_parcela',
                            'cd_projeto',
                            'cd_proposta'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(parc.cd_projeto = proj.cd_projeto)',
                      array('tx_sigla_projeto',
                            'cd_profissional_gerente'),
                      $this->_schema);

        $select->join(array('unid'=>KT_B_UNIDADE),
                      '(proj.cd_unidade = unid.cd_unidade)',
                      array('tx_sigla_unidade',
                            'cd_unidade'),
                      $this->_schema);
        $select->join(array('contproj'=>KT_A_CONTRATO_PROJETO),
                      '(proj.cd_projeto = contproj.cd_projeto)',
                      array(),
                      $this->_schema);
                  
        $select->joinLeft(array('prod'=>KT_S_PRODUTO_PARCELA),
                          '(prod.cd_projeto  = parc.cd_projeto)  and
                           (prod.cd_proposta = parc.cd_proposta) and
                           (prod.cd_parcela  = parc.cd_parcela)',
                          array('tx_produto_parcela'),
                          $this->_schema);
        $select->joinLeft(array('prof'=>KT_S_PROFISSIONAL),
                          '(prof.cd_profissional = proj.cd_profissional_gerente)',
                          array('cd_profissional',
                                'tx_profissional'),
                          $this->_schema);

    	if($arrParams['cd_contrato'] != 0){
            $select->where('contproj.cd_contrato = ?', $arrParams['cd_contrato'], Zend_Db::INT_TYPE);
    	}
    	if($arrParams['mes'] != 0){
            $select->where('parc.ni_mes_previsao_parcela = ?', $arrParams['mes'], Zend_Db::INT_TYPE);
    	}
    	if($arrParams['ano'] != 0){
            $select->where('parc.ni_ano_previsao_parcela = ?', $arrParams['ano'], Zend_Db::INT_TYPE);
    	}
    	if($arrParams['cd_profissional_gerente'] != -1){
            $select->where('proj.cd_profissional_gerente = ?', $arrParams['cd_profissional_gerente'], Zend_Db::INT_TYPE);
    	}
    	if($arrParams['cd_projeto'] != 0){
            $select->where('prod.cd_projeto = ?', $arrParams['cd_projeto'], Zend_Db::INT_TYPE);
    	}
    	if($arrParams['cd_proposta'] != 0){
            $select->where('prod.cd_proposta = ?', $arrParams['cd_proposta'], Zend_Db::INT_TYPE);
    	}
    	if($arrParams['cd_parcela'] != 0){
            $select->where('prod.cd_parcela = ?', $arrParams['cd_parcela'], Zend_Db::INT_TYPE);
    	}
    	if($arrParams['cd_unidade'] != 0){
            $select->where('unid.cd_unidade = ?', $arrParams['cd_unidade'], Zend_Db::INT_TYPE);
    	}
        
        $select->order(array('prof.tx_profissional',
                             'unid.tx_sigla_unidade',
                             'proj.tx_sigla_projeto',
                             'parc.cd_proposta',
                             'parc.ni_parcela',
                             'prod.tx_produto_parcela'));

        return $objTable->fetchAll($select);
    }
}