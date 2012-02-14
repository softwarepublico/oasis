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

class ProfissionalObjetoContratoController extends Base_Controller_Action
{
	private $profissionalObjetoContrato;
	private $objContrato;
	private $objPerfilProfissional;

	public function init()
	{
		parent::init();
		$this->profissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$this->objContrato                = new Contrato($this->_request->getControllerName());
		$this->objPerfilProfissional      = new PerfilProfissional($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
		//  Associa este array com um atributo da camada de visao
		$this->view->contratoCombo = $this->objContrato->getContrato(true, true);		
	}

	public function pesquisaProfissionalAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_objeto              = $this->_request->getParam('cd_objeto',0);

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_objeto == -1) {
			echo '';
		} else {
			/*
			 * Cria uma instancia do modelo e pesquisa pelos profissionais alocado ao projeto selecionado e aos profissionais que nao estao
			 * alocados ao mesmo 
			 */
			$profissional = new ProfissionalObjetoContrato($this->_request->getControllerName());

			// Recordset de profissionais que nao se encontram no projeto selecionado
            $foraObjeto  = $profissional->pesquisaProfissionalForaObjeto($cd_objeto);

			// Recordset de profissionais que se encontram no projeto selecionado
			$noObjeto  = $profissional->pesquisaProfissionalNoObjeto($cd_objeto,true);

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($foraObjeto as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_profissional']}\">{$fora['tx_profissional']}</option>";
			}

			$arr2 = "";
			foreach ($noObjeto as $no) {
				$arr2 .= "<option value=\"{$no['cd_profissional']}\">{$no['tx_profissional']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
		}
	}
	
	public function associaProfissionalObjetoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_objeto              = $post['cd_objeto'];
		$profissionais          = Zend_Json_Decoder::decode($post['profissionais']);
		
		$profissionalObjeto = new ProfissionalObjetoContrato($this->_request->getControllerName());
		
		$arrDados = array();
		foreach ($profissionais as $profissional) {
			$novo = $profissionalObjeto->createRow();
			$novo->cd_objeto              = $cd_objeto;
			$novo->cd_profissional        = $profissional;
			$novo->save();
		}
	}
	
	public function desassociaProfissionalObjetoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_objeto              = $post['cd_objeto'];
		$profissionais          = Zend_Json_Decoder::decode($post['profissionais']);
		
		$profissionalObjeto = new ProfissionalObjetoContrato($this->_request->getControllerName());
		
		$arrDados = array();
		foreach ($profissionais as $profissional) {
			$where = "cd_objeto={$cd_objeto} and cd_profissional={$profissional}";
			$profissionalObjeto->delete($where);
		}
	}

	public function pesquisaProfissionalGerenteObjetoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
		$profissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$res = $profissionalObjetoContrato->getProfissionalGerenteObjetoContrato($cd_objeto, true);
		
		$strOptions = "";
		
		foreach ($res as $cd_profissional => $tx_profissional) {
			$strOptions .= "<option value=\"{$cd_profissional}\">{$tx_profissional}</option>";			
		}
		
		echo $strOptions;
	}
	
	public function pesquisaProfissionalObjetoContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
		$profissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$res = $profissionalObjetoContrato->getProfissionalObjetoContrato($cd_objeto, true, true);
		
		$strOptions = "";
		
		foreach ($res as $cd_profissional => $tx_profissional) {
			$strOptions .= "<option value=\"{$cd_profissional}\">{$tx_profissional}</option>";			
		}
		
		echo $strOptions;
	}
	
	public function gridProfissionalAssociadoAction()
	{
		$this->_helper->layout->disableLayout();
		
		// Recupera os parametros enviados por get
		$cd_objeto              = $this->_request->getParam('cd_objeto');
		
		// Recordset de profissionais que se encontram no projeto selecionado
		$noObjeto  = $this->profissionalObjetoContrato->pesquisaProfissionalNoObjeto($cd_objeto,true);
		
		$arrFlag = array();
		
		foreach ($noObjeto as $profissional)
		{
			$arrDados = $this->profissionalObjetoContrato->getFlagProfissionalObjetoContrato($profissional['cd_profissional'], $cd_objeto);
			
			$arrFlag[$profissional['cd_profissional']] = $arrDados;
		}

		$this->view->cd_objeto	= $cd_objeto;
		$this->view->res		= $noObjeto;
		$this->view->arrFlag	= $arrFlag;
		$this->view->arrPerfilProfissional = $this->objPerfilProfissional->getPerfilProfissional($cd_objeto, true);
	}
	
	public function gravaRecebeEmailAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_profissional = $this->_request->getParam('cd_profissional');
		$cd_objeto       = $this->_request->getParam('cd_objeto');
		$st_recebe_email = ($this->_request->getParam('st_recebe_email') === "true") ? "S" : null;

		$flag = array('st_recebe_email' => $st_recebe_email);
		
		$retorno = $this->profissionalObjetoContrato->updateFlagProfissionalObjetoContrato($cd_profissional, $cd_objeto, $flag);
		
		$msg = ($retorno)? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function gravaObjetoPadraoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_profissional  = $this->_request->getParam('cd_profissional');
		$cd_objeto        = $this->_request->getParam('cd_objeto');
		$st_objeto_padrao = ($this->_request->getParam('st_objeto_padrao') === "true") ? "S" : null;
		
		$cd_objeto_padrao = $this->profissionalObjetoContrato->buscaObjetoPadrao($cd_profissional);

		$arrRetorno = array('comMsg'=>false,'msg'=>'', 'type'=>2);

		if (is_null($cd_objeto_padrao))
		{
			$flag = array('st_objeto_padrao' => $st_objeto_padrao);
			$retorno = $this->profissionalObjetoContrato->updateFlagProfissionalObjetoContrato($cd_profissional, $cd_objeto, $flag);
			$arrRetorno['msg'] = ($retorno)? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
		else
		{
			if ($cd_objeto == $cd_objeto_padrao && is_null($st_objeto_padrao))
			{
				$flag = array('st_objeto_padrao' => $st_objeto_padrao);
				$retorno = $this->profissionalObjetoContrato->updateFlagProfissionalObjetoContrato($cd_profissional, $cd_objeto, $flag);
				$arrRetorno['msg'] = ($retorno)? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
			}
			else
			{
				$arrRetorno['comMsg'] = true;
				$arrRetorno['type'	] = 2;
				$arrRetorno['msg'	] = Base_Util::getTranslator('L_MSG_ALERT_PROFISSIONAL_POSSUI_OBJETO_PADRAO');
			}
		}
		
		echo Zend_Json::encode($arrRetorno);
	}

	public function gravaPerfilProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrRetorno = array('sucesso'=>false);

		$cd_objeto				= $this->_request->getParam('cd_objeto');
		$cd_profissional		= $this->_request->getParam('cd_profissional');
		$cd_perfil_profissional = ($this->_request->getParam('cd_perfil_profissional') == -1) ? null : $this->_request->getParam('cd_perfil_profissional');

		$where = "cd_objeto = {$cd_objeto} and cd_profissional = {$cd_profissional}";

		if($this->profissionalObjetoContrato->update(array('cd_perfil_profissional'=>$cd_perfil_profissional), $where)){
			$arrRetorno['sucesso'] = true;
		}
		echo Zend_Json::encode($arrRetorno);
	}
}