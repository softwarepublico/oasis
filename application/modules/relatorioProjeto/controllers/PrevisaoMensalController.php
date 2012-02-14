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

class RelatorioProjeto_PrevisaoMensalController extends Base_Controller_Action
{
	private $arrParam;
	private $arrProjetos;
	private $arrPrevisaoMensal;
	private $arrHeader;
	private $arrHeaderTam;
	private $arrTotalHoras;
	private $quantMes;
	private $objPrevisaoMensalProjeto;
	private $objUtil;
    private $_objContrato;
	
	public function init()
	{
		parent::init();
		$this->objPrevisaoMensalProjeto = new RelatorioProjetoPrevisaoMensalProjeto();
		$this->objUtil                  = new Base_Controller_Action_Helper_Util();
        $this->_objContrato             = new Contrato();
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PREVISAO_MENSAL'));

        $cd_contrato = null;
        $comStatus	 = true;
        if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->arrContrato = $this->_objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}
	
	public function generateAction()
	{
		$this->_helper->layout->disableLayout();

        $this->arrParam['cd_contrato'] = $this->_request->getParam('cd_contrato',0);
		$this->arrParam['mesInicial' ] = $this->_request->getParam('mes_inicial',0);
		$this->arrParam['mesFinal'   ] = $this->_request->getParam('mes_final',0);
		$this->arrParam['anoInicial' ] = $this->_request->getParam('ano_inicial',0);
		$this->arrParam['anoFinal'   ] = $this->_request->getParam('ano_final',0);

		$this->arrHeader[0]     = Base_Util::getTranslator('L_VIEW_PROJETOS');
		$this->arrHeaderTam[0]  = '63';
		$this->arrTotalHoras[0] = Base_Util::getTranslator('L_VIEW_TOTAL');
				
		if($this->arrParam['anoFinal'] != 0 && $this->arrParam['anoFinal'] != $this->arrParam['anoInicial']){
			$this->relatorioProjetoPrevisaoAnual();
		} else {
			if($this->arrParam['mesFinal'] != 0 && $this->arrParam['mesInicial'] > $this->arrParam['mesFinal']){
				$mesAux                       = $this->arrParam['mesFinal'];
				$this->arrParam['mesFinal']   = $this->arrParam['mesInicial']; 
				$this->arrParam['mesInicial'] = $mesAux;
				$this->relatorioProjetoPrevisaoMensais();
                
			} else if($this->arrParam['mesFinal'] != 0 && $this->arrParam['mesInicial'] < $this->arrParam['mesFinal']) {
				$this->relatorioProjetoPrevisaoMensais();

			} else if($this->arrParam['mesFinal'] == 0 || $this->arrParam['mesInicial'] == $this->arrParam['mesFinal']){
				$this->arrParam['mesFinal'] = $this->arrParam['mesInicial'];
				$this->relatorioProjetoPrevisaoMensal();
			}
		}

		$this->view->quantMes          = $this->quantMes;
		$this->view->arrHeader         = $this->arrHeader;
		$this->view->arrHeaderTam      = $this->arrHeaderTam;
		$this->view->arrPrevisaoMensal = $this->arrPrevisaoMensal;
		$this->view->arrProjetos       = $this->arrProjetos;
		$this->view->arrParam          = $this->arrParam;
		$this->view->arrTotalHoras     = $this->arrTotalHoras;
	}
	
	private function relatorioProjetoPrevisaoMensal()
	{
		//verifica a quantidade de meses que será colocado no relatório
		$this->quantMes = $this->arrParam['mesFinal'] - $this->arrParam['mesInicial'];
		 
		$horas_total = 0;
		$this->arrPrevisaoMensal = $this->objPrevisaoMensalProjeto->getPrevisaoMensal($this->arrParam);
		foreach($this->arrPrevisaoMensal as $key=>$value){
			$arrDados = $this->objPrevisaoMensalProjeto->getHorasMensalProjeto($value['cd_projeto'],$this->arrParam['mesInicial'],$this->arrParam['anoInicial']);
			if(count($arrDados) == 0){
				$arrDados['ni_mes_previsao_parcela'] = $mesInicial;
				$arrDados['horas_total_projeto'] = "---";
				$horas_total += 0;
			} else {
				$horas_total += round($arrDados[0]['horas_total_projeto'],1);
			}
			$this->arrProjetos[$value['cd_projeto']][0] = $arrDados;
		}
		$this->arrTotalHoras[1]= $horas_total;  
		$this->arrHeader[1]    = $this->objUtil->getMes($this->arrParam['mesInicial']);
		$this->arrHeaderTam[1] = '30';
	}
	
