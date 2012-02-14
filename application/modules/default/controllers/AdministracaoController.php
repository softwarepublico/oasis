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

class AdministracaoController extends Base_Controller_Action
{

	private $objetoContrato;
	private $perfil;
	private $funcionalidade;
	
	public function init()
	{
		parent::init();

		$this->objetoContrato	   = new ObjetoContrato($this->_request->getControllerName());
		$this->perfil			   = new Perfil($this->_request->getControllerName());
		$this->funcionalidade      = new Funcionalidade($this->_request->getControllerName());
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ADMINISTRACAO'));
		$this->initView();

		if(ChecaPermissao::possuiPermissao('metrica') === true){
			$this->accordionMetrica();
		}

//		if(ChecaPermissao::possuiPermissao('metrica') === true){
			$this->accordeonPerfil();
//		}
//
//		if(ChecaPermissao::possuiPermissao('associar-profissional-projeto') === true){
			$this->accordeonBox();
//		}
//
//		if(ChecaPermissao::possuiPermissao('associar-profissional-projeto') === true){
			$this->accordeonPefilBoxInicio();
//		}
		if(ChecaPermissao::possuiPermissao('log') === true){
			$this->accordionLog();
		}
		
		//condição que checa a permissão para acessar o accordion mensageria
		if (ChecaPermissao::possuiPermissao('mensageria') === true){
			//metodo passa todas as informações necessarias para a tela de mensageria
			$this->accordionMensageria();
		}

		//condição que checa a permissão para acessar o accordion Funcionalidade
		if (ChecaPermissao::possuiPermissao('funcionalidade') === true){
			//metodo passa todas as informações necessarias para a tela de Funcionalidade
			$this->accordeonFuncionalidade();
		}
		
		//condição que checa a permissão para acessar o accordion Definição de Processo
		if (ChecaPermissao::possuiPermissao('definicao-processo') === true){
			//metodo passa todas as informações necessarias para a tela de Definição de Processo
			$this->accordeonDefinicaoProcesso();
		}
        
        //condição que checa a permissão para acessar o accordion Projeto legado
		if (ChecaPermissao::possuiPermissao('projeto-legado') === true){
            //metodo passa todas as informações necessarias para a tela de Projeto Legado
            $this->accordionProjetoLegado();
        }
        
        $arrTipoFuncionalidade     = array();
		$arrTipoFuncionalidade[0]  = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrTipoFuncionalidade["P"]= Base_Util::getTranslator('L_VIEW_COMBO_PRINCIPAL');
		$arrTipoFuncionalidade["I"]= Base_Util::getTranslator('L_VIEW_COMBO_ITEM');

        $this->view->arrTipoFuncionalidade  = $arrTipoFuncionalidade;
        
        
	}

	private function accordeonPefilBoxInicio()
	{
		$perfil					 = new Perfil($this->_request->getControllerName());
		$this->view->perfilCombo = $perfil->getPerfil ( true, true );

		$objObjetoContrato		 = new ObjetoContrato($this->_request->getControllerName());
		$this->view->objetoCombo = $objObjetoContrato->getObjetoContratoAtivo(null, true, true);
	}

	private function accordeonBox()
	{
		$objBoxInicio = new BoxInicio($this->_request->getControllerName());
		$this->view->comboTipoBox = $objBoxInicio->getArrTipoBox();
	}

	private function accordeonFuncionalidade()
	{
		$this->view->comboTipoObjeto   = $this->objetoContrato->getTipoObjeto();
		$this->view->arrFuncionalidade = $this->funcionalidade->getFuncionalidade(true, true,true);
	}
	
	private function accordeonDefinicaoProcesso()
	{
		$this->view->comboTipoProcesso          = $this->objetoContrato->getTipoObjeto();
		$this->view->arrPerfilDefinicaoProcesso = $this->perfil->getPerfil(true, true);
	}
	
	private function accordeonPerfil()
	{
		$objMenu = new Menu($this->_request->getControllerName());
		
		$this->view->data = $objMenu->fetchAll(null, 'tx_pagina');
		
		$form = new MenuForm();
		$form->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$this->view->formMenu = $form;
		
		// Obtem os perfis
		$perfil = new Perfil ( );
		$this->view->perfis = $perfil->getPerfil ( true, true );

		$formPerfil = new PerfilForm();
		$formPerfil->submit->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$formPerfil->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
		$formPerfil->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
		$this->view->formPefil = $formPerfil;
	}

