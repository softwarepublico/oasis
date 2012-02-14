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

class OcorrenciaAdministrativaController extends Base_Controller_Action
{
	private $ocorrenciaAdministrativa;
	private $contratoEvento;
	private $contrato;
		
	public function init()
	{
		parent::init();
		$this->ocorrenciaAdministrativa = new OcorrenciaAdministrativa($this->_request->getControllerName());
		$this->contratoEvento           = new ContratoEvento($this->_request->getControllerName());
		$this->contrato                 = new Contrato($this->_request->getControllerName());
	}

	public  function indexAction()
	{
		$mes = date('n');
		$ano = date('Y');

		$this->view->mesAno = $this->_helper->util->getMes($mes)."/".$ano;
	}

	public function pesquisaEventoForaContrato($cd_contrato)
	{
		$res = $this->contratoEvento->pesquisaEventoForaContrato($cd_contrato);

		$strOptions = "";

		foreach ($res as $evento) {
			$strOptions .= "<option value=\"{$evento['cd_evento']}\">{$evento['tx_evento']}</option>";
		}

		return $strOptions;

	}	

	public function pesquisaEventoNoContrato($cd_contrato, $comSelecione = false)
	{
		$res = $this->contratoEvento->pesquisaEventoNoContrato($cd_contrato);

		if ($comSelecione === true){
			$strOptions = "<option value=\"\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		} else {
			$strOptions = "";
		}

		foreach ($res as $evento) {
			$strOptions .= "<option value=\"{$evento['cd_evento']}\">{$evento['tx_evento']}</option>";
		}
		return $strOptions;
	}		
	
	public function pesquisaEventosAssociadosAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato = (int)$this->_request->getParam('cd_contrato');

		$arrEventoNoContrato = $this->pesquisaEventoNoContrato($cd_contrato, true);

		$arrEventos = array($arrEventoNoContrato);
		
		echo Zend_Json_Encoder::encode($arrEventos);
	}	

	public function pesquisaEventosAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato = (int)$this->_request->getParam('cd_contrato');

		$arrEventoForaContrato = $this->pesquisaEventoForaContrato($cd_contrato);
		$arrEventoNoContrato   = $this->pesquisaEventoNoContrato($cd_contrato);

		$arrEventos = array($arrEventoForaContrato, $arrEventoNoContrato);
		
		echo Zend_Json_Encoder::encode($arrEventos);
	}
	
	public function associaEventoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$post = $this->_request->getPost();
		
		$cd_contrato = $post['cd_contrato'];
		$eventos = Zend_Json_Decoder::decode($post['eventos']);
		
		foreach($eventos as $evento)
		{
			$row = $this->contratoEvento->createRow();
			$row->cd_contrato = $cd_contrato;
			$row->cd_evento   = $evento;
			$row->save();
		}
	}
	
	public function desassociaEventoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$post = $this->_request->getPost();
		
		$cd_contrato = $post['cd_contrato'];
		$eventos = Zend_Json_Decoder::decode($post['eventos']);
		
		foreach($eventos as $evento)
		{
			$where = "cd_contrato = {$cd_contrato} and cd_evento = {$evento}";
			$this->contratoEvento->delete($where);
		}
	}
	
	public function salvarOcorrenciaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

        $arrDados['dt_ocorrencia_administrativa'] = Base_Util::converterDatetime($arrDados['dt_ocorrencia_administrativa'], 'DD/MM/YYYY', 'YYYY-MM-DD');
        
		$retorno = $this->ocorrenciaAdministrativa->salvarOcorrenciaAdministrativa($arrDados);
		
		if ($retorno){
			$msg = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		}else{
			$msg = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
		echo $msg;
	}
	
	public function alterarOcorrenciaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		$retorno = $this->ocorrenciaAdministrativa->alterarOcorrenciaAdministrativa($arrDados);
		
		if ($retorno){
			$msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		}else{
			$msg = Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}
		
		echo $msg;
	}	
	
	public function excluirOcorrenciaAction ()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		$retorno = $this->ocorrenciaAdministrativa->excluirOcorrenciaAdministrativa($arrDados);
		if ($retorno){
			$msg = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else{
			$msg = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
		echo $msg;
	}
	
	public function gridOcorrenciaAdministrativaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes         = $this->_request->getParam('mes');
		$ano         = $this->_request->getParam('ano');
		$cd_contrato = $this->_request->getParam('cd_contrato');

		$mes         = ($mes == 0)?date('m'):$mes;
		$ano         = ($ano == 0)?date('Y'):$ano;
		$cd_contrato = ($cd_contrato == 0)?null:$cd_contrato;

		$mesAno = $this->_helper->util->getMes($mes);
		$mesAno = $mesAno."/".$ano;

		$mes = substr("00".$mes,-2);

		$this->view->res    = $this->ocorrenciaAdministrativa->getListaOcorrenciaAdministrativa($mes, $ano, $cd_contrato);
		$this->view->mesAno = $mesAno;
	}
	
	public function recuperaOcorrenciaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$dt_ocorrencia_administrativa = $this->_request->getParam('dt_ocorrencia_administrativa');
		$cd_evento                    = $this->_request->getParam('cd_evento');
		$cd_contrato                  = $this->_request->getParam('cd_contrato');

		$arrOcorrenciaAdministrativa  = $this->ocorrenciaAdministrativa->getDadosOcorrenciaAdministrativa($dt_ocorrencia_administrativa, $cd_evento, $cd_contrato);
        $arrOcorrenciaAdministrativa['dt_ocorrencia_administrativa'] = Base_Util::converterDatetime($arrOcorrenciaAdministrativa['dt_ocorrencia_administrativa'], 'YYYY-MM-DD', 'DD/MM/YYYY');

		echo Zend_Json_Encoder::encode($arrOcorrenciaAdministrativa);
	}
}