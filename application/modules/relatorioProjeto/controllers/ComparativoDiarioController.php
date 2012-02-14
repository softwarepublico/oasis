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

class RelatorioProjeto_ComparativoDiarioController extends Base_Controller_Action
{
	private $objProfissionalObjetoContrato;
	private $diaFinalMes;
	private $mes;
	private $ano;
	private $arrTamHeader;
	private $arrDescSemana;
	private $objObjetoContrato;
	private $_st_objeto_contrato;
	
	public function init()
	{
		parent::init();
        //Incluindo a classe para pegar as constantes
        Zend_Loader::loadClass('RelatorioDiversoHistorico',Base_Util::baseUrlModule('relatorioDiverso', 'models'));

		$this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$this->objObjetoContrato             = new ObjetoContrato($this->_request->getControllerName()); 	
	}
	
	/**
	 * Método utilizado para a tela do relatório.
	 *
	 */
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_COMPARATIVO_DIARIO_HISTORICO'));
		
		$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];
    	$arrProfissional = $this->objProfissionalObjetoContrato->getProfissionalObjetoContrato($cd_objeto,true);
		$this->view->arrProfissional = $arrProfissional;
	}

	/**
	 * Método que utilizando para inicializar o relatório.
	 * Recupera todas as informações necessarias para que seja montado
	 * os dados do relatório
	 *
	 */
	public function generateAction()
	{
		$this->_helper->layout->disableLayout();
		//Instanciando o objeto Historico 
		$objRelHistorico = new RelatorioDiversoHistorico();
		//Recupera os parametros da tela
		$this->mes             = $this->_request->getParam("mes");
		$this->ano             = $this->_request->getParam("ano");
		$cd_profissional = $this->_request->getParam("cd_profissional");
		//Verifica a quantidade de dias no mês
		$this->diaFinalMes = cal_days_in_month(CAL_GREGORIAN, $this->mes, $this->ano);
		$cd_objeto = $_SESSION['oasis_logged'][0]['cd_objeto'];

		//Condição que verifica ser a consulta e por um técnico especifico ou por todos
		if($cd_profissional == "-1"){
			$arrProfissional = $this->objProfissionalObjetoContrato->getProfissionalObjetoContrato($cd_objeto,false);
		} else {
			$objProfissional = new Profissional($this->_request->getControllerName());
			$arrDadosProfissional = $objProfissional->find($cd_profissional)->current()->toArray();
			$arrProfissional[$cd_profissional] = $arrDadosProfissional['tx_profissional'];
			unset($arrDadosProfissional);
		}
		$objContrato =  $this->objObjetoContrato->find($cd_objeto)->current();

        $this->_st_objeto_contrato = $objContrato->st_objeto_contrato;

//		if($objContrato->st_objeto_contrato == "P"){
		if($this->_st_objeto_contrato == "P"){
			$metodoPesquisa = "getProjetoComparativoDiario"; 
		} else {
			$metodoPesquisa = "getDemandaComparativoDiario"; 
		}

		foreach($arrProfissional as $key=>$value){
			$arrQuantHistorico          = $objRelHistorico->$metodoPesquisa($key,$this->diaFinalMes,$this->mes,$this->ano);
			$arrQuantProfissional[$key] = $this->montaDadosProfissional($arrQuantHistorico, $value);
		}
		
		$this->view->arrHeader       = $this->montaCabecalhoGrid();
		$this->view->arrTamHeader    = $this->arrTamHeader;
		$this->view->arrDescSemana   = $this->arrDescSemana;
		$this->view->arrProfissional = $arrProfissional; 
		$this->view->arrQuantProf    = $arrQuantProfissional; 	
	}
	
	/**
	 * Método que monta um array com os dias úteis do mês da pesquisa como índice e o valor
	 * é a quantidade de demanda realizado no dia.  
	 *
	 * @param array $arrProfissional
	 * @return array $arrQuantProf;
	 */
	private function montaDadosProfissional($arrProfissional, $tx_profissional)
	{
		$dia = 0;
		$arrQuantProf = array();
		$totalDemanda = 0; 
		if(count($arrProfissional) > 0){

			foreach($arrProfissional as $key=>$value){
                if($this->_st_objeto_contrato == "P"){
                    //explode a data para pegar o dia
                    $arrData = explode('-',$value['dt_inicio_historico']);
                }else{
                    $arrData = explode('-',date('Y-m-d',strtotime($value['dt_inicio_historico'])));
                }
				if($dia != $arrData[2]){
					$arrQuantProf[$arrData[2]] = $value['quant'];
					$totalDemanda += $value['quant'];
				} else {
					$arrQuantProf[$arrData[2]] += $value['quant'];
					$totalDemanda += $value['quant'];
				}
				$dia = $arrData[2];
			}
		}
		
		for($dia=01;$dia<=$this->diaFinalMes;$dia++){
			$dia_0 = substr("00".$dia,-2);
			if(!array_key_exists($dia_0, $arrQuantProf)){
				$arrQuantProf[$dia_0] = " - ";
			} 
		}
		
		$continuacao = "";
		if(strlen($tx_profissional) > 33){
			$continuacao = "...";
		}
		
		$arrQuantProf['00'] = substr($tx_profissional,0,33).$continuacao;
		ksort($arrQuantProf);
		$arrQuantProf[count($arrQuantProf)] = "".$totalDemanda;
		return $arrQuantProf;
	}
	
	/**
	 * Método que monta o cabeçalho da grid do relatório com os dias do mês da pesquisa
	 *
	 * @return array $arrHeader;
	 */
	private function montaCabecalhoGrid()
	{
		$objUtil = new Base_Controller_Action_Helper_Util();
//        $arrDescSemana = array('D','S','T','Q','Q','S','S');
		$arrDescSemana = array(Base_Util::getTranslator('L_VIEW_DIA_SEMANA_DOMINGO'),
                               Base_Util::getTranslator('L_VIEW_DIA_SEMANA_SEGUNDA'),
                               Base_Util::getTranslator('L_VIEW_DIA_SEMANA_TERCA'),
                               Base_Util::getTranslator('L_VIEW_DIA_SEMANA_QUARTA'),
                               Base_Util::getTranslator('L_VIEW_DIA_SEMANA_QUINTA'),
                               Base_Util::getTranslator('L_VIEW_DIA_SEMANA_SEXTA'),
                               Base_Util::getTranslator('L_VIEW_DIA_SEMANA_SABADO'));
		
		//Cabecalho
		$arrHeader[0]           = Base_Util::getTranslator('L_VIEW_TECNICO');
		//Tamanho do cabecalho
		$this->arrTamHeader[0]  = 66;
		//Descrição do dia da semanda para o cabecalho
		$this->arrDescSemana[0] = $objUtil->getMes($this->mes)."/".$this->ano;
		
		for($dia=1;$dia<=$this->diaFinalMes;$dia++){
			$dateTime   = mktime(0, 0, 0, $this->mes, $dia, $this->ano);
			$dia_semana = date("w", $dateTime);
			$arrHeader[$dia]           = substr("00".$dia,-2);
			$this->arrTamHeader[$dia]  = 6;
			$this->arrDescSemana[$dia] = $arrDescSemana[$dia_semana];
		}
		//Inclusão do Total das demandas do dia
		$quantArr = count($arrHeader);
		$arrHeader[$quantArr]           = Base_Util::getTranslator('L_VIEW_TOTAL');
		$this->arrTamHeader[$quantArr]  = 15;
		$this->arrDescSemana[$quantArr] = "";
        
		return $arrHeader;
	}
}