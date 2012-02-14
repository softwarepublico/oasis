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

class SolicitacaoController extends Base_Controller_Action
{
	private $solicitacao;
	private $form;

	public function init()
	{
		parent::init();
		$this->solicitacao = new Solicitacao($this->_request->getControllerName());
		
		// Definindo o form e os displays groups
		$this->form = new SolicitacaoForm();
		$this->form->addDisplayGroup(array('cd_objeto', 'st_solicitacao','cd_documento_origem','cadorigem'),"primeira");
		$this->form->addDisplayGroup(array('tx_solicitante', 'cd_unidade', 'tx_sala_solicitante','tx_telefone_solicitante', 'tx_solicitacao'), 'segunda');
		$this->form->addDisplayGroup(array('ni_prazo_atendimento', 'tx_obs_solicitacao', 'ni_solicitacao', 'ni_solicitacao', 'submit'),"terceira");
		$this->form->getDisplayGroup('segunda')->setLegend('Gestor Solicitante');
	}

	public function indexAction()
	{
		$this->form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $this->form;

		if ($this->_request->isPost()) {

			$this->formData = $this->_request->getPost();
			if ($this->form->isValid($this->formData)) {
			
				$ano 						     = date("Y");
				$novo 				             = $this->solicitacao->createRow();
				$novo->ni_ano_solicitacao        = $ano;
				$novo->cd_objeto                 = $this->formData['cd_objeto'];
				$novo->ni_solicitacao	         = $this->solicitacao->getNewValueByObjeto($this->formData['cd_objeto'], $ano);
				$novo->dt_solicitacao            = date('Y-m-d H:i:s');
				$novo->st_solicitacao            = $this->formData['st_solicitacao'];
				$novo->tx_solicitante   	     = $this->formData['tx_solicitante'];
				$novo->tx_sala_solicitante       = $this->formData['tx_sala_solicitante'];
				$novo->tx_telefone_solicitante   = $this->formData['tx_telefone_solicitante'];
				$novo->cd_unidade                = $this->formData['cd_unidade'];
				$novo->tx_solicitacao            = $this->formData['tx_solicitacao'];
				$novo->tx_obs_solicitacao 	     = $this->formData['tx_obs_solicitacao'];
				$novo->ni_prazo_atendimento      = $this->formData['ni_prazo_atendimento'];
                if ($this->formData['cd_documento_origem']==0) {
                    $novo->cd_documento_origem       = null;
                }else{
                    
                    $novo->cd_documento_origem       = $this->formData['cd_documento_origem'];
                }

				if ($novo->save()) {
					if (K_ENVIAR_EMAIL == 'S'){
						$_mail = new Base_Controller_Action_Helper_Mail();
						$arrDados['_solicitacao'] = "{$novo->ni_solicitacao}/$ano";
						$_mail->enviaEmail($this->formData['cd_objeto'],$this->_request->getControllerName(),$arrDados);
					}
					$this->_redirect('./solicitacao-servico');
				} 
			} else {
				$this->form->populate($this->formData);
			}
		}
	}

	public function editarAction()
	{
		$this->form->submit->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
		if ($this->_request->isPost()) {
			$this->formData = $this->_request->getPost();
			if ($this->form->isValid($this->formData)) {
				$this->formData['submit'];
				
				$ni_solicitacao     = (int)$this->form->getValue('ni_solicitacao');
				$ni_ano_solicitacao = (int)$this->form->getValue('ni_ano_solicitacao');
				$cd_objeto          = (int)$this->form->getValue('cd_objeto');

				$obj = $this->solicitacao->fetchRow("ni_solicitacao     = $ni_solicitacao and 
													 ni_ano_solicitacao = $ni_ano_solicitacao and
													 cd_objeto          = $cd_objeto");

				$obj->st_solicitacao            = $this->formData['st_solicitacao'];
				$obj->tx_solicitante   	        = $this->formData['tx_solicitante'];
				$obj->tx_sala_solicitante       = $this->formData['tx_sala_solicitante'];
				$obj->tx_telefone_solicitante   = $this->formData['tx_telefone_solicitante'];
				$obj->cd_unidade                = $this->formData['cd_unidade'];
				$obj->tx_solicitacao            = $this->formData['tx_solicitacao'];
				$obj->tx_obs_solicitacao 	    = $this->formData['tx_obs_solicitacao'];
				$obj->ni_prazo_atendimento      = $this->formData['ni_prazo_atendimento'];
				
                if ($this->formData['cd_documento_origem']==0) {
                    $obj->cd_documento_origem       = null;
                }else{
                    $obj->cd_documento_origem       = $this->formData['cd_documento_origem'];
                }
				
				$obj->save();

				$this->_redirect("./solicitacao-servico");

			} else {
				// redireciona
				$this->form->populate($this->formData);
			}
		}  else {
			$excluir = new Zend_Form_Element_Button('bt_excluir');
			$excluir->setAttrib('id', 'bt_excluir');
			$excluir->setAttrib('class', 'vermelho buttonBar');
			$excluir->setLabel(Base_Util::getTranslator('L_BTN_EXCLUIR'));
		    $this->form->addElement($excluir);
			$ni_solicitacao      = (int)$this->_request->getParam('ni_solicitacao', 0);
			$ni_ano_solicitacao  = (int)$this->_request->getParam('ni_ano_solicitacao', 0);
			$cd_objeto           = (int)$this->_request->getParam('cd_objeto', 0);
			if ($ni_solicitacao > 0 && $ni_ano_solicitacao > 0 && $cd_objeto > 0) {
				$solicitacao  = new Solicitacao($this->_request->getControllerName());
				$row   = $solicitacao->fetchRow("ni_solicitacao     = $ni_solicitacao and 
												 ni_ano_solicitacao = $ni_ano_solicitacao and
												 cd_objeto          = $cd_objeto");
				$row->tx_solicitacao = str_ireplace('\"','"',$row->tx_solicitacao);
				$this->form->populate($row->toArray());

				// Popula o combo de objeto somente com o objeto selecionado
				$objetoContrato    = new ObjetoContrato($this->_request->getControllerName());
				$arrObjContrato    = $objetoContrato->getObjeto($cd_objeto);
				$this->form->cd_objeto->addMultiOptions($arrObjContrato);
				
				$tipoSolicitacao    = new Solicitacao($this->_request->getControllerName());
				$arrTipoSolicitacao = $tipoSolicitacao->getTipoSolicitacao($cd_objeto, true);
				$this->form->st_solicitacao->addMultiOptions($arrTipoSolicitacao);
			}
		}
		$this->view->form = $this->form;

	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$ni_solicitacao     = (int)$this->_request->getParam('ni_solicitacao', 0);
		$ni_ano_solicitacao = (int)$this->_request->getParam('ni_ano_solicitacao', 0);
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		if ($this->solicitacao->delete("ni_solicitacao     = $ni_solicitacao and 
										ni_ano_solicitacao = $ni_ano_solicitacao and
										cd_objeto          = $cd_objeto")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function pesquisaTipoSolicitacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
		
		$arrTipoSolicitacao = $this->solicitacao->getTipoSolicitacao($cd_objeto);
		
		$strOptions = "<option value=\"\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		foreach ($arrTipoSolicitacao as $codigo => $texto) {
			$strOptions .= "<option value=\"{$codigo}\">{$texto}</option>";
		}

		echo $strOptions;
	}
}