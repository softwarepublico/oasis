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

class AtividadeController extends Base_Controller_Action
{
	private $atividade;
	private $objEtapa;
	
	public function init()
	{
		parent::init();
		$this->atividade = new Atividade($this->_request->getControllerName());
		$this->objEtapa  = new Etapa($this->_request->getControllerName());
	}

	public function indexAction()
	{}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData  = $this->_request->getPost();
		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
        
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            //altera e exlui o indice do array para ficar igual ao campo da tabela
            $formData['cd_etapa'] = $formData['cd_etapa_atividade'];
            unset($formData['cd_etapa_atividade']);

            $arrResult['msg'] = $this->atividade->salvarAtividade($formData);
            $db->commit();

		} catch(Base_Exception_Error $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ]  = $e->getMessage();
            $db->rollBack();
		} catch(Zend_Exception $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function recuperaAtividadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_atividade = $this->_request->getParam('cd_atividade',0);
		$row          = $this->atividade->getRowAtividade($cd_atividade);

		echo Zend_Json::encode($row->toArray());
	}

    public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_atividade = (int)$this->_request->getParam('cd_atividade', 0);

        $arrResult = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            $arrWhere['cd_atividade = ?'] = $cd_atividade;

            $this->_verificaIntegridadeAtividade($arrWhere);
            $this->atividade->excluirAtividade($arrWhere);
            $db->commit();

		} catch(Base_Exception_Alert $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg' ] = $e->getMessage();
            $db->rollBack();
		} catch(Base_Exception_Error $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = $e->getMessage();
            $db->rollBack();
		} catch(Zend_Exception $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

    private function _verificaIntegridadeAtividade($arrWhere)
    {
        $objObjetoContratoAtividade    = new ObjetoContratoAtividade();
        $objItemRisco                  = new ItemRisco();
        $objPlanejamento               = new Planejamento();
        $objHistorico                  = new Historico();
        $objHistoricoProjetoContinuado = new HistoricoProjetoContinuado();

        if($objObjetoContratoAtividade->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_OBJETO_CONTRATO'));
        }
        if($objItemRisco->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_ITEM_RISCO'));
        }
        if($objPlanejamento->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_PLANEJAMENTO'));
        }
        if($objHistorico->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_HISTORICO'));
        }
        if($objHistoricoProjetoContinuado->fetchAll($arrWhere)->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_PROJETO_CONTINUO'));
        }
    }

	public function pesquisaAtividadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_etapa = (int)$this->_request->getParam('cd_etapa', 0);
		$atividade = new Atividade($this->_request->getControllerName());
		$res = $atividade->getAtividade($cd_etapa);

		$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		foreach ($res as $cd_atividade => $tx_atividade) {
			$strOptions .= "<option value=\"{$cd_atividade}\">{$tx_atividade}</option>";
		}

		echo $strOptions;
	}
	
	public function montaComboEtapaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao_ti');
		$cd_atividade       = $this->_request->getParam('cd_atividade',0);
		
		$selected = "";
		$cd_etapa = 0;
		if($cd_atividade != 0){
            $arrFetch['cd_atividade = ?'] = $cd_atividade;
			$arrAtividade = $this->atividade->fetchrow($arrFetch)->toArray();
			$cd_etapa     = $arrAtividade['cd_etapa'];
			$selected     = "selected=\"selected\"";
		}
		if($cd_area_atuacao_ti){
			$arrDados = $this->objEtapa->comboEtapaAreaAtuacaoTi($cd_area_atuacao_ti,true);

			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_etapa == $key){
						$strOptions .= "<option {$selected} label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					} else {
						$strOptions .= "<option label=\"{$conteudo}\" value=\"{$key}\">{$conteudo}</option>";
					}
				}
			} else {
				$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			}
		} else {
			$strOptions = "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		echo $strOptions;
	}
	
	public function gridAtividadeAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_etapa = $this->_request->getParam('cd_etapa');
		$arrDados = $this->atividade->getDadosAtividade($cd_etapa);

		$this->view->res = $arrDados;
	}
	
	public function comboAtividadeAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_etapa = $this->_request->getParam("cd_etapa");

		$arrAtividade = $this->atividade->getAtividade($cd_etapa,true);
		
		$strOption = "";
		if(count($arrAtividade) > 0){
			foreach($arrAtividade as $key=>$value){
				$strOption .= "<option value=\"{$key}\" label=\"{$value}\">{$value}</option>";
			}
		}
		echo $strOption;
	}
}