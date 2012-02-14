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

class SubitemInventarioController extends Base_Controller_Action
{
	private $_objSubitemInventario;
	private $_objItemInventario;
	private $_cd_subitem_inventario;
	private $_cd_item_inventario;

	public function init()
	{
		parent::init();
		$this->_objSubitemInventario = new SubitemInventario($this->_request->getControllerName());
		$this->_objItemInventario    = new ItemInventario($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->view->formSubitemInventario = new SubitemInventarioForm();
	}

    public function salvarSubitemInventarioAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();
        
        $arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;

		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            //caso não exites a chave "cd_item_inventario" é uma atualização e deve pegar pelo hidden
			if( !empty ($formData['cd_subitem_inventario'])) {

                $this->_cd_subitem_inventario = $formData['cd_subitem_inventario'];
                $this->_cd_item_inventario    = $formData['cd_item_inventario_hidden'];

                $this->verificaExistenciaRegistro();
                
                $where              = "cd_subitem_inventario = {$this->_cd_subitem_inventario} and ";
                $where             .= "cd_item_inventario = {$this->_cd_item_inventario}";

				$novo               = $this->_objSubitemInventario->fetchRow($where);
				$arrResult['msg']   = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {

                $this->_cd_item_inventario   = $formData['cd_item_inventario'];

				$novo                        = $this->_objSubitemInventario->createRow();
				$novo->cd_subitem_inventario = $this->_objSubitemInventario->getNextValueOfField('cd_subitem_inventario');
				$novo->cd_item_inventario    = $this->_cd_item_inventario;
				$arrResult['msg']            = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}

			$novo->tx_subitem_inventario       = $this->toUpper($formData['tx_subitem_inventario']);
			$novo->st_info_chamado_tecnico     = $this->toUpper($formData['st_info_chamado_tecnico']);
			$novo->save();
            $db->commit();
		} catch(Base_Exception_Alert $e) {
            $db->rollBack();
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg' ] = $e->getMessage();
		} catch(Zend_Exception $e) {
            $db->rollBack();
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
    }

    public function recuperarSubitemInventarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$this->_cd_item_inventario    = $this->_request->getParam('cd_item_inventario', 0);
        $this->_cd_subitem_inventario = $this->_request->getParam('cd_subitem_inventario', 0);

		$this->verificaExistenciaRegistro();

        $res = $this->_objSubitemInventario->find($this->_cd_item_inventario, $this->_cd_subitem_inventario)->current()->toArray();

		echo Zend_Json::encode($res);
	}

    public function excluirSubitemInventarioAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $this->_cd_item_inventario = $this->_request->getParam('cd_item_inventario', 0);
        $this->_cd_subitem_inventario = $this->_request->getParam('cd_subitem_inventario', 0);

        $arrResult  = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
		$erro       = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            $this->verificaExistenciaRegistro();

            $where = "cd_item_inventario = {$this->_cd_item_inventario} and cd_subitem_inventario = {$this->_cd_subitem_inventario}";
            $this->_objSubitemInventario->delete($where);
            $db->commit();

		} catch(Base_Exception_Alert $e) {
            $db->rollBack();
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg' ] = $e->getMessage();
		} catch(Zend_Exception $e) {
            $db->rollBack();
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
    }

    /**
     * Metodo verificar a existencia do registro que está tentando ser alterado
     * caso o registro não esta contido na tabela retorna um exception
     */
    private function verificaExistenciaRegistro()
    {
        $where = "cd_item_inventario = {$this->_cd_item_inventario} and cd_subitem_inventario = {$this->_cd_subitem_inventario}";
        
        $result = $this->_objSubitemInventario->fetchAll($where);
        if(!$result->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_NAO_LOCALIZADO'));
        }
    }

	public function gridSubitemInventarioAction()
	{
		$this->_helper->layout->disableLayout();
        $this->_cd_item_inventario    = $this->_request->getParam('cd_item_inventario');

        $res = $this->_objSubitemInventario->fetchAll("cd_item_inventario = {$this->_cd_item_inventario}",'tx_subitem_inventario')->toArray();

        $this->view->res = $res;
	}

    public function montaComboItemInventarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao_ti');
		$cd_subitem_inventario       = $this->_request->getParam('cd_subitem_inventario',0);

		$selected = "";
		$cd_item_inventario = 0;
		if($cd_subitem_inventario != 0){
            $arrFetch['cd_subitem_inventario = ?'] = $cd_subitem_inventario;
			$arrSubitem = $this->_objSubitemInventario->fetchrow($arrFetch)->toArray();
			$cd_item_inventario     = $arrSubitem['cd_item_inventario'];
			$selected     = "selected=\"selected\"";
		}
		if($cd_area_atuacao_ti){
			$arrDados = $this->_objItemInventario->comboItemInventario($cd_area_atuacao_ti,true);

			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_item_inventario == $key){
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
}