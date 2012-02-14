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

class AssociarProfissionalProjetoController extends Base_Controller_Action
{
    private $_objProfissionalProjeto;
    private $_objProjeto;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ASSOCIAR_PROFISSIONAL'));

        $this->_objProfissionalProjeto = new ProfissionalProjeto($this->_request->getControllerName());
		$this->_objProjeto             = new Projeto($this->_request->getControllerName());
	}

	public function indexAction()
	{
		// Recupera os dados e armazena em um array
		$resProjeto    = $this->_objProjeto->fetchAll(null, 'tx_sigla_projeto');

		// Cria um array que manterá os valores do combobox
		$projetoCombo  = array(Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));

		// Percorre o resultset e adiciona os valores ao array que colocar� os valores no combobox
		// O Indice ser� o value do option do select e o valor sera o label do option do select
		foreach ($resProjeto as $nomeProjeto){
			$projetoCombo[$nomeProjeto->cd_projeto] = $nomeProjeto->tx_sigla_projeto;
		}
		
		//  Associa este array com um atributo da camada de visao
		$this->view->projetoCombo = $projetoCombo;
	}

	public function pesquisaProfissionalAction()
	{

		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_projeto 			= $this->_request->getParam('cd_projeto');
		$cd_papel_profissional  = $this->_request->getParam('cd_papel_profissional');
		$cd_objeto              = $_SESSION["oasis_logged"][0]["cd_objeto"];

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_projeto == 0) {
			echo '';
		} else {
			
			// Recordset de profissionais que nao se encontram no projeto selecionado
			$foraProjeto  = $this->_objProfissionalProjeto->pesquisaProfissionalForaProjeto($cd_objeto, $cd_projeto, $cd_papel_profissional);

			// Recordset de profissionais que se encontram no projeto selecionado
			$noProjeto  = $this->_objProfissionalProjeto->pesquisaProfissionalNoProjeto($cd_objeto, $cd_projeto, $cd_papel_profissional);

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($foraProjeto as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_profissional']}\">{$fora['tx_profissional']}</option>";
			}

			$arr2 = "";
			foreach ($noProjeto as $no) {
				$arr2 .= "<option value=\"{$no['cd_profissional']}\">{$no['tx_profissional']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
		}
	}
	
	public function associaProfissionalProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_projeto            = $post['cd_projeto'];
		$cd_papel_profissional = $post['cd_papel_profissional'];
		$profissionais         = Zend_Json_Decoder::decode($post['profissionais']);
		
		$arrDados = array();
		
		foreach ($profissionais as $profissional) {
			$novo = $this->_objProfissionalProjeto->createRow();
			$novo->cd_projeto            = $cd_projeto;
			$novo->cd_papel_profissional = $cd_papel_profissional;
			$novo->cd_profissional       = $profissional;
			$novo->save();
		}
	}
	
	public function desassociaProfissionalProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_projeto            = $post['cd_projeto'];
		$cd_papel_profissional = $post['cd_papel_profissional'];
		$profissionais         = Zend_Json_Decoder::decode($post['profissionais']);
		
		$arrDados = array();
		
		foreach ($profissionais as $profissional) {
			$where = "cd_projeto={$cd_projeto} and cd_profissional={$profissional} and cd_papel_profissional = {$cd_papel_profissional}";
			$this->_objProfissionalProjeto->delete($where);
		}
	}

    public function gridProfissionalPapelProfissionalAction()
    {
        $this->_helper->layout->disableLayout();

        $cd_projeto 			= $this->_request->getParam('cd_projeto');
		$cd_objeto              = $_SESSION["oasis_logged"][0]["cd_objeto"];

        $this->view->res = $this->_objProfissionalProjeto->getProfissionalPapelProfissionalOrderProfissional($cd_objeto, $cd_projeto, true);

    }
}