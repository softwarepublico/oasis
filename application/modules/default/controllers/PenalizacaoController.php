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

class PenalizacaoController extends Base_Controller_Action 
{
	private $objPenalizacao;
	private $objContratos;
	private $objPenalidade;
	private $codContrato;
	private $dtPenalizacao;
	
	public function init()
	{
		parent::init();
		$this->objPenalizacao = new Penalizacao($this->_request->getControllerName());
		$this->objContratos   = new ObjetoContrato($this->_request->getControllerName());
		$this->objPenalidade  = new Penalidade($this->_request->getControllerName());
		$this->contrato       = new Contrato($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
	}
	
	public function penalizacaoFormAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_objeto      = $this->_request->getParam('cd_objeto');
		$dt_penalizacao = $this->_request->getParam('dt_penalizacao');
		
		$arrObjetoContrato = $this->objContratos->getDadosObjetoContrato(null, $cd_objeto);
		
		$arrContrato       = $this->contrato->getDadosContrato($arrObjetoContrato[0]['cd_contrato']);
		$cd_contrato       = $arrContrato[0]['cd_contrato']; 
		
		$arrPenalidade = $this->objPenalidade->getDadosPenalidade($cd_contrato);
		
		if(count($arrPenalidade) > 0){
			foreach($arrPenalidade as $i=>$conteudo){
				$arrPenalidade[$i]['ni_qtd_ocorrencia']  = "";
				$arrPenalidade[$i]['tx_obs_penalizacao'] = "";
				$arrPenalizacao = $this->objPenalizacao->getDadosPenalizacao($dt_penalizacao, $cd_contrato,$conteudo['cd_penalidade']);
				if(count($arrPenalizacao) > 0){
					if(trim($conteudo['st_ocorrencia']) == "S"){
						$arrPenalidade[$i]['ni_qtd_ocorrencia'] = $arrPenalizacao[0]['ni_qtd_ocorrencia'];
					}
					$arrPenalidade[$i]['tx_obs_penalizacao'] = $arrPenalizacao[0]['tx_obs_penalizacao'];
					$arrPenalidade[$i]['dt_penalizacao']     = $arrPenalizacao[0]['dt_penalizacao'];
				}
			}
		}
		
		$this->view->arrPenalidade = $arrPenalidade; 
	}
	
	public function recuperaContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto = $this->_request->getParam("cd_objeto");


		$arrDados = $this->objContratos->getDadosObjetoContrato(null, $cd_objeto);

		$arrContrato = $this->contrato->getDadosContrato($arrDados[0]['cd_contrato']);
		$arrContrato = $arrContrato[0];
		
