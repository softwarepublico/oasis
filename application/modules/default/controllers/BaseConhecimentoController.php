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

class BaseConhecimentoController extends Base_Controller_Action 
{
	protected $objBaseConhecimento;
	protected $objAreaConhecimento;
	
	public function init()
	{
		parent::init();
		$this->objBaseConhecimento = new BaseConhecimento($this->_request->getControllerName());
		$this->objAreaConhecimento = new AreaConhecimento($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_BASE_CONHECIMENTO'));
		
		$this->view->arrAreaConhecimento = $this->objAreaConhecimento->comboAreaConhecimento();
	}
	
	public function salvarBaseConhecimentoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		//TODO Incluir na tabela o cd_profissional para cadastrar a base do conhecimento
		//$cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];
		//$arrPost['cd_profissional'] = ($cd_profissional != "")?$cd_profissional:null; 
		
		$return = $this->objBaseConhecimento->salvarBaseConhecimento($arrPost);
		$msg = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function gridBaseConhecimentoAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_area_conhecimento = $this->_request->getParam('cd_area_conhecimento',0);
		$tx_assunto           = $this->_request->getParam('tx_assunto');
		$tipo_consulta        = $this->_request->getParam('tipo_consulta');
		
		$cd_area_conhecimento = ($cd_area_conhecimento != 0) ? $cd_area_conhecimento : null;
		$tx_assunto           = (!is_null($tx_assunto)     ) ? $tx_assunto : null;
		
		$arrDados = $this->objBaseConhecimento->getDadosBaseConhecimento($cd_area_conhecimento,$tx_assunto,$tipo_consulta);
		
		$this->view->res = $arrDados;
	}

	public function tabBaseConhecimentoSolucaoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_base_conhecimento = $this->_request->getParam('cd_base_conhecimento');
		$arrDados             = $this->objBaseConhecimento->getSolucaoBaseConhecimento($cd_base_conhecimento);

		$this->view->arrBaseConhecimento = $arrDados;
	}
}