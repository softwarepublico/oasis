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

class DocumentoOrigemController extends Base_Controller_Action 
{
	private $docOrigem;
	private $docOrigemSolicitacao;
	
	public function init()
	{
		parent::init();
		$this->docOrigem			= new DocumentoOrigem($this->_request->getControllerName());
		$this->docOrigemSolicitacao	= new Solicitacao($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_DOCUMENTO_ORIGEM'));
		$form = new DocumentoOrigemForm();
		$this->view->form = $form;
	}

	/**
	 * Método que salva os dados do documento origem
	 */
	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$novo 			       			  = $this->docOrigem->createRow();
		$novo->cd_documento_origem		  = $this->docOrigem->getNextValueOfField('cd_documento_origem');
		$novo->tx_documento_origem        = $arrDados['tx_documento_origem'];
		$novo->dt_documento_origem        = $arrDados['dt_documento_origem'];
		$novo->tx_obs_documento_origem    = $arrDados['tx_obs_documento_origem'];
		if($novo->save()) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	/**
	 * Método que altera os dados de um documento origem
	 */
	public function alterarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$arrUpdate['tx_documento_origem']     = $arrDados['tx_documento_origem'];
		$arrUpdate['dt_documento_origem']     = $arrDados['dt_documento_origem'];
		$arrUpdate['tx_obs_documento_origem'] = $arrDados['tx_obs_documento_origem'];
		
		if( $this->docorigem->update( $arrUpdate, "cd_documento_origem = {$arrDados['cd_documento_origem']}" ) ){
			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}
	}

	/**
	 * Método que excluir os dados do documento origem
	 * verificar se existe ligação em outra tabela para excluir
	 */
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrDados = $this->_request->getPost();
		
		if( count( $this->docOrigemSolicitacao->fetchAll("cd_documento_origem = {$arrDados['cd_documento_origem']}")->toArray() ) > 0){
			echo "Documento Origem não pode ser excluído.";
		}else{
			if($this->docOrigem->delete("cd_documento_origem = {$arrDados['cd_documento_origem']}")){
                echo Base_Util::getTranslator('L_MSG_SUCESS_DELECAO');
			} else {
                echo Base_Util::getTranslator('L_MSG_ERRO_DELECAO_REGISTRO');
			}
		}
	}
	
	/**
	 * Método que recupera os dados de um documento origem expecífico
	 */
	public function recuperaDadosAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrDados = $this->_request->getPost();
		$arrDados = $this->docOrigem->fetchAll("cd_documento_origem = {$arrDados['cd_documento_origem']}")->toArray();
		
		echo Zend_Json::encode($arrDados[0]);
	}
	
	/**
	 * Método que recupera todos os documentos origem para montar a grid
	 */
	public function gridDocumentoOrigemAction()
	{
		$this->_helper->layout->disableLayout();
		$res = $this->docOrigem->fetchAll()->toArray();
		$this->view->res = $res;
	}
}