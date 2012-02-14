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

class CustoContratoDemandaController extends Base_Controller_Action
{
	private $objContrato;
	private $objCustoContratoDemanda;
	private $objCustoContratoDemandaPorMetrica;
	private $objUtil;
    private $objProjeto;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_CUSTO_CONTRATO_DEMANDA'));
		$this->objContrato                       = new Contrato($this->_request->getControllerName());
		$this->objProjeto                        = new Projeto($this->_request->getControllerName());
		$this->objCustoContratoDemanda           = new CustoContratoDemanda($this->_request->getControllerName());
		$this->objCustoContratoDemandaPorMetrica = new CustoContratoDemandaPorMetrica($this->_request->getControllerName());
		$this->objUtil                           = new Base_Controller_Action_Helper_Util();
	}

	public function indexAction()
	{}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();

		if ($this->_request->isPost()) {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			try {

				$arrDados = $this->objCustoContratoDemanda->fetchRow("cd_contrato = {$formData['cd_contrato_custo_contrato_demanda']}
					and ni_mes_custo_contrato_demanda = {$formData['ni_mes_custo_contrato_demanda']}
					and ni_ano_custo_contrato_demanda = {$formData['ni_ano_custo_contrato_demanda']}");

				if(count($arrDados) == 0){
					$this->salvar($formData);
					$msg = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
					$db->commit();
				} else {
					$this->alterar($formData);
					$msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
					$db->commit();
				}
			} catch(Zend_Exception $e){
				$db->rollback();

				$msg = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
                $msg .= '('.$e->getMessage().')';
			}
		} else {
			$msg = Base_Util::getTranslator('L_MSG_ERRO_SEM_DADOS_VALIDOS_REGISTRO');
		}
		$arrJson['msg'] = $msg;
		echo Zend_Json_Encoder::encode($arrJson);
	}

	/*
	 * Método para abrir e salvar a demanda, designado os profissionais e os níveis de
	 * serviço
	 */
	public function salvar(array $arrDados)
	{
		$error = false;

		$novo 				                 = $this->objCustoContratoDemanda->createRow();
		$novo->ni_mes_custo_contrato_demanda = $arrDados['ni_mes_custo_contrato_demanda'];
		$novo->ni_ano_custo_contrato_demanda = $arrDados['ni_ano_custo_contrato_demanda'];
		$novo->cd_contrato                   = $arrDados['cd_contrato_custo_contrato_demanda'];
		$novo->nf_total_multa                = (!empty($arrDados['nf_total_multa'])? $this->objUtil->validaValor($arrDados['nf_total_multa']): null);
		$novo->nf_total_glosa                = (!empty($arrDados['nf_total_glosa'])? $this->objUtil->validaValor($arrDados['nf_total_glosa']): null);
		$novo->nf_total_pago                 = $this->objUtil->validaValor($arrDados['nf_total_pago']);

		if (!$novo->save()){
			$error = true;
		}
		return $error;
	}

	public function alterar(array $arrDados)
	{
		$error = false;

		$addRow = array();
		$addRow['nf_total_multa'] = (!empty($arrDados['nf_total_multa'])? $this->objUtil->validaValor($arrDados['nf_total_multa']): null);
		$addRow['nf_total_glosa'] = (!empty($arrDados['nf_total_glosa'])? $this->objUtil->validaValor($arrDados['nf_total_glosa']): null);
		$addRow['nf_total_pago']  = $this->objUtil->validaValor($arrDados['nf_total_pago']);

		$where      = "cd_contrato                   = {$arrDados['cd_contrato_custo_contrato_demanda']} and
					   ni_mes_custo_contrato_demanda = {$arrDados['ni_mes_custo_contrato_demanda']} and
					   ni_ano_custo_contrato_demanda = {$arrDados['ni_ano_custo_contrato_demanda']}";
		
		if(!$this->objCustoContratoDemanda->update($addRow, $where)) {
			$error = true;
		}

		return $error;
	}

	public function recuperaCustoContratoDemandaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();

		$arrDados = $this->objCustoContratoDemanda->fetchRow("cd_contrato = {$formData['cd_contrato']}
					and ni_mes_custo_contrato_demanda = {$formData['ni_mes_custo_contrato_demanda']}
					and ni_ano_custo_contrato_demanda = {$formData['ni_ano_custo_contrato_demanda']}");

		$arrDados = (count($arrDados))? $arrDados->toArray() : $arrDados = array();

		$arrDadosContrato = $this->objContrato->find($formData['cd_contrato'])->current()->toArray();
		if (!is_null($arrDadosContrato["nf_valor_contrato"]) && !is_null($arrDadosContrato["ni_qtd_meses_contrato"])) {
			$arrDados["valor_contrato_demanda_mes"] = (string)round(($arrDadosContrato["nf_valor_contrato"]/$arrDadosContrato["ni_qtd_meses_contrato"]),1);
		}else{
			$arrDados["valor_contrato_demanda_mes"] = 0;
		}
		
		echo Zend_Json_Encoder::encode($arrDados);
	}

	public function salvarPorMetricaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();

        if ($this->_request->isPost()) {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			try {

                if($formData['cd_custo_demanda_por_metrica']==''){
					$this->salvarPMetrica($formData);
					$msg = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
					$db->commit();
				} else {
					$this->alterarPorMetrica($formData);
					$msg = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
					$db->commit();
				}
			} catch(Zend_Exception $e){
				$db->rollback();

				$msg = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
                $msg .= '('.$e->getMessage().')';
			}
		} else {
			$msg = Base_Util::getTranslator('L_MSG_ERRO_SEM_DADOS_VALIDOS_REGISTRO');
		}
		$arrJson['msg'] = $msg;
		echo Zend_Json_Encoder::encode($arrJson);
	}

	/*
	 * Método para abrir e salvar a demanda, designado os profissionais e os níveis de
	 * serviço
	 */
	public function salvarPMetrica(array $arrDados)
	{
		$error = false;

		$novo                               = $this->objCustoContratoDemandaPorMetrica->createRow();
		$novo->cd_custo_demanda_por_metrica = $this->objCustoContratoDemandaPorMetrica->getNextValueOfField('cd_custo_demanda_por_metrica');
        $novo->ni_mes                       = $arrDados['ni_mes_custo_metrica'];
		$novo->ni_ano                       = $arrDados['ni_ano_custo_metrica'];
		$novo->cd_contrato                  = $arrDados['cd_contrato_custo_metrica'];
		$novo->cd_projeto                   = $arrDados['cd_projeto_custo_metrica'];
		$novo->nf_qtd_unidade_metrica       = $this->objUtil->validaValor($arrDados['nf_qtd_unidade_metrica']);

		if (!$novo->save()){
			$error = true;
		}
        return $error;
	}

	public function alterarPorMetrica(array $arrDados)
	{
		$error = false;


		$addRow = array();
		$addRow['nf_qtd_unidade_metrica'] = $this->objUtil->validaValor($arrDados['nf_qtd_unidade_metrica']);
		$addRow['ni_mes']                 = $arrDados['ni_mes_custo_metrica'];
		$addRow['ni_ano']                 = $arrDados['ni_ano_custo_metrica'];
		$addRow['cd_projeto']             = $arrDados['cd_projeto_custo_metrica'];

		$where      = "cd_contrato                   = {$arrDados['cd_contrato_custo_metrica']} and
                       cd_custo_demanda_metrica      = {$arrDados['cd_custo_demanda_por_metrica']}";
		
		if(!$this->objCustoContratoDemanda->update($addRow, $where)) {
			$error = true;
		}

		return $error;
	}

	public function recuperaCustoContratoDemandaMetricaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$formData = $this->_request->getPost();
