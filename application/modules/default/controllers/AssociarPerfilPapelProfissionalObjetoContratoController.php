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

class AssociarPerfilPapelProfissionalObjetoContratoController extends Base_Controller_Action
{
    private $_objObjetoContratoPapelProfissional;
    private $_objObjetoContratoPerfilProfissional;

	public function init()
	{
		parent::init();
        $this->_objObjetoContratoPapelProfissional  = new ObjetoContratoPapelProfissional($this->_request->getControllerName());
        $this->_objObjetoContratoPerfilProfissional = new ObjetoContratoPerfilProfissional($this->_request->getControllerName());
	}

	public function indexAction()
	{}

/** funcionalidades da aba de perfil profissional **/
    public function pesquisaPerfilObjetoContratoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_objeto 			= $this->_request->getParam('cd_objeto',0);
		$cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao_ti',0);

        $rowSetForaObjetoContrato = $this->_objObjetoContratoPerfilProfissional->pesquisaPerfilForaObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $rowSetNoObjetoContrato   = $this->_objObjetoContratoPerfilProfissional->pesquisaPerfilNoObjetoContrato($cd_objeto, $cd_area_atuacao_ti);

        $arr1 = "";
        foreach ($rowSetForaObjetoContrato as $row) {
            $arr1 .= "<option label=\"{$row->tx_perfil_profissional}\" value=\"{$row->cd_perfil_profissional}\">{$row->tx_perfil_profissional}</option>";
        }

        $arr2 = "";
        foreach ($rowSetNoObjetoContrato as $row) {
            $arr2 .= "<option label=\"{$row->tx_perfil_profissional}\" value=\"{$row->cd_perfil_profissional}\">{$row->tx_perfil_profissional}</option>";
        }

        $retornaOsDois = array($arr1, $arr2);

        echo Zend_Json_Encoder::encode($retornaOsDois);
    }

    public function gridPerfilProfissionalObjetoContratoAction()
    {
		$this->_helper->layout->disableLayout();

        $cd_objeto 			= $this->_request->getParam('cd_objeto',0);
		$cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao_ti',0);

        $rowSetPerfilNoObjetoContrato         = $this->_objObjetoContratoPerfilProfissional
                                                     ->pesquisaPerfilNoObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $this->view->rowSetPerfilProfissional = $rowSetPerfilNoObjetoContrato;
    }

    public function associarPerfilObjetoContratoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();


		$cd_objeto = $post['cd_objeto'];
		$arrPerfil = Zend_Json_Decoder::decode($post['perfis']);

        $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            foreach ($arrPerfil as $perfil) {
                $arrInsert = array();
                $arrInsert['cd_objeto'             ] = $cd_objeto;
                $arrInsert['cd_perfil_profissional'] = $perfil;
                $this->_objObjetoContratoPerfilProfissional->salvarNovoPerfilProfissionalAoObjetoContrato($arrInsert);
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

    public function desassociarPerfilObjetoContratoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();


		$cd_objeto = $post['cd_objeto'];
		$arrPerfil = Zend_Json_Decoder::decode($post['perfis']);
        $arrResult = array('error'=>false, 'typeMsg'=>1, 'msg'=>'');

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();
            foreach ($arrPerfil as $perfil) {
                $arrDelete = array();
                $arrDelete['cd_objeto = ?'             ] = $cd_objeto;
                $arrDelete['cd_perfil_profissional = ?'] = $perfil;
                $this->_objObjetoContratoPerfilProfissional->excluirPerfilProfissionalAoObjetoContrato($arrDelete);
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

    public function salvarDescricaoPerfilProfissionalAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData  = $this->_request->getPost();
        $arrResult = array('error'=>false, 'typeMsg'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            $this->_objObjetoContratoPerfilProfissional->salvarDescricaoPerfilProfissional($formData);

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

/** funcionalidades da aba de papel profissional **/
    public function gridPapelProfissionalObjetoContratoAction()
    {
		$this->_helper->layout->disableLayout();

        $cd_objeto 			= $this->_request->getParam('cd_objeto',0);
		$cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao_ti',0);

        $rowSetPapelNoObjetoContrato         = $this->_objObjetoContratoPapelProfissional
                                                    ->pesquisaPapelNoObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $this->view->rowSetPapelProfissional = $rowSetPapelNoObjetoContrato;
    }

    public function pesquisaPapelObjetoContratoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_objeto 			= $this->_request->getParam('cd_objeto',0);
		$cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao_ti',0);

        $rowSetForaObjetoContrato = $this->_objObjetoContratoPapelProfissional->pesquisaPapelForaObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $rowSetNoObjetoContrato   = $this->_objObjetoContratoPapelProfissional->pesquisaPapelNoObjetoContrato($cd_objeto, $cd_area_atuacao_ti);

        $arr1 = "";
        foreach ($rowSetForaObjetoContrato as $row) {
            $arr1 .= "<option label=\"{$row->tx_papel_profissional}\" value=\"{$row->cd_papel_profissional}\">{$row->tx_papel_profissional}</option>";
        }

        $arr2 = "";
        foreach ($rowSetNoObjetoContrato as $row) {
            $arr2 .= "<option label=\"{$row->tx_papel_profissional}\" value=\"{$row->cd_papel_profissional}\">{$row->tx_papel_profissional}</option>";
        }

        $retornaOsDois = array($arr1, $arr2);

        echo Zend_Json_Encoder::encode($retornaOsDois);
    }

    public function associarPapelObjetoContratoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();


		$cd_objeto = $post['cd_objeto'];
		$arrPapel = Zend_Json_Decoder::decode($post['papeis']);

        $arrResult = array('error'=>false, 'typeMsg'=>1, 'msg'=>'');

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            foreach ($arrPapel as $papel) {
                $arrInsert = array();
                $arrInsert['cd_objeto'            ] = $cd_objeto;
                $arrInsert['cd_papel_profissional'] = $papel;
                $this->_objObjetoContratoPapelProfissional->salvarNovoPapelProfissionalAoObjetoContrato($arrInsert);
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

    public function desassociarPapelObjetoContratoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();


		$cd_objeto = $post['cd_objeto'];
		$arrPapel = Zend_Json_Decoder::decode($post['papeis']);
        $arrResult = array('error'=>false, 'typeMsg'=>1, 'msg'=>'');

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();
            foreach ($arrPapel as $papel) {
                $arrDelete = array();
                $arrDelete['cd_objeto = ?'            ] = $cd_objeto;
                $arrDelete['cd_papel_profissional = ?'] = $papel;
                $this->_objObjetoContratoPapelProfissional->excluirPapelProfissionalAoObjetoContrato($arrDelete);
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

    public function salvarDescricaoPapelProfissionalAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData  = $this->_request->getPost();
        $arrResult = array('error'=>false, 'typeMsg'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            $this->_objObjetoContratoPapelProfissional->salvarDescricaoPapelProfissional($formData);

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

}