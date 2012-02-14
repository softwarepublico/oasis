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

class GerenciarProjetosController extends Base_Controller_Action
{
	private $zendDate;
	private $objUtil;
	private $objDefinicaoMetrica;

	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_GERENCIAR_PROJETO'));
		$this->objUtil      		= new Base_Controller_Action_Helper_Util();
		$this->zendDate 			= new Zend_Date();
		$this->objDefinicaoMetrica	= new DefinicaoMetrica($this->_request->getControllerName());
		
	}

	public function indexAction()
	{
		$cd_contrato = $_SESSION["oasis_logged"][0]["cd_contrato"];
		
		$proposta        = new Proposta($this->_request->getControllerName());

		$this->view->res = $proposta->getPropostaAberta($cd_contrato);

		$mesAtual    = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		$anoAtual    = date("Y");
		
		$this->view->mesComboValue = $mesAtual;
		$this->view->anoComboValue = $anoAtual;
		$this->view->mesAno        = "{$this->objUtil->getMes($mesAtual)}/{$anoAtual}";
		
		$solicitacao = new Solicitacao($this->_request->getControllerName());
		$solicitacaoSelect = $solicitacao->select();
		$solicitacaoSelect->distinct();
		$solicitacaoSelect->from($solicitacao, array('ni_ano_solicitacao'));
		$resAnoSolicitacao = $solicitacao->fetchAll($solicitacaoSelect);
		
		/*
		 * CARREGANDO DADOS DO DIALOG DE ABERTURA DE PROPOSTA.
		 */
				//*** PRÉ-PROJETO
		// Executa pesquisa para o combobox de pré projetos
		$preProjeto      = new PreProjeto($this->_request->getControllerName());

		// Utiliza o objeto select para definir um order by da consulta
		$selectPreProjeto = $preProjeto->select()->where("cd_contrato = {$cd_contrato}");
		$selectPreProjeto->order(array('tx_sigla_pre_projeto'));

		// Recupera os dados e armazena em um array
		$resPreProjeto    = $preProjeto->fetchAll($selectPreProjeto);
		
		//*** PROJETO
		// Executa pesquisa para o combobox de projetos
		//busca apenas os projetos que edtão associados ao contrato do usuário que está logado
		$contratoProjeto          = new ContratoProjeto($this->_request->getControllerName());
		$arrProjetos              = $contratoProjeto->listaProjetosContrato($cd_contrato, true);

        $statusProjeto            = new Status($this->_request->getControllerName());
        $arrStatus                = $statusProjeto->getStatus(true);
        
        $this->view->projetoCombo = $arrProjetos;
        $this->view->statusCombo  = $arrStatus;

		// Cria um array que manterá os valores do combobox Pre Projeto
		$preProjetoCombo = array(Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE'), 'nenhum' => Base_Util::getTranslator('L_VIEW_COMBO_NENHUM'));

		// Percorre o resultset e adiciona os valores ao array que colocará os valores no combobox
		// O Indice será o value do option do select e o valor sera o label do option do select
		foreach ($resPreProjeto as $nomePreProjeto)
		{
			$preProjetoCombo[$nomePreProjeto->cd_pre_projeto] = $nomePreProjeto->tx_sigla_pre_projeto;
		}

		//  Associa este array com um atributo da camada de visao
		$this->view->preProjetoCombo = $preProjetoCombo;


		
		$preProjeto                = new PreProjeto($this->_request->getControllerName());
		$this->view->resPreProjeto = $preProjeto->getListaPreProjeto($cd_contrato);	
			
		$preProjetoEvolutivo                = new PreProjetoEvolutivo($this->_request->getControllerName());
		$this->view->resPreProjetoEvolutivo = $preProjetoEvolutivo->getListaPreProjetoEvolutivo($cd_contrato);	
		
		//*** TAB CONSULTA DE SOLICITAÇÕES DE SERVIÇO
		//Combo Unidade
		$unidade                      = new Unidade($this->_request->getControllerName());
		$arrUnidade                   = $unidade->getUnidade(true);
		$this->view->cd_unidade_combo = $arrUnidade;

		//Combo Profissional
		$cd_objeto       = $_SESSION['oasis_logged'][0]['cd_objeto'];
		$profissional    = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$arrProfissional = $profissional->getProfissionalGerenteObjetoContrato($cd_objeto, true);
		$this->view->cd_profissional_combo = $arrProfissional;
		
		//*** TAB CALCULO DE MÉTRICA
		//Combo de metrica
		$this->view->arrMetrica = $this->objDefinicaoMetrica->getComboDefinicaoMetrica( $_SESSION['oasis_logged'][0]['cd_contrato'], true );
	}
    
    public function getStatusProjetoAction()
    {
        
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $formData = $this->_request->getPost();
        $cd_projeto = $formData['cd_projeto'];
		
		if($cd_projeto != 0){

           $statusProjeto = new Status($this->_request->getControllerName());
           $projeto       = new Projeto($this->_request->getControllerName());
           
           $arrStatus         = $statusProjeto->getStatus();
           $cd_status_projeto = $projeto->getStatusProjeto($cd_projeto);
          
           $strOptions='';
           foreach ($arrStatus as $cd_status=>$status) {
                if ($cd_status_projeto != $cd_status) {
                    $strOptions .= "<option value=\"{$cd_status}\" label=\"{$status}\"> {$status}</option>";
                }else{
                    $strOptions .= "<option value=\"{$cd_status}\" selected=\"selected\" label=\"{$status}\"> {$status} </option>";
			     }
            }
		} 
		echo $strOptions;
    }
}