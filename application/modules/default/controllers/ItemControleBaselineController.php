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

class ItemControleBaselineController extends Base_Controller_Action 
{

	private $_objItemControleBaseline;
	
    const K_ITEM_CONTROLE_BASELINE_PROPOSTA      = 3;
    const K_ITEM_CONTROLE_BASELINE_REQUISITO     = 1;
    const K_ITEM_CONTROLE_BASELINE_REGRA_NEGOCIO = 4;
    const K_ITEM_CONTROLE_BASELINE_CASO_USO      = 5;

	public function init()
	{
		parent::init();
		$this->_objItemControleBaseline = new ItemControleBaseline($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ITEM_BASELINE'));
	    $this->initView();
	}	

    public function gridItemControleBaselineAction()
    {
        $this->_helper->layout->disableLayout();
        $select = $this->_objItemControleBaseline->select()->order("tx_item_controle_baseline");
        $res = $this->_objItemControleBaseline->fetchAll($select)->toArray();
        $this->view->res = $res;
    }
	
    public function redirecionaItemControleBaselineAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		
        $cd_item_controle_baseline = $this->_request->getPost('cd_item_controle_baseline',null);
		if( is_null($cd_item_controle_baseline) ){
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}
		$result = false;
		if( !empty($cd_item_controle_baseline) ) {
            $rs = $this->_objItemControleBaseline->find($cd_item_controle_baseline)->current();
            if( !is_null($rs) ){
                $result = $rs->toArray();
            }
		} else {
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_VARIFICA_INFORMAÇOES'),'tipo'=>'3')));
		}
		
        echo Zend_Json_Encoder::encode($result);
    }

    public function excluirItemControleBaselineAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		
        $cd_item_controle_baseline = $this->_request->getPost('cd_item_controle_baseline',null);
		if( is_null($cd_item_controle_baseline) ){
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}
		$result = array();
		$rs = false;
		if( !empty($cd_item_controle_baseline) ) {
            $rs = $this->_objItemControleBaseline->delete("cd_item_controle_baseline = {$cd_item_controle_baseline}");
		} else {
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_EXCLUSAO'),'tipo'=>'3')));
		}
		
		if( $rs ){
		    $result['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		    $result['tipo'] = '1';
		} else {
		    $result['msg'] = Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		    $result['tipo'] = '3';
		}
		
        echo Zend_Json_Encoder::encode($result);
    }
	
	public function salvarItemControleBaselineAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        // Post...
		$cd_item_controle_baseline     = $this->_request->getPost('cd_item_controle_baseline',null);
		$tx_item_controle_baseline     = $this->_request->getPost('tx_item_controle_baseline',null);

		if( is_null($cd_item_controle_baseline) || is_null($tx_item_controle_baseline)){
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}
		$result = array();
		$rs = false;
		if( empty($cd_item_controle_baseline) ) { // novo...
            $novo = $this->_objItemControleBaseline->createRow();
            $novo->cd_item_controle_baseline = $this->_objItemControleBaseline->getNextValueOfField('cd_item_controle_baseline');
            $novo->tx_item_controle_baseline = $tx_item_controle_baseline;
            $rs = $novo->save();
		} else {
            $row = $this->_objItemControleBaseline->fetchRow("cd_item_controle_baseline = {$cd_item_controle_baseline}");
            $row->tx_item_controle_baseline = $tx_item_controle_baseline;
            $rs = $row->save();
		}

        if( $rs ){
            $result['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $result['tipo'] = '1';
        } else {
            $result['msg'] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }
		
		echo Zend_Json_Encoder::encode($result);
	}
}