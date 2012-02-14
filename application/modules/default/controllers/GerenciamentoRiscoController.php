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

class GerenciamentoRiscoController extends Base_Controller_Action
{
	private $objProjeto;
	private $objItemRisco;
	private $objEtapa;
	private $objAtividade;
	private $objAnaliseRisco;
	private $objQuestaoAnaliseRisco;
	private $objQuestionarioAnaliseRisco;
	private $objProfissionalProjeto;
	private $arrParamRisco;

	/**
	 * Método utilizado para inicialiar as classes necessárias.
	 * @see application/lib/Base/Controller/Base_Controller_Action#init()
	 */
	public function init()
	{
		parent::init();
		$this->objProjeto                  = new Projeto($this->_request->getControllerName());
		$this->objAtividade                = new Atividade($this->_request->getControllerName());
		$this->objEtapa                    = new Etapa($this->_request->getControllerName());
		$this->objItemRisco                = new ItemRisco($this->_request->getControllerName());
		$this->objAnaliseRisco             = new AnaliseRisco($this->_request->getControllerName());
		$this->objQuestaoAnaliseRisco      = new QuestaoAnaliseRisco($this->_request->getControllerName());
		$this->objQuestionarioAnaliseRisco = new QuestionarioAnaliseRisco($this->_request->getControllerName());
		$this->objProfissionalProjeto      = new ProfissionalProjeto($this->_request->getControllerName());
	}

	/**
	 * Método de inicialização da tela
	 */
	public function indexAction()
	{
	}

	/**
	 * Método utilizado por ajax para criar a tab dos projetos com proposta em
	 * andamento.
	 */
	public function propostasAndamentoRiscoAction()
	{
		$this->_helper->layout->disableLayout();

		$impacto     = $this->_request->getPost("impacto");
		$impacto     = ($impacto != 'undefined')?$impacto:"tx_cor_impacto_projeto_risco";
		$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];

		$arrProjetosPropostas = $this->objProjeto->getProjetosPropostasExecucao($cd_contrato);
		$totalItem            = $this->objItemRisco->getQtdItem();

		foreach($arrProjetosPropostas as $key=>$value){
			$arrDados     = $this->objAnaliseRisco->getQtdImpacto($value['cd_projeto'],$value['cd_proposta'],null,null,null,$impacto);

			$riscoBranco   = 0;
			$riscoVerde    = 0;
			$riscoAmarelo  = 0;
			$riscoVermelho = 0;
			$riscoCinza    = 0;
			if(count($arrDados) > 0){
				foreach($arrDados as $chave=>$valor){
					$corRisco  = trim($valor[$impacto]);
					$$corRisco = $valor["count"];
				}
			}
			$riscoBranco = ($totalItem - ($riscoVerde + $riscoVermelho + $riscoBranco + $riscoAmarelo + $riscoCinza)) + $riscoBranco;

			$tamanho = ($totalItem != 0)?(250/$totalItem):0;
			$arrProjetosPropostas[$key]['riscoBranco']     = round($riscoBranco*$tamanho);
			$arrProjetosPropostas[$key]['riscoVerde']      = round($riscoVerde*$tamanho);
			$arrProjetosPropostas[$key]['riscoAmarelo']    = round($riscoAmarelo*$tamanho);
			$arrProjetosPropostas[$key]['riscoVermelho']   = round($riscoVermelho*$tamanho);
			$arrProjetosPropostas[$key]['riscoCinza']      = round($riscoCinza*$tamanho);
			$arrProjetosPropostas[$key]['tamanho']         = 250;
			/**
			 * Condição que verifica o tamanho do preenchimento de todas as cores para
			 * colocar o tamanho exato da div no phtml.
			 */
			if($tamanho != 0){
				$arrProjetosPropostas[$key]['tamanho'] = $arrProjetosPropostas[$key]['riscoBranco']
				+$arrProjetosPropostas[$key]['riscoVerde']
				+$arrProjetosPropostas[$key]['riscoAmarelo']
				+$arrProjetosPropostas[$key]['riscoVermelho']
				+$arrProjetosPropostas[$key]['riscoCinza'];
			}
				
		}

