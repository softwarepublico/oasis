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

class SuspensaoPropostaController extends Base_Controller_Action
{
	private $objContratoProjeto;
	private $objContrato;
	private $objProposta;
	private $objParcela;

	public function init()
	{
		parent::init();
		$this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
		$this->objContrato        = new Contrato($this->_request->getControllerName());
		$this->objProposta        = new Proposta($this->_request->getControllerName());
		$this->objParcela         = new Parcela($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
		$this->view->arrContratoProjeto = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', null, false, 'A');
	}

	public function pesquisaPropostaAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_contrato              = $this->_request->getParam('cd_contrato',0);

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_contrato == -1) {
			echo '';
		} else {

			// projeto em execução, com propostas não encerradas
            $emExecucao  = $this->objProposta->getPropostasSuspensaoProposta($cd_contrato);

			// projetos sem proposta encerrada mas com o flag de suspenso
			$suspenso    = $this->objProposta->getPropostasSuspensaoProposta($cd_contrato,'S');

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";
			foreach ($emExecucao as $key => $projetoProposta) {
				$arr1 .= "<option value=\"{$key}\">{$projetoProposta}</option>";
			}

			$arr2 = "";
			foreach ($suspenso as $key => $projetoProposta) {
				$arr2 .= "<option value=\"{$key}\">{$projetoProposta}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
		}
	}
	
	public function suspendePropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		$cd_contrato        = $post['cd_contrato'];
		$propostas          = Zend_Json_Decoder::decode(str_ireplace("\\","",$post['propostas']));
		
		$arrDados = array();
		foreach ($propostas as $proposta) {
			if ($erros === false) {
				$arrProjetoProposta                 = explode('_', $proposta);
				$arrUpdate['st_suspensao_proposta'] = 'S';
				$erros = $this->objProposta->atualizaProposta($arrProjetoProposta[0], $arrProjetoProposta[1], $arrUpdate);
				if ($erros === false) {
					$erros = $this->alteraPropostaSuspensaoProposta($arrProjetoProposta[0], $arrProjetoProposta[1], 1);
				}
			}
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_GRAVAR_SUSPENSAO_PROPOSTA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_GRAVAR_SUSPENSAO_PROPOSTA');
		}
	}
	
	public function retiraSuspensaoPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

		$cd_contrato        = $post['cd_contrato'];
		$propostas          = Zend_Json_Decoder::decode(str_ireplace("\\","",$post['propostas']));

		$arrDados = array();
		foreach ($propostas as $proposta) {
			if ($erros === false) {
				$arrProjetoProposta                 = explode('_', $proposta);
				$arrUpdate['st_suspensao_proposta'] = null;
				$erros = $this->objProposta->atualizaProposta($arrProjetoProposta[0], $arrProjetoProposta[1], $arrUpdate);
				if ($erros === false) {
					$erros = $this->alteraPropostaSuspensaoProposta($arrProjetoProposta[0], $arrProjetoProposta[1], 2);
				}
			}
		}

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_GRAVAR_RETIRADA_SUSPENSAO_PROPOSTA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_GRAVAR_RETIRADA_SUSPENSAO_PROPOSTA');
		}
	}

	public function alteraPropostaSuspensaoProposta($cd_projeto, $cd_proposta, $tipo)
	{
		$erros = false;

		$select = $this->objParcela->select()
		->where("cd_projeto = ?", $cd_projeto, Zend_Db::INT_TYPE)
		->where("cd_proposta = ?", $cd_proposta, Zend_Db::INT_TYPE)
		->where("ni_mes_execucao_parcela is null", $cd_projeto, Zend_Db::INT_TYPE)
		->where("ni_ano_execucao_parcela is null", $cd_projeto, Zend_Db::INT_TYPE)
		->order("ni_parcela");

		$arrParcelas = $this->objParcela->fetchAll($select);
		
		//Suspensão da Proposta
		if ($tipo == 1) {
			$arrUpdate["ni_mes_previsao_parcela"] = null;
			$arrUpdate["ni_ano_previsao_parcela"] = null;
			foreach($arrParcelas as $parcela){
				if ($erros === false) {
					$where = "cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$parcela->cd_parcela}";
					if (!$this->objParcela->update($arrUpdate, $where)) {
						$erros = true;
					}
				}
			}
		}
		//Retira Suspensão da Proposta
		if ($tipo == 2) {
			$arrUpdate["ni_mes_previsao_parcela"] = date('n');
			$arrUpdate["ni_ano_previsao_parcela"] = date('Y');
			foreach($arrParcelas as $parcela){
				if ($erros === false) {
					$where = "cd_projeto = {$cd_projeto} and cd_proposta = {$cd_proposta} and cd_parcela = {$parcela->cd_parcela}";
					if (!$this->objParcela->update($arrUpdate, $where)) {
						$erros = true;
					}
					$arrUpdate["ni_mes_previsao_parcela"]      = $arrUpdate["ni_mes_previsao_parcela"] + 1;
					if ($arrUpdate["ni_mes_previsao_parcela"] == 13) {
						$arrUpdate["ni_mes_previsao_parcela"]  = 1;
						$arrUpdate["ni_ano_previsao_parcela"]  = $arrUpdate["ni_ano_previsao_parcela"] + 1;
					}
				}
			}
		}
		return $erros;
	}
}