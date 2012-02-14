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

class AssociarAtividadeObjetoContratoController extends Base_Controller_Action
{
    private $_objEtapa;
    private $_objObjetoContratoAtividade;

	public function init()
	{
		parent::init();
        $this->_objEtapa                   = new Etapa($this->_request->getControllerName());
        $this->_objObjetoContratoAtividade = new ObjetoContratoAtividade($this->_request->getControllerName());
	}

	public function indexAction()
	{}

    public function comboEtapaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao_ti',0);

        $arrEtapa = $this->_objEtapa->comboEtapaAreaAtuacaoTi($cd_area_atuacao_ti, true);

        $strOptions = '';
        foreach($arrEtapa as  $key=>$value){
            $strOptions .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
        }
        echo $strOptions;
    }

    public function pesquisaAtividadeObjetoContratoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_objeto = $this->_request->getParam('cd_objeto',0);
		$cd_etapa  = $this->_request->getParam('cd_etapa',0);

        $rowSetForaObjetoContrato = $this->_objObjetoContratoAtividade->pesquisaAtividadeForaObjetoContrato($cd_objeto, $cd_etapa);
        $rowSetNoObjetoContrato   = $this->_objObjetoContratoAtividade->pesquisaAtividadeNoObjetoContrato($cd_objeto, $cd_etapa);

        $arr1 = "";
        foreach ($rowSetForaObjetoContrato as $row) {
            $arr1 .= "<option label=\"{$row->tx_atividade}\" value=\"{$row->cd_atividade}\">{$row->tx_atividade}</option>";
        }

        $arr2 = "";
        foreach ($rowSetNoObjetoContrato as $row) {
            $arr2 .= "<option label=\"{$row->tx_atividade}\" value=\"{$row->cd_atividade}\">{$row->tx_atividade}</option>";
        }

        $retornaOsDois = array($arr1, $arr2);

        echo Zend_Json_Encoder::encode($retornaOsDois);
    }

    public function associarAtividadeAction()
    {
        $this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

		$cd_objeto    = $post['cd_objeto'];
		$cd_etapa     = $post['cd_etapa'];
		$arrAtividade = Zend_Json_Decoder::decode($post['atividades']);

        $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            foreach ($arrAtividade as $atividade) {
                $arrInsert = array();
                $arrInsert['cd_objeto'   ] = $cd_objeto;
                $arrInsert['cd_etapa'    ] = $cd_etapa;
                $arrInsert['cd_atividade'] = $atividade;
                $this->_objObjetoContratoAtividade->salvarNovaAtividadeAoObjetoContrato($arrInsert);
            }

            $db->commit();

        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function desassociarAtividadeAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

		$cd_objeto    = $post['cd_objeto'];
		$cd_etapa     = $post['cd_etapa'];
		$arrAtividade = Zend_Json_Decoder::decode($post['atividades']);

        $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();
            foreach ($arrAtividade as $atividade) {
                $arrWhereDelete = array();
                $arrWhereDelete['cd_objeto = ?'   ] = $cd_objeto;
                $arrWhereDelete['cd_etapa = ?'    ] = $cd_etapa;
                $arrWhereDelete['cd_atividade = ?'] = $atividade;
                $this->_objObjetoContratoAtividade->excluirAtividadeDoObjetoContrato($arrWhereDelete);
            }

            $db->commit();

        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

}