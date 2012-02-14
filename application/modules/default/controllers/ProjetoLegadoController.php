<?php
/**
 * @Copyright Copyright 2011 Hudson Carrano Filho, Brasil.
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

class ProjetoLegadoController extends Base_Controller_Action
{
	private $projetos;
   

    public function init()
	{
		parent::init();
        $this->projetos     = new Projeto($this->_request->getControllerName());
      
	}
	
	public function indexAction()
	{
	}
    
 	public function gridProjetoLegadoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$this->view->res = $this->projetos->getDadosProjeto(null, true);
	}
    
   	public function salvarProjetoLegadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrDados = $this->_request->getPost();
        
        if ($arrDados['cd_gerente_projeto'] == -1) {
            $arrDados['cd_gerente_projeto'] = null;
        }

        if (empty($arrDados["cd_projeto"])) {
            $return = $this->projetos->salvaProjeto($arrDados, $cd_projeto);
            $arrDados['cd_projeto'] = $cd_projeto;
            $return = $this->criarPropostaUmProjetoLegado($arrDados);
//            $msg    = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
            $msg    = ($return) ? Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO') : Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
		}
		else{
            $return = $this->projetos->alterarProjeto($arrDados);
			$msg    = ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO') : Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO');
		}

		echo $msg;
	}

   public function recuperaProjetoLegadoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');

		$res = $this->projetos->GetDadosProjeto($cd_projeto);
        
		echo Zend_Json::encode($res[0]);
	}

public function criarPropostaUmProjetoLegado($ArrDados)
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		$erros	= false;
        
        $cd_projeto  = $ArrDados['cd_projeto'];
        $cd_proposta = 1;
        

        /*
         * Cria a proposta
         */
        if ($erros === false) {
            $erros = $this->criaPropostaLegado($ArrDados, $cd_projeto);
        }
        /*
         * Criar parcela 1 - Parcela do orçamento
         */
        $cd_parcela='';
        if ($erros === false) {
            $erros = $this->criarPrimeiraParcelaLegado($cd_projeto, $cd_proposta, $cd_parcela);
        }

        /*
         Criar registro de processamento da proposta 1 
         * */
        if ($erros === false){
            $erros = $this->criarRegistroProcessamentoPrimeiraPropostaLegado($ArrDados, $cd_projeto, $cd_proposta);
        }

        /*
         Criar registro de processamento da parcela 1 
         * */
        if ($erros === false){
            $erros = $this->criarRegistroProcessamentoPrimeiraParcelaLegado($ArrDados, $cd_projeto, $cd_proposta, $cd_parcela);
        }

		if ($erros === true) {
			$db->rollback();
//			echo Base_Util::getTranslator('L_MSG_ERRO_CRIAR_PROPOSTA');
            
		} else {
			$db->commit();
//			echo Base_Util::getTranslator('L_MSG_SUCESS_CRIAR_PROPOSTA');
		}
        
        return $erros;

	}
    

	public function criarRegistroProcessamentoPrimeiraParcelaLegado($ArrDados, $cd_projeto, $cd_proposta, $cd_parcela)
	{
		$erros = false;
                        
		$objProcessamentoParcela                                      = new ProcessamentoParcela($this->_request->getControllerName());
		$rowProcessamentoParcela                                      = $objProcessamentoParcela->createRow();
		$rowProcessamentoParcela->cd_processamento_parcela            = $objProcessamentoParcela->getNextValueOfField('cd_processamento_parcela');
		$rowProcessamentoParcela->cd_proposta                         = $cd_proposta;
		$rowProcessamentoParcela->cd_projeto                          = $cd_projeto;
		$rowProcessamentoParcela->cd_parcela                          = $cd_parcela;
		$rowProcessamentoParcela->cd_objeto_execucao                  = 0;
		$rowProcessamentoParcela->ni_ano_solicitacao_execucao         = 2006;
		$rowProcessamentoParcela->ni_solicitacao_execucao             = 0;
		$rowProcessamentoParcela->st_autorizacao_parcela              = 'S';
		$rowProcessamentoParcela->dt_autorizacao_parcela              = date('Y-m-d H:i:s');
		$rowProcessamentoParcela->cd_prof_autorizacao_parcela         = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoParcela->st_ativo                            = 'S';
		$rowProcessamentoParcela->cd_prof_fechamento_parcela          = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoParcela->cd_prof_parecer_tecnico_parc        = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoParcela->cd_profissional_aceite_parcela      = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoParcela->cd_prof_homologacao_parcela         = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoParcela->id                                  = $_SESSION["oasis_logged"][0]["cd_profissional"];

		if (!$rowProcessamentoParcela->save())
		{
			$erros = true;
		}

		return $erros;
	}

	public function criarPrimeiraParcelaLegado($cd_projeto, $cd_proposta, &$cd_parcela)
	{
		$erros = false;

		$objParcela = new Parcela($this->_request->getControllerName());
		$rowParcela = $objParcela->createRow();
		$cd_parcela = $objParcela->getNextValueOfField('cd_parcela'); 
        
        $rowParcela->cd_parcela 	     = $cd_parcela;
		$rowParcela->cd_projeto  	     = $cd_projeto;
		$rowParcela->cd_proposta 	     = $cd_proposta;
		$rowParcela->ni_parcela  	     = 1;
		$rowParcela->ni_horas_parcela    = 0.0;
		$rowParcela->st_modulo_proposta  = 'S';
		$rowParcela->ni_mes_previsao_parcela  = 0;
		$rowParcela->ni_ano_previsao_parcela  = 0;
		$rowParcela->ni_mes_execucao_parcela  = 0;
		$rowParcela->ni_ano_execucao_parcela  = 0;
		$rowParcela->id                       = $_SESSION["oasis_logged"][0]["cd_profissional"];

               
		if (!$rowParcela->save()) {
			$erros = true;
		}
		return $erros;
	}


	public function criaPropostaLegado($ArrDados, $cd_projeto)
	{
        $erros = false;
		/*
		 * Cria a proposta
		 */
		$objProposta = new Proposta($this->_request->getControllerName());
		$rowProposta = $objProposta->createRow();
		$rowProposta->cd_proposta                   = 1;
		$rowProposta->cd_projeto                    = $cd_projeto;
		$rowProposta->cd_objeto                     = 0;
		$rowProposta->ni_solicitacao                = 0;
		$rowProposta->ni_ano_solicitacao            = 2006;
		$rowProposta->ni_horas_proposta             = 0.0;
		$rowProposta->st_contrato_anterior          = 'S';
		$rowProposta->cd_prof_encerramento_proposta = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProposta->ni_mes_proposta               = 0;
		$rowProposta->ni_ano_proposta               = 0;
		$rowProposta->id                            = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProposta->nf_indice_avaliacao_proposta  = 0;

        if (!$rowProposta->save()) {
			$erros = true;
		}

		return $erros;
	}
    
