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

class ConhecimentoController extends Base_Controller_Action
{
	private $conhecimento;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_CONHECIMENTO'));

		$this->conhecimento = new Conhecimento($this->_request->getControllerName());
	}

	public function indexAction()
	{	
		$form = new ConhecimentoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
		
		// Caso receba o cd_tipo_conhecimento, ele mostra o combo Conhecimento selecionado com o valor enviado
		if (!is_null($this->_request->getParam('cd_tipo_conhecimento'))) {
			$formData['cd_tipo_conhecimento'] = $this->_request->getParam('cd_tipo_conhecimento');
			$form->populate($formData);
		}
		
		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
			
			if ($form->isValid($formData)) {

				$novo 				         = $this->conhecimento->createRow();
				$novo->cd_conhecimento		 = $this->conhecimento->getNextValueOfField('cd_conhecimento');
				$novo->cd_tipo_conhecimento  = $formData['cd_tipo_conhecimento'];
				$novo->tx_conhecimento       = $formData['tx_conhecimento'];
				$novo->st_padrao             = $formData['st_padrao'];

				if ($novo->save()) {
					$this->_redirect("./conhecimento/index/cd_tipo_conhecimento/{$formData['cd_tipo_conhecimento']}");
				} else {
					// mensagem de erro //TODO L_ERRO??????
				}
			} else {
				$form->populate($formData);
			}
		}
	}

	public function editarAction()
	{
		$form = new ConhecimentoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_conhecimento = (int)$form->getValue('cd_conhecimento');

				$obj = $this->conhecimento->fetchRow("cd_conhecimento= $cd_conhecimento");

				$obj->cd_tipo_conhecimento  = $formData['cd_tipo_conhecimento'];
				$obj->tx_conhecimento       = $formData['tx_conhecimento'];
				$obj->st_padrao             = $formData['st_padrao'];

				$obj->save();

				$this->_redirect("./conhecimento/index/cd_tipo_conhecimento/{$formData['cd_tipo_conhecimento']}");

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

			$cd_conhecimento= (int)$this->_request->getParam('cd_conhecimento', 0);
			if ($cd_conhecimento> 0) {
				$conhecimento = new Conhecimento($this->_request->getControllerName());
				$row   = $conhecimento->fetchRow("cd_conhecimento={$cd_conhecimento}");
				$rowDados = $row->toArray();
				$form->populate($rowDados);
			}
		}
		$this->view->form = $form;
	}
	
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_conhecimento = (int)$this->_request->getParam('cd_conhecimento', 0);
		if ($this->conhecimento->delete(array('cd_conhecimento = ?'=>$cd_conhecimento))) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

	public function gridConhecimentoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_tipo_conhecimento = $this->_request->getParam('cd_tipo_conhecimento');
		
		$arrDados = $this->conhecimento->getDadosConhecimento($cd_tipo_conhecimento);
		
		$this->view->res = $arrDados;
	}
}