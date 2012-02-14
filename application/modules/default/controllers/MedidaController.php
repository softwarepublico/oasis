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

Class MedidaController extends Base_Controller_Action
{
	private $medida;
	
	public function init()
	{
		parent::init();
		$this->medida = new Medida($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_MENU_MEDIDA'));
		$form = new MedidaForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();

			if ($form->isValid($formData)) {
				$novo 			          = $this->medida->createRow();
				$novo->cd_medida          = $this->medida->getNextValueOfField('cd_medida');
				$novo->tx_medida          = $formData['tx_medida'];
				$novo->tx_objetivo_medida = $formData['tx_objetivo_medida'];
				
				if ($novo->save()) {
					$this->_redirect('./medida');
				} else {
					die(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
				}
			} else {
				$form->populate($formData);
			}
		}
	}
	
	public function editarAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_MENU_MEDIDA'));
		$form = new MedidaForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_medida = (int)$form->getValue('cd_medida');

				$obj = $this->medida->fetchRow("cd_medida = {$cd_medida}");
				
				$obj->tx_medida = $formData['tx_medida'];
				
				$obj->tx_objetivo_medida = $formData['tx_objetivo_medida'];
				
				$obj->save();

				$this->_redirect('./medida');

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
		
			$cd_medida = (int)$this->_request->getParam('cd_medida', 0);
			if ($cd_medida > 0) {
				$row   = $this->medida->fetchRow("cd_medida = {$cd_medida}");
				$form->populate($row->toArray());
			}
		}
		$this->view->form = $form;
	}
	
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_medida = (int)$this->_request->getParam('cd_medida', 0);
		if ($this->medida->delete("cd_medida = {$cd_medida}")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridMedidaAction()
	{
		$this->_helper->layout->disableLayout();
		$select = $this->medida->select()->order("tx_medida");
		$res = $this->medida->fetchAll($select)->toArray();
		$this->view->res = $res;
	}
}