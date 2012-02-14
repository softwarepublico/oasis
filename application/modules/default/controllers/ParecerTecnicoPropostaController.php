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

class ParecerTecnicoPropostaController extends Base_Controller_Action
{
	private $_parcelaOrcamento;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PARECER_TECNICO_PROPOSTA'));
		$this->_parcelaOrcamento = new Base_Controller_Action_Helper_ParcelaOrcamento();
	}

	public function indexAction()
	{
		//desabilita o layout para montar o POP-UP
		$this->_helper->layout->disableLayout();

		//recebe os parametros a serem enviados para o POP-UP
		$post = $this->_request->getPost();

		//*** ITEM PARECER TECNICO PROPOSTA
		// Executa pesquisa para carregar o array do item-parecer-tecnico
		$itemParecerTecnico      = new ItemParecerTecnico($this->_request->getControllerName());

		// Utiliza o objeto select para definir um order by da consulta
		$selectItemParecerTecnico = $itemParecerTecnico->select()
													   ->where('st_proposta is not null');

		// Recupera os dados e armazena em um array
		$resItemParecerTecnico    = $itemParecerTecnico->fetchAll($selectItemParecerTecnico);

		//  Associa este array com um atributo da camada de visao
		$this->view->listaItemParecerTecnico = $resItemParecerTecnico;
		$this->view->cd_projeto				 = $post['cd_projeto_parecer_tecnico_proposta'];
		$this->view->cd_proposta			 = $post['cd_proposta_parecer_tecnico_proposta'];
		$this->view->tx_projeto				 = $post['projeto_proposta_parecer_tecnico'];
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		//atualiza a tabela s_processamento_proposta
		//setando o flag de parecer técnico
		if ($erros === false){
			$erros = $this->atualizaParecerTecnicoProposta($post, $cd_processamento_proposta);
		}

		//grava a avaliação do parecer ténico
		//na tabela a_parecer_tecnico_proposta
		//com a avaliação para cada item de parecer técnico
		if ($erros === false){
			$erros = $this->gravaParecerTecnicoProposta($post, $cd_processamento_proposta);
		}

		//Se a proposta for código 1, ela é a proposta inicial do projeto
		//e se o objeto do contrato permitir, possui uma parcela de pagamento do orçamento, criada automaticamente
		//no momento da criação da proposta
		//O número desta parcela é sempre 1
		//Seta o flag de parecer técnico para esta parcela de número 1
		if ($erros === false && $post['cd_proposta_parecer_tecnico_proposta'] == 1){
			$this->_parcelaOrcamento->verificaIndicadorParcelaOrcamento($post['cd_projeto_parecer_tecnico_proposta']);
			if ($this->_parcelaOrcamento->getStParcelaOrcamento() == 'S') {
				$erros = $this->gravaParecerTecnicoPrimeiraParcela($post);
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
				$arrInf['cd_projeto']   = $post['cd_projeto_parecer_tecnico_proposta'];
				$arrInf['cd_proposta']  = $post['cd_proposta_parecer_tecnico_proposta'];
				$arrInf['st_msg_email'] = $_SESSION['st_msg_email'];
				$arrInf['_tx_obs']      = $_SESSION['tx_obs'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
				unset ($_SESSION['st_msg_email']);
				unset ($_SESSION['tx_obs']);
			}
		}
	}

	public function atualizaParecerTecnicoProposta($post, &$cd_processamento_proposta)
	{
		$erros = false;

		$cd_projeto  = $post['cd_projeto_parecer_tecnico_proposta'];
		$cd_proposta = $post['cd_proposta_parecer_tecnico_proposta'];

		$objProcessamentoProposta = new ProcessamentoProposta($this->_request->getControllerName());

		//busca o registro de processamento da proposta
		//na tabela s_processamento_proposta
		//para obter o código sequencial do registro
		//para uso na associativa a_parecer_tecnico_proposta
		//no método gravaParecerTecnicoProposta
		$rowProcessamentoProposta  = $objProcessamentoProposta->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and st_ativo = 'S'");
		$cd_processamento_proposta = $rowProcessamentoProposta->cd_processamento_proposta;

		//seta os campos a serem atualizados na tabela s_processamento_parcela
		$addRow = array();
		$addRow['st_parecer_tecnico_proposta']    = $post['st_parecer_tecnico_proposta'];
		$addRow['dt_parecer_tecnico_proposta']    = date('Y-m-d H:i:s');
		$addRow['tx_obs_parecer_tecnico_prop']    = $post['tx_obs_parecer_tecnico_prop'];
		$addRow['cd_prof_parecer_tecnico_propos'] = $_SESSION["oasis_logged"][0]["cd_profissional"];

		if ($post['st_parecer_tecnico_proposta'] == 'N'){
            if (K_PARECER_TECNICO_NEGATIVO_COORDENACAO == 'N') {
    			$addRow['st_ativo'] = null;
            }  
 		}

		if (K_ENVIAR_EMAIL == "S") {
			$_SESSION['st_msg_email'] = $post['st_parecer_tecnico_proposta'];
			$_SESSION['tx_obs']       = $post['tx_obs_parecer_tecnico_prop'];
		}

		$erros = $objProcessamentoProposta->atualizaProcessamentoProposta($cd_projeto, $cd_proposta, $addRow);

		return $erros;
	}

	public function gravaParecerTecnicoProposta($post, $cd_processamento_proposta)
	{
		$erros = false;

		$objParecerTecnicoProposta = new ParecerTecnicoProposta($this->_request->getControllerName());

		foreach($post['cd_item_parecer_tecnico'] as $cd_item_parecer_tecnico => $st_avaliacao){
			if ($erros === false){
				$rowParecerTecnicoProposta                            = $objParecerTecnicoProposta->createRow();
				$rowParecerTecnicoProposta->cd_item_parecer_tecnico   = $cd_item_parecer_tecnico;
				$rowParecerTecnicoProposta->cd_proposta               = $post['cd_proposta_parecer_tecnico_proposta'];
				$rowParecerTecnicoProposta->cd_projeto                = $post['cd_projeto_parecer_tecnico_proposta'];
				$rowParecerTecnicoProposta->cd_processamento_proposta = $cd_processamento_proposta;
				$rowParecerTecnicoProposta->st_avaliacao              = $st_avaliacao;

				if (!$rowParecerTecnicoProposta->save()){
					$erros = true;
				}
			}
		}
		return $erros;
	}
	
	public function gravaParecerTecnicoPrimeiraParcela($post)
	{
		$erros = false;

		$objParcela              = new Parcela($this->_request->getControllerName());
		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());
		$rowPrimeiraParcela      = $objParcela->fetchRow("cd_projeto = {$post['cd_projeto_parecer_tecnico_proposta']} and cd_proposta = {$post['cd_proposta_parecer_tecnico_proposta']} and ni_parcela = 1");

		$addRow = array();

		if ($post['st_parecer_tecnico_proposta'] == 'A'){
			$addRow['st_parecer_tecnico_parcela']              = $post['st_parecer_tecnico_proposta'];
			$addRow['dt_parecer_tecnico_parcela']              = date('Y-m-d H:i:s');
			$addRow['tx_obs_parecer_tecnico_parcela']          = $post['tx_obs_parecer_tecnico_prop'];
			$addRow['cd_prof_parecer_tecnico_parc'] = $_SESSION["oasis_logged"][0]["cd_profissional"];
		}else{
			$addRow['st_fechamento_parcela']                   = null;
			$addRow['dt_fechamento_parcela']                   = null;
			$addRow['cd_prof_fechamento_parcela']      = null;
			$addRow['st_parecer_tecnico_parcela']              = null;
			$addRow['dt_parecer_tecnico_parcela']              = null;
			$addRow['tx_obs_parecer_tecnico_parcela']          = null;
			$addRow['cd_prof_parecer_tecnico_parc'] = null;
		}

		$erros = $objProcessamentoParcela->atualizaProcessamentoParcela($post['cd_projeto_parecer_tecnico_proposta'], $post['cd_proposta_parecer_tecnico_proposta'], $rowPrimeiraParcela->cd_parcela, $addRow);

		return $erros;
	}
}