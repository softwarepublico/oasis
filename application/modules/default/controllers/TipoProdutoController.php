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

class TipoProdutoController extends Base_Controller_Action 
{
	private $objTipoProduto;
	private $objMetrica;
	
	public function init()
	{
		parent::init();
		$this->objTipoProduto = new TipoProduto($this->_request->getControllerName());
		$this->objMetrica     = new DefinicaoMetrica($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_TIPO_PRODUTO'));
		$arrComboMetrica           = $this->objMetrica->getDefinicaoMetrica(true);
		$this->view->comboMetrica  = $arrComboMetrica; 	
	}
	
	public function salvarTipoProdutoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		$return = $this->objTipoProduto->salvarTipoProduto($arrPost);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function alterarTipoProdutoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		$return = $this->objTipoProduto->alterarTipoProduto($arrPost);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		
		echo $msg;
	}
	
	public function excluirTipoProdutoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_tipo_produto = $this->_request->getParam('cd_tipo_produto');
		
		if($this->objTipoProduto->delete("cd_tipo_produto = {$cd_tipo_produto}")){
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function recuperaTipoProdutoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_tipo_produto = $this->_request->getParam('cd_tipo_produto');
		
		$arrDados = $this->objTipoProduto->getDadosTipoProduto(null, $cd_tipo_produto)->toArray();
		
		echo Zend_Json_Encoder::encode($arrDados);
	}
	
	public function gridTipoProdutoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_definicao_metrica = $this->_request->getParam('cd_definicao_metrica');
		
		$arrDados = $this->objTipoProduto->getDadosTipoProduto($cd_definicao_metrica);
		
		$this->view->res = $arrDados;
	}
}