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

class PainelContratoController extends Base_Controller_Action 
{
	private $objEmpresa;
	private $objContrato;
	
	public function init()
	{
		parent::init();
		$this->objEmpresa  = new Empresa($this->_request->getControllerName());
		$this->objContrato = new Contrato($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
		$arrSituacao[0]   = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrSituacao['A'] = Base_Util::getTranslator('L_VIEW_COMBO_ATIVO');
		$arrSituacao['I'] = Base_Util::getTranslator('L_VIEW_COMBO_INATIVO');

		$this->view->comboSituacao = $arrSituacao;
	}
	
	public function gridPainelContratoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_empresa  = $this->_request->getParam('cd_empresa',0);
		$st_contrato = $this->_request->getParam('st_situacao',0);
		
		if($cd_empresa != "0" && $st_contrato != "0"){
			$arrDadosContrato = $this->objContrato->getContratoEmpresa($cd_empresa, $st_contrato);
		} else if($cd_empresa != "0"){
			$arrDadosContrato = $this->objContrato->getContratoEmpresa($cd_empresa);
		} else if($st_contrato != "0"){
			$arrDadosContrato = $this->objContrato->getContratoEmpresa(null,$st_contrato);
		} else {
			$arrDadosContrato = $this->objContrato->getContratoEmpresa();
		}
		$this->view->res = $arrDadosContrato; 
	}
}