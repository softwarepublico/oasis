<?php

class Pedido_PedidoAutoridadeController extends Base_Controller_Action {

	private $_objUsuarioPedido;

	public function init()
    {
		parent::init();
		$this->_objUsuarioPedido = new UsuarioPedido($this->_request->getControllerName());
	}

	public function indexAction(){}

    public function gridAutoridadeAction()
    {
		$this->_helper->layout->disableLayout();
        
        $arrWhere      = array('st_inativo = ?'=> 'N');
        $order         = array('tx_sigla_unidade','tx_nome_usuario');

        $rowSetUsuario = $this->_objUsuarioPedido->getUsuario($arrWhere, $order);

		$this->view->autoridades     = $rowSetUsuario;
		$this->view->comboDesignacao = array('T'=>Base_Util::getTranslator('L_VIEW_COMBO_TODOS'),
                                             'A'=>Base_Util::getTranslator('L_VIEW_COMBO_AUTORIDADE'),
                                             'C'=>Base_Util::getTranslator('L_VIEW_COMBO_COMITE'),
                                             'N'=>Base_Util::getTranslator('L_VIEW_COMBO_USUARIO'));
    }

    public function salvarAutoridadeAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();

        $arrResult = array('error'=>false, 'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'));

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            $this->_objUsuarioPedido->salvarStatusAutoridade($formData);

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
 }