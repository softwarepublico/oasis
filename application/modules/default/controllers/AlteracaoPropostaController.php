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

class AlteracaoPropostaController extends Base_Controller_Action
{
    private $objParcela;
    private $objProcessamentoParcela;

	public function init()
	{
		parent::init();
        $this->objParcela              = new Parcela($this->_request->getControllerName());
        $this->objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getPost();

		$this->view->cd_projeto  = $params['cd_projeto'];
		$this->view->cd_proposta = $params['cd_proposta'];
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$post = $this->_request->getPost();

		unset($post['retorno_booleano']);
		
		//$retornoBooleano é um paramentro recebido no post para verificar o tipo de retorno deste metodo (salvarAction)
		//caso o parametro 'retorno_booleano' foi postado o retorno do método será diferenciado 
		$retornoBooleano = $this->_request->getParam('retorno_booleano',0);
		$retornoBooleano = ($retornoBooleano === "true") ? true : false;
		
		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		//grava na tabela s_processamento_proposta os dados de alteração de proposta
		//abandona o registro atualmente ativo e grava o motivo da alteração de proposta
		if ($erros === false){
			$erros = $this->salvaAlteracaoProposta($post);
		}

		//atualiza os dados da proposta zerando os flags e o mês e ano de execução
		//Se a proposta estava marcada para encerramento, 
		//abandona o processamento da última parcela da proposta, pois foi a homologação desta
		//que marcou a proposta para encerramento 
		if ($erros === false){
			$erros = $this->atualizaProposta($post);
		}

		//abandona o processamento atual das parcelas que foram autorizadas
		//mas ainda não foram fechadas para que seus valores de horas possam
		//ser alterados, pois quando a parcela foi autorizada, não se pode
		//alterar seus dados
		if ($erros === false){
			$erros = $this->zeraProcessamentoParcelasAutorizadasNaoFechadas($post);
		}

		if ($erros === true) {
			$db->rollback();
			if($retornoBooleano){
				echo 'false';
			} else {
				echo Base_Util::getTranslator('L_MSG_ERRO_ABRIR_PROPOSTA');
			}
		} else {
			$db->commit();

			if($retornoBooleano){
				echo 'true';
			} else {
				echo Base_Util::getTranslator('L_MSG_SUCESS_ABRIR_PROPOSTA');
			}
            
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail               = new Base_Controller_Action_Helper_Mail();
				$arrInf['cd_projeto']   = $post['cd_projeto_alteracao_proposta'];
				$arrInf['cd_proposta']  = $post['cd_proposta_alteracao_proposta'];
				$arrInf['_tx_obs']      = $_SESSION['tx_obs'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
				unset ($_SESSION['tx_obs']);
			}
		}
	}

	public function salvaAlteracaoProposta($post)
	{
		$erros = false;

		$objProcessamentoProposta = new ProcessamentoProposta($this->_request->getControllerName());

		$addRow                                 = array();
		$addRow['st_ativo']                     = null;
		$addRow['tx_motivo_alteracao_proposta'] = $post['tx_motivo_alteracao_proposta'];

		$erros = $objProcessamentoProposta->atualizaProcessamentoProposta($post['cd_projeto_alteracao_proposta'],
																		  $post['cd_proposta_alteracao_proposta'],
																		  $addRow);

		if (K_ENVIAR_EMAIL == "S") {
			$_SESSION['tx_obs'] = $post['tx_motivo_alteracao_proposta'];
		}
		return $erros;
	}

	public function atualizaProposta($post)
	{
		$erros            = false;

		$objProposta      = new Proposta($this->_request->getControllerName());
		$where            = "cd_projeto = {$post['cd_projeto_alteracao_proposta']} and cd_proposta = {$post['cd_proposta_alteracao_proposta']}";
		$rowPropostaAtual = $objProposta->fetchRow($where);

		$addRow = array();
		$addRow['st_alteracao_proposta']    = "S";
		$addRow['st_descricao']             = "0";
		$addRow['st_profissional']          = "0";
		$addRow['st_metrica']               = "0";
		$addRow['st_documentacao']          = "0";
		$addRow['st_modulo']                = "0";
		$addRow['st_parcela']               = "0";
		$addRow['st_produto']               = "0";
		$addRow['ni_mes_proposta']          = null;
		$addRow['ni_ano_proposta']          = null;

		if (!$objProposta->update($addRow, $where)){
			$erros = true;
		}

		if ($erros === false){
			if ($rowPropostaAtual->st_encerramento_proposta === "E"){
				$upRow = array();
				$upRow['st_encerramento_proposta'] = null;

				if (!$objProposta->update($upRow, $where)){
					$erros = true;
				}
				
				if ($erros === false){
					$erros = $this->zeraProcessamentoUltimaParcelaProposta($post);
				}
			}
		}
		return $erros;
	}

	public function zeraProcessamentoParcelasAutorizadasNaoFechadas($post)
	{
		$erros                   = false;
		$arrProcessamentoParcela = $this->objParcela->getParcelasAutorizadasNaoFechadas($post['cd_projeto_alteracao_proposta'], $post['cd_proposta_alteracao_proposta']);
		$addRow                  = array();
		$addRow['st_ativo']      = null;

		foreach($arrProcessamentoParcela as $processamentoParcela){
			if ($erros === false){
				$erros = $this->objProcessamentoParcela->atualizaProcessamentoParcela($post['cd_projeto_alteracao_proposta'], $post['cd_proposta_alteracao_proposta'], $processamentoParcela['cd_parcela'], $addRow);
				
				if ($erros === false){
					$erros = $this->zeraMesAnoExecucaoParcela($processamentoParcela['cd_parcela']);
				}
			}
		}
		return $erros;
	}
	
	public function zeraProcessamentoUltimaParcelaProposta($post)
	{
		$erros                   = false;
		
		$objParcela              = new Parcela($this->_request->getControllerName());
		$objProcessamentoParcela = new ProcessamentoParcela($this->_request->getControllerName());
		
		$cd_ultima_parcela       = $objParcela->getUltimaParcelaProposta($post['cd_projeto_alteracao_proposta'], $post['cd_proposta_alteracao_proposta']);
		
		$addRow                  = array();
		$addRow['st_ativo']      = null;

		$erros = $objProcessamentoParcela->atualizaProcessamentoParcela($post['cd_projeto_alteracao_proposta'], $post['cd_proposta_alteracao_proposta'], $cd_ultima_parcela, $addRow);

		if ($erros === false){
			$erros = $this->zeraMesAnoExecucaoParcela($cd_ultima_parcela);
		}
		return $erros;
	}
	
	public function zeraMesAnoExecucaoParcela($cd_parcela)
	{
		$erros      = false;
		$upRow      = array();
		$objParcela = new Parcela($this->_request->getControllerName());
        
		$upRow['ni_mes_execucao_parcela'] = null;
		$upRow['ni_ano_execucao_parcela'] = null;
		
		$where = "cd_parcela = {$cd_parcela}";

		if (!$objParcela->update($upRow, $where)){
			$erros = true;
		}
		return $erros;
	}
}