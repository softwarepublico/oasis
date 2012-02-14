<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Demanda, objetos e Serviços de TI.
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

class ReuniaoGeralProfissionalController extends Base_Controller_Action
{
	private $reuniaoGeralProfissional;
	private $reuniaoGeral;
	
	public function init()
	{
		parent::init();
		$this->objeto                   = new ObjetoContrato($this->_request->getControllerName());
		$this->reuniaoGeralProfissional = new ReuniaoGeralProfissional($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
		// Utiliza o objeto select para definir um order by da consulta
		$selectObjeto = $this->objeto->select();
		$selectObjeto->order("tx_objeto");

        // Recupera os dados e armazena em um array
		$resObjeto = $this->objeto->fetchAll($selectObjeto);
		
		// Cria um array que manter� os valores do combobox
		$objetoCombo  = array(Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'));

		// Percorre o resultset e adiciona os valores ao array que colocar� os valores no combobox
		// O Indice ser� o value do option do select e o valor sera o label do option do select
		foreach ($resObjeto as $nomeObjeto)
		{
			$objetoCombo[$nomeObjeto->cd_objeto] = $nomeObjeto->tx_objeto;
		}
		
		//  Associa este array com um atributo da camada de visao
		$this->view->objetoCombo = $objetoCombo;
	}

	public function pesquisaReuniaoGeralObjetoAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_objeto = $this->_request->getParam('cd_objeto');

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_objeto == 0) {
			echo '';
		} else {
			/*
			 * Cria uma instancia do modelo e pesquisa pelas reunioes do contrato
			 */
			$this->reuniaoGeral = new ReuniaoGeral($this->_request->getControllerName());
			// Recordset das reunioes dos objetos
			$rsReuniaoGeralObjeto = $this->reuniaoGeral->getReuniaoGeralObjeto($cd_objeto);
			
			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			
			$arrReuniaoGeral = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			foreach ($rsReuniaoGeralObjeto as $key=>$value) {
				$data = Base_Util::converterDate($value, 'YYYY-MM-DD', 'DD/MM/YYYY');
				$arrReuniaoGeral .= "<option value=\"{$key}\">{$data}</option>";
			}
			
			echo $arrReuniaoGeral;
		}
	}

	public function pesquisaProfissionalAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        // Recupera os parametros enviados por get
		$cd_reuniao_geral = $this->_request->getParam('cd_reuniao_geral');

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_reuniao_geral == 0) {
			echo '';
		} else {
			/*
			 * Cria uma instancia do modelo e pesquisa pelos profissionais alocado ao objeto selecionado e aos profissionais que nao estao
			 * alocados ao mesmo 
			 */
			$profissional = new ReuniaoGeralProfissional($this->_request->getControllerName());

			// Recordset de profissionais que nao se encontram no objeto selecionado
			$foraReuniaoGeral  = $profissional->pesquisaProfissionalForaReuniaoGeral($cd_reuniao_geral);

			// Recordset de profissionais que se encontram no objeto selecionado
			$noReuniaoGeral  = $profissional->pesquisaProfissionalNoReuniaoGeral($cd_reuniao_geral);
			
			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($foraReuniaoGeral as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_profissional']}\">{$fora['tx_profissional']}</option>";
			}

			$arr2 = "";
			foreach ($noReuniaoGeral as $no) {
				$arr2 .= "<option value=\"{$no['cd_profissional']}\">{$no['tx_profissional']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
		}
	}
	
	public function associaReuniaoGeralProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_reuniao_geral    = $post['cd_reuniao_geral'];
		$profissionais = Zend_Json_Decoder::decode($post['profissionais']);
		$cd_objeto    = $post['cd_objeto'];
		
		$profissionalReuniaoGeral = new ReuniaoGeralProfissional($this->_request->getControllerName());
		
		foreach ($profissionais as $profissional) {
			$novo = $profissionalReuniaoGeral->createRow();
			$novo->cd_reuniao_geral = $cd_reuniao_geral;
			$novo->cd_objeto        = $cd_objeto;
			$novo->cd_profissional  = $profissional;
			$novo->save();
		}
	}
	
	public function desassociaReuniaoGeralProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_reuniao_geral    = $post['cd_reuniao_geral'];
		$profissionais = Zend_Json_Decoder::decode($post['profissionais']);
		$cd_objeto    = $post['cd_objeto'];
		
		$profissionalReuniaoGeral = new ReuniaoGeralProfissional($this->_request->getControllerName());
		
		foreach ($profissionais as $profissional) {
			$where = "cd_reuniao_geral={$cd_reuniao_geral} and cd_objeto={$cd_objeto} and cd_profissional={$profissional}";
			$profissionalReuniaoGeral->delete($where);
		}
	}

	public function pesquisaReuniaoGeralProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_reuniao_geral = (int)$this->_request->getParam('cd_reuniao_geral', 0);
		$reuniaoGeralProfissional = new ReuniaoGeralProfissional($this->_request->getControllerName());
		$res = $reuniaoGeralProfissional->getReuniaoGeralProfissional($cd_reuniao_geral);
		
		$strOptions = "<option value=\"\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		
		foreach ($res as $cd_profissional => $tx_profissional) {
			$strOptions .= "<option value=\"{$cd_profissional}\">{$tx_profissional}</option>";			
		}
		
		echo $strOptions;
	}
}