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

class PropostaController extends Base_Controller_Action
{
	private $_parcelaOrcamento;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_CRIAR_PROPOSTA'));
		$this->zendDate           = new Zend_Date();
		$this->_parcelaOrcamento  = new Base_Controller_Action_Helper_ParcelaOrcamento();
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getParams();

		$this->view->cd_objeto			= $params['cd_objeto'];
		$this->view->ni_solicitacao		= $params['ni_solicitacao'];
		$this->view->ni_ano_solicitacao = $params['ni_ano_solicitacao'];


		//*** PRÉ-PROJETO
		// Executa pesquisa para o combobox de pré projetos
		$preProjeto      = new PreProjeto($this->_request->getControllerName());

		// Utiliza o objeto select para definir um order by da consulta
		$selectPreProjeto = $preProjeto->select();
		$selectPreProjeto->order(array('tx_sigla_pre_projeto'));

		// Recupera os dados e armazena em um array
		$resPreProjeto    = $preProjeto->fetchAll($selectPreProjeto);

		// Cria um array que manterÃ¡ os valores do combobox Pre Projeto
		$preProjetoCombo = array(Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'), 'nenhum' => Base_Util::getTranslator('L_VIEW_COMBO_NENHUM'));

		// Percorre o resultset e adiciona os valores ao array que colocarÃ¡ os valores no combobox
		// O Indice serÃ¡ o value do option do select e o valor sera o label do option do select
		foreach ($resPreProjeto as $nomePreProjeto)
		{
			$preProjetoCombo[$nomePreProjeto->cd_pre_projeto] = $nomePreProjeto->tx_sigla_pre_projeto;
		}

		//*** PRE-PROJETO EVOLUTIVO
		$preProjetoEvolutivo       = new PreProjetoEvolutivo($this->_request->getControllerName());
		$selectPreProjetoEvolutivo = $preProjetoEvolutivo->select()->order("tx_pre_projeto_evolutivo");
		$resPreProjetoEvolutivo    = $preProjetoEvolutivo->fetchAll($selectPreProjetoEvolutivo);
		$preProjetoEvolutivoCombo  = array(Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));
		foreach ($resPreProjetoEvolutivo as $nomePreProjetoEvolutivo)
		{
			$preProjetoEvolutivoCombo[$nomePreProjetoEvolutivo->cd_pre_projeto_evolutivo] = $nomePreProjetoEvolutivo->tx_pre_projeto_evolutivo;
		}

		/* PROJETO
		 * Busca o contrato do objeto para obter a lista de projetos associados ao contrato
		 * que emitiu a solicitação de serviço
		 */
		$objeto      = new ObjetoContrato($this->_request->getControllerName());
		$arrObjeto   = $objeto->fetchRow("cd_objeto = {$params['cd_objeto']}")->toArray();
		$cd_contrato = $arrObjeto["cd_contrato"];

		$projetoContrato = new ContratoProjeto($this->_request->getControllerName());
		$projetoCombo    = $projetoContrato->listaProjetosContrato($cd_contrato, true, false);
		$projetoCombo[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		
		//  Associa este array com um atributo da camada de visao
		$this->view->preProjetoCombo          = $preProjetoCombo;
		$this->view->projetoCombo             = $projetoCombo;
		$this->view->preProjetoEvolutivoCombo = $preProjetoEvolutivoCombo;
	}

	public function criarPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		$erros	= false;
		$post	= $this->_request->getPost();
		

