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

class CondicaoSubItemMetricaController extends Base_Controller_Action
{
    private $objCondicaoSubItemMetrica;
    private $objDefinicaoMetrica;
    private $objItemMetrica;
    private $objSubItemMetrica;

	public function init()
	{
		parent::init();

        $this->objCondicaoSubItemMetrica = new CondicaoSubItemMetrica($this->_request->getControllerName());
        $this->objDefinicaoMetrica       = new DefinicaoMetrica($this->_request->getControllerName());
        $this->objItemMetrica            = new ItemMetrica($this->_request->getControllerName());
        $this->objSubItemMetrica         = new SubItemMetrica($this->_request->getControllerName());
	}

	public function indexAction(){}

    public function gridCondicaoSubItemMetricaAction()
    {
        $this->_helper->layout->disableLayout();

        $cd_definicao_metrica = $this->_request->getParam('cd_definicao_metrica');
        $cd_item_metrica      = $this->_request->getParam('cd_item_metrica');
        $cd_sub_item_metrica  = $this->_request->getParam('cd_sub_item_metrica');

        $res             = $this->objCondicaoSubItemMetrica->getDadosCondicaoSubItemMetrica( $cd_definicao_metrica , $cd_item_metrica, $cd_sub_item_metrica );
		$this->view->res = $res;
    }

    public function salvarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

        $arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));
        $this->objCondicaoSubItemMetrica->getDefaultAdapter()->beginTransaction();
        try{

			$error = false;

            $novo                               = $this->objCondicaoSubItemMetrica->createRow();
            $novo->cd_condicao_sub_item_metrica = $this->objCondicaoSubItemMetrica->getNextValueOfField("cd_condicao_sub_item_metrica");
            $novo->cd_sub_item_metrica          = $post['cmb_sub_item_metrica_condicao_sub_item'];
            $novo->cd_item_metrica              = $post['cmb_item_metrica_condicao_sub_item'];
            $novo->cd_definicao_metrica         = $post['cmb_definicao_metrica_condicao_sub_item'];
            $novo->tx_condicao_sub_item_metrica = $post['tx_condicao_sub_item_metrica'];
            $novo->ni_valor_condicao_satisfeita = $post['ni_valor_condicao_satisfeita'];

            if( !$novo->save() ){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
                $error = true;
            }

        	if($error){
	        	$this->objCondicaoSubItemMetrica->getDefaultAdapter()->rollBack();
	        }else{
	        	$this->objCondicaoSubItemMetrica->getDefaultAdapter()->commit();
	        }
        }catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function recuperarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post    = $this->_request->getPost();
        $retorno = $this->objCondicaoSubItemMetrica->find( $post['cd_condicao_sub_item_metrica'],
                                                           $post['cd_item_metrica'],
                                                           $post['cd_definicao_metrica'],
                                                           $post['cd_sub_item_metrica'] )
                                                   ->current()
                                                   ->toArray();

        echo Zend_Json::encode($retorno);
    }

    public function alterarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

        $arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'));
        $this->objCondicaoSubItemMetrica->getDefaultAdapter()->beginTransaction();
        try{

			$error = false;

            $arrUpdate['tx_condicao_sub_item_metrica'] = $post['tx_condicao_sub_item_metrica'];
            $arrUpdate['ni_valor_condicao_satisfeita'] = $post['ni_valor_condicao_satisfeita'];

            $where  = "cd_condicao_sub_item_metrica  = {$post['cd_condicao_sub_item_metrica']}  				  and ";
            $where .= "cd_sub_item_metrica           = {$post['cd_sub_item_metrica_condicao_sub_item_hidden']}    and ";
            $where .= "cd_item_metrica               = {$post['cd_item_metrica_condicao_sub_item_hidden']}        and ";
            $where .= "cd_definicao_metrica          = {$post['cd_definicao_metrica_condicao_sub_item_hidden']}       ";

            if( !$this->objCondicaoSubItemMetrica->update($arrUpdate, $where) ){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
                $error = true;
            }

        	if($error){$this->objCondicaoSubItemMetrica->getDefaultAdapter()->rollBack();
	        }else{$this->objCondicaoSubItemMetrica->getDefaultAdapter()->commit();}
            
        }catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

	public function excluirAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

        $arrResult = array('error'=>'', 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
        $this->objCondicaoSubItemMetrica->getDefaultAdapter()->beginTransaction();
        try{

			$error = false;

            $where  = "cd_condicao_sub_item_metrica  = {$post['cd_condicao_sub_item_metrica']} and ";
            $where .= "cd_sub_item_metrica           = {$post['cd_sub_item_metrica']}          and ";
            $where .= "cd_item_metrica               = {$post['cd_item_metrica']}              and ";
            $where .= "cd_definicao_metrica          = {$post['cd_definicao_metrica']}";

            if( !$this->objCondicaoSubItemMetrica->delete( $where ) ){
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
                $error = true;
            }
                
        	if($error){$this->objCondicaoSubItemMetrica->getDefaultAdapter()->rollBack();
	        }else{$this->objCondicaoSubItemMetrica->getDefaultAdapter()->commit();}
            
        }catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }
}