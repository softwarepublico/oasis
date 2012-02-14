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

class CasoDeUsoAtorController extends Base_Controller_Action
{
	private $ator;

	public function init()
	{
		parent::init();
		$this->ator  = new Ator($this->_request->getControllerName());
	}

	public function indexAction()
	{}
	
	public function salvaAtorProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$tx_ator    = $this->_request->getParam('tx_ator');
		$cd_projeto = $this->_request->getParam('cd_projeto');
	
		$res = $this->ator->salvaAtor($cd_projeto, $tx_ator);
		
		$msg = ($res) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		
		echo $msg;
	}
	
	public function pesquisaAtorProjetoAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');
		// realiza a consulta
		$res = $this->ator->pesquisaAtorProjeto($cd_projeto,true);

		$this->view->res = $res;
	}
	
	public function recuperaAtorProjetoAction(){
	
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_ator = $this->_request->getParam('cd_ator');
	
		$res = $this->ator->recuperaAtorProjeto($cd_ator,true);
		
		$ator = $res[0]['tx_ator'];
		
		echo $ator;
	}

	public function alteraAtorProjetoAction(){
	
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$tx_ator  = $this->_request->getParam('tx_ator');
		$cd_ator  = $this->_request->getParam('cd_ator');
		$cd_projeto = $this->_request->getParam('cd_projeto');
		
	
		$res = $this->ator->alteraAtorProjeto($cd_projeto, $cd_ator, $tx_ator);
		
		$msg = ($res) ? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO') : Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		
		echo $msg;

	}
	
	public function excluirAtorProjetoAction()
	{
		$objInteracao = new Interacao($this->_request->getControllerName());
		
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();		
		$cd_ator  = $this->_request->getParam('cd_ator');

		$arrInteracao = $objInteracao->recuperaInteracao(null,null,$cd_ator);
		if(!$arrInteracao){
			$res = $this->ator->excluirAtorProjeto($cd_ator);
			$msg = ($res) ? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
			echo $msg;
		} else {
			echo Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_INTERACOES');
		}
	}
	
	public function comboAtorAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();		
		$cd_projeto  = $this->_request->getParam('cd_projeto');
		
		$arrComboAtor = $this->ator->getAtor(true, $cd_projeto);

		$strOption = "";
		if(count($arrComboAtor) > 0){
			foreach($arrComboAtor as $key=>$value){
				$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
			}
		}
		echo $strOption;
	}
}