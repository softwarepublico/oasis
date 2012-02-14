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

class ControleController extends Base_Controller_Action
{

	private $zendDate;
	private $objContrato;
	private $objContratos;
	private $objUtil;
	private $objSolicitacao;
	private $objTipoDocumentacao;
	private $objProjeto;


	public function init()
	{
		parent::init();
        $this->view->headTitle(Base_Util::setTitle('L_TIT_CONTROLE'));

		$this->zendDate				= new Zend_Date();
		$this->objContrato			= new Contrato($this->_request->getControllerName());
		$this->objContratos			= new ObjetoContrato($this->_request->getControllerName());
		$this->objUtil				= new Base_Controller_Action_Helper_Util();
		$this->objSolicitacao		= new Solicitacao($this->_request->getControllerName());
		$this->objTipoDocumentacao	= new TipoDocumentacao($this->_request->getControllerName());
		$this->objProjeto			= new Projeto($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$mesAtual = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		$anoAtual = date("Y");

		$this->view->mesAno = "{$this->objUtil->getMes($mesAtual)}/{$anoAtual}";

		//*** ITEM PARECER TECNICO PROPOSTA
		$itemParecerTecnico       = new ItemParecerTecnico($this->_request->getControllerName());
		$selectItemParecerTecnico = $itemParecerTecnico->select()
													   ->where('st_proposta IS NOT NULL');

		$this->view->listaItemParecerTecnico = $itemParecerTecnico->fetchAll($selectItemParecerTecnico);
		//*** FIM ITEM PARECER TECNICO PROPOSTA

		//*** ITEM PARECER TECNICO PARCELA
		$itemParecerTecnicoParcela       = new ItemParecerTecnico($this->_request->getControllerName());
		$selectItemParecerTecnicoParcela = $itemParecerTecnicoParcela->select()
																	 ->where('st_parcela IS NOT NULL');

		$this->view->listaItemParecerTecnicoParcela = $itemParecerTecnicoParcela->fetchAll($selectItemParecerTecnicoParcela);
		//*** FIM ITEM PARECER TECNICO PARCELA

		//lista de Projetos para a tab Alteração de Propostas
		$this->view->listaProjetos = $this->objProjeto->getProjeto(true);
		//fim lista de Projetos para a tab Alteração de Propostas
		
		//EXTRATO MENSAL
		$this->view->listaContrato = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', null, true);

		//*** Inicio do acorddeon Penalização
		$this->acorddeonPenalizacao();

		//*** Inicio do acorddeon Documentação do Projeto para o controle
        $this->acorddeonDocumentacaoProjetoControle();
        
        //aba de Alocação de Recurso de Contrato Anterior do Acordeon Propostas
        $this->abaAlocacaoRecursoContratoAnterior();
        
        //aba de Desalocação de Recurso de Contrato do Acordeon Propostas
        $this->abaDesalocacaoRecurso();

		if(ChecaPermissao::possuiPermissao('disponibilidade-servico') === true){
		 	$this->accordionDisponibilidadeServico();
		}

        if(ChecaPermissao::possuiPermissao('custo-contrato-demanda') === true){
		 	$this->accordionCustoContratoDemanda();
		}

        if(ChecaPermissao::possuiPermissao('suspensao-proposta') === true){
		 	$this->accordionSuspensaoProposta();
		}

        if(ChecaPermissao::possuiPermissao('ocorrencia-administrativa') === true){
		 	$this->accordionOcorrenciaAdministrativa();
		}
	}

	private function accordionDisponibilidadeServico()
	{
		$this->view->arrObjetoContratoDemandaServico = $this->objContratos->getObjetoContratoAtivo(array("D","S"), true, false, true);
		$this->view->arrTipoDocumentoAnaliseDispServico = $this->objTipoDocumentacao->getTipoDocumentacao("D", true);
	}
	
    private function acorddeonDocumentacaoProjetoControle()
    {
        $objTipoDocumentacao = new TipoDocumentacao($this->_request->getControllerName());

        $this->view->arrComboTipoDocumentacao = $objTipoDocumentacao->getTipoDocumentacao("C",true);
    }

	private function acorddeonPenalizacao()
	{
		$this->view->contratos     = $this->objContratos->getObjetoContratoAtivo( null, true );
	}
	
	public function pesquisaPropostasAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$proposta = new Proposta($this->_request->getControllerName());

		if (!is_null($this->_request->getParam('mes'))) {
			$mesAtual = $this->_request->getParam('mes');
		} else {
			$mesAtual = $this->zendDate->get(Zend_Date::MONTH_SHORT);
		}

		if (!is_null($this->_request->getParam('ano'))) {
			$anoAtual = $this->_request->getParam('ano');

		} else {
			$anoAtual = date("Y");
		}

		$mesAtual = substr("00".$mesAtual,-2);
		//		$this->view->res = $proposta->getPropostasControle($mesAtual, $anoAtual);
		$this->view->res = $proposta->getPropostasControle();

		echo $this->view->render('controle-proposta/pesquisa-propostas.phtml');
	}
	
	public function abaAlocacaoRecursoContratoAnterior()
	{
		$this->view->arrContratoAtivo = $this->objContrato->getContratoAtivoObjeto(true, 'P');
	}
	
	public function abaDesalocacaoRecurso()
	{
		$this->view->arrContratoAtivo = $this->objContrato->getContratoAtivoObjeto(true, 'P');
	}

    private function accordionCustoContratoDemanda()
	{
		$arrContratoDemanda = $this->objContrato->getContratoAtivoObjeto(true, 'D');
        $arrContratoServico = $this->objContrato->getContratoAtivoObjeto(false, 'S');

        foreach($arrContratoDemanda as $key => $value){
            $arrContratosTipoDemandaServico[$key] = $value;
        }

        foreach($arrContratoServico as $key => $value){
            $arrContratosTipoDemandaServico[$key] = $value;
        }

        $this->view->arrContratosTipoDemandaServico = $arrContratosTipoDemandaServico;
	}

    private function accordionSuspensaoProposta()
	{
		$this->view->arrContratoProjeto = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P', null, false, 'A');
	}

    private function accordionOcorrenciaAdministrativa()
	{
		$this->view->dt_ocorrencia_administrativa = date('d/m/Y H:i:s');
	}
}