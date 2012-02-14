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

class RegraNegocioController extends Base_Controller_Action
{
    private $regraNegocio;

    public function init()
    {
        parent::init();
        $this->regraNegocio = new RegraNegocio($this->_request->getControllerName());
    }

    public function         indexAction(){ $this->initView(); }

    public function  regraNegocioAction(){ $this->initView(); }

    public function regrasNegocioAction(){ $this->initView(); }

    public function gridRegraNegocioAction()
    {
        $this->_helper->layout->disableLayout();
        $cd_projeto_regra_negocio = $this->_request->getPost('cd_projeto_regra_negocio',null);
        if( is_null($cd_projeto_regra_negocio) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }

        $this->view->res = $this->regraNegocio->getRegraNegocioWithLastVersion($cd_projeto_regra_negocio);
    }

    public function recuperaRegraNegocioAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_regra_negocio        = $this->_request->getPost('cd_regra_negocio',null);
        $ni_versao_regra_negocio = $this->_request->getPost('ni_versao_regra_negocio',1); // se não vier traz a versão 1.
        if( is_null($cd_regra_negocio) || is_null($ni_versao_regra_negocio) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $result = false;
        if( !empty($cd_regra_negocio) ) {
            $where = "cd_regra_negocio = {$cd_regra_negocio} AND ni_versao_regra_negocio = {$ni_versao_regra_negocio}";
            $result = $this->regraNegocio->fetchAll($where)->toArray();
        } else {
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_RECUPERAR_REGISTRO'),'tipo'=>'3')));
        }

        echo Zend_Json_Encoder::encode($result);
    }

    public function excluirRegraNegocioAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_regra_negocio = $this->_request->getPost('cd_regra_negocio',null);
        if( is_null($cd_regra_negocio) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $result = array();
        $rs = false;
        if( !empty($cd_regra_negocio) ) {
            $rs = $this->regraNegocio->delete("cd_regra_negocio = {$cd_regra_negocio}");
        } else {
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'),'tipo'=>'3')));
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

    public function salvarRegraNegocioAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_regra_negocio           = $this->_request->getPost('cd_regra_negocio',null);
        $cd_projeto_regra_negocio   = $this->_request->getPost('cd_projeto_regra_negocio',null);
        $tx_regra_negocio           = $this->_request->getPost('tx_regra_negocio',null);
        $st_regra_negocio           = $this->_request->getPost('st_regra_negocio','A'); //se não vier seta como 'A' :ativo
        $tx_descricao_regra_negocio = $this->_request->getPost('tx_descricao_regra_negocio',null);

        if( is_null($cd_regra_negocio)
        || is_null($cd_projeto_regra_negocio)
        || is_null($tx_regra_negocio)
        || is_null($st_regra_negocio)
        || is_null($tx_descricao_regra_negocio) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $result = array();
        $rs = false;
        if( empty($cd_regra_negocio) ){
            $novo['cd_regra_negocio']           = $this->regraNegocio->getNextValueOfField("cd_regra_negocio");
            $novo['ni_ordem_regra_negocio']     = $this->regraNegocio->getNextValueNiOrdemRegraDeNegocio( $cd_projeto_regra_negocio );
            $novo['dt_regra_negocio']           = date("Y-m-d H:i:s");
            $novo['cd_projeto_regra_negocio']   = $cd_projeto_regra_negocio;
            $novo['tx_regra_negocio']           = $tx_regra_negocio;
            $novo['st_regra_negocio']           = $st_regra_negocio;
            $novo['tx_descricao_regra_negocio'] = $tx_descricao_regra_negocio;
            $novo['ni_versao_regra_negocio']    = $this->regraNegocio->getNextVersion($cd_regra_negocio);
            $rs = $this->regraNegocio->insert($novo);
        } else {
            $verifFechamento = $this->regraNegocio->getRegraNegocioWithLastVersion($cd_projeto_regra_negocio,$cd_regra_negocio,'S');

            if( empty($verifFechamento) ){
                $novo['st_regra_negocio']           = $st_regra_negocio;
                $novo['tx_descricao_regra_negocio'] = $tx_descricao_regra_negocio;
                $rs = $this->regraNegocio->atualizaRegraNegocio($novo, $cd_regra_negocio, $cd_projeto_regra_negocio );
            } else {
                $rs = false;
            }
        }

        if( $rs ){
            $result['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $result['tipo'] = '1';
        } else {
            $result['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }

        echo Zend_Json_Encoder::encode($result);
    }


    public function gridFechamentoVersaoAction()
    {
		$this->_helper->layout->disableLayout();

		$cd_projeto_regra_negocio = $this->_request->getPost('cd_projeto_regra_negocio',null);
		if( is_null($cd_projeto_regra_negocio) ){
			die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}

		$this->view->res = $this->regraNegocio->getRegraNegocioAtivaUltimaVersao($cd_projeto_regra_negocio);
    }

    public function fecharVersaoRegraNegocioAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $arrDados = $this->_request->getPost();

        $arrWhere["cd_projeto_regra_negocio = ?"] = $arrDados['cd_projeto_regra_negocio'];
        $arrWhere["cd_regra_negocio			= ?"] = $arrDados['cd_regra_negocio'];
        $arrWhere["dt_regra_negocio			= ?"] = $arrDados['dt_regra_negocio'];

        $arrUpdate['st_fechamento_regra_negocio'] = "S";
        $arrUpdate['dt_fechamento_regra_negocio'] = date("Y-m-d");

        if($this->regraNegocio->update($arrUpdate, $arrWhere)){
        	echo Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_VERSAO');
        }else{
        	echo Base_Util::getTranslator('L_MSG_ERRO_FECHAR_VERSAO');
        }
    }
}