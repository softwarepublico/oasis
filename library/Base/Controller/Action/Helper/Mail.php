<?php
class Base_Controller_Action_Helper_Mail extends Zend_Controller_Action_Helper_Abstract
{
	private $_objMail;
	private $_objMenu;
	private $_objProfissionalObjetoContrato;
	private $_objMsgEmail;

	public function  __construct() {
		Zend_Loader::loadClass('Menu'                      , Base_Util::baseUrlModule('default', 'models'));
		Zend_Loader::loadClass('ProfissionalObjetoContrato', Base_Util::baseUrlModule('default', 'models'));
		Zend_Loader::loadClass('MsgEmail'                  , Base_Util::baseUrlModule('default', 'models'));

		$this->_objMail                       = new Base_Mail();
		$this->_objMenu                       = new Menu();
		$this->_objProfissionalObjetoContrato = new ProfissionalObjetoContrato();
		$this->_objMsgEmail                   = new MsgEmail();
	}

	public function enviaEmail($cd_objeto, $controller, $arrDados)
	{
		$cd_menu = $this->_objMenu->buscaMenu($controller);

		if (array_key_exists("st_msg_email", $arrDados) ) {
			$arrMsg  = $this->_objMsgEmail->getMsgEmail($cd_menu, $arrDados["st_msg_email"]);
			unset ($arrDados["st_msg_email"]);
		}else{
			$arrMsg  = $this->_objMsgEmail->getMsgEmail($cd_menu);
		}

		if (array_key_exists("cd_profissional", $arrDados) ) {
			$cd_profissional = $arrDados["cd_profissional"];
			unset ($arrDados["cd_profissional"]);
		}
		
		//To
		eval("\$arrDest = \$this->_objProfissionalObjetoContrato->{$arrMsg['tx_metodo_msg_email']};");
		
		//Subject
		foreach ($arrDados as $key=>$value){
				$arrMsg['tx_assunto_msg_email'] = str_replace($key, $value, $arrMsg['tx_assunto_msg_email']);
		}
		
		//Body
		foreach ($arrDados as $key=>$value){
				$arrMsg['tx_msg_email'] = str_replace($key, $value, $arrMsg['tx_msg_email']);
		}

		foreach ($arrDest as $destinatario){
			try{
				$this->_objMail->emailHtml(array(), array("{$destinatario['tx_email_institucional']}"=>"{$destinatario['tx_profissional']}"), $arrMsg['tx_msg_email'], $arrMsg['tx_assunto_msg_email']);
			}catch(Zend_Exception $e){
				echo "<pre>";
				print_r($e);
				die("<hr>arquivo:".__FILE__."<br>linha:".__LINE__);
			}
		}
	}

	public function setDadosMsgEmail($arrInf, $controllerName)
	{
		$_objContrato             = new Contrato();
		$_objProjeto              = new Projeto();
		$arrContrato              = $_objContrato->getDadosContratoAtivoObjetoTipoProjeto($arrInf['cd_projeto']);
		$arrProjeto               = $_objProjeto->find($arrInf['cd_projeto'])->current()->toArray();

		if (array_key_exists('cd_parcela', $arrInf)) {
			$_objParcela = new Parcela();
			$arrParcela  = $_objParcela->find($arrInf['cd_parcela'],$arrInf['cd_projeto'],$arrInf['cd_proposta'])->current()->toArray();
			$arrDados['_ni_parcela']   = $arrParcela['ni_parcela'];
		}

		$arrDados['_tx_sigla_projeto'] = $arrProjeto['tx_sigla_projeto'];
		$arrDados['_cd_proposta']      = $arrInf['cd_proposta'];
		
		if (array_key_exists('st_msg_email', $arrInf)) {
			$arrDados['st_msg_email']  = $arrInf['st_msg_email'];
		}
		
		if (array_key_exists('_tx_obs', $arrInf)) {
			$arrDados['_tx_obs']       = $arrInf['_tx_obs'];
		}

		$this->enviaEmail($arrContrato['cd_objeto'], $controllerName, $arrDados);
	}
}
