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

class ContratoController extends Base_Controller_Action
{
	private $contrato;
	private $objUtil;
	private $objContatoEmpresa;
	private $objDefinicaoMetrica;
	
	public function init()
	{
		parent::init();
		$this->contrato             = new Contrato($this->_request->getControllerName());
        $this->objUtil              = new Base_Controller_Action_Helper_Util();
		$this->objContatoEmpresa    = new ContatoEmpresa($this->_request->getControllerName());
		$this->objDefinicaoMetrica  = new DefinicaoMetrica($this->_request->getControllerName());
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData = $this->_request->getPost();

		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			if(!empty($formData['cd_contrato'])) {
				$novo             = $this->contrato->fetchRow("cd_contrato = {$formData['cd_contrato']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo              = $this->contrato->createRow();
				$novo->cd_contrato = $this->contrato->getNextValueOfField('cd_contrato');
				$arrResult['msg']  = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}

			$novo->cd_empresa                 = $formData['cd_empresa_contrato'];
			$novo->cd_contato_empresa         = ($formData['cd_contato_empresa_contrato'] == 0 || $formData['cd_contato_empresa_contrato'] == '')? null : $formData['cd_contato_empresa_contrato'];
			$novo->tx_numero_contrato         = $formData['tx_numero_contrato'];
			$novo->dt_inicio_contrato         = new Zend_Db_Expr("{$this->contrato->to_date("'" . $formData['dt_inicio_contrato'] . "'", 'DD/MM/YYYY')}");
			$novo->dt_fim_contrato            = new Zend_Db_Expr("{$this->contrato->to_date("'" . $formData['dt_fim_contrato'] . "'", 'DD/MM/YYYY')}");
			$novo->st_aditivo                 =  $formData['st_aditivo'];
			$novo->tx_cpf_gestor              = ($formData['tx_cpf_gestor'						   ]) ? $formData['tx_cpf_gestor']						  : null;
			$novo->st_contrato                = ($formData['st_contrato'						   ]) ? $formData['st_contrato'  ]						  : "A";
			$novo->cd_definicao_metrica       = ($formData['cd_metrica_unidade_prevista_contrato'  ]) ? $formData['cd_metrica_unidade_prevista_contrato'] : null;
			$novo->tx_gestor_contrato         = ($formData['tx_gestor_contrato'					   ]) ? $formData['tx_gestor_contrato']					  : null;
			$novo->tx_fone_gestor_contrato    = ($formData['tx_fone_gestor_contrato'			   ]) ? $formData['tx_fone_gestor_contrato']		 	  : null;
			$novo->tx_numero_processo         = ($formData['tx_numero_processo'					   ]) ? $formData['tx_numero_processo']			 		  : null;
			$novo->tx_obs_contrato            = ($formData['tx_obs_contrato'					   ]) ? $formData['tx_obs_contrato']					  : null;
			$novo->tx_objeto                  = ($formData['tx_objeto'							   ]) ? $formData['tx_objeto']							  : null;
			$novo->tx_localizacao_arquivo     = ($formData['tx_localizacao_arquivo'				   ]) ? $formData['tx_localizacao_arquivo']				  : null;
			$novo->tx_co_gestor               = ($formData['tx_co_gestor'						   ]) ? $formData['tx_co_gestor']						  : null;
			$novo->tx_cpf_co_gestor           = ($formData['tx_cpf_co_gestor'					   ]) ? $formData['tx_cpf_co_gestor']					  : null;
			$novo->tx_fone_co_gestor_contrato = ($formData['tx_fone_co_gestor_contrato'			   ]) ? $formData['tx_fone_co_gestor_contrato']			  : null;
			$novo->ni_horas_previstas         = $this->objUtil->validaValor($formData['ni_horas_previstas'	  ]);
			$novo->nf_valor_contrato          = $this->objUtil->validaValor($formData['nf_valor_contrato'	  ]);
			$novo->nf_valor_unitario_hora     = $this->objUtil->validaValor($formData['nf_valor_unitario_hora']);

			$novo->ni_mes_inicial_contrato    = (int)substr(trim($formData['dt_inicio_contrato']),3,2);
			$novo->ni_ano_inicial_contrato    = substr(trim($formData['dt_inicio_contrato']),6,4);
			$novo->ni_mes_final_contrato      = (int)substr(trim($formData['dt_fim_contrato']),3,2);
			$novo->ni_ano_final_contrato      = substr(trim($formData['dt_fim_contrato']),6,4);

			$diaInicial                       = substr("00".substr(trim($formData['dt_inicio_contrato']),0,2),-2);
			$novo->ni_mes_inicial_contrato    = (int)substr(trim($formData['dt_inicio_contrato']),3,2);
			$mesInicial                       = substr("00".$novo->ni_mes_inicial_contrato,-2);
			$novo->ni_ano_inicial_contrato    = substr(trim($formData['dt_inicio_contrato']),6,4);
			$diaFinal                         = substr("00".substr(trim($formData['dt_fim_contrato']),0,2),-2);
			$novo->ni_mes_final_contrato      = (int)substr(trim($formData['dt_fim_contrato']),3,2);
			$mesFinal                         = substr("00".$novo->ni_mes_final_contrato,-2);
			$novo->ni_ano_final_contrato      = substr(trim($formData['dt_fim_contrato']),6,4);

			$dataInicialConvert               = "{$novo->ni_ano_inicial_contrato}-{$mesInicial}-{$diaInicial} 00:00:00";
			$dataFinalConvert                 = "{$novo->ni_ano_final_contrato}-{$mesFinal}-{$diaFinal} 00:00:00";
			$objDateDiff                      = new Util_Datediff($dataInicialConvert,$dataFinalConvert,"m");
			$novo->ni_qtd_meses_contrato      = $objDateDiff->datediff();

			$novo->save();
            $db->commit();
            
		} catch(Zend_Exception $e) {
            $db->rollBack();
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function recuperaContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_contrato = $this->_request->getParam('cd_contrato');

		$res = $this->contrato->getAllDadosContrato($cd_contrato);

        $res[0]['dt_inicio_contrato'] = date('d/m/Y', strtotime($res[0]['dt_inicio_contrato']));
        $res[0]['dt_fim_contrato']    = date('d/m/Y', strtotime($res[0]['dt_fim_contrato']));

		echo Zend_Json::encode($res);
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_contrato = (int)$this->_request->getParam('cd_contrato', 0);
		if ($this->contrato->delete("cd_contrato = {$cd_contrato}")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

/*
	public function pesquisaContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_empresa = (int)$this->_request->getParam('cd_empresa', 0);
		$contrato = new Contrato($this->_request->getControllerName());
		$res = $contrato->getContrato($cd_empresa);

		$strOptions = "<option value=\"\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";

		foreach ($res as $cd_contrato => $tx_numero_contrato) {
			$strOptions .= "<option value=\"{$cd_contrato}\">{$tx_numero_contrato}</option>";
		}

		echo $strOptions;
	}
*/
	public function pesquisaContratoAtivoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$res = $this->contrato->getContrato(true);

		$strOptions = "";
		foreach ($res as $cd_contrato => $tx_numero_contrato) {
			$strOptions .= "<option value=\"{$cd_contrato}\">{$tx_numero_contrato}</option>";
		}

		echo $strOptions;
	}	

	public function pesquisaContratoAtivoObjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$res = $this->contrato->getContratoAtivoObjeto(true);

		$strOptions = "";
		foreach ($res as $cd_contrato => $tx_numero_contrato) {
			$strOptions .= "<option value=\"{$cd_contrato}\">{$tx_numero_contrato}</option>";
		}
		echo $strOptions;
	}			

	public function getPrepostoEmpresaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_empresa = $this->_request->getParam('cd_empresa');
		$res 		= $this->objContatoEmpresa->getComboPrepostoEmpresa($cd_empresa, true);

		$strOptions = '';
		foreach ($res as $cd_preposto=>$nome_preposto) {
			$strOptions .= "<option value=\"{$cd_preposto}\">{$nome_preposto}</option>";
		}
		echo $strOptions;
	}

