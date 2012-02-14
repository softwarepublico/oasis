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

class AutorizacaoParcelaController extends Base_Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		//desabilita o layout para abrir o pop-up
		$this->_helper->layout->disableLayout();
		
		$params = $this->_request->getPost();
		
		$this->view->cd_projeto			= $params['cd_projeto'	];
		$this->view->cd_proposta		= $params['cd_proposta'	];
		$this->view->cd_parcela			= $params['cd_parcela'	];
		$this->view->ni_parcela			= $params['ni_parcela'	];
		$this->view->tx_sigla_projeto	= trim($params['tx_sigla_projeto']);
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		//Gera uma solicitação de serviço do tipo Execução de Proposta
		//autorizando a execução da parcela
		if ($erros === false){
			$erros = $this->salvaSolicitacaoAutorizacaoParcela($post, $cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		}

		//insere um registro ativo para a parcela na tabela s_processamento_parcela
		//iniciando seu ciclo de vida
		if ($erros === false){
			$erros = $this->salvaAutorizacaoParcela($post, $cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		}

		//atualiza os campos que indicam o mês e ano
		//de execução da parcela
		//de acordo com o mês e ano correntes no momento da autorização
		if ($erros === false){
			$erros = $this->atualizaMesAnoExecucaoParcela($post);
		}
		
		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_AUTORIZAR_PARCELA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_AUTORIZAR_PARCELA');
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail               = new Base_Controller_Action_Helper_Mail();
				$arrInf['cd_projeto']   = $post['cd_projeto_autorizacao_parcela'];
				$arrInf['cd_proposta']  = $post['cd_proposta_autorizacao_parcela'];
				$arrInf['cd_parcela']   = $post['cd_parcela_autorizacao_parcela'];
				$arrInf['_tx_obs']      = $_SESSION['tx_obs'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
				unset ($_SESSION['tx_obs']);
			}
		}
	}

	public function salvaSolicitacaoAutorizacaoParcela($post, &$cd_objeto, &$ni_solicitacao, &$ni_ano_solicitacao)
	{
		$erros = false;
		
		//busca a proposta do projeto
		//para obter a chave da solicitação de serviço
		//que solicitou a proposta
		$objProposta = new Proposta($this->_request->getControllerName());
		$rowProposta = $objProposta->fetchRow("cd_projeto = {$post['cd_projeto_autorizacao_parcela']} and cd_proposta = {$post['cd_proposta_autorizacao_parcela']}");

		//com a chave da solicitação de serviço
		//busca os dados da solicitação de serviço que solicitou o desenvolvimento
		//do projeto da parcela que será autorizada
		//para aproveitamento de alguns dados
		$objSolicitacao = new Solicitacao($this->_request->getControllerName());
		$rowSolicitacao = $objSolicitacao->getSolicitacao($rowProposta->cd_objeto, $rowProposta->ni_solicitacao, $rowProposta->ni_ano_solicitacao);
		
		//busca o contrato ativo ao qual o projeto está associado
		//e com o código do contrato busca o objeto do contrato
		//para ser utilizado no envio da solicitação de execução de proposta
		$contrato       = new Contrato($this->_request->getControllerName());
		$arrContrato    = $contrato->getDadosContratoAtivoObjetoTipoProjeto($post['cd_projeto_autorizacao_parcela']);
		$objetoContrato = new ObjetoContrato($this->_request->getControllerName());
		$arrObjeto      = $objetoContrato->fetchRow(array("cd_contrato = ?"=>$arrContrato["cd_contrato"]))->toArray();

		//prazo fixo para atendimento da solicitação
		//30 dias porque a parcela é mensal
		$rowSolicitacao['ni_prazo_atendimento'] = 30;
		
		//cria nova solicitação
		$newSolicitacao = $objSolicitacao->createRow();
		
		$cd_objeto 						         = $arrObjeto['cd_objeto'];
		$ni_ano_solicitacao 					 = date("Y");
		$ni_solicitacao                          = $objSolicitacao->getNewValueByObjeto($cd_objeto, $ni_ano_solicitacao);
		$newSolicitacao->cd_objeto               = $cd_objeto;
		$newSolicitacao->ni_solicitacao          = $ni_solicitacao;
		$newSolicitacao->ni_ano_solicitacao      = $ni_ano_solicitacao;
		$newSolicitacao->ni_prazo_atendimento    = $rowSolicitacao['ni_prazo_atendimento'];
		$newSolicitacao->cd_unidade              = $rowSolicitacao['cd_unidade'];
		$newSolicitacao->dt_solicitacao          = date('Y-m-d H:i:s');
		
		
		//dados do usuário logado que autoriza a parcela
		$newSolicitacao->tx_solicitante          = $_SESSION['oasis_logged'][0]['tx_profissional'];
		$newSolicitacao->tx_telefone_solicitante = K_DDD_PREFIXO_TELEFONE."-".$_SESSION['oasis_logged'][0]['tx_ramal_profissional'];
		
		//dados setados manualmente pelo fato de a solicitação de serviço
		//do tipo execução de proposta ser feita de forma diferente

        $arrValueMsg = array('value1'=>$post['ni_parcela_autorizacao_parcela'],
                             'value2'=>$post['tx_sigla_projeto_autorizacao_parcela']);

		$newSolicitacao->tx_solicitacao          = Base_Util::getTranslator('L_MSG_EXECUTAR_PARCELA_VARIAVEL_PROJETO_VARIAVEL', $arrValueMsg);
		$newSolicitacao->st_solicitacao          = 2;
		$newSolicitacao->tx_obs_solicitacao      = $post['tx_obs_autorizacao_parcela'];
		
		if (!$newSolicitacao->save()){$erros = true;}

		if (K_ENVIAR_EMAIL == "S") {
			$_SESSION['tx_obs']                  = $post['tx_obs_autorizacao_parcela'];
		}
		
		return $erros;
	}
	
	public function salvaAutorizacaoParcela($post, $cd_objeto, $ni_solicitacao, $ni_ano_solicitacao)
	{
		$erros = false;
		
		$cd_projeto  = $post['cd_projeto_autorizacao_parcela'];
		$cd_proposta = $post['cd_proposta_autorizacao_parcela'];
		$cd_parcela  = $post['cd_parcela_autorizacao_parcela'];		

		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());
		
		$rowProcessamentoParcela = $objProcessamentoParcela->createRow();

		$rowProcessamentoParcela->cd_processamento_parcela            = $objProcessamentoParcela->getNextValueOfField('cd_processamento_parcela');
		$rowProcessamentoParcela->cd_proposta                         = $cd_proposta;
		$rowProcessamentoParcela->cd_projeto                          = $cd_projeto;
		$rowProcessamentoParcela->cd_parcela                          = $cd_parcela;
		$rowProcessamentoParcela->cd_objeto_execucao                  = $cd_objeto;
		$rowProcessamentoParcela->ni_ano_solicitacao_execucao         = $ni_ano_solicitacao;
		$rowProcessamentoParcela->ni_solicitacao_execucao             = $ni_solicitacao;
		$rowProcessamentoParcela->st_autorizacao_parcela              = 'S';
		$rowProcessamentoParcela->dt_autorizacao_parcela              = date('Y-m-d H:i:s');
		$rowProcessamentoParcela->cd_prof_autorizacao_parcela = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoParcela->st_ativo                            = 'S';
		
		if (!$rowProcessamentoParcela->save()){	$erros = true; }
		
		return $erros;
	}
	
	public function atualizaMesAnoExecucaoParcela($post)
	{
		$erros = false;

		$objParcela = new Parcela($this->_request->getControllerName());

		$where = "cd_projeto = {$post['cd_projeto_autorizacao_parcela']} and cd_proposta = {$post['cd_proposta_autorizacao_parcela']} and cd_parcela = {$post['cd_parcela_autorizacao_parcela']}";

		$addRow = array();
		$mesCorrente = date("m");
		$anoCorrente = date("Y");
			
		$addRow['ni_mes_previsao_parcela'] = $mesCorrente;
		$addRow['ni_ano_previsao_parcela'] = $anoCorrente;
		$addRow['ni_mes_execucao_parcela'] = $mesCorrente;
		$addRow['ni_ano_execucao_parcela'] = $anoCorrente;

		$erros = $objParcela->update($addRow, $where);

		return $erros;
	}
}