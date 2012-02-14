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

class Ator extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_ATOR;
	protected $_primary  = 'cd_ator';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getAtor($comSelecione = false, $cd_projeto=null)
    {
		$select = $this->select();
		if(!is_null($cd_projeto)){
			$select->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE);
		}
		
		$arrAtor = array();
		if ($comSelecione === true) {
			$arrAtor[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$res = $this->fetchAll($select);
		foreach ($res as  $valor) {
			$arrAtor[$valor->cd_ator] = $valor->tx_ator;
		}
		return $arrAtor;
	}
	
	public function salvaAtor($cd_projeto, $tx_ator)
    {
		$novo              = $this->createRow();
		$novo->cd_ator     = $this->getNextValueOfField('cd_ator');
		$novo->cd_projeto  = $cd_projeto;
		$novo->tx_ator     = $tx_ator;
		
		if($novo->save()){
    		return true;
		}else{
    		return false;
		}
	}
	
	public function pesquisaAtorProjeto($cd_projeto, $objeto = false)
    {
		$select = $this->select()->where("cd_projeto = ?",$cd_projeto, Zend_Db::INT_TYPE);

		$arrAtor = $this->fetchAll($select);
		
		if(!$objeto){
			$arrAtor = $arrAtor->toArray();
		}
        
		return $arrAtor;
	}
	
	public function recuperaAtorProjeto($cd_ator, $objeto = false)
    {
		$select = $this->select()->where("cd_ator = ?",$cd_ator, Zend_Db::INT_TYPE);
		$arrAtor = $this->fetchAll($select);
		
		if(!$objeto){
			$arrAtor = $arrAtor->toArray();
		}

		return $arrAtor;
	}	
	
    public function alteraAtorProjeto($cd_projeto, $cd_ator, $tx_ator)
    {
		$arrUpdate["cd_ator"]  = $cd_ator;
		$arrUpdate["tx_ator"]  = $tx_ator;
		$arrUpdate["cd_projeto"] = $cd_projeto;
		
		if($this->update($arrUpdate, array("cd_ator = ?"=>$cd_ator))){
			return true;
		} else {
			return false;
		}
	}
	
	public function excluirAtorProjeto($cd_ator)
    {
		if($this->delete(array("cd_ator = ?"=>$cd_ator))){
			return true;
		} else {
			return false;
		}
	}
}