	/**
	 * Envia as informações para o accordion de Métrica
	 */
	private function accordionMetrica()
	{
		$formDefinicao 				= new DefinicaoMetricaForm();
		$formItem 					= new ItemMetricaForm();
		$formSubItemMetrica 		= new SubItemMetricaForm();
		$formCondicaoSubItemMetrica = new CondicaoSubItemMetricaForm($this->_request->getControllerName());
		
		
		//aba de definicao
		$formDefinicao->salvar->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$formDefinicao->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
		$formDefinicao->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
		$this->view->formMetrica = $formDefinicao;
		
		//abra de item
        $formItem->salvar->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$formItem->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
		$formItem->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
		$this->view->formItemMetrica = $formItem;

		
		//aba de sub-item
        $formSubItemMetrica->salvar->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$formSubItemMetrica->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
		$formSubItemMetrica->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
		$this->view->formSubItemMetrica = $formSubItemMetrica;
		
		//aba de condições
        $formCondicaoSubItemMetrica->salvar->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$formCondicaoSubItemMetrica->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
		$formCondicaoSubItemMetrica->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
		$this->view->formCondicaoSubItemMetrica = $formCondicaoSubItemMetrica;

		//aba Tipo de Produto
		$objDefinicaoMetrica       = new DefinicaoMetrica($this->_request->getControllerName());
        $arrComboMetrica           = $objDefinicaoMetrica->getDefinicaoMetrica(true);
		$this->view->comboMetrica  = $arrComboMetrica;

	}
	
	/**
	 * Envia as informações para o accordion de Log
	 */
	private function accordionLog()
	{
		$objProfissional    = new Profissional($this->_request->getControllerName());
		
		$arrProfissional    = $objProfissional->getProfissional(true);
		$arrProfissional[0] = Base_Util::getTranslator('L_VIEW_COMBO_TODOS');
        
		$this->view->arrProfissionalLog = $arrProfissional; 
		
		//recupera as tabelas do sistema
		$db 		= Zend_Registry::get('db');
		$arrTabelas = $db->listTables(K_SCHEMA);
		
		$arr[0] = Base_Util::getTranslator('L_VIEW_COMBO_TODAS');
		if( count($arrTabelas) > 0 ){
			foreach($arrTabelas as $tabela){
				$arr[$tabela] = $this->toUpper($tabela);
			}
		}
		$this->view->arrTabelasEsquema = $arr;
	}
	
	/**
	 * Método que manda as informações da tela de mensageria
	 */
	private function accordionMensageria()
	{
		//envia array de objetos ativos usado no accordion mensageria
		$this->view->arrTipoObjetoContrato	= $this->objetoContrato->getObjetoContratoAtivo(null,true,false);
		//envia array de perfil usado no accordion mensageria
		$this->view->arrTipoPerfil = $this->perfil->getPerfil(true,true);

		$this->view->objDataDiff = new Base_View_Helper_Datediff();
	}

    private function accordionProjetoLegado()
	{
        $formProjetoLegado = new ProjetoLegadoForm();
        $formProjetoLegado->salvar->setLabel(Base_Util::getTranslator('L_BTN_SALVAR'));
		$formProjetoLegado->cancelar->setLabel(Base_Util::getTranslator('L_BTN_CANCELAR'));
		$formProjetoLegado->alterar->setLabel(Base_Util::getTranslator('L_BTN_ALTERAR'));
		$this->view->formProjetoLegado = $formProjetoLegado;

   		$objProjeto    		  = new Projeto($this->_request->getControllerName());
        $projetos =$objProjeto->getDadosProjeto(null, true);
		$this->view->projetos = $projetos;
        

     
        
	}

    public function gridExecutaAction()
    {
		$this->_helper->layout->disableLayout();

        $txSelect = $this->_request->getPost('tx_select');
        $db       = Zend_Registry::get('db');

        $arrProibido = array(
            'ABORT',        'ALTER',        'COMMIT',       'DELETE',       'FETCH',
            'AGGREGATE',    'COPY',         'DISCARD',      'GRANT',        'CREATE',
            'DROP',         'INSERT',       'LISTEN',       'LOAD',         'LOCK',
            'MOVE',         'NOTIFY',       'PREPARE',      'PREPARE',      'REASSIGN',
            'REINDEX',      'RELEASE',      'RESET',        'REVOKE',       'ROLLBACK',
            'SAVEPOINT',    'EXPLAIN',      'SHOW',         'START',        'ANALYZE',
            'TABLE',        'BEGIN',        'TRUNCATE',     'CHECKPOINT',   'UNLISTEN',
            'CLOSE',        'UPDATE',       'CLUSTER',      'VACUUM',       'COMMENT',
            'DEALLOCATE',   'EXECUTE',      'VALUES',       'COMMIT',       'DECLARE',
            'SET'
        );

        foreach($arrProibido as $item){
            if(false !== stripos(strtoupper($txSelect), $item)){
                throw new Exception('A palavra '.$item.' não pode ser usada no comando');
            }
        }

        $this->view->result = $db->fetchAll($txSelect);
    }

}