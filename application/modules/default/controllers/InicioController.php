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

class InicioController extends Base_Controller_Action 
{
	private $objBoxInicio;
	private $objPerfilBoxInicio;
	private $objProfissionalObjetoContrato;
	private $objObjetoContrato;
	private $objContrato;
	private $objSolicitacoes;
	private $objProposta;
	private $objDemandaProfissional;
	private $arrProfissionalObjeto = array();
	private $strPosisaoBoxInicio;
	private $objUtil;
	private $objMensageria;
	private $objProfissionalMensageria;
	private $objControle;

	private $comboTipoBox;
	
    public function init()
	{
		parent::init();

		$this->objBoxInicio                  = new BoxInicio($this->_request->getControllerName());
		$this->objPerfilBoxInicio            = new PerfilBoxInicio($this->_request->getControllerName());
		$this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$this->objObjetoContrato             = new ObjetoContrato($this->_request->getControllerName());
		$this->objContrato                   = new Contrato($this->_request->getControllerName());
		$this->objSolicitacoes				 = new Solicitacao($this->_request->getControllerName());
		$this->objProposta                   = new Proposta($this->_request->getControllerName());
		$this->objDemandaProfissional		 = new DemandaProfissional($this->_request->getControllerName());
		$this->objMensageria				 = new Mensageria($this->_request->getControllerName());
		$this->objProfissionalMensageria	 = new ProfissionalMensageria($this->_request->getControllerName());
		$this->objControle                   = new Controle($this->_request->getControllerName());
		$this->objUtil						 = new Base_Controller_Action_Helper_Util();
		
		$this->view->comboTipoBox						= $this->objBoxInicio->getArrTipoBox();
		$this->arrProfissionalObjeto['cd_profissional'] = $_SESSION['oasis_logged'][0]['cd_profissional'];
		$this->arrProfissionalObjeto['cd_objeto']       = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$this->arrProfissionalObjeto['cd_perfil']       = $_SESSION['oasis_logged'][0]['cd_perfil'];
		$this->arrProfissionalObjeto['tipo_box'] 		= $this->objObjetoContrato
		   											           ->find( $this->arrProfissionalObjeto['cd_objeto'] )
													           ->current()
													           ->st_objeto_contrato;
	}
	
	public function indexAction()
	{
        
       if ($_SESSION['oasis_logged'][0]['host'] != $_SERVER['HTTP_HOST'] ) {
           $this->_redirect("/auth/logout");
       }
		if( $_SESSION["oasis_logged"][0]["st_dados_todos_contratos"] === "S" ){

			$arrDadosContrato = $this->objContrato->find($_SESSION["oasis_logged"][0]["cd_contrato"])->current();
			$this->view->tx_contrato_prof_associado = $arrDadosContrato->tx_numero_contrato;
		}

		//carrega os boxes associados ao perfil de quem loga
	    $this->strPosisaoBoxInicio	= $this->objProfissionalObjetoContrato->getPosicaoBoxInicio( $this->arrProfissionalObjeto['cd_profissional'], $this->arrProfissionalObjeto['cd_objeto'] );
		$this->view->box_list		= $this->strPosisaoBoxInicio;

		$objMensagens = $this->objMensageria->getMsgObjetoPerfilPeriodo($this->arrProfissionalObjeto['cd_objeto'], $this->arrProfissionalObjeto['cd_profissional'],true);

		$possuiMensagemAberta	= false;
		if( $objMensagens->valid() ){
			$possuiMensagemAberta	= true;
		}
		$this->view->msgAberta		= $possuiMensagemAberta;
	}

	public function dialogMensagensNaoLidasAction()
	{
		$this->_helper->layout->disableLayout();
		$this->view->objMensagens = $this->objMensageria->getMsgObjetoPerfilPeriodo($this->arrProfissionalObjeto['cd_objeto'], $this->arrProfissionalObjeto['cd_profissional'], true);
	}

	public function confirmaLeituraMensagemAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		//recebe os codigos das mensagens exibidas na tela para leitura e transforma em array
		$arrCdMensagens = explode("|", $this->_request->getParam("cd_mensagens_nao_lidas"));

		$dtLeitura = date("Y-m-d H:i:s");

		$arrUpdate['dt_leitura_mensagem'] = new Zend_Db_Expr($this->objProfissionalMensageria->to_timestamp("'{$dtLeitura}'",'YYYY-MM-DD HH24:MI:SS'));
        
        

