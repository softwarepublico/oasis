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

class QuestionarioController extends Base_Controller_Action 
{
	private $objQuestionario;
	private $objProjeto;
	private $objProposta;
	private $objUnidade;
	private $objGrupoFator;
	private $objGrupoFatorItem;
	private $codProjeto;
	private $codProposta;
	
	public function init()
	{
		parent::init();
		$this->objProjeto        = new Projeto($this->_request->getControllerName());
		$this->objProposta       = new Proposta($this->_request->getControllerName());
		$this->objUnidade        = new Unidade($this->_request->getControllerName());
		$this->objGrupoFator     = new GrupoFator($this->_request->getControllerName());
		$this->objGrupoFatorItem = new GrupoFatorItem($this->_request->getControllerName());
		$this->objQuestionario   = new Questionario($this->_request->getControllerName());
	}
	
	public function indexAction(){}

	public function montaComboProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrProjeto = $this->objProjeto->getProjeto(true);
		if(count($arrProjeto)>0){
			$strSelect = "";
			foreach($arrProjeto as $key=>$value){
				$strSelect .= "<option label=\"{$value}\" value=\"{$key}\">$value</option>";			
			}
		}
		
		echo Zend_Json_Encoder::encode($strSelect);
	}
	
	public function montaComboPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam('cd_projeto',0);
		
		$arrDados[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		if($cd_projeto != 0){
			$arrDados = $this->objProposta->getProposta($cd_projeto,true);
		}
			
		$strSelect = "";
		foreach($arrDados as $key=>$value){
			$strSelect .= "<option label=\"{$value}\" value=\"{$key}\">$value</option>";			
		}
		
		echo Zend_Json_Encoder::encode($strSelect);
	}
	
	public function questionarioFormAction()
	{
		$this->_helper->layout->disableLayout();
		
		$this->codProjeto  = $this->_request->getParam('cd_projeto');
		$this->codProposta = $this->_request->getParam('cd_proposta');
		
		$arrDados = $this->objProjeto->getDadosProjeto($this->codProjeto);
		$arrDados = $arrDados[0];
		$arrUnidade = $this->objUnidade->find($arrDados['cd_unidade'])->current();
		$arrDados['tx_sigla_unidade'] = $arrUnidade->tx_sigla_unidade;

		$arrGrupoFator = array();
		$arrGrupoFator = $this->objGrupoFator->getDadosGrupoFator();		

		$arrGrupoFatorItem = array();
		foreach($arrGrupoFator as $key=>$value){
			$arrGrupoFatorItem[$value['cd_grupo_fator']] = $this->getGrupoFatorItem($arrGrupoFator[$key]['cd_grupo_fator']);
		}
		$arrProposta = $this->objProposta->getDadosProjetoProposta($this->codProjeto, $this->codProposta);
		
		$this->view->tx_motivo_insatisfacao = $arrProposta[0]['tx_motivo_insatisfacao'];
		$this->view->arrProjeto             = $arrDados;
		$this->view->arrGrupoFator          = $arrGrupoFator;
		$this->view->arrGrupoFatorItem      = $arrGrupoFatorItem; 
	}
	
	private function getGrupoFatorItem($cd_grupo_fator)
	{
		$arrGrupoFatorItem = $this->objGrupoFatorItem->getGrupoFatorItem($cd_grupo_fator);

		foreach($arrGrupoFatorItem as $key=>$value){
			$arrDados = $this->objQuestionario->getDadosQuestionario($this->codProjeto, $this->codProposta, $value['cd_grupo_fator'], $value['cd_item_grupo_fator']);
			if(count($arrDados) > 0){
				$arrGrupoFatorItem[$key]['st_avaliacao_qualidade'] = $arrDados[0]['st_avaliacao_qualidade'];
			} else {
				$arrGrupoFatorItem[$key]['st_avaliacao_qualidade'] = "";
			}
		}
		return $arrGrupoFatorItem;
	}
	
	public function trataDadosQuestionarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		$this->codProposta      = $arrPost['cd_proposta'];
		$this->codProjeto       = $arrPost['cd_projeto'];
		$tx_motivo_insatisfacao = $arrPost['tx_motivo_insatisfacao']; 
		unset($arrPost['cd_proposta']);
		unset($arrPost['cd_projeto']);
		unset($arrPost['tx_motivo_insatisfacao']);
		
		if(count($arrPost) > 0){
			$i=0;
			foreach($arrPost as $key=>$value){
				$arrStAvaliacao     = explode("_",$key);
				//Caputarar o código do grupo fator e do item grupo fator
				$tam = count($arrStAvaliacao);
				$arrInsert[$i]['cd_projeto']             = $this->codProjeto;
				$arrInsert[$i]['cd_proposta']            = $this->codProposta;
				$arrInsert[$i]['cd_grupo_fator']         = $arrStAvaliacao[$tam-2];
				$arrInsert[$i]['cd_item_grupo_fator']    = $arrStAvaliacao[$tam-1];
				$arrInsert[$i]['st_avaliacao_qualidade'] = $value;
				$i++;
			}
			$return = $this->salvaDadosQuestionarioAction($arrInsert);
			if($return){
				$returnProposta = $this->salvaMotivoInsatisfacao($tx_motivo_insatisfacao);
				if($returnProposta){
					$msg = Base_Util::getTranslator('L_MSG_SUCESS_QUESTIONARIO_RESPONDIDO');
				} else {
					$msg = Base_Util::getTranslator('L_MSG_ERRO_SALVAR_MOTIVO_INSATISFACAO');
				}
			} else {
				$msg = Base_Util::getTranslator('L_MSG_ERRO_RESPONDER_QUESTIONARIO');
			}
			echo $msg;
		} else {
			echo Base_Util::getTranslator('L_MSG_ALERT_RESPONDA_ITENS_QUESTIONARIO');
		}
	}
	
	private function salvaDadosQuestionarioAction(array $arrInsert)
	{
		foreach($arrInsert as $key=>$value){
			$arrQuestionario = $this->objQuestionario->getDadosQuestionario($value['cd_projeto'],$value['cd_proposta'],$value['cd_grupo_fator'],$value['cd_item_grupo_fator']);
			if(count($arrQuestionario) > 0){
				$return = $this->objQuestionario->alterarDadosQuestionario($arrInsert[$key]);
			} else {
				$return = $this->objQuestionario->salvarDadosQuestionario($arrInsert[$key]);
			}
		}
		return $return;
	}
	
	private function salvaMotivoInsatisfacao($tx_motivo_insatisfacao)
	{
		$where = "cd_projeto = {$this->codProjeto} and cd_proposta = {$this->codProposta}";
		$arrInsert['tx_motivo_insatisfacao'] = $tx_motivo_insatisfacao;
		if($this->objProposta->update($arrInsert, $where)){
			return true;
		} else {
			return false;
		}
	}

	public function geraAvaliacaoQualidadeAction(){

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getPost();

		$this->codProjeto	= $params['cd_projeto' ];
		$this->codProposta	= $params['cd_proposta'];

		$arrResult	= array('type'=>1, 'msg'=>'');
		$erro		= false;
		$db			= Zend_Registry::get('db');
		$db->beginTransaction();
		try {
			$objSelectQuestionario = $this->objQuestionario->getDadosRespostaQuestionario($this->codProjeto, $this->codProposta);

			//caso não exeste registro é porque o questionario não foi respondido
			if( !$objSelectQuestionario->valid() ){
				throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_QUESTIONARIO_PROPOSTA_NAO_RESPONDIDO'));
				$erro = true;
			}

			foreach($objSelectQuestionario as $value){
				$arrGrupoFatorQtdQuestaoRespondida[$value->cd_grupo_fator][$value->st_avaliacao_qualidade] = $value->qtd_questao_respondida;
			}

			//obtem os valores dos cálculos dos fatores
			foreach($arrGrupoFatorQtdQuestaoRespondida as $key=>$value1){

				// formula = ((peso_1*qdt_questao_respondida_1)+(peso_2*qdt_questao_respondida_2)+...+(peso_n*qdt_questao_respondida_n))*FNS
				$formula = "(";
				foreach($value1 as $peso=>$qdt){
					$formula .= "({$peso}*{$qdt})+";
				}

				$arrDadosGrupoFator = $this->objGrupoFator->getDadosGrupoFator($key);
				if( count($arrDadosGrupoFator) == 0 ){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ADICIONAR_PESO'));
					$erro = true;
				}
				//remove o ultimo "+" e adiciona o complemento à formula
				$formula = substr($formula, 0,-1).")*".$arrDadosGrupoFator[0]['ni_peso_grupo_fator'];

				//traduz a string da formula para valor numérico correspondente
				eval("\$cf = $formula;");

				//cria o array com o valor calculado para Calculo do Fator (CF)
				// e CFmax
				$arrDadosPrimeiroCalculo[$key]['CF'		] = $cf;
				$arrDadosPrimeiroCalculo[$key]['CF_MAX'	] = $arrDadosGrupoFator[0]['ni_peso_grupo_fator'  ] * $arrDadosGrupoFator[0]['ni_indice_grupo_fator'];
			}

			// &Sigma;CFA   = CF_1 + CF_2 + ... + CF_n
			// &Sigma;CFmax = ((FNS_1*indice_grupo_fator_1) + (FNS_2*indice_grupo_fator_1) + ... + (FNS_n*indice_grupo_fator_n)) * peso_1

			$somatorioCFA	= 0;
			$CFMaximo		= 0;
			foreach( $arrDadosPrimeiroCalculo as $cd_grupo_fator=>$dados){
				$somatorioCFA	= $somatorioCFA+$dados['CF'];
				$CFMaximo		= $CFMaximo+$dados['CF_MAX'];
			}
			$CFMaximo = $CFMaximo * 1;

			//valor em % para a avaliação da proposta
			$avaliacaoProposta = round(($somatorioCFA * 100) / $CFMaximo, 2) ;

			$where = "cd_projeto = {$this->codProjeto} and cd_proposta = {$this->codProposta}";
			if(!$this->objProposta->update(array('nf_indice_avaliacao_proposta'=>$avaliacaoProposta), $where)){
				throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_REGISTRAR_AVALIACAO_QUALIDADE'));
				$erro = true;
			}else{
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_AVALIACAO_QUALIDADE_PROPOSTA',$avaliacaoProposta);
			}

			if(!$erro){
				$db->commit();
			} else {
				$db->rollBack();
			}
		} catch(Base_Exception_Alert $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg' ] = $e->getMessage();
		} catch(Base_Exception_Error $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = $e->getMessage();
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_CALCULAR_AVALIACAO_QUALIDADE'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
}