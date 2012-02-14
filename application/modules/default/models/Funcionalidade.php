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

class Funcionalidade extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_B_FUNCIONALIDADE;
	protected $_primary  = 'cd_funcionalidade';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function listaFuncionalidade($cd_funcionalidade = null, $tipoItem = false)
	{
		$select = $this->select()->order("tx_codigo_funcionalidade");

		if (!is_null($cd_funcionalidade)) {
			$select->where("cd_funcionalidade = ?",$cd_funcionalidade, Zend_Db::INT_TYPE);
		}
        if ($tipoItem === true) {
            $select->where("st_funcionalidade = 'I'");
        }
		return  $this->fetchAll($select);
	}
	
	public function getFuncionalidade($comSelecione = false, $comCodigoClassificacao = false, $tipoItem = false)
	{
		$arrFuncionalidade = array();

		if ($comSelecione === true) {
			$arrFuncionalidade[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

		$res = $this->listaFuncionalidade(null, $tipoItem);

		foreach ($res as  $valor) {
			if ($comCodigoClassificacao === true) {
				$arrFuncionalidade[$valor->cd_funcionalidade] = "{$valor->tx_codigo_funcionalidade} - {$valor->tx_funcionalidade}";
			}else{
			$arrFuncionalidade[$valor->cd_funcionalidade] = "{$valor->tx_funcionalidade}";
			}
		}

		return $arrFuncionalidade;
	}

	public function recuperaFuncionalidade($cd_funcionalidade)
    {
		return $this->listaFuncionalidade($cd_funcionalidade)->toArray();
	}

	public function excluirFuncionalidade($cd_funcionalidade)
	{
		if($this->delete(array('cd_funcionalidade = ?'=>$cd_funcionalidade))){
			return true;
		} else {
			return false;
		}
	}

	public function salvaFuncionalidade(array $arrFuncionalidade)
	{
		$novo 				            = $this->createRow();
		$novo->cd_funcionalidade	    = $this->getNextValueOfField('cd_funcionalidade');
		$novo->tx_codigo_funcionalidade = $arrFuncionalidade['tx_codigo_funcionalidade'];
		$novo->tx_funcionalidade		= $arrFuncionalidade['tx_funcionalidade'];
		$novo->tx_descricao     		= $arrFuncionalidade['tx_descricao'];
		$novo->st_funcionalidade		= ($arrFuncionalidade['st_funcionalidade'] == "0")? null : $arrFuncionalidade['st_funcionalidade'];

		if ($novo->save()) {
			return true;
		} else {
			return false;
		}
	}

	public function alterarFuncionalidade(array $arrFuncionalidade)
	{
		$arrUpdate['tx_codigo_funcionalidade']  = $arrFuncionalidade['tx_codigo_funcionalidade'];
		$arrUpdate['tx_funcionalidade']			= $arrFuncionalidade['tx_funcionalidade'];
		$arrUpdate['tx_descricao']			    = $arrFuncionalidade['tx_descricao'];
		$arrUpdate['st_funcionalidade']		    = ($arrFuncionalidade['st_funcionalidade'] == "0")? null : $arrFuncionalidade['st_funcionalidade'];

		if($this->update($arrUpdate, array('cd_funcionalidade = ?'=>$arrFuncionalidade['cd_funcionalidade']))){
			return true;
		} else {
			return false;
		}
	}
}