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

class NivelServicoController extends Base_Controller_Action
{
	private $nivelservico;

	public function init()
	{
		parent::init();
		$this->nivelservico = new NivelServico($this->_request->getControllerName());		
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

			if(!empty($formData['cd_nivel_servico'])) {
				$novo             = $this->nivelservico->fetchRow("cd_nivel_servico = {$formData['cd_nivel_servico']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo                   = $this->nivelservico->createRow();
				$novo->cd_nivel_servico = $this->nivelservico->getNextValueOfField('cd_nivel_servico');
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
            $novo->cd_objeto                = $formData['cd_objeto_nivel_servico'];
            $novo->tx_nivel_servico         = $formData['tx_nivel_servico'];
            $novo->ni_horas_prazo_execucao  = $formData['ni_horas_prazo_execucao'];

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

	public function recuperaNivelServicoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_nivel_servico = $this->_request->getParam('cd_nivel_servico');

		$res = $this->nivelservico->getRowNivelServico($cd_nivel_servico);

		echo Zend_Json::encode($res);
	}

	public function excluirAction()
	{
	
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_nivel_servico = (int)$this->_request->getParam('cd_nivel_servico', 0);
		if ($this->nivelservico->delete("cd_nivel_servico=$cd_nivel_servico")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridNivelServicoAction($cd_objeto = null)
	{
		if(is_null($cd_objeto)){
			$this->_helper->layout->disableLayout();
			// recupera o parametro
			$cd_objeto = $this->_request->getParam('cd_objeto', 0);
		}

		// realiza a consulta
		$select = $this->nivelservico->select()->where("cd_objeto = {$cd_objeto}")->order("tx_nivel_servico");

		$res    = $this->nivelservico->fetchAll($select)->toArray();
		$this->view->res = $res;
	}
}