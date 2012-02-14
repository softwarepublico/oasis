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

class GerenciamentoRotinaController extends Base_Controller_Action
{
	private $objObjetoContratoRotina;
	private $objRotinaProfissional;
	private $objExecucaoRotina;
    private $objStatusAtendimento;

	public function init()
	{
		parent::init();
		$this->objObjetoContratoRotina =  new ObjetoContratoRotina($this->_request->getControllerName());
		$this->objRotinaProfissional   =  new RotinaProfissional($this->_request->getControllerName());
		$this->objExecucaoRotina       =  new ExecucaoRotina($this->_request->getControllerName());
        $this->objStatusAtendimento    = new StatusAtendimento($this->_request->getControllerName());
	}

	public  function indexAction()
	{
        //aba Designação de Rotina
        $cd_objeto             = $_SESSION["oasis_logged"][0]["cd_objeto"];
        $this->view->cd_objeto = $cd_objeto;
        $this->view->arrRotina = $this->objObjetoContratoRotina->getRotinaObjetoContrato($cd_objeto, true);

        //aba Execução de Rotina
        //Combo Profissional
		$profissional                      = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$arrProfissional                   = $profissional->getProfissionalGerenteTecnicoObjetoContrato($cd_objeto, true);
		$this->view->cd_profissional_combo = $arrProfissional;

	}

    public function pesquisaRotinaProfissionalAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_objeto  = $this->_request->getParam('cd_objeto',0);
		$cd_rotina  = $this->_request->getParam('cd_rotina',0);

        $rowSetForaRotina = $this->objRotinaProfissional->pesquisaProfissionalForaRotina($cd_objeto, $cd_rotina);
        $rowSetNaRotina   = $this->objRotinaProfissional->pesquisaProfissionalNaRotina($cd_objeto, $cd_rotina);

        $arr1 = "";
        foreach ($rowSetForaRotina as $row) {
            $arr1 .= "<option label=\"{$row->tx_profissional}\" value=\"{$row->cd_profissional}\">{$row->tx_profissional}</option>";
        }

        $arr2 = "";
        foreach ($rowSetNaRotina as $row) {
            $arr2 .= "<option label=\"{$row->tx_profissional}\" value=\"{$row->cd_profissional}\">{$row->tx_profissional}</option>";
        }

        $retornaOsDois = array($arr1, $arr2);

        echo Zend_Json_Encoder::encode($retornaOsDois);
    }

    public function associarProfissionalAction()
    {
        $this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

		$cd_objeto       = $post['cd_objeto'];
		$cd_rotina       = $post['cd_rotina'];
		$arrProfissional = Zend_Json_Decoder::decode($post['profissionais']);

        $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            foreach ($arrProfissional as $profissional) {
                $arrInsert = array();
                $arrInsert['cd_objeto'   ]    = $cd_objeto;
                $arrInsert['cd_rotina'   ]    = $cd_rotina;
                $arrInsert['cd_profissional'] = $profissional;
                $this->objRotinaProfissional->salvarNovoProfissionalRotina($arrInsert);
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

    public function desassociarProfissionalAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $post = $this->_request->getPost();

		$cd_objeto       = $post['cd_objeto'];
        $cd_rotina       = $post['cd_rotina'];
		$arrProfissional = Zend_Json_Decoder::decode($post['profissionais']);

        $arrResult = array('error'=>false, 'typeMsg'=>null, 'msg'=>null);

        try{
            $db = Zend_Registry::get('db');
            $db->beginTransaction();
            foreach ($arrProfissional as $profissional) {
                $arrWhereDelete = array();
                $arrWhereDelete['cd_objeto = ?']       = $cd_objeto;
                $arrWhereDelete['cd_rotina = ?']       = $cd_rotina;
                $arrWhereDelete['cd_profissional = ?'] = $profissional;
                $this->objRotinaProfissional->excluirProfissionalRotina($arrWhereDelete);
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

	public function gridExecucaoRotinaAction()
	{
		$this->_helper->layout->disableLayout();

		$dt_execucao_rotina  = Base_Util::converterDate($this->_request->getParam('dt_execucao_rotina'), 'DD/MM/YYYY', 'YYYY-MM-DD');

		//parâmetro opcional
		if ($this->_request->getParam('cd_profissional') != -1) {
            $cd_profissional = $this->_request->getParam('cd_profissional');
		} else {
			$cd_profissional = null;
		}

		$cd_objeto         = $_SESSION["oasis_logged"][0]["cd_objeto"];
		$arrExecucaoRotina = $this->objExecucaoRotina->getExecucaoRotina($cd_objeto, $dt_execucao_rotina, $cd_profissional);

        //Cálculo do Tempo de Resposta da Rotina
        $arrTempoRespostaRotina = array();
		if (count($arrExecucaoRotina->toArray()) > 0){

            $arrTempoResposta = $this->objStatusAtendimento->getTempoResposta();

			foreach($arrExecucaoRotina as $key=>$value){
				if($value['cd_rotina'] != ""){
                    $ni_prazo_execucao_rotina = $value['ni_prazo_execucao_rotina'];
                    $hi  = $value['dt_execucao_rotina'].' '.$value['tx_hora_execucao_rotina'];
                    $arr = Base_Controller_Action_Helper_Util::getTempoResposta($arrTempoResposta,$ni_prazo_execucao_rotina,$hi);
					$arrTempoRespostaRotina[$value['cd_rotina']] = $arr;
				}
			}
		}

		$this->view->res                    = $arrExecucaoRotina;
        $this->view->arrTempoRespostaRotina = $arrTempoRespostaRotina;
	}
	
	public function tabHistoricoExecucaoRotinaAction()
	{
		$this->_helper->layout->disableLayout();
        
		$objHistoricoExecucaoRotina = new HistoricoExecucaoRotina($this->_request->getControllerName());
		$objRotina                  = new Rotina($this->_request->getControllerName());

        $arrParams = array();

		$arrParams['dt_execucao_rotina'] = $this->_request->getParam('dt_execucao_rotina');
		$arrParams['cd_profissional']    = $this->_request->getParam('cd_profissional');
		$arrParams['cd_objeto']          = $this->_request->getParam('cd_objeto');
		$arrParams['cd_rotina']          = $this->_request->getParam('cd_rotina');
		$tab_origem                      = $this->_request->getParam('tab_origem');

		$this->view->arrDadosHistorico = $objHistoricoExecucaoRotina->getHistoricoExecucaoRotina($arrParams);

		$this->view->arrDadosExecucao  = $this->objExecucaoRotina->getExecucaoRotina($arrParams['cd_objeto'], $arrParams['dt_execucao_rotina'], $arrParams['cd_profissional'], $arrParams['cd_rotina']);

		$this->view->arrDadosRotina    = $objRotina->getDadosRotina($arrParams['cd_objeto'], $arrParams['cd_rotina']);

		$this->view->tab_origem        = $tab_origem;
	}
}