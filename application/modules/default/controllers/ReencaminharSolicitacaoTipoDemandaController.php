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

class ReencaminharSolicitacaoTipoDemandaController extends Base_Controller_Action
{
	private $objDemandaProfissional;
	private $objDemanda;
	private $objDemandaNivelServico;
	private $objNivelServico;
    private $objStatusAtendimento;
    	
	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REENCAMINHAMENTO_SOLICITACAO_SERVICO'));
		$this->objDemandaProfissional = new DemandaProfissional($this->_request->getControllerName());
		$this->objDemanda             = new Demanda($this->_request->getControllerName());
		$this->objDemandaNivelServico = new DemandaProfissionalNivelServico($this->_request->getControllerName());
		$this->objNivelServico        = new NivelServico($this->_request->getControllerName());
        $this->objStatusAtendimento   = new StatusAtendimento($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$cd_objeto          = (int)$this->_request->getParam('cd_objeto', 0);
		$ni_solicitacao     = (int)$this->_request->getParam('ni_solicitacao', 0);
		$ni_ano_solicitacao = (int)$this->_request->getParam('ni_ano_solicitacao', 0);
		$cd_demanda         = (int)$this->_request->getParam('cd_demanda');

		$this->view->comboStatusAtendimento = $this->objStatusAtendimento->getComboStatusAtendimento(true);
		
		// Consulta Solicitacao
		$solicitacao 	                  = new Solicitacao($this->_request->getControllerName());
		$resSolicitacao                   = $solicitacao->getSolicitacao($cd_objeto, $ni_solicitacao, $ni_ano_solicitacao);
		$resSolicitacao['tx_solicitacao'] = str_ireplace('\"','"',$resSolicitacao['tx_solicitacao']);
		$this->view->solicitacao          = $resSolicitacao;

		$this->view->arrNivelServico = $this->objNivelServico->getNivelServico($cd_objeto, true);
		
		//Data e Hora da Demanda
		$dt_demanda             = date("d/m/Y H:i:s");
		$this->view->dt_demanda = $dt_demanda;

		$rowDemanda = $this->objDemanda->fetchRow("cd_demanda = {$cd_demanda}");

        $this->view->cd_status_atendimento = $rowDemanda->cd_status_atendimento;
		$this->view->cd_demanda            = $rowDemanda->cd_demanda;
		$this->view->dt_demanda            = Base_Util::converterDatetime($rowDemanda->dt_demanda, 'YYYY-MM-DD', 'DD/MM/YYYY');

        //montar as observações anteriores da demanda
        $rowSetObsDemanda =  $this->objDemandaNivelServico->getObservacoesDemanda($rowDemanda->cd_demanda);
        $strObservacaoDemanda = '';
        if($rowSetObsDemanda->valid()){
            $count = 1;
            foreach($rowSetObsDemanda as $row){
                if($row->tx_obs_nivel_servico){
                    $strObservacaoDemanda .= $count.') '.$row->tx_obs_nivel_servico."; ".PHP_EOL;
                    $count++;
                }
            }
        }
		$this->view->strObservacaoDemanda = $strObservacaoDemanda;

	}

	public function reencaminharDemandaProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_demanda       = $this->_request->getParam('cd_demanda',0);
		$cd_profissional  = $this->_request->getParam('cd_profissional',0);
		$cd_nivel_servico = $this->_request->getParam('cd_nivel_servico',0);
		
		$db = Zend_Registry::get('db');
		$db->beginTransaction();
		$erros = false;

		if ($erros === false)
			$erros = $this->reencaminharDemanda($cd_demanda);

		if ($erros === false)
			$erros = $this->reencaminharDemandaProfissional($cd_demanda, $cd_profissional);

