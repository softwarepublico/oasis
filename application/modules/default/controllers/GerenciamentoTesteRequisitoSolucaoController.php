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

class GerenciamentoTesteRequisitoSolucaoController extends Base_Controller_Action
{
    private $itemTesteRequisito;
    private $requisito;

	public function init()
	{
		parent::init();
        $this->itemTesteRequisito                    = new ItemTesteRequisito($this->_request->getControllerName());
        $this->requisito                             = new Requisito($this->_request->getControllerName());
		$this->view->permissao_requisito             = (ChecaPermissao::possuiPermissao('gerenciamento-teste-requisito'            ) === true)? 1:0;
        $this->view->permissao_requisito_analise     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-requisitoo-analise'   ) === true)? 1:0;
        $this->view->permissao_requisito_solucao     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-requisito-solucao'    ) === true)? 1:0;
        $this->view->permissao_requisito_homologacao = (ChecaPermissao::possuiPermissao('gerenciamento-teste-requisito-homologacao') === true)? 1:0;
	}

	public function indexAction()
	{
		 $this->initView();
	}

	public function gridGerenciamentoTesteRequisitoSolucaoAction()
	{
	    $this->_helper->layout->disableLayout();
        $cd_requisito = $this->_request->getPost('cd_requisito',null);
        $cd_projeto   = $this->_request->getPost('cd_projeto',null);
        if( is_null($cd_requisito) || is_null($cd_projeto) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $this->view->res = $this->itemTesteRequisito->getGridItemTesteRequisitoAll('solucao',$cd_requisito,$cd_projeto);
    }

    public function saveAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_projeto   = $this->_request->getPost('cd_projeto',null);
        $cd_requisito = $this->_request->getPost('cd_requisito',null);
        if( is_null($cd_projeto) || is_null($cd_requisito) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $valCd = 'cd_item_teste_requisito__';
        $tamCd = strlen($valCd);
        $valTx = 'tx_solucao__';
        $tamTx = strlen($valTx);
        foreach($_POST as $k=>$v){
            if( substr($k,0,$tamCd) == $valCd ){
                $codigos_itens[substr($k,$tamCd)] = $v;
            }
            if( substr($k,0,$tamTx) == $valTx ){
                $solucoes[substr($k,$tamTx)] = $v;
            }
        }
        $qtdSolucoes    = count($solucoes);
        $qtdCodigoItens = count($codigos_itens);
        if( $qtdSolucoes != $qtdCodigoItens ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_QUANTIDADE_INPUTS'),'tipo'=>'3')));
        }

        $retorno = true;
        $result  = array();
        $rs      = false;

        $this->itemTesteRequisito->getDefaultAdapter()->beginTransaction();

        foreach( $solucoes as $codigoItemTeste => $textArea ){
            if( $retorno ){
                $textArea = trim($textArea);
                $dtRequisito = $this->requisito->getUltimaVersaoRequisito( $cd_projeto,$cd_requisito );
                $arr = array();
                $arr['cd_item_teste']       = $codigoItemTeste;
                $arr['tx_solucao']          = $textArea;
                $arr['dt_versao_requisito'] = $dtRequisito[0]['dt_versao_requisito'];
                
                if(!empty($textArea)){
                    $arr['dt_solucao']              = date('Y-m-d');
                    $arr['st_solucao']              = "S";
                    $arr['cd_profissional_solucao'] = $_SESSION['oasis_logged'][0]['cd_profissional'];
                } else {
                    $arr['dt_solucao']              = null;
                    $arr['st_solucao']              = null;
                    $arr['cd_profissional_solucao'] = null;
                }
                if( empty($codigos_itens[$codigoItemTeste]) ) { // novo...
                    $arr['cd_item_teste_requisito'] = $this->itemTesteRequisito->getNextValueOfField('cd_item_teste_requisito');
                    $arr['cd_requisito'           ] = $cd_requisito;
                    $arr['cd_projeto'             ] = $cd_projeto;
                    $rs = $this->itemTesteRequisito->insert($arr);
                } else {
                    $where = "cd_item_teste_requisito = {$codigos_itens[$codigoItemTeste]}
                              and
                              cd_requisito = {$cd_requisito}
                              and
                              cd_projeto = {$cd_projeto}
                              and
                              cd_item_teste = {$codigoItemTeste}
                              and
                              dt_versao_requisito = '{$dtRequisito[0]['dt_versao_requisito']}'
                              ";
                    $rs = $this->itemTesteRequisito->update($arr,$where);
                }
                if( !$rs ){
                    $retorno = false;
                }
            }
        }
        if( $rs ){
            $this->itemTesteRequisito->getDefaultAdapter()->commit();
            $result['msg' ] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $result['tipo'] = '1';
        } else {
            $this->itemTesteRequisito->getDefaultAdapter()->rollBack();
            $result['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }
        echo Zend_Json_Encoder::encode($result);
    }
}