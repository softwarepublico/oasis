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

class TreinamentoController extends Base_Controller_Action 
{
	private $treinamento;
	private $treinamentoProfissional;
	
	public function init()
	{
		parent::init();
		$this->treinamento				= new Treinamento($this->_request->getControllerName());
		$this->treinamentoProfissional	= new TreinamentoProfissional($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_TREINAMENTO'));
		$form = new TreinamentoForm();
		$this->view->form = $form;
	}

	/**
	 * Método que salva os dados do treinamento
	 */
	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$novo 			       			  = $this->treinamento->createRow();
		$novo->cd_treinamento 			  = $this->treinamento->getNextValueOfField('cd_treinamento');
		$novo->tx_treinamento             = $arrDados['tx_treinamento'];
		$novo->tx_obs_treinamento         = $arrDados['tx_obs_treinamento'];
		if($novo->save()) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	/**
	 * Método que altera os dados de um treinamento
	 */
	public function alterarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$arrUpdate['tx_treinamento']     = $arrDados['tx_treinamento'];
		$arrUpdate['tx_obs_treinamento'] = $arrDados['tx_obs_treinamento'];
		
		if( $this->treinamento->update( $arrUpdate, "cd_treinamento = {$arrDados['cd_treinamento']}" ) ){
			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}
	}

	/**
	 * Método que excluir os dados do treinamento
	 * verificar se existe ligação em outra tabela para excluir
	 */
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrDados = $this->_request->getPost();
		
		if( count( $this->treinamentoProfissional->fetchAll("cd_treinamento = {$arrDados['cd_treinamento']}")->toArray() ) > 0){
			echo "Treinamento não pode ser excluído.";
		}else{
			if($this->treinamento->delete("cd_treinamento = {$arrDados['cd_treinamento']}")){
                echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			} else {
                echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
			}
		}
	}
	
	/**
	 * Método que recupera os dados de um treinamento expecífico
	 */
	public function recuperaDadosAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrDados = $this->_request->getPost();
		$arrDados = $this->treinamento->fetchAll("cd_treinamento = {$arrDados['cd_treinamento']}")->toArray();
		
		echo Zend_Json::encode($arrDados[0]);
	}
	
	/**
	 * Método que recupera todos os treinamento para montar a grid
	 */
	public function gridTreinamentoAction()
	{
		$this->_helper->layout->disableLayout();
		$res = $this->treinamento->fetchAll()->toArray();
		$this->view->res = $res;
	}
}