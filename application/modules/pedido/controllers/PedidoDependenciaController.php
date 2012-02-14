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

class Pedido_PedidoDependenciaController extends Base_Controller_Action
{

    private $_objPergunta;
    private $_objOpcaoRespostaPergunta;
    private $_objPerguntaDepende;

	public function init()
    {
		parent::init();
        $this->_objPergunta              = new PerguntaPedido($this->_request->getControllerName());
        $this->_objOpcaoRespostaPergunta = new OpcaoRespostaPerguntaPedido($this->_request->getControllerName());
        $this->_objPerguntaDepende       = new PerguntaDependeRespostaPedido($this->_request->getControllerName());
	}

	public function indexAction()
    {
        $this->view->arrPergunta = $this->_objPergunta->comboPergunta(true);
	}

    public function comboPerguntaAction()
    {
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

        echo $this->_objPergunta->comboPergunta(true, true);
    }

    public function comboRespostaAction()
    {
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

        $cd_pergunta = $this->_request->getParam('cd_pergunta',0);

        echo $this->_objOpcaoRespostaPergunta->comboResposta($cd_pergunta, true, true);
    }

    public function pesquisaPerguntaAction()
    {
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$formData = $this->_request->getPost();
		
        $naoAssociada = $this->_objPerguntaDepende->pesquisaPerguntaForaAssociacao($formData);
        $associada    = $this->_objPerguntaDepende->pesquisaPerguntaAssociada($formData);

        $arr1 = "";
        foreach ($naoAssociada as $fora) {
            $fim = "";
				if(strlen($fora->tx_titulo_pergunta) >= 50){
					$fim = "...";
				}
				$tx_titulo_pergunta = substr($fora->tx_titulo_pergunta,0,50).$fim;

            $arr1 .= "<option title=\"{$fora->tx_titulo_pergunta}\" value=\"{$fora->cd_pergunta_pedido}\">{$tx_titulo_pergunta}</option>";
        }

        $arr2 = "";
        foreach ($associada as $na) {
            $fim = "";
				if(strlen($na->tx_titulo_pergunta) >= 50){
					$fim = "...";
				}
				$tx_titulo_pergunta = substr($na->tx_titulo_pergunta,0,50).$fim;

            $arr2 .= "<option title=\"{$na->tx_titulo_pergunta}\" value=\"{$na->cd_pergunta_pedido}\">{$tx_titulo_pergunta}</option>";
        }
        $retornaOsDois = array($arr1, $arr2);
        echo Zend_Json_Encoder::encode($retornaOsDois);
    }

    public function associaPerguntaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_pergunta  = $post['cd_pergunta'];
		$cd_resposta  = $post['cd_resposta'];
		$arrPerguntas = Zend_Json_Decoder::decode($post['perguntas']);

		foreach ($arrPerguntas as $pergunta) {
			$novo = $this->_objPerguntaDepende->createRow();
			$novo->cd_pergunta_pedido  = $cd_pergunta;
			$novo->cd_resposta_pedido  = $cd_resposta;
			$novo->cd_pergunta_depende = $pergunta;
			$novo->save();
		}
    }

	public function desassociaPerguntaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$cd_pergunta  = $this->_objPerguntaDepende->getDefaultAdapter()->quote($post['cd_pergunta']);
		$cd_resposta  = $this->_objPerguntaDepende->getDefaultAdapter()->quote($post['cd_resposta']);
		$arrPerguntas = Zend_Json_Decoder::decode($post['perguntas']);

		foreach ($arrPerguntas as $pergunta) {
            $pergunta = $this->_objPerguntaDepende->getDefaultAdapter()->quote($pergunta);

			$where    = "cd_pergunta_pedido = {$cd_pergunta} and cd_resposta_pedido = {$cd_resposta} and cd_pergunta_depende = {$pergunta}";
			$this->_objPerguntaDepende->delete($where);
		}
	}
}