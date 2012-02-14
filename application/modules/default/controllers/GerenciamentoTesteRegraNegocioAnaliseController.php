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

class GerenciamentoTesteRegraNegocioAnaliseController extends Base_Controller_Action
{

    private $itemTesteRegraNegocio;
    private $regraNegocio;

	public function init()
	{
		parent::init();
        $this->view->permissao_regraNegocio             = (ChecaPermissao::possuiPermissao('gerenciamento-teste-regra-negocio') === true)? 1:0;
        $this->view->permissao_regraNegocio_analise     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-regra-negocio-analise') === true)? 1:0;
        $this->view->permissao_regraNegocio_solucao     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-regra-negocio-solucao') === true)? 1:0;
        $this->view->permissao_regraNegocio_homologacao = (ChecaPermissao::possuiPermissao('gerenciamento-teste-regra-negocio-homologacao') === true)? 1:0;
	}

	public function indexAction()
	{
		 $this->initView();
	}

	public function gridGerenciamentoTesteRegraNegocioAnaliseAction()
	{
		$this->_helper->layout->disableLayout();
        $cd_regra_negocio = $this->_request->getPost('cd_regra_negocio',null);
        $cd_projeto       = $this->_request->getPost('cd_projeto',null);
        if( is_null($cd_regra_negocio) || is_null($cd_projeto) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $this->itemTesteRegraNegocio = new ItemTesteRegraNegocio($this->_request->getControllerName());
        $this->view->res = $this->itemTesteRegraNegocio->getGridItemTesteRegraNegocioAll('analise',$cd_regra_negocio,$cd_projeto);
	}

	public function saveAction()
	{
	    $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_projeto       = $this->_request->getPost('cd_projeto_regra_negocio',null);
        $cd_regra_negocio = $this->_request->getPost('cd_regra_negocio',null);
        if( is_null($cd_projeto) || is_null($cd_regra_negocio) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
	    $valCd = 'cd_item_teste_regra_negocio__';
        $tamCd = strlen($valCd);
        $valTx = 'tx_analise__';
        $tamTx = strlen($valTx);
        foreach($_POST as $k=>$v){
            if( substr($k,0,$tamCd) == $valCd ){
                $codigos_itens[substr($k,$tamCd)] = $v;
            }
            if( substr($k,0,$tamTx) == $valTx ){
                $analises[substr($k,$tamTx)] = $v;
            }
        }
        $qtdAnalises    = count($analises);
        $qtdCodigoItens = count($codigos_itens);
        if( $qtdAnalises != $qtdCodigoItens ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_QUANTIDADE_INPUTS'),'tipo'=>'3')));
        }

        $retorno = true;
        $result  = array();
        $rs      = false;

        $this->itemTesteRegraNegocio = new ItemTesteRegraNegocio($this->_request->getControllerName());
        $this->regraNegocio          = new RegraNegocio($this->_request->getControllerName());

        $this->itemTesteRegraNegocio->getDefaultAdapter()->beginTransaction();
        foreach( $analises as $codigoItemTeste => $textArea ){
            if( $retorno ){
                $textArea = trim($textArea);
                $dtRegraNegocio = $this->regraNegocio->getUltimaVersaoRegraNegocio( $cd_regra_negocio );
                $arr = array();
                $arr['cd_item_teste']    = $codigoItemTeste;
                $arr['tx_analise']       = $textArea;
                $arr['dt_regra_negocio'] = $dtRegraNegocio[0]['dt_regra_negocio'];
                if(!empty($textArea)){
                    $arr['st_solucao']              = null;

                    $arr['dt_analise']              = date('Y-m-d');
                    $arr['st_analise']              = "S";
                    $arr['cd_profissional_analise'] = $_SESSION['oasis_logged'][0]['cd_profissional'];
                } else {
                    $arr['st_solucao']              = null;

                    $arr['dt_analise']              = null;
                    $arr['st_analise']              = null;
                    $arr['cd_profissional_analise'] = null;
                }
                if( empty($codigos_itens[$codigoItemTeste]) ) { // novo...
                    $arr['cd_item_teste_regra_negocio'] = $this->itemTesteRegraNegocio->getNextValueOfField('cd_item_teste_regra_negocio');
                    $arr['cd_regra_negocio'           ] = $cd_regra_negocio;
                    $arr['cd_projeto_regra_negocio'   ] = $cd_projeto;
                    $rs = $this->itemTesteRegraNegocio->insert($arr);
                } else {
                    $where = "cd_item_teste_regra_negocio = {$codigos_itens[$codigoItemTeste]}
                              and
                              cd_regra_negocio = {$cd_regra_negocio}
                              and
                              cd_projeto_regra_negocio = {$cd_projeto}
                              and
                              cd_item_teste = {$codigoItemTeste}
                              and
                              dt_regra_negocio = '{$dtRegraNegocio[0]['dt_regra_negocio']}'
                              ";
                    $rs = $this->itemTesteRegraNegocio->update($arr,$where);
                }
                if( !$rs ){
                    $retorno = false;
                }
            }
        }
        if( $rs ){
            $this->itemTesteRegraNegocio->getDefaultAdapter()->commit();
            $result['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $result['tipo'] = '1';
        } else {
            $this->itemTesteRegraNegocio->getDefaultAdapter()->rollBack();
            $result['msg'] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }

        echo Zend_Json_Encoder::encode($result);
	}

	public function approveAction()
	{
	    $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_projeto       = $this->_request->getPost('cd_projeto_regra_negocio',null);
        $cd_regra_negocio = $this->_request->getPost('cd_regra_negocio',null);
        if( is_null($cd_projeto) || is_null($cd_regra_negocio) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }

	    $valCd = 'cd_item_teste_regra_negocio__';
        $tamCd = strlen($valCd);
        $valTx = 'tx_analise__';
        $tamTx = strlen($valTx);
        foreach($_POST as $k=>$v){
            if( substr($k,0,$tamCd) == $valCd ){
                $codigos_itens[substr($k,$tamCd)] = $v;
            }
            if( substr($k,0,$tamTx) == $valTx ){
                $analises[substr($k,$tamTx)] = $v;
            }
        }
        $qtdAnalises    = count($analises);
        $qtdCodigoItens = count($codigos_itens);
        if( $qtdAnalises != $qtdCodigoItens ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_QUANTIDADE_INPUTS'),'tipo'=>'3')));
        }

        $retorno = true;
        $result  = array();
        $rs      = false;

        $this->itemTesteRegraNegocio = new ItemTesteRegraNegocio($this->_request->getControllerName());
        $this->regraNegocio          = new RegraNegocio($this->_request->getControllerName());

        $this->itemTesteRegraNegocio->getDefaultAdapter()->beginTransaction();

        foreach( $analises as $codigoItemTeste => $textArea ){
            if( $retorno ){
                $textArea = trim($textArea);
                $dtRegraNegocio = $this->regraNegocio->getUltimaVersaoRegraNegocio( $cd_regra_negocio );
                $arr = array();
                $arr['tx_analise']                  = $textArea;
                $arr['st_item_teste_regra_negocio'] = 'H';
                if(!empty($textArea)){
                    $arr['dt_analise']              = date('Y-m-d');
                    $arr['st_analise']              = "S";
                    $arr['cd_profissional_analise'] = $_SESSION['oasis_logged'][0]['cd_profissional'];
                } else {
                    $rs = false;
                }
                if( empty($codigos_itens[$codigoItemTeste]) ) {
                    $rs = false;
                } else {
                    $where = "cd_item_teste_regra_negocio = {$codigos_itens[$codigoItemTeste]}
                              and
                              cd_regra_negocio = {$cd_regra_negocio}
                              and
                              cd_projeto_regra_negocio = {$cd_projeto}
                              and
                              cd_item_teste = {$codigoItemTeste}
                              and
                              dt_regra_negocio = '{$dtRegraNegocio[0]['dt_regra_negocio']}'
                              ";
                    $rs = $this->itemTesteRegraNegocio->update($arr,$where);
                }
                if( !$rs ){
                    $retorno = false;
                }
            }
        }
        if( $rs ){
            $this->itemTesteRegraNegocio->getDefaultAdapter()->commit();
            $result['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $result['tipo'] = '1';
        } else {
            $this->itemTesteRegraNegocio->getDefaultAdapter()->rollBack();
            $result['msg'] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }

        echo Zend_Json_Encoder::encode($result);
	}
}