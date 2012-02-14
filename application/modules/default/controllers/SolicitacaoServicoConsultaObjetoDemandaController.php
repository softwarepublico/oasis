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

class SolicitacaoServicoConsultaObjetoDemandaController extends Base_Controller_Action
{

	private $zendDate;
	private $objSolicitacao;
	private $objDemandaProfissional;
	private $objDemandaProfissionalNivelServico;
	private $objDemanda;	

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_SOLICITACOES_DE_SERVICO'));
		$this->zendDate                           = new Zend_Date();
		$this->objSolicitacao                     = new Solicitacao($this->_request->getControllerName());
		$this->objDemandaProfissional             = new DemandaProfissional($this->_request->getControllerName());
		$this->objDemandaProfissionalNivelServico = new DemandaProfissionalNivelServico($this->_request->getControllerName());
		$this->objDemanda                         = new Demanda($this->_request->getControllerName());		
	}

	public function indexAction()
	{

	}

	public function gridSolicitacaoTipoDemandaConsultaAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_unidade      = ($this->_request->getParam('cd_unidade')            ) ? $this->_request->getParam('cd_unidade')                     :null;
		$tx_solicitante  = ($this->_request->getParam('tx_solicitante')        ) ? $this->toUpper($this->_request->getParam('tx_solicitante')) :null;
		$dt_inicio       = ($this->_request->getParam('dt_inicio')             ) ? Base_Util::converterDate($this->_request->getParam('dt_inicio'), 'DD/MM/YYYY', 'YYYY-MM-DD') : null;
		$dt_fim          = ($this->_request->getParam('dt_fim')                ) ? Base_Util::converterDate($this->_request->getParam('dt_fim'), 'DD/MM/YYYY', 'YYYY-MM-DD') : null;
		$cd_profissional = ($this->_request->getParam('cd_profissional')!= -1  ) ? $this->_request->getParam('cd_profissional')                :null;
		$tx_demanda      = ($this->_request->getParam('tx_demanda')            ) ? $this->toUpper($this->_request->getParam('tx_demanda'))     :null;
		$tipo_consulta   = ($this->_request->getParam('tipo_consulta')         ) ? $this->toUpper($this->_request->getParam('tipo_consulta'))  : "AND";
		$solicitacao     = ($this->_request->getParam('solicitacao')           ) ? $this->_request->getParam('solicitacao')                    : null;

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

		if ($arrSolicitacaoTipoDemandaConsulta){
			foreach($arrSolicitacaoTipoDemandaConsulta as $key=>$value){
				if($value['cd_demanda'] != ""){
					$arrDemandaProfissional = $this->objDemandaProfissional->getProfissionalDemanda($value['cd_demanda'], $value['cd_objeto'], "S",3);
					$arrProfissionalDemanda[$value['cd_demanda']] = $arrDemandaProfissional;
					if(!array_key_exists($value['cd_demanda'],$arrProfissionalDemanda)){
						$arrProfissionalDemanda[$value['cd_demanda']] = array();
					}
				}
			}
		}else{
			$arrProfissionalDemanda[] = array();
		}		
		
		$this->view->res                    = $arrSolicitacaoTipoDemandaConsulta;
		$this->view->arrProfissionalDemanda = $arrProfissionalDemanda;
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

	public function tabDetalheSolicitacaoAction()
	{
		$this->_helper->layout->disableLayout();
				
		$objDemanda   = new Demanda($this->_request->getControllerName());
		$objHistorico = new HistoricoExecucaoDemanda($this->_request->getControllerName());
		
		$cd_demanda             = $this->_request->getParam("cd_demanda");
		$this->view->tab_origem = $this->_request->getParam("tab_origem");
		
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