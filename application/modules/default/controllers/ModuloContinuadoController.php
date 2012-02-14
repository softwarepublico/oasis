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

class ModuloContinuadoController extends Base_Controller_Action
{
	private $moduloContinuado;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_MODULO_CONTINUADO'));
		$this->moduloContinuado = new ModuloContinuado($this->_request->getControllerName());
	}

	public function indexAction()
	{

		$form = new ModuloContinuadoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;

		// Caso receba o cd_projeto_continuado, ele mostra o combo Projeto Continuado selecionado com o valor enviado
		if (!is_null($this->_request->getParam('cd_objeto')) && !is_null($this->_request->getParam('cd_projeto_continuado'))) {
			$formData['cd_objeto']             = $this->_request->getParam('cd_objeto');
			$formData['cd_projeto_continuado'] = $this->_request->getParam('cd_projeto_continuado');
			$form->populate($formData);
			// Busca os dados dos combos-filhos para monta-los e posteriormente preenche-lo com o valor selecionado
			$projetoContinuado = new ProjetoContinuado($this->_request->getControllerName());
			$arrProjetoContinuado = $projetoContinuado->getProjetoContinuado($formData['cd_objeto'], true);
			$form->cd_projeto_continuado->addMultiOptions($arrProjetoContinuado);
			$this->view->grid = $this->montaGrid($formData['cd_projeto_continuado']);
		}
				
		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
				
			if ($form->isValid($formData)) {
					
				$novo 				             		= $this->moduloContinuado->createRow();
				$novo->cd_modulo_continuado	            = $this->moduloContinuado->getNextValueOfField('cd_modulo_continuado');
				$novo->tx_modulo_continuado             = $formData['tx_modulo_continuado'];
				$novo->cd_objeto                        = $formData['cd_objeto'];
				$novo->cd_projeto_continuado            = $formData['cd_projeto_continuado'];

				if ($novo->save()) {
					$this->_redirect("./modulo-continuado/index/cd_objeto/{$formData['cd_objeto']}/cd_projeto_continuado/{$formData['cd_projeto_continuado']}");
				} else {
					// mensagem de erro L_ERRO_????
				}

			} else {
				$form->populate($formData);
			}
		}
	}

	public function editarAction()
	{
		$form = new ModuloContinuadoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_modulo_continuado = (int)$form->getValue('cd_modulo_continuado');

				$obj = $this->moduloContinuado->fetchRow("cd_modulo_continuado= $cd_modulo_continuado");

				$obj->tx_modulo_continuado             = $formData['tx_modulo_continuado'];
				$obj->cd_objeto                        = $formData['cd_objeto'];
				$obj->cd_projeto_continuado            = $formData['cd_projeto_continuado'];


				$obj->save();

				$this->_redirect("./modulo-continuado/index/cd_objeto/{$formData['cd_objeto']}/cd_projeto_continuado/{$formData['cd_projeto_continuado']}");

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

			$cd_modulo_continuado= (int)$this->_request->getParam('cd_modulo_continuado', 0);
			if ($cd_modulo_continuado> 0) {
				$moduloContinuado  = new ModuloContinuado($this->_request->getControllerName());
				$row   = $moduloContinuado->fetchRow("cd_modulo_continuado=$cd_modulo_continuado");

				$rowDados = $row->toArray();
				$form->populate($row->toArray($rowDados));
				
				// Busca os dados dos combos-filhos para monta-los e posteriormente preenche-lo com o valor selecionado
				$projetoContinuado = new ProjetoContinuado($this->_request->getControllerName());
				$arrProjetoContinuado = $projetoContinuado->getProjetoContinuado($rowDados['cd_objeto'], true);
				$form->cd_projeto_continuado->addMultiOptions($arrProjetoContinuado);
			}
				
			$this->view->grid = $this->montaGrid($rowDados['cd_projeto_continuado']);
				
		}

		$this->view->form = $form;

	}
	
	public function pesquisaModuloContinuadoAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto_continuado = (int)$this->_request->getParam('cd_projeto_continuado', 0);
		$moduloContinuado = new ModuloContinuado($this->_request->getControllerName());
		$res = $moduloContinuado->getModuloContinuado($cd_projeto_continuado);

		$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		foreach ($res as $cd_modulo_continuado => $tx_modulo_continuado) {
			$strOptions .= "<option value=\"{$cd_modulo_continuado}\">{$tx_modulo_continuado}</option>";
		}

		echo $strOptions;

	}
	
	public function salvarModuloContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$return = $this->moduloContinuado->salvarModuloContinuado($arrDados);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function alterarModuloContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$return = $this->moduloContinuado->alterarModuloContinuado($arrDados);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		echo $msg;		
		
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$objHistoricoContinuado = new HistoricoProjetoContinuado($this->_request->getControllerName());
		
		$cd_modulo_continuado  = (int)$this->_request->getParam('cd_modulo_continuado', 0);
		$cd_projeto_continuado = (int)$this->_request->getParam('cd_projeto_continuado', 0);

		$arrHistoricoContinuado = $objHistoricoContinuado->fetchAll("cd_modulo_continuado = {$cd_modulo_continuado} and cd_projeto_continuado = {$cd_projeto_continuado}")->toArray();
		if(count($arrHistoricoContinuado) == 0){	
			if ($this->moduloContinuado->delete("cd_modulo_continuado={$cd_modulo_continuado}")) {
				echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
			} else {
				echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
			}
		} else {
			echo Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO');
		}
	}
	
	public function recuperarModuloContinuadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_modulo_continuado = (int)$this->_request->getParam('cd_modulo_continuado', 0);
		$arrDados = $this->moduloContinuado->recuperaModuloContinuado(null,null,$cd_modulo_continuado);
		
		echo Zend_Json::encode($arrDados);
	}
	
	public function gridModuloContinuadoAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto_continuado = (int)$this->_request->getParam('cd_projeto_continuado', 0);		
		$cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);

		$arrModuloContinuo = $this->moduloContinuado->recuperaModuloContinuado($cd_projeto_continuado,$cd_objeto);
		
		$this->view->res = $arrModuloContinuo; 
	}
}