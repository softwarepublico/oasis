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

class DemandaController extends Base_Controller_Action
{
	private $objDemanda;
	private $objeto;
	private $objDemadaProfissional;
	private $objDemandaProfissionalNivelServico;
	private $cd_demanda;
    private $objStatusAtendimento;
    
    
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_DEMANDAS'));
		$this->objDemanda = new Demanda($this->_request->getControllerName());
		$this->objeto     = new ObjetoContrato($this->_request->getControllerName());
		$this->objDemadaProfissional              = new DemandaProfissional($this->_request->getControllerName());
		$this->objDemandaProfissionalNivelServico = new DemandaProfissionalNivelServico($this->_request->getControllerName());
        $this->objStatusAtendimento               = new StatusAtendimento($this->_request->getControllerName());
	}

	public function indexAction()
	{
   
        
		//Unidade
		$unidade                      = new Unidade($this->_request->getControllerName());
		$arrUnidade                   = $unidade->getUnidade(true);
		$this->view->cd_unidade_combo = $arrUnidade;

        
        
        $this->view->demandaAberta  = 0;
        $this->view->demandaFechada = 0;
        
		$cd_demanda = (int)$this->_request->getParam('cd_demanda');	
			
		if (!empty($cd_demanda)){
			$rowDemanda = $this->objDemanda->fetchRow($this->objDemanda->select()->where("cd_demanda = ?", $cd_demanda, Zend_Db::INT_TYPE));
			$cd_objeto              = $rowDemanda->cd_objeto;
			$this->view->cd_demanda = $cd_demanda;
			$this->view->demanda    = $rowDemanda;
			$this->view->dt_demanda = date('d/m/Y H:i:s', strtotime($rowDemanda->dt_demanda));
			$this->view->cd_unidade = $rowDemanda->cd_unidade;
            $this->view->cd_status_atendimento = $rowDemanda->cd_status_atendimento;
		}
		else {
			$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];
			$this->view->cd_unidade = K_CD_UNIDADE;
			//Data e Hora da Demanda
			$this->view->dt_demanda = date("d/m/Y H:i:s");
		}
		
		$selectObjeto = $this->objeto->select()
                                     ->from($this->objeto, array("tx_objeto"))
                                     ->where("cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE);

		$arrSelectObjeto       = $this->objeto->fetchRow($selectObjeto);
		$this->view->cd_objeto = $cd_objeto;
		$this->view->tx_objeto = $arrSelectObjeto->tx_objeto;  

		//Combo de Status de Atendimento
        $this->view->comboStatusAtendimento = $this->objStatusAtendimento->getComboStatusAtendimento(true);
	}

	public function salvarDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();

        $arrResult = array('error'  => false,
                           'typeMsg'=> 1,
                           'msg'    => '',
                           'cd_demanda'=>'');

		if ($this->_request->isPost()) {

			$db = Zend_Registry::get('db');
			try {
                $db->beginTransaction();

                $arrDados['cd_demanda'        ] = $formData['cd_demanda'];
                $arrDados['cd_objeto'         ] = $formData['cd_objeto'];
                $arrDados['ni_ano_solicitacao'] = (!empty($formData['ni_ano_solicitacao'])? $formData['ni_ano_solicitacao']: null);
                $arrDados['ni_solicitacao'    ] = (!empty($formData['ni_solicitacao'])? $formData['ni_solicitacao']: null);
                $arrDados['dt_demanda'        ] = (!empty($formData['dt_demanda'])? $formData['dt_demanda']: date('Y-m-d H:i:s'));
                $arrDados['tx_demanda'        ] = $formData['tx_demanda'];
                $arrDados['cd_status_atendimento' ] = $formData['cd_status_atendimento'];
                $arrDados['tx_solicitante_demanda'] = (!empty($formData['tx_solicitante_demanda'])? $formData['tx_solicitante_demanda']: null);
                $arrDados['cd_unidade'            ] = (!empty($formData['cd_unidade'])? $formData['cd_unidade']: null);

                $arrReturn = $this->objDemanda->salvarDemanda($arrDados);

                $arrResult['msg'] = $arrReturn['msg'];

                if(isset ($arrReturn['cd_demanda'])){
                    $arrResult['cd_demanda'] = $arrReturn['cd_demanda'];
                }
                
                $db->commit();
			}catch(Base_Exception_Error $e){
    	        $db->rollBack();
                $arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = $e->getMessage();
            } catch(Zend_Exception $e){
				$arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
                $db->rollback();
			}
        }else{
            throw new Exception(Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'));
        }

        echo Zend_Json_Encoder::encode($arrResult);
	}

    public function salvarDesignacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();

        $arrResult = array('error'  => false,
                           'typeMsg'=> 1,
                           'msg'    => '',
                           'perguntaPrioridade'=> false,
                           'cd_demanda'=>'');

		if($this->_request->isPost()) {

			$db = Zend_Registry::get('db');
			try {
                $db->beginTransaction();

                if(empty($formData['cd_demanda'])){
                    $arrResult['perguntaPrioridade'] = true;
                    throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_DEFINIR_PRIORIDADE_DEMANDA'));
                }
                $this->cd_demanda = $formData['cd_demanda'];

                if(empty($formData['cd_profissional_hidden']) && empty($formData['cd_nivel_servico_hidden']) ){
                    $this->salvarDesignacaoProf($formData);
                    $arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
                }else{
                    $this->alterarDesignacaoProf($formData);
                    $arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
                }
                $db->commit();
			}catch(Base_Exception_Alert $e){
    	        $db->rollBack();
                $arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 2;
                $arrResult['msg'    ] = $e->getMessage();
			}catch(Base_Exception_Error $e){
    	        $db->rollBack();
                $arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = $e->getMessage();
            } catch(Zend_Exception $e){
				$arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
                $db->rollback();
			}
        }else{
            throw new Zend_Exception(Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'));
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

    public function salvarDesignacao1Action()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();

        $arrResult = array('error'  => false,
                           'typeMsg'=> 1,
                           'msg'    => '',
                           'perguntaPrioridade'=> false,
                           'cd_demanda'=> '');

		if($this->_request->isPost()) {
			$db = Zend_Registry::get('db');
			try {
                $db->beginTransaction();

                if(empty($formData['cd_demanda'])){
                    $arrResult['perguntaPrioridade'] = true;
                    throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_DEFINIR_PRIORIDADE_DEMANDA'));
                }
                $this->cd_demanda = $formData['cd_demanda'];

                if(empty($formData['cd_profissional_hidden']) && empty($formData['cd_nivel_servico_hidden']) ){
                    $this->salvarDesignacaoProf($formData);
                    $arrResult['cd_demanda']= $this->cd_demanda;
                    $arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
                }else{
                    $this->alterarDesignacaoProf($formData);
                    $arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
                }
                $db->commit();
			}catch(Base_Exception_Alert $e){
    	        $db->rollBack();
                $arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 2;
                $arrResult['msg'    ] = $e->getMessage();
			}catch(Base_Exception_Error $e){
    	        $db->rollBack();
                $arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = $e->getMessage();
            } catch(Zend_Exception $e){
				$arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
                $db->rollback();
			}
        }else{
            throw new Zend_Exception(Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'));
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

    /**
     * Método utilizado para salvar a designação de profissional para
     * execução de demanda
     * @param array $arrDados
     * @return void || Zend_Exception
     */
    public function salvarDesignacaoProf(array $arrDados)
    {
        if(!$this->objDemadaProfissional->validaDados($this->cd_demanda, $arrDados)){
            if($this->objDemandaProfissionalNivelServico->validaDados($this->cd_demanda, $arrDados)){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_DADOS_NIVEL_SERVICO'));
            }
        }else{
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_DADOS_PROFISSIONAL'));
        }
    }

    /**
     * Método utilizado para alterar a designação de profissional
     * cadastrado para execução de demanda
     * @param array $arrDados
     * @return void || Zend_Exception
     */
	public function alterarDesignacaoProf(array $arrDados)
	{
		if(array_key_exists('cd_profissional_hidden',$arrDados)){
			if($arrDados['cd_profissional_hidden'] != ""){
				$arrDados['cd_profissional'] = $arrDados['cd_profissional_hidden'];
				unset($arrDados['cd_profissional_hidden']);
			}
		}
		if(array_key_exists('cd_nivel_servico_hidden',$arrDados)){
			if($arrDados['cd_nivel_servico_hidden'] != ""){
				$arrDados['cd_nivel_servico'] = $arrDados['cd_nivel_servico_hidden'];
				unset($arrDados['cd_nivel_servico_hidden']);
			}
		}
        if(!$this->objDemadaProfissional->validaDados($this->cd_demanda, $arrDados)){
            if($this->objDemandaProfissionalNivelServico->validaDados($this->cd_demanda, $arrDados)){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_DADOS_NIVEL_SERVICO'));
            }
        }else{
            throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_DADOS_PROFISSIONAL'));
        }
	}

    public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_demanda = (int)$this->_request->getParam('cd_demanda', 0);
		if ($this->objDemanda->delete(array("cd_demanda = ?" => $cd_demanda))) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

    public function qtdDemandaProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        
		$cd_profissional = (int)$this->_request->getParam('cd_profissional', 0);

        $arrQtdDemandasProfissional = $this->objDemadaProfissional->getQtdDemandasProfissional($cd_profissional);
        echo Zend_Json_Encoder::encode($arrQtdDemandasProfissional);
        
     }

    
    
/*
	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();
		if ($this->_request->isPost()) {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			try {
				if(empty($formData['cd_demanda'])){
					$this->salvar($formData);
					$msg = Base_Util::getTranslator('L_MSG_SUCESS_DESIGNACAO_PROFISSIONAL');
				} else {
					$this->alterar($formData);
					$msg = Base_Util::getTranslator('L_MSG_SUCESS_DESIGNACAO_PROFISSIONAL');
				}
                $db->commit();
			} catch(Zend_Exception $e){
				$db->rollback();
				$msg = Base_Util::getTranslator('L_MSG_ERRO_DESIGNACAO_PROFISSIONAL');
			}
		} else {
			$msg = Base_Util::getTranslator('L_MSG_ALERT_SEM_PROFISSIONAL_DESIGNADO');
		}
		$arrJson['msg']        = $msg;
		$arrJson['cd_demanda'] = $this->cd_demanda;
		echo Zend_Json_Encoder::encode($arrJson);
	}

	public function salvar(array $arrDados)
	{
		$error = false;
		
		$novo 				          = $this->objDemanda->createRow();
		$novo->cd_demanda		      = $this->objDemanda->getNextValueOfField('cd_demanda');
		$novo->cd_objeto              = $arrDados['cd_objeto'];
		$novo->ni_ano_solicitacao     = (!empty($arrDados['ni_ano_solicitacao'])? $arrDados['ni_ano_solicitacao']: null);
		$novo->ni_solicitacao         = (!empty($arrDados['ni_solicitacao'])? $arrDados['ni_solicitacao']: null);
		$novo->dt_demanda             = date('Y-m-d H:i:s');
		$novo->tx_demanda             = $arrDados['tx_demanda'];
		$novo->cd_prioridade          = ($arrDados['cd_prioridade'])?$arrDados['cd_prioridade']:null;
		$novo->tx_solicitante_demanda = (!empty($arrDados['tx_solicitante_demanda'])? $arrDados['tx_solicitante_demanda']: null);
		$novo->cd_unidade             = (!empty($arrDados['cd_unidade'])? $arrDados['cd_unidade']: null);

		if ($novo->save()){
			$this->cd_demanda = $novo->cd_demanda;
			$error = $this->objDemadaProfissional->validaDados($this->cd_demanda, $arrDados);
			if(!$error){
				$error = $this->objDemandaProfissionalNivelServico->validaDados($this->cd_demanda, $arrDados);
			}
		} else {
			$error = true;
		}
		return $error;
	}

	public function alterar(array $arrDados)
	{
		$error = false;

		if(array_key_exists('cd_profissional_hidden',$arrDados)){
			if($arrDados['cd_profissional_hidden'] != ""){
				$arrDados['cd_profissional'] = $arrDados['cd_profissional_hidden'];
				unset($arrDados['cd_profissional_hidden']);
			}
		}
		if(array_key_exists('cd_nivel_servico_hidden',$arrDados)){
			if($arrDados['cd_nivel_servico_hidden'] != ""){
				$arrDados['cd_nivel_servico'] = $arrDados['cd_nivel_servico_hidden'];
				unset($arrDados['cd_nivel_servico_hidden']);
			}
		}
		$addRow = array();
		$addRow['tx_demanda']             = $arrDados['tx_demanda'];
		$addRow['tx_solicitante_demanda'] = $arrDados['tx_solicitante_demanda'];
		$addRow['cd_unidade']             = $arrDados['cd_unidade'];

        if(isset($arrDados['cd_prioridade'])){
            $addRow['cd_prioridade'] = $arrDados['cd_prioridade'];
        }

		$where      = array("cd_demanda = ?" => $arrDados['cd_demanda']);
		$returnUpdate = $this->objDemanda->update($addRow, $where);

		if($returnUpdate) {
			$error = $this->objDemadaProfissional->validaDados($arrDados['cd_demanda'], $arrDados);
			if(!$error)
				$error = $this->objDemandaProfissionalNivelServico->validaDados($arrDados['cd_demanda'], $arrDados);
		} else {
			$error = true;
		}
		return $error;
	}
*/



}