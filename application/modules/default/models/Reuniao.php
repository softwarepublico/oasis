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

class Reuniao extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_REUNIAO;
	protected $_primary  = array('cd_reuniao','cd_projeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getReuniaoProjeto($cd_projeto, $comSelecione=false)
	{
		$arrReuniaoprojeto = array();
		
        $select = $this->select();
		if (!is_null($cd_projeto)) {
			$select->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE)
                   ->order("dt_reuniao DESC");
		}

		$res = $this->fetchAll($select);
		
		if($comSelecione===true){
			$arrReuniaoprojeto[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		foreach ($res as  $valor) {
			$arrReuniaoprojeto[$valor->cd_reuniao] = $valor->dt_reuniao;
		}
		return $arrReuniaoprojeto;
	}
	
	public function getDadosReuniao($cd_projeto = null, $cd_reuniao = null)
	{
		$select = $this->select();
		if(!is_null($cd_projeto)){
			$select->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE)
                   ->order("dt_reuniao desc");
		} else if (!is_null($cd_reuniao)){
			$select->where("cd_reuniao = ?", $cd_reuniao, Zend_Db::INT_TYPE);
		}
		
		return $this->fetchAll($select)->toArray();
	}
	
	public function salvarReuniao(array $arrReuniao)
	{
		$novo 			  		= $this->createRow();
		$novo->cd_reuniao		= $this->getNextValueOfField('cd_reuniao');
		$novo->cd_profissional  = $arrReuniao['cd_profissional_reuniao'];
		$novo->cd_projeto 		= $arrReuniao['cd_projeto_reuniao'];
		$novo->dt_reuniao       = (!empty ($arrReuniao['dt_reuniao'])) ? new Zend_Db_Expr("{$this->to_date("'{$arrReuniao['dt_reuniao']}'", 'DD/MM/YYYY')}"): null;
		$novo->tx_local_reuniao = $arrReuniao['tx_local_reuniao'];
		$novo->tx_pauta 		= $arrReuniao['tx_pauta'];
		$novo->tx_participantes = $arrReuniao['tx_participantes'];
		$novo->tx_ata 			= $arrReuniao['tx_ata'];
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarReuniao(array $arrReuniao)
	{
		$arrUpdate['cd_profissional']   = $arrReuniao['cd_profissional_reuniao'];
		$arrUpdate['cd_projeto'] 		= $arrReuniao['cd_projeto_reuniao'];
		$arrUpdate['dt_reuniao']        = (!empty ($arrReuniao['dt_reuniao'])) ? new Zend_Db_Expr("{$this->to_date("'{$arrReuniao['dt_reuniao']}'", 'DD/MM/YYYY')}") : null;
		$arrUpdate['tx_local_reuniao']  = $arrReuniao['tx_local_reuniao'];
		$arrUpdate['tx_pauta'] 		    = $arrReuniao['tx_pauta'];
		$arrUpdate['tx_participantes']  = $arrReuniao['tx_participantes'];
		$arrUpdate['tx_ata'] 			= $arrReuniao['tx_ata'];
		
		if($this->update($arrUpdate,array( "cd_reuniao = ?"=>$arrReuniao['cd_reuniao'] ))){
			return true;
		} else {
			return false;
		}
	}
}