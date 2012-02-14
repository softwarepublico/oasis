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

class AreaConhecimento extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_AREA_CONHECIMENTO;
	protected $_primary  = 'cd_area_conhecimento';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function comboAreaConhecimento($comSelecione = true)
	{
		$arrDados = $this->fetchAll();
		
		if($comSelecione){
			$arrAreaConhecimento[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		foreach($arrDados as  $valor) {
			$arrAreaConhecimento[$valor->cd_area_conhecimento] = $valor->tx_area_conhecimento;
		}
		return $arrAreaConhecimento;
	}
	
	public function getDadosAreaConhecimento($cd_area_conhecimento = null)
	{
        $select = $this->select();
		if(!is_null($cd_area_conhecimento)){
			$select->where("cd_area_conhecimento = ?", $cd_area_conhecimento, Zend_Db::INT_TYPE);
		}
		return $this->fetchAll($select)->toArray();
	}
	
	public function salvarAreaConhecimento(array $arrAreaConhecimento)
	{
		$novo = $this->createRow();	
		$novo->cd_area_conhecimento = $this->getNextValueOfField("cd_area_conhecimento");
		$novo->tx_area_conhecimento = $arrAreaConhecimento['tx_area_conhecimento'];

		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarAreaConhecimento(array $arrAreaConhecimento)
	{
		if($this->update($arrAreaConhecimento,array('cd_area_conhecimento = ?'=>$arrAreaConhecimento['cd_area_conhecimento']))){
			return true;
		} else {
			return false;
		}
	}
	
	public function excluirAreaConhecimento($cd_area_conhecimento)
	{
		if($this->delete(array('cd_area_conhecimento = ?'=>$cd_area_conhecimento))){
			return true;
		} else {
			return false;
		}
	}
}