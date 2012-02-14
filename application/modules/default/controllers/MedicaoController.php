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

class MedicaoController extends Base_Controller_Action
{
	private $objMedicao;
	private $objBoxIncio;
	private $objAnaliseMedicao;
	private $objMedicaoMedida;
	
	public function init()
	{
		parent::init();
		$this->objMedicao 		 = new Medicao($this->_request->getControllerName());
		$this->objBoxIncio		 = new BoxInicio($this->_request->getControllerName());
		$this->objAnaliseMedicao = new AnaliseMedicao($this->_request->getControllerName());
		$this->objMedicaoMedida  = new MedicaoMedida($this->_request->getControllerName());
	}

	public function indexAction()
	{
	}

/****************** METODOS USADOS NAS ABAS DE MEDICAO ******************************************************/
	
	public function salvarNovaMedicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
				
		$novo 			       			= $this->objMedicao->createRow();
		$novo->cd_medicao				= $this->objMedicao->getNextValueOfField('cd_medicao');
		$novo->tx_medicao				= $arrDados['tx_medicao'];
		$novo->st_nivel_medicao			= $arrDados['st_nivel'];
		$novo->tx_objetivo_medicao		= $arrDados['tx_objetivo_medicao'];
		$novo->tx_procedimento_coleta	= $arrDados['tx_procedimento_coleta'];
		$novo->tx_procedimento_analise	= $arrDados['tx_procedimento_analise'];

		if($novo->save()) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	public function alterarMedicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		$arrUpdate['tx_medicao']				= $arrDados['tx_medicao'];
		$arrUpdate['st_nivel_medicao']			= $arrDados['st_nivel'];
		$arrUpdate['tx_objetivo_medicao']		= $arrDados['tx_objetivo_medicao'];
		$arrUpdate['tx_procedimento_coleta']	= $arrDados['tx_procedimento_coleta'];
		$arrUpdate['tx_procedimento_analise']	= $arrDados['tx_procedimento_analise'];

		if($this->objMedicao->update($arrUpdate, "cd_medicao = {$arrDados['cd_medicao']}")) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}
	}
	
	public function recuperaDadosMedicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_medicao = $this->_request->getParam('cd_medicao');
		
		$arrDados = $this->objMedicao->find($cd_medicao)->current()->toArray();
				
		echo Zend_Json::encode($arrDados);
		
	}

	public function gridMedicoesAction()
	{
		$this->_helper->layout->disableLayout();
		$res			 = $this->objMedicao->getAllMedicoes();
		$this->view->res = $res;
	}	
	

/****************** METODOS USADOS NAS ABAS DE ANALISE DE MEDICAO *******************************************/	
	
	public function carregaCombosAnaliseMedicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		//monta os options para o combo de Medições
		$arrMedicao = $this->objMedicao->getComboMedicoes(true);
		//monta os options para o combo de Box
		$arrBox		= $this->objBoxIncio->getComboBoxInicio(true);

		$retornaOsDois = array($arrMedicao, $arrBox);
		
		echo Zend_Json_Encoder::encode($retornaOsDois);
	}

	public function salvarNovaAnaliseMedicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		$novo 			       				= $this->objAnaliseMedicao->createRow();
		$novo->cd_medicao					= $arrDados['cmb_medicao'];
		$novo->cd_box_inicio				= $arrDados['cmb_box_inicio'];
		$novo->tx_resultado_analise_medicao	= $arrDados['tx_resultado_analise_medicao'];
		$novo->tx_dados_medicao				= $arrDados['tx_dados_medicao'];
		$novo->cd_profissional				= $_SESSION["oasis_logged"][0]["cd_profissional"];
		$novo->dt_analise_medicao			= date("Y-m-d H:i:s");

		if($novo->save()) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	public function alterarAnaliseMedicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		$arrUpdate['tx_resultado_analise_medicao']	= $arrDados['tx_resultado_analise_medicao'];
		$arrUpdate['tx_dados_medicao'			 ]	= $arrDados['tx_dados_medicao'];

		$where  = "cd_medicao 		  = {$arrDados['cd_medicao']} and ";
		$where .= "cd_box_inicio 	  = {$arrDados['cd_box_inicio']} and ";
		$where .= "dt_analise_medicao = '{$arrDados['dt_analise']}' ";
		
		if($this->objAnaliseMedicao->update( $arrUpdate, $where ) ) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}
	}
	
	public function recuperaDadosAnaliseMedicaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_medicao		= $this->_request->getParam('cd_medicao');
		$cd_box_inicio	= $this->_request->getParam('cd_box_inicio');
		$dt_analise		= $this->_request->getParam('dt_analise');
		
		$row = $this->objAnaliseMedicao->getAnaliseMedicaoEspecifica($dt_analise,$cd_medicao,$cd_box_inicio);

        $arrDados = $row->toArray();
        
        //mascara as datas
        $arrDados['dt_analise_mask'          ] = date('d/m/Y', strtotime($row->dt_analise_medicao));
        $arrDados['dt_decisao_mask'          ] = date('d/m/Y', strtotime($row->dt_decisao));
        $arrDados['dt_decisao_executada_mask'] = date('d/m/Y', strtotime($row->dt_decisao_executada));

		echo Zend_Json::encode($arrDados);
	}
	
	public function gridAnaliseMedicaoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$rowSet			 = $this->objAnaliseMedicao->getAnaliseMedicaoDecisao(1);
		$this->view->res = $rowSet;
	}
	
