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

class ReuniaoGeralController extends Base_Controller_Action
{
	private $reuniaogeral;

	public function init()
	{
		parent::init();
		$this->reuniaogeral = new ReuniaoGeral($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->_redirect('menu-reuniao-geral');
	}

	public function excluirReuniaoGeralAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_reuniao_geral = (int)$this->_request->getParam('cd_reuniao_geral', 0);
 		if ($this->reuniaogeral->delete("cd_reuniao_geral=$cd_reuniao_geral")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}
	
	public function gridReuniaoGeralAction()
	{
		$this->_helper->layout->disableLayout();

        $cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
		$cd_objeto = ($cd_objeto != 0)?$cd_objeto:null;
		
		$arrReuniaoGeral = $this->reuniaogeral->getDadosReuniaoGeral($cd_objeto);

        $arrDataReuniaoGeral=array();
        foreach ($arrReuniaoGeral as $reuniao_geral){
            $arrDataReuniaoGeral[$reuniao_geral["cd_reuniao_geral"]] = Base_Util::converterDate($reuniao_geral['dt_reuniao'],"YYYY-MM-DD","DD/MM/YYYY");
            $reuniao_geral['tx_ata']                                 = str_ireplace('\"','"',$reuniao_geral['tx_ata']);
        }

		$this->view->res     = $arrReuniaoGeral;
		$this->view->resData = $arrDataReuniaoGeral;
	}
	
	public function salvarDadosReuniaoGeralAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrReuniaoGeral = $this->_request->getPost();
		$return = $this->reuniaogeral->salvarReuniaoGeral($arrReuniaoGeral);
		
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		echo $msg;
	}
	
	public function alterarReuniaoGeralAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrReuniaoGeral = $this->_request->getPost();

        $return = $this->reuniaogeral->alterarReuniaoGeral($arrReuniaoGeral);
		
		$msg = ($return)?Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'):Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		echo $msg;
	}
	
	public function recuperaReuniaoGeralAction()
	{
        
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_reuniao_geral = (int)$this->_request->getParam('cd_reuniao_geral', 0);
		$cd_reuniao_geral = ($cd_reuniao_geral != 0)?$cd_reuniao_geral:null;
		$arrReuniaoGeral = $this->reuniaogeral->getDadosReuniaoGeral(null,$cd_reuniao_geral);

        $arrReuniaoGeral[0]['dt_reuniao'] = Base_Util::converterDate($arrReuniaoGeral[0]['dt_reuniao'],'YYYY-MM-DD','DD/MM/YYYY');
		$arrReuniaoGeral[0]['tx_ata']     = str_ireplace('\"','"',$arrReuniaoGeral[0]['tx_ata']);
		
		echo Zend_Json::encode($arrReuniaoGeral);
	}
}