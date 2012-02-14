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

class Pedido_PedidoRespostaController extends Base_Controller_Action {

    private $_objRespostaPedido;

	public function init()
    {
		parent::init();
        $this->_objRespostaPedido  = new RespostaPedido($this->_request->getControllerName());
	}

	public function indexAction()
    {}

    public function gridRespostaAction()
    {
		$this->_helper->layout->disableLayout();
		$this->view->respostas = $this->_objRespostaPedido->getResposta();
    }

    public function recuperaRespostaAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

        $cd_resposta_pedido = $this->_request->getParam('cd_resposta_pedido', 0);
        $row                = $this->_objRespostaPedido->getResposta($cd_resposta_pedido);

        echo Zend_Json::encode($row->toArray());
    }

    public function salvarRespostaAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

        $formData = $this->_request->getPost();

        $arrResult = array('error'=>false, 'type'=>1, 'msg'=>'');
        try{
            $db = Zend_Registry::get('db');
			$db->beginTransaction();

            if(!empty($formData['cd_resposta_pedido'])) {
				$novo             = $this->_objRespostaPedido->fetchRow("cd_resposta_pedido = {$formData['cd_resposta_pedido']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo             = $this->_objRespostaPedido->createRow();
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
            $novo->cd_resposta_pedido = $this->_objRespostaPedido->getNextValueOfField('cd_resposta_pedido');
            $novo->tx_titulo_resposta = strip_tags($formData['tx_titulo_resposta']);

            $novo->save();
            $db->commit();

        }catch(Zend_Exception $e){
            $arrResult['error'] = true;
            $arrResult['type' ] = 3;
            $arrResult['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

	public function excluirRespostaAction()
    {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_resposta_pedido = $this->_request->getParam('cd_resposta_pedido', 0);

        $arrResult = array('error'=>false, 'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
        try{
            $db = Zend_Registry::get('db');
			$db->beginTransaction();

            $this->_verificaIntegridade($cd_resposta_pedido);

            $this->_objRespostaPedido->delete(array('cd_resposta_pedido = ?'=>$cd_resposta_pedido));

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

    private function _verificaIntegridade($cd_resposta_pedido)
    {
        $objOpcaoRespostaPergunta = new OpcaoRespostaPerguntaPedido();

        $obj = $objOpcaoRespostaPergunta->fetchAll($objOpcaoRespostaPergunta->select()->where('cd_resposta_pedido = ?', $cd_resposta_pedido, Zend_Db::INT_TYPE));

        if($obj->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_PERGUNTA'));
        }
    }
}