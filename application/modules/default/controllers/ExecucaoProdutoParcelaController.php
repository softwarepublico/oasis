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

class ExecucaoProdutoParcelaController extends Base_Controller_Action 
{
	private $objProjeto;
	private $objProposta;
	private $objParcela;
	private $objProdutoParcela;
	
	public function init()
	{
		parent::init();
		$this->objProjeto        = new Projeto($this->_request->getControllerName());
		$this->objProposta       = new Proposta($this->_request->getControllerName());
		$this->objParcela        = new Parcela($this->_request->getControllerName());
		$this->objProdutoParcela = new ProdutoParcela($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
		$this->view->headTitle(Base_Util::setTitle('L_TIT_EXECUCAO_PRODUTOS_PARCELA'));
		$this->view->comboProjeto = $this->objProjeto->getProjeto(true);
	}
	
	public function gridExecucaoProdutoParcelaAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam("cd_projeto");
		$cd_modulo  = $this->_request->getParam("cd_modulo");
		$cd_etapa   = $this->_request->getParam("cd_etapa",0);
		$cd_etapa   = ($cd_etapa != 0)?$cd_etapa:null;
		$cd_objeto  = $_SESSION['oasis_logged'][0]['cd_objeto'];
		
		$arrEtapa     = $this->objEtapa->getDadosEtapa($cd_objeto,$cd_etapa);
		$arrAtividade = array();
		$arrDados     = array();
		foreach($arrEtapa as $key=>$value){
			$arrAtividade[$value['cd_etapa']] = $this->objAtividade->getDadosAtividade($value['cd_etapa']);
			if(count($arrAtividade[$value['cd_etapa']]) > 0 ){
				foreach($arrAtividade[$value['cd_etapa']] as $chave=>$valor){
					$rowDados = $this->objPlanejamento->getPlanejamento($cd_projeto, $cd_modulo, $value['cd_etapa'], $valor['cd_atividade']);
					if(count($rowDados) > 0 ){
						$arrAtividade[$value['cd_etapa']][$chave]['dt_inicio_atividade']     = date('d/m/Y', strtotime($rowDados->dt_inicio_atividade));
						$arrAtividade[$value['cd_etapa']][$chave]['dt_fim_atividade']        = date('d/m/Y', strtotime($rowDados->dt_fim_atividade));
						$arrAtividade[$value['cd_etapa']][$chave]['nf_porcentagem_execucao'] = $rowDados->nf_porcentagem_execucao;
						$arrAtividade[$value['cd_etapa']][$chave]['tx_obs_atividade']        = $rowDados->tx_obs_atividade;
					} else {
						$arrAtividade[$value['cd_etapa']][$chave]['dt_inicio_atividade']     = "";
						$arrAtividade[$value['cd_etapa']][$chave]['dt_fim_atividade']        = "";
						$arrAtividade[$value['cd_etapa']][$chave]['nf_porcentagem_execucao'] = "";
						$arrAtividade[$value['cd_etapa']][$chave]['tx_obs_atividade']        = "";
					}  
				}
			}
		}
		
		$this->view->arrEtapa     = $arrEtapa;
		$this->view->arrAtividade = $arrAtividade;
	}

	public function salvaExecucaoProdutoParcelaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost = $this->_request->getPost();
		
		$cd_projeto = $arrPost['cd_projeto_planejamento'];
		$cd_modulo  = $arrPost['cd_modulo_planejamento'];
		$cd_etapa   = $arrPost['cd_etapa_planejamento'];
		unset($arrPost['cd_projeto_planejamento']);
		unset($arrPost['cd_modulo_planejamento']);
		unset($arrPost['cd_etapa_planejamento']);

		$i        = 0;
		$arrDados = array();
		foreach($arrPost as $key=>$value){
			$arrCampo = explode('_',$key);
			
			$dtInicio     = "dtInicio_{$arrCampo[1]}_{$arrCampo[2]}";
			$dtTermino    = "dtTermino_{$arrCampo[1]}_{$arrCampo[2]}";
			$porcentagem  = "porcentagem_{$arrCampo[1]}_{$arrCampo[2]}";
			$obsAtividade = "obsAtividade_{$arrCampo[1]}_{$arrCampo[2]}";
			
			if(empty($arrPost[$porcentagem]) && empty($arrPost[$dtInicio]) && empty($arrPost[$dtTermino]) && empty($arrPost[$obsAtividade])){
				continue;
			} else {
				if(!empty($arrPost[$dtInicio])){
					$arrDados[$i]['dt_inicio_atividade'] = $arrPost[$dtInicio];
					unset($arrPost[$dtInicio]);
				} else {
					$arrDados[$i]['dt_inicio_atividade'] = null;
				}
				if(!empty($arrPost[$dtTermino])){
					$arrDados[$i]['dt_fim_atividade'] = $arrPost[$dtTermino];
					unset($arrPost[$dtTermino]);
				} else {
					$arrDados[$i]['dt_fim_atividade'] = null;
				}
				if(!empty($arrPost[$porcentagem])){
					$arrDados[$i]['nf_porcentagem_execucao'] = $arrPost[$porcentagem];
					unset($arrPost[$porcentagem]);
				} else {
					$arrDados[$i]['nf_porcentagem_execucao'] = null;
				}
				if(!empty($arrPost[$obsAtividade])){
					$arrDados[$i]['tx_obs_atividade'] = $arrPost[$obsAtividade];
					unset($arrPost[$obsAtividade]);
				} else {
					$arrDados[$i]['tx_obs_atividade'] = null;
				}
				
				$arrDados[$i]['cd_etapa']                = $arrCampo[1];
				$arrDados[$i]['cd_atividade']            = $arrCampo[2];
				$arrDados[$i]['cd_projeto']              = $cd_projeto;
				$arrDados[$i]['cd_modulo']               = $cd_modulo;
			}
			$i++;
		}
		
		if( count($arrDados) > 0 ){
			$return = $this->objPlanejamento->trataDadosGravacao($arrDados);
			$msg    = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		} else {
			$msg = Base_Util::getTranslator('L_MSG_ERRO_SEM_INFORMACOES_SEREM_SALVAS');
		}
		echo $msg;
	}
}