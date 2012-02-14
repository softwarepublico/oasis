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

class RegraNegocioRequisito extends Base_Db_Table_Abstract 
{
	protected $_name 	 = KT_A_REGRA_NEGOCIO_REQUISITO;
	protected $_primary  = array('cd_projeto_regra_negocio','dt_regra_negocio','cd_regra_negocio','cd_projeto','dt_versao_requisito','cd_requisito');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getRegraNegocioRequisitoNaoAssociadas( $cd_projeto, $cd_requisito, $dt_versao )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('rn'=>KT_S_REGRA_NEGOCIO),
                      array('cd_projeto_regra_negocio',
                            'dt_regra_negocio',
                            'cd_regra_negocio',
                            'tx_regra_negocio'=> new Zend_Db_Expr("rn.tx_regra_negocio {$this->concat()} ' (".Base_Util::getTranslator('L_SQL_VERSAO')." ' {$this->concat()} ni_versao_regra_negocio {$this->concat()} ')'")),
                      $this->_schema);

		$select->joinLeft(array('reg'=>$this->_montaSelectAssociacaoRegraRequisito($cd_projeto, $cd_requisito, $dt_versao)),
                          '(rn.cd_projeto_regra_negocio	= reg.cd_projeto_regra_negocio) AND
                           (rn.dt_regra_negocio 		= reg.dt_regra_negocio) 		AND
                           (rn.cd_regra_negocio			= reg.cd_regra_negocio)',
                          array());

        $select->join(array('maxdat'=>$this->_montaSelectUltimaVersaoRegraDeNegocio()),
                      '(rn.cd_projeto_regra_negocio	= maxdat.cd_projeto_regra_negocio)	AND
					   (rn.cd_regra_negocio			= maxdat.cd_regra_negocio)			AND
                       (rn.dt_regra_negocio			= maxdat.dt_regra_negocio)',
                      array());

        $select->where('rn.cd_projeto_regra_negocio 	= ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('reg.cd_projeto_regra_negocio 	IS NULL')
               ->where('reg.dt_regra_negocio			IS NULL')
               ->where('reg.cd_regra_negocio			IS NULL');

        $select->order('rn.tx_regra_negocio');

        return $this->fetchAll($select)->toArray();
	}

    private function _montaSelectAssociacaoRegraRequisito($cd_projeto, $cd_requisito, $dt_versao)
    {
        $select = $this->select()
                       ->from($this,
                              array('cd_projeto_regra_negocio',
                                    'dt_regra_negocio',
                                    'st_inativo',
                                    'cd_regra_negocio'))
                       ->where('cd_projeto			= ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('cd_requisito 		= ?', $cd_requisito, Zend_Db::INT_TYPE)
                       ->where('dt_versao_requisito = ?', $dt_versao);

        return $select;
    }

    private function _montaSelectUltimaVersaoRegraDeNegocio()
    {
        $objTable = new RegraNegocio();

        $select   = $objTable->select()
                             ->from($objTable,
                                    array('cd_projeto_regra_negocio',
                                          'cd_regra_negocio',
                                          'dt_regra_negocio' => new Zend_Db_Expr('max(dt_regra_negocio)')))
                             ->group(array('cd_projeto_regra_negocio', 'cd_regra_negocio'));

        return $select;
    }
	
	public function getRegraNegocioRequisitoAssociadas( $cd_projeto, $cd_requisito, $dt_versao )
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('rn'=>KT_S_REGRA_NEGOCIO),
                      array('cd_projeto_regra_negocio',
                            'dt_regra_negocio',
                            'cd_regra_negocio',
                            'tx_regra_negocio'=> new Zend_Db_Expr("rn.tx_regra_negocio {$this->concat()} ' (".Base_Util::getTranslator('L_SQL_VERSAO')." ' {$this->concat()} ni_versao_regra_negocio {$this->concat()} ')'")),
                      $this->_schema);

		$select->joinLeft(array('reg'=>$this->_montaSelectAssociacaoRegraRequisito($cd_projeto, $cd_requisito, $dt_versao)),
                          '(rn.cd_projeto_regra_negocio	= reg.cd_projeto_regra_negocio) AND
                           (rn.dt_regra_negocio 		= reg.dt_regra_negocio) 		AND
                           (rn.cd_regra_negocio			= reg.cd_regra_negocio)',
                          array());

        $select->where('rn.cd_projeto_regra_negocio 	= ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('reg.cd_projeto_regra_negocio 	IS NOT NULL')
               ->where('reg.dt_regra_negocio			IS NOT NULL')
               ->where('reg.cd_regra_negocio			IS NOT NULL')
               ->where('reg.st_inativo					IS NULL');

        $select->order('rn.tx_regra_negocio');

        return $this->fetchAll($select)->toArray();
	}
	
	public function getDadosVersaoRequisitoAnterior( $cd_projeto, $cd_requisito, $dt_versao_requisito )
	{
        $select = $this->select()
                       ->where('cd_requisito		= ?', $cd_requisito, Zend_Db::INT_TYPE)
                       ->where('cd_projeto			= ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('dt_versao_requisito = ?', $dt_versao_requisito)
                       ->where('st_inativo IS NULL');
                       
		return $this->fetchRow($select)->toArray();
	}
}