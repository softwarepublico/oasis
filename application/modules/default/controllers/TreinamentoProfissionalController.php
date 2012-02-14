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

class TreinamentoProfissionalController extends Base_Controller_Action 
{
	private $treinamentoProfissional;
	private $objetoContrato;
	private $treinamento;
	
	public function init()
	{
		parent::init();
		$this->treinamentoProfissional	= new TreinamentoProfissional($this->_request->getControllerName());
		$this->objetoContrato			= new ObjetoContrato($this->_request->getControllerName());
		$this->treinamento				= new Treinamento($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->view->arrTipoObjetoContrato	= $this->objetoContrato->getObjetoContratoAtivo(null,true,false);
		$this->view->arrTreinamento			= $this->treinamento->getTreinamento(true);

        $this->view->headTitle(Base_Util::setTitle('L_TIT_TREINAMENTO_PROFISSIONAL'));
	}

	/**
	 * Método que pesquisa os profissionais para preenchimento dos selects multiplos
	 */
	public function pesquisaTreinamentoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
				
		// Recupera os parametros enviados por get
		$cd_objeto              = $this->_request->getParam('cd_objeto');
		$cd_treinamento         = $this->_request->getParam('cd_treinamento');
		
		if ($cd_objeto == -1) {
			echo '';
		} else {
			
			// Recordset de profissionais que nao se encontram no projeto selecionado
			$foraTreinamento  = $this->treinamento->pesquisaProfissionalForaTreinamento($cd_objeto,false,$cd_treinamento);

			// Recordset de profissionais que se encontram no projeto selecionado
			$noTreinamento  = $this->treinamento->pesquisaProfissionalNoTreinamento($cd_objeto,false,$cd_treinamento);

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($foraTreinamento as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_profissional']}\">{$fora['tx_profissional']}</option>";
			}

			$arr2 = "";
			foreach ($noTreinamento as $no) {
				$arr2 .= "<option value=\"{$no['cd_profissional']}\">{$no['tx_profissional']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
			
		}
	}
	
	/**
	 * Método que adiciona um profissional a um determinado treinamento
	 */
	public function associaTreinamentoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_treinamento = $post['cd_treinamento'];
		$profissionais  = Zend_Json_Decoder::decode($post['profissionais']);
		
		$treinamentoProfissional = new TreinamentoProfissional($this->_request->getControllerName());
		
		$arrDados = array();
		foreach ($profissionais as $profissional) {
			$novo = $treinamentoProfissional->createRow();
			$novo->cd_profissional				= $profissional;
			$novo->cd_treinamento				= $cd_treinamento; 
			$novo->save();
		}
	}	
	
	/**
	 * Método que exclui um profissional do treinamento
	 */
	public function desassociaTreinamentoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
				
		$cd_treinamento			 = $post['cd_treinamento'];
		$profissionais			 = Zend_Json_Decoder::decode($post['profissionais']);
		$treinamentoProfissional = new TreinamentoProfissional($this->_request->getControllerName());
		
		$arrDados = array();
		
		foreach ($profissionais as $profissional) {
			$where = "cd_treinamento={$cd_treinamento} and cd_profissional={$profissional}";
			$treinamentoProfissional->delete($where);
		}
		
	}
	
	/**
	 * Método que recupera os proficionais assoiciados a um determinado treinamento
	 * para montar a grid que possibilita o cadastramento da data do treinamento
	 */
	public function gridTreinamentoProfissionalAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_objeto       = $this->_request->getParam('cd_objeto');
		$cd_treinamento  = $this->_request->getParam('cd_treinamento');
		$res			 = $this->treinamento->pesquisaProfissionalNoTreinamentoParaGrid($cd_objeto,false,$cd_treinamento);
        
		$this->view->res     = $res;
	}

	/**
	 * Método que salva a data de treinamento de um profissional
	 */
	public function salvarDataTreinamentoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		$arrUpdate['dt_treinamento_profissional'] = $arrDados['dt_treinamento'];
		$where = "cd_treinamento = {$arrDados['cd_treinamento']} and cd_profissional = {$arrDados['cd_profissional']}"; 
		if( $this->treinamentoProfissional->update( $arrUpdate, $where) ){
			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}		
	}
}