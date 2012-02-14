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

class SituacaoProjetoController extends Base_Controller_Action
{
	private $situacaoProjeto;
	private $objData;
	private $objUtil;

	public function init()
	{
		parent::init();
		$this->objData  = new Zend_Date();
		$this->objUtil = new Base_Controller_Action_Helper_Util();
	}

	public function salvarPosicionamentoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();

		$post['ni_mes_situacao_projeto'] = $this->objData->get(Zend_Date::MONTH);
		$post['ni_ano_situacao_projeto'] = $this->objData->get(Zend_Date::YEAR);

		$situacaoProjeto 			 = new SituacaoProjeto($this->_request->getControllerName());

		$select = $situacaoProjeto->select();
		$select->where("cd_projeto={$post['cd_projeto']}");
		$select->where("ni_mes_situacao_projeto={$post['ni_mes_situacao_projeto']}");
		$select->where("ni_ano_situacao_projeto={$post['ni_ano_situacao_projeto']}");

		$rowSituacaoProjeto = $situacaoProjeto->fetchRow($select);

		$erro = false;

		if ($rowSituacaoProjeto !== null) {
			// update
			$where  = "ni_mes_situacao_projeto={$post['ni_mes_situacao_projeto']}";
			$where .= " and ni_ano_situacao_projeto={$post['ni_ano_situacao_projeto']}";
			$where .= " and cd_projeto={$post['cd_projeto']}";

			$erro = $situacaoProjeto->update($post, $where);
		} else {
			// insert
			$erro = $situacaoProjeto->insert($post);
		}

		if ($erro !== false) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}

	public function ultimaSituacaoProjetoAction()
	{
		$this->_helper->layout->disableLayout();

		$situacaoProjeto = new SituacaoProjeto($this->_request->getControllerName());

		$cd_projeto      	 		 = $this->_request->getParam('cd_projeto');
		$mes_ano_selecionado 		 = $this->_request->getParam('mes_ano_selecionado');

		/*
		 * Verifica se os dados estao vindo para o mes corrente ou se eh para mes anterior ou posterior
		 */
		if ($mes_ano_selecionado === null) {
			$this->view->mesAtual 		 = $this->objData->get(Zend_Date::MONTH);
			$this->view->mesAtualExtenso = ucfirst($this->objData->get(Zend_Date::MONTH_NAME));
			$this->view->anoAtual 		 = $this->objData->get(Zend_Date::YEAR);
		} else {
			$arrDatas = explode("_", $mes_ano_selecionado);
			
			$mesAtual = $arrDatas[0];
			$anoAtual = $arrDatas[1];

			$this->view->mesAtual = $mesAtual;
			$this->view->anoAtual = $anoAtual;
		    $this->view->mesAtualExtenso = $this->_helper->util->getMes($mesAtual);
		}
		if (substr($this->view->mesAtual, 0, 1) == "0") {
			$this->view->mesAtual = substr($this->view->mesAtual, 1);
		}
		/*
		 * Pesquisa pelo mês anterior
		 */
		$selectMesAnterior = $situacaoProjeto->select();
		$selectMesAnterior->where("cd_projeto = ?", $cd_projeto);
		$selectMesAnterior->where("ni_ano_situacao_projeto||''||ni_mes_situacao_projeto < ?", $this->view->anoAtual.$this->view->mesAtual);
		$selectMesAnterior->order('ni_ano_situacao_projeto desc');
		$selectMesAnterior->order('ni_mes_situacao_projeto');
		$selectMesAnterior->limit(0,1);
		
		$rowMesAnterior = $situacaoProjeto->fetchRow($selectMesAnterior);
		
		if ($rowMesAnterior) {
			$this->view->mesAnterior = $rowMesAnterior->ni_mes_situacao_projeto;
			$this->view->anoAnterior = $rowMesAnterior->ni_ano_situacao_projeto;
		}
		
		/*
		 * Pesquisa pelo próximo mês
		 */
		$selectProximoMes = $situacaoProjeto->select();
		$selectProximoMes->where("cd_projeto = ?", $cd_projeto);
		$selectProximoMes->where("ni_ano_situacao_projeto||''||ni_mes_situacao_projeto > ?", $this->view->anoAtual.$this->view->mesAtual);
		$selectProximoMes->order('ni_ano_situacao_projeto desc');
		$selectProximoMes->order('ni_mes_situacao_projeto asc');
		$selectProximoMes->limit(0,1);
        
		$rowProximoMes = $situacaoProjeto->fetchRow($selectProximoMes);
		
		if ($rowProximoMes) {
			$this->view->proximoMes = $rowProximoMes->ni_mes_situacao_projeto;
			$this->view->proximoAno = $rowProximoMes->ni_ano_situacao_projeto;
		}

		$this->view->cd_projeto	 = $cd_projeto;
		$this->view->podeAlterar = false;
		if ($this->view->mesAtual == $this->objData->get(Zend_Date::MONTH)) {
			$this->view->podeAlterar = true;	
		}

		$select = $situacaoProjeto->select();
		$select->where("cd_projeto={$cd_projeto}");
		$select->where("ni_mes_situacao_projeto={$this->view->mesAtual}");
		$select->where("ni_ano_situacao_projeto={$this->view->anoAtual}");

		$rowSituacaoProjeto = $situacaoProjeto->fetchRow($select);
		
		$this->view->tx_situacao_projeto = Base_Util::getTranslator('L_MSG_ALERT_SITUACAO_PROJETO');

		$jaExiste = false;

		if ($rowSituacaoProjeto !== null) {
			$this->view->tx_situacao_projeto = $rowSituacaoProjeto->tx_situacao_projeto;
			$jaExiste = true;
		}

		$this->view->jaExiste = $jaExiste;
	}

	public function editarPosicionamentoAction()
	{
		$this->_helper->layout->disableLayout();

		$objSituacaoProjeto = new SituacaoProjeto();

		$post = $this->_request->getPost();

		$this->view->cd_projeto = $post['cd_projeto'];

		if( $post['operacao'] === 'N'){
			
			$this->view->mesAtualExtenso	 = ucfirst($this->objData->get(Zend_Date::MONTH_NAME));
			$this->view->anoAtual			 = $this->objData->get(Zend_Date::YEAR);
			$this->view->tx_situacao_projeto = '';

		}else if($post['operacao'] === 'E'){

			$result = $objSituacaoProjeto->find($post['cd_projeto'], $this->objData->get(Zend_Date::MONTH), $this->objData->get(Zend_Date::YEAR))->current();

			$this->view->mesAtualExtenso	 = $this->objUtil->getMes($result->ni_mes_situacao_projeto);
			$this->view->anoAtual			 = $result->ni_ano_situacao_projeto;
			$this->view->tx_situacao_projeto = $result->tx_situacao_projeto;
		}
	}
}