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

class ReuniaoGeral extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_REUNIAO_GERAL;
	protected $_primary  = array('cd_reuniao_geral','cd_objeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getReuniaoGeralObjeto($cd_objeto, $comSelecione=false)
	{
		$arrReuniaoobjeto = array();
		
        $select = $this->select();
		if (!is_null($cd_objeto)) {
			$select->where("cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE)
                   ->order("dt_reuniao DESC");
		}

		$res = $this->fetchAll($select);
		
		if($comSelecione===true){
			$arrReuniaoobjeto[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		foreach ($res as  $valor) {
			$arrReuniaoobjeto[$valor->cd_reuniao_geral] = $valor->dt_reuniao;
		}
		return $arrReuniaoobjeto;
	}
	
	public function getDadosReuniaoGeral($cd_objeto = null, $cd_reuniao_geral = null)
	{
        
        $select = $this->select();
		if(!is_null($cd_objeto)){
			$select->where("cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE)
                   ->order("dt_reuniao desc");
		} else if (!is_null($cd_reuniao_geral)){
			$select->where("cd_reuniao_geral = ?", $cd_reuniao_geral, Zend_Db::INT_TYPE);
		}
 
		return $this->fetchAll($select)->toArray();
	}
	
	public function salvarReuniaoGeral(array $arrReuniaoGeral)
	{
		$novo 			  		= $this->createRow();
		$novo->cd_reuniao_geral = $this->getNextValueOfField('cd_reuniao_geral');
		$novo->cd_profissional  = $arrReuniaoGeral['cd_profissional_reuniao'];
		$novo->cd_objeto 		= $arrReuniaoGeral['cd_objeto_reuniao'];
		$novo->dt_reuniao       = (!empty ($arrReuniaoGeral['dt_reuniao'])) ? new Zend_Db_Expr("{$this->to_date("'{$arrReuniaoGeral['dt_reuniao']}'", 'DD/MM/YYYY')}") : null;
		$novo->tx_local_reuniao = $arrReuniaoGeral['tx_local_reuniao'];
		$novo->tx_pauta 		= $arrReuniaoGeral['tx_pauta'];
		$novo->tx_participantes = $arrReuniaoGeral['tx_participantes'];
		$novo->tx_ata 			= $arrReuniaoGeral['tx_ata'];
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarReuniaoGeral(array $arrReuniaoGeral)
	{
		$arrUpdate['cd_reuniao_geral']	= $arrReuniaoGeral['cd_reuniao_geral'];
		$arrUpdate['cd_profissional']   = $arrReuniaoGeral['cd_profissional_reuniao'];
		$arrUpdate['cd_objeto'] 		= $arrReuniaoGeral['cd_objeto_reuniao'];
		$arrUpdate['dt_reuniao']        = (!empty ($arrReuniaoGeral['dt_reuniao'])) ? new Zend_Db_Expr("{$this->to_date("'{$arrReuniaoGeral['dt_reuniao']}'", 'DD/MM/YYYY')}") : null;
		$arrUpdate['tx_local_reuniao']  = $arrReuniaoGeral['tx_local_reuniao'];
		$arrUpdate['tx_pauta'] 		    = $arrReuniaoGeral['tx_pauta'];
		$arrUpdate['tx_participantes']  = $arrReuniaoGeral['tx_participantes'];
		$arrUpdate['tx_ata'] 			= $arrReuniaoGeral['tx_ata'];

		if($this->update($arrUpdate,array("cd_reuniao_geral = ?" => $arrReuniaoGeral['cd_reuniao_geral']))){
			return true;
		} else {
			return false;
		}
	}
}