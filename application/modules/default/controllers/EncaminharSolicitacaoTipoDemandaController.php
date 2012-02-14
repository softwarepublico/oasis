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

class EncaminharSolicitacaoTipoDemandaController extends Base_Controller_Action
{
	private $objSolicitacao;
	private $objProfissionalObjetoContrato;
	private $objNivelServico;
	private $objDemanda;
	private $objDemandaProfissional;
	private $objDemandaProfissionalNivelServico;
    private $objStatusAtendimento;
	
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_SOLICITACAO_SERVICO_TIPO_DEMANDA'));
        
		$this->objNivelServico                    = new NivelServico($this->_request->getControllerName());
		$this->objProfissionalObjetoContrato      = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$this->objSolicitacao                     = new Solicitacao($this->_request->getControllerName());
		$this->objDemanda                         = new Demanda($this->_request->getControllerName());
		$this->objDemandaProfissional             = new DemandaProfissional($this->_request->getControllerName());
		$this->objDemandaProfissionalNivelServico = new DemandaProfissionalNivelServico($this->_request->getControllerName());
        $this->objStatusAtendimento               = new StatusAtendimento($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		$ni_solicitacao     = (int)$this->_request->getParam('ni_solicitacao', 0);
		$ni_ano_solicitacao = (int)$this->_request->getParam('ni_ano_solicitacao', 0);
		$cd_demanda         = (int)$this->_request->getParam('cd_demanda');
		
		//Combo de Status de Atendimento
        $this->view->comboStatusAtendimento = $this->objStatusAtendimento->getComboStatusAtendimento(true);

		// Consulta Solicitacao
		$arrSolicitacao = $this->objSolicitacao->getSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		$arrSolicitacao['tx_solicitacao'] = str_ireplace('\"','"',$arrSolicitacao['tx_solicitacao']);
		
		$this->view->solicitacao = $arrSolicitacao;
		
		// Data e Hora da Demanda
		$this->view->dt_demanda = date("d/m/Y H:i:s");
		
		/*
		 * Verificar com a elis se e para cadastrar no banco de dados ou fazer um update deletando os caras já associados
		 */
		if (!empty($cd_demanda)){
			$rowDemanda = $this->objDemanda->fetchRow(
                                    $this->objDemanda->select()
                                                     ->where("cd_demanda = ?", $cd_demanda, Zend_Db::INT_TYPE));
			$this->view->cd_demanda            = $rowDemanda->cd_demanda;
            $this->view->cd_status_atendimento = $rowDemanda->cd_status_atendimento;
		}
	}
	
	public function montaComboNivelServicoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto  = $this->_request->getParam('cd_objeto');
		$cd_demanda = $this->_request->getParam("cd_demanda",0); 
		$strCombo   = "";

		if($cd_demanda == 0){
			$arrNivelServico = $this->objNivelServico->getNivelServico($cd_objeto);
		} else {
			$arrNivelServico = $this->objDemandaProfissionalNivelServico->getDemandaNivelServico($cd_demanda,$cd_objeto,"N",2);
		}
		if(count($arrNivelServico)){	
			$strCombo .= "<option label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			foreach($arrNivelServico as $key=>$value)
				$strCombo .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
		} else {
			$strCombo .= "<option label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strCombo;
	}
	
	public function montaComboProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto  = $this->_request->getParam('cd_objeto');
		$cd_demanda = $this->_request->getParam('cd_demanda',0);
		