//[cd_custo_demanda_por_metrica] => 
//    [ni_mes_custo_contrato_demanda_metrica] => 1
//    [ni_ano_custo_contrato_demanda_metrica] => 2012
//    [cd_contrato_custo_contrato_demanda_metrica] => 15
//    [cd_projeto_metrica] => 272
//    [nf_total_pago_metrica] => 100,00        
        
		$arrDados = $this->objCustoContratoDemandaMetrica->fetchRow("cd_contrato = {$formData['cd_contrato_custo_metrica']}
					and ni_mes = {$formData['ni_mes_custo_metrica']}
					and ni_ano = {$formData['ni_ano_custo_metrica']}");

		$arrDados = (count($arrDados))? $arrDados->toArray() : $arrDados = array();

//		$arrDadosContrato = $this->objContrato->find($formData['cd_contrato'])->current()->toArray();
//		if (!is_null($arrDadosContrato["nf_valor_contrato"]) && !is_null($arrDadosContrato["ni_qtd_meses_contrato"])) {
//			$arrDados["valor_contrato_demanda_mes"] = (string)round(($arrDadosContrato["nf_valor_contrato"]/$arrDadosContrato["ni_qtd_meses_contrato"]),1);
//		}else{
//			$arrDados["valor_contrato_demanda_mes"] = 0;
//		}
		
		echo Zend_Json_Encoder::encode($arrDados);
	}
    
   public function gridCustoContratoMetricaAction()
	{
        $formData = $this->_request->getPost();

//        $cd_contrato = $formData['cd_contrato_custo_metrica'];
//        $ni_mes      = $formData['ni_mes_custo_metrica'];
//        $ni_ano      = $formData['ni_ano_custo_metrica'];
//         
		$this->_helper->layout->disableLayout();
        $this->view->res = $this->objCustoContratoDemandaPorMetrica->getCustoMetrica($formData);
	} 
}