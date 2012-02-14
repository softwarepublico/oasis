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
 
class AnaliseExecucaoProjetoController extends Base_Controller_Action
{
    private $analiseExecucaoProjeto;

    public function init()
    {
        parent::init();
        $this->analiseExecucaoProjeto = new AnaliseExecucaoProjeto($this->_request->getControllerName());
    }

    public function indexAction()
    {
        $this->initView();
    }

    public function analiseExecucaoAction()
    {
        $this->initView();
    }

    public function projetosExecucaoAction()
    {
        $this->initView();
    }

    public function montaComboProjetoExecucaoAction()
    {
        
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato = $this->_request->getParam("cd_contrato");
	    $arrProjeto =  $this->analiseExecucaoProjeto->getProjetosExecucao(true, false, false, $cd_contrato);
//	    $arrProjeto =  $this->analiseExecucaoProjeto->getcontratoprojeto(true,$cd_contrato);
        $strOption = "";
		if(count($arrProjeto) > 0){
			foreach($arrProjeto as $key=>$value){
				$strOption .= "<option value=\"{$key}\" label=\"{$value}\">{$value}</option>";
			}
		}
		echo $strOption;
    }
    
    public function gridAnaliseExecucaoProjetoAction()
    {
        $this->_helper->layout->disableLayout();
        $cd_projeto = $this->_request->getPost('cd_projeto',null);
        if( is_null($cd_projeto) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $this->view->res = $this->analiseExecucaoProjeto->getGridAnaliseExecucao($cd_projeto);
    }

    public function recuperaAnaliseExecucaoProjetoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $dt_analise_execucao_projeto        = $this->_request->getPost('dt_analise_execucao_projeto',null);
        if( is_null($dt_analise_execucao_projeto) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $result = false;
        if( !empty($dt_analise_execucao_projeto) ) {
            $result = $this->analiseExecucaoProjeto->recuperaAnaliseExecucaoProjeto($dt_analise_execucao_projeto);

            foreach($result as $key=>$value){
                $result[$key]['dt_decisao_analise_execucao'] = date('d/m/Y', strtotime($value['dt_decisao_analise_execucao']));
            }

        } else {
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_SEM_CODIGO'),'tipo'=>'3')));
        }

        echo Zend_Json_Encoder::encode($result);
    }

    public function excluirAnaliseExecucaoProjetoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $dt_analise_execucao_projeto = $this->_request->getPost('dt_analise_execucao_projeto',null);
        if( is_null($dt_analise_execucao_projeto) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $result = array();
        $rs = false;
        if( !empty($dt_analise_execucao_projeto) ) {
            $rs = $this->analiseExecucaoProjeto->delete(array("dt_analise_execucao_projeto = ?" => $dt_analise_execucao_projeto));
        } else {
            die(Base_Util::getTranslator('L_MSG_ERRO_SEM_INDICE'));
        }

        if( $rs ){
            $result['msg' ] = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
            $result['tipo'] = '1';
        } else {
            $result['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }

        echo Zend_Json_Encoder::encode($result);
    }

    public function fecharAnaliseExecucaoProjetoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $dt_analise_execucao_projeto = $this->_request->getPost('dt_analise_execucao_projeto',null);
        if( is_null($dt_analise_execucao_projeto) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $result = array();
        $rs = false;
        if( !empty($dt_analise_execucao_projeto) ) {
            $rs = $this->analiseExecucaoProjeto->update(
                array( 'st_fecha_analise_execucao_proj' => 'S' ),
                array("dt_analise_execucao_projeto = ?" => $dt_analise_execucao_projeto)
            );
        } else {
            die(Base_Util::getTranslator('L_MSG_ERRO_SEM_INDICE'));
        }

        if( $rs ){
            $result['msg' ] = Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_ANALISE');
            $result['tipo'] = '1';
        } else {
            $result['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_FECHAR_ANALISE');
            $result['tipo'] = '3';
        }

        echo Zend_Json_Encoder::encode($result);
    }

    public function salvarAnaliseExecucaoProjetoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_projeto                    = $this->_request->getParam('cd_projeto_execucao',null);
        $dt_analise_execucao_projeto   = $this->_request->getParam('dt_analise_execucao_projeto',null);
        $tx_resultado_analise_execucao = $this->_request->getParam('tx_resultado_analise_execucao',null);
        $tx_decisao_analise_execucao   = $this->_request->getParam('tx_decisao_analise_execucao',null);
        $dt_decisao_analise_execucao   = $this->_request->getParam('dt_decisao_analise_execucao',null);

        if( is_null($cd_projeto)
        || is_null($dt_analise_execucao_projeto)
        || is_null($tx_resultado_analise_execucao)
        || is_null($tx_decisao_analise_execucao)
        || is_null($dt_decisao_analise_execucao) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }


        $result = array();
        $rs = false;
        if(empty($dt_analise_execucao_projeto)){
            $novo['dt_analise_execucao_projeto']   = date('Y-m-d H:i:s');
            $novo['cd_projeto']                    = $cd_projeto;
            $novo['tx_resultado_analise_execucao'] = $tx_resultado_analise_execucao;
            $novo['tx_decisao_analise_execucao']   = empty($tx_decisao_analise_execucao)?null:$tx_decisao_analise_execucao;
            $novo['dt_decisao_analise_execucao']   = empty($dt_decisao_analise_execucao)?null:$dt_decisao_analise_execucao;
            $rs = $this->analiseExecucaoProjeto->insert($novo);
        } else {
            $update['cd_projeto']                    = $cd_projeto;
            $update['tx_resultado_analise_execucao'] = $tx_resultado_analise_execucao;
            $update['tx_decisao_analise_execucao']   = empty($tx_decisao_analise_execucao)?null:$tx_decisao_analise_execucao;
            $update['dt_decisao_analise_execucao']   = empty($dt_decisao_analise_execucao)?null:$dt_decisao_analise_execucao;
            $rs = $this->analiseExecucaoProjeto->update($update,"dt_analise_execucao_projeto = '{$dt_analise_execucao_projeto}'");
        }
        if( $rs ){
            $result['msg' ] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $result['tipo'] = '1';
        } else {
            $result['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }
        echo Zend_Json_Encoder::encode($result);
    }
}