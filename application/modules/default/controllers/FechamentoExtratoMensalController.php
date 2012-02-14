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

class FechamentoExtratoMensalController extends Base_Controller_Action
{
	private $contrato;
	private $parcela;
	private $extratoMensal;
	private $extratoMensalParcela;
	private $objContratoDefinicaoMetrica;

	public function init()
	{
		parent::init();
		
		$this->contrato                    = new Contrato($this->_request->getControllerName());
		$this->parcela                     = new Parcela($this->_request->getControllerName());
		$this->extratoMensal               = new ExtratoMensal($this->_request->getControllerName());
		$this->extratoMensalParcela        = new ExtratoMensalParcela($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica = new ContratoDefinicaoMetrica($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->view->headTitle(Base_Util::setTitle('L_TIT_FECHAMENTO_EXTRATO_MENSAL'));

		$arrContrato = $this->contrato->getContratoPorTipoDeObjeto(true, 'P');

		$this->view->listaContrato = $arrContrato;
	}

	public function gridFechamentoExtratoMensalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato = $this->_request->getParam('cd_contrato');

		$arrDadosContrato = $this->contrato->fetchRow(array('cd_contrato = ?'=>$cd_contrato))->toArray();
		
		$mes_inicial        = $arrDadosContrato['ni_mes_inicial_contrato'];
		$ano_inicial        = $arrDadosContrato['ni_ano_inicial_contrato'];
		$qtd_meses_contrato = $arrDadosContrato['ni_qtd_meses_contrato'];
		
		$mes = $mes_inicial;
		$ano = $ano_inicial;
		
		$arrDados = array();
		
		for ($i=1;$i<=$qtd_meses_contrato;$i++){
			 $horas_parcelas = 0;
			 $qtd_parcelas   = 0;

			 $st_encerramento_proposta    = 'S';
			 $arrParcelasHomologadasNoMes = $this->parcela->getParcelasHomologadasNoMes($mes, $ano, $cd_contrato);
			 
			 foreach ($arrParcelasHomologadasNoMes as $parcela){
			 	$cd_ultima_parcela = $this->parcela->getUltimaParcelaProposta($parcela['cd_projeto'], $parcela['cd_proposta']);
			 	
			 	if ($parcela['cd_parcela'] == $cd_ultima_parcela){
			 		if ($parcela['st_encerramento_proposta'] == "S"){
			 			$horas_parcelas += $parcela['ni_horas_parcela'];
			 			$qtd_parcelas   += 1;
			 		}else{
						//se tiver pelo menos uma proposta com ultima parcela sem ser encerrada,
						//o flag será setado
						$st_encerramento_proposta = 'N';
					}
			 	}else{
		 			$horas_parcelas += $parcela['ni_horas_parcela'];
		 			$qtd_parcelas   += 1;
			 	}
			 }
			 
			 $arrExtratoMensal = $this->extratoMensal->getExtratoMensal($cd_contrato, $mes, $ano);
			 
			 $mes_fechado = (!empty($arrExtratoMensal))? "S" : "N";
			 
			 $arrDados[$i] = array('mes' => $mes, 'ano' => $ano, 'mes_ano' => Base_Util::getMes($mes)."/".$ano, 'qtd_parcelas' => $qtd_parcelas, 'horas_parcelas' => $horas_parcelas, 'mes_fechado' => $mes_fechado, 'st_encerramento_proposta' => $st_encerramento_proposta);
			 
			 $mes = $mes + 1;
			 if ($mes == 13){
				$mes = 1;
				$ano = $ano + 1;
			 }
		}

		//Busca a métrica indicada como padrão para o contrato
		//e obtém a sigla da métrica
		$this->arrDadosMetricaPadrao	  = $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($arrDadosContrato['cd_contrato']);

		$this->view->unidadePadraoMetrica = ( count($this->arrDadosMetricaPadrao) > 0 ) ? $this->arrDadosMetricaPadrao[0]['tx_sigla_metrica'] : Base_Util::getTranslator('L_VIEW_UNIDADES_METRICA') ;
		$this->view->arrDados             = $arrDados;
		$this->view->cd_contrato          = $cd_contrato;

		echo $this->view->render('fechamento-extrato-mensal/grid-fechamento-extrato-mensal.phtml');
	}
	
	public function fecharExtratoMensalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato    = $this->_request->getParam('cd_contrato');
		$ni_mes_extrato = $this->_request->getParam('mes');
		$ni_ano_extrato = $this->_request->getParam('ano');
		$qtd_parcelas   = $this->_request->getParam('qtd_parcelas');
		$horas_parcelas = $this->_request->getParam('horas_parcelas');
		
		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;
		
		/*		
		 * Grava os dados resumidos do extrato mensal na tabela s_extrato_mensal, com a quantidade de parcelas
		homologadas e a soma de horas dessas parcelas
		*/
		$erros = $this->gravaExtratoMensal($cd_contrato, $ni_mes_extrato, $ni_ano_extrato, $qtd_parcelas, $horas_parcelas);
		
		if ($erros === false){
			/* Grava os dados detalhados de cada parcela homologada para cada mês e ano do contrato */
			$erros = $this->gravaExtratoMensalParcela($cd_contrato, $ni_mes_extrato, $ni_ano_extrato);
		}
		
		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_FECHAR_EXTRATO_MENSAL');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_EXTRATO_MENSAL');
		}		
	}
	
	public function gravaExtratoMensal($cd_contrato, $ni_mes_extrato, $ni_ano_extrato, $qtd_parcelas, $horas_parcelas)
	{
		$erros = false;
		
		$addRow = array();
		$addRow['cd_contrato']           = $cd_contrato;
		$addRow['ni_mes_extrato']        = $ni_mes_extrato;
		$addRow['ni_ano_extrato']        = $ni_ano_extrato;
		$addRow['ni_qtd_parcela']        = $qtd_parcelas;
		$addRow['ni_horas_extrato']      = $horas_parcelas;
		$addRow['dt_fechamento_extrato'] = date('Y-m-d H:i:s');
		
		if (!$this->extratoMensal->insert($addRow)){
			$erros = true;
		}
		
		return $erros;
	}
	
	public function gravaExtratoMensalParcela($cd_contrato, $ni_mes_extrato, $ni_ano_extrato)
	{
		$erros  = false;
		$addRow = array();
		
		$arrParcelasHomologadasNoMes = $this->parcela->getParcelasHomologadasNoMes($ni_mes_extrato, $ni_ano_extrato, $cd_contrato);
		
		foreach($arrParcelasHomologadasNoMes as $parcela)
		{
			if ($erros === false)
			{
				$addRow['cd_contrato']             = $cd_contrato;
				$addRow['ni_ano_extrato']          = $ni_ano_extrato;
				$addRow['ni_mes_extrato']          = $ni_mes_extrato;
				$addRow['cd_proposta']             = $parcela['cd_proposta'];
				$addRow['cd_projeto']              = $parcela['cd_projeto'];
				$addRow['cd_parcela']              = $parcela['cd_parcela'];
				$addRow['ni_hora_parcela_extrato'] = $parcela['ni_horas_parcela']; 
				
				if (!$this->extratoMensalParcela->insert($addRow))
				{
					$erros = true;
				}
			}
		}
		return $erros;
	}
}