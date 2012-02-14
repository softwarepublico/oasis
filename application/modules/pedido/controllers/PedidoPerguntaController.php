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

class Pedido_PedidoPerguntaController extends Base_Controller_Action {

    private $_objPerguntaPedido;
    private $_objOpcaoPergunta;

	public function init()
    {
		parent::init();
        $this->_objPerguntaPedido = new PerguntaPedido($this->_request->getControllerName());
        $this->_objOpcaoPergunta  = new OpcaoRespostaPerguntaPedido($this->_request->getControllerName());
	}

	public function indexAction()
    {
        $this->view->arrOption = array('0'=>Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'),
                                       'S'=>Base_Util::getTranslator('L_VIEW_SIM'),
                                       'N'=>Base_Util::getTranslator('L_VIEW_NAO'));
	}

    public function gridPerguntaAction()
    {
		$this->_helper->layout->disableLayout();
		$this->view->perguntas    = $this->_objPerguntaPedido->getPergunta();
    }

    public function salvarPerguntaAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

        $formData = $this->_request->getPost();

        $arrResult = array('error'=>false, 'type'=>1, 'msg'=>'');
        try{
            $db = Zend_Registry::get('db');
			$db->beginTransaction();

            $arrResult['msg'] = $this->_objPerguntaPedido->salvarPergunta($formData);
            
            $db->commit();
        }catch(Base_Exception_Error $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 3;
            $arrResult['msg'  ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 3;
            $arrResult['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function recuperaPerguntaAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

        $cd_pergunta_pedido = $this->_request->getParam('cd_pergunta_pedido', 0);

        $result = $this->_objPerguntaPedido->getPergunta($cd_pergunta_pedido);
        
        echo Zend_Json::encode($result->toArray());

    }

	public function excluirPerguntaAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_pergunta_pedido = $this->_request->getParam('cd_pergunta_pedido', 0);

        $arrResult = array('error'=>false, 'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
        try{
            $db = Zend_Registry::get('db');
			$db->beginTransaction();

            $this->_verificaIntegridade($cd_pergunta_pedido);
            
            $this->_objPerguntaPedido->excluirPergunta($cd_pergunta_pedido);

            $db->commit();

        }catch(Base_Exception_Alert $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 2;
            $arrResult['msg'  ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 3;
            $arrResult['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

    private function _verificaIntegridade($cd_pergunta_pedido)
    {
        $rowSet = $this->_objOpcaoPergunta->verificaExistenciaDados($cd_pergunta_pedido);

        if($rowSet->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_RESPOSTA'));
        }
    }

}