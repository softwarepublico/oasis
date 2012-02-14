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

class EncaminharPreDemandaController extends Base_Controller_Action 
{
	private $objPreDemanda;
	private $objUnidade;
	private $objSolicitacao;
	private $objProfissionalObjetoContrato;
	private $objUtil;
	
	public function init()
	{
		parent::init();
		$this->objPreDemanda                 = new PreDemanda($this->_request->getControllerName());
		$this->objUnidade                    = new Unidade($this->_request->getControllerName());
		$this->objSolicitacao                = new Solicitacao($this->_request->getControllerName());
		$this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$this->objUtil                       = new Base_Controller_Action_Helper_Util();
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ENCAMINHAR_PRE_DEMANDA'));
		
		$cd_pre_demanda            = $this->_request->getParam('cd_pre_demanda');
		$arrPreDemanda             = $this->objPreDemanda->getPreDemanda($cd_pre_demanda);
		$arrPreDemanda[0]['tx_pre_demanda'] = str_ireplace('\"','"',$arrPreDemanda[0]['tx_pre_demanda']);
		$this->view->arrPreDemanda = $arrPreDemanda;
		
		$arrUnidadeCombo             = $this->objUnidade->getUnidade(true);
		$this->view->arrUnidadeCombo = $arrUnidadeCombo;
	}
	
	public function salvarPreDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;
		
		$arrDados = $this->_request->getPost();
		
		$ano 						   = date("Y");
		$ni_solicitacao                = $this->objSolicitacao->getNewValueByObjeto($arrDados['cd_objeto_receptor'], $ano);
		$novo 				           = $this->objSolicitacao->createRow();
		$novo->ni_ano_solicitacao      = $ano;
		$novo->cd_objeto               = $arrDados['cd_objeto_receptor'];
		$novo->ni_solicitacao	       = $ni_solicitacao; 
		$novo->dt_solicitacao          = date('Y-m-d H:i:s');
		$novo->st_solicitacao          = $arrDados['st_solicitacao'];
		$novo->tx_solicitante   	   = $arrDados['tx_profissional'];
		$novo->cd_unidade              = $arrDados['cd_unidade'];
		$novo->tx_solicitacao          = $arrDados['tx_pre_demanda'];
		$novo->tx_obs_solicitacao 	   = $arrDados['tx_obs_solicitacao'];
		$novo->ni_prazo_atendimento    = $arrDados['ni_prazo_atendimento'];

		if ($novo->save()){
			$retorno = $this->updatePreDemanda($ni_solicitacao,$ano,$arrDados['cd_pre_demanda']);
			if($retorno === false){
				$db->rollback();
				echo Base_Util::getTranslator('L_MSG_ERRO_ENCAMINHAR_PRE_DEMANDA');
			} else {
				$db->commit();
				echo Base_Util::getTranslator('L_MSG_SUCESS_ENCAMINHAR_PRE_DEMANDA');
				if (K_ENVIAR_EMAIL == "S") {
					$_objMail = new Base_Controller_Action_Helper_Mail();
					$_objMail->enviaEmail($arrDados['cd_objeto_receptor'], $this->_request->getControllerName(), $arrDados);
				}
			}
		} else {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_ENCAMINHAR_PRE_DEMANDA');
		}		
	}
	
	protected function updatePreDemanda($ni_solicitacao,$ni_ano_solicitacao,$cd_pre_demanda)
	{
	    $arrUpdate['ni_ano_solicitacao'] = $ni_ano_solicitacao;
	    $arrUpdate['ni_solicitacao']     = $ni_solicitacao;
	    
		if($this->objPreDemanda->update($arrUpdate,array('cd_pre_demanda = ?'=>$cd_pre_demanda))){
			return true;
		} else {
			return false;
		}
	}
}