		echo Zend_Json_Encoder::encode($arrContrato);
	}
	
	public function trataDadosPenalizacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		if(count($arrPost) > 0){
            

			$this->dtPenalizacao = $arrPost['dt_penalizacao'];
			$this->codContrato   = $arrPost['cd_contrato'];
			$i        = 0;
            $arrDados = array();
			foreach($arrPost as $key=>$value){
				if(trim($value) != ""){
					$inicial = substr($key,0,2);
					if($inicial == "ni"){
						$arrDados[$i]["ni_qtd_ocorrencia"] = $value;
						continue;
					}
					if($inicial == "tx"){
						$arrDados[$i]["tx_obs_penalizacao"] = $value;
						$arrDados[$i]["cd_penalidade"] = substr($key,19);
					} 
					$i++;
				} else {
					unset($arrPost[$key]);
				}
			}
			$this->salvarDadosPenalizacao($arrDados);
		} else {
			echo Base_Util::getTranslator('L_MSG_ALERT_INFORME_INFRACAO');
		}
	}
	
	private function salvarDadosPenalizacao(array $arrDados)
	{
		if(count($arrDados)>0){
			foreach($arrDados as $key=>$value){
				$arrDados[$key]['cd_contrato'   ] = $this->codContrato;
				$arrDados[$key]['dt_penalizacao'] = $this->dtPenalizacao;

				$return = $this->objPenalizacao->verificaTransacaoPenalizacao($arrDados[$key]);
				if(!$return){ 
					$cd_penalidade = $value['cd_penalidade'];
					break; 
				}
			}
			$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
			echo $msg;
		} else {
			echo  Base_Util::getTranslator('L_MSG_ALERT_VARIFICA_INFORMAÇOES');
		}
	}
	
	public function excluirPenalizacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$dt_penalizacao = $this->_request->getParam('dt_penalizacao');
		$cd_contrato    = $this->_request->getParam('cd_contrato');
		$cd_penalidade  = $this->_request->getParam('cd_penalidade');
		
		$return = $this->objPenalizacao->excluirPenalizacao($dt_penalizacao,$cd_contrato,$cd_penalidade);
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function gridJustificativaAction()
	{
		$this->_helper->layout->disableLayout();
		
		$ano = $this->_request->getParam('ano');
		$mes = $this->_request->getParam('mes');
		
		$ano = ($ano != 0)?$ano:date('Y');
		$mes = ($mes != 0)?$mes:date('m');

		$mes = (count($mes) == 1)?"0{$mes}":$mes;
		
		$mesAno = "{$mes}/{$ano}";
		
		$arrDados = $this->objPenalizacao->getDadosPenalizacao(null,null,null,$mesAno,"pz.dt_penalizacao DESC");

		$this->view->res = $arrDados;		
	}

    public function registrarJustificativaAction()
    {
        $this->_helper->layout->disableLayout();
        
        $arrPost = $this->_request->getPost();
        $arrPenalidade = $this->objPenalizacao->getDadosPenalizacao(Base_Util::converterDate($arrPost['dt_penalizacao'], 'YYYY-MM-DD', 'DD/MM/YYYY'),$arrPost['cd_contrato'],$arrPost['cd_penalidade']);

        $button = true;
        if(!is_null($arrPenalidade[0]['dt_justificativa'])){
            $button = false;
        }

        $this->view->arrPenalidade   = $arrPenalidade[0];
        $this->view->dadosPenalidade = $arrPost;
        $this->view->buttonRegistrar = $button;
    }

    public function analiseJustificativaAction()
    {
        $this->_helper->layout->disableLayout();

        $arrPost = $this->_request->getPost();
        $arrPenalidade = $this->objPenalizacao->getDadosPenalizacao(Base_Util::converterDate($arrPost['dt_penalizacao'], 'YYYY-MM-DD', 'DD/MM/YYYY'),$arrPost['cd_contrato'],$arrPost['cd_penalidade']);

        $button = true;
        if(!is_null($arrPenalidade[0]['st_aceite_justificativa'])){
            $button = false;
        }
        $this->view->arrPenalidade   = $arrPenalidade[0];
        $this->view->dadosPenalidade = $arrPost;
        $this->view->button = $button;
    }

    public function salvarJustificativaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $arrDados = $this->_request->getPost();


		$arrDados['dt_penalizacao']   = Base_Util::converterDate($arrDados['dt_penalizacao'], 'YYYY-MM-DD','DD/MM/YYYY');
        $arrDados['dt_justificativa'] = Base_Util::converterDate($arrDados['dt_justificativa'], 'DD/MM/YYYY','YYYY-MM-DD');

        if($this->objPenalizacao->verificaTransacaoPenalizacao($arrDados)){
           echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
        } else {
           echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
        }
    }

    public function salvarAceiteJustificativaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $arrDados = $this->_request->getPost();
        $arrDados['dt_penalizacao']   = Base_Util::converterDate($arrDados['dt_penalizacao'], 'YYYY-MM-DD','DD/MM/YYYY');

        if($this->objPenalizacao->verificaTransacaoPenalizacao($arrDados)){
           echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
        } else {
           echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
        }
    }
}