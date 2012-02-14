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

class CasoDeUsoController extends Base_Controller_Action
{
	private $projeto;
	private $ator;
	private $objModulo;

	public function init()
	{
		parent::init();
		$this->casoDeUso = new CasoDeUso($this->_request->getControllerName());
		$this->ator      = new Ator($this->_request->getControllerName());
		$this->projeto   = new Projeto($this->_request->getControllerName());
		$this->objModulo = new Modulo($this->_request->getControllerName());
	}

	public function indexAction()
	{}
	
	public function comboGroupCasoDeUsoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam('cd_projeto');
		$cd_modulo  = $this->_request->getParam('cd_modulo');
		$cd_modulo = ($cd_modulo)?$cd_modulo:null;
		
		$arrCasoDeUsoAberto  = $this->casoDeUso->getCasoDeUso(false,$cd_projeto,$cd_modulo,'N');
		$arrCasoDeUsoFechado = $this->casoDeUso->getCasoDeUso(false,$cd_projeto,$cd_modulo,'S');

		$strOption  = "<option selected=\"selected\" label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		//Monta Group Caso de Uso Abertos
        $strOption .= "<optgroup label=\"".Base_Util::getTranslator('L_VIEW_COMBO_ABERTOS')."\">";
		if(count($arrCasoDeUsoAberto) > 0){
			foreach($arrCasoDeUsoAberto as $key=>$value){
				$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
			}
		} else {
			$strOption .= "<option disabled=\"disabled\" value=\"999999\">".Base_Util::getTranslator('L_VIEW_COMBO_NENHUM_CASO_DE_USO_ABERTO')."</option>";
		}
		$strOption .= "</optgroup>";

		//Monta Group Caso de Uso Fechados
		$strOption .= "<optgroup label=\"".Base_Util::getTranslator('L_VIEW_COMBO_FECHADOS')."\">";
		if(count($arrCasoDeUsoFechado) > 0){
			foreach($arrCasoDeUsoFechado as $chave=>$valor){
				$strOption .= "<option label=\"{$valor}\" value=\"{$chave}\">{$valor}</option>";
			}
		} else {
			$strOption .= "<option disabled=\"disabled\" value=\"99999\">".Base_Util::getTranslator('L_VIEW_COMBO_NENHUM_CASO_DE_USO_FECHADO')."</option>";
		}
		$strOption .= "</optgroup>";

		echo $strOption;
	}
	
	public function comboCasoDeUsoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');
		$cd_modulo  = $this->_request->getParam('cd_modulo');
		$cd_modulo = ($cd_modulo)?$cd_modulo:null;
		
		$arrCasoDeUso = $this->casoDeUso->getCasoDeUso(true,$cd_projeto,$cd_modulo);

		$strSelect = "<option label='".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."' value='0'>".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		if(count($arrCasoDeUso) > 0){
			foreach($arrCasoDeUso as $key=>$conteudo){
				$strSelect .= "<option label='{$conteudo}' value='{$key}'>{$conteudo}({})</option>";
			}
		}
		
		echo $strSelect;
	}

	public function tabFecharCasoDeUsoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam("cd_projeto");
		$cd_modulo  = $this->_request->getParam("cd_modulo",0);
		$cd_modulo = ($cd_modulo != "undefined" && $cd_modulo != 0)?$cd_modulo:null;
		
		$this->view->arrModulo = $this->objModulo->getModulo($cd_projeto, true);	
		$this->view->cd_modulo = $cd_modulo;
		$this->view->res = $this->casoDeUso->pesquisaCasoDeUsoProjeto($cd_projeto,$cd_modulo);
	}
	
	public function fecharCasoDeUsoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_caso_de_uso        = $this->_request->getParam('cd_caso_de_uso');	
		$dt_versao_caso_de_uso = $this->_request->getParam('dt_versao_caso_de_uso');	
		
		$return = $this->casoDeUso->fechaCasoDeUso($cd_caso_de_uso, $dt_versao_caso_de_uso);
		$msg = ($return)? Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_CASO_DE_USO') : Base_Util::getTranslator('L_MSG_ERRO_FECHAR_CASO_DE_USO');
		
		echo $msg;
	}
}