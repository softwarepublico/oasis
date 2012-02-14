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

class CasoDeUsoInteracaoController extends Base_Controller_Action
{
	private $interacao;
	private $casoDeUso;
	
	public function init()
	{
		parent::init();
		$this->interacao = new Interacao($this->_request->getControllerName());
		$this->casoDeUso = new CasoDeUso($this->_request->getControllerName());
	}

	public function indexAction()
	{}
	
	/**
	 * Método utilizado por ajax para cadastrar os dados na tabela de interação
	 * 
	 * @author: Wunilberto Melo
	 * @since:  07/10/2008
	 * @return: $msg
	 */
	public function salvarInteracaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost = $this->_request->getPost();
		
		$arrDados = $this->casoDeUso->getUltimaVersaoCasoDeUso($arrPost['cd_caso_de_uso_combo']);
		if(!is_null($arrDados['st_fechamento_caso_de_uso'])){
			echo Base_Util::getTranslator('L_MSG_ALERT_CASO_DE_USO_FECHADO_IMPOSSIVEL_INCLUIR_ALTERAR_INTERACOES');

		} else {
			$arrPost['dt_versao_caso_de_uso'] = $arrDados['dt_versao_caso_de_uso'];
			$arrPost['cd_modulo']             = $arrPost['cd_modulo_interacao'];
			$arrPost['cd_caso_de_uso']        = $arrPost['cd_caso_de_uso_combo'];
			$arrPost['cd_ator']               = $arrPost['cd_ator_combo'];
			
			unset($arrDados); 
			unset($arrPost['cd_modulo_interacao']);
			unset($arrPost['cd_caso_de_uso_combo']);
			unset($arrPost['cd_ator_combo']);
			
			$res = $this->interacao->salvarInteracao($arrPost);
			$msg = ( $res ) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
	
			echo $msg;
		}
	}
	
	public function alterarInteracaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();				
		
		$arrDados = $this->casoDeUso->getUltimaVersaoCasoDeUso($arrPost['cd_caso_de_uso_combo']);
		if(!is_null($arrDados['st_fechamento_caso_de_uso'])){
			echo Base_Util::getTranslator('L_MSG_ALERT_CASO_DE_USO_FECHADO_IMPOSSIVEL_INCLUIR_ALTERAR_INTERACOES');
		} else {
			$arrPost['dt_versao_caso_de_uso'] = $arrDados['dt_versao_caso_de_uso'];
			$arrPost['cd_modulo']             = $arrPost['cd_modulo_interacao'];
			$arrPost['cd_caso_de_uso']        = $arrPost['cd_caso_de_uso_combo'];
			$arrPost['cd_ator']               = $arrPost['cd_ator_combo'];			
			
			unset($arrDados); 
			unset($arrPost['cd_modulo_interacao']);
			unset($arrPost['cd_caso_de_uso_combo']);
			unset($arrPost['cd_ator_combo']);
			unset($arrPost['dt_versao_caso_de_uso_interacao']);
								
			$res = $this->interacao->alterarInteracao($arrPost);
			$msg = ($res) ? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO') : Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
			echo $msg;
		}
	}
	
	public function excluirInteracaoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_interacao          = $this->_request->getParam('cd_interacao');
		$dt_versao_caso_de_uso = $this->_request->getParam('dt_versao_caso_de_uso');
		
		$msg = $this->interacao->excluirInteracao($cd_interacao, $dt_versao_caso_de_uso);
		$msg = ($msg)? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function pesquisaInteracaoProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$cd_projeto     = (int)$this->_request->getParam('cd_projeto');
		$cd_modulo      = (int)$this->_request->getParam('cd_modulo');
		$cd_caso_de_uso = (int)$this->_request->getParam('cd_caso_de_uso');

		$arrDados = $this->interacao->pesquisaInteracao($cd_projeto,$cd_modulo,$cd_caso_de_uso);
		$this->view->res = $arrDados;
	}
	
	public function recuperaInteracaoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_interacao          = $this->_request->getParam('cd_interacao');
		$dt_versao_caso_de_uso = $this->_request->getParam('dt_versao_caso_de_uso');
		
		$arrDados = $this->interacao->recuperaInteracao($cd_interacao,null,null,$dt_versao_caso_de_uso);
		
		echo Zend_Json::encode($arrDados);
	}
}