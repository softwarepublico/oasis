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

class PreDemandaController extends Base_Controller_Action
{

	private $preDemanda;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PRE_DEMANDA'));
		$this->preDemanda = new PreDemanda($this->_request->getControllerName());
	}

	public function indexAction()
	{

		$objObjetoContrato = new ObjetoContrato($this->_request->getControllerName());
		$arrObjetoExecutor = $objObjetoContrato->getObjetoContratoAtivo('D', true);
		$this->view->objetoCombo  = $arrObjetoExecutor;
		
		//Unidade
		$unidade                      = new Unidade($this->_request->getControllerName());
		$arrUnidade                   = $unidade->getUnidade(true);
		$this->view->cd_unidade_combo = $arrUnidade;		

		$dt_pre_demanda = date("d/m/Y H:i:s");
		$this->view->dt_pre_demanda = $dt_pre_demanda;

		//dados obtidos da sessão
		$cd_objeto                               = $_SESSION["oasis_logged"][0]["cd_objeto"];
		$arrObjeto                               = $objObjetoContrato->getDadosObjetoContrato(null, $cd_objeto);
		$this->view->cd_objeto_emissor           = $cd_objeto;
		$this->view->tx_objeto_emissor           = $arrObjeto[0]["tx_objeto"];
		$this->view->cd_profissional_solicitante = $_SESSION["oasis_logged"][0]["cd_profissional"];

	}

	public function salvarAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();

			if (empty($formData['cd_pre_demanda'])) {
				$novo 				               = $this->preDemanda->createRow();
				$novo->cd_pre_demanda		       = $this->preDemanda->getNextValueOfField('cd_pre_demanda');
				$novo->cd_objeto_emissor           = $formData['cd_objeto_emissor'];
				$novo->cd_objeto_receptor          = $formData['cd_objeto_receptor'];
				$novo->cd_unidade                  = $formData['cd_unidade'];
				$novo->tx_pre_demanda              = $formData['tx_pre_demanda'];
				$novo->dt_pre_demanda              = (!empty ($formData['dt_pre_demanda'])) ? 
                                                            new Zend_Db_Expr("{$this->preDemanda->to_timestamp("'" . $formData['dt_pre_demanda'] . "'", 'DD/MM/YYYY HH24:MI:SS')}") : null;
				$novo->cd_profissional_solicitante = $formData['cd_profissional_solicitante'];

				if ($novo->save()) {
					echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
					if (K_ENVIAR_EMAIL == "S") {
						$_objMail = new Base_Controller_Action_Helper_Mail();
						$_objMail->enviaEmail($formData['cd_objeto_emissor'], $this->_request->getControllerName(), $arrDados);
					}
				} else {
					echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
				}
			}
			else
			{
				$where = "cd_pre_demanda = {$formData['cd_pre_demanda']}";
				$rowPreDemanda  = $this->preDemanda->fetchRow($where);
				
				if (!is_null($rowPreDemanda))
				{
					$addRow = array();
					$addRow['cd_objeto_emissor']           = $formData['cd_objeto_emissor'];
					$addRow['cd_objeto_receptor']          = $formData['cd_objeto_receptor'];
					$addRow['cd_unidade']                  = $formData['cd_unidade'];
					$addRow['tx_pre_demanda']              = $formData['tx_pre_demanda'];
					$addRow['dt_pre_demanda']              = (!empty ($formData['dt_pre_demanda'])) ? 
                                                                new Zend_Db_Expr("{$this->preDemanda->to_timestamp("'" . $formData['dt_pre_demanda'] . "'", 'DD/MM/YYYY HH24:MI:SS')}") : null;
					$addRow['cd_profissional_solicitante'] = $formData['cd_profissional_solicitante'];
					
					if ($this->preDemanda->update($addRow, $where))
					{
						echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
					}
					else
					{
						echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
					}
				}
			}
		}
	}

	public function editarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);

		$objObjetoContrato = new ObjetoContrato($this->_request->getControllerName());
		$arrObjetoExecutor = $objObjetoContrato->getObjetoContratoAtivo(null, true);
		$this->view->objetoCombo = $arrObjetoExecutor;
		
		$unidade                      = new Unidade($this->_request->getControllerName());
		$arrUnidade                   = $unidade->getUnidade(true);
		$this->view->cd_unidade_combo = $arrUnidade;				

		if (!is_null($this->_request->getParam('cd_pre_demanda'))) {

			$cd_pre_demanda = (int)$this->_request->getParam('cd_pre_demanda', 0);
			$rowPreDemanda  = $this->preDemanda->fetchRow("cd_pre_demanda = {$cd_pre_demanda}");

			$rowDados       = $rowPreDemanda->toArray();

			$this->view->cd_pre_demanda		         = $rowDados['cd_pre_demanda'];
			$this->view->cd_objeto_emissor           = $rowDados['cd_objeto_emissor'];
			$this->view->cd_objeto_receptor          = $rowDados['cd_objeto_receptor'];
			$this->view->cd_unidade                  = $rowDados['cd_unidade'];
			$this->view->tx_pre_demanda              = $rowDados['tx_pre_demanda'];
			$this->view->dt_pre_demanda              = Base_Util::converterDatetime($rowDados['dt_pre_demanda'], 'YYYY-MM-DD', 'DD/MM/YYYY');
			$this->view->cd_profissional_solicitante = $rowDados['cd_profissional_solicitante'];
			$this->view->ni_solicitacao              = $rowDados['ni_solicitacao'];
			$this->view->tx_objeto_emissor           = $arrObjetoExecutor[$rowDados['cd_objeto_emissor']];
				
			echo $this->view->render('pre-demanda/index.phtml');

		}
		else
		{
			echo Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO');
		}

	}

	public function excluirAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_pre_demanda = (int)$this->_request->getParam('cd_pre_demanda', 0);
		
		if ($this->preDemanda->delete("cd_pre_demanda={$cd_pre_demanda}")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
}