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

class QuestaoAnaliseRiscoController extends Base_Controller_Action
{
	private $objQuestaoAnaliseRisco;
	private $objContrato;

	public function init()
	{
		parent::init();
		$this->objQuestaoAnaliseRisco = new QuestaoAnaliseRisco($this->_request->getControllerName());
		$this->objContrato            = new ObjetoContrato($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_QUESTAO_RISCO'));
	    $this->initView();
	    
	    $this->view->arrObjeto = $this->objContrato->getObjetoContratoAtivo(null,true);
	}

    public function gridQuestaoAnaliseRiscoAction()
    {
        $this->_helper->layout->disableLayout();
        
        $cd_etapa      = ($this->_request->getParam("cd_etapa"))?$this->_request->getParam("cd_etapa"):null;
        $cd_atividade  = ($this->_request->getParam("cd_atividade"))?$this->_request->getParam("cd_atividade"):null;
        $cd_item_risco = ($this->_request->getParam("cd_item_risco"))?$this->_request->getParam("cd_item_risco"):null;
        
        $this->view->res = $this->objQuestaoAnaliseRisco->getDadosQuestaoAnaliseRisco($cd_etapa,$cd_atividade,$cd_item_risco);
    }
	
    public function redirecionaQuestaoAnaliseRiscoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		
        $cd_questao_analise_risco = $this->_request->getPost('cd_questao_analise_risco',null);
		if( is_null($cd_questao_analise_risco) ){
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}
		$result = false;
		if( !empty($cd_questao_analise_risco) ){
            $rs = $this->objQuestaoAnaliseRisco->find($cd_questao_analise_risco)->current();
            if( !is_null($rs) ){
                $result = $rs->toArray();
            }
		} else {
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'),'tipo'=>'3')));
		}
        echo Zend_Json_Encoder::encode($result);
    }
    
    public function excluirQuestaoAnaliseRiscoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		
        $cd_questao_analise_risco = $this->_request->getPost('cd_questao_analise_risco',null);
		if( is_null($cd_questao_analise_risco) ){
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}
		$result = array();
		$rs = false;
		if( !empty($cd_questao_analise_risco) ) {
            $rs = $this->objQuestaoAnaliseRisco->delete("cd_questao_analise_risco = {$cd_questao_analise_risco}");
		} else {
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_NENHUN_DADO_RECEBIDO_INCLUSAO'),'tipo'=>'3')));
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
	
	public function salvarQuestaoAnaliseRiscoAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        // Post...
		$cd_questao_analise_risco          = $this->_request->getPost('cd_questao_analise_risco',null);
		$tx_questao_analise_risco          = $this->_request->getPost('tx_questao_analise_risco',null);
		$cd_item_risco                     = $this->_request->getPost('cd_item_risco_questao_analise_risco',null);
		$cd_etapa                          = $this->_request->getPost('cd_etapa_questao_analise_risco',null);
		$cd_atividade                      = $this->_request->getPost('cd_atividade_questao_analise_risco',null);
		$ni_peso_questao_analise_risco     = $this->_request->getPost('ni_peso_questao_analise_risco',null);
		$tx_obj_questao_analise_risco = $this->_request->getPost('tx_obj_questao_analise_risco',null);

		if( is_null($cd_questao_analise_risco) || is_null($cd_item_risco) || is_null($cd_etapa) || is_null($tx_questao_analise_risco) || is_null($cd_atividade) ){
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}
		$result = array();
		$rs = false;
		if( empty($cd_questao_analise_risco) ) { // novo...
            $novo = $this->objQuestaoAnaliseRisco->createRow();
            $novo->cd_questao_analise_risco          = $this->objQuestaoAnaliseRisco->getNextValueOfField('cd_questao_analise_risco');
            $novo->tx_questao_analise_risco          = $tx_questao_analise_risco;
            $novo->cd_item_risco                     = $cd_item_risco;
            $novo->cd_etapa                          = $cd_etapa;
            $novo->cd_atividade                      = $cd_atividade;
            $novo->ni_peso_questao_analise_risco     = $ni_peso_questao_analise_risco;
            $novo->tx_obj_questao_analise_risco = $tx_obj_questao_analise_risco;
            $rs = $novo->save();
		} else {
            $row = $this->objQuestaoAnaliseRisco->fetchRow("cd_questao_analise_risco = {$cd_questao_analise_risco}");
            $row->tx_questao_analise_risco          = $tx_questao_analise_risco;
            //$row->cd_item_risco                     = $cd_item_risco;
            //$row->cd_etapa                          = $cd_etapa;
            //$row->cd_atividade                      = $cd_atividade;
            $row->ni_peso_questao_analise_risco     = $ni_peso_questao_analise_risco;
            $row->tx_obj_questao_analise_risco = $tx_obj_questao_analise_risco;
            $rs = $row->save();
		}

        if( $rs ){
            $result['msg']  = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $result['tipo'] = '1';
        } else {
            $result['msg'] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }
		
		echo Zend_Json_Encoder::encode($result);
	}

	/* Método comentado pois a tabela de categoria e de risco não irão mais existe
	public function montaComboTopicoRiscoPorCategoriaRiscoAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $cd_categoria_risco = $this->_request->getPost('cd_categoria_risco',null);
        $cd_topico_risco    = $this->_request->getPost('cd_topico_risco',0);
        if( is_null($cd_categoria_risco) ){
            die(Zend_Json_Encoder::encode(array('msg'=>'ERRO: ACESSO INDEVIDO!','tipo'=>'3')));
        }
        
        $html = '<option value="0">'.Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE').'</option>';
        if( !empty($cd_categoria_risco) ) {
            $rs = $this->objTopicoRisco->getTopicoRiscoPorCategoriaRisco($cd_categoria_risco);
            foreach( $rs as $topico ){
                $codigo = $topico['cd_topico_risco'];
                $selected = ($cd_topico_risco==$codigo)?"selected=\"selected\"":"";
                $html .= "<option value=\"{$codigo}\" {$selected}>{$this->comboTopicoRisco[$codigo]}</option>";
            }
        }
        echo $html;
	}

	public function montaComboItemRiscoPorTopicoRiscoAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $cd_topico_risco = $this->_request->getPost('cd_topico_risco',null);
        $cd_item_risco   = $this->_request->getPost('cd_item_risco',0);
        if( is_null($cd_topico_risco) ){
            die(Zend_Json_Encoder::encode(array('msg'=>'ERRO: ACESSO INDEVIDO!','tipo'=>'3')));
        }
        
        $html = '<option value="0">'.Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE').'</option>';
        if( !empty($cd_topico_risco) ) {
            $rs = $this->objItemRisco->getItemRiscoPorTopicoRisco($cd_topico_risco);
            foreach( $rs as $item ){
                $codigo = $item['cd_item_risco'];
                $selected = ($cd_item_risco==$codigo)?"selected=\"selected\"":"";
                $html .= "<option value=\"{$codigo}\" {$selected}>{$this->comboItemRisco[$codigo]}</option>";
            }
        }
        echo $html;
	}
	*/
}