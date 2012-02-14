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

class MenuContratoController extends Base_Controller_Action
{
	private $objObjetoContrato;
	private $objContrato;
	private $arrContratoProjeto;
	private $objEmpresa;
	private $objPerfil;
    private $_objAreaAtuacaoTi;
	
	public function init()
	{
		parent::init();
		$this->objContrato	       = new Contrato($this->_request->getControllerName());
		$this->arrContratoProjeto  = $this->objContrato->getContratoPorTipoDeObjeto(true, 'P');
		$this->objEmpresa          = new Empresa($this->_request->getControllerName());
		$this->objObjetoContrato   = new ObjetoContrato($this->_request->getControllerName());
		$this->objPerfil		   = new Perfil($this->_request->getControllerName());
        $this->_objAreaAtuacaoTi   = new AreaAtuacaoTi($this->_request->getControllerName());
		$this->objTipoDocumentacao = new TipoDocumentacao($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_CONTRATO'));
		
		if((ChecaPermissao::possuiPermissao('empresa') === true) || (ChecaPermissao::possuiPermissao('contato-empresa') === true)){
			$this->accordionEmpresa();
		}

		if(ChecaPermissao::possuiPermissao('contrato-definicao-metrica') === true){
			$this->accordionAssociarMetricaContrato();
		}

		if(ChecaPermissao::possuiPermissao('associar-projeto-contrato') === true){
			$this->accordionAssociarProjetoContrato();
		}

		if((ChecaPermissao::possuiPermissao('perfil-profissional-papel-profissional') === true) ||
		   (ChecaPermissao::possuiPermissao('perfil-profissional') === true) ||
		   (ChecaPermissao::possuiPermissao('papel-profissional') === true) ){
		 	$this->accordionPerfilPapelProfissional();
		}

		if((ChecaPermissao::possuiPermissao('painel-contrato') === true) ||
		   (ChecaPermissao::possuiPermissao('contrato') === true)){
		 	$this->accordionContrato();
		}

		if(ChecaPermissao::possuiPermissao('perfil-menu') === true){
		 	$this->accordionPerfilSistemaObjetoContrato();
		}

		if(ChecaPermissao::possuiPermissao('etapa') === true || 
           ChecaPermissao::possuiPermissao('atividade') === true ||
           ChecaPermissao::possuiPermissao('associar-atividade-objeto-contrato') === true){
		 	$this->accordionEtapaAtividade();
		}

		if(ChecaPermissao::possuiPermissao('associar-perfil-papel-profissional-objeto-contrato') === true){
		 	$this->accordionAssociarPerfilPapelProfissionalObjetoContrato();
		}

		if(ChecaPermissao::possuiPermissao('documentacao-contrato') === true){
		 	$this->accordionDocumentacaoContrato();
		}

		if(ChecaPermissao::possuiPermissao('rotina') === true ||
           ChecaPermissao::possuiPermissao('associar-rotina-objeto-contrato') === true){
		 	$this->accordionRotina();
		}
	}

    public function comboAreaAtuacaoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        //o segundo parametro diz que a chamada é ajax e retorna a string de options pronta
        echo $this->_objAreaAtuacaoTi->comboAreaAtuacaoTi(true, true);
    }

	private function accordionEmpresa()
	{
		if(ChecaPermissao::possuiPermissao('empresa') === true){
			$formEmpresa = new EmpresaForm();
			$formEmpresa->salvar->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
			$formEmpresa->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
			$formEmpresa->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
			$this->view->formEmpresa = $formEmpresa;
		}
		if(ChecaPermissao::possuiPermissao('contato-empresa') === true){
			$formContatoEmpresa = new ContatoEmpresaForm();
			$formContatoEmpresa->salvar->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
			$formContatoEmpresa->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
			$formContatoEmpresa->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
			$this->view->formContatoEmpresa = $formContatoEmpresa;
		}
	}

