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

class PainelProfissionalController extends Base_Controller_Action 
{
	private $objEmpresa;
	private $objProfissional;

	public function init()
	{
		parent::init();
		$this->objEmpresa      = new Empresa($this->_request->getControllerName());
		$this->objProfissional = new Profissional($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
		$arrSituacao[0]   = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrSituacao['N'] = Base_Util::getTranslator('L_VIEW_COMBO_ATIVO');
		$arrSituacao['S'] = Base_Util::getTranslator('L_VIEW_COMBO_INATIVO');

		$this->view->comboSituacao = $arrSituacao;
		
		$arrEmpresa = $this->objEmpresa->getEmpresa(true);
		$this->view->comboEmpresa = $arrEmpresa; 
	}
	
	public function gridPainelProfissionalAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_empresa  = $this->_request->getParam('cd_empresa',0);
		$st_inativo  = $this->_request->getParam('st_inativo',0);
		
		if($cd_empresa != "0" && $st_inativo != "0"){
			$arrDadosProfissional = $this->objProfissional->getProfissionalEmpresa($cd_empresa, $st_inativo);
		} else if($cd_empresa != "0"){
			$arrDadosProfissional = $this->objProfissional->getProfissionalEmpresa($cd_empresa);
		} else if($st_inativo != "0"){
			$arrDadosProfissional = $this->objProfissional->getProfissionalEmpresa(null,$st_inativo);
		} else {
			$arrDadosProfissional = $this->objProfissional->getProfissionalEmpresa();
		}
		$this->view->res = $arrDadosProfissional; 
	}
}