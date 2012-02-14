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

class SubitemInvDescriController extends Base_Controller_Action
{
	private $_objSubitemInvDescri;
    private $_objSubitemInventario;
	private $_objItemInventario;
	private $_cd_subitem_inv_descri;
	private $_cd_subitem_inventario;
	private $_cd_item_inventario;

	public function init()
	{
		parent::init();
		$this->_objSubitemInvDescri  = new SubitemInvDescri($this->_request->getControllerName());
		$this->_objSubitemInventario = new SubitemInventario($this->_request->getControllerName());
		$this->_objItemInventario    = new ItemInventario($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->view->formSubitemInvDescri = new SubitemInvDescriForm();
	}

    public function salvarSubitemInvDescriAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData  = $this->_request->getPost();
        $arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');

		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            //caso não exites a chave "cd_item_inventario" é uma atualização e deve pegar pelo hidden
			if( !empty ($formData['cd_subitem_inv_descri'])) {
                $this->_cd_subitem_inv_descri = $formData['cd_subitem_inv_descri'];
                $this->_cd_subitem_inventario = $formData['cd_subitem_inventario'];
                $this->_cd_item_inventario    = $formData['cd_item_inventario'];

                $this->verificaExistenciaRegistro();
                
                $where = array(
                    "cd_subitem_inv_descri = ?" => $this->_cd_subitem_inv_descri,
                    "cd_subitem_inventario = ?" => $this->_cd_subitem_inventario,
                    "cd_item_inventario = ?" => $this->_cd_item_inventario
                );

				$novo               = $this->_objSubitemInvDescri->fetchRow($where);
				$arrResult['msg']   = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {

                $this->_cd_item_inventario    = $formData['cd_item_inventario'];
                $this->_cd_subitem_inventario = $formData['cd_subitem_inventario'];

				$novo                         = $this->_objSubitemInvDescri->createRow();
				$novo->cd_subitem_inv_descri  = $this->_objSubitemInvDescri->getNextValueOfField('cd_subitem_inv_descri');
				$novo->cd_subitem_inventario  = $this->_cd_subitem_inventario;
				$novo->cd_item_inventario     = $this->_cd_item_inventario;
				$arrResult['msg']             = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}

			$novo->tx_subitem_inv_descri      = $this->toUpper($formData['tx_subitem_inv_descri']);
			$novo->ni_ordem                   = $formData['ni_ordem'];
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

    public function recuperarSubitemInvDescriAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$this->_cd_item_inventario    = $this->_request->getParam('cd_item_inventario', 0);
		$this->_cd_subitem_inventario = $this->_request->getParam('cd_subitem_inventario', 0);
        $this->_cd_subitem_inv_descri = $this->_request->getParam('cd_subitem_inv_descri', 0);

        $res = $this->_objSubitemInvDescri->find($this->_cd_item_inventario, $this->_cd_subitem_inventario, $this->_cd_subitem_inv_descri)->current()->toArray();

		echo Zend_Json::encode($res);
	}

    public function excluirSubitemInvDescriAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $this->_cd_item_inventario = $this->_request->getParam('cd_item_inventario', 0);
        $this->_cd_subitem_inventario = $this->_request->getParam('cd_subitem_inventario', 0);
        $this->_cd_subitem_inv_descri = $this->_request->getParam('cd_subitem_inv_descri', 0);

        $arrResult  = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
		$erro       = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            $this->verificaExistenciaRegistro();

            $where = "cd_item_inventario = {$this->_cd_item_inventario} and cd_subitem_inventario = {$this->_cd_subitem_inventario} and cd_subitem_inv_descri = {$this->_cd_subitem_inv_descri}";
            $this->_objSubitemInvDescri->delete($where);
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
    private function verificaExistenciaRegistro ()
    {
        $result = $this->_objSubitemInvDescri->fetchAll(array(
                    "cd_subitem_inv_descri = ?" => $this->_cd_subitem_inv_descri,
                    "cd_subitem_inventario = ?" => $this->_cd_subitem_inventario,
                    "cd_item_inventario = ?"    => $this->_cd_item_inventario
                ));
        if(!$result->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_NAO_LOCALIZADO'));
        }
        return $this;
    }

	public function gridSubitemInvDescriAction()
	{
		$this->_helper->layout->disableLayout();
        
        $this->_cd_item_inventario    = $this->_request->getParam('cd_item_inventario');
        $this->_cd_subitem_inventario = $this->_request->getParam('cd_subitem_inventario');
                
        $this->view->res = $this->_objSubitemInvDescri->fetchAll(array(
                "cd_item_inventario = ?"   =>$this->_cd_item_inventario,
                "cd_subitem_inventario = ?"=>$this->_cd_subitem_inventario
            ),'ni_ordem');
	}

     public function montaComboSubitemInventarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        
		$cd_item_inventario     = $this->_request->getParam('cd_item_inventario');

		$selected = "";
		if($cd_item_inventario){
			$arrDados = $this->_objSubitemInventario->comboSubitemInventario($cd_item_inventario,true);

			if(count($arrDados) > 0){
				$strOptions = "";
				foreach($arrDados as $key=>$conteudo){
					if($cd_subitem_inventario == $key){
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