	private function accordionContrato()
	{
		if(ChecaPermissao::possuiPermissao('painel-contrato') === true){
                    $arrSituacao[0]   = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
                    $arrSituacao['A'] = Base_Util::getTranslator('L_VIEW_COMBO_ATIVO');
                    $arrSituacao['I'] = Base_Util::getTranslator('L_VIEW_COMBO_INATIVO');

                    $this->view->comboSituacao = $arrSituacao;
                    $arrEmpresa                = $this->objEmpresa->getEmpresa(true);
                    $this->view->comboEmpresa  = $arrEmpresa;
		}
		if(ChecaPermissao::possuiPermissao('contrato') === true){
                    $form = new ContratoForm();
                    $this->view->formContrato = $form;
		}
		if(ChecaPermissao::possuiPermissao('objeto-contrato') === true){
                    $form = new ObjetoContratoForm();
                    $this->view->formObjetoContrato = $form;
		}
		if(ChecaPermissao::possuiPermissao('penalidade') === true){
                    $form = new PenalidadeForm();
                    $this->view->formPenalidade = $form;
		}
		if(ChecaPermissao::possuiPermissao('projeto-previsto') === true){
                    $form = new ProjetoPrevistoForm();
                    $this->view->formProjetoPrevisto = $form;
		}
		if(ChecaPermissao::possuiPermissao('nivel-servico') === true){
                    $form = new NivelServicoForm();
                    $this->view->formNivelServico = $form;
		}
	}

	private function accordionAssociarMetricaContrato()
	{
		$this->view->contratoCombo = $this->arrContratoProjeto;
	}

	private function accordionAssociarProjetoContrato()
	{
		$this->view->comboContratoAssociaProjetoContrato = $this->arrContratoProjeto;
	}

	private function accordionPerfilPapelProfissional()
	{
		if(ChecaPermissao::possuiPermissao('perfil-profissional') === true){
			$formPerfilProfissional = new PerfilProfissionalForm();
			$formPerfilProfissional->salvar->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
			$formPerfilProfissional->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
			$formPerfilProfissional->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
			$this->view->formPerfilProfissional = $formPerfilProfissional;
		}
		if(ChecaPermissao::possuiPermissao('papel-profissional') === true){
			$this->view->arrContratoPapelProfissional = $this->arrContratoProjeto;
		}
		if(ChecaPermissao::possuiPermissao('perfil-profissional-papel-profissional') === true){
			$this->view->arrObjetoContratoAtivoPapelPerfilProfissional = $this->objObjetoContrato->getObjetoContratoAtivo( null, true );
		}
	}

	private function accordionPerfilSistemaObjetoContrato()
	{
		$this->view->arrPerfisPerfilSistemaObjetoContrato  = $this->objPerfil->getPerfil ( true, true );
		$this->view->arrObjetosPerfilSistemaObjetoContrato = $this->objObjetoContrato->getObjetoContratoAtivo(null, true, true);
	}

	private function accordionEtapaAtividade()
	{
        if(ChecaPermissao::possuiPermissao('etapa') === true){
            $form                      = new EtapaForm();
            $this->view->formEtapa     = $form;
        }

        if(ChecaPermissao::possuiPermissao('atividade') === true){
            $form                      = new AtividadeForm();
            $this->view->formAtividade = $form;
        }

        if(ChecaPermissao::possuiPermissao('associar-atividade-objeto-contrato') === true){
            $this->view->arrAreaAtuacaoTiAssociacao  = $this->_objAreaAtuacaoTi->comboAreaAtuacaoTi(true);
            $this->view->arrObjetoContratoAssociacao2 = $this->objObjetoContrato->getObjetoContratoAtivo(null, true, false, true);
        }
	}

	private function accordionAssociarPerfilPapelProfissionalObjetoContrato()
	{
        //aba Perfil Objeto
        $this->view->arrTodosObjetoContratoAtivo = $this->objObjetoContrato->getObjetoContratoAtivo(null, true, false, true);
        //aba Papel Objeto
        $this->view->arrObjetoContratoProjeto    = $this->objObjetoContrato->getObjetoContratoAtivo('P', true, false, true);

        $this->view->arrAreaAtuacaoTi            = $this->_objAreaAtuacaoTi->comboAreaAtuacaoTi(true);
	}

	private function accordionDocumentacaoContrato()
	{
		$this->view->arrContratoCombo      = $this->objContrato->getTodosContratos(true, true);
		$this->view->tipoDocumentacaoCombo = $this->objTipoDocumentacao->getTipoDocumentacao("O", true);
	}

    private function accordionRotina()
	{
        if(ChecaPermissao::possuiPermissao('rotina') === true){
            $form                      = new RotinaForm();
            $this->view->formRotina    = $form;
        }

        if(ChecaPermissao::possuiPermissao('associar-rotina-objeto-contrato') === true){
            $this->view->arrAreaAtuacaoTiAssociacao  = $this->_objAreaAtuacaoTi->comboAreaAtuacaoTi(true);
            $this->view->arrObjetoContratoAssociacao = $this->objObjetoContrato->getObjetoContratoAtivo('D', true, false, true);
        }
	}
}