		$arrResult = array(
            'error'=>false, 
            'typeMsg'=>1, 
            'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_LEITURA'), 
            'dtLeitura'=>date('d/m/Y H:m:s',strtotime($dtLeitura))
        );

        $this->objProfissionalMensageria->getDefaultAdapter()->beginTransaction();
        try{
			foreach( $arrCdMensagens as $key=>$cd_mensagem ){
				$where = array(
                    "cd_mensageria = ?"   => $cd_mensagem,
                    "cd_profissional = ?" => $this->arrProfissionalObjeto['cd_profissional']
                );
                
				if(	!$this->objProfissionalMensageria->update($arrUpdate, $where)){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_CONFIRMAR_LEITURA'));
				}
			}
			$this->objProfissionalMensageria->getDefaultAdapter()->commit();
        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'	] = $e->getMessage();
			$this->objProfissionalMensageria->getDefaultAdapter()->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
            $this->objProfissionalMensageria->getDefaultAdapter()->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	public function dialogMensagensAntigasAction()
	{
		$this->_helper->layout->disableLayout();
	}

	public function pesquisaMensagensAntigasAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$arrDados = $this->_request->getPost();

		$arrData['dt_inicial']	= '';
		$arrData['dt_final'  ]	= null;

		$arrData['dt_inicial']	= $arrDados['ano_msg_inicial']."-".substr("00".$arrDados['mes_msg_inicial'],-2)."-01 00:00:00";

		if( ($arrDados['mes_msg_final'] != 0) && ($arrDados['ano_msg_final'] != 0) ){
			$diasMes              = cal_days_in_month(CAL_GREGORIAN, $arrDados['mes_msg_final'], $arrDados['ano_msg_final']);
			$arrData['dt_final'  ] = $arrDados['ano_msg_final']."-".substr("00".$arrDados['mes_msg_final'],-2)."-{$diasMes} 23:59:59";
		}

		$arrMensagem = $this->objMensageria->getMsgLidaProfissional( $this->arrProfissionalObjeto['cd_objeto'], $this->arrProfissionalObjeto['cd_profissional'], $arrData );

		$strHtml = '';
		$count	 = 1;

		if( count($arrMensagem) > 0 ){
			foreach( $arrMensagem as $msg ){

				$dtLeitura = '';
				$confirmacao_leitura = '';
				if( $msg['dt_leitura_mensagem'] != '' ){
					$dtLeitura = '<p class="float-l clear-l" style="color: red; text-decoration: underline;">'.Base_Util::getTranslator('L_VIEW_DATA_LEITURA').': '. date('d/m/Y H:m:s',strtotime($msg['dt_leitura_mensagem'])).'</p>' ;
				}else{
					$onclick = 'onclick="confirmaLeituraMensagem('.$msg['cd_mensageria'].')"';
					$confirmacao_leitura = '<p class="float-l clear-l">
												<button class="float-l verde" '.$onclick.' value="Confirmar Leitura" type="button" id="btn_conf_leitura_msg_'.$msg["cd_mensageria"].'" name="btn_conf_leitura_msg_'.$msg["cd_mensageria"].'">'.Base_Util::getTranslator('L_VIEW_CONFIRMAR_LEITURA').'</button>
											 </p>';
					$confirmacao_leitura .= '<p class="float-l clear-l" style="color: red; text-decoration: underline;">
												<span id="span_conf_leitura_msg_'.$msg['cd_mensageria'].'" style="display: none;" ></span>
											</p>';
				}

				$strHtml .=	'<p class="float-l clear-l bold" style="text-decoration: underline;">'.Base_Util::getTranslator('L_VIEW_MENSAGEM').'&nbsp;'.$count.'</p>';
				$strHtml .= '<div class="float-l clear-l">';
				$strHtml .=		$msg['tx_mensagem'];
				$strHtml .= '</div>';
				$strHtml .= $dtLeitura;
				$strHtml .= $confirmacao_leitura;
				$strHtml .= '<br>';

				$count++;
			}
		}else{
			$strHtml = '<p class="float-l clear-l bold" style="color:red; text-decoration: underline;">'.Base_Util::getTranslator('L_MSG_ALERT_NAO_EXISTE_MENSAGEM_PERIODO').'</p>';
		}
		echo $strHtml;
	}

	public function boxTrocaContratoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->view->arrContrato = $this->objContrato->getTodosContratos(false, true);
	}

	public function boxManagerAction()
	{
		$this->_helper->layout->disableLayout();

		$arrPosisao	= explode(',',$this->strPosisaoBoxInicio);
        $result		= $this->objPerfilBoxInicio->getBoxesByPerfil( $this->arrProfissionalObjeto['cd_perfil'], $this->arrProfissionalObjeto['cd_objeto'] );


        $arrBoxes = array();
        if(count($result) > 0){
	        $i=0;
	        foreach( $result as $box ){
	            $boxAll   = $this->objBoxInicio->find( $box->cd_box_inicio );
	            $boxAtual = $boxAll->current();
	            if( $boxAtual->st_tipo_box_inicio == $this->arrProfissionalObjeto['tipo_box'] || $boxAtual->st_tipo_box_inicio == "A" || $this->arrProfissionalObjeto['cd_objeto'] == 0 ){
		            $arrBoxes[$i]['mostra'] = false;
		            foreach( $arrPosisao as $pos ){
		                if( $boxAtual->tx_box_inicio == $pos ){
		                    $arrBoxes[$i]['mostra'] = true;
		                }
		            }
		            $arrBoxes[$i]['titulo'] = $boxAtual->tx_titulo_box_inicio;
		            $arrBoxes[$i]['id']     = $boxAtual->tx_box_inicio;
		            
		        $tipo = '';
//                $tam = strlen($boxAtual->st_tipo_box_inicio);
//                for($i=0;$i<$tam;$i++){
//                    if( isset($boxAtual->st_tipo_box_inicio[$i]) ){
//                        $e     = ( !isset($boxAtual->st_tipo_box_inicio[$i+1]) )?' e ':',  ';
//                        $tipo .= $e . $this->comboTipoBox[ $boxAtual->st_tipo_box_inicio[$i] ];
//                    }
//                }
//                $tipo = substr($tipo,2);
		            
		            $arrBoxes[$i]['tipo']   = $tipo;
	                $i++;
	            }
	        }
        }
        $this->view->arrBoxes = $arrBoxes;
	}	

	public function gravaPosisaoAction()
	{
	    $arrUpdatePosicaoBoxInicio['tx_posicao_box_inicio'] = $this->_request->getPost('tx_posicao_box_inicio');
	    $arrUpdatePosicaoBoxInicio['cd_profissional']       = $this->arrProfissionalObjeto['cd_profissional'];
        $arrUpdatePosicaoBoxInicio['cd_objeto']             = $this->arrProfissionalObjeto['cd_objeto'];

		$update =  $this->objProfissionalObjetoContrato->updatePosicaoBoxInicio( $arrUpdatePosicaoBoxInicio );
        var_dump($update);
        if( $update ){
            die($update);
        }
	    $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }
    
	public function boxMapaSiteAction()
    {
		$this->_helper->layout->disableLayout();

        $menu  = new Menu($this->_request->getControllerName());
        $this->view->dataMenu = $menu->getMenuProfissional( $this->arrProfissionalObjeto['cd_profissional'] );
//        $this->view->dataMenu = $menu->fetchAll();
        $this->view->titulo_box_mapa_site = $this->objBoxInicio->getTitulo('box-mapa-site');
    }
    
    public function boxExecucaoContratoAction()
    {
		$this->_helper->layout->disableLayout();

		//recebe os parametros quando o usuário logado tem visão para todos os contratos
		$arrParams = $this->_request->getPost();

		include_once 'grafico/SaldoHorasContrato.php';
		$objBusiness = new Grafico_SaldoHorasContrato();

		$cd_contrato = ( !array_key_exists('cd_contrato', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_contrato'] : $arrParams['cd_contrato'];

        $tx_sigla_metrica_padrao_contrato = $this->_getSiglaMetricaPadraoContrato($cd_contrato);

		$params = array();
		$params['cd_contrato'] = $cd_contrato;

		$rsDadosContrato = $objBusiness->getDadosContrato( $params );

        foreach($rsDadosContrato as $key=>$value){
            $rsDadosContrato[$key]['dt_inicio'] = date('m/Y', strtotime($value['dt_inicio']));
            $rsDadosContrato[$key]['dt_final' ] = date('m/Y', strtotime($value['dt_final']));
        }
		
		$mesInicial	 = $rsDadosContrato[0]["ni_mes_inicial_contrato"];
		$anoInicial	 = $rsDadosContrato[0]["ni_ano_inicial_contrato"];
		$mes_fatia	 = (int)$mesInicial;
		$ano_fatia	 = (int)$anoInicial;
		$qtdMes		 = 0;
		$mes_hoje	 = (int)date('m');
		$ano_hoje	 = (int)date('Y');

		
	/*	while (($mes_fatia <= $mes_hoje)||($ano_fatia < $ano_hoje)){
			$qtdMes++;
			$mes_fatia++;
			if( $mes_fatia>12 ){
					$mes_fatia = 1;
					$ano_fatia++;
			}
		}

		 if ($qtdMes > $rsDadosContrato[0]["ni_qtd_meses_contrato"]) {
			$qtdMes = $rsDadosContrato[0]["ni_qtd_meses_contrato"];
		 }
    */
//		$qtdMes = $rsDadosContrato[0]["ni_qtd_meses_contrato"];

		if( $ano_hoje > $anoInicial ){
		        $mes_fatia = 12 + $mes_hoje;				
		} else
		{
		        $mes_fatia = $mes_hoje;				
		}

		$qtdMes = $mes_fatia - $mesInicial + 1;
		$total = 0;
		$mes = (int) $mesInicial;
		$ano = (int) $anoInicial;
		$horasPrevistas = $rsDadosContrato[0]['ni_horas_previstas'];
		
		
		if (!is_null($horasPrevistas) ){
			for( $i=0;$i<$qtdMes;$i++ ){
				$params['ano'] = $ano;
				$params['mes'] = $mes;

				$rsDadosRealizadoParcela = $objBusiness->getDadosRealizadoParcela( $params );
                if (count($rsDadosRealizadoParcela) > 0){	
				  $wikidata['Realizado'][ $objBusiness->arrMes[$mes].' '.$ano ] = (!empty($rsDadosRealizadoParcela))?$rsDadosRealizadoParcela[0]['horas']:0;
				  $total += (!empty($rsDadosRealizadoParcela))?$rsDadosRealizadoParcela[0]['horas']:0;
				} else {
				  $wikidata['Realizado'][ $objBusiness->arrMes[$mes].' '.$ano ] = 0;
				  $total += 0;
				}
				
				// incrementa o mes e se for maior que 12 reinicia o mes e incrementa o ano.
				$mes++;
				if( $mes>12 ){
					$mes = 1;
					$ano++;
				}
			}
			$wikidata['Realizado']['Falta']= $horasPrevistas - $total;
		}else{
			$wikidata = array ('Realizado'=>array());
		}

		if(count($wikidata['Realizado']) > 0){

			$graph = new ezcGraphPieChart();
			$graph->options->font->maxFontSize = 12;

			$graph->options->label = ' %3$.1f %% (%2$d)';

			$graph->data['Contrato'] = new ezcGraphArrayDataSet($wikidata['Realizado']);

			$graph->data['Contrato']->highlight['Falta'] = true;

			$graph->renderer = new ezcGraphRenderer3d();

			$graph->renderer->options->moveOut = .2;
			$graph->renderer->options->pieChartOffset = 63;
			$graph->renderer->options->pieChartGleam = .2;
			$graph->renderer->options->pieChartGleamColor = '#FFFFFF';
			$graph->renderer->options->pieChartGleamBorder = 1;

			$graph->renderer->options->pieChartShadowSize = 5;
			$graph->renderer->options->pieChartShadowColor = '#BABDB6';

			$graph->renderer->options->pieChartSymbolColor = '#55575388';

			$graph->background->background = '#FBFBFB';

			$caminho = SYSTEM_PATH_ABSOLUTE . "/public/img/svg/";
			$url     = SYSTEM_PATH . "/public/img/svg/";

			$graph->render( 510, 220, "{$caminho}box_execucao_contrato.svg" );

			// variáveis para view
			$this->view->imagem_box_execucao_contrato = "{$url}box_execucao_contrato.svg";
			$this->view->horas_restante_box_execucao_contrato  = round($wikidata['Realizado']['Falta'],1);

			$result_qtd_mes = ($rsDadosContrato[0]["ni_qtd_meses_contrato"] - ($qtdMes - 1));

			if ($result_qtd_mes > 0) {
				$horas_executar = $wikidata['Realizado']['Falta']/$result_qtd_mes;
				$this->view->horas_mes_box_execucao_contrato = round($horas_executar,1);
			} else {
				$this->view->horas_mes_box_execucao_contrato = 0;
			}

			$params['ano'] = date('Y');
			$params['mes'] = date('m');

			$rsDadosPrevisaoParcela  = $objBusiness->getDadosPrevisaoParcela( $params );

			$this->view->horas_previstas_box_execucao_contrato = (!empty($rsDadosPrevisaoParcela))?round($rsDadosPrevisaoParcela[0]['horas'],1):0;
		}
		$this->view->tx_sigla_metrica_padrao      = $tx_sigla_metrica_padrao_contrato;
		$this->view->qde_box_saldo_horas_contrato = $horasPrevistas;
		$this->view->titulo_box_execucao_contrato = $this->objBoxInicio->getTitulo('box-execucao-contrato');
    }
    
    public function boxSaldoHorasContratoAction()
    {
		$this->_helper->layout->disableLayout();

		//recebe os parametros quando o usuário logado tem visão para todos os contratos
		$arrParams = $this->_request->getPost();

         $wikidata = array(); 

        include_once 'grafico/SaldoHorasContrato.php';
        $objBusiness = new Grafico_SaldoHorasContrato();

        //TODO Melhorar o processo de box para os objetos e contratos definidos com '0'
        $cd_contrato = ( !array_key_exists('cd_contrato', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_contrato'] : $arrParams['cd_contrato'];

        $tx_sigla_metrica_padrao_contrato = $this->_getSiglaMetricaPadraoContrato($cd_contrato);

        $params = array();
        $params['cd_contrato'] = $cd_contrato;
        
        $rsDadosContrato = $objBusiness->getDadosContrato( $params );

        foreach($rsDadosContrato as $key=>$value){
            $rsDadosContrato[$key]['dt_inicio'] = date('m/Y', strtotime($value['dt_inicio']));
            $rsDadosContrato[$key]['dt_final' ] = date('m/Y', strtotime($value['dt_final']));
        }

        $mesInicial = $rsDadosContrato[0]["ni_mes_inicial_contrato"];
        $anoInicial = $rsDadosContrato[0]["ni_ano_inicial_contrato"];
        $qtdMes     = $rsDadosContrato[0]["ni_qtd_meses_contrato"];
        

		
        $qtdMes = (int) $qtdMes;
        $mes = (int) $mesInicial;
        $ano = (int) $anoInicial;
		
//$mes = $mes + 1;

        $horasPrevistas = $rsDadosContrato[0]['ni_horas_previstas'];
		
		if (!is_null($horasPrevistas) ){
			for( $i=1;$i<$qtdMes;$i++ ){
	            $params['ano'] = $ano;
	            $params['mes'] = $mes; 
	            
	            $rsDadosPrevisaoParcela  = $objBusiness->getDadosPrevisaoParcela( $params );
	            $rsDadosRealizadoParcela = $objBusiness->getDadosRealizadoParcela( $params );

	            $wikidata['Previsto'  ][ $objBusiness->arrMes[$mes].' '.$ano ] = (!empty($rsDadosPrevisaoParcela))?$rsDadosPrevisaoParcela[0]['horas']:0.0;
                $wikidata['Contratado'][ $objBusiness->arrMes[$mes].' '.$ano ] = (int) $horasPrevistas/$qtdMes;
	            $wikidata['Realizado' ][ $objBusiness->arrMes[$mes].' '.$ano ] = (!empty($rsDadosRealizadoParcela))?$rsDadosRealizadoParcela[0]['horas']:0.0;
	            
	            // incrementa o mes e se for maior que 12 reinicia o mes e incrementa o ano.
	            $mes++;
	            if( $mes>12 ){
	                $mes = 1;
	                $ano++;
	            }
	        }
        }else{
			$wikidata = array ('Previsto'=>array(' ' =>0 ),'Contratado'=>array(' ' =>0), 'Realizado'=>array(' ' =>0));
		}

        $graph = new ezcGraphBarChart();
		
  
        // Add data
        foreach ( $wikidata as $language => $data )
        {
			$graph->data[$language] = new ezcGraphArrayDataSet( $data );
        }
    
		
        $graph->data['Contratado']->displayType = ezcGraph::LINE;
        $graph->data->background = '#FBFBFB';
         
        $graph->options->fillLines = 210;
        $graph->background->background = '#FBFBFB';
        
        $caminho = SYSTEM_PATH_ABSOLUTE . "/public/img/svg/";
        $url     = SYSTEM_PATH . "/public/img/svg/";
        
        $graph->renderer = new ezcGraphRenderer3d();
        
        $graph->renderer->options->legendSymbolGleam = .5;
        $graph->renderer->options->barChartGleam = .5;
        
        
        $graph->render( 520, 220, "{$caminho}box_saldo_horas_contrato.svg" );
         
        // variáveis para view
        $this->view->imagem_box_saldo_horas_contrato = "{$url}box_saldo_horas_contrato.svg";
        $this->view->titulo_box_saldo_horas_contrato = $this->objBoxInicio->getTitulo('box-saldo-horas-contrato');
        $this->view->qde_box_saldo_horas_contrato    = $horasPrevistas;
        $this->view->tx_sigla_metrica_padrao         = $tx_sigla_metrica_padrao_contrato;
    }
    
	public function boxSolicitacoesAction()
    {
		$this->_helper->layout->disableLayout();

		//recebe os parametros quando o usuário logado tem visão para todos os contratos
		$arrParams = $this->_request->getPost();
        $cd_objeto = ( !array_key_exists('cd_objeto', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_objeto'] : $arrParams['cd_objeto'];

        $ano       = date("Y");
    	
  		$rowLeitura								= $this->objSolicitacoes->getQtdSolicitacoes($cd_objeto,'L',$ano);
  		$this->view->solicitacoes_para_leitura	= $rowLeitura->count;
        $rowExecucao							= $this->objSolicitacoes->getQtdSolicitacoes($cd_objeto,'E',$ano);
        $this->view->solicitacoes_em_execucao	= $rowExecucao->count;
        $rowConcluidas							= $this->objSolicitacoes->getQtdSolicitacoes($cd_objeto,'C',$ano);
        $this->view->solicitacoes_concluidas	= $rowConcluidas->count;
    	$this->view->titulo_box_solicitacoes	= $this->objBoxInicio->getTitulo('box-solicitacoes');
    } 

    public function boxFechamentoExtratoAction()
    {
		$this->_helper->layout->disableLayout();

        
        include_once 'grafico/SaldoHorasContrato.php';
        $objBusiness = new Grafico_SaldoHorasContrato();
        
        $parcela = new Parcela();

		//recebe os parametros quando o usuário logado tem visão para todos os contratos
		$arrParams = $this->_request->getPost();
        $cd_objeto = ( !array_key_exists('cd_objeto', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_objeto'] : $arrParams['cd_objeto'];
        $cd_contrato = ( !array_key_exists('cd_contrato', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_contrato'] : $arrParams['cd_contrato'];

        $ano       = date("Y");
        $mes       = date("m");
        $params = array();
        $params['cd_contrato'] = $cd_contrato;
        $params['ano'] = $ano;
        $params['mes'] = $mes; 

        
  		$rowPrevisto							= $objBusiness->getHorasPrevisaoParcelaMes( $params );
        $rowEncerrar     						= $objBusiness->getDadosEncerramentoProposta( $params );
        $rowExtrato                             =  $objBusiness->getDadosPrevisaoParcela( $params ) ;
        if (!empty($rowExtrato)) {
            $rowExtrato[0]['horas']             =  $rowExtrato[0]['horas'] - $rowEncerrar[0]['horas'];
        }
        $this->view->previsto               	= (!empty($rowPrevisto))?$rowPrevisto:0;
        $this->view->encerrar               	= (!empty($rowEncerrar[0]['horas']))?$rowEncerrar[0]['horas']:0;
        $this->view->extrato                	= (!empty($rowValorExtrato[0]['horas']))?$rowValorExtrato[0]['horas']:0;
    	$this->view->titulo_box_fechamento_extrato = $this->objBoxInicio->getTitulo('box-fechamento-extrato').' '.$params['mes'].'/'.$params['ano'];
    } 

    public function boxQtdSolicitacaoDemandaAction()
    {
		$this->_helper->layout->disableLayout();

		//recebe os parametros quando o usuário logado tem visão para todos os contratos
		$arrParams = $this->_request->getPost();
		
         $wikidata = array();

        include_once 'grafico/SaldoHorasContrato.php';
		$objBusiness = new Grafico_SaldoHorasContrato();

		//TODO Melhorar o processo de box para os objetos e contratos definidos com '0'
        $cd_contrato = ( !array_key_exists('cd_contrato', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_contrato'] : $arrParams['cd_contrato'];
		$cd_objeto   = ( !array_key_exists('cd_objeto', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_objeto'] : $arrParams['cd_objeto'];
	

        $params = array();
        $params['cd_contrato'] = $cd_contrato;
		$params['cd_objeto'] = $cd_objeto;

        $params['mes'] = $mes;

        $rsDadosSolicitacaoDemanda  = $objBusiness->getDadosSolicitacaoDemanda( $params );
		$cont = count($rsDadosSolicitacaoDemanda);
		$ano = date("Y");
		
		if($cont != 0){
			for( $i=0;$i<$cont;$i++ ){
				
	            

		        $wikidata['Solicitacao'][$rsDadosSolicitacaoDemanda[$i][mes].'/'.$ano ] = $rsDadosSolicitacaoDemanda[$i]['qtd_sol'];
				$wikidata['Demanda' ][ $rsDadosSolicitacaoDemanda[$i][mes].'/'.$ano ] =$rsDadosSolicitacaoDemanda[$i]['qtd_dem'];

	            }
		}else{

			$wikidata = array ('Solicitacao'=>array(' ' =>0 ),'Demanda'=>array(' ' =>0));
		}

        $graph = new ezcGraphBarChart();


        // Add data
        foreach ( $wikidata as $language => $data )
        {
			$graph->data[$language] = new ezcGraphArrayDataSet( $data );
        }


        $graph->data->background = '#FBFBFB';

        $graph->options->fillLines = 210;
        $graph->background->background = '#FBFBFB';

        $caminho = SYSTEM_PATH_ABSOLUTE . "/public/img/svg/";
        $url     = SYSTEM_PATH . "/public/img/svg/";

        $graph->renderer = new ezcGraphRenderer3d();

        $graph->renderer->options->legendSymbolGleam = .5;
        $graph->renderer->options->barChartGleam = .5;


        $graph->render( 520, 220, "{$caminho}box-qtd-solicitacao-demanda.svg" );

        // variáveis para view
        $this->view->imagem_box_qtd_solicitacao_demanda = "{$url}box-qtd-solicitacao-demanda.svg";
        $this->view->titulo_box_qtd_solicitacao_demanda = $this->objBoxInicio->getTitulo('box-qtd-solicitacao-demanda');
//        $this->view->qde_box_saldo_horas_contrato    = $horasPrevistas;
//		$this->view->tx_sigla_metrica_padrao         = $tx_sigla_metrica_padrao_contrato;
    }


	public function boxExtratoGeralContratoAction()
    {
		$this->_helper->layout->disableLayout();

		//recebe os parametros quando o usuário logado tem visão para todos os contratos
		$arrParams = $this->_request->getPost();

        $cd_objeto		  = ( !array_key_exists('cd_objeto', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_objeto'] : $arrParams['cd_objeto'];

//    	$cd_objeto        = ($_SESSION["oasis_logged"][0]["cd_objeto"] != 0)?$_SESSION["oasis_logged"][0]["cd_objeto"]:3;

		$arrDadosObjeto   = $this->objObjetoContrato->find($cd_objeto)->current()->toArray();
		$arrDadosContrato = $this->objContrato->find($arrDadosObjeto["cd_contrato"])->current()->toArray();

        $tx_sigla_metrica_padrao_contrato = $this->_getSiglaMetricaPadraoContrato($arrDadosObjeto["cd_contrato"]);

		$mes_corrente       = date("m");
		$ano_corrente       = date("Y");
		$mes_inicial        = $arrDadosContrato["ni_mes_inicial_contrato"];
		$mes_final          = $arrDadosContrato["ni_mes_final_contrato"];
		$ano_inicial        = $arrDadosContrato["ni_ano_inicial_contrato"];
		$ano_final          = $arrDadosContrato["ni_ano_final_contrato"];
		$ni_horas_previstas = $arrDadosContrato["ni_horas_previstas"];
		$cd_contrato        = $arrDadosObjeto["cd_contrato"];
		
		$valorExecutado             = $this->objContrato->getSomaHorasExecutadasContrato($mes_inicial, $mes_final, $ano_inicial, $ano_final,$cd_contrato);
		$valorAlocado               = $this->objContrato->somaHorasAlocadasContrato($arrDadosContrato["cd_contrato"]);
		$this->view->valorExecutado = ( !is_null($valorExecutado["ni_horas_parcela"]) ) ? $valorExecutado["ni_horas_parcela"] : 0;
		$this->view->valorAlocado   = ( !is_null($valorAlocado["total_alocado"	   ]) )	? $valorAlocado["total_alocado"		] : 0;
		
		$objDatediff     = new Util_Datediff("{$ano_corrente}-{$mes_corrente}-01 00:00:00", "{$ano_final}-".substr("00".$mes_final,-2)."-01 00:00:00", "m");
		$meses_restantes = ($mes_corrente == $mes_final && $ano_corrente == $ano_final) ? $objDatediff->datediff()+1 : $objDatediff->datediff();
		
		$mes_loop = $mes_corrente;
		$ano_loop = $ano_corrente;
		
		$totalValorParcelaMesesRestantes           = 0;
		$totalValorParcelaMesesRestantesNaoAlocado = 0;
		$totalValorParcelaMesesRestantesAlocado    = 0;
		
		for ($i=1;$i<=$meses_restantes;$i++)
		{
			$arrValorParcelaMesesRestantes               = $this->objContrato->somaHorasMesRestante($mes_loop, $ano_loop,$cd_contrato);
			$valorParcelaMesesRestantes                  = (!is_null($arrValorParcelaMesesRestantes["ni_horas_parcela"]))?$arrValorParcelaMesesRestantes["ni_horas_parcela"]:0;
			$totalValorParcelaMesesRestantes            += $valorParcelaMesesRestantes;
			
			$arrValorParcelaMesesRestantesNaoAlocado     = $this->objContrato->somaHorasMesRestanteNaoAlocado($mes_loop, $ano_loop,$cd_contrato);
			$valorParcelaMesesRestantesNaoAlocado        = (!is_null($arrValorParcelaMesesRestantesNaoAlocado["ni_horas_parcela"]))?$arrValorParcelaMesesRestantesNaoAlocado["ni_horas_parcela"]:0;
			$totalValorParcelaMesesRestantesNaoAlocado  += $valorParcelaMesesRestantesNaoAlocado;
			
			$arrValorParcelaMesesRestantesAlocado        = $this->objContrato->somaHorasMesRestanteAlocado($mes_loop, $ano_loop,$cd_contrato);
			$valorParcelaMesesRestantesAlocado           = (!is_null($arrValorParcelaMesesRestantesAlocado["ni_horas_parcela"]))?$arrValorParcelaMesesRestantesAlocado["ni_horas_parcela"]:0;
			$totalValorParcelaMesesRestantesAlocado     += $valorParcelaMesesRestantesAlocado;
			
			$mes_loop = $mes_loop + 1;
			if ($mes_loop == 13){
				$mes_loop = 1;
				$ano_loop = $ano_loop + 1;
			}
		}

		$arrValorDiferencaAlocacaoAlteracaoProposta = $this->objContrato->diferencaAlocacaoAlteracaoProposta($arrDadosObjeto["cd_contrato"], $mes_inicial, $ano_inicial, $mes_final, $ano_final);
		$valorDiferencaAlocacaoAlteracaoProposta    = (!is_null($arrValorDiferencaAlocacaoAlteracaoProposta["diferenca"]))?$arrValorDiferencaAlocacaoAlteracaoProposta["diferenca"]:0;
		
  		$this->view->ni_horas_previstas                        = $ni_horas_previstas; 
  		$this->view->totalValorParcelaMesesRestantes           = $totalValorParcelaMesesRestantes; 
        $this->view->totalValorParcelaMesesRestantesNaoAlocado = $totalValorParcelaMesesRestantesNaoAlocado; 
        $this->view->totalValorParcelaMesesRestantesAlocado    = $totalValorParcelaMesesRestantesAlocado; 
        $this->view->valorDiferencaAlocacaoAlteracaoProposta   = $valorDiferencaAlocacaoAlteracaoProposta; 
    	$this->view->titulo_box_extrato_geral_contrato		   = $this->objBoxInicio->getTitulo('box-extrato-geral-contrato');
        $this->view->tx_sigla_metrica_padrao                   = $tx_sigla_metrica_padrao_contrato;
    }   
      
	public function boxTotalPropostasNovasEvolutivasAction()
    {
		$this->_helper->layout->disableLayout();

		//recebe os parametros quando o usuário logado tem visão para todos os contratos
		$arrParams = $this->_request->getPost();

        $cd_objeto		  = ( !array_key_exists('cd_objeto', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_objeto'] : $arrParams['cd_objeto'];

//    	$cd_objeto        = ($_SESSION["oasis_logged"][0]["cd_objeto"] != 0)?$_SESSION["oasis_logged"][0]["cd_objeto"]:3;
    	
		$arrDadosObjeto   = $this->objObjetoContrato->find($cd_objeto)->current()->toArray();
		$cd_contrato      = $arrDadosObjeto["cd_contrato"];
		
		$totalPropostasNovas      = $this->objControle->getTotalPropostasNovasEvolutivas($cd_contrato, 'N');
		$totalPropostasEvolutivas = $this->objControle->getTotalPropostasNovasEvolutivas($cd_contrato, 'E');
		
		$totalPropostasNovas      = ( !is_null($totalPropostasNovas->qtde_propostas)		) ? $totalPropostasNovas->qtde_propostas      : 0;
		$totalPropostasEvolutivas = ( !is_null($totalPropostasEvolutivas->qtde_propostas)	) ? $totalPropostasEvolutivas->qtde_propostas : 0;
		
//		$arrDados = array('Propostas Novas' => $totalPropostasNovas, 'Propostas Evolutivas' => $totalPropostasEvolutivas);
//		$arrDados = array('Propostas' => array('Propostas Novas' => $totalPropostasNovas, 'Propostas Evolutivas' => $totalPropostasEvolutivas));
		$arrDados = array('Propostas' => array('Novas' => $totalPropostasNovas, 'Evolutivas' => $totalPropostasEvolutivas));

//		if ($arrDados["Propostas"]["Propostas Novas"] > 0 && $arrDados["Propostas"]["Propostas Evolutivas"] > 0)
		if ($arrDados["Propostas"]["Novas"] > 0 && $arrDados["Propostas"]["Evolutivas"] > 0)
		{
			$graph = new ezcGraphPieChart();

			$graph->options->font->maxFontSize = 12;

			$graph->options->label = ' %3$.1f %% (%2$d)';
			
			$graph->legend->position = ezcGraph::BOTTOM;
//			$graph->legend->title = 'Propostas';
			

			$graph->data['Proposta'] = new ezcGraphArrayDataSet($arrDados['Propostas']);

			$graph->renderer = new ezcGraphRenderer3d();

			$graph->renderer->options->moveOut				= .1;
			$graph->renderer->options->pieChartOffset		= 63;
			$graph->renderer->options->pieChartGleam		= .2;
			$graph->renderer->options->pieChartGleamColor	= '#FFFFFF';
			$graph->renderer->options->pieChartGleamBorder	= 1;

			$graph->renderer->options->pieChartShadowSize	= 5;
			$graph->renderer->options->pieChartShadowColor	= '#BABDB6';

			$graph->renderer->options->pieChartSymbolColor	= '#55575388';

			$graph->background->background = '#FBFBFB';

			$caminho = SYSTEM_PATH_ABSOLUTE . "/public/img/svg/";
			$url     = SYSTEM_PATH . "/public/img/svg/";

            
		//	$graph->render( 515, 220, "{$caminho}box_total_propostas_novas_evolutivas.svg" );
			$graph->render( 240, 220, "{$caminho}box_total_propostas_novas_evolutivas.svg" );

			// variáveis para view
			$this->view->imagem_box_total_propostas_novas_evolutivas = "{$url}box_total_propostas_novas_evolutivas.svg";

			$this->view->propostas_novas      = $arrDados["Propostas"]["Novas"];
			$this->view->propostas_evolutivas = $arrDados["Propostas"]["Evolutivas"];
		}
		
		$this->view->titulo_box_total_propostas_novas_evolutivas = $this->objBoxInicio->getTitulo('box-total-propostas-novas-evolutivas');
    }

	public function boxDemandaPorTecnicoAction()
	{
		$this->_helper->layout->disableLayout();

		$this->view->titulo_box_demanda_por_tecnico = $this->objBoxInicio->getTitulo('box-demanda-por-tecnico');

		//recebe os parametros quando o usuário logado tem visão para todos os contratos
		$arrParams = $this->_request->getPost();

		$cd_objeto			 = ( !array_key_exists('cd_objeto', $arrParams)) ? $_SESSION['oasis_logged'][0]['cd_objeto'] : $arrParams['cd_objeto'];

		$mesAno				 = date('m/Y');
		$this->view->periodo = $this->objUtil->getMesRes(date('n'))."/".date('Y');
		$this->view->res	 = $this->objDemandaProfissional->getDemandaPorProfissional($cd_objeto, $mesAno);
	}

    private function _getSiglaMetricaPadraoContrato($cd_contrato)
    {
        $objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
        $arrDadosMetricaPadrao       = $objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($cd_contrato);
        return $arrDadosMetricaPadrao[0]['tx_sigla_metrica'];
    }
}