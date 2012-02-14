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

class Modulo extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_MODULO;
	protected $_primary  = array('cd_modulo','cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getModulo($cd_projeto = null, $comSelecione = false, $comTodos = false)
	{
		$arrModulo = array();
		if ($comSelecione === true) {
			$arrModulo[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		if ($comTodos === true) {
			$arrModulo['todos']  = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
		}

        $select = $this->select()
                       ->distinct()
                       ->setIntegrityCheck(false);
        $select->from(array('pm'=>KT_A_PROPOSTA_MODULO),
                      array(),
                      $this->_schema);
        $select->join(array('pro'=>KT_S_PROPOSTA),
                      '(pm.cd_projeto = pro.cd_projeto and pm.cd_proposta = pro.cd_proposta)',
                      array(),
                      $this->_schema);
        $select->join(array('modu'=>$this->_name),
                      '(pm.cd_modulo = modu.cd_modulo and pm.cd_projeto = modu.cd_projeto)',
                      array('cd_modulo','tx_modulo'),
                      $this->_schema);
//        $select->where('pro.st_encerramento_proposta is null or pro.st_encerramento_proposta = ?', 'E');
        $select->order('modu.tx_modulo');

		if (!is_null($cd_projeto)) {
            $select->where('pm.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
		}

		$rowSet = $this->fetchAll($select);
		if($rowSet->valid()){
			foreach ($rowSet as  $valor) {
				$arrModulo[$valor->cd_modulo] = $valor->tx_modulo;
			}
		}
		return $arrModulo;
	}

	public function listaModulo($cd_projeto, $comSelecione = false, $comTodos = false)
	{
		$arrModulo = array();
		if ($comSelecione === true) {
			$arrModulo[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		if ($comTodos === true) {
			$arrModulo['todos'] = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
		}

        $select = $this->select()
                       ->from($this, array('cd_modulo','tx_modulo'))
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->order('tx_modulo');

		$rowSet = $this->fetchAll($select);

		if($rowSet->valid()){
			foreach ($rowSet as  $valor) {
				$arrModulo[$valor->cd_modulo] = $valor->tx_modulo;
			}
		}
		return $arrModulo;
	}
	
	public function pesquisaModuloNaoVinculado($cd_projeto, $cd_proposta)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from($this, array('cd_modulo','tx_modulo'))
                       ->where('cd_modulo NOT IN ?', $this->_montaSubSelectPesquisaVinculoModulo($cd_projeto, $cd_proposta))
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->order('tx_modulo');

		return $this->fetchAll($select)->toArray();
	}

    public function pesquisarModuloVinculado($cd_projeto, $cd_proposta = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from($this, array('cd_modulo','tx_modulo'))
                       ->where('cd_modulo IN ?', $this->_montaSubSelectPesquisaVinculoModulo($cd_projeto, $cd_proposta))
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                       ->order('tx_modulo');

		return $this->fetchAll($select)->toArray();
	}

    /**
     * 
     * @param int $cd_projeto
     * @param int $cd_proposta    OPCIONAL
     * @return Zend_Db_Table_Select
     */
    private function _montaSubSelectPesquisaVinculoModulo($cd_projeto, $cd_proposta = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from(KT_A_PROPOSTA_MODULO, 'cd_modulo', $this->_schema)
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
		if(!is_null($cd_proposta)){
            $select->where('cd_proposta = ?', $cd_proposta, Zend_Db::INT_TYPE);
		}

        return $select;
    }

	public function getModuloProposta($cd_projeto, $cd_proposta)
	{
        $subSelect = $this->select()
                          ->setIntegrityCheck(false)
                          ->from(KT_A_PROPOSTA_MODULO,
                                 array('cd_modulo'),
                                 $this->_schema)
                          ->where("cd_projeto  = ?", $cd_projeto, Zend_Db::INT_TYPE)
                          ->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE);

        $select = $this->select()
                       ->setIntegrityCheck(false)
                       ->from($this->_name,
                              array('cd_modulo','tx_modulo'),
                              $this->_schema)
                       ->where('cd_modulo IN (?)', $subSelect)
                       ->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE)
                       ->order('tx_modulo');

        $rowSet = $this->fetchAll($select);

        $arrModuloProposta = array();
		if ($rowSet->valid()){
			foreach ($rowSet as $valor){
				$arrModuloProposta[$valor->cd_modulo] = $valor->tx_modulo;
			}
		}
		return $arrModuloProposta;
	}
	
	/**
	 * Método que verifica o modulo especifico
	 *
	 * @param int $cd_modulo
	 * @param int $cd_projeto
	 * @param boolean $object
	 * @return array $arrDados
	 * @author wunilberto melo
	 * @since 03/10/2008
	 */
	public function recuperaModulo($cd_modulo, $cd_projeto, $objct = false)
	{
		$select = $this->select()
                       ->where("cd_modulo  = ?", $cd_modulo, Zend_Db::INT_TYPE)
                       ->where("cd_projeto = ?",$cd_projeto, Zend_Db::INT_TYPE);
		$arrDados = $this->fetchAll($select);
		
		if($objct){
			$arrDados = $arrDados->toArray();
		}
		
		return $arrDados;
	}
}