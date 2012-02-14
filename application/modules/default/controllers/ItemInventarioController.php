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

class ItemInventarioController extends Base_Controller_Action
{
	private $_objItemInventario;
	private $_cd_item_inventario;

	public function init()
	{
		parent::init();
		$this->_objItemInventario = new ItemInventario($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->view->formItemInventario = new ItemInventarioForm();
	}

    public function salvarItemInventarioAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();

        $arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			if($formData['cd_item_inventario'] != '') {
                $this->_cd_item_inventario = $formData['cd_item_inventario'];
                $this->verificaExistenciaRegistro();
				$novo               = $this->_objItemInventario->fetchRow("cd_item_inventario= {$this->_cd_item_inventario}");
				$arrResult['msg']   = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo                       = $this->_objItemInventario->createRow();
				$novo->cd_item_inventario   = $this->_objItemInventario->getNextValueOfField('cd_item_inventario');
				$arrResult['msg']           = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}

			$novo->cd_area_atuacao_ti       = $formData['cd_area_atuacao_ti'];
			$novo->tx_item_inventario       = $this->toUpper($formData['tx_item_inventario']);

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
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
    }

    public function recuperarItemInventarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$this->_cd_item_inventario = $this->_request->getParam('cd_item_inventario', 0);

		$this->verificaExistenciaRegistro();

        $res = $this->_objItemInventario->find($this->_cd_item_inventario)->current()->toArray();
        
		echo Zend_Json::encode($res);
	}

    public function excluirItemInventarioAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $this->_cd_item_inventario = $this->_request->getParam('cd_item_inventario', 0);

        $arrResult  = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
		$erro       = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

            $this->verificaExistenciaRegistro();

            $this->_objItemInventario->delete("cd_item_inventario = {$this->_cd_item_inventario}");

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
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
    }

    /**
     * Metodo verificar a existencia do registro que está tentando ser alterado
     * caso o registro não esta contido na tabela retorna um exception
     */
    private function verificaExistenciaRegistro()
    {
        $result = $this->_objItemInventario->fetchAll("cd_item_inventario = {$this->_cd_item_inventario}");
        if(!$result->valid()){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_NAO_LOCALIZADO'));
        }
    }

	public function gridItemInventarioAction()
	{
		$this->_helper->layout->disableLayout();
        $this->view->res = $this->_objItemInventario->fetchAll(null,'tx_item_inventario')->toArray();
	}
}