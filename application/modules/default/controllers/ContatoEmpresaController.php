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

class ContatoEmpresaController extends Base_Controller_Action
{
	private $contatoEmpresa;
	private $empresa;
	private $objContrato;
	
	public function init()
	{
		parent::init();
		$this->contatoEmpresa = new ContatoEmpresa($this->_request->getControllerName());
		$this->empresa		  = new Empresa($this->_request->getControllerName());
		$this->objContrato	  = new Contrato($this->_request->getControllerName());
		
		$this->view->addHelperPath('Base/View/Helper', 'Base_View_Helper');
		$this->_helper->addPath('Base/Controller/Action/Helper', 'Base_Controller_Action_Helper');
		
	}

	public function indexAction()
	{	
        $this->view->headTitle(Base_Util::setTitle('L_TIT_CONTATO_EMPRESA'));
		
		// Caso receba o cd_empresa, ele mostra o combo empresa selecionado com o valor enviado
		if (!is_null($this->_request->getParam('cd_empresa'))) {
			$formData['cd_empresa'] = $this->_request->getParam('cd_empresa');
			$form->populate($formData);
		}
				
		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
			
			if ($form->isValid($formData)) {

				$novo 			           = $this->contatoEmpresa->createRow();
				$novo->cd_contato_empresa  = $this->contatoEmpresa->getNextValueOfField('cd_contato_empresa');
				$novo->cd_empresa          = $formData['cd_empresa'];
				$novo->tx_contato_empresa  = $formData['tx_contato_empresa'];
				$novo->tx_telefone_contato = $formData['tx_telefone_contato'];
				$novo->tx_email_contato    = ($formData['tx_email_contato'] != "")?$formData['tx_email_contato']:null;
				$novo->tx_celular_contato  = ($formData['tx_celular_contato'] != "0")?$formData['tx_celular_contato']:null;
				$novo->tx_obs_contato      = ($formData['tx_obs_contato'] != "")?$formData['tx_obs_contato']:null;
				
				if ($novo->save()) {
					$this->_redirect("./contato-empresa/index/cd_empresa/{$formData['cd_empresa']}");
				} else {
					// mensagem de erro  //TODO L_ERRO_???????
				}

			} else {
				$form->populate($formData);
			}
		}
	}

	public function getEmpresasAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		
		$arrEmpresa = $this->empresa->getEmpresa(true);
		
		$strOption = '';
		foreach( $arrEmpresa as $key=>$value ){
			$strOption .= "<option value=\"{$key}\">{$value}</option>";
		}
		echo $strOption;
	}
	
	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData 	= $this->_request->getPost();
		
		$arrResult	= array('erro'=>false,'type'=>1, 'msg'=>'');
		
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			
			if(!empty($formData['cd_contato_empresa'])) {
				$novo             = $this->contatoEmpresa->fetchRow("cd_contato_empresa = {$formData['cd_contato_empresa']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo             		  = $this->contatoEmpresa->createRow();
				$novo->cd_contato_empresa = $this->contatoEmpresa->getNextValueOfField('cd_contato_empresa');
				$arrResult['msg'] 		  = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
			
			$novo->cd_empresa          =  $formData['cd_empresa_contato_empresa'];
			$novo->tx_contato_empresa  =  $formData['tx_contato_empresa'];
			$novo->tx_telefone_contato =  $formData['tx_telefone_contato'];
			$novo->tx_email_contato    = ($formData['tx_email_contato'] != "")   ? $formData['tx_email_contato'  ]:null;
			$novo->tx_celular_contato  = ($formData['tx_celular_contato'] != "0")? $formData['tx_celular_contato']:null;
			$novo->tx_obs_contato      = ($formData['tx_obs_contato'] != "")     ? $formData['tx_obs_contato'    ]:null;
			
			($novo->save()) ? $db->commit() : $db->rollBack();
			
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
	
	public function recuperarContatoEmpresaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contato_empresa = (int) $this->_request->getParam('cd_contato_empresa');
	
		$res = $this->contatoEmpresa->find($cd_contato_empresa)->current()->toArray();
		
		echo Zend_Json::encode($res);
	}
	
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contato_empresa = (int)$this->_request->getParam('cd_contato_empresa');
		
		//resultset de todos os perfis associados ao menu a ser excluido
		$arrContrato = $this->objContrato->fetchAll(
													$this->objContrato->select()
																	  ->from($this->objContrato, 'tx_numero_contrato')
																	  ->where("cd_contato_empresa = {$cd_contato_empresa}")
																	  ->order("{$this->objContrato->substringOrderNumeroContrato()}")
													)->toArray();

		$comAssociacao	= false;
		if(count($arrContrato) > 0){
			$comAssociacao = true;
			$msg = $this->montaMensagemContrato($arrContrato);
		}else{
			if ($this->contatoEmpresa->delete("cd_contato_empresa = {$cd_contato_empresa}")) {
				$msg = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
			} else {
				$msg = Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
			}
		}
		echo Zend_Json::encode(array($comAssociacao, $msg));
	}

	private function montaMensagemContrato($arrContrato)
	{
		$msg		 = '';
		$qtdContrato = count($arrContrato);
		$count		 = 1;
		
		$msg .= "<div style=\"margin-left: 23px;\">";
		if($qtdContrato <= 10){
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_CONTATO_ASSOCIADO_CONTRATO', $qtdContrato);
		}else{
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_CONTATO_ASSOCIADO_CONTRATO_10', $qtdContrato);
		}
		
		foreach($arrContrato as $contrato){
			if($count > 10){
				break;
			}
			$msg .= "&rArr; ".Base_Util::getTranslator('L_VIEW_CONTRATO').": <b>{$contrato['tx_numero_contrato']}</b><br/>";
			$count++;
		}
		$msg .= "</div>";
		
		return $msg;
	}
	
	public function pesquisaContatoEmpresaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_empresa = (int)$this->_request->getParam('cd_empresa', 0);
		$contatoEmpresa = new ContatoEmpresa($this->_request->getControllerName());
		$res = $contatoEmpresa->getContatoEmpresa($cd_empresa);

		$strOptions = "<option value=\"\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		foreach ($res as $cd_contato_empresa => $tx_contato_empresa) {
			$strOptions .= "<option value=\"{$cd_contato_empresa}\">{$tx_contato_empresa}</option>";
		}

		echo $strOptions;

	}	
	
	public function gridContatoEmpresaAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_empresa 	 = (int)$this->_request->getParam('cd_empresa', 0);
		$res        	 = $this->contatoEmpresa->getContatoEmpresa($cd_empresa);
		$this->view->res = $res;
	}
}