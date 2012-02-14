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

class CasoDeUsoDefinicaoController extends Base_Controller_Action
{
	private $casoDeUso;

	public function init()
	{
		parent::init();
		$this->casoDeUso  = new CasoDeUso($this->_request->getControllerName());
	}

	public function indexAction(){}
	
	/**
	 * Método executado por ajax que receberá as informações para salva-lás
	 */
    public function salvaDefinicaoProjetoAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost = $this->_request->getPost();
		
		$res = $this->casoDeUso->salvaDefinicaoCasoDeUso($arrPost);
		$msg = ($res) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		
		echo $msg;
	}
	
	/**
	 * Método executado por ajax para alterar os dados da tabela caso
	 * de uso. 
	 */
	public function alteraCasoDeUsoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		$arrDados = $this->casoDeUso->getUltimaVersaoCasoDeUso($arrPost['cd_caso_de_uso']);
		if(!is_null($arrDados['st_fechamento_caso_de_uso'])){
			echo Base_Util::getTranslator('L_MSG_ALERT_CASO_DE_USO_FECHADO_IMPOSSIVEL_ALTERAR');
		} else {
			$res = $this->casoDeUso->alteraCasoDeUsoProjeto($arrPost);
			$msg = ($res) ? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO') : Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
			
			echo $msg;
		}
	}

	/**
	 * Método execultado por ajax para excluir o caso de uso da tabela
	 */
	public function excluirCasoDeUsoProjetoAction()
	{
		$objInteracao   = new Interacao($this->_request->getControllerName());
		$objComplemento = new Complemento($this->_request->getControllerName());
		
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();		
		
		$cd_caso_de_uso        = $this->_request->getParam('cd_caso_de_uso');
		$dt_versao_caso_de_uso = $this->_request->getParam('dt_versao_caso_de_uso');

		// Verifica na tabela de interação se o caso de uso está sendo utilizados
		$arrInteracao = $objInteracao->recuperaInteracao(null, $cd_caso_de_uso,null,$dt_versao_caso_de_uso);
		
		 // Verifica na tabela de complemento se o caso de uso está sendo utilizados
		$arrComplemento = $objComplemento->recuperaComplemento(null,false, $cd_caso_de_uso, $dt_versao_caso_de_uso);

		if($arrInteracao){
			echo Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_INTERACOES');
		} else {
			if($arrComplemento){
				echo Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_COMPLEMENTOS');
			} else {
				$res = $this->casoDeUso->excluirCasoDeUsoProjeto($cd_caso_de_uso, $dt_versao_caso_de_uso);
				$msg = ($res) ? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
				echo $msg;
			}
		}
	}
	
	/**
	 * Método executado por ajax para recuperar o dado de um
	 * caso de uso especifico.
	 */
	public function recuperaDefinicaoProjetoAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_caso_de_uso        = $this->_request->getParam('cd_caso_de_uso');
		$cd_modulo             = $this->_request->getParam('cd_modulo');
		$cd_projeto            = $this->_request->getParam('cd_projeto');
		$dt_versao_caso_de_uso = $this->_request->getParam('dt_versao_caso_de_uso');
		
		$res = $this->casoDeUso->recuperaCasoDeUsoProjeto($cd_caso_de_uso,$cd_projeto,$cd_modulo,true,$dt_versao_caso_de_uso);
		
		echo Zend_Json::encode($res);
	}
	
	/**
	 * Método que realiza a pesquisa nos casos de usos do projeto
	 * e montará uma grid com os dados recuperados.
	 */
	public function pesquisaDefinicaoProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$cd_projeto = $this->_request->getParam('cd_projeto');
		$cd_modulo = $this->_request->getParam('cd_modulo');
		$cd_modulo = ($cd_modulo)?$cd_modulo:null;
		// realiza a consulta
		$res = $this->casoDeUso->pesquisaCasoDeUsoProjeto($cd_projeto,$cd_modulo);
		
		$this->view->res = $res;
	}
}