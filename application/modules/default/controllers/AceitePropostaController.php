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

class AceitePropostaController extends Base_Controller_Action
{
	private $_parcelaOrcamento;

	public function init()
	{
		parent::init();
		$this->_parcelaOrcamento = new Base_Controller_Action_Helper_ParcelaOrcamento();
	}

	public function indexAction()
	{
		//desabilita o layout para montar o POP-UP
		$this->_helper->layout->disableLayout();

		//recebe os parametros a serem enviados para o POP-UP
		$post = $this->_request->getPost();

		$this->view->cd_projeto	 = $post['cd_projeto_aceite_proposta'];
		$this->view->cd_proposta = $post['cd_proposta_aceite_proposta'];
		$this->view->tx_projeto	 = $post['projeto_proposta_aceite'];

	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		if ($erros === false){
			$erros = $this->salvaAceiteProposta($post);
		}

		//atualiza o flag de aceite na solicitação de serviço
		//do tipo "Solicitação de Proposta" que solicitou a proposta
		if ($erros === false) {
			$erros = $this->atualizaAceiteSolicitacaoProposta($post);
		}

		//se a proposta for de código 1
		//ela possui uma parcela criada automaticamente criada no momento da criação da proposta
		//que é a parcela de pagamento do orçamento da proposta
		//Esta função seta o flag de aceite desta parcela
		if ($erros === false && $post['cd_proposta_aceite_proposta'] == 1) {
			$this->_parcelaOrcamento->verificaIndicadorParcelaOrcamento($post['cd_projeto_aceite_proposta']);
			if ($this->_parcelaOrcamento->getStParcelaOrcamento() == 'S') {
				$erros = $this->atualizaAceitePrimeiraParcela($post);
			}
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_GRAVAR_ACEITE_PROPOSTA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_GRAVAR_ACEITE_PROPOSTA');
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail               = new Base_Controller_Action_Helper_Mail();
				$arrInf['cd_projeto']   = $post['cd_projeto_aceite_proposta'];
				$arrInf['cd_proposta']  = $post['cd_proposta_aceite_proposta'];
				$arrInf['st_msg_email'] = $_SESSION['st_msg_email'];
				$arrInf['_tx_obs']      = $_SESSION['tx_obs'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
				unset ($_SESSION['st_msg_email']);
				unset ($_SESSION['tx_obs']);
			}
		}
	}

	public function salvaAceiteProposta($post)
	{
		$erros = false;

		$cd_projeto  = $post['cd_projeto_aceite_proposta'];
		$cd_proposta = $post['cd_proposta_aceite_proposta'];

		$objProcessamentoProposta = new ProcessamentoProposta($this->_request->getControllerName());

		$addRow = array();
		$addRow['st_aceite_proposta']      = $post['st_aceite_proposta'];
		$addRow['dt_aceite_proposta']      = date('Y-m-d H:i:s');
		$addRow['tx_obs_aceite_proposta']  = $post['tx_obs_aceite_proposta'];
		$addRow['cd_prof_aceite_proposta'] = $_SESSION["oasis_logged"][0]["cd_profissional"];

		if ($post['st_aceite_proposta'] == 'N'){
			$addRow['st_ativo'] = null;
		}

		if (K_ENVIAR_EMAIL == "S") {
			$_SESSION['st_msg_email'] = $post['st_aceite_proposta'];
			$_SESSION['tx_obs']       = $post['tx_obs_aceite_proposta'];
		}

		$erros = $objProcessamentoProposta->atualizaProcessamentoProposta($cd_projeto, $cd_proposta, $addRow);

		return $erros;
	}
	
	public function atualizaAceiteSolicitacaoProposta($post)
	{
		$erros = false;

		$addRow                  = array();
		$addRow['st_aceite']     = 'S';
		$addRow['dt_aceite']     = date('Y-m-d H:i:s');
		$addRow['tx_obs_aceite'] = $post['tx_obs_aceite_proposta'];

		$proposta = new Proposta($this->_request->getControllerName());
		$rowProposta = $proposta->fetchRow("cd_projeto = {$post['cd_projeto_aceite_proposta']} and cd_proposta = {$post['cd_proposta_aceite_proposta']}");

		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$erros = $solicitacao->atualizaSolicitacao($rowProposta->cd_objeto, $rowProposta->ni_solicitacao, $rowProposta->ni_ano_solicitacao, $addRow);

		return $erros;
	}

	public function atualizaAceitePrimeiraParcela($post)
	{
		$erros = false;

		$objParcela              = new Parcela($this->_request->getControllerName());
		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());
		$rowPrimeiraParcela      = $objParcela->fetchRow("cd_projeto = {$post['cd_projeto_aceite_proposta']} and cd_proposta = {$post['cd_proposta_aceite_proposta']} and ni_parcela = 1");

		$addRow = array();
		if ($post['st_aceite_proposta'] == 'S')	{
			$addRow['st_aceite_parcela']              = $post['st_aceite_proposta'];
			$addRow['dt_aceite_parcela']              = date('Y-m-d H:i:s');
			$addRow['tx_obs_aceite_parcela']          = $post['tx_obs_aceite_proposta'];
			$addRow['cd_profissional_aceite_parcela'] = $_SESSION["oasis_logged"][0]["cd_profissional"];
		}else{
			$addRow['st_fechamento_parcela']          = null;
			$addRow['dt_fechamento_parcela']          = null;
			$addRow['cd_prof_fechamento_parcela']     = null;
			$addRow['st_parecer_tecnico_parcela']     = null;
			$addRow['dt_parecer_tecnico_parcela']     = null;
			$addRow['tx_obs_parecer_tecnico_parcela'] = null;
			$addRow['cd_prof_parecer_tecnico_parc']   = null;
			$addRow['st_aceite_parcela']              = null;
			$addRow['dt_aceite_parcela']              = null;
			$addRow['tx_obs_aceite_parcela']          = null;
			$addRow['cd_profissional_aceite_parcela'] = null;
		}

		$erros = $objProcessamentoParcela->atualizaProcessamentoParcela($post['cd_projeto_aceite_proposta'], $post['cd_proposta_aceite_proposta'], $rowPrimeiraParcela->cd_parcela, $addRow);

		return $erros;
	}
}