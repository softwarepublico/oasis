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

class ItemParecerTecnicoController extends Base_Controller_Action
{
	private $itemparecertecnico;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ITEM_PARECER_TECNICO'));
		$this->itemparecertecnico = new ItemParecerTecnico($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$form = new ItemParecerTecnicoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
			
			if ($form->isValid($formData)) {

				$novo 			               = $this->itemparecertecnico->createRow();
				$novo->cd_item_parecer_tecnico = $this->itemparecertecnico->getNextValueOfField('cd_item_parecer_tecnico');
				$novo->tx_item_parecer_tecnico = $formData['tx_item_parecer_tecnico'];
				$novo->st_proposta             = ($formData['st_proposta'])?$formData['st_proposta']:null;
				$novo->st_parcela              = ($formData['st_parcela'])?$formData['st_parcela']:null;
				$novo->tx_descricao            = ($formData['tx_descricao'])?$formData['tx_descricao']:null;
				
				if ($novo->save()) {
					$this->_redirect('./item-parecer-tecnico');
				} else {
					// mensagem de erro L_ERRO_???
				}

			} else {
				$form->populate($formData);
			}
		}
	}

	public function editarAction()
	{
		$form = new ItemParecerTecnicoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_item_parecer_tecnico = (int)$form->getValue('cd_item_parecer_tecnico');

				$obj = $this->itemparecertecnico->fetchRow("cd_item_parecer_tecnico= $cd_item_parecer_tecnico");

				$obj->tx_item_parecer_tecnico = $formData['tx_item_parecer_tecnico'];
				$obj->st_proposta             = ($formData['st_proposta'])?$formData['st_proposta']:null;
				$obj->st_parcela              = ($formData['st_parcela'])?$formData['st_parcela']:null;
				$obj->tx_descricao            = $formData['tx_descricao'];
				
				$obj->save();

				$this->_redirect('./item-parecer-tecnico');

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
		
		
			$cd_item_parecer_tecnico= (int)$this->_request->getParam('cd_item_parecer_tecnico', 0);
			if ($cd_item_parecer_tecnico> 0) {
				$itemparecertecnico = new ItemParecerTecnico($this->_request->getControllerName());
				$row   = $itemparecertecnico->fetchRow("cd_item_parecer_tecnico=$cd_item_parecer_tecnico");
				$form->populate($row->toArray());
			}
		}
		
		$this->view->form = $form;

	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_item_parecer_tecnico = (int)$this->_request->getParam('cd_item_parecer_tecnico', 0);
		if ($this->itemparecertecnico->delete("cd_item_parecer_tecnico=$cd_item_parecer_tecnico")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridItemParecerTecnicoAction()
	{

		$this->_helper->layout->disableLayout();
		
		$res = $this->itemparecertecnico->fetchAll(null,"tx_item_parecer_tecnico")->toArray();
		$this->view->res = $res;
	}
}