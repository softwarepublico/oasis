<?php
class Pedido_PedidoReservadoProximoContratoController extends Base_Controller_Action
{
    private $_objSolicitacaoPedido;

    public function init()
    {
        parent::init();
        $this->_objSolicitacaoPedido = new SolicitacaoPedido($this->_request->getControllerName());
    }

    public function indexAction()
    {
        $objSolicitacao             = $this->_objSolicitacaoPedido->getSolicitacaoPedido(array('st_situacao_pedido = ?'=>'X'));
        $this->view->objSolicitacao = $objSolicitacao;
    }
}