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

class ProcessamentoParcelaController extends Base_Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{

	}

	public function fecharParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		//atualiza a tabela s_processamento_parcela
		//setando o flag de fechamento do registro ativo para a parcela
		if ($erros === false)
		{
			$erros = $this->fechaParcela($post);
		}

		//fecha a solicitação de serviço do tipo "Execução de Proposta"
		//que autorizou a execução da parcela
		if ($erros === false)
		{
			$erros = $this->fechaSolicitacaoExecucaoParcela($post);
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_FECHAR_PARCELA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_PARCELA');
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail               = new Base_Controller_Action_Helper_Mail();
				$arrInf['cd_projeto']   = $post['cd_projeto'];
				$arrInf['cd_proposta']  = $post['cd_proposta'];
				$arrInf['cd_parcela']   = $post['cd_parcela'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
			}
		}

	}

	public function fechaParcela($post)
	{
		$erros = false;

		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());

		if ($erros === false)
		{
			$addRow = array();
				
			$addRow['st_fechamento_parcela']              = 'S';
			$addRow['dt_fechamento_parcela']              = date('Y-m-d H:i:s');
			$addRow['cd_prof_fechamento_parcela'] = $_SESSION["oasis_logged"][0]["cd_profissional"];

			$erros = $objProcessamentoParcela->atualizaProcessamentoParcela($post['cd_projeto'], $post['cd_proposta'], $post['cd_parcela'], $addRow);
		}

		return $erros;
	}

	public function fechaSolicitacaoExecucaoParcela($post)
	{
		$erros = false;
		
		$processamentoParcela    = new ProcessamentoParcela($this->_request->getControllerName());
		$rowProcessamentoParcela = $processamentoParcela->fetchRow("cd_projeto = {$post['cd_projeto']} and cd_proposta = {$post['cd_proposta']} and cd_parcela = {$post['cd_parcela']} and st_ativo = 'S'");

		$addRow                  = array();
		$addRow['st_fechamento'] = 'S';
		$addRow['dt_fechamento'] = date('Y-m-d H:i:s');
		
		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$erros       = $solicitacao->atualizaSolicitacao($rowProcessamentoParcela->cd_objeto_execucao, $rowProcessamentoParcela->ni_solicitacao_execucao, $rowProcessamentoParcela->ni_ano_solicitacao_execucao, $addRow);

		return $erros;
	}
}