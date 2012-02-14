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

class StatusController extends Base_Controller_Action
{
	private $status;

	public function init()
	{
		parent::init();
		$this->status = new Status($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_STATUS'));

		$form = new StatusForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;

		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
				
			if ($form->isValid($formData)) {

				$novo 			  =  $this->status->createRow();
				$novo->cd_status  =	 $this->status->getNextValueOfField('cd_status');
				$novo->tx_status  =  $formData['tx_status'];


				if ($novo->save()) {
					$this->_redirect('./status');
				} else {
					//TODO mensagem de erro L_ERRO_?????
				}

			} else {
				$form->populate($formData);
			}
		}
	}

	public function editarAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_STATUS'));
		$form = new StatusForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_status = (int)$form->getValue('cd_status');

				$obj = $this->status->fetchRow("cd_status= $cd_status");

				$obj->tx_status = $formData['tx_status'];


				$obj->save();

				$this->_redirect('./status');

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

			$cd_status= (int)$this->_request->getParam('cd_status', 0);
			if ($cd_status> 0) {
				$status = new Status($this->_request->getControllerName());
				$row   	= $status->fetchRow("cd_status=$cd_status");
				$form->populate($row->toArray());
			}
		}
		$this->view->form = $form;
	}

	public function excluirAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_status = (int)$this->_request->getParam('cd_status', 0);
		if ($this->status->delete("cd_status=$cd_status")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

	public function gridStatusAction()
	{
		$this->_helper->layout->disableLayout();
		
		// realiza a consulta
		$res = $this->status->fetchAll(null,"tx_status")->toArray();
		$this->view->res = $res;
	}

//	private function montaGrid()
//	{
//
//		// realiza a consulta
//		$res = $this->status->fetchAll();
//
//		// monta a grid
//		$strGrid = "<table>
//	    <tr>
//			<td>STATUS</td>
//		</tr>";
//		foreach ($res as $status):
//
//		$strGrid .= "<tr>
//		<td>
//		<a href=\"{$this->_helper->BaseUrl->baseUrl()}/status/editar/cd_status/"
//		. $status->cd_status
//		. "\">" . $status->tx_status
//		. " </a>
//		</td>
//		</tr>";
//		endforeach;
//
//		$strGrid .= "</table><br />";
//
//
//		// retorna a string da grid para o ajax
//		return $strGrid;
//
//	}
}