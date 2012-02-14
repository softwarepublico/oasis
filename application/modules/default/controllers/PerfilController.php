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

class PerfilController extends Base_Controller_Action
{
	private $perfil;

	public function init()
	{
		parent::init();
		$this->perfil = new Perfil($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PERFIL'));
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData = $this->_request->getPost();

		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			
			if(!empty($formData['cd_perfil'])) {
				$novo             = $this->perfil->fetchRow("cd_perfil= {$formData['cd_perfil']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo             = $this->perfil->createRow();
				$novo->cd_perfil  = $this->perfil->getNextValueOfField('cd_perfil');
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
			$novo->tx_perfil       = $formData['tx_perfil'];
			
			if($novo->save()){
				$db->commit();
			} else {
				$db->rollBack();
			}
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg'] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function recuperaPerfilAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_perfil = $this->_request->getParam('cd_perfil');
	
		$res = $this->perfil->find($cd_perfil)->current()->toArray();
		
		echo Zend_Json::encode($res);
	}
	
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$objMenuPerfil	= new PerfilMenu($this->_request->getControllerName());
		$comAssociacao	= false;
		$cd_perfil		= (int) $this->_request->getParam('cd_perfil');
		
		//resultset de todos os perfis associados ao menu a ser excluido
		$rowSetMenuPerfil = $objMenuPerfil->getMenuAssociadoAoPerfil( $cd_perfil );
		
		
		if($rowSetMenuPerfil->valid()){
			$comAssociacao = true;
			$msg = $this->montaMensagemPerfil($rowSetMenuPerfil);
		}else{	
			if ($this->perfil->delete("cd_perfil={$cd_perfil}")) {
				$msg = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
			} else {
				$msg = Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
			}
		}
		echo Zend_Json::encode(array($comAssociacao, $msg));
	}
	
	public function gridPerfilAction()
	{
		$this->_helper->layout->disableLayout();
		// recupera o parametro
		$res = $this->perfil->fetchAll(null,"tx_perfil")->toArray();
		$this->view->res = $res;
	}

	private function montaMensagemPerfil($rowSetMenuPerfil)
	{
		$msg 	 = '';
		$qtdMenu = count($rowSetMenuPerfil);
		$count   = 1;
		
		$msg .= "<div style=\"margin-left: 23px;\">";
		if($qtdMenu <= 10){
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_MENU_ASSOCIADO',$qtdMenu);
		}else{
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_MENU_ASSOCIADO_10',$qtdMenu);
		}
		
		foreach($rowSetMenuPerfil as $perfil){
			if($count > 10){
				break;
			}
			$msg .= "&rArr; <b>".Base_Util::getTranslator($perfil->tx_menu)."</b> &rarr; <b>{$perfil->tx_objeto}</b><br/>";
			$count++;
		}
		$msg .= "</div>";
		return $msg;
	}
}