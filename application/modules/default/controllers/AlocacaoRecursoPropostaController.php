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

class AlocacaoRecursoPropostaController extends Base_Controller_Action
{
	private $controle;
	private $objContrato;
	private $objContratoDefinicaoMetrica;
	private $_parcelaOrcamento;

	public function init()
	{
		parent::init();
		$this->controle                    = new Controle($this->_request->getControllerName());
		$this->objContrato                 = new Contrato($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
		$this->_parcelaOrcamento           = new Base_Controller_Action_Helper_ParcelaOrcamento();
	}

	public function indexAction()
	{
		$this->view->headTitle(Base_Util::setTitle('L_TIT_ALOCACAO_RECURSO'));
                
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

		$proposta    = new Proposta($this->_request->getControllerName());
		$arrProposta = $proposta->fetchRow(array("cd_projeto = ?"=>$cd_projeto, 'cd_proposta = ?'=>$cd_proposta));

		$arrProposta = $arrProposta->toArray();

		$ni_horas_proposta = $arrProposta['ni_horas_proposta'];

		$this->view->ni_horas_proposta = $ni_horas_proposta;
		$this->view->arrProposta       = $arrProposta;

		$this->_parcelaOrcamento->verificaIndicadorParcelaOrcamento($cd_projeto);
		
		$st_parcela_orcamento             = $this->_parcelaOrcamento->getStParcelaOrcamento();
		$ni_porcentagem_parc_orcamento = $this->_parcelaOrcamento->getNiPorcentagemParcelaOrcamento();

		$this->view->st_parcela_orcamento             = $st_parcela_orcamento;
		$this->view->ni_porcentagem_parc_orcamento = $ni_porcentagem_parc_orcamento;

		if ((int)$cd_proposta == 1 && $st_parcela_orcamento == 'S'){
			$ni_horas_modulo_proposta = round(($ni_horas_proposta*$ni_porcentagem_parc_orcamento)/100, 1);
		}
		else{
			$ni_horas_modulo_proposta = 0;
		}
		$this->view->ni_horas_modulo_proposta = $ni_horas_modulo_proposta;

		$tipo = 'D';
		$horasAlocadasD = $this->controle->getHorasAlocadas($cd_projeto, $cd_proposta, $tipo);
		
		
		$tipo = 'C';
		$horasAlocadasC = $this->controle->getHorasAlocadas($cd_projeto, $cd_proposta, $tipo);

		$horasAlocadas = $horasAlocadasD - $horasAlocadasC;

		$this->view->horas_alocadas = round($horasAlocadas,1);

		$tipo = 'D';
		$horasAlocadasModuloPropostaD = $this->controle->getHorasAlocadas($cd_projeto, $cd_proposta, $tipo, true);

		$tipo = 'C';
		$horasAlocadasModuloPropostaC = $this->controle->getHorasAlocadas($cd_projeto, $cd_proposta, $tipo, true);

		$horasAlocadasModuloProposta = $horasAlocadasModuloPropostaD - $horasAlocadasModuloPropostaC;

		$this->view->horas_alocadas_modulo_proposta = round($horasAlocadasModuloProposta,1);

//		$ni_horas_proposta_final = $ni_horas_proposta - $horasAlocadasC + $horasAlocadasD;
		$ni_horas_proposta_final = $ni_horas_proposta - $horasAlocadas;
		
		$this->view->ni_horas_proposta_final = $ni_horas_proposta_final;

		if (is_null($arrProposta['st_contrato_anterior'])){
			//$ni_horas_modulo_proposta_final = $ni_horas_modulo_proposta - ($horasAlocadasModuloPropostaC + $horasAlocadasModuloPropostaD);
			$ni_horas_modulo_proposta_final = $ni_horas_modulo_proposta - $horasAlocadasModuloProposta;
		}
		else{
			$ni_horas_modulo_proposta_final = 0;
		}

		$this->view->ni_horas_modulo_proposta_final = round($ni_horas_modulo_proposta_final,1);

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

		$this->view->soma_total = round($soma_total,1);

		$tipo = 'D';
		$debito_contrato_atual = $this->controle->getHorasAlocadas($cd_projeto, $cd_proposta, $tipo, false, $cd_contrato);

		$tipo = 'C';
		$credito_contrato_atual = $this->controle->getHorasAlocadas($cd_projeto, $cd_proposta, $tipo, false, $cd_contrato);

		$nu_horas_alocado_contrato_atual = abs($credito_contrato_atual - $debito_contrato_atual);
		
		$this->view->nu_horas_alocado_contrato_atual = round($nu_horas_alocado_contrato_atual,1);

		if ($cd_proposta == 1 && $st_parcela_orcamento == 'S'){
			$arrDadosParcelaProposta = $parcela->getDadosParcelaUm($cd_projeto, $cd_proposta);
			$mes_parcela             = $arrDadosParcelaProposta['ni_mes_previsao_parcela'];
			$ano_parcela             = $arrDadosParcelaProposta['ni_ano_previsao_parcela'];

			$flagModuloProposta      = $this->verificaParcelaProposta($mes_parcela, $ano_parcela, $mes_inicial, $ano_inicial, $mes_final, $ano_final);
		}
		else{
			$flagModuloProposta = false;
		}

		$this->view->flagModuloProposta = $flagModuloProposta;

		$projetoPrevisto        = new ProjetoPrevisto($this->_request->getControllerName());
		$arrProjeto             = $projetoPrevisto->getProjetoPrevisto($cd_contrato);
		$this->view->arrProjeto = $arrProjeto;

		$arrHorasProjetoPrevisto = array();
		foreach ($arrProjeto->toArray() as $projetoPrevisto){

			$ni_horas_projeto_previsto = $projetoPrevisto['ni_horas_projeto_previsto'];

			$credito = round((float)$this->controle->getHorasProjetoPrevisto($projetoPrevisto['cd_projeto_previsto'], 'C'),1);
			$debito  = round($this->controle->getHorasProjetoPrevisto($projetoPrevisto['cd_projeto_previsto'], 'D'),1);

			$total   = round($ni_horas_projeto_previsto + ($credito - $debito),1);

			$arrHorasProjetoPrevisto[$projetoPrevisto['cd_projeto_previsto']] = array('credito' => $credito, 'debito' => $debito, 'total' => $total);
		}

		$this->view->arrHorasProjetoPrevisto = $arrHorasProjetoPrevisto;

		$this->arrDadosMetricaPadrao	  = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);
		$this->view->unidadePadraoMetrica = ( count($this->arrDadosMetricaPadrao) > 0 ) ? $this->arrDadosMetricaPadrao[0]['tx_sigla_metrica'] : Base_Util::getTranslator('L_VIEW_UNID_METRICA');
	}

