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

class InformacaoTecnicaController extends Base_Controller_Action
{
	private $informacaoTecnica;

	public function init()
	{
		parent::init();
		$this->informacaoTecnica = new InformacaoTecnica($this->_request->getControllerName());
        $this->view->headTitle(Base_Util::setTitle('L_TIT_INFORMACOES_TECNICAS'));
	}

	public function indexAction()
	{
		
		$form = new InformacaoTecnicaForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
		
		// Caso receba o cd_etapa, ele mostra o combo Etapa selecionado com o valor enviado
		if (!is_null($this->_request->getParam('cd_projeto'))) {
			$formData['cd_projeto'] = $this->_request->getParam('cd_projeto');
			$form->populate($formData);
			$this->view->grid = $this->montaGrid($formData['cd_projeto']);
		}
		
		
		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
			
			if ($form->isValid($formData)) {
			
				$novo 				                  = $this->informacaoTecnica->createRow();
				$novo->cd_projeto                     = $formData['cd_projeto'];
				$novo->cd_tipo_dado_tecnico           = $formData['cd_tipo_dado_tecnico'];
				$novo->tx_conteudo_informacao_tecnica = $formData['tx_conteudo_informacao_tecnica'];

				if ($novo->save()) {
					$this->_redirect("./informacao-tecnica/index/cd_projeto/{$formData['cd_projeto']}");
				} else {
					// mensagem de erro //TODO L_ERRO_?????
				}

			} else {
				$form->populate($formData);
			}
		}
	}

	public function editarAction()
	{
		$form = new InformacaoTecnicaForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_projeto           = (int)$form->getValue('cd_projeto');
				$cd_tipo_dado_tecnico = (int)$form->getValue('cd_tipo_dado_tecnico');				

				$obj = $this->informacaoTecnica->fetchRow("cd_projeto= $cd_projeto and cd_tipo_dado_tecnico = $cd_tipo_dado_tecnico");

				$obj->tx_conteudo_informacao_tecnica = $formData['tx_conteudo_informacao_tecnica'];
				
				
				$obj->save();

				$this->_redirect("./informacao-tecnica/index/cd_projeto/{$formData['cd_projeto']}");

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

			$cd_projeto           = (int)$this->_request->getParam('cd_projeto', 0);
			$cd_tipo_dado_tecnico = (int)$this->_request->getParam('cd_tipo_dado_tecnico', 0);
			if ($cd_projeto > 0 && $cd_tipo_dado_tecnico > 0) {
				$informacaoTecnica  = new InformacaoTecnica($this->_request->getControllerName());
				$row      = $informacaoTecnica->fetchRow("cd_projeto= $cd_projeto and cd_tipo_dado_tecnico = $cd_tipo_dado_tecnico");
				$rowDados = $row->toArray();
				$form->populate($row->toArray($rowDados));
			}
			$this->view->grid = $this->montaGrid($cd_projeto);
		}
		
		$this->view->form = $form;
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto           = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_tipo_dado_tecnico = (int)$this->_request->getParam('cd_tipo_dado_tecnico', 0);
		
		if ($this->informacaoTecnica->delete("cd_projeto= $cd_projeto and cd_tipo_dado_tecnico = $cd_tipo_dado_tecnico")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function listarAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		// recupera o parametro
		$cd_projeto = (int)$this->_request->getParam('cd_projeto', 0);
		echo $this->montaGrid($cd_projeto);
	}
	
	private function montaGrid($cd_projeto)
	{
		// realiza a consulta
		$select = $this->informacaoTecnica->select();
		$select->where("cd_projeto = $cd_projeto");

		$res = $this->informacaoTecnica->fetchAll($select);

		// monta a grid
		$strGrid = "<table>
	    <tr>
			<td>".Base_Util::getTranslator('L_TIT_INFORMACOES_TECNICAS')."</td>
		</tr>";
		foreach ($res as $informacaoTecnica):

		$tipoDadoTecnico  = new TipoDadoTecnico($this->_request->getControllerName());
		$row              = $tipoDadoTecnico->fetchRow("cd_tipo_dado_tecnico= {$informacaoTecnica->cd_tipo_dado_tecnico}");
				
		$strGrid .= "<tr>
		<td>"
		. $row->tx_tipo_dado_tecnico
		. "</td>
		<td>
		<a href=\"{$this->_helper->BaseUrl->baseUrl()}/informacao-tecnica/editar/cd_projeto/"
		. $informacaoTecnica->cd_projeto
		. "/cd_tipo_dado_tecnico/" 
		. $informacaoTecnica->cd_tipo_dado_tecnico
		. "\">" . $informacaoTecnica->tx_conteudo_informacao_tecnica
		. " </a>
		</td>
		</tr>";
		endforeach;

		$strGrid .= "</table><br />";

		// retorna a string da grid para o ajax
		return $strGrid;
	}
}