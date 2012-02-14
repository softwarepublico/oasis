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

class ReuniaoProfissionalController extends Base_Controller_Action
{
	private $reuniaoProfissional;
	private $reuniao;	
	
	public function init()
	{
		parent::init();
		$this->projeto             = new Projeto($this->_request->getControllerName());
		$this->reuniaoProfissional = new ReuniaoProfissional($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
		// Utiliza o objeto select para definir um order by da consulta
		$selectProjeto = $this->projeto->select();
		$selectProjeto->order("tx_sigla_projeto");
		
		// Recupera os dados e armazena em um array
		$resProjeto = $this->projeto->fetchAll($selectProjeto);
		
		// Cria um array que manter� os valores do combobox
		$projetoCombo  = array(Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));

		// Percorre o resultset e adiciona os valores ao array que colocar� os valores no combobox
		// O Indice ser� o value do option do select e o valor sera o label do option do select
		foreach ($resProjeto as $nomeProjeto)
		{
			$projetoCombo[$nomeProjeto->cd_projeto] = $nomeProjeto->tx_sigla_projeto;
		}
		
		//  Associa este array com um atributo da camada de visao
		$this->view->projetoCombo = $projetoCombo;
	}

	public function pesquisaReuniaoProjetoAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		// Recupera os parametros enviados por get
		$cd_projeto = $this->_request->getParam('cd_projeto');

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_projeto == 0) {
			echo '';
		} else {
			/*
			 * Cria uma instancia do modelo e pesquisa pelas reunioes do contrato
			 */
			$this->reuniao = new Reuniao($this->_request->getControllerName());
			// Recordset das reunioes dos projetos
			$rsReuniaoProjeto = $this->reuniao->getReuniaoProjeto($cd_projeto);
			
			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			
			$arrReuniao = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			foreach ($rsReuniaoProjeto as $key=>$value) {
				$data = Base_Util::converterDate($value, 'YYYY-MM-DD', 'DD/MM/YYYY');
				$arrReuniao .= "<option value=\"{$key}\">{$data}</option>";
			}
			
			echo $arrReuniao;
		}
	}

	public function pesquisaProfissionalAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_reuniao = $this->_request->getParam('cd_reuniao');

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_reuniao == 0) {
			echo '';
		} else {
			/*
			 * Cria uma instancia do modelo e pesquisa pelos profissionais alocado ao projeto selecionado e aos profissionais que nao estao
			 * alocados ao mesmo 
			 */
			$profissional = new ReuniaoProfissional($this->_request->getControllerName());

			// Recordset de profissionais que nao se encontram no projeto selecionado
			$foraReuniao  = $profissional->pesquisaProfissionalForaReuniao($cd_reuniao);

			// Recordset de profissionais que se encontram no projeto selecionado
			$noReuniao  = $profissional->pesquisaProfissionalNoReuniao($cd_reuniao);
			
			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($foraReuniao as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_profissional']}\">{$fora['tx_profissional']}</option>";
			}

			$arr2 = "";
			foreach ($noReuniao as $no) {
				$arr2 .= "<option value=\"{$no['cd_profissional']}\">{$no['tx_profissional']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
		}
	}
	
	public function associaReuniaoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_reuniao    = $post['cd_reuniao'];
		$profissionais = Zend_Json_Decoder::decode($post['profissionais']);
		$cd_projeto    = $post['cd_projeto'];
		
		$profissionalReuniao = new ReuniaoProfissional($this->_request->getControllerName());
		
		foreach ($profissionais as $profissional) {
			$novo = $profissionalReuniao->createRow();
			$novo->cd_reuniao      = $cd_reuniao;
			$novo->cd_projeto      = $cd_projeto;
			$novo->cd_profissional = $profissional;
			$novo->save();
		}
	}
	
	public function desassociaReuniaoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_reuniao    = $post['cd_reuniao'];
		$profissionais = Zend_Json_Decoder::decode($post['profissionais']);
		$cd_projeto    = $post['cd_projeto'];
		
		$profissionalReuniao = new ReuniaoProfissional($this->_request->getControllerName());
		
		foreach ($profissionais as $profissional) {
			$where = "cd_reuniao={$cd_reuniao} and cd_projeto={$cd_projeto} and cd_profissional={$profissional}";
			$profissionalReuniao->delete($where);
		}
	}

	public function pesquisaReuniaoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_reuniao = (int)$this->_request->getParam('cd_reuniao', 0);
		$reuniaoProfissional = new ReuniaoProfissional($this->_request->getControllerName());
		$res = $reuniaoProfissional->getReuniaoProfissional($cd_reuniao);
		
		$strOptions = "<option value=\"\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		
		foreach ($res as $cd_profissional => $tx_profissional) {
			$strOptions .= "<option value=\"{$cd_profissional}\">{$tx_profissional}</option>";			
		}
		
		echo $strOptions;
	}
}