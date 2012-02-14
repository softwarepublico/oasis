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

class Pedido_PedidoEncaminharRecomendacaoComiteController extends Base_Controller_ActionPedido
{
    private $_objSolicitacaoPedido;
    private $_objHistoricoPedido;
    private $_objPerguntaPedido;

    /**
     * Status do pedido ao ser encaminhado pelo comite
     *
     * @var String $_situacao_analise_comite Status da solicitação enviada pelo comite
     */
    private $_situacao_analise_comite = 'D';

    public function init()
    {
		parent::init();
        $this->_objSolicitacaoPedido = new SolicitacaoPedido($this->_request->getControllerName());
        $this->_objHistoricoPedido   = new HistoricoPedido($this->_request->getControllerName());
        $this->_objPerguntaPedido    = new PerguntaPedido($this->_request->getControllerName());
    }

	public function indexAction()
    {
        $this->_forward('index', 'pedido-solicitacao', 'pedido');
    }

    public function gridPedidoComiteAction()
    {
        $this->_helper->layout->disableLayout();

        $pedidos             = $this->_objSolicitacaoPedido->getSelicitacaoValidadaParaEncaminhar(array('st_situacao_pedido IN (?)'=>array('T')));
        $this->view->pedidos = $pedidos;
    }

    public function montaEncaminhamentoSolicitacaoAction()
    {
        $this->_helper->layout->disableLayout();

        $cd_solicitacao_pedido = $this->_request->getParam('cd_solicitacao_pedido', 0);

        $resultSetPedido        = $this->_objSolicitacaoPedido->getSolicitacaoPedido(array('cd_solicitacao_pedido = ?'=>$cd_solicitacao_pedido,
                                                                                           'st_situacao_pedido IN (?)'=>'T'));
        $resultSetHistorico     = $this->_objHistoricoPedido->getHistoricoPedido(array('cd_solicitacao_historico = ?'=> $cd_solicitacao_pedido));
        $resultSetFormulario    = $this->_objPerguntaPedido->getQuestionario($cd_solicitacao_pedido);

		$this->view->pedido     = $resultSetPedido->getRow(0); //transforma em objeto row para usar $this->pedido->cd_solicitacao_pedio na view
		$this->view->historico  = $resultSetHistorico;
		$this->view->formulario = $resultSetFormulario;
    }

     public function encaminharPedidoAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $arrResult = array('error' => false, 'type' => 1, 'msg' => Base_Util::getTranslator('L_MSG_SUCESS_ENCAMINHAR_REGISTRO'));

        $formData = $this->_request->getPost();

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            $data = date('Y-m-d H:i:s');
            //adiciona o status e a data aos dados a serem registrados no histórico
            $formData['status'               ] = $this->_situacao_analise_comite;
            $formData['dt_registro_historico'] = $data;

            $this->_objHistoricoPedido->registraHistoricoPedido($formData);

            $formData['dt_analise_comite' ] = $data;
            $formData['tx_analise_comite' ] = $formData['tx_descricao_historico'];
            $formData['st_situacao_pedido'] = $this->_situacao_analise_comite;

            //exclui do array os indices que não serão utilizado no registro do encaminhamento
            unset($formData['dt_registro_historico']);
            unset($formData['tx_descricao_historico']);
            unset($formData['status']);
            $this->_objSolicitacaoPedido->registraEncaminhamentoRecomendacaoComite($formData);

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