		$this->view->arrProjetosPropostas = $arrProjetosPropostas;
		$this->initView();
	}

	/**
	 * Método utilizado pelo ajax para mostrar as etapas, atividades e itens do risco
	 * na tab de item do risco. Montando uma treview.
	 */
	public function analiseItemRiscoAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_objeto   = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$cd_projeto  = $this->_request->getPost('cd_projeto');
		$cd_proposta = $this->_request->getPost('cd_proposta');
		$impacto     = $this->_request->getPost("st_impacto",0);
		$impacto     = ($impacto != 0)?$impacto:"tx_cor_impacto_projeto_risco";

		$objDadosprojeto = $this->objProjeto->find($cd_projeto)->current();
		$this->view->tx_projeto_risco  = $objDadosprojeto->tx_sigla_projeto;
		$this->view->tx_proposta_risco = "Nº {$cd_proposta}";

		$arrEtapas = $this->objEtapa->getDadosEtapa($cd_objeto);
		
		foreach($arrEtapas as $key=>$value){
			//Verificação dos risco da etapa;
			$arrRiscoEtapa = $this->objAnaliseRisco->getQtdImpacto($cd_projeto,$cd_proposta,$value['cd_etapa'],null,null,$impacto);

			$totalItem     = $this->objItemRisco->getQtdItem($value['cd_etapa']);
			if($totalItem != 0){
				$riscoBranco   = 0;
				$riscoVerde    = 0;
				$riscoAmarelo  = 0;
				$riscoVermelho = 0;
				$riscoCinza    = 0;

				if(count($arrRiscoEtapa) > 0){
					foreach($arrRiscoEtapa as $chave=>$valor){
						$corRisco  = trim($valor[$impacto]);
						$$corRisco = $valor["count"];
					}
				}
				$riscoBranco = ($totalItem - ($riscoVerde + $riscoVermelho + $riscoBranco + $riscoAmarelo + $riscoCinza)) + $riscoBranco;
					
				$tamanho = ($totalItem != 0)?(250/$totalItem):0;
				$arrEtapas[$key]['riscoBranco']     = ($tamanho!=0)?round($riscoBranco*$tamanho):250;
				$arrEtapas[$key]['riscoVerde']      = round($riscoVerde*$tamanho);
				$arrEtapas[$key]['riscoAmarelo']    = round($riscoAmarelo*$tamanho);
				$arrEtapas[$key]['riscoVermelho']   = round($riscoVermelho*$tamanho);
				$arrEtapas[$key]['riscoCinza']      = round($riscoCinza*$tamanho);
				$arrEtapas[$key]['tx_riscoBranco']  = "";
				if($tamanho != 0){
					$arrEtapas[$key]['tamanho']         = $arrEtapas[$key]['riscoBranco']+$arrEtapas[$key]['riscoVerde']+$arrEtapas[$key]['riscoAmarelo']+$arrEtapas[$key]['riscoVermelho']+$arrEtapas[$key]['riscoCinza']+1;
				} else {
					$arrEtapas[$key]['tamanho'] = 250;
				}
			} else {
				$arrEtapas[$key]['riscoBranco']     = 250;
				$arrEtapas[$key]['riscoVerde']      = 0;
				$arrEtapas[$key]['riscoAmarelo']    = 0;
				$arrEtapas[$key]['riscoVermelho']   = 0;
				$arrEtapas[$key]['riscoCinza']      = 0;
				$arrEtapas[$key]['tx_riscoBranco']  = Base_Util::getTranslator('L_MSG_ALERT_SEM_ITEM_RISCO_CADASTRADO');
				$arrEtapas[$key]['tamanho']         = 250;
			}
			$arrAtividade[$value['cd_etapa']] = $this->getAtividade($cd_projeto,$cd_proposta,$value['cd_etapa'],$impacto);
			if(count($arrAtividade[$value['cd_etapa']]) > 0){
				foreach($arrAtividade[$value['cd_etapa']] as $chave=>$valor){
					$arrItemsRisco = $this->getItemRisco($cd_projeto, $cd_proposta, $value['cd_etapa'], $valor['cd_atividade'],$impacto);
					if(count($arrItemsRisco) > 0){
						$arrItemRisco["{$value['cd_etapa']}_{$valor['cd_atividade']}"] = $arrItemsRisco;
					}
				}
			}
		}

		$this->view->cd_projeto   = $cd_projeto;
		$this->view->cd_proposta  = $cd_proposta;
		$this->view->arrEtapas    = $arrEtapas;
		$this->view->arrAtividade = $arrAtividade;
		$this->view->arrItens     = $arrItemRisco;
	}

	/**
	 * @since: 22/06/2009
	 * @autor: Wunilberto
	 */
	private function getAtividade($cd_projeto,$cd_proposta,$cd_etapa,$impacto)
	{
		$arrAtividade = $this->objAtividade->getDadosAtividade($cd_etapa);
		if(count($arrAtividade) > 0){
			foreach($arrAtividade as $chave=>$valor){
				//Verificação dos risco da atividade;
				$arrRiscoAtividade = $this->objAnaliseRisco->getQtdImpacto($cd_projeto,$cd_proposta,$cd_etapa,$valor['cd_atividade'],null,$impacto);
				$totalItem     = $this->objItemRisco->getQtdItem($cd_etapa,$valor['cd_atividade']);
				if($totalItem != 0){
					$riscoBranco   = 0;
					$riscoVerde    = 0;
					$riscoAmarelo  = 0;
					$riscoVermelho = 0;
					$riscoCinza    = 0;
						
					if(count($arrRiscoAtividade) > 0 ){
						foreach($arrRiscoAtividade as $key=>$value){
							$corRisco  = trim($value[$impacto]);
							$$corRisco = $value["count"];
						}
					}
						
					$riscoBranco = ($totalItem - ($riscoVerde + $riscoVermelho + $riscoBranco + $riscoAmarelo + $riscoCinza)) + $riscoBranco;

					$tamanho = ($totalItem != 0)?(250/$totalItem):0;
					$arrAtividade[$chave]['riscoBranco']     = ($tamanho!=0)?round($riscoBranco*$tamanho):250;
					$arrAtividade[$chave]['riscoVerde']      = round($riscoVerde*$tamanho);
					$arrAtividade[$chave]['riscoAmarelo']    = round($riscoAmarelo*$tamanho);
					$arrAtividade[$chave]['riscoVermelho']   = round($riscoVermelho*$tamanho);
					$arrAtividade[$chave]['riscoCinza']      = round($riscoCinza*$tamanho);
					$arrAtividade[$chave]['tx_riscoBranco']  = "";
					if($tamanho != 0){
						$arrAtividade[$chave]['tamanho']         = $arrAtividade[$chave]['riscoBranco']+$arrAtividade[$chave]['riscoVerde']+$arrAtividade[$chave]['riscoAmarelo']+$arrAtividade[$chave]['riscoVermelho']+$arrAtividade[$chave]['riscoCinza'];
					} else {
						$arrAtividade[$chave]['tamanho'] = 250;
					}
				} else {
					$arrAtividade[$chave]['riscoBranco']     = 250;
					$arrAtividade[$chave]['riscoVerde']      = 0;
					$arrAtividade[$chave]['riscoAmarelo']    = 0;
					$arrAtividade[$chave]['riscoVermelho']   = 0;
					$arrAtividade[$chave]['riscoCinza']      = 0;
					$arrAtividade[$chave]['tx_riscoBranco']  = Base_Util::getTranslator('L_MSG_ALERT_SEM_ITEM_RISCO_CADASTRADO');
					$arrAtividade[$chave]['tamanho']         = 250;
				}
			}
		} else {
			$arrAtividade = array();
		}
		return $arrAtividade;
	}

	/**
	 * coment
	 * @since: 22/06/2009
	 * @autor: Wunilberto
	 */
	private function getItemRisco($cd_projeto,$cd_proposta,$cd_etapa,$cd_atividade,$impacto)
	{
		$arrDadosItemRisco = $this->objItemRisco->getItemRisco(false, null, $cd_etapa, $cd_atividade);
		if(count($arrDadosItemRisco) > 0){
			foreach($arrDadosItemRisco as $cd_item_risco=>$tx_item_risco) {
				$arrItemRisco[$cd_item_risco]['tx_item_risco'] = $tx_item_risco;
				$arrCorRisco = $this->objAnaliseRisco->fetchAll(
                        $this->objAnaliseRisco->select()
                                ->from($this->objAnaliseRisco,$impacto)
                                ->where("cd_projeto = ?", $cd_projeto , Zend_Db::INT_TYPE)
                                ->where("cd_proposta = ?",$cd_proposta, Zend_Db::INT_TYPE)
                                ->where("cd_etapa = ?", $cd_etapa, Zend_Db::INT_TYPE)
                                ->where("cd_atividade = ?", $cd_atividade, Zend_Db::INT_TYPE)
                                ->where("cd_item_risco = ?", $cd_item_risco, Zend_Db::INT_TYPE)
                )->toArray();
				if(count($arrCorRisco) > 0){
					$arrItemRisco[$cd_item_risco]['cor_risco'] =$arrCorRisco[0][$impacto];
				} else {
					$arrItemRisco[$cd_item_risco]['cor_risco'] ="riscoBranco";
				}
			}
		} else {
			$arrItemRisco = array();
		}
		return $arrItemRisco;
	}

	/**
	 * Método utilizado pelo ajax para montar a tab de questionario dos itens do risco.
	 */
	public function questionarioAnaliseItemRiscoAction()
	{
		$this->_helper->layout->disableLayout();
			
		$arrParam['cd_projeto']    = $this->_request->getParam("cd_projeto");
		$arrParam['cd_proposta']   = $this->_request->getParam("cd_proposta");
		$arrParam['cd_etapa']      = $this->_request->getParam("cd_etapa");
		$arrParam['cd_atividade']  = $this->_request->getParam("cd_atividade");
		$arrParam['cd_item_risco'] = $this->_request->getParam("cd_item_risco");
			
		$arrAnaliseRisco = array();
		$arrAnaliseRisco = $this->objAnaliseRisco->recuperaUltimaAnaliseRisco($arrParam['cd_projeto'],$arrParam['cd_proposta'],$arrParam['cd_etapa'],$arrParam['cd_atividade'],$arrParam['cd_item_risco']);
		if(count($arrAnaliseRisco) == 0 ){
			$arrAnaliseRisco[0]['st_nao_aplica_risco']            = "";
			$arrAnaliseRisco[0]['st_impacto_projeto_risco']       = "";
			$arrAnaliseRisco[0]['st_impacto_custo_risco']         = "";
			$arrAnaliseRisco[0]['st_impacto_tecnico_risco']       = "";
			$arrAnaliseRisco[0]['st_impacto_cronograma_risco']    = "";
			$arrAnaliseRisco[0]['tx_cor_impacto_projeto_risco']   = "riscoBranco";
			$arrAnaliseRisco[0]['tx_cor_impacto_custo_risco']     = "riscoBranco";
			$arrAnaliseRisco[0]['tx_cor_impacto_tecnico_risco']   = "riscoBranco";
			$arrAnaliseRisco[0]['tx_cor_impacto_cronog_risco']    = "riscoBranco";
		}
		//Recupera informações para a grid do sistema
		$this->gridQuestionarioItemRisco($arrParam);
		//Busca os dados para mostrar as informações da sequência realizada
		$this->buscaDadosItem($arrParam);

		$arrFatorRisco        = array();
		$arrFatorRisco[0]     = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrFatorRisco["TBD"] = Base_Util::getTranslator('L_VIEW_COMBO_SER_DETERMINADO');
		$arrFatorRisco["H"]   = Base_Util::getTranslator('L_VIEW_COMBO_ALTO');
		$arrFatorRisco["M"]   = Base_Util::getTranslator('L_VIEW_COMBO_MEDIO');
		$arrFatorRisco["L"]   = Base_Util::getTranslator('L_VIEW_COMBO_BAIXO');
		$arrFatorRisco["NA"]  = Base_Util::getTranslator('L_VIEW_COMBO_NAO_APLICA');

		$arrImpacto        = array();
		$arrImpacto[0]     = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrImpacto["VL"]  = Base_Util::getTranslator('L_VIEW_COMBO_MUITO_BAIXO');
		$arrImpacto["L"]   = Base_Util::getTranslator('L_VIEW_COMBO_BAIXO');
		$arrImpacto["MOD"] = Base_Util::getTranslator('L_VIEW_COMBO_MODERADO');
		$arrImpacto["H"]   = Base_Util::getTranslator('L_VIEW_COMBO_ALTO');
		$arrImpacto["VH"]  = Base_Util::getTranslator('L_VIEW_COMBO_MUITO_ALTO');
		$arrImpacto["TBD"] = Base_Util::getTranslator('L_VIEW_COMBO_SER_DETERMINADO');
		$arrImpacto["NA"]  = Base_Util::getTranslator('L_VIEW_COMBO_NAO_APLICA');

		$this->view->arrImpacto             = $arrImpacto;
		$this->view->arrFatorRisco          = $arrFatorRisco;
		$this->view->arrAnaliseRisco        = $arrAnaliseRisco;
		$this->initView();
	}

	/**
	 * Método que recupera todos os profissionais do projeto.
	 * @param $cd_projeto
	 * @return array $arrDados.
	 */
	private function getProfissionalProjeto($cd_projeto)
	{
		$arrProfissionalProjeto = $this->objProfissionalProjeto->getProfissionalNoProjeto($cd_projeto);
		$arrDados[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		if(count($arrProfissionalProjeto) > 0){
			foreach($arrProfissionalProjeto as $key=>$value){
				$arrDados[$value['cd_profissional']] = $value['tx_profissional'];
			}
		}
		return $arrDados;
	}

	/**
	 * Método que recupera os dados para a descrição do Item para a aba de
	 * Analise de Risco.
	 * O retorno do método já e mandado para a tela direto.
	 * @param $arrParam
	 */
	private function buscaDadosItem(array $arrParam)
	{
		$arrDados = array();
		$arrDados[0] = $this->objProjeto
                            ->fetchRow(
                                $this->objProjeto->select()
                                                 ->from($this->objProjeto,"tx_sigla_projeto")
                                                 ->where("cd_projeto = ?", $arrParam['cd_projeto'],Zend_Db::INT_TYPE)
                            )->toArray();

		$arrDados[1] = $this->objEtapa
                            ->fetchRow(
                                $this->objEtapa->select()
                                               ->from($this->objEtapa,"tx_etapa")
                                               ->where("cd_etapa = ?", $arrParam['cd_etapa'],Zend_Db::INT_TYPE)
                            )->toArray();
			
		$arrDados[2] = $this->objAtividade
                            ->fetchRow(
                                $this->objAtividade->select()
                                                   ->from($this->objAtividade,"tx_atividade")
                                                   ->where("cd_atividade = ?", $arrParam['cd_atividade'],Zend_Db::INT_TYPE)
                            )->toArray();

		$arrDados[3] = $this->objItemRisco
                            ->fetchRow(
                                $this->objItemRisco->select()
                                                   ->from($this->objItemRisco,"tx_item_risco")
                                                   ->where('cd_item_risco = ?',$arrParam['cd_item_risco'],Zend_Db::INT_TYPE)
                        )->toArray();
			
		$arrDados[4]['cd_proposta'] = $arrParam['cd_proposta'];
		$this->view->arrDados = $arrDados;
	}

	/**
	 * Método que monta a grid na aba de Analise de Risco.
	 * recupera as questões do item e já recupera as respostas se possuir.
	 * Retorno Direto para a tela.
	 * @param $arrParam
	 */
	private function gridQuestionarioItemRisco($arrParam)
	{
		$arrQuestionario = $this->objQuestaoAnaliseRisco->getDadosQuestaoAnaliseRisco($arrParam['cd_etapa'],$arrParam['cd_atividade'],$arrParam['cd_item_risco']);
		if(count($arrQuestionario) > 0){
			foreach($arrQuestionario as $key=>$value){
				$resposta = $this->objQuestionarioAnaliseRisco->fetchAll(
                                        $this->objQuestionarioAnaliseRisco->select()
                                                                          ->from($this->objQuestionarioAnaliseRisco,
                                                                                 "st_resposta_analise_risco")
                                                                          ->where("cd_projeto               = ?", $arrParam['cd_projeto'           ], Zend_Db::INT_TYPE)
                                                                          ->where("cd_proposta              = ?", $arrParam['cd_proposta'          ], Zend_Db::INT_TYPE)
                                                                          ->where("cd_etapa                 = ?", $arrParam['cd_etapa'             ], Zend_Db::INT_TYPE)
                                                                          ->where("cd_atividade             = ?", $arrParam['cd_atividade'         ], Zend_Db::INT_TYPE)
                                                                          ->where("cd_item_risco            = ?", $arrParam['cd_item_risco'        ], Zend_Db::INT_TYPE)
                                                                          ->where("cd_questao_analise_risco = ?", $value['cd_questao_analise_risco'], Zend_Db::INT_TYPE)
                                        )->toArray();
				if(count($resposta)>0){
					$arrQuestionario[$key]['st_resposta_analise_risco'] = $resposta[0]['st_resposta_analise_risco'];
				} else {
					$arrQuestionario[$key]['st_resposta_analise_risco'] = 0;
				}
			}
		}
		$arrQuestao        = array();
		$arrQuestao["TBD"] = Base_Util::getTranslator('L_VIEW_COMBO_SER_DETERMINADO');
		$arrQuestao["Y"]   = Base_Util::getTranslator('L_VIEW_COMBO_SIM');
		$arrQuestao["N"]   = Base_Util::getTranslator('L_VIEW_COMBO_NAO');
		$arrQuestao["NA"]  = Base_Util::getTranslator('L_VIEW_COMBO_NAO_APLICA');
		$this->view->arrQuestao      = $arrQuestao;
		$this->view->arrQuestionario = $arrQuestionario;
	}

	/**
	 * Método para o phtml da Aba de analise do Risco
	 * @since: 24/06/2009 23:09:32
	 * @autor: Wunilberto
	 */
	public function analiseRiscoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$arrParam['cd_projeto']    = $this->_request->getParam("cd_projeto");
		$arrParam['cd_proposta']   = $this->_request->getParam("cd_proposta");
		$arrParam['cd_etapa']      = $this->_request->getParam("cd_etapa");
		$arrParam['cd_atividade']  = $this->_request->getParam("cd_atividade");
		$arrParam['cd_item_risco'] = $this->_request->getParam("cd_item_risco");
		
		$arrAnaliseRisco = array();
		$arrAnaliseRisco = $this->objAnaliseRisco->recuperaUltimaAnaliseRisco($arrParam['cd_projeto'],$arrParam['cd_proposta'],$arrParam['cd_etapa'],$arrParam['cd_atividade'],$arrParam['cd_item_risco']);
		if(count($arrAnaliseRisco) == 0 ) {
            $arrAnaliseRisco[0]['tx_analise_risco']            = "";
            $arrAnaliseRisco[0]['tx_acao_analise_risco']       = "";
            $arrAnaliseRisco[0]['st_fechamento_risco']         = "";
            $arrAnaliseRisco[0]['cd_profissional']             = "";
            $arrAnaliseRisco[0]['cd_profissional_responsavel'] = ""; 
            $arrAnaliseRisco[0]['dt_limite_acao']              = "";
            $arrAnaliseRisco[0]['st_acao']                     = "";
            $arrAnaliseRisco[0]['tx_observacao_acao']          = "";
            $arrAnaliseRisco[0]['dt_fechamento_risco']         = "";
            $arrAnaliseRisco[0]['tx_mitigacao_risco']          = "";
		} else {
			if($arrAnaliseRisco[0]['dt_limite_acao'] != ""){
            	$arrAnaliseRisco[0]['dt_limite_acao'] = date('d/m/Y', strtotime($arrAnaliseRisco[0]['dt_limite_acao']));
			} 
		}
		
		//Busca os dados para mostrar as informações da sequência realizada
		$this->buscaDadosItem($arrParam);
		
		$arrStatus = array();
		$arrStatus[0]   = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrStatus['A'] = Base_Util::getTranslator('L_VIEW_COMBO_ABERTO');
		$arrStatus['T'] = Base_Util::getTranslator('L_VIEW_COMBO_EM_TRATAMENTO');
		$arrStatus['F'] = Base_Util::getTranslator('L_VIEW_COMBO_FECHADO');
		
		$this->view->arrProfissionalProjeto = $this->getProfissionalProjeto($arrParam['cd_projeto']);
		$this->view->arrAnaliseRisco        = $arrAnaliseRisco;
		$this->view->arrStatus              = $arrStatus;
	}

	/**
	  * Método para calcular o risco da aba de Questionário de risco
	  * @since: 25/06/2009 13:47:58
	  * @autor: Wunilberto
	  */
	public function calcularQuestionarioAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrPost = $this->_request->getPost();
		
		if(array_key_exists("st_nao_aplica_risco",$arrPost)){
			$arrRetorno['erro'] = false;
			//Cores do risco
			$arrRetorno['tx_cor_impacto_projeto_risco']= "riscoCinza"; 			
			$arrRetorno['tx_cor_impacto_tecnico_risco']= "riscoCinza"; 			
			$arrRetorno['tx_cor_impacto_custo_risco']  = "riscoCinza"; 			
			$arrRetorno['tx_cor_impacto_cronog_risco'] = "riscoCinza";
			//valores do campo 			
			$arrRetorno['st_impacto_projeto_risco']    = "NA"; 			
			$arrRetorno['st_impacto_tecnico_risco']    = "NA"; 			
			$arrRetorno['st_impacto_custo_risco']      = "NA"; 			
			$arrRetorno['st_impacto_cronograma_risco'] = "NA"; 			
		} else {
			$this->arrParamRisco = array();
			$this->arrParamRisco['cd_profissional']                = $_SESSION['oasis_logged'][0]['cd_profissional'];
			$this->arrParamRisco['cd_projeto']                     = $arrPost['cd_projeto'];
			$this->arrParamRisco['cd_proposta']                    = $arrPost['cd_proposta'];
			$this->arrParamRisco['cd_etapa']                       = $arrPost['cd_etapa_risco'];
			$this->arrParamRisco['cd_atividade']                   = $arrPost['cd_atividade_risco'];
			$this->arrParamRisco['cd_item_risco']                  = $arrPost['cd_item_risco'];
			$this->arrParamRisco['st_impacto_projeto_risco']       = $arrPost['st_impacto_projeto_risco'];
			$this->arrParamRisco['st_impacto_custo_risco']         = $arrPost['st_impacto_custo_risco'];
			$this->arrParamRisco['st_impacto_tecnico_risco']       = $arrPost['st_impacto_tecnico_risco'];
			$this->arrParamRisco['st_impacto_cronograma_risco']    = $arrPost['st_impacto_cronograma_risco'];
			//Apaga os dados desnecessarios 	
			unset($arrPost['cd_projeto']);
			unset($arrPost['cd_proposta']);
			unset($arrPost['cd_etapa_risco']);
			unset($arrPost['cd_atividade_risco']);
			unset($arrPost['cd_item_risco']);
			unset($arrPost['st_impacto_projeto_risco']);
			unset($arrPost['st_impacto_custo_risco']);
			unset($arrPost['st_impacto_tecnico_risco']);
			unset($arrPost['st_impacto_cronograma_risco']);
			
			$erro = false;
			try{
				$db = Zend_Registry::get('db');
				$db->beginTransaction();
	
				$erro = (!$erro) ? $this->salvaAnaliseRisco() : true;
				$erro = (!$erro) ? $this->salvaQuestionarioRisco($arrPost) : true;
				$erro = (!$erro) ? $arrRetorno = $this->calculoQuestionario(): true;
				//valores do campo da tela
				$arrRetorno['erro'] = false;
				$arrRetorno['st_impacto_projeto_risco'   ] = $this->arrParamRisco['st_impacto_projeto_risco'];
				$arrRetorno['st_impacto_tecnico_risco'   ] = $this->arrParamRisco['st_impacto_tecnico_risco'];
				$arrRetorno['st_impacto_custo_risco'     ] = $this->arrParamRisco['st_impacto_custo_risco'  ];
				$arrRetorno['st_impacto_cronograma_risco'] = $this->arrParamRisco['st_impacto_cronograma_risco'];
				
				$db->rollBack();
			}catch(Base_Exception_Alert $e){
				$arrRetorno['erro'] = true;
				$arrRetorno['type'] = 2;
				$arrRetorno['msg' ] = $e->getMessage();
			}catch(Zend_Exception $e){
				$arrRetorno['erro'] = true;
				$arrRetorno['type'] = 3;
				$arrRetorno['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO_CALCULO_RISCO').$e->getMessage();
			}
		}
		echo Zend_Json::encode($arrRetorno);	
	}
	
	/**
	 * Método para calcular o Questionario do risco
	 * @return array
	 */
	private function calculoQuestionario()
	{
		$qtdPesoTotal = (int)$this->objQuestionarioAnaliseRisco->getValorPesoResposta($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco'],'NA','<>');
		$qtdPesoYes   = (int)$this->objQuestionarioAnaliseRisco->getValorPesoResposta($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco'],'Y');
		$qtdPesoNo    = (int)$this->objQuestionarioAnaliseRisco->getValorPesoResposta($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco'],'N');
		$qtdPesoTBD   = (int)$this->objQuestionarioAnaliseRisco->getValorPesoResposta($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco'],'TBD');
		
		return $this->calcGeral($qtdPesoTotal,$qtdPesoYes,$qtdPesoNo,$qtdPesoTBD);
	}
	
	/**
	 * Método que recebe os paramêtros para salvar os dados da analise e
	 * os dados do questionário.
	 * @return json $arrResult
	 */
	public function salvaQuestionarioAnaliseAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost = $this->_request->getPost();
		
		$this->arrParamRisco = array();
		$this->arrParamRisco['cd_profissional']                = $_SESSION['oasis_logged'][0]['cd_profissional'];
		$this->arrParamRisco['cd_projeto']                     = $arrPost['cd_projeto'];
		$this->arrParamRisco['cd_proposta']                    = $arrPost['cd_proposta'];
		$this->arrParamRisco['cd_etapa']                       = $arrPost['cd_etapa_risco'];
		$this->arrParamRisco['cd_atividade']                   = $arrPost['cd_atividade_risco'];
		$this->arrParamRisco['cd_item_risco']                  = $arrPost['cd_item_risco'];
		unset($arrPost['cd_projeto']);
		unset($arrPost['cd_proposta']);
		unset($arrPost['cd_etapa_risco']);
		unset($arrPost['cd_atividade_risco']);
		unset($arrPost['cd_item_risco']);

		if(array_key_exists('st_impacto_projeto_risco',$arrPost)){
			$this->arrParamRisco['st_impacto_projeto_risco'] = $arrPost['st_impacto_projeto_risco'];
			unset($arrPost['st_impacto_projeto_risco']);
		}
		if(array_key_exists('st_impacto_custo_risco',$arrPost)){
			$this->arrParamRisco['st_impacto_custo_risco'] = $arrPost['st_impacto_custo_risco'];
			unset($arrPost['st_impacto_custo_risco']);
		}
		if(array_key_exists('st_impacto_tecnico_risco',$arrPost)){
			$this->arrParamRisco['st_impacto_tecnico_risco'] = $arrPost['st_impacto_tecnico_risco'];
			unset($arrPost['st_impacto_tecnico_risco']);
		}
		if(array_key_exists('st_impacto_cronograma_risco',$arrPost)){
			$this->arrParamRisco['st_impacto_cronograma_risco'] = $arrPost['st_impacto_cronograma_risco'];
			unset($arrPost['st_impacto_cronograma_risco']);
		}
		if(array_key_exists('tx_analise_risco',$arrPost)){
			$this->arrParamRisco['tx_analise_risco'] = $arrPost['tx_analise_risco'];
			unset($arrPost['tx_analise_risco']);
		}
		if(array_key_exists('tx_acao_analise_risco',$arrPost)){
			$this->arrParamRisco['tx_acao_analise_risco'] = $arrPost['tx_acao_analise_risco'];
			unset($arrPost['tx_acao_analise_risco']);
		}
		if(array_key_exists('cd_profissional_responsavel',$arrPost)){
			$this->arrParamRisco['cd_profissional_responsavel'] = $arrPost['cd_profissional_responsavel'];
			unset($arrPost['cd_profissional_responsavel']);
		}
		if(array_key_exists('dt_limite_acao',$arrPost)){
			$this->arrParamRisco['dt_limite_acao'] = $arrPost['dt_limite_acao'];
			unset($arrPost['dt_limite_acao']);
		}
		if(array_key_exists('st_acao',$arrPost)){
			$this->arrParamRisco['st_acao'] = $arrPost['st_acao'];
			unset($arrPost['st_acao']);
		}
		if(array_key_exists('tx_observacao_acao',$arrPost)){
			$this->arrParamRisco['tx_observacao_acao'] = $arrPost['tx_observacao_acao'];
			unset($arrPost['tx_observacao_acao']);
		}
		if(array_key_exists('tx_mitigacao_risco',$arrPost)){
			$this->arrParamRisco['tx_mitigacao_risco'] = $arrPost['tx_mitigacao_risco'];
			unset($arrPost['tx_mitigacao_risco']);
		}
		if(array_key_exists('st_nao_aplica_risco',$arrPost)){
			$this->arrParamRisco['st_nao_aplica_risco'] = $arrPost['st_nao_aplica_risco'];
			$this->arrParamRisco['st_impacto_projeto_risco']    = "NA";
			$this->arrParamRisco['st_impacto_tecnico_risco']    = "NA";
			$this->arrParamRisco['st_impacto_custo_risco']      = "NA";
			$this->arrParamRisco['st_impacto_cronograma_risco'] = "NA";
			unset($arrPost['st_nao_aplica_risco']);
			foreach($arrPost as $key=>$value){
				$arrPost[$key] = "NA";
			}
		}
		
		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO_INFORMACOES_ANALISE_QUESTIONARIO_RISCO'));
		$erro = false;
			
		try{
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			$erro = (!$erro) ? $this->salvaAnaliseRisco() : true;
			$erro = (!$erro) ? $this->salvaQuestionarioRisco($arrPost) : true;
			$erro = (!$erro) ? $this->calcRiscoItem():true;

			if($erro){
				$db->rollBack();
			} else {
				$db->commit();
			}
		}catch(Base_Exception_Alert $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg' ] = $e->getMessage();
		}catch(Base_Exception_Error $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = $e->getMessage();
		}catch(Zend_Exception $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_INFORMACOES_ANALISE_QUESTIONARIO_RISCO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	/**
	  * Método que ira salvar os dados da analise do risco
	  * @since: 25/06/2009 21:54:40
	  * @autor: Wunilberto
	  */
	public function salvarAnaliseAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$this->arrParamRisco = $this->_request->getPost();
		
		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO_INFORMACOES_ANALISE_QUESTIONARIO_RISCO'));
		$erro = false;
		try{
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			$erro = (!$erro) ? $this->salvaAnaliseRisco() : true;
			
			if($erro){
				$db->rollBack();
			} else {
				$db->commit();
			}
		}catch(Base_Exception_Alert $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg'] = $e->getMessage();
		}catch(Base_Exception_Error $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = $e->getMessage();
		}catch(Zend_Exception $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_INFORMACOES_ANALISE_QUESTIONARIO_RISCO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
	
	/**
	 * Método que salva os dados da analise
	 * @return boolean
	 */
	private function salvaAnaliseRisco()
	{
		$return = $this->objAnaliseRisco->validaDadosGravacao($this->arrParamRisco);
		if($return){
			throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_ANALISE_RISCO'));
		}
		return $return;
	}

	/**
	 * Método que salva os dados do questionário do risco
	 * @param $arrQuestionario
	 * @return boolean
	 */
	private function salvaQuestionarioRisco(array $arrQuestionario)
	{
		$return = false;
			
		$arrAnalise = $this->objAnaliseRisco->recuperaUltimaAnaliseRisco($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco']);
		if(count($arrAnalise) > 0){
			//			if(!empty($arrAnalise[0]['st_fechamento_risco'])){
			$this->arrParamRisco['dt_analise_risco'] = $arrAnalise[0]['dt_analise_risco'];
			foreach($arrQuestionario as $key=>$value){
				$arrTmp = explode('-',$key);
					
				$this->arrParamRisco['cd_questao_analise_risco']  = $arrTmp[1];
				$this->arrParamRisco['st_resposta_analise_risco'] = $value;
				$return = $this->objQuestionarioAnaliseRisco->validaDadosGravacao($this->arrParamRisco);
				if($return){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_QUESTIONARIO_RISCO'));
				}
			}
		} else {
			if(!$return){
				throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_ANALISE_RISCO_NAO_LOCALIZADA'));
			}
			$return = true;
		}
		return $return;
	}

	protected function calcRiscoItem()
	{
		$erro = false;
		if(array_key_exists("st_nao_aplica_risco",$this->arrParamRisco)){
			$arrCorRisco['cd_projeto']                  = $this->arrParamRisco['cd_projeto'];
			$arrCorRisco['cd_proposta']                 = $this->arrParamRisco['cd_proposta'];
			$arrCorRisco['cd_etapa']                    = $this->arrParamRisco['cd_etapa'];
			$arrCorRisco['cd_atividade']                = $this->arrParamRisco['cd_atividade'];
			$arrCorRisco['cd_item_risco']               = $this->arrParamRisco['cd_item_risco'];
			$arrCorRisco['tx_cor_impacto_projeto_risco']= "riscoCinza";
    		$arrCorRisco['tx_cor_impacto_tecnico_risco']= "riscoCinza";
    		$arrCorRisco['tx_cor_impacto_custo_risco']  = "riscoCinza";
    		$arrCorRisco['tx_cor_impacto_cronog_risco'] = "riscoCinza";
			
			$erro = $this->objAnaliseRisco->validaDadosGravacao($arrCorRisco);
			if($erro){
				throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_CALCULO_RISCO_NAO_REALIZADO'));
			}			
		} else {	
			$qtdPesoTotal = (int)$this->objQuestionarioAnaliseRisco->getValorPesoResposta($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco'],'NA','<>');
			$qtdPesoYes   = (int)$this->objQuestionarioAnaliseRisco->getValorPesoResposta($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco'],'Y');
			$qtdPesoNo    = (int)$this->objQuestionarioAnaliseRisco->getValorPesoResposta($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco'],'N');
			$qtdPesoTBD   = (int)$this->objQuestionarioAnaliseRisco->getValorPesoResposta($this->arrParamRisco['cd_projeto'],$this->arrParamRisco['cd_proposta'],$this->arrParamRisco['cd_etapa'],$this->arrParamRisco['cd_atividade'],$this->arrParamRisco['cd_item_risco'],'TBD');
	
			$arrCorRisco = $this->calcGeral($qtdPesoTotal,$qtdPesoYes,$qtdPesoNo,$qtdPesoTBD);
			if(count($arrCorRisco) > 0){
				$arrCorRisco['cd_projeto']    = $this->arrParamRisco['cd_projeto'];
				$arrCorRisco['cd_proposta']   = $this->arrParamRisco['cd_proposta'];
				$arrCorRisco['cd_etapa']      = $this->arrParamRisco['cd_etapa'];
				$arrCorRisco['cd_atividade']  = $this->arrParamRisco['cd_atividade'];
				$arrCorRisco['cd_item_risco'] = $this->arrParamRisco['cd_item_risco'];
	
				$erro = $this->objAnaliseRisco->validaDadosGravacao($arrCorRisco);
				if($erro){
					throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_CALCULO_RISCO_NAO_REALIZADO'));
				}
			} else {
				throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_CALCULO_ITEM_RISCO_NAO_REALIZADO'));
				$erro = true;
			}
		}
		return $erro;
	}

	/**
	 * Método que realiza o calculo geral do risco e retorna
	 * um array com as cores de cada impacto.
	 * @param $qtdPesoTotal
	 * @param $qtdPesoYes
	 * @param $qtdPesoNo
	 * @param $qtdPesoTBD
	 * @return array analise
	 */
	private function calcGeral($qtdPesoTotal,$qtdPesoYes,$qtdPesoNo,$qtdPesoTBD)
	{
		$arrAnalise = array();
		if(($qtdPesoTBD > $qtdPesoNo) && ($qtdPesoTBD > $qtdPesoYes)){
			$arrAnalise['tx_impacto_projeto']    = "riscoBranco";
			$arrAnalise['tx_impacto_tecnico']    = "riscoBranco";
			$arrAnalise['tx_impacto_custo']      = "riscoBranco";
			$arrAnalise['tx_impacto_cronograma'] = "riscoBranco";
		} else {
			$media = (($qtdPesoNo+$qtdPesoTBD)/$qtdPesoTotal);

			$y = $this->resultProbabilidade($media);
			$arrDados = $this->getImpactoAnalise();

			$arrAnalise['tx_cor_impacto_projeto_risco'] = trim($this->corGrafico($arrDados['tx_impacto_projeto'],$y));
			$arrAnalise['tx_cor_impacto_tecnico_risco'] = trim($this->corGrafico($arrDados['tx_impacto_tecnico'],$y));
			$arrAnalise['tx_cor_impacto_custo_risco'  ] = trim($this->corGrafico($arrDados['tx_impacto_custo'  ],$y));
			$arrAnalise['tx_cor_impacto_cronog_risco' ] = trim($this->corGrafico($arrDados['tx_impacto_cronograma'],$y));
		}
			
		return $arrAnalise;
	}

	private function getImpactoAnalise()
	{
		$arrDados = $this->objAnaliseRisco->recuperaUltimaAnaliseRisco(
		$this->arrParamRisco['cd_projeto'],
		$this->arrParamRisco['cd_proposta'],
		$this->arrParamRisco['cd_etapa'],
		$this->arrParamRisco['cd_atividade'],
		$this->arrParamRisco['cd_item_risco']);

		$arrAnalise['tx_impacto_projeto']    = $this->valoImpacto(trim($arrDados[0]['st_impacto_projeto_risco']));
		$arrAnalise['tx_impacto_tecnico']    = $this->valoImpacto(trim($arrDados[0]['st_impacto_tecnico_risco']));
		$arrAnalise['tx_impacto_custo']      = $this->valoImpacto(trim($arrDados[0]['st_impacto_custo_risco']));
		$arrAnalise['tx_impacto_cronograma'] = $this->valoImpacto(trim($arrDados[0]['st_impacto_cronograma_risco']));
		return $arrAnalise;
	}

	private function valoImpacto($siglaImpacto)
	{
		if($siglaImpacto == "VL"){
			return 1;
		} else if($siglaImpacto == "L"){
			return 2;
		} else if($siglaImpacto == "MOD"){
			return 3;
		} else if($siglaImpacto == "H"){
			return 4;
		} else if($siglaImpacto == "VH"){
			return 5;
		} else if($siglaImpacto == "NA"){
			return 6;
		} else if($siglaImpacto == "TDB"){
			return 7;
		}
	}

	private function resultProbabilidade($probabilidade)
	{
		if($probabilidade < 0.2){
			return 1;
		} else if((0.2 <= $probabilidade) && ($probabilidade < 0.4)){
			return 2;
		} else if((0.4 <= $probabilidade) && ($probabilidade < 0.6)){
			return 3;
		} else if((0.6 <= $probabilidade) && ($probabilidade < 0.8)){
			return 4;
		} else if(0.8 <= $probabilidade){
			return 5;
		}
	}

	private function corGrafico($x,$y)
	{
		$cor = "";
		$result = "{$x}_{$y}";
		switch ($result) {
			case "1_1":
				$cor = "riscoVerde";
				break;
			case "1_2":
				$cor = "riscoVerde";
				break;
			case "1_3":
				$cor = "riscoVerde";
				break;
			case "1_4":
				$cor = "riscoVerde";
				break;
			case "1_5":
				$cor = "riscoAmarelo";
				break;
			case "2_1":
				$cor = "riscoVerde";
				break;
			case "2_2":
				$cor = "riscoVerde";
				break;
			case "2_3":
				$cor = "riscoVerde";
				break;
			case "2_4":
				$cor = "riscoAmarelo";
				break;
			case "2_5":
				$cor = "riscoAmarelo";
				break;
			case "3_1":
				$cor = "riscoVerde";
				break;
			case "3_2":
				$cor = "riscoVerde";
				break;
			case "3_3":
				$cor = "riscoAmarelo";
				break;
			case "3_4":
				$cor = "riscoAmarelo";
				break;
			case "3_5":
				$cor = "riscoVermelho";
				break;
			case "4_1":
				$cor = "riscoVerde";
				break;
			case "4_2":
				$cor = "riscoAmarelo";
				break;
			case "4_3":
				$cor = "riscoAmarelo";
				break;
			case "4_4":
				$cor = "riscoVermelho";
				break;
			case "4_5":
				$cor = "riscoVermelho";
				break;
			case "5_1":
				$cor = "riscoAmarelo";
				break;
			case "5_2":
				$cor = "riscoAmarelo";
				break;
			case "5_3":
				$cor = "riscoVermelho";
				break;
			case "5_4":
				$cor = "riscoVermelho";
				break;
			case "5_5":
				$cor = "riscoVermelho";
				break;
			case "6_1":
				$cor = "riscoCinza";
				break;
			case "6_2":
				$cor = "riscoCinza";
				break;
			case "6_3":
				$cor = "riscoCinza";
				break;
			case "6_4":
				$cor = "riscoCinza";
				break;
			case "6_5":
				$cor = "riscoCinza";
				break;
			default:
				$cor = "riscoBranco";
				break;
		}
		return $cor;
	}
	
	public function fecharAnaliseRiscoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$this->arrParamRisco            = $this->_request->getPost();
		$this->arrParamRisco['st_acao'] = "F";
		
		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_FECHAR_ITEM_RISCO'));
		$erro = false;
		try{
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			$erro = (!$erro) ? $this->salvaAnaliseRisco() : true;
			
			if($erro){
				$db->rollBack();
			} else {
				$db->commit();
			}
		}catch(Base_Exception_Alert $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg'] = $e->getMessage();
		}catch(Zend_Exception $e){
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg'] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
}