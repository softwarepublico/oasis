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

class HomologacaoPropostaController extends Base_Controller_Action
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

		$this->view->cd_projeto	 = $post['cd_projeto_homologacao_proposta'];
		$this->view->cd_proposta = $post['cd_proposta_homologacao_proposta'];
		$this->view->tx_projeto	 = $post['projeto_proposta_homologacao'];

	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		//atualiza os campos de homologação da proposta
		//na tabela s_processamento_proposta
		if ($erros === false){
			$erros = $this->salvaHomologacaoProposta($post);
		}

		//atualiza os campos de homologação da solicitação de serviço
		//que solicitou a proposta
		if ($erros === false){
			$erros = $this->atualizaHomologacaoSolicitacaoProposta($post);
		}

		//se a proposta é a proposta de código 1
		//ela possui uma parcela criada automaticamente no momento da criação da proposta
		//Após a primeira alteração de proposta, após ela ter sido processada
		//se a quantidade horas da proposta for alterada,
		//será criada automaticamente um parcela com a diferença de horas do orçamento
		//Estas parcelas criadas automaticamente criadas são as parcelas adicionais
		//de uma proposta
		//Esta função atualiza os dados de homologação de uma parcela adicional
		//de orçamento de proposta
		if ($erros === false && $post['cd_proposta_homologacao_proposta'] == 1){
			$this->_parcelaOrcamento->verificaIndicadorParcelaOrcamento($post['cd_projeto_homologacao_proposta']);
			if ($this->_parcelaOrcamento->getStParcelaOrcamento() == 'S') {
				$erros = $this->atualizaDadosParcelaAdicional($post);
			}
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail               = new Base_Controller_Action_Helper_Mail();
				$arrInf['cd_projeto']   = $post['cd_projeto_homologacao_proposta'];
				$arrInf['cd_proposta']  = $post['cd_proposta_homologacao_proposta'];
				$arrInf['st_msg_email'] = $_SESSION['st_msg_email'];
				$arrInf['_tx_obs']      = $_SESSION['tx_obs'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
				unset ($_SESSION['st_msg_email']);
				unset ($_SESSION['tx_obs']);
			}
		}
	}

	public function salvaHomologacaoProposta($post)
	{
		$erros = false;

		$cd_projeto  = $post['cd_projeto_homologacao_proposta'];
		$cd_proposta = $post['cd_proposta_homologacao_proposta'];

		$objProcessamentoProposta = new ProcessamentoProposta($this->_request->getControllerName());

		$addRow = array();
		$addRow['st_homologacao_proposta']      = $post['st_homologacao_proposta'];
		$addRow['dt_homologacao_proposta']      = date('Y-m-d H:i:s');
		$addRow['tx_obs_homologacao_proposta']  = $post['tx_obs_homologacao_proposta'];
		$addRow['cd_prof_homologacao_proposta'] = $_SESSION["oasis_logged"][0]["cd_profissional"];

		if ($post['st_homologacao_proposta'] == 'N'){
			$addRow['st_ativo'] = null;
		}

		if (K_ENVIAR_EMAIL == "S") {
			$_SESSION['st_msg_email'] = $post['st_homologacao_proposta'];
			$_SESSION['tx_obs']       = $post['tx_obs_homologacao_proposta'];
		}
		
		$erros = $objProcessamentoProposta->atualizaProcessamentoProposta($cd_projeto, $cd_proposta, $addRow);
		return $erros;
	}

	public function atualizaHomologacaoSolicitacaoProposta($post)
	{
		$erros = false;

		$addRow                       = array();
		$addRow['st_homologacao']     = 'S';
		$addRow['dt_homologacao']     = date('Y-m-d H:i:s');
		$addRow['tx_obs_homologacao'] = $post['tx_obs_homologacao_proposta'];

		$proposta = new Proposta($this->_request->getControllerName());
		$rowProposta = $proposta->fetchRow("cd_projeto = {$post['cd_projeto_homologacao_proposta']} and cd_proposta = {$post['cd_proposta_homologacao_proposta']}");

		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$erros = $solicitacao->atualizaSolicitacao($rowProposta->cd_objeto, $rowProposta->ni_solicitacao, $rowProposta->ni_ano_solicitacao, $addRow);

		return $erros;
	}

	public function atualizaDadosParcelaAdicional($post)
	{
		$erros = false;

		$objParcela  = new Parcela($this->_request->getControllerName());
		$objProposta = new Proposta($this->_request->getControllerName());
		$rowProposta = $objProposta->fetchRow("cd_projeto = {$post['cd_projeto_homologacao_proposta']} and cd_proposta = {$post['cd_proposta_homologacao_proposta']}");

		if (is_null($rowProposta->st_alteracao_proposta))
		{
			$rowParcelaAdicional = $objParcela->fetchAll("cd_projeto = {$post['cd_projeto_homologacao_proposta']} and cd_proposta = {$post['cd_proposta_homologacao_proposta']} and ni_parcela = 1");
		}
		else
		{
			$rowParcelaAdicional = $objParcela->fetchAll("cd_projeto = {$post['cd_projeto_homologacao_proposta']} and cd_proposta = {$post['cd_proposta_homologacao_proposta']} and st_modulo_proposta is not null and ni_mes_previsao_parcela is null");
		}

		foreach ($rowParcelaAdicional as $parcelaAdicional)
		{
			if ($erros === false)
			{
				$erros = $this->atualizaHomologacaoParcelaAdicional($post, $parcelaAdicional);
					
				if ($erros === false)
				{
					$erros = $this->atualizaMesAnoPrevisaoExecucaoParcelaAdicional($post, $parcelaAdicional);
				}
			}
		}

		return $erros;
	}

	public function atualizaHomologacaoParcelaAdicional($post, $parcelaAdicional)
	{
		$erros = false;

		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());

		$addRow = array();

		if ($post['st_homologacao_proposta'] == 'A')
		{
			$addRow['st_homologacao_parcela']              = $post['st_homologacao_proposta'];
			$addRow['dt_homologacao_parcela']              = date('Y-m-d H:i:s');
			$addRow['tx_obs_homologacao_parcela']          = $post['tx_obs_homologacao_proposta'];
			$addRow['cd_prof_homologacao_parcela'] = $_SESSION["oasis_logged"][0]["cd_profissional"];
		}
		else
		{
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
			$addRow['st_homologacao_parcela']         = null;
			$addRow['dt_homologacao_parcela']         = null;
			$addRow['tx_obs_homologacao_parcela']     = null;
			$addRow['cd_prof_homologacao_parcela']    = null;
		}

		$erros = $objProcessamentoParcela->atualizaProcessamentoParcela($post['cd_projeto_homologacao_proposta'], $post['cd_proposta_homologacao_proposta'], $parcelaAdicional->cd_parcela, $addRow);

		return $erros;
	}

	public function atualizaMesAnoPrevisaoExecucaoParcelaAdicional($post, $parcelaAdicional)
	{
		$erros = false;

		$objParcela = new Parcela($this->_request->getControllerName());

		$where = "cd_projeto = {$post['cd_projeto_homologacao_proposta']} and cd_proposta = {$post['cd_proposta_homologacao_proposta']} and cd_parcela = {$parcelaAdicional->cd_parcela}";

		$addRow = array();
		$mesCorrente = date("n");
		$anoCorrente = date("Y");
			
		$addRow['ni_mes_previsao_parcela'] = $mesCorrente;
		$addRow['ni_ano_previsao_parcela'] = $anoCorrente;
		$addRow['ni_mes_execucao_parcela'] = $mesCorrente;
		$addRow['ni_ano_execucao_parcela'] = $anoCorrente;

		if (!$objParcela->update($addRow, $where)){
			$erros = true;
		}
		return $erros;
	}
}