		$arr1 = "";
		$arrProfissional  = $this->objProfissionalObjetoContrato->getProfissionalGerenteTecnicoObjetoContrato($cd_objeto, true);
		if(count($arrProfissional) > 0)
			foreach($arrProfissional as $key=>$value)
				$arr1 .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
		echo $arr1;		
	}
	
	public function gridEncaminharSolicitacaoDemandaAction()
	{
		$this->_helper->layout->disableLayout();
		$arrDados = array();
		
		$cd_demanda = $this->_request->getParam('cd_demanda',0);
		if($cd_demanda){
			$arrDados = $this->objDemandaProfissional->getDadosDemandaProfissionalGrid($cd_demanda);
		}
		$this->view->res = $arrDados;
	}
	
	public function excluirProfissionalDesignadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_demanda       = $this->_request->getParam('cd_demanda');
		$cd_profissional  = $this->_request->getParam('cd_profissional');
		$cd_nivel_servico = $this->_request->getParam('cd_nivel_servico');
		
		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		try {
			//Verifica se o profissional possui histórico se tiver não posso apagar
			$this->excluirDemandaProfissionalNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico);
			$msg = Base_Util::getTranslator('L_MSG_SUCESS_DESASSOCIAR_PROFISSIONAL_DEMANDA');
			$db->commit();
		} catch(Zend_Exception $e){
			$db->rollback();
			$msg = Base_Util::getTranslator('L_MSG_ERRO_DESASSOCIAR_PROFISSIONAL_DEMANDA');
		}
		echo $msg;
	}
	
	private function excluirDemandaProfissionalNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico)
	{
		if($this->objDemandaProfissionalNivelServico->delete(
                array("cd_demanda = ?"       => $cd_demanda,
                      "cd_profissional = ?"  =>$cd_profissional,
                      "cd_nivel_servico = ?" => $cd_nivel_servico
                ))){
			$arrDados = $this->objDemandaProfissionalNivelServico->fetchAll(
                               array("cd_demanda = ?"      => $cd_demanda,
                                     "cd_profissional = ?" => $cd_profissional)
                               )->toArray();
			if(count($arrDados) == 0){
				if($this->excluirDemandaProfissional($cd_demanda, $cd_profissional)){
					return true;
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	private function excluirDemandaProfissional($cd_demanda, $cd_profissional)
	{
		if($this->objDemandaProfissional->delete(
                array("cd_demanda = ?"      => $cd_demanda,
                      "cd_profissional = ?" => $cd_profissional
                     ))){
			return true;
		} else {
			return false;
		}
	}
	
	public function confirmaDemandaProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_demanda       = (int)$this->_request->getParam('cd_demanda', 0);
		$cd_profissional  = (int)$this->_request->getParam('cd_profissional', 0);
		$cd_nivel_servico = (int)$this->_request->getParam('cd_nivel_servico', 0);

		$whereDemandaNivelServico = array(
            "cd_demanda = ?"      => $cd_demanda,
            "cd_profissional = ?" => $cd_profissional,
            "cd_nivel_servico = ?"=> $cd_nivel_servico
        );

		$arrDemandaNivelServico = $this->objDemandaProfissionalNivelServico->fetchRow($whereDemandaNivelServico)->toArray();
		$msg = "";
		if(is_null($arrDemandaNivelServico['dt_demanda_nivel_servico'])){
			$rowUpdateNivelServico['dt_demanda_nivel_servico'] = date('Y-m-d H:i:s');
			if($this->objDemandaProfissionalNivelServico->update($rowUpdateNivelServico,$whereDemandaNivelServico)) {
				$rowUpdateDemandaProfissional['dt_demanda_profissional'] = date('Y-m-d H:i:s');
				$whereDemandaProfissional = array(
                    "cd_demanda = ?"      => $cd_demanda,
                    "cd_profissional = ?" => $cd_profissional
                );
				if($this->objDemandaProfissional->update($rowUpdateDemandaProfissional,$whereDemandaProfissional)){
					$msg = Base_Util::getTranslator('L_MSG_SUCESS_CONFIRMAR_DESIGNACAO_PROFISSIONAL_DEMANDA');
					if (K_ENVIAR_EMAIL == "S") {
						$arrDados["cd_profissional"] = $cd_profissional;
						$this->_helper->Mail->enviaEmail($_SESSION["oasis_logged"][0]["cd_objeto"], $this->_request->getControllerName(), $arrDados);
					}
				} else {
					$msg = Base_Util::getTranslator('L_MSG_ERRO_CONFIRMAR_DESIGNACAO_PROFISSIONAL_DEMANDA');
				}
			} else {
				$msg = Base_Util::getTranslator('L_MSG_ERRO_CONFIRMAR_DESIGNACAO_PROFISSIONAL_DEMANDA');
			}
		}
		echo $msg;
	}
	
	public function recuperaDesignacaoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$objProfissional                    = new Profissional($this->_request->getControllerName());
		$objDemandaProfissionalNivelServico = new DemandaProfissionalNivelServico($this->_request->getControllerName());

		$cd_demanda       = (int)$this->_request->getParam('cd_demanda', 0);
		$cd_profissional  = (int)$this->_request->getParam('cd_profissional', 0);
		$cd_nivel_servico = (int)$this->_request->getParam('cd_nivel_servico', 0);
		
		$rowProfissional = $objProfissional->fetchRow(array("cd_profissional = ?" => $cd_profissional));

		if(count($rowProfissional) > 0)
			$arrJson['nomeProfissional'] = $rowProfissional->tx_profissional;
		
		$rowNivelServico = $this->objNivelServico->fetchRow(array("cd_nivel_servico = ?"=>$cd_nivel_servico));
		
		if(count($rowNivelServico) > 0)
			$arrJson['nomeNivelServico'] = $rowNivelServico->tx_nivel_servico;
		
		$whereDemandaNivelServico = array(
            "cd_demanda = ?"       =>$cd_demanda,
            "cd_profissional = ?"  =>$cd_profissional,
            "cd_nivel_servico = ?" =>$cd_nivel_servico,
        );

        $rowDemandaNivelServico = $this->objDemandaProfissionalNivelServico->fetchRow($whereDemandaNivelServico);
		if(count($rowDemandaNivelServico) > 0){
			$arrJson['strObservacao'] = $rowDemandaNivelServico->tx_obs_nivel_servico;
			$arrJson['dataDesignacao'] = ($rowDemandaNivelServico->dt_demanda_nivel_servico)?$rowDemandaNivelServico->dt_demanda_nivel_servico:null;
		}
 		echo Zend_Json_Encoder::encode($arrJson);
	}
}