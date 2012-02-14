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

class ItemMetricaController extends Base_Controller_Action
{
    private $objItemMetrica;
    private $objSubItemMetrica;

	public function init()
	{
		parent::init();

        $this->objItemMetrica = new ItemMetrica($this->_request->getControllerName());
        $this->objSubItemMetrica = new SubItemMetrica($this->_request->getControllerName());
	}


	public function indexAction(){}

    public function getComboItemMetricaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_definicao_metrica = $this->_request->getParam("cd_definicao_metrica", 0);
        $arrItemMetrica       = $this->objItemMetrica->getComboItemMetrica(true, $cd_definicao_metrica);

        $strOption = "";
        foreach( $arrItemMetrica as $cd_item_metrica=>$tx_item_metrica ){
            $strOption .= "<option value=\"{$cd_item_metrica}\">{$tx_item_metrica}</option>";
        }
        echo $strOption;
    }

    public function salvarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

        $arrResult = array(
            'error'    => false, 
            'errorType'=> 1, 
            'msg'      => Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO')
        );
        try{
            $this->objItemMetrica->getDefaultAdapter()->beginTransaction();
            
            $novo                           = $this->objItemMetrica->createRow();
            $novo->cd_item_metrica          = $this->objItemMetrica->getNextValueOfField("cd_item_metrica");
            $novo->cd_definicao_metrica     = $post['cmb_definicao_metrica_item'];
            $novo->tx_item_metrica          = $post['tx_item_metrica'];
            $novo->tx_formula_item_metrica  = $post['tx_formula_item_metrica'];
            $novo->tx_variavel_item_metrica = $post['tx_variavel_item_metrica'];
            $novo->ni_ordem_item_metrica    = ($post['ni_ordem_item_metrica']) ? $post['ni_ordem_item_metrica'] : null;

            if( !$novo->save() )
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO'));
            
            $this->objItemMetrica->getDefaultAdapter()->commit();
        }catch(Base_Exception_Alert $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
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

        $post      = $this->_request->getPost();
        $arrResult = array(
            'error'    => false, 
            'errorType'=> 1, 
            'msg'      => Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO')
        );
        try{
            $this->objItemMetrica->getDefaultAdapter()->beginTransaction();
            
            $arrUpdate['tx_item_metrica'         ] = $post['tx_item_metrica'];
            $arrUpdate['tx_formula_item_metrica' ] = $post['tx_formula_item_metrica'];
            $arrUpdate['tx_variavel_item_metrica'] = $post['tx_variavel_item_metrica'];
            $arrUpdate['ni_ordem_item_metrica'   ] = $post['ni_ordem_item_metrica'];

            $where  = array(
                " cd_item_metrica = ?"      => $post['cd_item_metrica'],
                " cd_definicao_metrica = ?" => $post['cd_definicao_metrica_hidden']
            );

            if( !$this->objItemMetrica->update($arrUpdate, $where) )
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));

            $this->objItemMetrica->getDefaultAdapter()->commit();
        }catch(Base_Exception_Alert $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
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
        try{
            $this->objItemMetrica->getDefaultAdapter()->beginTransaction();

            $where = array(
                "cd_item_metrica = ?"      => $post['cd_item_metrica'],
                "cd_definicao_metrica = ?" => $post['cd_definicao_metrica']
            );
            $this->verificaExistenciaRegistro( $post['cd_item_metrica'], $post['cd_definicao_metrica'] )
                 ->verificaAssociacaoRegistro( $where );

            if( !$this->objItemMetrica->delete( $where ) )
                throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
            
            $this->objItemMetrica->getDefaultAdapter()->commit();
        }catch(Base_Exception_Alert $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $this->objItemMetrica->getDefaultAdapter()->rollBack();
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
        $retorno = $this->objItemMetrica->find( $post['cd_item_metrica'],$post['cd_definicao_metrica'])->current()->toArray();
        echo Zend_Json::encode($retorno);
    }

    public function gridItemMetricaAction()
    {
        $this->_helper->layout->disableLayout();
		$this->view->res = $this->objItemMetrica->getDadosItemMetrica(null, $this->_request->getParam('cd_definicao_metrica', 0));
    }

    private function verificaExistenciaRegistro( $cd_item_metrica, $cd_definicao_metrica )
    {
        $res = $this->objItemMetrica->find($cd_item_metrica, $cd_definicao_metrica)->current();
        if( count($res) == 0 )
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_ITEM_METRICA_NAO_CADASTRADO'));
        
        return $this;
    }

    private function verificaAssociacaoRegistro( array $where )
    {
        if( $this->objSubItemMetrica->fetchAll($where)->valid() )
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO'));
        
        return $this;
    }
}