	public function verificaParcelaProposta($mes_parcela, $ano_parcela, $mes_inicial_contrato, $ano_inicial_contrato, $mes_final_contrato, $ano_final_contrato)
	{
		$resultado = false;
		if ((int)$ano_inicial_contrato == (int)$ano_final_contrato) {
			if ((int)$ano_parcela == (int)$ano_inicial_contrato) {
				if ((int)$mes_parcela >= (int)$mes_inicial_contrato && (int)$mes_parcela <= (int)$mes_final_contrato) {
					$resultado = true;
				}
			}
		} else {
			if ((int)$ano_parcela == (int)$ano_inicial_contrato) {
				if ((int)$mes_parcela >= (int)$mes_inicial_contrato) {
					$resultado = true;
				}
			}
			if ((int)$ano_parcela == (int)$ano_final_contrato) {
				if ((int)$mes_parcela <= (int)$mes_final_contrato) {
					$resultado = true;
				}
			}
		}
		return $resultado;
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

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_GRAVAR_ALOCACAO_RECURSO_PROPOSTA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_GRAVAR_ALOCACAO_RECURSO_PROPOSTA');
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail               = new Base_Controller_Action_Helper_Mail();
				$arrInf['cd_projeto']   = $post['cd_projeto_alocacao_recurso_proposta'];
				$arrInf['cd_proposta']  = $post['cd_proposta_alocacao_recurso_proposta'];
				$arrDadosEmail          = $_objMail->setDadosMsgEmail($arrInf, $this->_request->getControllerName());
			}
		}
	}

	public function gravaAlocacaoRecursoProposta($post)
	{
		$erros = false;

		$cd_projeto           = $post['cd_projeto_alocacao_recurso_proposta'];
		$cd_proposta          = $post['cd_proposta_alocacao_recurso_proposta'];
		$st_parcela_orcamento = $post['st_parcela_orcamento'];

		if ($cd_proposta == 1 && $st_parcela_orcamento == 'S'){
			foreach($post['modulo_proposta'] as $cd_projeto_previsto => $horasMP){
				if (!empty($horasMP)){
					if ($horasMP > 0){
						$erros = $this->controle->registraControle("S", "D", $post['cd_contrato'], $cd_projeto_previsto, $cd_projeto, $horasMP, $cd_proposta);
					}
					if ($horasMP < 0){
						$erros = $this->controle->registraControle("S", "C", $post['cd_contrato'], $cd_projeto_previsto, $cd_projeto, abs($horasMP), $cd_proposta);
					}
				}
			}
		}

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

    public function verificaObjetoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');

		$arrContratoObjeto = $this->objContrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);
		if($arrContratoObjeto){
			die("1");
		} else {
			die("2");
		}
	}

    public function atualizaProcessamentoPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		//atualiza os campos de homologação da proposta
		//na tabela s_processamento_proposta
        $erros = $this->atualizaProcessamentoProposta2($post);

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		}
	}

    public function atualizaProcessamentoProposta2($post)
	{
		$erros = false;

		$cd_projeto  = $post['cd_projeto_alocacao_recurso_proposta'];
		$cd_proposta = $post['cd_proposta_alocacao_recurso_proposta'];

		$objProcessamentoProposta = new ProcessamentoProposta($this->_request->getControllerName());

		$addRow = array();
		$addRow['st_alocacao_proposta']      = "N";
		$addRow['dt_alocacao_proposta']      = date('Y-m-d H:i:s');
		$addRow['cd_prof_alocacao_proposta'] = $_SESSION['oasis_logged'][0]['cd_profissional'];

		$erros = $objProcessamentoProposta->atualizaProcessamentoProposta($cd_projeto, $cd_proposta, $addRow);
		return $erros;
	}

}