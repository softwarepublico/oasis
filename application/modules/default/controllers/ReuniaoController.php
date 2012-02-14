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

class ReuniaoController extends Base_Controller_Action
{
	private $reuniao;

	public function init()
	{
		parent::init();
		$this->reuniao = new Reuniao($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REUNIAO'));
		
		$form = new ReuniaoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$novo 			  		= $this->reuniao->createRow();
				$novo->cd_reuniao		=	$this->reuniao->getNextValueOfField('cd_reuniao');
				$novo->cd_projeto 		= $formData['cd_projeto'];
				$novo->dt_reuniao       = (!empty ($formData['dt_reuniao'])) ? new Zend_Db_Expr("{$this->reuniao->to_date("'" . $formData['dt_reuniao'] . "'", 'DD/MM/YYYY')}") : null;
				$novo->tx_local_reuniao = $formData['tx_local_reuniao'];
				$novo->tx_pauta 		= $formData['tx_pauta'];
				$novo->tx_participantes = $formData['tx_participantes'];
				$novo->tx_ata 			= $formData['tx_ata'];
				
				if ($novo->save()) {
					$this->_redirect('./reuniao/listar');
				} else {
					//TODO mensagem de erro
				}

			} else {
				$form->populate($formData);
			}
		}
	}

	public function editarAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REUNIAO'));
		$form = new ReuniaoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$formData['submit'];

				$cd_reuniao = (int)$form->getValue('cd_reuniao');

				$obj = $this->reuniao->fetchRow("cd_reuniao= $cd_reuniao");

				$obj->cd_projeto 	   = $formData['cd_projeto'];
				$obj->dt_reuniao       = (!empty ($formData['dt_reuniao'])) ? new Zend_Db_Expr("{$this->reuniao->to_date("'" . $formData['dt_reuniao'] . "'", 'DD/MM/YYYY')}"): null;
				$obj->tx_local_reuniao = $formData['tx_local_reuniao'];
				$obj->tx_pauta 		   = $formData['tx_pauta'];
				$obj->tx_participantes = $formData['tx_participantes'];
				$obj->tx_ata 		   = $formData['tx_ata'];
				$obj->save();

				$this->_redirect('./reuniao/listar');
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

			$cd_reuniao= (int)$this->_request->getParam('cd_reuniao', 0);
			if ($cd_reuniao> 0) {
				$reuniao = new Reuniao($this->_request->getControllerName());
				$row   = $reuniao->fetchRow(array("cd_reuniao = ?" => $cd_reuniao));
				$form->populate($row->toArray());
			}
		}
		$this->view->form = $form;
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_reuniao = (int)$this->_request->getParam('cd_reuniao', 0);
		if ($this->reuniao->delete(array("cd_reuniao = ?"=> $cd_reuniao))) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridReuniaoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_projeto = ($cd_projeto != 0)?$cd_projeto:null;
		
		$arrReuniao = $this->reuniao->getDadosReuniao($cd_projeto);

		$arrDataReuniao = array();
		foreach ($arrReuniao as $reuniao)
		{
			$arrDataReuniao[$reuniao["cd_reuniao"]] = Base_Util::converterDate($reuniao['dt_reuniao'],"YYYY-MM-DD","DD/MM/YYYY");
			$reuniao['tx_ata']                      = str_ireplace('\"','"',$reuniao['tx_ata']);
		}

		$this->view->res     = $arrReuniao;
		$this->view->resData = $arrDataReuniao;
	}
	
	public function salvarDadosReuniaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrReuniao = $this->_request->getPost();
		$return = $this->reuniao->salvarReuniao($arrReuniao);
		
		echo ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
	}
	
	public function alterarDadosReuniaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrReuniao = $this->_request->getPost();
		$return = $this->reuniao->alterarReuniao($arrReuniao);
		
		echo ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
	}
	
	public function recuperaReuniaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_reuniao = (int)$this->_request->getParam('cd_reuniao', 0);
		$cd_reuniao = ($cd_reuniao != 0)?$cd_reuniao:null;
		$arrReuniao = $this->reuniao->getDadosReuniao(null,$cd_reuniao);
		
		$arrReuniao[0]['dt_reuniao'] = Base_Util::converterDate($arrReuniao[0]['dt_reuniao'],'YYYY-MM-DD','DD/MM/YYYY');
		$arrReuniao[0]['tx_ata']     = str_ireplace('\"','"',$arrReuniao[0]['tx_ata']);
		
		echo Zend_Json::encode($arrReuniao);
	}
}