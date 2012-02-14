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

class PainelDemandaController extends Base_Controller_Action
{
	private $demanda;
	private $objUtil;
	private $objDemandaProfissional;
	private $objDemandaProfissionalNivelServico;
	private $objeto;
	private $objNivelServico;
    private $objStatusAtendimento;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_PAINEL_DEMANDA'));
		$this->objUtil                            = new Base_Controller_Action_Helper_Util();
		$this->demanda                            = new Demanda($this->_request->getControllerName());
		$this->objDemandaProfissional             = new DemandaProfissional($this->_request->getControllerName());
		$this->objDemandaProfissionalNivelServico = new DemandaProfissionalNivelServico($this->_request->getControllerName());
		$this->objeto                             = new ObjetoContrato($this->_request->getControllerName());
		$this->objNivelServico                    = new NivelServico($this->_request->getControllerName());
        $this->objStatusAtendimento               = new StatusAtendimento($this->_request->getControllerName());
	}

	public  function indexAction()
	{
		$this->view->mesAno = "{$this->objUtil->getMes(date('n'))}/".date('Y');
		
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

	public function gridDemandaAndamentoAction()
	{
		$this->_helper->layout->disableLayout();

		$mes    = $this->_request->getParam('mes');
		$ano    = $this->_request->getParam('ano');
		
		$cd_profissional = $this->_request->getParam('cd_profissional');
		$cd_profissional = ($cd_profissional > -1)?$cd_profissional:null;		

		$mes    = ($mes == 0)?date('m'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;

		$mesAno = $this->objUtil->getMes($mes);
		$mesAno = $mesAno."/".$ano;
		$mes    = substr("00".$mes,-2);

		$cd_objeto           = $_SESSION['oasis_logged'][0]['cd_objeto'];	
			
		$arrDemandaAndamento     = $this->demanda->getDemandaAndamento($mes, $ano, $cd_objeto, $cd_profissional);
		$arrProfissionalDemanda  = array();
		$arrTempoRespostaDemanda = array();
		if(count($arrDemandaAndamento) > 0){

            $arrTempoResposta = $this->objStatusAtendimento->getTempoResposta();
            
			foreach($arrDemandaAndamento as $key=>$value){
				$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($value['cd_demanda'], $cd_objeto, "S",3);
				$arrProfissionalDemanda[$value['cd_demanda']] = $arrDemandaProfissional;
				if(!array_key_exists($value['cd_demanda'],$arrProfissionalDemanda)){
					$arrProfissionalDemanda[$value['cd_demanda']] = array();
				}

                //Código para busca do máximo prazo de execução dos níveis de serviço
                $maxPrazoExecucaoNivelServico = $this->objDemandaProfissionalNivelServico->getMaxPrazoExecucaoNivelServico($value['cd_demanda']);
                //Calculo do tempo de resposta
                $hi  = $value['dt_demanda'];
                $arr = Base_Controller_Action_Helper_Util::getTempoResposta($arrTempoResposta,$maxPrazoExecucaoNivelServico,$hi);
                $arrTempoRespostaDemanda[$value['cd_demanda']] = $arr;
			}
		}
		$this->view->arrProfissionalDemanda  = $arrProfissionalDemanda;
		$this->view->res                     = $arrDemandaAndamento;
		$this->view->mesAno                  = $mesAno;
        $this->view->arrTempoRespostaDemanda = $arrTempoRespostaDemanda;

    }

	public function gridDemandaExecutadaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes    = $this->_request->getParam('mes');
		$ano    = $this->_request->getParam('ano');
		
		$cd_profissional = $this->_request->getParam('cd_profissional');
		$cd_profissional = ($cd_profissional > -1)?$cd_profissional:null;				

		$mes    = ($mes == 0)?date('m'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;

		$mesAno = $this->objUtil->getMes($mes);
		$mesAno = $mesAno."/".$ano;
		$mes    = substr("00".$mes,-2);
		
		$cd_objeto           = $_SESSION['oasis_logged'][0]['cd_objeto'];			

		$arrDemandaExecutada = $this->demanda->getDemandaExecutada($mes, $ano, $cd_objeto, $cd_profissional);
		
		if ($arrDemandaExecutada)
		{
			foreach($arrDemandaExecutada as $key=>$value){
				$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($value['cd_demanda'], $cd_objeto, "S",3);
				$arrProfissionalDemanda[$value['cd_demanda']] = $arrDemandaProfissional;
				if(!array_key_exists($value['cd_demanda'],$arrProfissionalDemanda)){
					$arrProfissionalDemanda[$value['cd_demanda']] = array();
				}
			}
		}
		else
		{
			$arrProfissionalDemanda[] = array();
		}		
		
		$this->view->arrProfissionalDemanda = $arrProfissionalDemanda;		
		$this->view->res                    = $arrDemandaExecutada;
		$this->view->mesAno                 = $mesAno;
	}

	public function gridDemandaConcluidaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes    = $this->_request->getParam('mes');
		$ano    = $this->_request->getParam('ano');
		
		$cd_profissional = $this->_request->getParam('cd_profissional');
		$cd_profissional = ($cd_profissional > -1)?$cd_profissional:null;

		$mes    = ($mes == 0)?date('m'):$mes;
		$ano    = ($ano == 0)?date('Y'):$ano;

		$mesAno = $this->objUtil->getMes($mes);
		$mesAno = $mesAno."/".$ano;
		$mes    = substr("00".$mes,-2);

		$cd_objeto           = $_SESSION['oasis_logged'][0]['cd_objeto'];	
				
		$arrDemandaConcluida = $this->demanda->getDemandaConcluida($mes, $ano, $cd_objeto, $cd_profissional);
		
		$arrProfissionalDemanda = array();
		if(count($arrDemandaConcluida) > 0){		
			foreach($arrDemandaConcluida as $key=>$value){
				$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($value['cd_demanda'], $cd_objeto, "S",3);
				$arrProfissionalDemanda[$value['cd_demanda']] = $arrDemandaProfissional;
				if(!array_key_exists($value['cd_demanda'],$arrProfissionalDemanda)){
					$arrProfissionalDemanda[$value['cd_demanda']] = array();
				}
			}	
		}	
		
		$this->view->arrProfissionalDemanda = $arrProfissionalDemanda;		
		$this->view->res                    = $arrDemandaConcluida;
		$this->view->mesAno                 = $mesAno;
	}
	
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
	
	public function reencaminharDemandaAction()
	{
		$cd_demanda             = (int)$this->_request->getParam('cd_demanda');	
		$rowDemanda             = $this->demanda->getDemanda($cd_demanda);
		
		$this->view->arrNivelServico = $this->objNivelServico->getNivelServico($rowDemanda["cd_objeto"], true);
				
		$this->view->demanda    = $rowDemanda;
		
		//Combo Prioridade
		$this->view->comboPrioridade = $this->objUtil->getPrioridade();
		
	}
	
	public function concluirDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_demanda = $this->_request->getParam('cd_demanda');		

		$arrUpdate['st_conclusao_demanda'] = "S"; 
		$arrUpdate['dt_conclusao_demanda'] = date('Y-m-d H:i:s'); 
		
		$erros = $this->demanda->atualizaDemanda($cd_demanda, $arrUpdate);
		$msg   = (!$erros)? Base_Util::getTranslator('L_MSG_SUCESS_DEMANDA_CONCLUIDA'):Base_Util::getTranslator('L_MSG_ERRO_DEMANDA_CONCLUIDA');
		echo $msg;
	}	
	
	public function demandaExecutadaDetalhesAction()
	{
		$objDemanda   = new Demanda($this->_request->getControllerName());
		$objHistorico = new HistoricoExecucaoDemanda($this->_request->getControllerName());
		
		$cd_demanda             = $this->_request->getParam("cd_demanda");
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
}