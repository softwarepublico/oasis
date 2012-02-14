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

class PreProjetoEvolutivoController extends Base_Controller_Action
{
	private $preProjetoEvolutivo;
	private $projeto;
	
	public function init()
	{
        $this->view->headTitle(Base_Util::setTitle('L_MENU_PRE_PROJETO_EVOLUTIVO'));
		parent::init();
		$this->preProjetoEvolutivo = new PreProjetoEvolutivo($this->_request->getControllerName());
		$this->projeto             = new Projeto($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
		$form             = new PreProjetoEvolutivoForm();
		$this->view->form = $form;
		
		//Recuperação de Dados
		$cd_pre_projeto_evolutivo = $this->_request->getParam('cd_pre_projeto_evolutivo');
		if ($cd_pre_projeto_evolutivo > 0) {
			$arrDados   = array();
			$arrDados['cd_pre_projeto_evolutivo'] = $cd_pre_projeto_evolutivo;
			$form->populate($arrDados);
		}
	}
	
	public function salvarDadosPreProjetoEvolutivoAction(array $arrPreProjetoEvolutivo = null)
	{
		if(count($arrPreProjetoEvolutivo) > 0){
			$return  = $this->preProjetoEvolutivo->salvaPreProjetoEvolutivo($arrPreProjetoEvolutivo);
			return $return;
		} else {
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$arrDados = $this->_request->getPost();
			
			unset($arrDados['retorno_booleano']);
			$retornoBooleano = $this->_request->getParam('retorno_booleano',0);
			
			if( $retornoBooleano === "true" ){
				//estes parâmetros são adicionados quando a chamada deste método é feito pelo acordion de análise de mudança
				//para registrar a data da abertura do pré projeto
				$arrDados['st_gerencia_mudanca'] = "S"; 
				$arrDados['dt_gerencia_mudanca'] = new Zend_Db_Expr("{$this->preProjetoEvolutivo->to_date("'" . date("Ymd") . "'", 'YYYYMMDD')}");
				
				$retornoBooleano = true;
			}else{
				$retornoBooleano = false;
			}
			
			$arrDados["cd_contrato"] = $_SESSION["oasis_logged"][0]["cd_contrato"];
			
			$return   = $this->preProjetoEvolutivo->salvaPreProjetoEvolutivo($arrDados);
			
			if($retornoBooleano){
				echo ($return) ? 'true' : 'false';				
			}else{
				$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
				echo $msg;
			}
		}
	}
	
	public function recuperaPreProjetoEvolutivoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_pre_projeto_evolutivo = $this->_request->getParam('cd_pre_projeto_evolutivo');
		$res                      = $this->preProjetoEvolutivo->recuperaPreProjetoEvolutivo($cd_pre_projeto_evolutivo);
		
		echo Zend_Json::encode($res);
	}
	
	public function alterarDadosPreProjetoEvolutivoAction(array $arrPreProjetoEvolutivo = null)
	{
		if(count($arrPreProjetoEvolutivo) > 0){
			$return  = $this->preProjetoEvolutivo->alterarPreProjetoEvolutivo($arrPreProjetoEvolutivo);
			return $return; 
		} else {
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$arrDados = $this->_request->getPost();
			$return   = $this->preProjetoEvolutivo->alterarPreProjetoEvolutivo($arrDados);
			$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
			echo $msg;
		}
	}
	
	public function excluirPreProjetoEvolutivoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$cd_pre_projeto_evolutivo           = (int)$this->_request->getParam('cd_pre_projeto_evolutivo');
				
		$msg = $this->preProjetoEvolutivo->excluirPreProjetoEvolutivo($cd_pre_projeto_evolutivo);
			
		$msg = ($msg)? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		echo $msg;
		
	}
	
	public function listaPreProjetoEvolutivoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = (int)$this->_request->getParam('cd_projeto', 0);
		$res        = $this->preProjetoEvolutivo->getPreProjetoEvolutivo($cd_projeto, true);
		
		$strOptions = "";
		
		foreach ($res as $cd_pre_projeto_evolutivo => $tx_pre_projeto_evolutivo) {
			$strOptions .= "<option value=\"{$cd_pre_projeto_evolutivo}\">{$tx_pre_projeto_evolutivo}</option>";			
		}
		
		echo $strOptions;
	}	
}