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

Class RequisitoCasoDeUso extends Base_Db_Table_Abstract 
{
	protected $_name 	 = KT_A_REQUISITO_CASO_DE_USO;
	protected $_primary  = array('cd_projeto','dt_versao_requisito','cd_requisito','dt_versao_caso_de_uso','cd_caso_de_uso','cd_modulo');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

    /**
     * Método para retornar os casos de uso não associados ao requisito pai selecionado
     *
     * @param Integer $cd_projeto
     * @param Integer $cd_requisito
     * @param String $dt_versao     formato YYYY-MM-DD HH24:MI:SS
     * @return Array
     */
	public function getRequisitoCasoDeUsoNaoAssociados( $cd_projeto, $cd_requisito, $dt_versao )
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('cdu'=>KT_S_CASO_DE_USO),
                      array('cd_projeto',
                            'cd_caso_de_uso',
                            'cd_modulo',
                            'dt_versao_caso_de_uso',
                            'tx_caso_de_uso' => new Zend_Db_Expr("tx_caso_de_uso {$this->concat()} ' (".Base_Util::getTranslator('L_SQL_VERSAO')." ' {$this->concat()} ni_versao_caso_de_uso {$this->concat()} ')'")),
                      $this->_schema);
        $select->joinLeft(array('rcu'=>$this->_montaSelectCasoDeUso($cd_projeto, $cd_requisito, $dt_versao)),
                          '(cdu.cd_projeto				= rcu.cd_projeto) 			 AND
                           (cdu.dt_versao_caso_de_uso 	= rcu.dt_versao_caso_de_uso) AND
                           (cdu.cd_caso_de_uso			= rcu.cd_caso_de_uso) 		 AND
                           (cdu.cd_modulo				= rcu.cd_modulo)');

        $select->join(array('maxdat'=>$this->_montaSelectUltimaVersaoUC()),
                      '(cdu.cd_projeto				= maxdat.cd_projeto)			AND
					   (cdu.cd_caso_de_uso			= maxdat.cd_caso_de_uso)		AND
					   (cdu.dt_versao_caso_de_uso	= maxdat.dt_versao_caso_de_uso)	AND
					   (cdu.cd_modulo				= maxdat.cd_modulo)');

        $select->where('cdu.cd_projeto 			  = ? ', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('rcu.cd_projeto 			  IS NULL')
               ->where('rcu.dt_versao_caso_de_uso IS NULL')
               ->where('rcu.cd_caso_de_uso		  IS NULL')
               ->where('rcu.cd_modulo			  IS NULL');
        $select->order('cdu.tx_caso_de_uso');

        return $this->fetchAll($select)->toArray();
	}

    private function _montaSelectCasoDeUso($cd_projeto, $cd_requisito, $dt_versao)
    {
        $select = $this->select()
                       ->from($this,
                              array('cd_projeto',
                                    'dt_versao_caso_de_uso',
                                    'cd_caso_de_uso',
                                    'st_inativo',
                                    'cd_modulo'))
                       ->where('cd_projeto			= ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->where('cd_requisito        = ?', $cd_requisito, Zend_Db::INT_TYPE)
                       ->where('dt_versao_requisito = ?', $dt_versao);

        return $select;
    }

    private function _montaSelectUltimaVersaoUC()
    {
        $objTable = new CasoDeUso();
        $select   = $objTable->select()
                             ->from($objTable,
                                    array('cd_projeto',
                                          'cd_caso_de_uso',
                                          'cd_modulo',
                                          'dt_versao_caso_de_uso' => new Zend_Db_Expr('max(dt_versao_caso_de_uso)')))
                             ->group(array('cd_projeto',
                                           'cd_caso_de_uso',
                                           'cd_modulo'));
        return $select;
    }

    /**
     * Método para retornar os casos de uso não associados ao requisito pai selecionado
     *
     * @param Integer $cd_projeto
     * @param Integer $cd_requisito
     * @param String $dt_versao     formato YYYY-MM-DD HH24:MI:SS
     * @return Array
     */
	public function getRequisitoCasoDeUsoAssociados( $cd_projeto, $cd_requisito, $dt_versao )
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('cdu'=>KT_S_CASO_DE_USO),
                      array('cd_projeto',
                            'cd_caso_de_uso',
                            'cd_modulo',
                            'dt_versao_caso_de_uso',
                            'tx_caso_de_uso' => new Zend_Db_Expr("tx_caso_de_uso {$this->concat()} ' (".Base_Util::getTranslator('L_SQL_VERSAO')." ' {$this->concat()} ni_versao_caso_de_uso {$this->concat()} ')'")),
                      $this->_schema);
        $select->joinLeft(array('rcu'=>$this->_montaSelectCasoDeUso($cd_projeto, $cd_requisito, $dt_versao)),
                          '(cdu.cd_projeto				= rcu.cd_projeto) 			 AND
                           (cdu.dt_versao_caso_de_uso 	= rcu.dt_versao_caso_de_uso) AND
                           (cdu.cd_caso_de_uso			= rcu.cd_caso_de_uso) 		 AND
                           (cdu.cd_modulo				= rcu.cd_modulo)');

        $select->where('cdu.cd_projeto 			  = ?', $cd_projeto, Zend_Db::INT_TYPE)
               ->where('rcu.cd_projeto 			  IS NOT NULL')
               ->where('rcu.dt_versao_caso_de_uso IS NOT NULL')
               ->where('rcu.cd_caso_de_uso		  IS NOT NULL')
               ->where('rcu.cd_modulo			  IS NOT NULL')
               ->where('rcu.st_inativo			  IS NULL');
        $select->order('cdu.tx_caso_de_uso');

        return $this->fetchAll($select)->toArray();
	}

    /**
     * Método para retornar os casos de uso não associados ao requisito pai selecionado
     *
     * @param Integer $cd_projeto
     * @param Integer $cd_requisito
     * @param String $dt_versao_requisito     formato YYYY-MM-DD HH24:MI:SS
     *
     * @return Array
     */
	public function getDadosVersaoRequisitoAnterior( $cd_projeto, $cd_requisito, $dt_versao_requisito )
	{
        $select = $this->select()
                       ->where('cd_requisito        = ?', $cd_requisito, Zend_Db::INT_TYPE)
                       ->where('cd_projeto          = ?', $cd_projeto,   Zend_Db::INT_TYPE)
                       ->where('dt_versao_requisito = ?', $dt_versao_requisito)
                       ->where('st_inativo is null');

		return $this->fetchRow($select)->toArray();
	}
}