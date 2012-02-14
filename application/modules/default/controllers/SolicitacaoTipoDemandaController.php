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

class SolicitacaoTipoDemandaController extends Base_Controller_Action
{
	private $objSolicitacao;
	private $objUtil;
	private $objDemandaProfissional;
	private $objDemandaProfissionalNivelServico;
	private $objDemanda;
    private $objStatusAtendimento;
	
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_SOLICITACAO_SERVICO_TIPO_DEMANDA'));
		$this->objUtil                            = new Base_Controller_Action_Helper_Util();
		$this->objSolicitacao                     = new Solicitacao($this->_request->getControllerName());
		$this->objDemandaProfissional             = new DemandaProfissional($this->_request->getControllerName());
		$this->objDemandaProfissionalNivelServico = new DemandaProfissionalNivelServico($this->_request->getControllerName());
		$this->objDemanda                         = new Demanda($this->_request->getControllerName());
        $this->objStatusAtendimento               = new StatusAtendimento($this->_request->getControllerName());
	}

	public  function indexAction()
	{
		$this->view->mesAno = "{$this->objUtil->getMes(date('n'))}/".date('Y');
		
		//Tab CONSULTA
		//Combo Unidade
		$unidade                      = new Unidade($this->_request->getControllerName());
		$arrUnidade                   = $unidade->getUnidade(true);
		$this->view->cd_unidade_combo = $arrUnidade;

		//Combo Profissional
		$cd_objeto       = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$profissional    = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$arrProfissional = $profissional->getProfissionalGerenteTecnicoObjetoContrato($cd_objeto, true);
		$this->view->cd_profissional_combo = $arrProfissional;
	}

	public function gridSolicitacaoTipoDemandaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes                     = $this->_request->getParam('mes');
		$ano                     = $this->_request->getParam('ano');
		$cd_profissional         = $this->_request->getParam('cd_profissional');
		$cd_profissional         = ($cd_profissional > -1)?$cd_profissional:null;
		$arrProfissionalDemanda  = array();
		$arrSituacaoProfissional = array();

		$mes       = ($mes == 0)?date('n'):$mes;
		$ano       = ($ano == 0)?date('Y'):$ano;

		$mesAno    = $this->objUtil->getMes($mes);
		$mesAno    = $mesAno."/".$ano;
		$mes       = substr("00".$mes,-2);
		$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];
		
		$arrSolicitacaoTipoDemanda = $this->objSolicitacao->getSolicitacaoTipoDemanda($mes, $ano, $cd_objeto, $cd_profissional);
		
		$arrProfissionalDemanda  = array();
        $arrTempoRespostaDemanda = array();
		if (count($arrSolicitacaoTipoDemanda > 0)){

            $arrTempoResposta = $this->objStatusAtendimento->getTempoResposta();
            
			foreach($arrSolicitacaoTipoDemanda as $key=>$value){
				if($value['cd_demanda'] != ""){
					$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($value['cd_demanda'], $value['cd_objeto'], "S",3);
					$arrProfissionalDemanda[$value['cd_demanda']] = $arrDemandaProfissional;
					if(!array_key_exists($value['cd_demanda'],$arrProfissionalDemanda)){
						$arrProfissionalDemanda[$value['cd_demanda']] = array();
					}

                    $maxPrazoExecucaoNivelServico = $this->objDemandaProfissionalNivelServico->getMaxPrazoExecucaoNivelServico($value['cd_demanda']);
                    $hi  = $value['dt_leitura_solicitacao'];
                    $arr = Base_Controller_Action_Helper_Util::getTempoResposta($arrTempoResposta,$maxPrazoExecucaoNivelServico,$hi);
					$arrTempoRespostaDemanda[$value['cd_demanda']] = $arr;
				}
			}
            

		}


		$arrSolicitacoes = $this->objSolicitacao->verificaJustificativa($cd_objeto, $arrSolicitacaoTipoDemanda);

		$this->view->st_necessita_justificativa = $arrSolicitacoes[0];
		$this->view->ni_minutos_justificativa   = $arrSolicitacoes[1];
		$this->view->res                        = $arrSolicitacoes[2];
		
		$this->view->arrProfissionalDemanda     = $arrProfissionalDemanda;
		$this->view->arrTempoRespostaDemanda    = $arrTempoRespostaDemanda;
		$this->view->mesAno                     = $mesAno;
	}

	public function gridSolicitacaoTipoDemandaExecutadaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes             = $this->_request->getParam('mes');
		$ano             = $this->_request->getParam('ano');
		$cd_profissional = $this->_request->getParam('cd_profissional');
		$cd_profissional = ($cd_profissional > -1)?$cd_profissional:null;
		
		$mes    = ($mes == 0)?date('n'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;

		$mesAno    = $this->objUtil->getMes($mes);
		$mesAno    = $mesAno."/".$ano;
		$mes       = substr("00".$mes,-2);
		$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];		
		
		$arrSolicitacaoTipoDemandaExecutada = $this->objSolicitacao->getSolicitacaoTipoDemandaExecutada($mes, $ano, $cd_objeto, $cd_profissional);
		
		$arrProfissionalDemanda = array();
		if (count($arrSolicitacaoTipoDemandaExecutada > 0)){		
			foreach($arrSolicitacaoTipoDemandaExecutada as $key=>$value){
				if($value['cd_demanda'] != ""){
					$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($value['cd_demanda'], $value['cd_objeto'], "S",3);
					$arrProfissionalDemanda[$value['cd_demanda']] = $arrDemandaProfissional;
					if(!array_key_exists($value['cd_demanda'],$arrProfissionalDemanda)){
						$arrProfissionalDemanda[$value['cd_demanda']] = array();
					}
				}
			}	
		}	
		
		$this->view->arrProfissionalDemanda = $arrProfissionalDemanda;
		$this->view->res                    = $arrSolicitacaoTipoDemandaExecutada;
		$this->view->mesAno                 = $mesAno;
	}
	
	public function gridSolicitacaoTipoDemandaConcluidaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes             = $this->_request->getParam('mes');
		$ano             = $this->_request->getParam('ano');
		$cd_profissional = $this->_request->getParam('cd_profissional');
		$cd_profissional = ($cd_profissional > -1)?$cd_profissional:null;		

		$mes    = ($mes == 0)?date('n'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;

		$mesAno = $this->objUtil->getMes($mes);
		$mesAno = $mesAno."/".$ano;
		
		$mes       = substr("00".$mes,-2);
		$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];	
			
		$arrSolicitacaoTipoDemandaConcluida = $this->objSolicitacao->getSolicitacaoTipoDemandaConcluida($mes, $ano, $cd_objeto, $cd_profissional);
		
		$arrProfissionalDemanda = array();
		if (count($arrSolicitacaoTipoDemandaConcluida > 0)){				
			foreach($arrSolicitacaoTipoDemandaConcluida as $key=>$value){
				if($value['cd_demanda'] != ""){
					$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($value['cd_demanda'], $value['cd_objeto'], "S",3);
					$arrProfissionalDemanda[$value['cd_demanda']] = $arrDemandaProfissional;
					if(!array_key_exists($value['cd_demanda'],$arrProfissionalDemanda)){
						$arrProfissionalDemanda[$value['cd_demanda']] = array();
					}
				}
			}
		}		
		
		$this->view->arrProfissionalDemanda = $arrProfissionalDemanda;
		$this->view->res                    = $arrSolicitacaoTipoDemandaConcluida;
		$this->view->mesAno                 = $mesAno;
	}
	
	public function conclusaoSolicitacaoAction()
	{
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getPost();

		$this->view->cd_objeto			= $params['cd_objeto'];
		$this->view->ni_solicitacao		= $params['ni_solicitacao'];
		$this->view->ni_ano_solicitacao = $params['ni_ano_solicitacao'];
	}

	public function concluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$erros = false;
		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		$post = $this->_request->getPost();

		if ($erros === false){
			$erros = $this->concluirDemanda($post);
		}
		
		if ($erros === false){
			$erros = $this->concluirSolicitacaoTipoDemanda($post);
		}

		//se a demanda foi originada por uma pre-demanda, busca o número da solicitação de serviço
		//que originou a demanda. Com o código da solicitação de serviço, busca a pre-demanda que originou
		//a demanda. Se houver pre-demanda, atualiza a tabela s_pre_demanda com os dados de fechamento
		//para que a execução possa ser avaliada pelo solicitante da pre-demanda
		if ($erros === false){
			$erros = $this->fechaPreDemanda($post);
		}
		
		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_CONCLUIR_SOLICITACAO_SERVICO');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_CONCLUIR_SOLICITACAO_SERVICO');
			if (K_ENVIAR_EMAIL == "S") {
				$_objMail                 = new Base_Controller_Action_Helper_Mail();
				$arrDados["st_msg_email"] = $_SESSION["st_msg_email"];

				if ($_SESSION["st_msg_email"] == "C") {
					$arrDados["cd_profissional"] = $_SESSION["cd_profissional_emissor"];
					$_objMail->enviaEmail($_SESSION["cd_objeto_emissor"], $this->_request->getControllerName(), $arrDados);
				}else{
					$_objMail->enviaEmail($_SESSION["oasis_logged"][0]["cd_objeto"], $this->_request->getControllerName(), $arrDados);
				}
				unset ($_SESSION["st_msg_email"]);
				unset ($_SESSION["cd_objeto_emissor"]);
				unset ($_SESSION["cd_profissional_emissor"]);
			}
		}
	}
	
	private function concluirDemanda($post)
	{
		$erros = false;
		
		$rowDemanda = $this->objDemanda->fetchRow("cd_objeto = {$post['cd_objeto_conclusao_solicitacao']} and ni_solicitacao = {$post['ni_solicitacao_conclusao_solicitacao']} and ni_ano_solicitacao = {$post['ni_ano_solicitacao_conclusao_solicitacao']}")->toArray();
		
		$upRow                         = array();
		$upRow['st_conclusao_demanda'] = "S";
		$upRow['dt_conclusao_demanda'] = date('Y-m-d H:i:s');

		$erros = $this->objDemanda->atualizaDemanda($rowDemanda["cd_demanda"], $upRow);
		
		return $erros;
	}
	
	private function concluirSolicitacaoTipoDemanda($post)
	{
		$erros = false;
		
		$upRow                            = array();
		$upRow['tx_execucao_solicitacao'] = $post['tx_execucao_solicitacao'];
		$upRow['st_fechamento']           = "S";
		$upRow['dt_fechamento']           = date('Y-m-d H:i:s');

		$erros = $this->objSolicitacao->atualizaSolicitacao($post['cd_objeto_conclusao_solicitacao'], $post['ni_solicitacao_conclusao_solicitacao'], $post['ni_ano_solicitacao_conclusao_solicitacao'], $upRow);
		
		return $erros;
	}
	
	public function registraLeituraSolicitacaoTipoDemandaAction()
	{
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		$ni_solicitacao     = (int)$this->_request->getParam('ni_solicitacao', 0);
		$ni_ano_solicitacao = (int)$this->_request->getParam('ni_ano_solicitacao', 0);

		// Consulta Solicitacao
		$solicitacao 	= new Solicitacao($this->_request->getControllerName());
		$resSolicitacao = $solicitacao->getSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		$this->gravaDataHoraLeituraSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao, $resSolicitacao);
		
		$this->view->solicitacao = $resSolicitacao;
	}	
	
	public function gravaDataHoraLeituraSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao, $resSolicitacao)
	{
		if (is_null($resSolicitacao['dt_leitura_solicitacao']))
		{
			$addRow = array();
			$addRow['dt_leitura_solicitacao'] = date('Y-m-d H:i:s');
			
			$erros = $this->objSolicitacao->atualizaSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao, $addRow);
		}
    }
	
/*	public function gravaDataHoraLeituraSolicitacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$ni_solicitacao     = (int)$this->_request->getParam('ni_solicitacao', 0);
		$ni_ano_solicitacao = (int)$this->_request->getParam('ni_ano_solicitacao', 0);
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		
		$solicitacao    = new Solicitacao($this->_request->getControllerName());
		$rowSolicitacao = $solicitacao->getSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		
		if (is_null($rowSolicitacao['dt_leitura_solicitacao']))
		{
			$addRow = array();
			$addRow['dt_leitura_solicitacao'] = date('Y-m-d H:i:s');
			
			$erros = $solicitacao->atualizaSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao, $addRow);
			
			if ($erros === false)
			{
				echo "Solicitação Lida com Sucesso"; 
			}
			else
			{
				echo "Erro ao Gravar Data e Hora de Leitura da Solicitação de Serviço"; 
			}
		}
		else
		{
			echo "Solicitação Lida Anteriormente"; 
		}
	}	*/

	public function tabProfissionalDesignadoAction()
	{
		$objHistoricoDemanda = new HistoricoExecucaoDemanda($this->_request->getControllerName());
		$objDemanda          = new Demanda($this->_request->getControllerName());
		$objProfissional     = new Profissional($this->_request->getControllerName());
		
		$this->_helper->layout->disableLayout();
		$cd_demanda      = $this->_request->getParam('cd_demanda');
		$cd_profissional = $this->_request->getParam('cd_profissional');
		$tab_origem      = $this->_request->getParam('tab_origem');

		$arrDados        = $objHistoricoDemanda->getDadosHistoricoExecucaoDemanda($cd_demanda,null,$cd_profissional);
		$arrProfissional = $objProfissional->getDadosProfissional($cd_profissional);

		$arrDemanda = $objDemanda->getDemanda($cd_demanda);

		$arrDemandaNivelServico   = $this->objDemandaProfissionalNivelServico->getDadosDemandaprofissionalNivelServico($cd_demanda,$cd_profissional);
		
		$this->view->arrDados               = $arrDados;
		$this->view->arrDemanda             = $arrDemanda;
		$this->view->arrDemandaNivelServico = $arrDemandaNivelServico;
		$this->view->arrProfissional        = $arrProfissional;
		$this->view->tab_origem             = $tab_origem;
	}
	
	public function fechaDemandaGerenteAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$arrPost = $this->_request->getPost();
		
		$cd_demanda      = $arrPost['cd_demanda'];
		unset($arrPost['cd_demanda']);
		$cd_profissional = $arrPost['cd_profissional']; 
		unset($arrPost['cd_profissional']);
		
		
		foreach($arrPost as $chave=>$valor){
			if($valor == ""){
				unset($arrPost[$chave]);
				continue;
			}
			if(substr($chave,0,21) == "tx_motivo_fechamento_"){
				
				$cd_nivel_servico     = substr($chave,21);
				$tx_motivo_fechamento = $valor; 
				 
				$arrUpdate['tx_motivo_fechamento']        = $tx_motivo_fechamento;
				$arrUpdate['dt_fechamento_nivel_servico'] = date("Y-m-d H:i:s");
				$arrUpdate['dt_fechamento_gerente']       = date("Y-m-d H:i:s");
				$arrUpdate['st_fechamento_nivel_servico'] = "S"; 
				$arrUpdate['st_fechamento_gerente']       = "S"; 
				
				$erros = $this->objDemandaProfissionalNivelServico->atualizaDemandaNivelServico($cd_demanda,$cd_profissional, $cd_nivel_servico,$arrUpdate);
				
				$situacaoDemandaNivelServico = $this->objDemandaProfissionalNivelServico->getSituacaoDemandaNivelServico($cd_demanda, $cd_profissional);
				
				if ($erros === false && $situacaoDemandaNivelServico == "F")
				{
					$erros = $this->fechaDemandaProfissional($cd_demanda, $cd_profissional);
				}
				
				$situacaoDemandaProfissional = $this->objDemandaProfissional->getSituacaoDemandaProfissional($cd_demanda);
				
				if ($erros === false && $situacaoDemandaProfissional == "F")
				{
					$erros = $this->fechaDemanda($cd_demanda);
				}
			}
		}		
		
		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_FINALIZAR_NIVEL_SERVICO');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_NIVEL_SERVICO_FINALIZADO');
		}
	}	
	
	private function fechaDemandaProfissional($cd_demanda, $cd_profissional)
	{
		$erros = false;
		
		$upRow                          = array();
		$upRow["cd_profissional"]       = $cd_profissional;
		$upRow["st_fechamento_demanda"] = "S";
		$upRow["dt_fechamento_demanda"] = date('Y-m-d H:i:s');
		
		$erros = $this->objDemandaProfissional->atualizaDemanda($cd_demanda, $upRow);
		
		return $erros;
	}

	private function fechaDemanda($cd_demanda)
	{
		$erros = false;
		
		$upRow                          = array();
		$upRow["st_fechamento_demanda"] = "S";
		$upRow["dt_fechamento_demanda"] = date('Y-m-d H:i:s');
		
		$erros = $this->objDemanda->atualizaDemanda($cd_demanda, $upRow);
		
		return $erros;
	}	
	
	public function solicitacaoExecutadaDetalhesAction()
	{
		$objDemanda   = new Demanda($this->_request->getControllerName());
		$objHistorico = new HistoricoExecucaoDemanda($this->_request->getControllerName());
		
		$cd_demanda = $this->_request->getParam("cd_demanda");
		$this->view->abaorigem  = $this->_request->getParam("abaOrigem");
		
		//Recupero os dados da demanda
		if (!empty($cd_demanda)){
			$arrDemanda               = $objDemanda->getDemanda($cd_demanda);
			$arrDemanda['tx_demanda'] = str_ireplace('\"','"',$arrDemanda['tx_demanda']);		
			//Recupero os dados do historico do profissional
			$arrHistorico             = $objHistorico->historicoDemandaNivelServico($cd_demanda);
		}else{
			$arrDemanda   = array();
			$arrHistorico = array();
		}
		
		$this->view->arrHistorico = $arrHistorico;
		$this->view->arrDemanda   = $arrDemanda;
	}
	
	public function fechaPreDemanda($post)
	{
		$erros = false;
		
		$preDemanda    = new PreDemanda($this->_request->getControllerName());
		$rowPreDemanda = $preDemanda->fetchRow("cd_objeto_receptor = {$post['cd_objeto_conclusao_solicitacao']} and ni_ano_solicitacao = {$post['ni_ano_solicitacao_conclusao_solicitacao']} and ni_solicitacao = {$post['ni_solicitacao_conclusao_solicitacao']}");
		if (!is_null($rowPreDemanda))
		{
			$upRow = array();
			$upRow['st_fim_pre_demanda']            = "S";
			$upRow['dt_fim_pre_demanda']            = date('Y-m-d H:i:s');
			$upRow['st_aceite_pre_demanda']         = null;
			$upRow['tx_obs_reabertura_pre_demanda'] = null;
			$upRow['st_reabertura_pre_demanda']     = null;
			
			if (!$preDemanda->update($upRow, "cd_pre_demanda = {$rowPreDemanda->cd_pre_demanda}"))
			{
				$erros = true;
			}else{
				if (K_ENVIAR_EMAIL == "S") {
					$_SESSION["st_msg_email"]            = "C";
					$_SESSION["cd_objeto_emissor"]       = $rowPreDemanda->cd_objeto_emissor;
					$_SESSION["cd_profissional_emissor"] = $rowPreDemanda->cd_profissional_solicitante;
				}
			}
		}else{
			if (K_ENVIAR_EMAIL == "S") {
				$_SESSION["st_msg_email"] = "S";
			}
		}

		return $erros;
	}

	public function gridSolicitacaoTipoDemandaConsultaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_unidade      = ($this->_request->getParam('cd_unidade')          ) ? $this->_request->getParam('cd_unidade')                    : null;
		$tx_solicitante  = ($this->_request->getParam('tx_solicitante')      ) ? $this->toUpper($this->_request->getParam('tx_solicitante')): null;
		$dt_inicio       = ($this->_request->getParam('dt_inicio')           ) ? Base_Util::converterDate($this->_request->getParam('dt_inicio'), 'DD/MM/YYYY', 'YYYY-MM-DD'):null;
		$dt_fim          = ($this->_request->getParam('dt_fim')              ) ? Base_Util::converterDate($this->_request->getParam('dt_fim'), 'DD/MM/YYYY', 'YYYY-MM-DD'):null;
		$cd_profissional = ($this->_request->getParam('cd_profissional')!= -1) ? $this->_request->getParam('cd_profissional')               : null;
		$tx_demanda      = ($this->_request->getParam('tx_demanda')          ) ? $this->toUpper($this->_request->getParam('tx_demanda'))    : null;
		$tipo_consulta   = ($this->_request->getParam('tipo_consulta')       ) ? $this->toUpper($this->_request->getParam('tipo_consulta')) : "AND";
		$solicitacao     = ($this->_request->getParam('solicitacao')         ) ? $this->_request->getParam('solicitacao')                    : null;

		$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];

		if (!is_null($solicitacao) && strpos($solicitacao, "/") != 0){
			$arrSolicitacao     = explode("/", $solicitacao);
			$ni_solicitacao     = $arrSolicitacao[0];
			$ni_ano_solicitacao = $arrSolicitacao[1];
		}
		else{
			$ni_solicitacao     = null;
			$ni_ano_solicitacao = null;
		}
			
		$arrSolicitacaoTipoDemandaConsulta = $this->objSolicitacao->getSolicitacaoTipoDemandaConsulta($cd_objeto, $cd_unidade, $tx_solicitante, $dt_inicio, $dt_fim, $cd_profissional, $tx_demanda, $tipo_consulta, $ni_solicitacao, $ni_ano_solicitacao);

		if ($arrSolicitacaoTipoDemandaConsulta)
		{
			foreach($arrSolicitacaoTipoDemandaConsulta as $key=>$value){
				if($value['cd_demanda'] != ""){
					$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($value['cd_demanda'], $value['cd_objeto'], "S",3);
					$arrProfissionalDemanda[$value['cd_demanda']] = $arrDemandaProfissional;
					if(!array_key_exists($value['cd_demanda'],$arrProfissionalDemanda)){
						$arrProfissionalDemanda[$value['cd_demanda']] = array();
					}
				}
			}
		}
		else
		{
			$arrProfissionalDemanda[] = array();
		}		
		
		$this->view->res                    = $arrSolicitacaoTipoDemandaConsulta;
		$this->view->arrProfissionalDemanda = $arrProfissionalDemanda;
	}	
}