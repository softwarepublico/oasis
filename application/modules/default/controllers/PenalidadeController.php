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

class PenalidadeController extends Base_Controller_Action
{
	private $penalidade;
	private $objUtil;

	public function init()
	{
		parent::init();
		$this->penalidade = new Penalidade($this->_request->getControllerName());
		$this->objUtil    = new Base_Controller_Action_Helper_Util();
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

			if(!empty($formData['cd_penalidade'])) {
				$novo          = $this->penalidade->fetchRow("cd_penalidade = {$formData['cd_penalidade']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo                = $this->penalidade->createRow();
				$novo->cd_penalidade = $this->penalidade->getNextValueOfField('cd_penalidade');
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}

			$novo->cd_contrato               = $formData['cd_contrato_penalidade'];
			$novo->tx_penalidade             = $formData['tx_penalidade'];
			$novo->st_ocorrencia             = $formData['st_ocorrencia'];
			$novo->tx_abreviacao_penalidade  = $formData['tx_abreviacao_penalidade'];
			$novo->ni_valor_penalidade       = $this->objUtil->validaValor($formData['ni_valor_penalidade']);
			$novo->ni_penalidade             = $formData['ni_penalidade'];

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

	public function recuperaPenalidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_penalidade = $this->_request->getParam('cd_penalidade');

		$res = $this->penalidade->getRowPenalidade($cd_penalidade);

		echo Zend_Json::encode($res);
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_penalidade = (int)$this->_request->getParam('cd_penalidade', 0);
		if ($this->penalidade->delete("cd_penalidade=$cd_penalidade")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridPenalidadeAction($cd_contrato = null)
	{
		if(is_null($cd_contrato)){
			$this->_helper->layout->disableLayout();
			// recupera o parametro
			$cd_contrato = $this->_request->getParam('cd_contrato', 0);
		}
		
		// realiza a consulta
		$select = $this->penalidade->select();
		$select->where("cd_contrato = $cd_contrato");
		$select->order('ni_penalidade ASC');
		$res = $this->penalidade->fetchAll($select)->toArray();
		$this->view->res = $res;
	}
	
	public function verificaNumeroPenalidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato   = trim($this->_request->getParam('cd_contrato'));
		$ni_penalidade = trim($this->_request->getParam('ni_penalidade'));
		
		$arrDados = $this->penalidade->getDadosPenalidade($cd_contrato);
		
		$cad = "N";
		foreach($arrDados as $key=>$value){
			if($ni_penalidade == $arrDados[$key]['ni_penalidade']){
				$cad = "S";
			} 
		}
		echo ($cad == "S")?"2":"3";
	}
}