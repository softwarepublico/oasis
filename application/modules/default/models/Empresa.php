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

class Empresa extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_EMPRESA;
	protected $_primary  = 'cd_empresa';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getEmpresa($comSelecione = false)
	{
		$arrEmpresa = array();
		
		if ($comSelecione === true) {
			$arrEmpresa[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		$res = $this->fetchAll($this->select()->where("cd_empresa <> 0")->order("tx_empresa"));
		
		foreach ($res as  $valor) {
			$arrEmpresa[$valor->cd_empresa] = $valor->tx_empresa;
		}
	
		return $arrEmpresa;
	}
	
	public function excluirEmpresa($cd_empresa)
	{
		$objContrato     	= new Contrato();
		$objProfissional 	= new Profissional();
		$objContatoEmpresa	= new ContatoEmpresa();
		
		$where = array('cd_empresa = ?'=>$cd_empresa);
		$arrContrato = $objContrato->fetchAll($where)->toArray();
		$arrContato  = $objContatoEmpresa->fetchAll($where)->toArray();
		
		if( (count($arrContrato) > 0) || (count($arrContato) > 0) ){
			return 2;
		} else {
			$arrProfissional = $objProfissional->fetchAll($where)->toArray();
			if(count($arrProfissional) > 0 ){
				return 2;			
			} else {
				if ($this->delete($where)) {
					return 1;
				} else {
					return 0;
				}
			}
		}
	}
}