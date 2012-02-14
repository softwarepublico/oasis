<?php

/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Demanda, Projetos e Serviços de TI.
 * 			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 * 			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 * 			  ou (na sua opnião) qualquer versão.
 * 			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 * 			  sem uma garantia implicita de ADEQUAÇÂO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 * 			  Veja a Licença Pública Geral GNU para maiores detalhes.
 * 			  Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 * 			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 * 			  Fifth Floor, Boston, MA 02110-1301 USA.
 */
class AssociarItemInventarioContratoController extends Base_Controller_Action
{

    private $objContrato;
    private $objInventario;
    private $objContratoItemInventario;

    public function init ()
    {
        parent::init();
        $this->objContrato = new Contrato( $this->_request->getControllerName() );
        $this->objContratoItemInventario = new ContratoItemInventario( $this->_request->getControllerName() );
        $this->objInventario = new Inventario( $this->_request->getControllerName() );
    }

    public function indexAction ()
    {
        //$this->view->arrContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'D');
    }

    public function pesquisaItemInventarioAction ()
    {
        $this->_helper->viewRenderer->setNoRender( true );
        $this->_helper->layout->disableLayout();
        $cd_contrato = $this->_request->getParam( "cd_contrato", 0 );

        //recupera os dados do contrato
        $dadosContrato = $this->objContrato->find( $cd_contrato )->current()->toArray();
        //verifica o status do contrato
        $statusContrato = $dadosContrato['st_contrato'];

        if ($cd_contrato == 0) {
            echo '';
        } else {

            $itemInventariosForaDoContrato = $this->objContratoItemInventario->pesquisaItemInventarioForaDoContrato( $cd_contrato );
            $itemInventariosNoContrato = $this->objContratoItemInventario->pesquisaItemInventarioNoContrato( $cd_contrato );

            $arr1 = "";

            foreach ($itemInventariosForaDoContrato as $fora) {
                $arr1 .= "<option value=\"{$fora['cd_item_inventario']}\">{$fora['tx_item_inventario']}</option>";
            }

            $arr2 = "";
            foreach ($itemInventariosNoContrato as $no) {
                $arr2 .= "<option value=\"{$no['cd_item_inventario']}\">{$no['tx_item_inventario']}</option>";
            }

            $retorno = array($arr1, $arr2, $statusContrato);
            echo Zend_Json_Encoder::encode( $retorno );
        }
    }

    public function associaItemInventarioAction ()
    {
        $this->_helper->viewRenderer->setNoRender( true );
        $this->_helper->layout->disableLayout();
        
        $post = $this->_request->getPost();

        $cd_contrato   = $post['cd_contrato'];
        $cd_inventario = $post['cd_inventario'];
        
        $itemInventarios = Zend_Json_Decoder::decode( $post['item_inventarios'] );
        
        $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();
        
            foreach ($itemInventarios as $itemInventario) {

                $novo = $this->objContratoItemInventario->createRow();
                $novo->cd_contrato        = $cd_contrato;
                $novo->cd_inventario      = $cd_inventario;
                $novo->cd_item_inventario = $itemInventario;
                $novo->save();
            }
            
            $db->commit();
             
        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
    }

    public function desassociaItemInventarioAction ()
    {
        $this->_helper->viewRenderer->setNoRender( true );
        $this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();
        $cd_contrato = $post['cd_contrato'];
        $itemInventarios = Zend_Json_Decoder::decode( $post['item_inventarios'] );

         $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);
         try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();
        
            foreach ($itemInventarios as $itemInventario) {
                $where = "cd_contrato = {$cd_contrato} and cd_item_inventario = {$itemInventario}";
                $this->objContratoItemInventario->delete( $where );
            }
            
            $db->commit();

         }catch(Base_Exception_Error $e){
                $arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = $e->getMessage();
                $db->rollBack();
         }catch(Zend_Exception $e){
                $arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO') . $e->getMessage();
                $db->rollBack();
         }
         echo Zend_Json_Encoder::encode($arrResult);   

    }

    public function pesquisaItemInventarioContratoAction ()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender( true );

        
        $cd_contrato = (int) $this->_request->getParam( "cd_contrato", 0 );
        $arrItemInventarios = $this->objContratoItemInventario->listaItemInventariosContrato( $cd_contrato, true );

        $options = '';

        foreach ($arrItemInventarios as $key => $value) {
            $options .= "<option value=\"{$key}\">{$value}</option>";
        }
        echo $options;
    }

}