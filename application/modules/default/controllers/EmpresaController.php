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

class EmpresaController extends Base_Controller_Action
{
	private $empresa;

	public function init()
	{
		parent::init();
		$this->empresa = new Empresa($this->_request->getControllerName());
	}

	public function indexAction(){$this->initView();}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData 	= $this->_request->getPost();
		
		$arrResult	= array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro		= false;
		
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			
			if(!empty($formData['cd_empresa'])) {
				$novo             = $this->empresa->fetchRow("cd_empresa = {$formData['cd_empresa']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo             = $this->empresa->createRow();
				$novo->cd_empresa = $this->empresa->getNextValueOfField('cd_empresa');
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
			
			$novo->tx_empresa          = strtoupper($formData['tx_empresa']);
			$novo->tx_cnpj_empresa     =  $formData['tx_cnpj_empresa'];
			$novo->tx_endereco_empresa = ($formData['tx_endereco_empresa']) ? $formData['tx_endereco_empresa'] : null ;
			$novo->tx_telefone_empresa = ($formData['tx_telefone_empresa']) ? $formData['tx_telefone_empresa'] : null;
			$novo->tx_fax_empresa      = ($formData['tx_fax_empresa'	 ]) ? $formData['tx_fax_empresa'	 ] : null;
			$novo->tx_email_empresa    = ($formData['tx_email_empresa'	 ]) ? $formData['tx_email_empresa'	 ] : null;
			
			($novo->save()) ? $db->commit() : $db->rollBack();
			
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
	
	public function recuperarEmpresaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_empresa = $this->_request->getParam('cd_empresa');
	
		$res = $this->empresa->find($cd_empresa)->current()->toArray();
		
		echo Zend_Json::encode($res);
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_empresa = (int)$this->_request->getParam('cd_empresa');

		$return 	= $this->empresa->excluirEmpresa($cd_empresa);
		
		if($return != 2){
			if($return == 1 ){
				$retorno['atualiza' ] = true;
				$retorno['msg' ] = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
				$retorno['tipo'] = 1;
			}else{
				$retorno['atualiza' ] = false;
				$retorno['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
				$retorno['tipo'] = 3;
			}
		} else {
			$retorno['atualiza' ] = false;
			$retorno['msg' ] = Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO');
			$retorno['tipo'] = 2;
		}
		echo Zend_Json::encode($retorno);
	}

	public function gridEmpresaAction()
	{
		$this->_helper->layout->disableLayout();
		$res             = $this->empresa->fetchAll(null,'tx_empresa')->toArray();
		$this->view->res = $res;		
	}

	public function comboEmpresaAction()
	{

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$res = $this->empresa->getEmpresa(true);

		$strOptions = "";

		foreach ($res as $cd_empresa => $tx_empresa) {
			$strOptions .= "<option value=\"{$cd_empresa}\">{$tx_empresa}</option>";
		}
		echo $strOptions;
	}
}