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

class ProjetoPrevistoController extends Base_Controller_Action
{
	private $projetoPrevisto;

	public function init()
	{
		parent::init();
		$this->projetoPrevisto = new ProjetoPrevisto($this->_request->getControllerName());
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData = $this->_request->getPost();

		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			if(!empty($formData['cd_projeto_previsto'])) {
				$novo             = $this->projetoPrevisto->fetchRow("cd_projeto_previsto = {$formData['cd_projeto_previsto']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
			} else {
				$novo                      = $this->projetoPrevisto->createRow();
				$novo->cd_projeto_previsto = $this->projetoPrevisto->getNextValueOfField('cd_projeto_previsto');
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}

			$novo->cd_contrato                   = $formData['cd_contrato_projeto_previsto'					];
			$novo->tx_projeto_previsto           = $formData['tx_projeto_previsto'							];
			$novo->ni_horas_projeto_previsto     = $formData['ni_horas_projeto_previsto'					];
			$novo->tx_descricao_projeto_previsto = ($formData['tx_descricao_projeto_previsto'				]) ? $formData['tx_descricao_projeto_previsto'					] : null ;
			$novo->cd_unidade                    = ($formData['cd_unidade_projeto_previsto'					]) ? $formData['cd_unidade_projeto_previsto'					] : null;
			$novo->st_projeto_previsto           = ($formData['st_projeto_previsto'							]) ? $formData['st_projeto_previsto'							] : null;
			$novo->cd_definicao_metrica			 = ($formData['cd_metrica_unidade_prevista_projeto_previsto']) ? $formData['cd_metrica_unidade_prevista_projeto_previsto'	] : null;

			if($novo->save()){
				$db->commit();
			} else {
				$db->rollBack();
			}
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg'] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function recuperaProjetoPrevistoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto_previsto = $this->_request->getParam('cd_projeto_previsto');

		$row = $this->projetoPrevisto->getRowProjetoPrevisto($cd_projeto_previsto);
		echo Zend_Json::encode($row->toArray());
	}

	public function excluirAction()
	{
	
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto_previsto = (int)$this->_request->getParam('cd_projeto_previsto', 0);
		if ($this->projetoPrevisto->delete("cd_projeto_previsto = {$cd_projeto_previsto}")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridProjetoPrevistoAction($cd_contrato = null)
	{
		if(is_null($cd_contrato)){
			$this->_helper->layout->disableLayout();
			// recupera o parametro
			$cd_contrato = $this->_request->getParam('cd_contrato', 0);
		}
		
		$rowSetProjetoPrevisto = $this->projetoPrevisto->getGridProjetoPrevisto($cd_contrato);
		$this->view->res    = $rowSetProjetoPrevisto;
	}

	
	public function pesquisaProjetoPrevistoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato = (int)$this->_request->getParam('cd_contrato', 0);
		$projetoPrevisto = new ProjetoPrevisto($this->_request->getControllerName());
		$res = $projetoPrevisto->getProjetoPrevisto($cd_contrato);

		$strOptions = "<option value=\"\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		foreach ($res as $cd_projeto_previsto => $tx_projeto_previsto) {
			$strOptions .= "<option value=\"{$cd_projeto_previsto}\">{$tx_projeto_previsto}</option>";
		}
		echo $strOptions;
	}	
}