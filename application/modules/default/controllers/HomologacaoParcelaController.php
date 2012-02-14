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

class HomologacaoParcelaController extends Base_Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getPost();

		$this->view->cd_projeto 		= $params['cd_projeto'];
		$this->view->cd_proposta 		= $params['cd_proposta'];
		$this->view->cd_parcela 		= $params['cd_parcela'];
		$this->view->ni_parcela 		= $params['ni_parcela'];
		$this->view->tx_sigla_projeto 	= $params['tx_sigla_projeto'];
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
			$erros = $this->salvaHomologacaoParcela($post);
		}

		if ($erros === false){
			$erros = $this->salvaHomologacaoSolicitacao($post);
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail               = new Base_Controller_Action_Helper_Mail();
				$arrInf['cd_projeto']   = $post['cd_projeto_homologacao_parcela'];
				$arrInf['cd_proposta']  = $post['cd_proposta_homologacao_parcela'];
				$arrInf['cd_parcela']   = $post['cd_parcela_homologacao_parcela'];
				$arrInf['st_msg_email'] = $_SESSION['st_msg_email'];
				$arrInf['_tx_obs']      = $_SESSION['tx_obs'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
				unset ($_SESSION['st_msg_email']);
				unset ($_SESSION['tx_obs']);
			}
		}
	}

	public function salvaHomologacaoParcela($post)
	{
		$erros = false;

		$cd_projeto  = $post['cd_projeto_homologacao_parcela'];
		$cd_proposta = $post['cd_proposta_homologacao_parcela'];
		$cd_parcela  = $post['cd_parcela_homologacao_parcela'];

		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());
		$objParcela              = new Parcela($this->_request->getControllerName());

		$addRow = array();
		$addRow['st_homologacao_parcela']              = $post['st_homologacao_parcela'];
		$addRow['dt_homologacao_parcela']              = date('Y-m-d H:i:s');
		$addRow['tx_obs_homologacao_parcela']          = $post['tx_obs_homologacao_parcela'];
		$addRow['cd_prof_homologacao_parcela'] = $_SESSION["oasis_logged"][0]["cd_profissional"];

		if ($post['st_homologacao_parcela'] == 'N')
		{
			//Seta o campo que indica qual o registro ativo para null
			//e guarda o registro atualmente ativo para ser duplicado pela função
			//insereProcessamentoParcelaAvaliacaoNegativa
			$addRow['st_ativo'] = null;
			$oldRow             = $objProcessamentoParcela->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$cd_parcela} and st_ativo = 'S'");
		}
			
		$erros = $objProcessamentoParcela->atualizaProcessamentoParcela($cd_projeto, $cd_proposta, $cd_parcela, $addRow);

		//se conseguiu marcar o registro atualmente ativo para null
		//e gravar os dados do parecer negativo,
		//cria um novo registro ativo para a parcela
		//com campos preenchidos até o ponto de autorização de parcela
		if ($post['st_homologacao_parcela'] == 'N' && $erros === false)
		{
			$erros = $objProcessamentoParcela->insereProcessamentoParcelaAvaliacaoNegativa($cd_projeto, $cd_proposta, $cd_parcela, $oldRow);
		}
		
		//busca o código da ultima parcela da proposta que está sendo homologada
		$ultimaParcelaProposta = $objParcela->getUltimaParcelaProposta($cd_projeto, $cd_proposta);
		
		//se a parcela que está sendo homologada, for a última parcela da proposta,
		//indica a proposta para encerramento, marcando o campo st_encerramento_proposta
		//com a letra "E"
		if ($erros === false && $cd_parcela === $ultimaParcelaProposta)
		{
			$erros = $this->marcaPropostaParaEncerramento($cd_projeto, $cd_proposta);
		}

		if (K_ENVIAR_EMAIL == "S") {
			$_SESSION['st_msg_email'] = $post['st_homologacao_parcela'];
			$_SESSION['tx_obs']       = $post['tx_obs_homologacao_parcela'];
		}

		return $erros;
	}

	public function salvaHomologacaoSolicitacao($post)
	{
		$erros = false;

		$processamentoParcela    = new ProcessamentoParcela($this->_request->getControllerName());
		$rowProcessamentoParcela = $processamentoParcela->fetchRow("cd_projeto = {$post['cd_projeto_homologacao_parcela']} and cd_proposta = {$post['cd_proposta_homologacao_parcela']} and cd_parcela = {$post['cd_parcela_homologacao_parcela']} and st_ativo = 'S'");

		$addRow                       = array();
		$addRow['st_homologacao']     = 'S';
		$addRow['dt_homologacao']     = date('Y-m-d H:i:s');
		$addRow['tx_obs_homologacao'] = $post['tx_obs_homologacao_parcela'];

		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$erros       = $solicitacao->atualizaSolicitacao($rowProcessamentoParcela->cd_objeto_execucao, $rowProcessamentoParcela->ni_solicitacao_execucao, $rowProcessamentoParcela->ni_ano_solicitacao_execucao, $addRow);

		return $erros;
	}
	
	public function marcaPropostaParaEncerramento($cd_projeto, $cd_proposta)
	{
		$erros = false;
		
		$objProposta                        = new Proposta($this->_request->getControllerName());
		$addRow                             = array();
		$addRow['st_encerramento_proposta'] = "E";
		
		$erros = $objProposta->update($addRow, "cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta}");
		
		return $erros;
	}
}