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

class GerenciamentoTesteCasoUsoSolucaoController extends Base_Controller_Action
{

	public function init()
	{
		parent::init();
		$this->view->permissao_casoDeUso             = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso') === true)? 1:0;
        $this->view->permissao_casoDeUso_analise     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-analise') === true)? 1:0;
        $this->view->permissao_casoDeUso_solucao     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-solucao') === true)? 1:0;
        $this->view->permissao_casoDeUso_homologacao = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-homologacao') === true)? 1:0;
	}

	public function indexAction()
	{
		 $this->initView();
	}

	public function gridGerenciamentoTesteCasoUsoSolucaoAction()
	{
	    $this->_helper->layout->disableLayout();
        $cd_caso_de_uso = $this->_request->getPost('cd_caso_de_uso',null);
        $cd_projeto     = $this->_request->getPost('cd_projeto',null);
        $cd_modulo      = $this->_request->getPost('cd_modulo',null);
        if( is_null($cd_caso_de_uso) || is_null($cd_projeto) || is_null($cd_modulo)){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $this->itemTesteRegraNegocio = new ItemTesteCasoDeUso($this->_request->getControllerName());
        $this->view->res = $this->itemTesteRegraNegocio->getGridItemTesteCasoDeUsoAll('solucao',$cd_caso_de_uso,$cd_projeto,$cd_modulo);
    }

    public function saveAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_projeto     = $this->_request->getPost('cd_projeto',null);
        $cd_caso_de_uso = $this->_request->getPost('cd_caso_de_uso',null);
        $cd_modulo      = $this->_request->getPost('cd_modulo',null);
        if( is_null($cd_projeto) || is_null($cd_caso_de_uso) || is_null($cd_modulo) ){
            die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
        }
        $valCd = 'cd_item_teste_caso_de_uso__';
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

        $this->itemTesteCasoDeUso = new ItemTesteCasoDeUso($this->_request->getControllerName());
        $this->casoDeUso          = new CasoDeUso($this->_request->getControllerName());

        $this->itemTesteCasoDeUso->getDefaultAdapter()->beginTransaction();

        foreach( $solucoes as $codigoItemTeste => $textArea ){
            if( $retorno ){
                $textArea = trim($textArea);
                $dtCasoDeUso = $this->casoDeUso->getUltimaVersaoCasoDeUso( $cd_caso_de_uso,$cd_projeto,$cd_modulo );
                $arr = array();
                $arr['cd_item_teste']         = $codigoItemTeste;
                $arr['tx_solucao']            = $textArea;
                $arr['dt_versao_caso_de_uso'] = $dtCasoDeUso['dt_versao_caso_de_uso'];
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
                    $arr['cd_item_teste_caso_de_uso'] = $this->itemTesteCasoDeUso->getNextValueOfField('cd_item_teste_caso_de_uso');
                    $arr['cd_caso_de_uso'] = $cd_caso_de_uso;
                    $arr['cd_projeto'] = $cd_projeto;
                    $arr['cd_modulo'] = $cd_modulo;
                    $rs = $this->itemTesteCasoDeUso->insert($arr);
                } else {
                    $where = "cd_item_teste_caso_de_uso = {$codigos_itens[$codigoItemTeste]}
                              and
                              cd_caso_de_uso = {$cd_caso_de_uso}
                              and
                              cd_projeto = {$cd_projeto}
                              and
                              cd_item_teste = {$codigoItemTeste}
                              and
                              dt_versao_caso_de_uso = '{$dtCasoDeUso['dt_versao_caso_de_uso']}'
                              and
                              cd_modulo = '{$cd_modulo}'
                              ";
                    $rs = $this->itemTesteCasoDeUso->update($arr,$where);
                }
                if( !$rs ){
                    $retorno = false;
                }
            }
        }
        if( $rs ){
            $this->itemTesteCasoDeUso->getDefaultAdapter()->commit();
            $result['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
            $result['tipo'] = '1';
        } else {
            $this->itemTesteCasoDeUso->getDefaultAdapter()->rollBack();
            $result['msg'] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $result['tipo'] = '3';
        }

        echo Zend_Json_Encoder::encode($result);
    }
}