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

class GrupoFator extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_GRUPO_FATOR;
	protected $_primary  = 'cd_grupo_fator';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	
	public function getGrupoFator($comSelecione = false)
	{
		
		$arrGrupoFator = array();
		if ($comSelecione === true) {
			$arrGrupoFator[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$res = $this->fetchAll();
		foreach ($res as  $valor) {
			$arrGrupoFator[$valor->cd_grupo_fator] = $valor->tx_grupo_fator;
		}
		return $arrGrupoFator;
	}

	public function getDadosGrupoFator($cd_grupo_fator = null)
	{
		$select = $this->select();
		if(!is_null($cd_grupo_fator)){
			$select->where("cd_grupo_fator = ?", $cd_grupo_fator, Zend_Db::INT_TYPE);
		}
		$select->order("ni_ordem_grupo_fator");
		return $this->fetchAll($select)->toArray();
	}
	
	public function salvarGrupoFator(array $arrDados)
	{
		$novo = $this->createRow();
		$novo->cd_grupo_fator        = $this->getNextValueOfField('cd_grupo_fator');
		$novo->tx_grupo_fator        = $arrDados['tx_grupo_fator'];
  		$novo->ni_peso_grupo_fator   = $arrDados['ni_peso_grupo_fator'];
  		$novo->ni_ordem_grupo_fator  = $arrDados['ni_ordem_grupo_fator'];
  		$novo->ni_indice_grupo_fator = $arrDados['ni_indice_grupo_fator'];
  		
  		if($novo->save()){
  			return true;
  		} else {
  			return false;
  		}
	}
	
	public function alterarGrupoFator(array $arrDados)
	{
		if($this->update($arrDados,array('cd_grupo_fator = ?'=>$arrDados['cd_grupo_fator']))){
			return true;
		} else {
			return false;
		}
	}
}