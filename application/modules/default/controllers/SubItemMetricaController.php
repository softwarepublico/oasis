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

class SubItemMetricaController extends Base_Controller_Action
{
    private $objSubItemMetrica;
    private $objItemMetrica;
    private $objPropostaSubItemMetrica;

	public function init()
	{
		parent::init();

        $this->objSubItemMetrica         = new SubItemMetrica($this->_request->getControllerName());
        $this->objItemMetrica            = new ItemMetrica($this->_request->getControllerName());
        $this->objPropostaSubItemMetrica = new PropostaSubItemMetrica($this->_request->getControllerName());
	}

	public function indexAction(){}

    public function getComboSubItemMetricaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_definicao_metrica = $this->_request->getParam("cd_definicao_metrica", 0);
        $cd_item_metrica      = $this->_request->getParam("cd_item_metrica", 0);
        $arrSubItemMetrica    = $this->objSubItemMetrica->getComboSubItemMetrica(true, $cd_definicao_metrica, $cd_item_metrica);

        $strOption = "";
        foreach( $arrSubItemMetrica as $cd_sub_item_metrica=>$tx_sub_item_metrica ){
            $strOption .= "<option value=\"{$cd_sub_item_metrica}\">{$tx_sub_item_metrica}</option>";
        }
        echo $strOption;
    }

    public function gridSubItemMetricaAction()
    {
        $this->_helper->layout->disableLayout();

        $cd_definicao_metrica = $this->_request->getPost('cd_definicao_metrica');
        $cd_item_metrica      = $this->_request->getPost('cd_item_metrica');

        $res             = $this->objSubItemMetrica->getDadosSubItemMetrica( $cd_definicao_metrica , $cd_item_metrica );
		$this->view->res = $res;
    }

    public function salvarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

        $arrResult = array('error'=>false, 'errorType'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));
        try{
            $this->objSubItemMetrica->getDefaultAdapter()->beginTransaction();
            
            $novo                               = $this->objSubItemMetrica->createRow();
            $novo->cd_sub_item_metrica          = $this->objSubItemMetrica->getNextValueOfField("cd_sub_item_metrica");
            $novo->cd_item_metrica              = $post['cmb_item_metrica_sub_item'];
            $novo->cd_definicao_metrica         = $post['cmb_definicao_metrica_sub_item'];
            $novo->tx_sub_item_metrica          = $post['tx_sub_item_metrica'];
            $novo->tx_variavel_sub_item_metrica = $post['tx_variavel_sub_item_metrica'];
            $novo->ni_ordem_sub_item_metrica    = $post['ni_ordem_sub_item_metrica'];
            $novo->st_interno                   = ($post['st_interno']== 'S') ? "S" : null ;

            if( !$novo->save() )
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));

            $this->objSubItemMetrica->getDefaultAdapter()->commit();
        }catch(Base_Exception_Alert $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function alterarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

        $arrResult = array('error'=>false, 'errorType'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'));
        try{
            $this->objSubItemMetrica->getDefaultAdapter()->beginTransaction();

            $arrUpdate['tx_sub_item_metrica'         ] = $post['tx_sub_item_metrica'];
            $arrUpdate['tx_variavel_sub_item_metrica'] = $post['tx_variavel_sub_item_metrica'];
            $arrUpdate['ni_ordem_sub_item_metrica'   ] = $post['ni_ordem_sub_item_metrica'];
            $arrUpdate['st_interno'                  ] = ($post['st_interno']== 'S') ? "S" : null ;

            $where  = array(
                "cd_sub_item_metrica = ?"  => $post['cd_sub_item_metrica'],
                "cd_item_metrica = ?"      => $post['cd_item_metrica_hidden'],
                "cd_definicao_metrica = ?" => $post['cd_definicao_metrica_sub_item_hidden']
            );

            if( !$this->objSubItemMetrica->update($arrUpdate, $where) )
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));

            $this->objSubItemMetrica->getDefaultAdapter()->commit();
        }catch(Base_Exception_Alert $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
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

        $arrResult = array('error'=>false, 'errorType'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
        $this->objSubItemMetrica->getDefaultAdapter()->beginTransaction();
        try{

            $where  = array(
                "cd_sub_item_metrica  = ?" => $post['cd_sub_item_metrica'],
                "cd_item_metrica      = ?" => $post['cd_item_metrica'],
                "cd_definicao_metrica = ?" => $post['cd_definicao_metrica']
            );
            $this->verificaExistenciaRegistro( $post['cd_sub_item_metrica'], $post['cd_item_metrica'], $post['cd_definicao_metrica'] )
                 ->verificaAssociacaoRegistro( $where );


            if( !$this->objSubItemMetrica->delete( $where ) )
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
            
            $this->objSubItemMetrica->getDefaultAdapter()->commit();
        }catch(Base_Exception_Alert $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $this->objSubItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

    private function verificaExistenciaRegistro( $cd_sub_item_metrica, $cd_item_metrica, $cd_definicao_metrica )
    {
        $res = $this->objSubItemMetrica->find( $cd_sub_item_metrica, $cd_definicao_metrica, $cd_item_metrica )->current();
        if( count($res) == 0 ){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
        }
        return $this;
    }

    private function verificaAssociacaoRegistro( array $where )
    {
        if( $this->objPropostaSubItemMetrica->fetchAll( $where )->valid() ){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO'));
        }
        return $this;
    }

    public function recuperarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post    = $this->_request->getPost();
        $retorno = $this->objSubItemMetrica->find( $post['cd_sub_item_metrica'], $post['cd_definicao_metrica'], $post['cd_item_metrica'] )->current()->toArray();
        
        echo Zend_Json::encode($retorno);
    }
}