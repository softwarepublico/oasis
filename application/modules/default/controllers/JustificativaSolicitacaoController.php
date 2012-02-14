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

class JustificativaSolicitacaoController extends Base_Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();
		
		$params = $this->_request->getParams();

		$this->view->cd_objeto			= $params['cd_objeto_justificativa_solicitacao'];
		$this->view->ni_solicitacao		= $params['ni_solicitacao_justificativa_solicitacao'];
		$this->view->ni_ano_solicitacao = $params['ni_ano_solicitacao_justificativa_solicitacao'];

	}

	public function verJustificativaSolicitacaoAction()
	{
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getParams();

		$cd_objeto			= $params['cd_objeto_justificativa_solicitacao'];
		$ni_solicitacao		= $params['ni_solicitacao_justificativa_solicitacao'];
		$ni_ano_solicitacao = $params['ni_ano_solicitacao_justificativa_solicitacao'];

		$_objSolicitacao    = new Solicitacao($this->_request->getControllerName());
		$arrSolicitacao     = $_objSolicitacao->find($ni_solicitacao,$ni_ano_solicitacao,$cd_objeto)->current()->toArray();

		$arrSolicitacao['st_aceite_just_solicitacao'] = (!is_null($arrSolicitacao['st_aceite_just_solicitacao']) && $arrSolicitacao['st_aceite_just_solicitacao'] == "S")?"Sim":"Não";
		$this->view->arrSolicitacao                            = $arrSolicitacao;
	}
	
	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$erros = false;
		
		$post = $this->_request->getPost();		


		$arrRetorno = array('error'=>'','errorType'=>'','msg'=>'');

		$solicitacao = new Solicitacao($this->_request->getControllerName());
		
		$addRow = array();
		$addRow['tx_justificativa_solicitacao'] = $post['tx_justificativa_solicitacao'];
		$addRow['dt_justificativa'] = date('Y-m-d H:i:s');
		
		$erros = $solicitacao->atualizaSolicitacao($post['cd_objeto_justificativa_solicitacao'], $post['ni_solicitacao_justificativa_solicitacao'], $post['ni_ano_solicitacao_justificativa_solicitacao'], $addRow);
		
		if ($erros === false){
			$arrRetorno['msg'	  ] = Base_Util::getTranslator('L_MSG_SUCESS_CADASTRAR_JUSTIFICATIVA_SOLICITACAO');
		}else{
			$arrRetorno['errorTpe'] = 3;
			$arrRetorno['msg'	  ] = Base_Util::getTranslator('L_MSG_ERRO_CADASTRAR_JUSTIFICATIVA_SOLICITACAO');
		}
		$arrRetorno['error'] = $erros;
		
		echo Zend_Json::encode($arrRetorno);
	}
}