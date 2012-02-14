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

class CasoDeUsoComplementoController extends Base_Controller_Action
{
	private $objComplemento;
	private $casoDeUso;

	public function init()
	{
		parent::init();
		$this->objComplemento = new Complemento($this->_request->getControllerName());
		$this->casoDeUso      = new CasoDeUso($this->_request->getControllerName());
	}

	public function indexAction()
	{}
	
	/**
	 * Método desenvolvido para validar os dados e enviar para 
	 * a classe que salvará os dados na tabela
	 */
	
	public function salvarComplementoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		$arrDados = $this->casoDeUso->getUltimaVersaoCasoDeUso($arrPost['cd_caso_de_uso_complemento']);
		if(!is_null($arrDados['st_fechamento_caso_de_uso'])){
			echo Base_Util::getTranslator('L_MSG_ALERT_CASO_DE_USO_FECHADO_IMPOSSIVEL_INCLUIR_ALTERAR_COMPLEMENTOS');
		} else {
			$arrPost['dt_versao_caso_de_uso'] = $arrDados['dt_versao_caso_de_uso'];
			$arrPost['cd_modulo']             = $arrPost['cd_modulo_complemento'];
			$arrPost['cd_caso_de_uso']        = $arrPost['cd_caso_de_uso_complemento'];
			
			unset($arrDados); 
			unset($arrPost['cd_modulo_complemento']);
			unset($arrPost['cd_caso_de_uso_complemento']);
			
			$res = $this->objComplemento->salvarComplemento($arrPost);
			$msg = ( $res ) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
			echo $msg;
		}
	}
	
	public function alterarComplementoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
			$arrPost = $this->_request->getPost();
		
		$arrDados = $this->casoDeUso->getUltimaVersaoCasoDeUso($arrPost['cd_caso_de_uso_complemento']);
		if(!is_null($arrDados['st_fechamento_caso_de_uso'])){
			echo Base_Util::getTranslator('L_MSG_ALERT_CASO_DE_USO_FECHADO_IMPOSSIVEL_INCLUIR_ALTERAR_COMPLEMENTOS');
		} else {
			$arrPost['dt_versao_caso_de_uso'] = $arrDados['dt_versao_caso_de_uso'];
			$arrPost['cd_modulo']             = $arrPost['cd_modulo_complemento'];
			$arrPost['cd_caso_de_uso']        = $arrPost['cd_caso_de_uso_complemento'];
			
			unset($arrDados); 
			unset($arrPost['cd_modulo_complemento']);
			unset($arrPost['cd_caso_de_uso_complemento']);
		
			$res = $this->objComplemento->alterarComplemento($arrPost);
			$msg = ( $res ) ? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO') : Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
			echo $msg;
		}
	}

	public function excluirComplementoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_complemento        = $this->_request->getParam('cd_complemento');
		$dt_versao_caso_de_uso = $this->_request->getParam('dt_versao_caso_de_uso');
		
		$msg = $this->objComplemento->excluirComplemento($cd_complemento, $dt_versao_caso_de_uso);
		$msg = ( $msg ) ? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		echo $msg;
	}
	
	
	public function recuperaComplementoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_complemento        = $this->_request->getParam('cd_complemento');
		$dt_versao_caso_de_uso = $this->_request->getParam('dt_versao_caso_de_uso');
		
		$arrComplemento = $this->objComplemento->recuperaComplemento($cd_complemento,null,null,$dt_versao_caso_de_uso);
		
		echo Zend_Json::encode($arrComplemento);
	}
	
	public function gridComplementoExececaoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto     = $this->_request->getParam('cd_projeto');
		$cd_modulo      = $this->_request->getParam('cd_modulo');
		$cd_caso_de_uso = $this->_request->getParam('cd_caso_de_uso');
		
		$arrExcercao = $this->objComplemento->gridComplemento($cd_projeto,$cd_modulo,$cd_caso_de_uso,"E");
		$this->view->res = $arrExcercao;
	}

	public function gridComplementoRegraAction()
    {
		$this->_helper->layout->disableLayout();
		
		$cd_projeto     = $this->_request->getParam('cd_projeto');
		$cd_modulo      = $this->_request->getParam('cd_modulo');
		$cd_caso_de_uso = $this->_request->getParam('cd_caso_de_uso');
		
		$arrRegra = $this->objComplemento->gridComplemento($cd_projeto,$cd_modulo,$cd_caso_de_uso,"R");
		
		$this->view->res = $arrRegra;
	}

	public function gridComplementoFluxoAlternativoAction()
    {
		$this->_helper->layout->disableLayout();
		
		$cd_projeto     = $this->_request->getParam('cd_projeto');
		$cd_modulo      = $this->_request->getParam('cd_modulo');
		$cd_caso_de_uso = $this->_request->getParam('cd_caso_de_uso');

		$arrFluxoAlternativo = $this->objComplemento->gridComplemento($cd_projeto,$cd_modulo,$cd_caso_de_uso,"F");
		$this->view->res = $arrFluxoAlternativo;
	}
}