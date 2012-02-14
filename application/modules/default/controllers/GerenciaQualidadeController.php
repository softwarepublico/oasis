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

class GerenciaQualidadeController extends Base_Controller_Action
{
	private $objGerenciaQualidade;
	
	public function init()
	{
		parent::init();
		$this->objGerenciaQualidade = new GerenciaQualidade($this->_request->getControllerName());
	}
	
	public function indexAction()
	{}
	
	public function gridGerenciaQualidadeAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  = $this->_request->getParam('cd_projeto');
		$cd_proposta = $this->_request->getParam('cd_proposta');
		
		$arrDados = $this->objGerenciaQualidade->getDadosGerenciaQualidade($cd_projeto,$cd_proposta);
		$this->view->res = $arrDados; 
	}

	/**
	 * Método que salva os dados as fases da gerencia de qualidade
	 * @autor: Wunilberto
	 * @since: 30/06/2009
	 */
	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrGerenciaQualidade = $this->_request->getPost();
        
        $arrGerenciaQualidade['cd_projeto'            ] = $arrGerenciaQualidade['cd_projeto_qualidade'];
        $arrGerenciaQualidade['cd_proposta'           ] = $arrGerenciaQualidade['cd_proposta_qualidade'];
        $arrGerenciaQualidade['dt_auditoria_qualidade'] = Base_Util::converterDate($arrGerenciaQualidade['dt_auditoria_qualidade'], 'DD/MM/YYYY', 'YYYY-MM-DD');

        unset($arrGerenciaQualidade['cd_projeto_qualidade']);
        unset($arrGerenciaQualidade['cd_proposta_qualidade']);

		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			if(!empty($arrGerenciaQualidade['cd_gerencia_qualidade'])) {
				$erro = $this->objGerenciaQualidade->alteraGerenciaQualidade($arrGerenciaQualidade);
				if($erro){
					throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
				} else {
					$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
				}
			} else {
				$erro = $this->objGerenciaQualidade->salvarGerenciaQualidade($arrGerenciaQualidade);
				if($erro){
					throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
				} else {
					$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
				}
			}
			if($erro){
				$db->rollBack();
			} else {
				$db->commit();
			}
		}catch(Base_Exception_Alert $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg' ] = $e->getMessage();
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
	
	/*
	 * Método que excluí as informações da tabela
	 * @author: Wunilberto.Melo
	 * @since: 30/06/2009
	 */
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost = $this->_request->getPost();

		$where = "cd_gerencia_qualidade = {$arrPost['cd_gerencia_qualidade']} and
				  cd_projeto = {$arrPost['cd_projeto']} and
				  cd_proposta = {$arrPost['cd_proposta']}";
		if($this->objGerenciaQualidade->delete($where)){
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

	public function recuperaGerenciamentoQualidadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost = $this->_request->getPost();
		$rowSetGerenciaQualidade = $this->objGerenciaQualidade->getDadosGerenciaQualidade($arrPost['cd_projeto'], $arrPost['cd_proposta'], $arrPost['cd_gerencia_qualidade']);
        $arrDados = $rowSetGerenciaQualidade->getRow(0)->toArray();

        $arrDados['dt_auditoria_qualidade'] = date('d/m/Y',strtotime($arrDados['dt_auditoria_qualidade']));

		echo Zend_Json::encode($arrDados);
	}
}