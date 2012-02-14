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

class HistoricoController extends Base_Controller_Action
{
	
	private $historico;
	private $profissionalProjeto;
	
	public function init()
	{
		parent::init();
		$this->historico = new Historico($this->_request->getControllerName());
        $this->view->headTitle(Base_Util::setTitle('L_TIT_HISTORICO'));
	}
	
	public function indexAction()
	{
		$cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];
	
		$form = new HistoricoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->form = $form;

		//dados obtidos da sessão
		$this->view->cd_profissional 	 = $cd_profissional;

		//Caso receba o cd_proposta, ele mostra o combo Proposta selecionado com o valor enviado
		if (!is_null($this->_request->getParam('cd_proposta'))) {
			$formData['cd_proposta'] = $this->_request->getParam('cd_proposta');
			$form->populate($formData);
			//$this->view->gridHistorico = $this->gridHistorico($formData['cd_proposta']);
		}
				
		if ($this->_request->isPost()) {

			$formData = $this->_request->getPost();
			
			if ($form->isValid($formData)) {			
				$return = $this->salvarDadosHistoricoAction($formData);
				if($return){
					$this->_redirect($this->_helper->BaseUrl->baseUrl()."/historico");
				} else {
					die(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
				}
			} else {
				$form->populate($formData);
			}
		}
	}//fim do método indexAction
	
	public function editarHistoricoAction()
	{		
		$form = new HistoricoForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$return = $this->alterarDadosHistoricoAction($formData);
				if($return){
					$this->_redirect("./historico/index/cd_projeto/{$formData['cd_projeto']}/cd_proposta/{$formData['cd_proposta']}");
				} else {
					die(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
				}
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

			$cd_historico = (int)$this->_request->getParam('cd_historico', 0);
			if ($cd_historico > 0) {
				$historico  = new Historico($this->_request->getControllerName());
				$row   = $historico->fetchRow("cd_historico = $cd_historico");
				
				$rowDados = $row->toArray();
				$form->populate($row->toArray($rowDados));
								
				// Busca os dados dos combos-filhos para monta-los e posteriormente preenche-lo com o valor selecionado
				$proposta = new Proposta($this->_request->getControllerName());
				$arrPropostaAberta = $proposta->getPropostaAbertaHistorico($rowDados['cd_projeto']);
				$form->cd_proposta->addMultiOptions($arrPropostaAberta);
				
				$modulo = new Modulo($this->_request->getControllerName());
				$arrModuloProposta = $modulo->getModuloProposta($rowDados['cd_proposta']);
				$form->cd_modulo->addMultiOptions($arrModuloProposta);
				
				$etapa = new Etapa($this->_request->getControllerName());
				$arrEtapaProfissionalObjeto  = $etapa->getEtapaProfissionalObjeto($rowDados['cd_objeto']);
				array_unshift($arrEtapaProfissionalObjeto, Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
				$form->cd_etapa->addMultiOptions($arrEtapaProfissionalObjeto);
				
				$atividade = new Atividade($this->_request->getControllerName());
				$arrAtividade = $atividade->getAtividade($rowDados['cd_etapa']);
				array_unshift($arrAtividade, Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
				$form->cd_atividade->addMultiOptions($arrAtividade);
				
				$form->populate($row->toArray($rowDados));
			}
			$this->view->grid = $this->gridHistorico($rowDados['cd_projeto']);
		}//fim do else
			$this->view->form = $form;
	}
	
	public function excluirHistoricoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$cd_historico           = (int)$this->_request->getParam('cd_historico');
				
		$msg = $this->historico->excluirHistorico($cd_historico);

        $arrRetorno = array();
        if($msg){
            $arrRetorno['type'] = 1;
            $arrRetorno['msg' ] = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
        }else{
            $arrRetorno['type'] = 3;
            $arrRetorno['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
        }
        echo Zend_Json::encode($arrRetorno);

	}//fim do metodo excluir
	
    public function pesquisaHistoricoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto             = $this->_request->getParam('cd_projeto ');
		$cd_proposta			= $this->_request->getParam('cd_proposta');
		$cd_modulo		  		= $this->_request->getParam('cd_modulo');
		$cd_etapa  				= $this->_request->getParam('cd_etapa');
		$cd_atividade  			= $this->_request->getParam('cd_atividade');
		
		$historico = new Historico($this->_request->getControllerName());
		$select 	  = $historico->select()
                                  ->where("cd_projeto   = ?",$cd_projeto,Zend_Db::INT_TYPE)
                                  ->where("cd_proposta  = ?",$cd_proposta,Zend_Db::INT_TYPE)
                                  ->where("cd_modulo    = ?",$cd_modulo,Zend_Db::INT_TYPE)
                                  ->where("cd_etapa     = ?",$cd_etapa,Zend_Db::INT_TYPE)
                                  ->where("cd_atividade = ?",$cd_atividade,Zend_Db::INT_TYPE);
		
		$res = $historico->fetchAll($select)->toArray();
		echo Zend_Json_Encoder::encode($res);	
	}
	
	public function gridHistoricoAction()
	{
		$this->_helper->layout->disableLayout();
		$cd_projeto      = $this->_request->getParam('cd_projeto', 0);
		$cd_proposta     = $this->_request->getParam('cd_proposta', 0);
		$cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];
		//realiza a consulta
		$this->view->resu = $this->historico->listaHistorico($cd_projeto,$cd_proposta, $cd_profissional);
	}
		
