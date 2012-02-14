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

class FiscalizacaoPropostaController extends Base_Controller_Action 
{
	private $objContrato;
	private $objAcompanhamentoProposta;
	private $objDocumentacaoProjeto;
	private $objProjetos;
	
	public function init()
	{
		parent::init();
		$this->objContrato               = new ObjetoContrato($this->_request->getControllerName());
		$this->objAcompanhamentoProposta = new AcompanhamentoProposta($this->_request->getControllerName());
		$this->objDocumentacaoProjeto    = new DocumentacaoProjeto($this->_request->getControllerName());
		$this->objProjetos               = new Projeto($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
       $this->view->headTitle(Base_Util::setTitle('L_TIT_FISCALIZACAO_PROPOSTA'));

		$params = $this->_request->getParams();

		if( array_key_exists("errorDonwload", $params) ){
			$this->view->errorDonwload = 1;
		}else{
			$this->view->errorDonwload = 0;
		}

        $objContrato 		= new Contrato($this->_request->getControllerName());
		$cd_contrato 		= null;
		$comStatus			= true;

		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}

		$this->view->arrContrato       = $objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
		$this->view->arrObjetoContrato = $this->objContrato->getObjetoContratoAtivo('P',true,false);
	}
	
	public function gridFiscalizacaoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$ni_mes_execucao_parcela = $this->_request->getParam('ni_mes_execucao_parcela');
		$ni_ano_execucao_parcela = $this->_request->getParam('ni_ano_execucao_parcela');
		$cd_contrato             = $this->_request->getParam('cd_contrato');

		$this->view->mes_execucao = $ni_mes_execucao_parcela; 
		$this->view->ano_execucao = $ni_ano_execucao_parcela; 
		$this->view->cd_contrato  = $cd_contrato;
		
		$arrProjetos = $this->objAcompanhamentoProposta->getProjetoProposta($ni_mes_execucao_parcela, $ni_ano_execucao_parcela, $cd_contrato);

		if(count($arrProjetos) > 0){
			$arrDocumentacaoProjeto = array();
			$i = 0;
			foreach($arrProjetos as $key=>$value){
				//Recupera a ultima documentação de Modelo de Dados cd_tipo_documentacao = 1
				$arrDocumentacaoProjeto = $this->objDocumentacaoProjeto->getDadosUltimaDocumentacaoProjeto($value['cd_projeto'],1);
				if(count($arrDocumentacaoProjeto) > 0){
					$arrProjetos[$i]['dt_documentacao_modelo_de_dados']      = $arrDocumentacaoProjeto[0]['dt_documentacao_projeto'];
					$arrProjetos[$i]['cd_tipo_documentacao_modelo_de_dados'] = $arrDocumentacaoProjeto[0]['cd_tipo_documentacao'];
					
					
				}
				//Recupera a ultima documentação do WireFrame cd_tipo_documentacao = 2
				$arrDocumentacaoProjeto = $this->objDocumentacaoProjeto->getDadosUltimaDocumentacaoProjeto($value['cd_projeto'],2);
				if(count($arrDocumentacaoProjeto) > 0){
					$arrProjetos[$i]['dt_documentacao_wireframe']      = $arrDocumentacaoProjeto[0]['dt_documentacao_projeto'];
					$arrProjetos[$i]['cd_tipo_documentacao_wireframe'] = $arrDocumentacaoProjeto[0]['cd_tipo_documentacao'];
				}
				$i++;
			}
		}

        $this->view->perfil = $_SESSION['oasis_logged'][0]['cd_perfil'];
		$this->view->arrProjeto = $arrProjetos; 
	}
	
	public function downloadAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
        $data     = $this->_request->getParam('data');
        $projeto  = $this->_request->getParam('projeto');
        $tipo     = $this->_request->getParam('tipo');
	
        $arrDados   = $this->objDocumentacaoProjeto->getDocumentoProposta($data, $projeto, $tipo);
		$objProjeto = $this->objProjetos->find($projeto)->current();
	
		$arrExetensao = explode(".",$arrDados[0]['tx_arq_documentacao_projeto']);
		$exetensao    = $arrExetensao[1]; 
		unset($arrExetensao);
		
        $arquivo = SYSTEM_PATH_ABSOLUTE.
        		   DIRECTORY_SEPARATOR."public"
        		   .DIRECTORY_SEPARATOR."documentacao"
        		   .DIRECTORY_SEPARATOR."projeto"
        		   .DIRECTORY_SEPARATOR.$objProjeto->tx_sigla_projeto
				   .DIRECTORY_SEPARATOR.$arrDados[0]['tx_arq_documentacao_projeto'];
        if (!file_exists($arquivo)){
			$url = $_SERVER['HTTP_REFERER']."/index/errorDonwload/1";
			$this->_redirect($url);
        }

        if($arrDados[0]['tx_nome_arquivo'] != ""){
        	$tx_nome_arquivo = $arrDados[0]['tx_nome_arquivo'];
        } else {
        	$arrNomeDocumento = explode(".",$arrDados[0]['tx_arq_documentacao_projeto']);
        	$tx_nome_arquivo = $arrNomeDocumento[0];
        }
        
        header ("Content-type: octet/stream ");
        header ("Content-disposition: attachment; filename={$tx_nome_arquivo}.{$exetensao};");
        header ("Content-Length: ".filesize($arquivo));
        readfile($arquivo);
        exit();
	}
	
	public function salvarAcompanhamentoPropostaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();

		if($arrDados['tx_acompanhamento_proposta'] != ""){	
			if(!array_key_exists('st_restrito',$arrDados)){
				$arrDados['st_restrito']="";
			}
			
			$return = $this->objAcompanhamentoProposta->salvarAcompanhamentoProposta($arrDados);
			echo ($return) ? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO') : Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_REGISTRO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ALERT_PREENCHIMENTO_CAMPO_ACOMPANHAMENTO_PROPOSTA_OBRIGATORIO');
		}
	}
	
	public function relatorioFiscalizacaoProdutoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto  = $this->_request->getParam("cd_projeto");
		$cd_proposta = $this->_request->getParam("cd_proposta");
		$cd_perfil   = $this->_request->getParam("cd_perfil");
		
		$arrDados = $this->objAcompanhamentoProposta->getDadosAcompanhamentoProduto($cd_projeto, $cd_proposta, $cd_perfil);
		$this->view->arrDados = $arrDados; 
	}

	public function modalAcompanhamentoPropostaAction()
	{
		$this->_helper->layout->disableLayout();

		$params = $this->_request->getPost();

		$this->view->cd_projeto  = $params['cd_projeto'];
		$this->view->cd_proposta = $params['cd_proposta'];
	}

}