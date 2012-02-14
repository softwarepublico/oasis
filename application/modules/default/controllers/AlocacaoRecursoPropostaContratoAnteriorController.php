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

class AlocacaoRecursoPropostaContratoAnteriorController extends Base_Controller_Action
{
	private $controle;
	private $contrato;
	private $objContratoDefinicaoMetrica;
	
	public function init()
	{
		parent::init();
		$this->controle                    = new Controle($this->_request->getControllerName());
		$this->contrato                    = new Contrato($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
	}

	public function indexAction()
	{}
	
	public function gridAlocacaoRecursoPropostaContratoAnteriorAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato_atual = $this->_request->getParam('cd_contrato');

		$this->view->res   = $this->controle->getPropostasAlocarRecursoContratoAnterior($cd_contrato_atual);

		//Busca a métrica indicada como padrão para o contrato
		//e obtém a sigla da métrica
		$this->arrDadosMetricaPadrao	  = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato_atual);
		$this->view->unidadePadraoMetrica = ( count($this->arrDadosMetricaPadrao) > 0 ) ? $this->arrDadosMetricaPadrao[0]['tx_sigla_metrica'] : Base_Util::getTranslator('L_VIEW_UNIDADES_METRICA');

		echo $this->view->render('alocacao-recurso-proposta-contrato-anterior/grid-alocacao-recurso-proposta-contrato-anterior.phtml');
	}
	
	public function tabAlocacaoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto       = $this->_request->getParam('cd_projeto');
		$cd_proposta      = $this->_request->getParam('cd_proposta');
		$tx_sigla_projeto = $this->_request->getParam('tx_sigla_projeto');
		
		$this->view->tx_sigla_projeto = $tx_sigla_projeto;
		$this->view->cd_proposta      = $cd_proposta;
		$this->view->cd_projeto       = $cd_projeto;
		
		$contrato    = new Contrato($this->_request->getControllerName());
		$arrContrato = $contrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);

		$cd_contrato = $arrContrato['cd_contrato'];
        $dt_inicio   = date('d/m/Y', strtotime($arrContrato['dt_inicio_contrato']));
		$dt_final    = date('d/m/Y', strtotime($arrContrato['dt_fim_contrato']));
		$mes_inicial = $arrContrato['ni_mes_inicial_contrato'];
		$ano_inicial = $arrContrato['ni_ano_inicial_contrato'];
		$mes_final   = $arrContrato['ni_mes_final_contrato'];
		$ano_final   = $arrContrato['ni_ano_final_contrato'];

		$proposta = new Proposta($this->_request->getControllerName());
		$arrProposta = $proposta->fetchRow("cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta}");

		$arrProposta = $arrProposta->toArray();

		$ni_horas_proposta = $arrProposta['ni_horas_proposta'];
		
		$this->view->ni_horas_proposta = $ni_horas_proposta;
		$this->view->arrProposta       = $arrProposta;
		
		$tipo = 'D';
		$horasAlocadasD = $this->controle->getHorasAlocadas($cd_projeto, $cd_proposta, $tipo);
		
		$tipo = 'C';
		$horasAlocadasC = $this->controle->getHorasAlocadas($cd_projeto, $cd_proposta, $tipo);

		$horasAlocadas  = $horasAlocadasD - $horasAlocadasC;

		$this->view->horas_alocadas = $horasAlocadas;
		
		$ni_horas_proposta_final = $ni_horas_proposta - $horasAlocadas;
		
		$this->view->ni_horas_proposta_final          = $ni_horas_proposta_final;
		$this->view->st_parcela_orcamento             = '';
		$this->view->ni_porcentagem_parc_orcamento = 0;
		
		$this->view->cd_contrato = $cd_contrato;

		if ((int)$mes_inicial == 12){
			$mes_inicial_2 = 1;
			$ano_inicial_2 = (int)$ano_inicial + 1;
		} else {
			$mes_inicial_2 = (int)($mes_inicial) + 1;
			$ano_inicial_2 = (int)($ano_inicial);
		}
		if ((int)$mes_final == 1){
			$mes_final_2 = 12;
			$ano_final_2 = (int)$ano_final - 1;
		} else {
			$mes_final_2 = (int)$mes_final - 1;
			$ano_final_2 = (int)$ano_final;
		}
		
		$parcela = new Parcela($this->_request->getControllerName());
		
		$arrSomaParcela = $parcela->getSomaHorasParcelaPeriodoContrato($cd_contrato, $cd_projeto, $cd_proposta, $mes_inicial_2, $ano_inicial_2, $mes_final_2, $ano_final_2);

		$soma_periodo = (!is_null($arrSomaParcela['ni_horas_parcela'])? $arrSomaParcela['ni_horas_parcela'] : 0);

		$ni_horas_parcela_inicial = $parcela->getSomaHorasParcelaMes($cd_contrato, $cd_projeto, $cd_proposta, $mes_inicial, $ano_inicial);

		$soma_mes_inicial = $this->controle->calculaSomaHorasParcelaMes($ni_horas_parcela_inicial, $dt_inicio, 'I');

		$ni_horas_parcela_final = $parcela->getSomaHorasParcelaMes($cd_contrato, $cd_projeto, $cd_proposta, $mes_final, $ano_final);

		$soma_mes_final = $this->controle->calculaSomaHorasParcelaMes($ni_horas_parcela_final, $dt_final, 'F');

		if (($mes_inicial == $mes_final) && ($ano_inicial == $ano_final)) {
			$soma_total = $soma_mes_inicial;
		} else {
			$soma_total = $soma_mes_inicial + $soma_periodo + $soma_mes_final;
		}

		$this->view->soma_total = $soma_total;
		
		$projetoPrevisto        = new ProjetoPrevisto($this->_request->getControllerName());
		$arrProjeto             = $projetoPrevisto->getProjetoPrevisto($cd_contrato);
		$this->view->arrProjeto = $arrProjeto;
		
		$arrHorasProjetoPrevisto = array();
		foreach ($arrProjeto->toArray() as $projetoPrevisto){

			$ni_horas_projeto_previsto = $projetoPrevisto['ni_horas_projeto_previsto'];

			$credito = $this->controle->getHorasProjetoPrevisto($projetoPrevisto['cd_projeto_previsto'], 'C');
			$debito  = $this->controle->getHorasProjetoPrevisto($projetoPrevisto['cd_projeto_previsto'], 'D');

			$total   = $ni_horas_projeto_previsto + ($credito - $debito);

			$arrHorasProjetoPrevisto[$projetoPrevisto['cd_projeto_previsto']] = array('credito' => $credito, 'debito' => $debito, 'total' => $total);
		}
		
		$this->view->arrHorasProjetoPrevisto = $arrHorasProjetoPrevisto;

		$this->arrDadosMetricaPadrao	  = $this->objContratoDefinicaoMetrica->getSiglaUnidadeMetricaPadraoContratoAtivoProjeto($cd_projeto);
		$this->view->unidadePadraoMetrica = ( count($this->arrDadosMetricaPadrao) > 0 ) ? $this->arrDadosMetricaPadrao['tx_sigla_unidade_metrica'] : Base_Util::getTranslator('L_VIEW_UNID_METRICA');
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();
		
		$erros = $this->gravaAlocacaoRecursoProposta($post);
		
		if ($erros === false){
			$erros = $this->salvaAlocacaoRecursoProposta($post);
		}
		
		if ($erros === false){
			$erros = $this->salvaAtualizacaoPropostaContratoAnterior($post);
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_GRAVAR_ALOCACAO_RECURSO_PROPOSTA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_GRAVAR_ALOCACAO_RECURSO_PROPOSTA');
		}
	}
	
	public function gravaAlocacaoRecursoProposta($post)
	{
		$erros = false;

		$cd_projeto  = $post['cd_projeto_alocacao_recurso_proposta'];
		$cd_proposta = $post['cd_proposta_alocacao_recurso_proposta'];
		
		foreach($post['cd_projeto_previsto'] as $cd_projeto_previsto => $horas){
			if (!empty($horas)){
				if ($horas > 0){
					$erros = $this->controle->registraControle(null, "D", $post['cd_contrato'], $cd_projeto_previsto, $cd_projeto, $horas, $cd_proposta);
				}
				if ($horas < 0){
					$erros = $this->controle->registraControle(null, "C", $post['cd_contrato'], $cd_projeto_previsto, $cd_projeto, abs($horas), $cd_proposta);
				}
			}
		}
		return $erros;
	}
	
	public function salvaAlocacaoRecursoProposta($post)
	{
		$erros = false;

		$cd_projeto  = $post['cd_projeto_alocacao_recurso_proposta'];
		$cd_proposta = $post['cd_proposta_alocacao_recurso_proposta'];

		$objProcessamentoProposta = new ProcessamentoProposta($this->_request->getControllerName());

		$addRow = array();
		$addRow['st_alocacao_proposta']      = "S";
		$addRow['dt_alocacao_proposta']      = date('Y-m-d H:i:s');
		$addRow['cd_prof_alocacao_proposta'] = $_SESSION['oasis_logged'][0]['cd_profissional'];
		
		$erros = $objProcessamentoProposta->atualizaProcessamentoProposta($cd_projeto, $cd_proposta, $addRow);

		return $erros;
	}
	
	public function salvaAtualizacaoPropostaContratoAnterior($post)
	{
		$erros       = false;
		$objProposta = new Proposta($this->_request->getControllerName());

		$addRow = array();
		$addRow['st_contrato_anterior'] = "S";
		
		$erros = $objProposta->atualizaProposta($post['cd_projeto_alocacao_recurso_proposta'], $post['cd_proposta_alocacao_recurso_proposta'], $addRow);

		return $erros;
	}
}