	public function recuperaHistoricoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_historico = $this->_request->getParam('cd_historico');
	
		$res = $this->historico->recuperaHistorico($cd_historico);
	    foreach($res as $key=>$value){
			$res[$key]['tx_historico'       ] = str_ireplace('\"','"',$value['tx_historico']);
			$res[$key]['dt_inicio_historico'] = date('d/m/Y',strtotime($value['dt_inicio_historico']));
			$res[$key]['dt_fim_historico'   ] = date('d/m/Y',strtotime($value['dt_fim_historico']));
		}
		echo Zend_Json::encode($res);
	}
	
	public function salvarDadosHistoricoAction(array $arrHistorico = array())
	{
		if(count($arrHistorico) > 0){
            $arrHistorico['dt_inicio_historico'] = Base_Util::converterDate($arrHistorico['dt_inicio_historico'], 'DD/MM/YYYY', 'YYYY-MM-DD');
            $arrHistorico['dt_fim_historico'   ] = Base_Util::converterDate($arrHistorico['dt_fim_historico'   ], 'DD/MM/YYYY', 'YYYY-MM-DD');

			return $this->historico->salvaHistorico($arrHistorico);
		} else {
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$arrDados = $this->_request->getPost();
			$arrDados['cd_profissional'] = $_SESSION['oasis_logged'][0]['cd_profissional'];
            
            $arrDados['dt_inicio_historico'] = Base_Util::converterDate($arrDados['dt_inicio_historico'], 'DD/MM/YYYY', 'YYYY-MM-DD');
            $arrDados['dt_fim_historico'   ] = Base_Util::converterDate($arrDados['dt_fim_historico'   ], 'DD/MM/YYYY', 'YYYY-MM-DD');

			$return = $this->historico->salvaHistorico($arrDados);
			$msg    = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
			echo $msg;
		}
	}
	
	public function alterarDadosHistoricoAction(array $arrHistorico = array())
	{
		if(count($arrHistorico) > 0){
            $arrHistorico['dt_inicio_historico'] = Base_Util::converterDate($arrHistorico['dt_inicio_historico'], 'DD/MM/YYYY', 'YYYY-MM-DD');
            $arrHistorico['dt_fim_historico'   ] = Base_Util::converterDate($arrHistorico['dt_fim_historico'   ], 'DD/MM/YYYY', 'YYYY-MM-DD');

			return $this->historico->alterarHistorico($arrHistorico);
            
		} else {
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();

			$arrDados                        = $this->_request->getPost();
			$arrDados['cd_profissional'    ] = $_SESSION['oasis_logged'][0]['cd_profissional'];

            $arrDados['dt_inicio_historico'] = Base_Util::converterDate($arrDados['dt_inicio_historico'], 'DD/MM/YYYY', 'YYYY-MM-DD');
            $arrDados['dt_fim_historico'   ] = Base_Util::converterDate($arrDados['dt_fim_historico'   ], 'DD/MM/YYYY', 'YYYY-MM-DD');

            $return = $this->historico->alterarHistorico($arrDados);
			$msg    = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
			echo $msg;
		}
	}
}