	public function comboGroupContratoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$tipo_objeto = $this->_request->getParam('tipo_objeto');
		
		$arrContratoAtivo   = $this->contrato->getContratoPorTipoDeObjeto(false, $tipo_objeto, null, false, 'A');
		$arrContratoInativo = $this->contrato->getContratoPorTipoDeObjeto(false, $tipo_objeto, null, false, 'I');
		
		//Monta Group Ativos

		$strOption  = "<option selected=\"selected\" label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE_CONTRATO')."</option>";
		$strOption .= "<optgroup label=\"".Base_Util::getTranslator('L_VIEW_COMBO_ATIVOS')."\">";
		if(count($arrContratoAtivo) > 0){
			foreach($arrContratoAtivo as $key=>$value){
				$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
			}
		} else {
			$strOption .= "<option disabled=\"disabled\" value=\"999999\">".Base_Util::getTranslator('L_VIEW_COMBO_NENHUM_CONTRATO_ATIVO')."</option>";
		}
		$strOption .= "</optgroup>";
		
		//Monta Group Inativos
		$strOption .= "<optgroup label=\"".Base_Util::getTranslator('L_VIEW_COMBO_INATIVOS')."\">";
		if(count($arrContratoInativo) > 0){
			foreach($arrContratoInativo as $chave=>$valor){
				$strOption .= "<option label=\"{$valor}\" value=\"{$chave}\">{$valor}</option>";
			}
		} else {
			$strOption .= "<option disabled=\"disabled\" value=\"99999\">".Base_Util::getTranslator('L_VIEW_COMBO_NENHUM_CONTRATO_INATIVO')."</option>";
		}
		$strOption .= "</optgroup>";

		echo $strOption;		
	}

	public function getSiglaMetricaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrSigla = $this->objDefinicaoMetrica->getComboSiglaDefinicaoMetrica(true);

		$options = '';
		foreach ($arrSigla as $key=>$value) {
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		echo $options;
	}

}