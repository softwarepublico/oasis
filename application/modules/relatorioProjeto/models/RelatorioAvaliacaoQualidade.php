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

class RelatorioAvaliacaoQualidade extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

	public function getPropostaProjeto( $cd_projeto ){

		$objProposta = new Proposta();

		$select = $objProposta->select()
                              ->setIntegrityCheck(false);

        $select->from(array('prop'=>KT_S_PROPOSTA),
                      array('cd_proposta',
                            'cd_projeto',
                            'proposta'=> new Zend_Db_Expr("'".Base_Util::getTranslator('L_SQL_PROPOSTA_NR')." ' {$this->concat()} cd_proposta"),
                            'nf_indice_avaliacao_proposta'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      'prop.cd_projeto = proj.cd_projeto',
                      array('tx_sigla_projeto',
                            'tx_projeto'),
                      $this->_schema);

        $select->where("prop.cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);

        $select->order("cd_proposta");

		return $objProposta->fetchAll($select);
	}

	public function getPercentualAvaliacaoProjeto($cd_projeto){

		$objProposta = new Proposta();
		$select = $objProposta->select()
                              ->setIntegrityCheck(false);

        $select->from(array('prop'=>KT_S_PROPOSTA),
                      array('precentual_avaliacao_projeto'=> new Zend_Db_Expr("SUM(nf_indice_avaliacao_proposta)/COUNT(cd_proposta)")),
                      $this->_schema);

        $select->join(array('proj'=>KT_S_PROJETO),
                     '(prop.cd_projeto = proj.cd_projeto)',
                     array('tx_sigla_projeto',
                           'tx_projeto'),
                     $this->_schema);

        $select->where("prop.cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);

        $select->group("tx_projeto")
               ->group("tx_sigla_projeto");
               
		return $objProposta->fetchAll($select);
	}

	public function getDadosContrato($cd_contrato){

		$objContrato = new Contrato();
		$select = $objContrato->select()
                              ->setIntegrityCheck(false);

        $select->from(array('cont'=>KT_S_CONTRATO),
                      array('cd_contrato',
                            'tx_numero_contrato'),
                      $this->_schema);

        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      'cont.cd_contrato = oc.cd_contrato',
                      array('tx_objeto'),
                      $this->_schema);

        $select->where('cont.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

		return $objContrato->fetchAll($select);
	}
}