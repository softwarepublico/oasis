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

class Evento extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_EVENTO;
	protected $_primary  = 'cd_evento';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	
	public function getEvento($comSelecione = false)
	{
		$arrEvento = array();
		
		if ($comSelecione === true) {
			$arrEvento[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$res = $this->fetchAll();
		
		foreach ($res as  $valor) {
			$arrEvento[$valor->cd_evento] = $valor->tx_evento;
		}
	
		return $arrEvento;
	}

	public function getDadosEvento($cd_evento = null)
	{
        $select = null;
		if(!is_null($cd_evento)){
			$select = $this->select()
                           ->where("cd_evento = ?", $cd_evento, Zend_Db::INT_TYPE);
		}
		
		return $this->fetchAll($select)->toArray();
	}
	
	public function salvarEvento(array $arrDados)
	{
		$novo = $this->createRow();
		$novo->cd_evento = $this->getNextValueOfField('cd_evento');
		$novo->tx_evento = $arrDados['tx_evento'];
  		
  		if($novo->save()){
  			return true;
  		} else {
  			return false;
  		}
	}
	
	public function alterarEvento(array $arrDados)
	{
		if($this->update($arrDados,array('cd_evento = ?'=>$arrDados['cd_evento']))){
			return true;
		} else {
			return false;
		}
	}
}