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

class AreaAtuacaoTiController extends Base_Controller_Action
{
    private $_objAreaAtuacaoTi;
    
	public function init()
	{
		parent::init();
        $this->_objAreaAtuacaoTi = new AreaAtuacaoTi($this->_request->getControllerName());
	}

	public function indexAction()
	{}

	public function salvarAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();

        $arrResult = array('error'  => false,
                           'typeMsg'=> 1,
                           'msg'    => '');
        try{
            $db = Zend_Registry::get('db');
			$db->beginTransaction();

            $formData['tx_area_atuacao_ti'] = strip_tags($formData['tx_area_atuacao_ti']);
            $arrResult['msg']               = $this->_objAreaAtuacaoTi->salvarAreaAtuacaoTi($formData);

            $db->commit();

        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	public function excluirAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_area_atuacao_ti  = $this->_request->getParam('cd_area_atuacao_ti');
        
        $arrResult = array('error'  => false,
                           'typeMsg'=> 1,
                           'msg'    => Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
        try{
            $db = Zend_Registry::get('db');
			$db->beginTransaction();
            $arrWhere['cd_area_atuacao_ti = ?'] = $cd_area_atuacao_ti;
            $this->_objAreaAtuacaoTi->verificaIntegridadeAreaAtuacao($arrWhere);
            $this->_objAreaAtuacaoTi->excluirAreaAtuacaoTi($arrWhere);

            $db->commit();

        }catch(Base_Exception_Alert $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 2;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = $e->getMessage();
	        $db->rollBack();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
	        $db->rollBack();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	public function recuperarAction()
	{
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData  = $this->_request->getPost();

        $arrWhere['cd_area_atuacao_ti = ?'] = $formData['cd_area_atuacao_ti'];
        $rowSetAreaAtuacaoTi                = $this->_objAreaAtuacaoTi->getAreaAtuacaoTi($arrWhere);

        //pega apenas a row zero pois o retorno é um fetchAll e não fetchRow
        $arrDados = $rowSetAreaAtuacaoTi->getRow(0)->toArray();
        
        echo Zend_Json_Encoder::encode($arrDados);
	}

	public function gridAreaAtuacaoTiAction()
	{
		$this->_helper->layout->disableLayout();
        $this->view->res = $this->_objAreaAtuacaoTi->getAreaAtuacaoTi();
	}
}