		// Verifica qual tipo de proposta que serÃ¡ criada
		switch ($post['tipo_proposta']) {

			case 'N':

				/*
				 * PROPOSTA NOVA
				 */

				if ( ($post['cd_pre_projeto'] != "0") ) {

					/*
					 ********************************************
					 * CASO O USUÄRIO SELECIONOU UM PRÉ-PROJETO *
					 ********************************************
					 */
					if ( $post['cd_pre_projeto'] != 'nenhum') {


						$erros = $this->criaPropostaPreProjetoSelecionado($post, $cd_projeto);
						
						/*
						 * Associa o Projeto ao Contrato do usuário que está logado
						 */
						if ($erros === false) {
							$erros = $this->associaProjetoCriadoAoContrato($cd_projeto);
						}

						/*
						 * Cria a proposta
						 */
						if ($erros === false) {
							$erros = $this->criaProposta($post, $cd_projeto, $cd_proposta);
						}

						$this->_parcelaOrcamento->verificaIndicadorParcelaOrcamento($cd_projeto);

						if ($this->_parcelaOrcamento->getStParcelaOrcamento() == 'S') {

							/*
							 * Criar parcela 1 - Parcela do orçamento
							 */
							if ($erros === false) {
								$erros = $this->criarPrimeiraParcela($cd_projeto, $cd_proposta, $cd_parcela);
							}

							/*
							 Criar registro de processamento da parcela 1 - Parcela de Orçamento
							 * */
							if ($erros === false){
								$erros = $this->criarRegistroProcessamentoPrimeiraParcela($post, $cd_projeto, $cd_proposta, $cd_parcela);
							}

							/*
							 * Criar produto da parcela 1
							 */
							if ($erros === false) {
								$erros = $this->criarProdutoPrimeiraParcela($cd_projeto, $cd_proposta, $cd_parcela);
							}
						} 
					}  elseif ( $post['cd_pre_projeto'] == 'nenhum') {
						/*
						 ********************************************
						 * CASO O USUÁRIO NÃO SELECIONE UM PRÉ-PROJETO *
						 ********************************************
						 */

						$objProjeto = new Projeto($this->_request->getControllerName());
						$rowProjeto = $objProjeto->createRow();
						$cd_projeto = $objProjeto->getNextValueOfField('cd_projeto');

						$rowProjeto->cd_projeto       = $cd_projeto;
						$rowProjeto->tx_projeto       = $post['tx_projeto'];
						$rowProjeto->tx_sigla_projeto = $post['tx_sigla_projeto'];
						if (!$rowProjeto->save()) {
							$erros = true;
						}
						
						/*
						 * Associa o Projeto ao Contrato do usuário que está logado
						 */
						if ($erros === false) {
							$erros = $this->associaProjetoCriadoAoContrato($cd_projeto);
						}						

						/*
						 * Cria a proposta
						 */
						if ($erros === false) {
							$erros = $this->criaProposta($post, $cd_projeto, $cd_proposta);
						}

						$this->_parcelaOrcamento->verificaIndicadorParcelaOrcamento($cd_projeto);
						
						if ($this->_parcelaOrcamento->getStParcelaOrcamento() == 'S') {
							/*
							 * Criar parcela 1 - Parcela do orçamento
							 */
							if ($erros === false) {
								$erros = $this->criarPrimeiraParcela($cd_projeto, $cd_proposta, $cd_parcela);
							}

							/*
							 Criar registro de processamento da parcela 1 - Parcela de Orçamento
							 * */
							if ($erros === false){
								$erros = $this->criarRegistroProcessamentoPrimeiraParcela($post, $cd_projeto, $cd_proposta, $cd_parcela);
							}

							/*
							 * Criar produto da parcela 1
							 */
							if ($erros === false) {
								$erros = $this->criarProdutoPrimeiraParcela($cd_projeto, $cd_proposta, $cd_parcela);
							}
						}
					}
				}

				break;
			case 'E':

				/*
				 * PROPOSTA EVOLUTIVA
				 */
				if ( ($post['cd_projeto'] != "0") ) {
					
					$erros = $this->criaProposta($post, $post['cd_projeto'], $cd_proposta );
					
					if ($erros === false){
						if ( ($post['cd_pre_projeto_evolutivo'] != '0') ) {
							$erros = $this->atualizaPropostaCriadaPreProjetoEvolutivoSelecionado($post, $cd_proposta);
						}
					}
				}

				break;
			default:
				// somente para compor a estrutura do switch
				break;
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_CRIAR_PROPOSTA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_CRIAR_PROPOSTA');
		}

	}

	public function criarProdutoPrimeiraParcela($cd_projeto, $cd_proposta, $cd_parcela)
	{
		$erros = false;

		$objProdutoParcela 							  = new ProdutoParcela($this->_request->getControllerName());
		$rowProdutoParcela 							  = $objProdutoParcela->createRow();
		$rowProdutoParcela->cd_produto_parcela 	      = $objProdutoParcela->getNextValueOfField('cd_produto_parcela');
		$rowProdutoParcela->cd_projeto  	     	  = $cd_projeto;
		$rowProdutoParcela->cd_proposta 	     	  = $cd_proposta;
		$rowProdutoParcela->cd_parcela	     	      = $cd_parcela;
		$rowProdutoParcela->tx_produto_parcela        = Base_Util::getTranslator('L_VIEW_DOCUMENTO_PROPOSTA');

		if (!$rowProdutoParcela->save()) {
			$erros = true;
		}

		return $erros;
	}

	public function criarRegistroProcessamentoPrimeiraParcela($post, $cd_projeto, $cd_proposta, $cd_parcela)
	{
		$erros = false;

		$objProcessamentoParcela                                      = new ProcessamentoParcela($this->_request->getControllerName());
		$rowProcessamentoParcela                                      = $objProcessamentoParcela->createRow();
		$rowProcessamentoParcela->cd_processamento_parcela            = $objProcessamentoParcela->getNextValueOfField('cd_processamento_parcela');
		$rowProcessamentoParcela->cd_proposta                         = $cd_proposta;
		$rowProcessamentoParcela->cd_projeto                          = $cd_projeto;
		$rowProcessamentoParcela->cd_parcela                          = $cd_parcela;
		$rowProcessamentoParcela->cd_objeto_execucao                  = $post['cd_objeto'];
		$rowProcessamentoParcela->ni_ano_solicitacao_execucao         = $post['ni_ano_solicitacao'];
		$rowProcessamentoParcela->ni_solicitacao_execucao             = $post['ni_solicitacao'];
		$rowProcessamentoParcela->st_autorizacao_parcela              = 'S';
		$rowProcessamentoParcela->dt_autorizacao_parcela              = date('Y-m-d H:i:s');
		$rowProcessamentoParcela->cd_prof_autorizacao_parcela = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoParcela->st_ativo                            = 'S';

		if (!$rowProcessamentoParcela->save())
		{
			$erros = true;
		}

		return $erros;
	}

	public function criarPrimeiraParcela($cd_projeto, $cd_proposta, &$cd_parcela)
	{
		$erros = false;

		$objParcela = new Parcela($this->_request->getControllerName());
		$rowParcela = $objParcela->createRow();
		$cd_parcela = $objParcela->getNextValueOfField('cd_parcela');
		$rowParcela->cd_parcela 	     = $cd_parcela;
		$rowParcela->cd_projeto  	     = $cd_projeto;
		$rowParcela->cd_proposta 	     = $cd_proposta;
		$rowParcela->ni_parcela  	     = 1;
		$rowParcela->ni_horas_parcela    = 0;
		$rowParcela->st_modulo_proposta  = 'S';

		if (!$rowParcela->save()) {
			$erros = true;
		}
		return $erros;
	}


	public function criaProposta($post, $cd_projeto, &$cd_proposta)
	{
		$erros = false;
		/*
		 * Cria a proposta
		 */
		$objProposta = new Proposta($this->_request->getControllerName());
		$rowProposta = $objProposta->createRow();
		$cd_proposta = $objProposta->getNewValueByProjeto($cd_projeto);
		$rowProposta->cd_proposta        = $cd_proposta;
		$rowProposta->cd_projeto         = $cd_projeto;
		$rowProposta->cd_objeto          = $post['cd_objeto'];
		$rowProposta->ni_solicitacao     = $post['ni_solicitacao'];
		$rowProposta->ni_ano_solicitacao = $post['ni_ano_solicitacao'];
		$rowProposta->ni_horas_proposta  = 0;

		if (!$rowProposta->save()) {
			$erros = true;
		}

		return $erros;
	}


	public function criaPropostaPreProjetoSelecionado($post, &$cd_projeto)
	{
		$erros = false;

		/*
		 * Obtem dados do pre-projeto e cria um projeto
		 */
		$objPreProjeto = new PreProjeto($this->_request->getControllerName());
		$resPreProjeto = $objPreProjeto->find($post['cd_pre_projeto'])->current();

		$objProjeto = new Projeto($this->_request->getControllerName());
		$rowProjeto = $objProjeto->createRow();

		$cd_projeto = $objProjeto->getNextValueOfField('cd_projeto');
		$rowProjeto->cd_projeto 					 = $cd_projeto;
		$rowProjeto->cd_unidade 				     = $resPreProjeto->cd_unidade;
		$rowProjeto->cd_profissional_gerente       	 = $resPreProjeto->cd_gerente_pre_projeto;
		$rowProjeto->tx_projeto 					 = $resPreProjeto->tx_pre_projeto;
		$rowProjeto->tx_sigla_projeto                = $resPreProjeto->tx_sigla_pre_projeto;
		$rowProjeto->tx_contexto_geral_projeto       = $resPreProjeto->tx_contexto_geral_pre_projeto;
		$rowProjeto->tx_escopo_projeto		         = $resPreProjeto->tx_escopo_pre_projeto;
		$rowProjeto->tx_gestor_projeto	             = $resPreProjeto->tx_gestor_pre_projeto;
		$rowProjeto->tx_obs_projeto 			 	 = $resPreProjeto->tx_obs_pre_projeto;
		$rowProjeto->st_impacto_projeto		 		 = $resPreProjeto->st_impacto_pre_projeto;
		$rowProjeto->st_prioridade_projeto 		 	 = $resPreProjeto->st_prioridade_pre_projeto;

		if (!$rowProjeto->save()) {
			$erros = true;
		}


		/*
		 * Deleta o pre-projeto
		 */
		if ($erros === false) {
			if (!$objPreProjeto->delete("cd_pre_projeto={$post['cd_pre_projeto']}")) {
				$erros = true;
			}
		}

		return $erros;
	}

	public function fecharPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db    = Zend_Registry::get('db');
		
		//Código inserido para permitir o fechamento de propostas com muitos produtos
		//para evitar timeout
		set_time_limit(120);

		$db->beginTransaction();
		$erros = false;

		$post  = $this->_request->getPost();

		//grava um registro na tabela s_processamento_proposta
		//com st_ativo = 'S' e com st_fechamento_proposta = 'S'
		//indicando que a proposta agora estÃ¡ fechada
		//e foi submetida a processamento
		$erros = $this->fechaProposta($post);


		//Se a proposta for um proposta nova (cÃ³digo 1)
		//o fechamento da proposta tambÃ©m implica no fechamento
		//da primeira parcela, a parcela de orÃ§amento da proposta,
		//se o objeto do contrato ao qual pertence o projeto
		//permitir a parcela de orçamento
		if ($erros === false && $post['cd_proposta'] == 1)
		{
			$this->_parcelaOrcamento->verificaIndicadorParcelaOrcamento($post['cd_projeto']);
			if ($this->_parcelaOrcamento->getStParcelaOrcamento() == 'S') {
				$erros = $this->fechaPrimeiraParcela($post);
			}
		}

		//atualiza a proposta fechada
		//gravando o mÃªs e o ano de fechamento
		//para facilitar a consulta de propostas
		//na tela de Controle de Propostas
		if ($erros === false) {
			$erros = $this->atualizaMesAnoProposta($post);
		}

		//atualiza a solicitacao de servico do tipo 'Solicitacao de Proposta'
		//que solicitou a elaboracao da proposta que foi fechada
		//gravando a data de tÃ©rmino da solicitacao de servico
		if ($erros === false) {
			$erros = $this->fechaSolicitacaoProposta($post);
		}

		//grava um historico da proposta que foi fechada
		//gravando os dados do projeto, da proposta, da mÃ©trica PMD,
		//das parcelas da proposta e dos produtos das parcelas
		if ($erros === false)
		{
			$erros = $this->gravaHistoricoPropostaFechada($post);
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_FECHAR_PROPOSTA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_PROPOSTA');
			if (K_ENVIAR_EMAIL == 'S') {
				$_objMail = new Base_Controller_Action_Helper_Mail();
				$_objMail->enviaEmail($_SESSION['oasis_logged'][0]['cd_objeto'], $this->_request->getControllerName(), $arrDados);
			}
		}
		unset($_SESSION["dt_fechamento_proposta"]);
	}

	public function fechaProposta($post)
	{
		$erros = false;

		$objProcessamentoProposta = new ProcessamentoProposta($this->_request->getControllerName());
		$rowProcessamentoProposta = $objProcessamentoProposta->createRow();

		$cd_projeto  = $post['cd_projeto'];
		$cd_proposta = $post['cd_proposta'];

		$rowProcessamentoProposta->cd_processamento_proposta           = $objProcessamentoProposta->getNextValueOfField('cd_processamento_proposta');
		$rowProcessamentoProposta->cd_projeto                          = $cd_projeto;
		$rowProcessamentoProposta->cd_proposta                         = $cd_proposta;
		$rowProcessamentoProposta->st_fechamento_proposta	           = 'S';
		$dt_fechamento_proposta                                        = date('Y-m-d H:i:s');
		$rowProcessamentoProposta->dt_fechamento_proposta	           = $dt_fechamento_proposta;
		$rowProcessamentoProposta->cd_prof_fechamento_proposta = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoProposta->st_ativo	                           = 'S';
		$_SESSION["dt_fechamento_proposta"]                            = $dt_fechamento_proposta;

		if (!$rowProcessamentoProposta->save()){
			$erros = true;
		}

		return $erros;
	}

	public function fechaPrimeiraParcela($post)
	{
		$erros = false;

		$objParcela              = new Parcela($this->_request->getControllerName());
		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());
		$rowPrimeiraParcela      = $objParcela->fetchRow("cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']} and ni_parcela = 1");

		$addRow                                       = array();
		$addRow['st_fechamento_parcela']              = 'S';
		$addRow['dt_fechamento_parcela']              = date('Y-m-d H:i:s');
		$addRow['cd_prof_fechamento_parcela'] = $_SESSION["oasis_logged"][0]["cd_profissional"];

		$erros = $objProcessamentoParcela->atualizaProcessamentoParcela($post['cd_projeto'], $post['cd_proposta'], $rowPrimeiraParcela->cd_parcela, $addRow);

		return $erros;
	}

	public function atualizaMesAnoProposta($post)
	{
		$erros = false;

		$objProposta = new Proposta($this->_request->getControllerName());

		$addRow = array();
		$addRow['ni_mes_proposta'] = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		$addRow['ni_ano_proposta'] = $this->zendDate->get(Zend_Date::YEAR);

		$row = $objProposta->fetchRow("cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']}");
		if (!is_null($row))
		{
			if (!$objProposta->update($addRow, "cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']}"))
			{
				$erros = true;
			}
		}

		return $erros;
	}

	public function fechaSolicitacaoProposta($post)
	{
		$erros = false;

		$addRow                  = array();
		$addRow['st_fechamento'] = 'S';
		$addRow['dt_fechamento'] = date('Y-m-d H:i:s');

		$proposta = new Proposta($this->_request->getControllerName());
		$rowProposta = $proposta->fetchRow("cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']}");

		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$erros = $solicitacao->atualizaSolicitacao($rowProposta->cd_objeto, $rowProposta->ni_solicitacao, $rowProposta->ni_ano_solicitacao, $addRow);

		return $erros;
	}

	public function gravaHistoricoPropostaFechada($post)
	{
		$erros = false;

		//grava um historico dos dados do projeto ao qual pertence a proposta
		//na tabela s_historico_proposta
		//NÃ£o sÃ£o gravados cÃ³digos, visto que a descriÃ§Ã£o destes pode mudar
		//SÃ£o gravadas as descriÃ§Ãµes dos campos
		if ($erros === false) {
			$erros = $this->gravaHistoricoProposta($post, $dt_historico_proposta);
		}

		//grava um histÃ³rico da mÃ©trica da proposta fechada
		//na tabela s_historico_proposta_metrica
		//SÃ£o gravados os valores unitarios para cada métrica
		//que a proposta possuir
		if ($erros === false)
		{
			$erros = $this->gravaHistoricoPropostaMetrica($post);
		}

		//grava um historico de cada parcela da proposta
		//na tabela s_historico_proposta_parcela
		//e para cada parcela da proposta, grava um historico de cada produto de parcela
		//na tabela s_historico_proposta_produto
		if ($erros === false) {
			$erros = $this->gravaHistoricoPropostaParcela($post, $dt_historico_proposta);
		}

		return $erros;
	}

	public function gravaHistoricoProposta($post, &$dt_historico_proposta)
	{
		$erros = false;

		//busca os dados do projeto
		$objProjeto = new Projeto($this->_request->getControllerName());
		$resProjeto = $objProjeto->find($post['cd_projeto'])->current();

		//busca a sigla da unidade
		$objUnidade = new Unidade($this->_request->getControllerName());
		$resUnidade = $objUnidade->find($resProjeto->cd_unidade)->current();

		//busca o gerente do projeto
		$objGerenteProjeto = new Profissional($this->_request->getControllerName());
		$resGerenteProjeto = $objGerenteProjeto->find($resProjeto->cd_profissional_gerente)->current();

		//busca as horas totais da proposta
		$objProposta = new Proposta($this->_request->getControllerName());
		$resProposta = $objProposta->fetchRow("cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']}");

		$objHistoricoProposta  = new HistoricoProposta($this->_request->getControllerName());
		$rowHistoricoProposta  = $objHistoricoProposta->createRow();

		$dt_historico_proposta = $_SESSION["dt_fechamento_proposta"];

		$rowHistoricoProposta->dt_historico_proposta = $dt_historico_proposta;
		$rowHistoricoProposta->cd_projeto            = $post['cd_projeto'];
		$rowHistoricoProposta->cd_proposta           = $post['cd_proposta'];
		$rowHistoricoProposta->ni_horas_proposta     = $resProposta->ni_horas_proposta;
		$rowHistoricoProposta->tx_sigla_projeto		 = $resProjeto->tx_sigla_projeto;
		$rowHistoricoProposta->tx_projeto            = $resProjeto->tx_projeto;
		$rowHistoricoProposta->tx_contexdo_geral     = $resProjeto->tx_contexto_geral_projeto;
		$rowHistoricoProposta->tx_escopo_projeto     = $resProjeto->tx_escopo_projeto;
		$rowHistoricoProposta->tx_sigla_unidade      = $resUnidade->tx_sigla_unidade;
		$rowHistoricoProposta->tx_gestor_projeto     = $resProjeto->tx_gestor_projeto;
		$rowHistoricoProposta->tx_impacto_projeto    = $resProjeto->st_impacto_projeto;
		$rowHistoricoProposta->tx_gerente_projeto    = $resGerenteProjeto->tx_profissional;
		$rowHistoricoProposta->st_metrica_historico  = 'SWN';
		$rowHistoricoProposta->tx_inicio_previsto    = $resProjeto->ni_mes_inicio_previsto . "/" . $resProjeto->ni_ano_inicio_previsto;
		$rowHistoricoProposta->tx_termino_previsto   = $resProjeto->ni_mes_termino_previsto . "/" . $resProjeto->ni_ano_termino_previsto;


		if (!$rowHistoricoProposta->save())
		{
			$erros = true;
		}

		return $erros;
	}

	public function gravaHistoricoPropostaMetrica($post)
	{
		$erros = false;

		$objHistoricoPropostaMetrica = new HistoricoPropostaMetrica($this->_request->getControllerName());
		$rowHistoricoPropostaMetrica = array();

		//busca os dados da métrica
		$objPropostaDefinicaoMetrica = new PropostaDefinicaoMetrica($this->_request->getControllerName());
		$resPropostaDefinicaoMetrica = $objPropostaDefinicaoMetrica->fetchAll("cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']}");

		foreach ($resPropostaDefinicaoMetrica as $metrica)
		{
			$metrica = $metrica->toArray();	
			if ($erros === false)
			{
				$rowHistoricoPropostaMetrica["cd_projeto"]                             = $post['cd_projeto'];
				$rowHistoricoPropostaMetrica["cd_proposta"]                            = $post['cd_proposta'];
				$rowHistoricoPropostaMetrica["dt_historico_proposta"]                  = $_SESSION["dt_fechamento_proposta"];
				$rowHistoricoPropostaMetrica["cd_definicao_metrica"]                   = $metrica["cd_definicao_metrica"];
				$rowHistoricoPropostaMetrica["ni_um_prop_metrica_historico"]    = $metrica["ni_horas_proposta_metrica"];
				$rowHistoricoPropostaMetrica["tx_just_metrica_historico"]     = (is_null($metrica["tx_justificativa_metrica"])||empty($metrica["tx_justificativa_metrica"]))?null:$metrica["tx_justificativa_metrica"];
				if (!$objHistoricoPropostaMetrica->insert($rowHistoricoPropostaMetrica))
				{
					$erros = true;
				}
				else
				{
					$erros = $this->gravaHistoricoPropostaSubItemMetrica($post, $metrica["cd_definicao_metrica"]);
				}
			}
		}
		
		return $erros;
	}
	
	public function gravaHistoricoPropostaSubItemMetrica($post, $cd_definicao_metrica)
	{
		$erros = false;

		$objHistoricoPropostaSubItemMetrica = new HistoricoPropostaSubItemMetrica($this->_request->getControllerName());
		$rowHistoricoPropostaSubItemMetrica = array();

		//busca os dados dos subitens métrica
		$objPropostaSubItemMetrica = new PropostaSubItemMetrica($this->_request->getControllerName());
		$resPropostaSubItemMetrica = $objPropostaSubItemMetrica->fetchAll("cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']} and cd_definicao_metrica = {$cd_definicao_metrica}");

		foreach ($resPropostaSubItemMetrica as $subItemMetrica)
		{
			$subItemMetrica = $subItemMetrica->toArray();	
			if ($erros === false)
			{
				$rowHistoricoPropostaSubItemMetrica["cd_projeto"]                = $post['cd_projeto'];
				$rowHistoricoPropostaSubItemMetrica["cd_proposta"]               = $post['cd_proposta'];
				$rowHistoricoPropostaSubItemMetrica["dt_historico_proposta"]     = $_SESSION["dt_fechamento_proposta"];
				$rowHistoricoPropostaSubItemMetrica["cd_definicao_metrica"]      = $subItemMetrica["cd_definicao_metrica"];
				$rowHistoricoPropostaSubItemMetrica["cd_item_metrica"]           = $subItemMetrica["cd_item_metrica"];
				$rowHistoricoPropostaSubItemMetrica["cd_sub_item_metrica"]       = $subItemMetrica["cd_sub_item_metrica"];
				$rowHistoricoPropostaSubItemMetrica["ni_valor_sub_item_metrica"] = $subItemMetrica["ni_valor_sub_item_metrica"];
				
				if (!$objHistoricoPropostaSubItemMetrica->insert($rowHistoricoPropostaSubItemMetrica))
				{
					$erros = true;
				}
			}
		}
		
		return $erros;
	}

	public function gravaHistoricoPropostaParcela($post, $dt_historico_proposta)
	{
		$erros = false;

		$objParcela = new Parcela($this->_request->getControllerName());
		$resParcela = $objParcela->fetchAll("cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']}");

		$objHistoricoPropostaParcela = new HistoricoPropostaParcela($this->_request->getControllerName());

		foreach ($resParcela as $parcela){
			if ($erros === false){
				$rowHistoricoPropostaParcela   = $objHistoricoPropostaParcela->createRow();
				$cd_historico_proposta_parcela = $objHistoricoPropostaParcela->getNextValueOfField('cd_historico_proposta_parcela');

				$rowHistoricoPropostaParcela->cd_proposta                   = $post['cd_proposta'];
				$rowHistoricoPropostaParcela->cd_projeto                    = $post['cd_projeto'];
				$rowHistoricoPropostaParcela->dt_historico_proposta         = $dt_historico_proposta;
				$rowHistoricoPropostaParcela->cd_historico_proposta_parcela = $cd_historico_proposta_parcela;
				$rowHistoricoPropostaParcela->ni_parcela                    = $parcela->ni_parcela;
				$rowHistoricoPropostaParcela->ni_mes_previsao_parcela       = $parcela->ni_mes_previsao_parcela;
				$rowHistoricoPropostaParcela->ni_ano_previsao_parcela       = $parcela->ni_ano_previsao_parcela;
				$rowHistoricoPropostaParcela->ni_horas_parcela              = $parcela->ni_horas_parcela;

				$cd_parcela = $parcela->cd_parcela;

				if ($rowHistoricoPropostaParcela->save()){
					$erros = $this->gravaHistoricoPropostaProduto($post, $dt_historico_proposta, $cd_parcela, $cd_historico_proposta_parcela);
				}else{
					$erros = true;
				}
			}
		}
		return $erros;
	}

	public function gravaHistoricoPropostaProduto($post, $dt_historico_proposta, $cd_parcela, $cd_historico_proposta_parcela)
	{
		$erros = false;

		$objProduto = new ProdutoParcela($this->_request->getControllerName());
		$resProduto = $objProduto->fetchAll(array(
                "cd_projeto = ?"=> $post['cd_projeto'],
                "cd_proposta = ?"=>$post['cd_proposta'],
                "cd_parcela = ?"=> $cd_parcela
            )
        );

		$objHistoricoPropostaProduto = new HistoricoPropostaProduto($this->_request->getControllerName());

		foreach ($resProduto as $produto){
			if ($erros === false){
				$rowHistoricoPropostaProduto   = $objHistoricoPropostaProduto->createRow();
				$cd_historico_proposta_produto = $objHistoricoPropostaProduto->getNextValueOfField('cd_historico_proposta_produto');

				$rowHistoricoPropostaProduto->cd_historico_proposta_produto = $cd_historico_proposta_produto;
				$rowHistoricoPropostaProduto->dt_historico_proposta         = $dt_historico_proposta;
				$rowHistoricoPropostaProduto->cd_projeto                    = $post['cd_projeto'];
				$rowHistoricoPropostaProduto->cd_proposta                   = $post['cd_proposta'];
				$rowHistoricoPropostaProduto->cd_historico_proposta_parcela = $cd_historico_proposta_parcela;;
				$rowHistoricoPropostaProduto->tx_produto                    = $produto->tx_produto_parcela;
				$rowHistoricoPropostaProduto->cd_tipo_produto               = $produto->cd_tipo_produto;

				if (!$rowHistoricoPropostaProduto->save()){
					$erros = true;
				}
			}
		}
		return $erros;
	}

	public function encerrarPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post  = $this->_request->getPost();
		
		$objProposta = new Proposta($this->_request->getControllerName());

		$addRow = array();
		$addRow['st_encerramento_proposta']      = "S";
		$addRow['dt_encerramento_proposta']      = date('Y-m-d H:i:s');
		$addRow['cd_prof_encerramento_proposta'] = $_SESSION["oasis_logged"][0]["cd_profissional"];
		
		if ($objProposta->update($addRow, array("cd_projeto = ?"=>$post['cd_projeto'],"cd_proposta = ?"=>$post['cd_proposta']))){
			echo Base_Util::getTranslator('L_MSG_SUCESS_ENCERRADA_PROPOSTA');
		}else{
			echo Base_Util::getTranslator('L_MSG_ERRO_ENCERRADA_PROPOSTA');
		}
	}

	public function pesquisaPropostaAbertaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = (int)$this->_request->getParam('cd_projeto', 0);
		
		if($cd_projeto != 0){
			$proposta = new Proposta($this->_request->getControllerName());
			$res = $proposta->getPropostaAbertaHistorico($cd_projeto);
		} 

		$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		if($res){
			foreach ($res as $cd_proposta) {
				$strOptions .= "<option value=\"{$cd_proposta}\">".Base_Util::getTranslator('L_VIEW_PROPOSTA')." N.{$cd_proposta}</option>";
			}
		}
		echo $strOptions;
	}
	
	public function pesquisaPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = (int)$this->_request->getParam('cd_projeto', 0);
		
		$proposta = new Proposta($this->_request->getControllerName());
		$res = $proposta->getProposta($cd_projeto);

		$strOptions = "";
		foreach ($res as $key => $value) {
			$strOptions .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $strOptions;
	}
	
	public function salvarObjetivoPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto           = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_proposta          = (int)$this->_request->getParam('cd_proposta', 0);
		$tx_objetivo_proposta = $this->_request->getParam('tx_objetivo_proposta', null);
		
		$upRow = array();
		$upRow['tx_objetivo_proposta'] = $tx_objetivo_proposta;
		$upRow['st_objetivo_proposta'] = (($tx_objetivo_proposta === '') || ($tx_objetivo_proposta === null)) ? 0 : 1;
		
		$where = array(
            "cd_projeto = ?"=>$cd_projeto,
            "cd_proposta = ?"=>$cd_proposta
        );
		
		$proposta = new Proposta($this->_request->getControllerName());
		
		if ($proposta->update($upRow, $where)){
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	public function atualizaPropostaCriadaPreProjetoEvolutivoSelecionado($post, $cd_proposta)
	{
		$erros = false;

		$objProposta            = new Proposta($this->_request->getControllerName());
		$preProjetoEvolutivo    = new PreProjetoEvolutivo($this->_request->getControllerName());
		$resPreProjetoEvolutivo = $preProjetoEvolutivo->getDadosPreProjetoEvolutivo($post["cd_pre_projeto_evolutivo"]);

		$arrDados                         = array();
		$arrDados["tx_objetivo_proposta"] = "{$resPreProjetoEvolutivo["tx_pre_projeto_evolutivo"]} - {$resPreProjetoEvolutivo["tx_objetivo_pre_proj_evol"]}";
		$where                            = "cd_projeto = {$post["cd_projeto"]} and cd_proposta = {$cd_proposta}";
		
		if (!$objProposta->update($arrDados, $where)) {
			$erros = true;
		}

		/*
		 * Deleta o pre-projeto evolutivo
		 */
		if ($erros === false) {
			if (!$preProjetoEvolutivo->delete("cd_pre_projeto_evolutivo = {$post['cd_pre_projeto_evolutivo']}")) {
				$erros = true;
			}
		}

		return $erros;
	}

	public function associaProjetoCriadoAoContrato($cd_projeto)
	{
		$erros = false;
		
		$contratoProjeto    = new ContratoProjeto($this->_request->getControllerName());
		$rowContratoProjeto = $contratoProjeto->createRow();
		
		$rowContratoProjeto->cd_contrato = $_SESSION["oasis_logged"][0]["cd_contrato"];
		$rowContratoProjeto->cd_projeto  = $cd_projeto;
		
		if (!$rowContratoProjeto->save()){
			$erros = true;
		}
		
		return $erros;
	}
}