		if ($erros === false)
			$erros = $this->reencaminharDemandaProfissionalNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico);

		if ($erros === true) {
			$db->rollback();
			echo Base_Util::getTranslator('L_MSG_ERRO_REENCAMINHAR_DEMANDA_PROFISSIONAL');
		} else {
			$db->commit();
			echo Base_Util::getTranslator('L_MSG_SUCESS_REENCAMINHAMENTO_REALIZADO');
			if (K_ENVIAR_EMAIL == "S") {
				$arrDados["cd_profissional"] = $cd_profissional;
				$this->_helper->Mail->enviaEmail($_SESSION["oasis_logged"][0]["cd_objeto"], $this->_request->getControllerName(), $arrDados);
			}
		}
	}
	
	private function reencaminharDemanda($cd_demanda)
	{
		$erros = false;

		$arrUpdate['st_fechamento_demanda'] = null; 
		$arrUpdate['dt_fechamento_demanda'] = null; 
		
		$erros = $this->objDemanda->atualizaDemanda($cd_demanda, $arrUpdate);
		
		return $erros;
	}
	
	private function reencaminharDemandaProfissional($cd_demanda, $cd_profissional)
	{
		$erros = false;

		$arrUpdate['dt_fechamento_demanda'] = null; 
		$arrUpdate['st_fechamento_demanda'] = null; 
		$arrUpdate['cd_profissional']       = $cd_profissional; 
		
		$erros = $this->objDemandaProfissional->atualizaDemanda($cd_demanda, $arrUpdate);
		
		return $erros;
	}
	
	private function reencaminharDemandaProfissionalNivelServico($cd_demanda, $cd_profissional, $cd_nivel_servico)
	{
		$erros = false;

		$arrUpdate['dt_demanda_nivel_servico']       = date('Y-m-d H:i:s'); 
		$arrUpdate['dt_leitura_nivel_servico']       = null; 
		$arrUpdate['dt_fechamento_nivel_servico']    = null; 
		$arrUpdate['st_fechamento_nivel_servico']    = null; 
		$arrUpdate['st_fechamento_gerente']          = null; 
		$arrUpdate['dt_fechamento_gerente']          = null; 
		$arrUpdate['tx_motivo_fechamento']           = null; 
		$arrUpdate['dt_justificativa_nivel_servico'] = null; 
		$arrUpdate['tx_justificativa_nivel_servico'] = null;

        $arrWhere = array(
            "cd_demanda = ?"       => $cd_demanda,
            "cd_profissional = ?"  => $cd_profissional,
            "cd_nivel_servico = ?" => $cd_nivel_servico
        );

        $rowDFNS = $this->objDemandaNivelServico->fetchRow($arrWhere);

        if($rowDFNS->st_nova_obs_nivel_servico === 'S'){
            $arrUpdate['tx_obs_nivel_servico'     ] = $rowDFNS->tx_obs_nivel_servico.' '.$rowDFNS->tx_nova_obs_nivel_servico;
            $arrUpdate['st_nova_obs_nivel_servico'] = null;
            $arrUpdate['tx_nova_obs_nivel_servico'] = null;
        }
		
		$erros = $this->objDemandaNivelServico->atualizaDemandaNivelServico($cd_demanda,$cd_profissional,$cd_nivel_servico, $arrUpdate);
		
		return $erros;
	}
	
	public function gridReencaminharSolicitacaoDemandaAction()
	{
		$this->_helper->layout->disableLayout();
		$arrDados = array();
		
		$cd_demanda = $this->_request->getParam('cd_demanda',0);
		if($cd_demanda){
			$arrDados = $this->objDemandaProfissional->getDadosDemandaProfissionalGrid($cd_demanda);
		}
		
		$this->view->res = $arrDados;
	}
	
	public function verificaNivelServicoAssociadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrDados = $this->_request->getPost();
		$flag_nivel_servico = "N";
		
		$whereProfissionalNivelServico = array(
            "cd_demanda = ?"      => $arrDados['cd_demanda'],
            "cd_profissional = ?" => $arrDados['cd_profissional'],
            "cd_nivel_servico = ?"=> $arrDados['cd_nivel_servico']
        );
		$arrValidacaoProfissional      = $this->objDemandaNivelServico->fetchAll($whereProfissionalNivelServico)->toArray();
		if(count($arrValidacaoProfissional) == 0){
			$wherelNivelServico = array(
                "cd_demanda = ?"          => $arrDados['cd_demanda'],
                "cd_nivel_servico = ?"=> $arrDados['cd_nivel_servico']
            );
			$arrValidacaoNivelServico = $this->objDemandaNivelServico->fetchAll($wherelNivelServico)->toArray();
			if(count($arrValidacaoNivelServico) > 0){
				$flag_nivel_servico = "S";
				echo $flag_nivel_servico;
			}
		} else {
			echo $flag_nivel_servico;
		}
	}

    public function salvarObservacaoReencaminhamentoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();

        $arrResult = array('error'  => false,
                           'typeMsg'=> 1,
                           'msg'    => Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO_OBSERVACAO'));

		if( $this->_request->isPost() ){
			$db = Zend_Registry::get('db');
			try {
                $db->beginTransaction();

                $arrUpdate['st_nova_obs_nivel_servico'] = 'S';
                $arrUpdate['tx_nova_obs_nivel_servico'] = $formData['tx_obs_nivel_servico'];

                $arrWhere['cd_demanda = ?'      ] = $formData['cd_demanda'];
                $arrWhere['cd_profissional = ?' ] = $formData['cd_profissional_hidden'];
                $arrWhere['cd_nivel_servico = ?'] = $formData['cd_nivel_servico_hidden'];

                $this->objDemandaNivelServico->atualizaObservacaoNivelServico($arrUpdate, $arrWhere);

                $db->commit();
			}catch(Base_Exception_Error $e){
    	        $db->rollBack();
                $arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = $e->getMessage();
            } catch(Zend_Exception $e){
				$arrResult['error'  ] = true;
                $arrResult['typeMsg'] = 3;
                $arrResult['msg'    ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
                $db->rollback();
			}
        }else{
            throw new Exception(Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'));
        }

        echo Zend_Json_Encoder::encode($arrResult);


    }


}