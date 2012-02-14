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

class ModuloController extends Base_Controller_Action
{
	private $modulo;
	private $projeto;

	public function init()
	{
		parent::init();
		$this->modulo  = new Modulo($this->_request->getControllerName());
		$this->projeto = new Projeto($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$cd_projeto = $this->_request->getParam('cd_projeto');
		
		$arrProjeto = $this->projeto->getProjeto($cd_projeto);
		$this->view->projeto = $arrProjeto[0];
		
		$this->view->grid = $this->montaGrid($cd_projeto);
	}
	
	public function salvarModuloAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$tx_modulo  = $this->_request->getParam('tx_modulo');
		$cd_projeto = $this->_request->getParam('cd_projeto');

		$novo             = $this->modulo->createRow();
		$novo->cd_modulo  = $this->modulo->getNextValueOfField('cd_modulo');
		$novo->cd_projeto = $cd_projeto;
		$novo->tx_modulo  = $this->toUpper($tx_modulo);
        
		if(!$novo->save()){
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		} else{
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		}
	}
	
	public function montaComboModuloAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        //Paramêtro que verifica se deseja a inclusão do "Todos" na combo de modulos
        $comTodos = $this->_request->getParam('comTodos',false);
        $comTodos = ($comTodos)?true:false;

        $cd_projeto = $this->_request->getParam('cd_projeto',0);
		$cd_projeto = ($cd_projeto != 0)?$cd_projeto:null;
		
		$arrModulo  = $this->modulo->listaModulo($cd_projeto,true,$comTodos);
		$strOptions = "";
		if(count($arrModulo) > 0){
			foreach ($arrModulo as $cd_modulo => $tx_modulo) {
				$strOptions .= "<option label=\"{$tx_modulo}\" value=\"{$cd_modulo}\">{$tx_modulo}</option>";
			}
		}
		echo $strOptions;
	}
	
	public function pesquisarModuloAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_projeto = $this->_request->getParam('cd_projeto');
		$cd_proposta = $this->_request->getParam('cd_proposta');

		// Recordset de profissionais que nao se encontram no projeto selecionado
		$naoVinculado = $this->modulo->pesquisaModuloNaoVinculado($cd_projeto,$cd_proposta);

		// Recordset de profissionais que se encontram no projeto selecionado
		$vinculado    = $this->modulo->pesquisarModuloVinculado($cd_projeto,$cd_proposta);

		/*
		 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
		 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
		 */
		$arr1 = "";
		foreach ($naoVinculado as $fora) {
			$arr1 .= "<option label=\"{$fora['tx_modulo']}\" value=\"{$fora['cd_modulo']}\">{$fora['tx_modulo']}</option>";
		}

		$arr2 = "";
		foreach ($vinculado as $no) {
			$arr2 .= "<option label=\"{$no['tx_modulo']}\" value=\"{$no['cd_modulo']}\">{$no['tx_modulo']}</option>";
		}
		$retornaOsDois = array($arr1, $arr2);

		echo Zend_Json_Encoder::encode($retornaOsDois);
	}

	public function associaModuloPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		// Recupera os parametros enviados por get
		$cd_projeto  = $post['cd_projeto'];
		$cd_proposta = $post['cd_proposta'];
		$modulos     = Zend_Json_Decoder::decode($post['modulo']);
		
		$propostaModulo = new PropostaModulo($this->_request->getControllerName());
		
		foreach($modulos as $modulo){
			$novo              = $propostaModulo->createRow();
			$novo->cd_projeto  = $cd_projeto;
			$novo->cd_modulo   = $modulo;
			$novo->cd_proposta = $cd_proposta;
			$novo->save();
		}
	}
	
	public function desassociaModuloPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		// Recupera os parametros enviados por get
		$cd_projeto  = $post['cd_projeto'];
		$cd_proposta = $post['cd_proposta'];
		$modulos     = Zend_Json_Decoder::decode($post['modulo']);
		
		$propostaModulo = new PropostaModulo($this->_request->getControllerName());
		
		foreach ($modulos as $modulo) {
			$where = "cd_projeto={$cd_projeto} 
					  and cd_modulo={$modulo} 
					  and cd_proposta={$cd_proposta}"; 
					$propostaModulo->delete($where);
		}
	}
	
	public function pesquisaModuloProjetoAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');
		// realiza a consulta
		$select = $this->modulo->select();
		$select->where("cd_projeto = $cd_projeto");
		$select->order("tx_modulo");
		// $select->orWhere("cd_projeto = $cd_projeto");

		$res = $this->modulo->fetchAll($select);
		$arrModulo = $res->toArray();
		
		$this->view->res = $arrModulo;
	}
	
	public function alterarModuloAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$formData = $this->_request->getPost();

		$where = " cd_modulo = {$formData['cd_modulo']} ";
		$arrUpdate["cd_modulo"]  = $formData["cd_modulo"];
		$arrUpdate["tx_modulo"]  = $this->toUpper($formData["tx_modulo"]);
		$arrUpdate["cd_projeto"] = $formData["cd_projeto"];
		
		if($this->modulo->update($arrUpdate, $where)){
			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}
	}
	
	public function excluirModuloAction()
	{
		$objPropostaModulo = new PropostaModulo($this->_request->getControllerName());
		
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_modulo   = (int)$this->_request->getParam('cd_modulo');
		$cd_projeto  = (int)$this->_request->getParam('cd_projeto');
		$cd_proposta = (int)$this->_request->getParam('cd_proposta');
		
		$arrPropostaMoludo = $objPropostaModulo->getPropostaModulo($cd_modulo, $cd_projeto, $cd_proposta);
		$qtd = count($arrPropostaMoludo);
		
		if($qtd == 0 || $qtd == '0'){
			$where = " cd_modulo = {$cd_modulo} and cd_projeto = {$cd_projeto}";
			if($this->modulo->delete($where)){
				echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
			} else {
				echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
			}
		} else {
			echo Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO');
		}
	}
	
	public function pesquisaModuloPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = (int)$this->_request->getParam('cd_projeto', 0);
		$cd_proposta = (int)$this->_request->getParam('cd_proposta', 0);
		
		$res = $this->modulo->getModuloProposta($cd_projeto, $cd_proposta);

		$strOptions = "<option value=\"\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		if ($res){
			foreach ($res as $cd_modulo => $tx_modulo) {
				$strOptions .= "<option value=\"{$cd_modulo}\">{$tx_modulo}</option>";
		    }
		}
		echo $strOptions;
	}
}