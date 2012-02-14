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

class RelacaoContratualController extends Base_Controller_Action
{
	private $relacaocontratual;

	public function init()
	{
		parent::init();
		$this->relacaocontratual = new RelacaoContratual($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_RELACAO_CONTRATUAL'));
		
		$form = new RelacaoContratualForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
			
			if ($form->isValid($formData)) {

				$novo 			             = $this->relacaocontratual->createRow();
				$novo->cd_relacao_contratual =	$this->relacaocontratual->getNextValueOfField('cd_relacao_contratual');
				$novo->tx_relacao_contratual = $formData['tx_relacao_contratual'];
				
				if ($novo->save()) {
					$this->_redirect('./relacao-contratual');
				} else {
					//TODO  mensagem de erro
				}

			} else {
				$form->populate($formData);
			}
		}
	}

	public function editarAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_RELACAO_CONTRATUAL'));
		$form = new RelacaoContratualForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_relacao_contratual = (int)$form->getValue('cd_relacao_contratual');

				$obj = $this->relacaocontratual->fetchRow("cd_relacao_contratual= $cd_relacao_contratual");

				$obj->tx_relacao_contratual = $formData['tx_relacao_contratual'];

				
				$obj->save();

				$this->_redirect('./relacao-contratual');

			} else {
				// redireciona
				$form->populate($formData);
			}
		}  else {
			$excluir = new Base_Form_Element_Button('bt_excluir');
			$excluir->setAttrib('id', 'bt_excluir'); 
			$excluir->setAttrib('class', 'buttonBar vermelho');
			$excluir->setLabel(Base_Util::getTranslator('L_BTN_EXCLUIR'));
		    $form->addElement($excluir);

			$cd_relacao_contratual= (int)$this->_request->getParam('cd_relacao_contratual', 0);
			if ($cd_relacao_contratual> 0) {
				$relacaocontratual = new RelacaoContratual($this->_request->getControllerName());
				$row   = $relacaocontratual->fetchRow("cd_relacao_contratual=$cd_relacao_contratual");
				$form->populate($row->toArray());
			}
		}
		$this->view->form = $form;
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_relacao_contratual = (int)$this->_request->getParam('cd_relacao_contratual', 0);
		if ($this->relacaocontratual->delete("cd_relacao_contratual=$cd_relacao_contratual")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

	public function gridRelacaoContratualAction()
	{
		$this->_helper->layout->disableLayout();
		
		// realiza a consulta
		$res = $this->relacaocontratual->fetchAll()->toArray();
		$this->view->res = $res;
	}
}