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

class ExecucaoRotinaController extends Base_Controller_Action
{
	private $objExecucaoRotina;
	private $objHistoricoExecucaoRotina;
    private $objStatusAtendimento;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ROTINAS'));
		$this->objExecucaoRotina          =  new ExecucaoRotina($this->_request->getControllerName());
        $this->objHistoricoExecucaoRotina = new HistoricoExecucaoRotina($this->_request->getControllerName());
        $this->objStatusAtendimento       = new StatusAtendimento($this->_request->getControllerName());
	}

	public  function indexAction()
	{
	}

	public function gridExecucaoRotinaAction()
	{
		$this->_helper->layout->disableLayout();

		$dt_execucao_rotina  = Base_Util::converterDate($this->_request->getParam('dt_execucao_rotina'), 'DD/MM/YYYY', 'YYYY-MM-DD');

		$cd_objeto           = $_SESSION["oasis_logged"][0]["cd_objeto"];
        $cd_profissional     = $_SESSION["oasis_logged"][0]["cd_profissional"];
        
		$rowsetER   = $this->objExecucaoRotina->getExecucaoRotina($cd_objeto, $dt_execucao_rotina, $cd_profissional);
        
        //Cálculo do Tempo de Resposta da Rotina
        $arrTempoRespostaRotina = array();
		if ($rowsetER->valid()){
            $arrTempoResposta = $this->objStatusAtendimento->getTempoResposta();
			foreach($rowsetER as $row){
				if($row->cd_rotina != ""){
                    $ni_prazo_execucao_rotina = $row->ni_prazo_execucao_rotina;
                    $hi  = $row->dt_execucao_rotina.' '.$row->tx_hora_execucao_rotina;
                    $arr = Base_Controller_Action_Helper_Util::getTempoResposta($arrTempoResposta,$ni_prazo_execucao_rotina,$hi);
					$arrTempoRespostaRotina[$row->cd_rotina] = $arr;
				}
			}
		}
		$this->view->res                    = $rowsetER;
        $this->view->arrTempoRespostaRotina = $arrTempoRespostaRotina;
	}

    public function tabHistoricoExecucaoRotinaAction()
	{
		$this->_helper->layout->disableLayout();

		$objRotina                  = new Rotina($this->_request->getControllerName());

        $arrParams = array();

		$arrParams['dt_execucao_rotina'] = $this->_request->getParam('dt_execucao_rotina');
		$arrParams['cd_profissional']    = $this->_request->getParam('cd_profissional');
		$arrParams['cd_objeto']          = $this->_request->getParam('cd_objeto');
		$arrParams['cd_rotina']          = $this->_request->getParam('cd_rotina');
		$tab_origem                      = $this->_request->getParam('tab_origem');

		$this->view->arrDadosHistorico = $this->objHistoricoExecucaoRotina->getHistoricoExecucaoRotina($arrParams);

		$this->view->arrDadosExecucao  = $this->objExecucaoRotina->getExecucaoRotina($arrParams['cd_objeto'], $arrParams['dt_execucao_rotina'], $arrParams['cd_profissional'], $arrParams['cd_rotina']);

		$this->view->arrDadosRotina    = $objRotina->getDadosRotina($arrParams['cd_objeto'], $arrParams['cd_rotina']);

		$this->view->tab_origem        = $tab_origem;
	}

    public function gridHistoricoExecucaoRotinaAction()
	{
		$this->_helper->layout->disableLayout();

		$arrParams['dt_execucao_rotina'] = $this->_request->getParam('dt_execucao_rotina');
		$arrParams['cd_profissional']    = $this->_request->getParam('cd_profissional');
		$arrParams['cd_objeto']          = $this->_request->getParam('cd_objeto');
		$arrParams['cd_rotina']          = $this->_request->getParam('cd_rotina');

		$this->view->res = $this->objHistoricoExecucaoRotina->getHistoricoExecucaoRotina($arrParams);
	}

    public function registraHistoricoRotinaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$post = $this->_request->getPost();

        $post['dt_historico_rotina'] = Base_Util::converterDatetime($post['dt_historico_rotina'], 'DD/MM/YYYY', 'YYYY-MM-DD');


		if($post['dt_historico_execucao_rotina'] != ""){
			//alterar historico da rotina
			$erros = $this->objHistoricoExecucaoRotina->alterarHistoricoExecucaoRotina($post);
			if ($erros === false) {
				$db->rollback();
				echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
			} else {
				$db->commit();
				echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			}
		} else {
			//Registra historico da rotina
			$erros = $this->objHistoricoExecucaoRotina->salvarHistoricoExecucaoRotina($post);

            if ($erros === true) {
    			$erros = $this->objExecucaoRotina->alterarExecucaoRotina($post);
            }

			if ($erros === false) {
				$db->rollback();
				echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
			} else {
				$db->commit();
				echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
		}
	}
    
	public function excluirHistoricoExecucaoRotinaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrParams['dt_historico_execucao_rotina'] = $this->_request->getParam('dt_historico_execucao_rotina');
		$arrParams['dt_execucao_rotina']           = $this->_request->getParam('dt_execucao_rotina');
		$arrParams['cd_profissional']              = $this->_request->getParam('cd_profissional');
		$arrParams['cd_objeto']                    = $this->_request->getParam('cd_objeto');
		$arrParams['cd_rotina']                    = $this->_request->getParam('cd_rotina');

		$return = $this->objHistoricoExecucaoRotina->excluirHistoricoExecucaoRotina($arrParams);
		$msg = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'): Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');

		echo $msg;
	}

	public function recuperarHistoricoExecucaoRotinaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrParams['dt_historico_execucao_rotina'] = $this->_request->getParam('dt_historico_execucao_rotina');
		$arrParams['dt_execucao_rotina']           = $this->_request->getParam('dt_execucao_rotina');
		$arrParams['cd_profissional']              = $this->_request->getParam('cd_profissional');
		$arrParams['cd_objeto']                    = $this->_request->getParam('cd_objeto');
		$arrParams['cd_rotina']                    = $this->_request->getParam('cd_rotina');

		$arrDados = $this->objHistoricoExecucaoRotina->getHistoricoExecucaoRotina($arrParams)->toArray();

		$arrDados = $arrDados[0];

		$arrDados['dt_historico_rotina'] = date('d/m/Y H:i:s', strtotime($arrDados['dt_historico_rotina']));

		echo  Zend_Json_Encoder::encode($arrDados);
	}

    public function fechaExecucaoRotinaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		$arrParams['dt_execucao_rotina']           = $this->_request->getParam('dt_execucao_rotina');
		$arrParams['cd_profissional']              = $this->_request->getParam('cd_profissional');
		$arrParams['cd_objeto']                    = $this->_request->getParam('cd_objeto');
		$arrParams['cd_rotina']                    = $this->_request->getParam('cd_rotina');

		$erros = $this->objExecucaoRotina->fechaExecucaoRotina($arrParams);

		if ($erros === false) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_FINALIZAR_ROTINA');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_FINALIZAR_ROTINA');
		}
	}
}