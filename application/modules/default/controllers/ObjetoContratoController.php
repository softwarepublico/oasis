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

class ObjetoContratoController extends Base_Controller_Action
{
	private $objetoContrato;
   	private $objUtil;


	public function init()
	{
		parent::init();
		$this->objetoContrato = new ObjetoContrato($this->_request->getControllerName());
        $this->objUtil        = new Base_Controller_Action_Helper_Util();
		
		$this->view->addHelperPath('Base/View/Helper', 'Base_View_Helper');
		$this->_helper->addPath('Base/Controller/Action/Helper', 'Base_Controller_Action_Helper');
		
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData = $this->_request->getPost();

		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			if(!empty($formData['cd_objeto'])) {
				$novo          = $this->objetoContrato->fetchRow("cd_objeto= {$formData['cd_objeto']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo             = $this->objetoContrato->createRow();
				$novo->cd_objeto  = $this->objetoContrato->getNextValueOfField('cd_objeto');
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}

			$novo->cd_contrato                   = $formData['cd_contrato_objeto_contrato'];
			$novo->cd_area_atuacao_ti            = $formData['cd_area_atuacao_ti_objeto_contrato'];
            $novo->tx_objeto                     = $formData['tx_objeto_contrato'];
			$novo->st_objeto_contrato            = $formData['st_objeto_contrato'];
			$novo->st_parcela_orcamento          = ($formData['st_parcela_orcamento'])?$formData['st_parcela_orcamento']:null;
			$novo->ni_porcentagem_parc_orcamento = ($formData['ni_porcentagem_parc_orcamento'])?$formData['ni_porcentagem_parc_orcamento']:null;
			$novo->st_necessita_justificativa    = ($formData['st_necessita_justificativa'])?$formData['st_necessita_justificativa']:null;
			$novo->ni_minutos_justificativa      = ($formData['ni_minutos_justificativa'])?$formData['ni_minutos_justificativa']:null;
			$novo->tx_hora_inicio_just_periodo_1 = ($formData['tx_hora_inicio_just_periodo_1'])?$formData['tx_hora_inicio_just_periodo_1']:null;
			$novo->tx_hora_fim_just_periodo_1    = ($formData['tx_hora_fim_just_periodo_1'])?$formData['tx_hora_fim_just_periodo_1']:null;
			$novo->tx_hora_inicio_just_periodo_2 = ($formData['tx_hora_inicio_just_periodo_2'])?$formData['tx_hora_inicio_just_periodo_2']:null;
			$novo->tx_hora_fim_just_periodo_2    = ($formData['tx_hora_fim_just_periodo_2'])?$formData['tx_hora_fim_just_periodo_2']:null;

            $novo->ni_porcentagem_parc_orcamento = $this->objUtil->validaValor($novo->ni_porcentagem_parc_orcamento);
            
			if($novo->save()){
				$db->commit();
			} else {
				$db->rollBack();
			}
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg'] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function excluirAction()
	{
	
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
		if ($this->objetoContrato->delete("cd_objeto=$cd_objeto")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function pesquisaObjetoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato    = (int)$this->_request->getParam('cd_contrato', 0);
		$res            = $this->objetoContrato->getObjetoContrato($cd_contrato, true);
		$strOptions     = "";

		foreach ($res as $cd_objeto => $tx_objeto) {
			$strOptions .= "<option value=\"{$cd_objeto}\">{$tx_objeto}</option>";
		}

		echo $strOptions;
	}

	public function pesquisaObjetoContratoAtivoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$tipo_objeto = null;
		
		if( array_key_exists('tipo_objeto', $post)){
			$tipo_objeto = $post['tipo_objeto'];
		}
		
        $comSelecione      = true;
        $comAdministrador  = false;
        $comDescNrContrato = true;
		$res               = $this->objetoContrato->getObjetoContratoAtivo($tipo_objeto, $comSelecione, $comAdministrador, $comDescNrContrato);

		$strOptions     = "";
		foreach ($res as $cd_objeto => $tx_objeto) {
			$strOptions .= "<option value=\"{$cd_objeto}\">{$tx_objeto}</option>";
		}

		echo $strOptions;
	}

	public function recuperaObjetoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato = (int)$this->_request->getParam('cd_contrato', 0);
		$arrContrato = $this->objetoContrato->getDadosObjetoContrato($cd_contrato);
		
		echo Zend_Json::encode($arrContrato);		
	}
}