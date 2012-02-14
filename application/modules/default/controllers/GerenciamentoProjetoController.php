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

class GerenciamentoProjetoController extends Base_Controller_Action
{

	private $_objUtil;
	private $_objetoContrato;
	private $_perfil;
	private $_objProposta;
	private $_objReuniao;
	private $_objProjeto;
	private $_objContrato;
	private $_objContratoProjeto;
    private $_objProfissionalObjetoContrato;

	public function init()
	{
		parent::init();
		$this->_objUtil			   = new Base_Controller_Action_Helper_Util();
		$this->_objetoContrato	   = new ObjetoContrato($this->_request->getControllerName());
		$this->_perfil			   = new Perfil($this->_request->getControllerName());
		$this->_objProposta		   = new Proposta($this->_request->getControllerName());
		$this->_objReuniao		   = new Reuniao($this->_request->getControllerName());
		$this->_objProjeto		   = new Projeto($this->_request->getControllerName());
		$this->_objContrato         = new Contrato($this->_request->getControllerName());
		$this->_objContratoProjeto  = new ContratoProjeto($this->_request->getControllerName());
        $this->_objProfissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_GERENCIAMENTO_PROJETO'));

		/*
		 * Verifica se o usuário possui permissão para ver dados de outros contratos
		além do que ele está associado e preenche a combo de Contrato com os dados
		correspondentes à sua permissão
		*/
		$comStatus   = true;
		$cd_contrato = null;

		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus   = false;
		}

		$this->view->arrContrato = $this->_objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);

		//pegando mes e ano do sistema para enviar com a descrição e o ano
		$mes = date('n');
		$ano = date('Y');
		$this->view->mesAno = $this->_objUtil->getMes($mes)."/".$ano;
		
		//condição que checa a permissão para acessar o accordion de Análise de Execução
		if (ChecaPermissao::possuiPermissao('analise-execucao-projeto') === true){
			//metodo passa todas as informações necessarias para a tela de Análise de Execução
			$this->accordionAnaliseExecucaoProjeto();
		}
		//condição que checa a permissão para acessar o accordion de Gerenciamento de Teste
		if (ChecaPermissao::possuiPermissao('gerenciamento-teste') === true){
			//metodo passa todas as informações necessarias para a tela de Gerenciamento de Teste
			$this->accordionGerenciamentoTeste();
		}

		//condição que checa a permissão para acessar o accordion de Pedido de Mudança
		if (ChecaPermissao::possuiPermissao('pedido-mudanca') === true){
			//metodo passa todas as informações necessarias para a tela de Pedido de Mudança
			$this->accordionPedidoMudanca();
		}

		//condição que checa a permissão para acessar o accordion de Análise de Mudança
		if (ChecaPermissao::possuiPermissao('gerenciamento-mudanca') === true){
			//metodo passa todas as informações necessarias para a tela de Análise de Mudança
			$this->accordionAnaliseMudanca();
		}

		//condição que checa a permissão para acessar o accordion de Baseline
		if (ChecaPermissao::possuiPermissao('baseline') === true){
			//metodo passa todas as informações necessarias para a tela de Baseline
			$this->accordionBaseline();
		}
		
		//condição que checa a permissão para acessar o accordion de Baseline
		if (ChecaPermissao::possuiPermissao('planejamento') === true){
			//metodo passa todas as informações necessarias para a tela de Baseline
			$this->accordionPlanejamento();
		}
        /**
		 * Accordion Gerência de Qualidade
		 */
		if(ChecaPermissao::possuiPermissao('gerencia-qualidade') === true){
			$this->accordionGerenciaQualidade();
		}
	}
	
	/**
	 * Método que manda as informações da tela de Pedido de Mudança
	 */
	private function accordionPedidoMudanca()
	{
		$cd_contrato = ($_SESSION["oasis_logged"][0]["st_dados_todos_contratos"] == "S") ? 0 : $_SESSION["oasis_logged"][0]["cd_contrato"];
		$this->view->arrProjetosPedidoMudanca = $this->_objContratoProjeto->listaProjetosContrato($cd_contrato, true, false);
	}

	/**
	 * Método que manda as informações da tela de Analise de Mudança
	 */
	private function accordionAnaliseMudanca()
	{
		$this->view->arrProjetos = $this->_objProjeto->getProjeto(true);
	}

	/**
	 * Método que manda as informações da tela de Baseline
	 */
	private function accordionBaseline()
	{
		$cd_contrato = ($_SESSION["oasis_logged"][0]["st_dados_todos_contratos"] == "S") ? 0 : $_SESSION["oasis_logged"][0]["cd_contrato"];
		$this->view->arrProjetosBaseline = $this->_objProposta->getProjetosExecucaoSemEncerramentoProposta(true, $cd_contrato);
	}

	/**
	 * Método que manda as informações da tela de Análise de Execução
	 */
	private function accordionAnaliseExecucaoProjeto()
	{
		$this->view->arrContratoAnaliseExecucao = $this->_objContrato->getContratoAtivoObjeto(true, 'P');
	}

	/**
	 * Método que manda as informações da tela de Gerenciamento de Testes
	 */
	private function accordionGerenciamentoTeste()
	{
	    $this->view->permissao_casoDeUso             = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso') === true)? 1:0;
        $this->view->permissao_casoDeUso_analise     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-analise') === true)? 1:0;
        $this->view->permissao_casoDeUso_solucao     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-solucao') === true)? 1:0;
        $this->view->permissao_casoDeUso_homologacao = (ChecaPermissao::possuiPermissao('gerenciamento-teste-caso-uso-homologacao') === true)? 1:0;

        $this->view->permissao_requisito             = (ChecaPermissao::possuiPermissao('gerenciamento-teste-requisito') === true)? 1:0;
        $this->view->permissao_requisito_analise     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-requisito-analise') === true)? 1:0;
        $this->view->permissao_requisito_solucao     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-requisito-solucao') === true)? 1:0;
        $this->view->permissao_requisito_homologacao = (ChecaPermissao::possuiPermissao('gerenciamento-teste-requisito-homologacao') === true)? 1:0;

        $this->view->permissao_regraNegocio             = (ChecaPermissao::possuiPermissao('gerenciamento-teste-regra-negocio') === true)? 1:0;
        $this->view->permissao_regraNegocio_analise     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-regra-negocio-analise') === true)? 1:0;
        $this->view->permissao_regraNegocio_solucao     = (ChecaPermissao::possuiPermissao('gerenciamento-teste-regra-negocio-solucao') === true)? 1:0;
        $this->view->permissao_regraNegocio_homologacao = (ChecaPermissao::possuiPermissao('gerenciamento-teste-regra-negocio-homologacao') === true)? 1:0;
	}
	
	/**
	 * Método que manda as informações da tela de Planejamento
	 */
	private function accordionPlanejamento()
	{
		$cd_contrato = ($_SESSION["oasis_logged"][0]["st_dados_todos_contratos"] == "S") ? 0 : $_SESSION["oasis_logged"][0]["cd_contrato"];
		$this->view->comboProjeto = $this->_objContratoProjeto->listaProjetosContrato($cd_contrato, true, false);
	}

    private function accordionGerenciaQualidade()
	{
		$this->view->arrProfissional              = $this->_objProfissionalObjetoContrato->getProfissionalObjetoContrato($_SESSION['oasis_logged'][0]['cd_objeto'], true, false);
		$this->view->arrProjetosGerenciaQualidade = $this->_objContratoProjeto->listaProjetosContrato( $_SESSION["oasis_logged"][0]["cd_contrato"], true, false);
	}
}