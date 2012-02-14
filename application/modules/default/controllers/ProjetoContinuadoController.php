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

class ProjetoContinuadoController extends Base_Controller_Action
{
	private $projetoContinuado;

	public function init()
	{
		parent::init();
		$this->projetoContinuado = new ProjetoContinuado($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PROJETO_CONTINUADO'));
		
		$form = new ProjetoContinuadoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;

	}

	public function editarAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PROJETO_CONTINUADO'));
		$form = new ProjetoContinuadoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_projeto_continuado = (int)$form->getValue('cd_projeto_continuado');
				$obj = $this->projetoContinuado->fetchRow("cd_projeto_continuado= $cd_projeto_continuado");

				$obj->tx_projeto_continuado          = $formData['tx_projeto_continuado'];
				$obj->tx_sigla_projeto_continuado    = $formData['tx_sigla_projeto_continuado'];
				$obj->cd_objeto                      = $formData['cd_objeto_projeto_continuado'];
				$obj->tx_objetivo_projeto_continuado = $formData['tx_objetivo_projeto_continuado'];
				$obj->tx_obs_projeto_continuado      = ($formData['tx_obs_projeto_continuado'])?$formData['tx_obs_projeto_continuado']:null;
				$obj->st_prioridade_proj_continuado  = $formData['st_prioridade_proj_continuado'];
				
				$obj->save();
			} else {
				// redireciona
				$form->populate($formData);
			}
		}  else {
			$excluir = new Base_Form_Element_Button('bt_excluir');
			$excluir->setAttrib('id', 'bt_excluir');
			$excluir->setAttrib('class', 'vermelho buttonBar');
			$excluir->setLabel(Base_Util::getTranslator('L_BTN_EXCLUIR'));
		    $form->addElement($excluir);

			$cd_projeto_continuado= (int)$this->_request->getParam('cd_projeto_continuado', 0);
			if ($cd_projeto_continuado> 0) {
				$projetoContinuado  = new ProjetoContinuado($this->_request->getControllerName());
				$row   = $projetoContinuado->fetchRow("cd_projeto_continuado={$cd_projeto_continuado}");
				$form->populate($row->toArray());
			}
		}
		
		$this->view->form = $form;
	}

	public function pesquisaProjetoContinuadoAction()
	{
        
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
		$projetoContinuado = new ProjetoContinuado($this->_request->getControllerName());
		$res = $projetoContinuado->getProjetoContinuado($cd_objeto, true);
		
		$strOptions = "";
		foreach ($res as $cd_projeto_continuado => $tx_sigla_projeto_continuado) {
			$strOptions .= "<option value=\"{$cd_projeto_continuado}\">{$tx_sigla_projeto_continuado}</option>";			
		}
		
		echo $strOptions;
	}
	
	public function salvarProjetoContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrProjetoCont = $this->_request->getPost();
		$return = $this->projetoContinuado->salvarProjetoContinuado($arrProjetoCont);
		
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
	}

	public function alterarProjetoContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrProjetoCont = $this->_request->getPost();
		
		$return = $this->projetoContinuado->alterarProjetoContinuado($arrProjetoCont);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		echo $msg;
	}
	
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$objModuloContinuado = new ModuloContinuado($this->_request->getControllerName());
		
		$cd_projeto_continuado = (int)$this->_request->getParam('cd_projeto_continuado', 0);
		$arrModuloContinuado = $objModuloContinuado->fetchAll("cd_projeto_continuado = {$cd_projeto_continuado}")->toArray();
		if(count($arrModuloContinuado) == 0){				
			if ($this->projetoContinuado->delete("cd_projeto_continuado=$cd_projeto_continuado")) {
				echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
			} else {
				echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
			}
		} else {
			echo Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO');
		}
    }

	public function gridProjetoContinuadoAction()
	{
		$this->_helper->layout->disableLayout();
		$cd_objeto = (int)$this->_request->getParam('cd_objeto');
		
		$arrProjetoContinuado = $this->projetoContinuado->getDadosProjetoContinuado(null,$cd_objeto);
		
		foreach($arrProjetoContinuado as $key=>$value){
			$value['tx_objetivo_projeto_continuado'] = strip_tags($value['tx_objetivo_projeto_continuado']);		
		}
		
		$this->view->res = $arrProjetoContinuado;
	}
	
	public function recuperaProjetoContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$cd_projeto_continuado = (int)$this->_request->getParam('cd_projeto_continuado');
		$arrProjetoContinuado = $this->projetoContinuado->getDadosProjetoContinuado($cd_projeto_continuado,null);
		foreach($arrProjetoContinuado as $key=>$value){
			$arrProjetoContinuado[$key]['tx_objetivo_projeto_continuado'] = str_ireplace('\"','"',$value['tx_objetivo_projeto_continuado']);		
		}
		
		echo Zend_Json::encode($arrProjetoContinuado);
	}
}