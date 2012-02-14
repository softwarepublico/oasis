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

class PlanoImplantacaoController extends Base_Controller_Action 
{
	private $planoImplantacao;
	private $agendaPlanoImplantacao;
	
	public function init()
	{
		parent::init();
		$this->planoImplantacao		  = new PlanoImplantacao($this->_request->getControllerName());
		$this->agendaPlanoImplantacao = new AgendaPlanoImplantacao($this->_request->getControllerName());
	}
	
	public function indexAction(){$this->initView();}
	
	/**
	 * Método que insere ou autualiza o plano de implantação 
	 */
	public function salvarPlanoImplantacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$arrRetorno = array('error'=>'','msg'=>'','acao'=>'');
		
		if($arrDados['acao'] === 'insert'){
			//inserir
			$novo										= $this->planoImplantacao->createRow();
			$novo->cd_projeto							= $arrDados['cd_projeto']; 
			$novo->cd_proposta							= $arrDados['cd_proposta'];
			$novo->tx_descricao_plano_implantacao		= $arrDados['tx_descricao_plano_implantacao'];
			$novo->cd_prof_plano_implantacao	= $_SESSION["oasis_logged"][0]["cd_profissional"];
			
			if($novo->save()) {
				$arrRetorno['error'] = false;
				$arrRetorno['msg'  ] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
				$arrRetorno['acao' ] = "update";
			} else {
				$arrRetorno['error'] = true;
				$arrRetorno['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
				$arrRetorno['acao' ] = "insert";
			}
			
		}else if($arrDados['acao'] === 'update'){
			//atualizar
			$where = "cd_projeto = {$arrDados['cd_projeto']} and cd_proposta = {$arrDados['cd_proposta']}";
			
			$arrUpdate['tx_descricao_plano_implantacao']	= $arrDados['tx_descricao_plano_implantacao'];
			$arrUpdate['cd_prof_plano_implantacao']	= $_SESSION["oasis_logged"][0]["cd_profissional"];
			
			if( $this->planoImplantacao->update( $arrUpdate, $where ) ){
				$arrRetorno['error'] = false;
				$arrRetorno['msg'  ] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
				$arrRetorno['acao' ] = "update";
				
			} else {
				$arrRetorno['error'] = true;
				$arrRetorno['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
				$arrRetorno['acao' ] = "update";
			}
		}else{
			$arrRetorno['error'] = true;
			$arrRetorno['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO');
		}
		
		echo Zend_Json_Encoder::encode($arrRetorno);
	}

	/**
	 * Método que salva um novo registro de agenda
	 */
	public function salvarAgendaPlanoImplantacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		$novo								= $this->agendaPlanoImplantacao->createRow();
		$novo->cd_projeto					= $arrDados['cd_projeto']; 
		$novo->cd_proposta					= $arrDados['cd_proposta'];
		$novo->tx_agenda_plano_implantacao	= $arrDados['tx_agenda_plano_implantacao'];
		$novo->dt_agenda_plano_implantacao	= new Zend_Db_Expr("{$this->agendaPlanoImplantacao->to_timestamp("'{$arrDados['dt_agenda_plano_implantacao']} ".date('H:i:s')."'", 'DD/MM/YYYY HH24:MI:SS')}");
		
		if($novo->save()) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}

	/**
	 * Método que altera os dados de uma agenda
	 */
	public function alterarAgendaPlanoImplantacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$where  = "cd_projeto					= {$arrDados['cd_projeto']} and ";
		$where .= "cd_proposta					= {$arrDados['cd_proposta']} and ";
		$where .= "dt_agenda_plano_implantacao	= '{$arrDados['hidden_dt_agenda_plano_implantacao']}'";
		
		$arrUpdate['tx_agenda_plano_implantacao']	= $arrDados['tx_agenda_plano_implantacao'];
		
		if( $this->agendaPlanoImplantacao->update( $arrUpdate, $where ) ){
			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}
	}

	/**
	 * Método que exclui uma agenda do plano de implantação
	 */
	public function excluirAgendaPlanoImplantacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$where  = "cd_projeto 					= {$arrDados['cd_projeto']} and ";
		$where .= "cd_proposta 					= {$arrDados['cd_proposta']} and ";
		$where .= "dt_agenda_plano_implantacao 	= '{$arrDados['dt_agenda']}'";
		
		if( $this->agendaPlanoImplantacao->delete( $where ) ){
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

	/**
	 * Método que recupera os dados de uma agenda específica para apresentar
	 * na tela para alteração
	 */
	public function recuperaDadosAgendaImplantacaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrDados = $this->_request->getPost();
		
		$arrDados = $this->agendaPlanoImplantacao->getAgendaImplantacao( $arrDados['cd_projeto'],$arrDados['cd_proposta'],$arrDados['dt_agenda'] );

        $arrDados[0]['dt_agenda_masked'] = date('d/m/Y', strtotime($arrDados[0]['dt_agenda_plano_implantacao']));
		echo Zend_Json::encode($arrDados[0]);
	}

	/**
	 * Método que recupera os dados para montagem da grid
	 */
	public function gridAgendaImplantacaoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  = $this->_request->getParam('cd_projeto');
		$cd_proposta = $this->_request->getParam('cd_proposta');
		
		$res			 = $this->agendaPlanoImplantacao->getAgendaImplantacao( $cd_projeto,$cd_proposta );
		$this->view->res = $res;
	}
}