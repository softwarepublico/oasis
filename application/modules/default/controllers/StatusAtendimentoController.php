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

class StatusAtendimentoController extends Base_Controller_Action
{
	private $_objFormStatusAtendimento;
	private $_objStatusAtendimento;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_SOLICITACAO_SERVICO_TIPO_DEMANDA'));
        $this->_objFormStatusAtendimento = new StatusAtendimentoForm();
        $this->_objStatusAtendimento = new StatusAtendimento($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->formStatusAtendimento = $this->_objFormStatusAtendimento;
	}

	public function salvarStatusAtendimentoAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();

        $arrResult = array('error'  => false,
                           'typeMsg'=> 1,
                           'msg'    => '');
        try{
            $db = Zend_Registry::get('db');
			$db->beginTransaction();

            $formData['tx_status_atendimento'] = strip_tags($formData['tx_status_atendimento']);
            $arrResult['msg']          = $this->_objStatusAtendimento->salvarStatusAtendimento($formData);

            $db->commit();

        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	public function recuperarStatusAtendimentoAction()
	{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $cd_status_atendimento = $this->_request->getParam('cd_status_atendimento');

        $rowSetStatusAtendimento = $this->_objStatusAtendimento->getStatusAtendimento(array("cd_status_atendimento = ?" => $cd_status_atendimento));

        echo Zend_Json_Encoder::encode($rowSetStatusAtendimento->getRow(0)->toArray());
	}

    public function excluirStatusAtendimentoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_status_atendimento = $this->_request->getParam('cd_status_atendimento');

        $arrResult = array('error'  => false,
                           'typeMsg'=> 1,
                           'msg'    => Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
        try{
            $db = Zend_Registry::get('db');
			$db->beginTransaction();
            $arrWhere['cd_status_atendimento = ?'] = $cd_status_atendimento;
            //verificar quais são as tabelas relacionadas
//            $this->_objStatusAtendimento->verificaIntegridadeStatusAtendimento($arrWhere);
            $this->_objStatusAtendimento->excluirStatusAtendimento($arrWhere);

            $db->commit();

        }catch(Base_Exception_Alert $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 2;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	public function gridStatusAtendimentoAction()
	{
        $this->_helper->layout->disableLayout();
        $this->view->res = $this->_objStatusAtendimento->getStatusAtendimento();
	}

}