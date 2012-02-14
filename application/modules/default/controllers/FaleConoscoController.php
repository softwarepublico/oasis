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

class FaleConoscoController extends Base_Controller_Action 
{
    private $objFaleConosco;
    private $objUtil;
	
	public function init()
	{
		parent::init();
		$this->objFaleConosco = new FaleConosco($this->_request->getControllerName());
        $this->objUtil        = new Base_Controller_Action_Helper_Util();
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_FALE_CONOSCO'));
        $this->view->mesAno = $this->objUtil->getMes(date('n')).'/'.date('Y');
	}
	
	public function salvarFaleConoscoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		$arrResult = array('error'=>false,'errorType'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ENVIO_MENSAGEM'), 'comUrl'=>false, 'url'=>'');
		
		if(count($_SESSION) > 0 ){
			$arrResult['comUrl'] = true;
			$arrResult['url'   ] = '/inicio';
		}
		
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
		
			$arrPost['st_respondida'] = "N";
			$arrPost['dt_registro']   = date("Y-m-d H:i:s");
		
			$return = $this->objFaleConosco->salvarFaleConosco($arrPost);
		
			if($return){
				$db->commit();
			} else {
				$db->rollBack();
				throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ENVIO_MENSAGEM'));
			}
		}catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'      ] = $e->getMessage();
		} catch(Zend_Exception $e) {
			$arrResult['error'	  ] = true;
			$arrResult['errorType'] = 3;
			$arrResult['msg'      ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function gridFaleConoscoMensagemAbertaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes       = $this->_request->getParam('mes');
		$ano       = $this->_request->getParam('ano');

		$mes       = ($mes == 0)?date('n'):$mes;
		$ano       = ($ano == 0)?date('Y'):$ano;

		$mesAno    = $this->objUtil->getMes($mes);
		$mesAno    = $mesAno."/".$ano;
        $mes       = substr("00".$mes,-2);

		$arrFaleConoscoMensagemAberta = $this->objFaleConosco->getMensagemFaleConosco($mes, $ano, 'A');

		$this->view->res    = $arrFaleConoscoMensagemAberta;
		$this->view->mesAno = $mesAno;
	}

	public function gridFaleConoscoMensagemPendenteAction()
	{
		$this->_helper->layout->disableLayout();

		$mes       = $this->_request->getParam('mes');
		$ano       = $this->_request->getParam('ano');

		$mes       = ($mes == 0)?date('n'):$mes;
		$ano       = ($ano == 0)?date('Y'):$ano;

		$mesAno    = $this->objUtil->getMes($mes);
		$mesAno    = $mesAno."/".$ano;
        $mes       = substr("00".$mes,-2);

		$arrFaleConoscoMensagemPendente = $this->objFaleConosco->getMensagemFaleConosco($mes, $ano, 'P');

		$this->view->res    = $arrFaleConoscoMensagemPendente;
		$this->view->mesAno = $mesAno;
	}

	public function gridFaleConoscoMensagemRespondidaAction()
	{
		$this->_helper->layout->disableLayout();

		$mes       = $this->_request->getParam('mes');
		$ano       = $this->_request->getParam('ano');

		$mes       = ($mes == 0)?date('n'):$mes;
		$ano       = ($ano == 0)?date('Y'):$ano;

		$mesAno    = $this->objUtil->getMes($mes);
		$mesAno    = $mesAno."/".$ano;
        $mes       = substr("00".$mes,-2);

		$arrFaleConoscoMensagemRespondida = $this->objFaleConosco->getMensagemFaleConosco($mes, $ano, 'R');

		$this->view->res    = $arrFaleConoscoMensagemRespondida;
		$this->view->mesAno = $mesAno;
	}

    public function tabRespostaAction()
    {
        $this->_helper->layout->disableLayout();

        $cd_fale_conosco = $this->_request->getParam('cd_fale_conosco');
        $tab_origem      = $this->_request->getParam('tab_origem');

        $arrFaleConosco  = $this->objFaleConosco->find($cd_fale_conosco)->current()->toArray();

        $arrFaleConosco['dt_registro'] = date('d/m/Y H:m:s', strtotime($arrFaleConosco['dt_registro']));

        $this->view->arrFaleConosco = $arrFaleConosco;
        $this->view->tab_origem     = $tab_origem;
    }

    public function salvarRespostaAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $arrPost = $this->_request->getPost();

        $arrUpdate = array();
        $arrUpdate['tx_resposta'  ] = $arrPost['tx_resposta'];
        $arrUpdate['st_respondida'] = "S";
        $arrUpdate['st_pendente'  ] = ($arrPost['st_pendente'] == "S") ? $arrPost['st_pendente'] : null;
        $where                      = array('cd_fale_conosco = ?'=>$arrPost['cd_fale_conosco']);

        $msg = ($this->objFaleConosco->update($arrUpdate, $where)) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'): Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');

        echo $msg;
    }
}