<?php
class Pedido_PedidoEmitirSolicitacaoPropostaController extends Base_Controller_Action
{
    private $_objOpcaoRespostaPerguntaPedido;
    private $_objHistoricoPedido;
    private $_objSolicitacaoPedido;
    private $_objSolicitacao;

    public function init()
    {
        parent::init();
        Zend_Loader::loadClass('Solicitacao',Base_Util::baseUrlModule('default', 'models'));

        $this->_objSolicitacao                 = new Solicitacao($this->_request->getControllerName());
        $this->_objOpcaoRespostaPerguntaPedido = new OpcaoRespostaPerguntaPedido($this->_request->getControllerName());
        $this->_objHistoricoPedido             = new HistoricoPedido($this->_request->getControllerName());
        $this->_objSolicitacaoPedido           = new SolicitacaoPedido($this->_request->getControllerName());
    }

    public function indexAction()
    {
       $this->view->headTitle(Base_Util::setTitle('L_TIT_PED_EMITIR_SOLICITACAO'));
    }

    public function gridSolicitacaoAction()
    {
		$this->_helper->layout->disableLayout();

        $objSolicitacao             = $this->_objSolicitacaoPedido->getSolicitacaoPedido(array('st_situacao_pedido = ?'=>'S'));
        $this->view->objSolicitacao = $objSolicitacao;
    }

    public function formularioPedidoAction()
    {
		$this->_helper->layout->disableLayout();

        $cd_solicitacao_pedido = $this->_request->getParam('cd_solicitacao_pedido');

        $this->view->formulario = $this->_objOpcaoRespostaPerguntaPedido->getQuestionario($cd_solicitacao_pedido);
    }

    public function historicoPedidoAction()
    {
		$this->_helper->layout->disableLayout();

        $cd_solicitacao_pedido = $this->_request->getParam('cd_solicitacao_pedido');
        $this->view->historico = $this->_objHistoricoPedido->getHistoricoPedido(array('cd_solicitacao_historico = ?'=>$cd_solicitacao_pedido));
    }

    public function solicitacaoPedidoAction()
    {
		$this->_helper->layout->disableLayout();

        Zend_Loader::loadClass('ObjetoContrato',Base_Util::baseUrlModule('default', 'models'));
        
        $objUsuarioPedido  = new UsuarioPedido();
        $objObjetoContrato = new ObjetoContrato();
        $cd_solicitacao_pedido = $this->_request->getParam('cd_solicitacao_pedido');

        $objListaSolicitacao = $this->_objSolicitacaoPedido->getSolicitacaoPedido(array('cd_solicitacao_pedido = ?'=>$cd_solicitacao_pedido));
        $objListaUsuario     = $objUsuarioPedido->getUsuario(array('cd_usuario_pedido = ?'=>$objListaSolicitacao->getRow('cd_usuario_aut_competente')->cd_usuario_aut_competente));

        $this->view->arrObjeto    = $objObjetoContrato->getObjetoContratoAtivo('P', true, false, true);
        $this->view->dadosUsuario = $objListaUsuario->getRow(0);
        $this->view->cd_solicitacao_pedido = $cd_solicitacao_pedido;
    }

    public function cadastrarSolicitacaoPedidoAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $formData = $this->_request->getPost();

        $arrResult = array('error'=>false, 'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_CRIAR_SOLICITACAO'));

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            $data         = date("Y-m-d H:i:s");
            $ano          = date('Y');
            $statusPedido = 'I';

            $arrInsertSolicitacaoProposta['dt_solicitacao'         ] = (!empty ($data)) ? $data : null;
            $arrInsertSolicitacaoProposta['ni_ano_solicitacao'     ] = $ano;
            $arrInsertSolicitacaoProposta['ni_solicitacao'         ] = $this->_objSolicitacao->getNewValueByObjeto($formData['cd_objeto'], $ano);
            $arrInsertSolicitacaoProposta['cd_objeto'              ] = $formData['cd_objeto'];
            $arrInsertSolicitacaoProposta['st_solicitacao'         ] = 1;
            $arrInsertSolicitacaoProposta['tx_solicitante'         ] = $formData['tx_nome_usuario'];
            $arrInsertSolicitacaoProposta['tx_sala_solicitante'    ] = $formData['tx_sala_usuario'];
            $arrInsertSolicitacaoProposta['tx_telefone_solicitante'] = $formData['tx_telefone_usuario'];
            $arrInsertSolicitacaoProposta['cd_unidade'             ] = $formData['cd_unidade'];
            $arrInsertSolicitacaoProposta['tx_solicitacao'         ] = $formData['tx_obs_solicitacao'];
            $arrInsertSolicitacaoProposta['ni_prazo_atendimento'   ] = $formData['ni_prazo_atendimento'];
            //cria a solicitação de serviço
            $this->_objSolicitacao->criaNovaSolicitacaoServico($arrInsertSolicitacaoProposta);

            $arrUpdatePedido['st_situacao_pedido'   ] = $statusPedido;
            $arrUpdatePedido['cd_solicitacao_pedido'] = $formData['cd_solicitacao_pedido'];
            //atualiza o status do pedido
            $this->_objSolicitacaoPedido->atualizaStatusPedido($arrUpdatePedido);

            $arrInsertHistorico['cd_solicitacao_pedido' ] = $formData['cd_solicitacao_pedido'];
            $arrInsertHistorico['tx_descricao_historico'] = $formData['tx_obs_solicitacao'   ];
            $arrInsertHistorico['dt_registro_historico' ] = $data;
            $arrInsertHistorico['status'                ] = $statusPedido;
            //registra o historico de criação da solicitação do pedido
            $this->_objHistoricoPedido->registraHistoricoPedido($arrInsertHistorico);

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