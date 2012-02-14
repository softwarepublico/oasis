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

class Pedido_PedidoRelatorioController extends Base_Controller_Action {

	private $_module;
	private $_objSolicitacaoPedido;
	private $_objHistoricoPedido;
	private $_objPerguntaPedido;

	public function init()
    {
		parent::init();
        $this->_module               = $this->_request->getModuleName();
        
        $this->_objSolicitacaoPedido = new SolicitacaoPedido($this->_request->getControllerName());
        $this->_objHistoricoPedido   = new HistoricoPedido($this->_request->getControllerName());
        $this->_objPerguntaPedido    = new OpcaoRespostaPerguntaPedido($this->_request->getControllerName());

        $this->view->situacao        = $this->_objSolicitacaoPedido->getSituacaoSolicitacao(true);
	}

	public function indexAction()
    {
		$limit = 10;
		$start = intval($this->_request->getParam('start', 0), 10);

		$this->view->status = $this->_request->getParam('status', '');
		$this->view->inicio = $this->_request->getParam('inicio', '');
		$this->view->final  = $this->_request->getParam('final', '');
        $arrWhere = array();
		if($this->view->status != '0'){
            $arrWhere['st_situacao_pedido IN (?)'] = $this->view->status;
        }
		if(!empty($this->view->inicio)){
            $arrWhere['dt_solicitacao_pedido >= ?'] = date('Y-m-d', strtotime($this->view->inicio)). ' 00:00:00';
        }
		if(!empty($this->view->final)){
            $arrWhere['dt_solicitacao_pedido <= ?'] = date('Y-m-d', strtotime($this->view->final)) . ' 23:59:59';
        }
        $total = $this->_objSolicitacaoPedido->getTotalSolicitacaoPedido($arrWhere);
		$start = (($start >= $total || $start < 0) ? 0 : $start);

        $arrLimit['limit'] = $limit;
        $arrLimit['start'] = $start;

        $pedidos = $this->_objSolicitacaoPedido->getSolicitacaoPedido($arrWhere, 'dt_solicitacao_pedido DESC', $arrLimit);

		$this->view->pedidos  = $pedidos;
		$this->view->atual    = $start;
		$this->view->proximo  = $start + $limit;
		$this->view->anterior = $start - $limit;
		$this->view->paginas  = array();

		for($i = 0; $i < ceil($total / $limit); $i++){
			$this->view->paginas[$i + 1] = $i * $limit;
        }
	}

	public function historicoAction()
    {
		$pedido = $this->_request->getParam('pedido', 0);

        $rowSetSolicitacao = $this->_objSolicitacaoPedido->getSolicitacaoPedido(array('cd_solicitacao_pedido = ?'=>$pedido));

		if(!$rowSetSolicitacao->valid()){
			$this->_redirect("{$this->_module}pedido/pedido-relatorio");
        }
		$this->view->pedido    = $rowSetSolicitacao->getRow(0);
        
        $rowSetHistorico       = $this->_objHistoricoPedido->getHistoricoPedido(array('cd_solicitacao_historico = ?'=>$pedido));
		$this->view->historico = $rowSetHistorico;

        $resultSetFormulario    = $this->_objPerguntaPedido->getQuestionario($pedido);
		$this->view->formulario = $resultSetFormulario;
	}
}