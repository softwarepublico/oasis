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

class Pedido_PedidoOpcaoController extends Base_Controller_Action {

    private $_objPergunta;
    private $_objResposta;
    private $_objOpcaoResposta;


	public function init()
    {
		parent::init();

        $this->_objPergunta      = new PerguntaPedido($this->_request->getControllerName());
        $this->_objResposta      = new RespostaPedido($this->_request->getControllerName());
        $this->_objOpcaoResposta = new OpcaoRespostaPerguntaPedido($this->_request->getControllerName());
	}

	public function indexAction()
    {
		$this->view->respostas    = $this->_objResposta->comboResposta(true);
		$this->view->tipoResposta = $this->_objOpcaoResposta->getTipoResposta(true);
    }

    public function montaComboPerguntaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        echo $this->_objPergunta->comboPergunta(true,true);
    }

    public function getDadosPerguntaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $cd_pergunta = $this->_request->getParam('cd_pergunta',0);

        echo Zend_Json::encode($this->_objPergunta->getPergunta($cd_pergunta)->toArray());
    }

    public function gridOpcaoRespostaAction()
    {
        $this->_helper->layout->disableLayout();
        $cd_pergunta        = $this->_request->getParam('cd_pergunta',0);
		$this->view->opcoes = $this->_objOpcaoResposta->getOpcaoRespostaPergunta($cd_pergunta);
    }

    public function recuperarOpcaoRespostaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();

        $arrDados = $this->_objOpcaoResposta->find($formData['cd_pergunta_pedido'],$formData['cd_resposta_pedido'])
                                            ->current()
                                            ->toArray();
        echo Zend_Json::encode($arrDados);
    }

    public function salvarOpcaoRespostaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();

        $arrResult = array('error'=>false, 'type'=>1, 'msg'=>'');

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            $arrResult['msg'] = $this->_objOpcaoResposta->salvarOpcaoRespostaPergunta($formData);

            $db->commit();

        }catch(Base_Exception_Alert $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 2;
            $arrResult['msg'  ] = $e->getMessage();
	        $db->rollBack();
        }catch(Base_Exception_Error $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 3;
            $arrResult['msg'  ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 3;
            $arrResult['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function excluirOpcaoRespostaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();

        $arrResult = array('error'=>false, 'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            $this->_objOpcaoResposta->excluirOpcaoRespostaPergunta($formData);

            $db->commit();

        }catch(Base_Exception_Error $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 3;
            $arrResult['msg'  ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 3;
            $arrResult['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }
}