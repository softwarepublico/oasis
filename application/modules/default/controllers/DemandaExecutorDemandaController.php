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

class DemandaExecutorDemandaController extends Base_Controller_Action
{
	private $objDemanda;
	private $objHistoricoDemanda;
	private $objUtil;
	private $objDemandaProfissional;
	private $objDemandaProfissionalNivelServico;
    private $objStatusAtendimento;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_DEMANDAS'));
		$this->objDemanda                         = new Demanda($this->_request->getControllerName());
		$this->objHistoricoDemanda                = new HistoricoExecucaoDemanda($this->_request->getControllerName());
		$this->objUtil                            = new Base_Controller_Action_Helper_Util();
		$this->objDemandaProfissional             = new DemandaProfissional($this->_request->getControllerName());
		$this->objDemandaProfissionalNivelServico = new DemandaProfissionalNivelServico($this->_request->getControllerName());
        $this->objStatusAtendimento               = new StatusAtendimento($this->_request->getControllerName());
	}

	public  function indexAction()
	{
		$this->view->mesAno = "{$this->objUtil->getMes(date('n'))}/".date('Y');
	}

	public function gridDemandaSolicitadaAction()
	{
		$this->_helper->layout->disableLayout();
			
		$cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];

		$mes    = $this->_request->getParam('mes');
		$ano    = $this->_request->getParam('ano');

		$mes    = ($mes == 0)?date('n'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;

		$mesAno = $this->objUtil->getMes($mes);
		$mes    = substr("00".$mes,-2);
		$mesAno = $mesAno."/".$ano;

		$arrDemandaSolicitada    = $this->objDemanda->getDemandaSolicitadaExecutorDemanda($mes, $ano, $cd_profissional);
        $arrTempoRespostaDemanda = array();

        if (count($arrDemandaSolicitada) > 0) {
            $arrTempoResposta = $this->objStatusAtendimento->getTempoResposta();

            foreach($arrDemandaSolicitada as $key=>$value) {
                $arrHistoricoDemanda = $this->objHistoricoDemanda->getDadosHistoricoExecucaoDemanda($value['cd_demanda'],null,$value['cd_profissional'],$value['cd_nivel_servico']);
                if(count($arrHistoricoDemanda) > 0 ){
                    $arrDemandaSolicitada[$key]['st_historico'] = "S";
                } else {
                    $arrDemandaSolicitada[$key]['st_historico'] = "N";
                }

                //Código para busca do máximo prazo de execução dos níveis de serviço
                $maxPrazoExecucaoNivelServico = $this->objDemandaProfissionalNivelServico->getMaxPrazoExecucaoNivelServico($value['cd_demanda'], $value['cd_nivel_servico']);
                //Calculo do tempo de resposta
                $hi  = $value['dt_leitura_nivel_servico'];
                $arr = Base_Controller_Action_Helper_Util::getTempoResposta($arrTempoResposta,$maxPrazoExecucaoNivelServico,$hi);
                $arrTempoRespostaDemanda[$value['cd_demanda']][$value['cd_nivel_servico']] = $arr;
            }
        }
		$this->view->res                     = $arrDemandaSolicitada;
		$this->view->mesAno                  = $mesAno;
        $this->view->arrTempoRespostaDemanda = $arrTempoRespostaDemanda;
	}

	public function gridDemandaExecutadaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];

		$mes    = $this->_request->getParam('mes');
		$ano    = $this->_request->getParam('ano');

		$mes    = ($mes == 0)?date('n'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;

		$mesAno = $this->objUtil->getMes($mes);
		$mes    = substr("00".$mes,-2);
		$mesAno = $mesAno."/".$ano;

		$arrDemandaExecutada = $this->objDemanda->getDemandaExecutadaExecutorDemanda($mes, $ano, $cd_profissional);

		$this->view->res    = $arrDemandaExecutada;
		$this->view->mesAno = $mesAno;
	}

	public function demandaDetalhesAction()
	{
		$this->buscaDetalhesDemanda();
	}

	public function gravaDataHoraLeituraDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_demanda       = (int)$this->_request->getParam('cd_demanda', 0);
		$cd_nivel_servico = (int)$this->_request->getParam('cd_nivel_servico', 0);
		$cd_profissional  = $_SESSION['oasis_logged'][0]['cd_profissional'];

		//Recupera Informações da demanda
		$rowDemanda             = $this->objDemanda->getDemanda($cd_demanda);
		//recupera informações da demanda com a designação do profissional
		$arrDemandaProfissional = $this->objDemandaProfissional->getDadosDemandaProfissional($cd_demanda, $cd_profissional);
		//Recupera informações da demanda com a designação com nivel de serviço
		$where = "cd_demanda = {$cd_demanda} and cd_profissional = {$cd_profissional} and cd_nivel_servico = {$cd_nivel_servico} ";
		$arrDemandaNivelServico = $this->objDemandaProfissionalNivelServico->fetchRow($where)->toArray();

		if (is_null($arrDemandaNivelServico['dt_leitura_nivel_servico']))
		{
			$addRow = array();
			$addRow['dt_leitura_nivel_servico'] = date('Y-m-d H:i:s');
				
			$erros = $this->objDemandaProfissionalNivelServico->atualizaDemandaNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico, $addRow);
			if ($erros === false){
				echo Base_Util::getTranslator('L_MSG_SUCESS_LEITURA_DEMANDA');
			} else {
				echo Base_Util::getTranslator('L_MSG_ERRO_INCLUIR_DATA_HORA_LEITURA_DEMANDA');
			}
		} else {
			echo Base_Util::getTranslator('L_MSG_ALERT_DEMANDA_LIDA_ANTERIORMENTE');
		}
	}

	public function executarDemandaAction()
	{
		$this->buscaDetalhesDemandaProfissional();
	}

	public function demandaExecutadaDetalhesAction()
	{
		$objHistoricoDemanda = new HistoricoExecucaoDemanda($this->_request->getControllerName());

		$cd_demanda       = $this->_request->getParam("cd_demanda");
		$cd_nivel_servico = $this->_request->getParam("cd_nivel_servico",0); 
		$cd_profissional  = $_SESSION['oasis_logged'][0]['cd_profissional']; 
		$aba_origem       = $this->_request->getParam("abaOrigem");
		$verificaAcesso   = "N";
		
		$arrDados = array();
		if($cd_nivel_servico != 0){		
			$whereProfissionalNivelServico = "cd_demanda = {$cd_demanda} and cd_profissional = {$cd_profissional} and cd_nivel_servico = {$cd_nivel_servico}";
			$arrDados = $this->objDemandaProfissionalNivelServico->fetchRow($whereProfissionalNivelServico);
			$verificaAcesso = "S";
		}
		if((count($arrDados)>0) || $verificaAcesso == "N"){
			$arrHistorico = $objHistoricoDemanda->getDadosHistoricoExecucaoDemanda($cd_demanda,null,$cd_profissional,$cd_nivel_servico);
			$this->buscaDetalhesDemanda();
			$this->view->arrHistorico = $arrHistorico;
		} else {
			$this->view->acessoProfissional = "N";
		}
		$this->view->abaOrigem = $aba_origem;
	}

	public function buscaDetalhesDemanda()
	{
		$cd_demanda       = $this->_request->getParam('cd_demanda');
		$cd_nivel_servico = $this->_request->getParam('cd_nivel_servico');
		
		$rowDemanda                   = $this->objDemanda->getDemanda($cd_demanda, $cd_nivel_servico);
		$rowDemanda['tx_demanda']     = str_ireplace('\"','"',$rowDemanda['tx_demanda']);
		$this->view->demanda          = $rowDemanda;
		$this->view->cd_nivel_servico = $cd_nivel_servico;
        $this->view->cd_profissional_logado = $_SESSION['oasis_logged'][0]['cd_profissional'];
	}
	
	public function buscaDetalhesDemandaProfissional()
	{
		$cd_demanda       = $this->_request->getParam('cd_demanda');
		$cd_nivel_servico = $this->_request->getParam('cd_nivel_servico');
		$cd_profissional  = $_SESSION['oasis_logged'][0]['cd_profissional']; 		
		
		$rowDemanda                   = $this->objDemanda->getDemanda($cd_demanda, $cd_nivel_servico, $cd_profissional);
		$rowDemanda['tx_demanda']     = str_ireplace('\"','"',$rowDemanda['tx_demanda']);
		$this->view->demanda          = $rowDemanda;
		$this->view->cd_nivel_servico = $cd_nivel_servico;
        $this->view->cd_profissional_logado = $_SESSION['oasis_logged'][0]['cd_profissional'];
	}

	public function fechaExecucaoDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$cd_demanda       = $this->_request->getParam("cd_demanda");
		$cd_profissional  = $this->_request->getParam("cd_profissional");
		$cd_nivel_servico = $this->_request->getParam("cd_nivel_servico"); 
		
		//atualiza a tabela s_demanda com os dados de execução da demanda
		if ($erros === false){
			$erros = $this->gravaExecucaoDemandaNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico);
		}

		$situacaoDemandaNivelServico = $this->objDemandaProfissionalNivelServico->getSituacaoDemandaNivelServico($cd_demanda, $cd_profissional);
		
		if ($erros === false && $situacaoDemandaNivelServico == "F"){
			$erros = $this->fechaDemandaProfissional($cd_demanda, $cd_profissional);
		}
		
		$situacaoDemandaProfissional = $this->objDemandaProfissional->getSituacaoDemandaProfissional($cd_demanda);
		
		if ($erros === false && $situacaoDemandaProfissional == "F"){
			$erros = $this->fechaDemanda($cd_demanda);
			if (K_ENVIAR_EMAIL == "S" && $erros === false) {
				$_objMail = new Base_Controller_Action_Helper_Mail();
				$_objMail->enviaEmail($_SESSION["oasis_logged"][0]["cd_objeto"], $this->_request->getControllerName(), $arrDados);
			}
		}
		
		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_FINALIZAR_DEMANDA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_FINALIZAR_DEMANDA');
		}
	}

	private function gravaExecucaoDemandaNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico)
	{
		$erros = false;

		$upRow                                = array();
		$upRow['st_fechamento_nivel_servico'] = "S";
		$upRow['dt_fechamento_nivel_servico'] = date('Y-m-d H:i:s');

		$erros = $this->objDemandaProfissionalNivelServico->atualizaDemandaNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico, $upRow);

		return $erros;
	}
	
	private function verificaDemandaSemSolicitacaoServico($cd_demanda)
	{
		$rowDemanda        = $this->objDemanda->fetchRow("cd_demanda = {$cd_demanda}")->toArray();
		$possuiSolicitacao = (!is_null($rowDemanda['ni_solicitacao']))? true : false;
		return $possuiSolicitacao;
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
	
	public function registraHistoricoDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		$post['cd_profissional'] = $_SESSION['oasis_logged'][0]['cd_profissional'];

        $post['dt_inicio'] = Base_Util::converterDatetime($post['dt_inicio'], 'DD/MM/YYYY', 'YYYY-MM-DD');
        $post['dt_fim'   ] = Base_Util::converterDatetime($post['dt_fim'   ], 'DD/MM/YYYY', 'YYYY-MM-DD');


		if($post['cd_historico_execucao_demanda'] != ""){
			//alterar historico da demanda
			$erros = $this->objHistoricoDemanda->alterarHistoricoExecucaoDemanda($post);
			if ($erros === false) {
				$db->rollback();
				echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
			} else {
				$db->commit();
				echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			}
		} else {
			//Registra historico da demanda
			$erros = $this->objHistoricoDemanda->salvarHistoricoExecucaoDemanda($post);
			if ($erros === false) {
				$db->rollback();
				echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
			} else {
				$db->commit();
				echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
		}
	}

	public function gridHistoricoExecucaoDemandaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_demanda       = $this->_request->getParam('cd_demanda');
		$cd_nivel_servico = $this->_request->getParam("cd_nivel_servico");
		 
		$arrDados = $this->objHistoricoDemanda->getDadosHistoricoExecucaoDemanda($cd_demanda,null,null,$cd_nivel_servico);
		
		$this->view->res = $arrDados;
	}

	public function excluirHistoricoExecucaoDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_historico_execucao = $this->_request->getParam('cd_historico_execucao');

		$return = $this->objHistoricoDemanda->excluirHistoricoExecucaoDemanda($cd_historico_execucao);
		$msg = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'): Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');

		echo $msg;
	}

	public function recuperarHistoricoExecucaoDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_historico_execucao = $this->_request->getParam('cd_historico_execucao');
		$arrDados = $this->objHistoricoDemanda->getDadosHistoricoExecucaoDemanda(null,$cd_historico_execucao);

		$arrDados = $arrDados[0];

		$arrDados['dt_inicio'] = date('d/m/Y H:i:s', strtotime($arrDados['dt_inicio']));
		$arrDados['dt_fim'   ] = date('d/m/Y H:i:s', strtotime($arrDados['dt_fim']));

		echo  Zend_Json_Encoder::encode($arrDados);
	}
}