/****************** METODOS USADOS NAS ABAS DE DECISAO ******************************************************/	
	
	public function salvarDecisaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		$arrUpdate['tx_decisao'] = $arrDados['tx_decisao'];
		$arrUpdate['dt_decisao'] = (!empty ($arrDados['dt_decisao'])) ? new Zend_Db_Expr("{$this->objAnaliseMedicao->to_date("'{$arrDados['dt_decisao']}'", 'DD/MM/YYYY')}") : null;

		$where  = "cd_medicao 		  = {$arrDados['cd_medicao']} and ";
		$where .= "cd_box_inicio 	  = {$arrDados['cd_box_inicio']} and ";
		$where .= "dt_analise_medicao = '{$arrDados['dt_analise']}' ";
		
		if($this->objAnaliseMedicao->update( $arrUpdate, $where ) ) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
	
	public function gridDecisaoMedicaoAction()
	{
		$this->_helper->layout->disableLayout();
		$rowSet 		 = $this->objAnaliseMedicao->getAnaliseMedicaoDecisao(0);
		$this->view->res = $rowSet;
	}
	
	
/****************** METODOS USADOS NA ABA DE EXECUÇÃO ******************************************************/	
	
	public function salvarExecucaoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$arrUpdate['st_decisao_executada'	 ] = (array_key_exists('st_decisao_executada',$arrDados)) ? "E": "N";
		$arrUpdate['tx_obs_decisao_executada'] = $arrDados['tx_obs_decisao_executada'];
		$arrUpdate['dt_decisao_executada'	 ] = (!empty ($arrDados['dt_decisao_executada'])) ? new Zend_Db_Expr("{$this->objAnaliseMedicao->to_date("'{$arrDados['dt_decisao_executada']}'", 'DD/MM/YYYY')}") : null;
		
		$where  = "cd_medicao 		  = {$arrDados['cd_medicao']} and ";
		$where .= "cd_box_inicio 	  = {$arrDados['cd_box_inicio']} and ";
		$where .= "dt_analise_medicao = '{$arrDados['dt_analise']}' ";
		
		if($this->objAnaliseMedicao->update( $arrUpdate, $where ) ) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}

/****************** METODOS USADOS NA ABA DE MEDIÇÃO MEDIDA *************************************************/

	public function carregaComboMedicaoMedidaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		//monta os options para o combo de Medições
		$arrMedicao = $this->objMedicao->getComboMedicoes(true);
		
		echo Zend_Json_Encoder::encode($arrMedicao);
	}
	
	public function pesquisaMedicaoMedidaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
				
		// Recupera os parametros enviados por get
		$cd_medicao = $this->_request->getParam('cd_medicao');
		
		if ($cd_medicao == 0) {
			echo '';
		} else {
			
			// Recordset de profissionais que nao se encontram no projeto selecionado
			$foraMedicao  = $this->objMedicaoMedida->pesquisaMedidaForaMedicao( $cd_medicao );

			// Recordset de profissionais que se encontram no projeto selecionado
			$naMedicao    = $this->objMedicaoMedida->pesquisaMedidaNaMedicao( $cd_medicao );

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($foraMedicao as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_medida']}\">{$fora['tx_medida']}</option>";
			}

			$arr2 = "";
			foreach ($naMedicao as $na) {
				$arr2 .= "<option value=\"{$na['cd_medida']}\">{$na['tx_medida']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
			
		}
		
	}

	public function associaMedicaoMedidaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		
		$cd_medicao = $this->_request->getParam('cd_medicao');
		$medidas  = Zend_Json_Decoder::decode($this->_request->getParam('medidas'));

		foreach ($medidas as $medida) {
			$novo 				= $this->objMedicaoMedida->createRow();
			$novo->cd_medida	= $medida;
			$novo->cd_medicao	= $cd_medicao; 
			$novo->save();
		}
	}

	public function desassociaMedicaoMedidaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
				
		$cd_medicao		 = $post['cd_medicao'];
		$medidas		 = Zend_Json_Decoder::decode($post['medidas']);
		
		foreach ($medidas as $medida) {
			$where = "cd_medicao={$cd_medicao} and cd_medida={$medida}";
			$this->objMedicaoMedida->delete($where);
		}
	}

	public function gridMedicaoMedidaAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_medicao = $this->_request->getParam('cd_medicao');
		
		$res			 = $this->objMedicaoMedida->getMedidaMedicao( $cd_medicao );
		$this->view->res = $res;
	}
	
	public function salvarPrioridadeMedicaoMedidaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_medicao = $this->_request->getParam('cd_medicao');
		$dados		= $this->_request->getParam('dados_prioridade');
		
		$arrDados = explode('_',$dados);
		$cd_medida = $arrDados[0]; //pega o cod medida
		
		$arrUpdate['st_prioridade_medida'] = $arrDados[1]; //pega a prioridade

		$where = "cd_medicao = {$cd_medicao} and cd_medida = {$cd_medida}";
		
		if($this->objMedicaoMedida->update( $arrUpdate, $where ) ) {
			echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		}
	}
}