public function criarRegistroProcessamentoPrimeiraPropostaLegado($ArrDados, $cd_projeto, $cd_proposta)
	{
		$erros = false;
		$objProcessamentoProposta                                      = new ProcessamentoProposta($this->_request->getControllerName());
		$rowProcessamentoProposta                                      = $objProcessamentoProposta->createRow();
		$rowProcessamentoProposta->cd_processamento_proposta           = $objProcessamentoProposta->getNextValueOfField('cd_processamento_proposta');
		$rowProcessamentoProposta->cd_proposta                         = $cd_proposta;
		$rowProcessamentoProposta->cd_projeto                          = $cd_projeto;
		$rowProcessamentoProposta->cd_prof_fechamento_proposta         = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoProposta->st_ativo                            = 'S';
		$rowProcessamentoProposta->st_fechamento_proposta              = 'S';
		$rowProcessamentoProposta->dt_fechamento_proposta              = date('Y-m-d H:i:s');
		$rowProcessamentoProposta->cd_prof_parecer_tecnico_propos      = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoProposta->cd_prof_aceite_proposta             = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoProposta->cd_prof_homologacao_proposta        = $_SESSION["oasis_logged"][0]["cd_profissional"];
        $rowProcessamentoProposta->cd_prof_alocacao_proposta           = $_SESSION["oasis_logged"][0]["cd_profissional"];
		$rowProcessamentoProposta->id                                  = $_SESSION["oasis_logged"][0]["cd_profissional"];
        
		if (!$rowProcessamentoProposta->save())
		{
			$erros = true;
		}

		return $erros;
	}
    
    
}