	private function relatorioProjetoPrevisaoMensais()
	{
		$this->quantMes = $this->arrParam['mesFinal'] - $this->arrParam['mesInicial'];
		
		$this->arrPrevisaoMensal = $this->objPrevisaoMensalProjeto->getPrevisaoMensal($this->arrParam);
		$ano        = $this->arrParam['anoInicial'];
		$mesFinal   = $this->arrParam['mesFinal'];
		$mesInicial = $this->arrParam['mesInicial'];
		
		for($i=1;$mesInicial <= $mesFinal;$i++){
			$this->arrTotalHoras[$i] = 0;
			$mesInicial++;
		}
		
		foreach($this->arrPrevisaoMensal as $key=>$chave){
			$mesInicial = $this->arrParam['mesInicial'];
			for($i=0;$mesInicial <= $mesFinal;$i++){
				$arrDados = $this->objPrevisaoMensalProjeto->gethorasMensalProjeto($chave['cd_projeto'],$mesInicial,$ano);
				if(count($arrDados) == 0){
					$arrDados[0]['ni_mes_previsao_parcela'] = $mesInicial;
					$arrDados[0]['horas_total_projeto']     = "---";
					$this->arrTotalHoras[$i+1] += 0;
				} else {
					$this->arrTotalHoras[$i+1] += round($arrDados[0]['horas_total_projeto'],1);
				}
				$this->arrProjetos[$chave['cd_projeto']][$i] = $arrDados; 
				$mesInicial++;
			}
		}
		
		$mesInicial = $this->arrParam['mesInicial'];
		
		$tamCol = ($this->quantMes < 6)?19:17;
		for($i=1;$mesInicial <= $mesFinal;$i++){
			$this->arrHeader[$i]    = $this->objUtil->getMes($mesInicial);
			$this->arrHeaderTam[$i] = $tamCol;
			$mesInicial++;
		}
	}
	
	private function relatorioProjetoPrevisaoAnual()
	{
		$quantAno = $this->arrParam['anoFinal'] - $this->arrParam['anoInicial']; 

		$mesInicial = $this->arrParam['mesInicial'];		
		$mesFinal   = $this->arrParam['mesFinal'];
		$anoInicial = $this->arrParam['anoInicial'];
		
		//verifica a quantidade de meses do relatório
		$this->quantMes = (13 - $mesInicial) + $mesFinal;
		$tamCol = ($this->quantMes < 6)?19:17;
		
		//Loop para buscar a descrição dos meses
		$aux = "";
		for($i=0;$i <= $this->quantMes;$i++){
			$this->arrTotalHoras[$i+1] = 0;
			$this->arrHeader[$i+1]    = $this->objUtil->getMes($mesInicial);
			$this->arrHeaderTam[$i+1] = $tamCol;
			if($mesInicial == $mesFinal){
				if($anoInicial == $this->arrParam['anoFinal']){
					break;
				}
			}
			$mesInicial++;
			if($mesInicial == 13){
				$mesInicial = 1;
				$anoInicial++;
			}
		}
		
		$this->arrPrevisaoMensal = $this->objPrevisaoMensalProjeto->getPrevisaoMensais($this->arrParam);
	    foreach($this->arrPrevisaoMensal as $key=>$chave){
			$mesInicial = $this->arrParam['mesInicial'];
			$anoInicial = $this->arrParam['anoInicial'];
			
			for($i=0;$i <= $this->quantMes;$i++){
				$arrDados = $this->objPrevisaoMensalProjeto->gethorasMensalProjeto($chave['cd_projeto'],$mesInicial,$anoInicial);
				if(count($arrDados) == 0){
					$arrDados[0]['ni_mes_previsao_parcela'] = $mesInicial;
					$arrDados[0]['horas_total_projeto']     = "---";
					$this->arrTotalHoras[$i+1] += 0;
				} else {
					$this->arrTotalHoras[$i+1] += round($arrDados[0]['horas_total_projeto'],1);
				}
				$this->arrProjetos[$chave['cd_projeto']][$i] = $arrDados;
				 
				if($mesInicial == $mesFinal){
					if($anoInicial == $this->arrParam['anoFinal']){
						break;
					}
				}
				$mesInicial++;
				if($mesInicial == 13){
					$mesInicial = 1;
					$anoInicial++;
				}
			}
		}
	}
}