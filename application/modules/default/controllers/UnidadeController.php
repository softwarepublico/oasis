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

Class UnidadeController extends Base_Controller_Action
{
	private $unidade;
	
	public function init()
	{
		parent::init();
		$this->unidade = new Unidade($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_UNIDADE'));

		$form = new UnidadeForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();

			if ($form->isValid($formData)) {
				$novo 			        = $this->unidade->createRow();
				$novo->cd_unidade       = $this->unidade->getNextValueOfField('cd_unidade');
				$novo->tx_sigla_unidade = $formData['tx_sigla_unidade'];
				
				if ($novo->save()) {
					$this->_redirect('./unidade');
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
        $this->view->headTitle(Base_Util::setTitle('L_TIT_UNIDADE'));
        
		$form = new UnidadeForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_unidade = (int)$form->getValue('cd_unidade');

				$obj = $this->unidade->fetchRow("cd_unidade = $cd_unidade");
				
				$obj->tx_sigla_unidade           = $formData['tx_sigla_unidade'];
				
				$obj->save();

				$this->_redirect('./unidade');

			} else {
				// redireciona
				$form->populate($formData);
			}
		}  else {
			$excluir = new Base_Form_Element_Button('bt_excluir');
			$excluir->setAttrib('id', 'bt_excluir');
			$excluir->setAttrib('class', 'buttonBar vermelho');
			$excluir->setLabel(Base_Util::getTranslator('L_BTN_EXCLUIR') );
		    $form->addElement($excluir);
		
			$cd_unidade = (int)$this->_request->getParam('cd_unidade', 0);
			if ($cd_unidade > 0) {
				$row   = $this->unidade->fetchRow("cd_unidade = {$cd_unidade}");
				$form->populate($row->toArray());
			}
		}
		$this->view->form = $form;
		
		//$this->gridUnidadeAction($row['cd_unidade']);
		
		
	}
	
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_unidade = (int)$this->_request->getParam('cd_unidade', 0);
		if ($this->unidade->delete("cd_unidade = {$cd_unidade}")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridUnidadeAction()
	{
		$this->_helper->layout->disableLayout();
		$res = $this->unidade->fetchAll(null,"tx_sigla_unidade")->toArray();
		$this->view->res = $res;
	}
}
