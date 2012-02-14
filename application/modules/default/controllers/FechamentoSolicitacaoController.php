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

class FechamentoSolicitacaoController extends Base_Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();
		
		//recupera os parametros do post para enviar para a pop-up
		$params = $this->_request->getPost();

		//envia dados para pop-up
		$this->view->cd_objeto			= $params['cd_objeto'		  ];
		$this->view->tx_objeto			= $params['tx_objeto'		  ];
		$this->view->ni_solicitacao		= $params['ni_solicitacao'	  ];
		$this->view->ni_ano_solicitacao = $params['ni_ano_solicitacao'];
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$erros = false;

		$post  = $this->_request->getPost();

		$upRow = array();
		$upRow['st_grau_satisfacao']     = $post['st_grau_satisfacao'];
		$upRow['tx_obs_grau_satisfacao'] = $post['tx_obs_grau_satisfacao'];
		$upRow['dt_grau_satisfacao']     = date('Y-m-d H:i:s');

		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$erros = $solicitacao->atualizaSolicitacao($post['cd_objeto_fechamento_solicitacao'], $post['ni_solicitacao_fechamento_solicitacao'], $post['ni_ano_solicitacao_fechamento_solicitacao'], $upRow);

		if ($erros === false){
			echo Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_SOLICITACAO_SERVICO');
		}else{
			echo Base_Util::getTranslator('L_MSG_ERRO_FECHAR_SOLICITACAO_SERVICO');
		}
	}
}