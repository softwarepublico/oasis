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

class ItemRiscoController extends Base_Controller_Action
{
	private $objItemRisco;
	private $objContrato;
	

	public function init()
	{
		parent::init();
		$this->objItemRisco = new ItemRisco($this->_request->getControllerName());
		$this->objContrato  = new ObjetoContrato($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ITEM_RISCO'));
        $this->view->arrObjeto = $this->objContrato->getObjetoContratoAtivo(null,true);
	    $this->initView();
	}

    public function gridItemRiscoAction()
    {
        $this->_helper->layout->disableLayout();
        
        $cd_etapa     = $this->_request->getParam("cd_etapa",0);
        $cd_atividade = $this->_request->getParam("cd_atividade",0);
        $cd_etapa     = ($cd_etapa != 0)?$cd_etapa:null;
        $cd_atividade = ($cd_atividade != 0)?$cd_atividade:null;
        
        $arrItemRisco = $this->objItemRisco->getDadosItemRisco($cd_etapa,$cd_atividade);
        $this->view->res = $arrItemRisco;
    }
	
    public function redirecionaItemRiscoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		
        $cd_item_risco = $this->_request->getPost('cd_item_risco',null);
		$result = array();
		if( !empty($cd_item_risco) ) {
            $rs = $this->objItemRisco->find($cd_item_risco)->current();
            if( !is_null($rs) ){
                $result = $rs->toArray();
            }
		}
        echo Zend_Json_Encoder::encode($result);
    }
    
    public function excluirItemRiscoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		
        $cd_item_risco = $this->_request->getPost('cd_item_risco',null);
		$result = array();
		$rs = false;
		if( !empty($cd_item_risco) ) {
            $rs = $this->objItemRisco->delete("cd_item_risco = {$cd_item_risco}");
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
	
	public function salvarItemRiscoAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
        // Post...
		$cd_item_risco           = $this->_request->getPost('cd_item_risco',null);
		$cd_etapa                = $this->_request->getPost('cd_etapa_item_risco',null);
		$cd_atividade            = $this->_request->getPost('cd_atividade_item_risco',null);
		$tx_item_risco           = $this->_request->getPost('tx_item_risco',null);
		$tx_descricao_item_risco = $this->_request->getPost('tx_descricao_item_risco',null);

		$result = array();
		$rs = false;
		if( empty($cd_item_risco) ) { // novo...
            $novo = $this->objItemRisco->createRow();
            $novo->cd_item_risco           = $this->objItemRisco->getNextValueOfField('cd_item_risco');
            $novo->tx_item_risco           = $tx_item_risco;
            $novo->cd_etapa                = $cd_etapa;
            $novo->cd_atividade            = $cd_atividade;
            $novo->tx_descricao_item_risco = $tx_descricao_item_risco;
            $rs = $novo->save();
		} else {
            $row = $this->objItemRisco->fetchRow("cd_item_risco = {$cd_item_risco}");
            $row->tx_item_risco           = $tx_item_risco;
            $row->cd_etapa                = $cd_etapa;
            $row->cd_atividade            = $cd_atividade;
            $row->tx_descricao_item_risco = $tx_descricao_item_risco;
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
	
	public function comboItemRiscoAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		
        $cd_etapa     = $this->_request->getParam('cd_etapa',null);
        $cd_atividade = $this->_request->getParam('cd_atividade',null);
        
        $arrItemRisco = $this->objItemRisco->getItemRisco(true,null,$cd_etapa,$cd_atividade);

        $strOption = "";
        if(count($arrItemRisco) > 0){
        	foreach($arrItemRisco as $key=>$value){
	        	$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
        	}
        }
        echo $strOption;
	}
}