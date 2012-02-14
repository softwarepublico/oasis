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

class AssociarRotinaObjetoContratoController extends Base_Controller_Action
{
    private $_objObjetoContratoRotina;

	public function init()
	{
		parent::init();
        $this->_objObjetoContratoRotina = new ObjetoContratoRotina($this->_request->getControllerName());
	}

	public function indexAction()
	{}

    public function pesquisaRotinaObjetoContratoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_objeto           = $this->_request->getParam('cd_objeto',0);
		$cd_area_atuacao_ti  = $this->_request->getParam('cd_area_atuacao_ti',0);

        $rowSetForaObjetoContrato = $this->_objObjetoContratoRotina->pesquisaRotinaForaObjetoContrato($cd_objeto, $cd_area_atuacao_ti);
        $rowSetNoObjetoContrato   = $this->_objObjetoContratoRotina->pesquisaRotinaNoObjetoContrato($cd_objeto, $cd_area_atuacao_ti);

        $arr1 = "";
        foreach ($rowSetForaObjetoContrato as $row) {
            $arr1 .= "<option label=\"{$row->tx_rotina}\" value=\"{$row->cd_rotina}\">{$row->tx_rotina}</option>";
        }

        $arr2 = "";
        foreach ($rowSetNoObjetoContrato as $row) {
            $arr2 .= "<option label=\"{$row->tx_rotina}\" value=\"{$row->cd_rotina}\">{$row->tx_rotina}</option>";
        }

        $retornaOsDois = array($arr1, $arr2);

        echo Zend_Json_Encoder::encode($retornaOsDois);
    }

    public function associarRotinaAction()
    {
        $this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

		$cd_objeto    = $post['cd_objeto'];
		$arrRotina = Zend_Json_Decoder::decode($post['rotinas']);

        $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            foreach ($arrRotina as $rotina) {
                $arrInsert = array();
                $arrInsert['cd_objeto'   ] = $cd_objeto;
                $arrInsert['cd_rotina']    = $rotina;
                $this->_objObjetoContratoRotina->salvarNovaRotinaAoObjetoContrato($arrInsert);
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

    public function desassociarRotinaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

		$cd_objeto    = $post['cd_objeto'];
		$arrRotina = Zend_Json_Decoder::decode($post['rotinas']);

        $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();
            foreach ($arrRotina as $rotina) {
                $arrWhereDelete = array();
                $arrWhereDelete['cd_objeto = ?'   ] = $cd_objeto;
                $arrWhereDelete['cd_rotina = ?']    = $rotina;
                $this->_objObjetoContratoRotina->excluirRotinaDoObjetoContrato($arrWhereDelete);
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

}