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

class DocumentacaoProjetoController extends Base_Controller_Action
{
	private $projetos;
	private $tipoDocumentacao;
	private $documentacaoProjeto;

	public function init()
	{
		parent::init();
		$this->projetos            = new Projeto($this->_request->getControllerName());
		$this->tipoDocumentacao    = new TipoDocumentacao($this->_request->getControllerName());
		$this->documentacaoProjeto = new DocumentacaoProjeto($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->_helper->layout->disableLayout();
		
		$this->view->tipoDocumentacaoCombo = $this->tipoDocumentacao->getTipoDocumentacao("P",true);
		$this->view->cd_projeto 		   = $this->_request->getParam('cd_projeto');
		$this->view->cd_proposta 		   = $this->_request->getParam('cd_proposta');
	}
	
	public function gridDocumentacaoAction()
	{
		$this->_helper->layout->disableLayout();
		
		$cd_projeto = $this->_request->getParam('cd_projeto');

		$arrDocumentacao = $this->documentacaoProjeto->getDadosDocumentacaoProjeto($cd_projeto);
		
		$this->view->res = $arrDocumentacao; 
	}
	
	public function uploadFileAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$arrReturn = array(
            'error'  => false, 
            'msg'    => Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO_DOCUMENTO'),
            'typeMsg'=> 1
        );
	
		$cd_projeto           = $this->_request->getParam('cd_projeto');
		$cd_proposta          = $this->_request->getParam('cd_proposta');
		$cd_tipo_documentacao = $this->_request->getParam('cd_tipo_documentacao');
        $uploadDir            = $this->_request->getParam('upload_dir');
        $fileElementName      = $this->_request->getPost('fileName');
        $fileMaxSize          = $this->_request->getPost('fileSize',K_SIZE_10MB_EM_BYTE);
        
        // Consulta tipos de arquivos permitidos
        $tipos   = $this->tipoDocumentacao->find($cd_tipo_documentacao)->current();
        $tipoDoc = explode(".", $tipos->tx_extensao_documentacao);
        
        try{
            $uploadValid = $this->helperUpload()->validaUpload( $fileElementName, $nomeOriginal, $extensao, $tipoDoc, $fileMaxSize);
            if( $uploadValid ){
                $projeto = $this->projetos->find($cd_projeto)->current();
                $dataAgora = date("YmdHis");
                // Cria o nome do arquivo
                $novoNome = $dataAgora . "_" . $projeto->tx_sigla_projeto . "_" . str_replace(" ", "_", $tipos->tx_tipo_documentacao) . ".$extensao";

                // Cria a estrutura de pastas e salva o arquivo
                $caminho = $_SERVER['DOCUMENT_ROOT']  
                . $this->_helper->BaseUrl->baseUrl()
                . $uploadDir;

                if (isset($_SERVER['WINDIR']))
                    $caminho = str_replace("/", "\\", $caminho);

                chdir($caminho);

                $dirProjeto = DIRECTORY_SEPARATOR . $projeto->tx_sigla_projeto;
                if (!file_exists($caminho . $dirProjeto))
                    mkdir($projeto->tx_sigla_projeto);

                chdir($caminho . $dirProjeto);

                $returnUpload = move_uploaded_file($_FILES[$fileElementName]['tmp_name'],
                                $caminho
                                . $dirProjeto
                                . DIRECTORY_SEPARATOR
                                . $novoNome);
                if (!$returnUpload){
                    throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INESPERADO'));
                } else {
                    // Grava as informações do upload na tabela a_documentacao_projeto
                    chdir($_SERVER['DOCUMENT_ROOT'] . $this->_helper->BaseUrl->baseUrl());
                    $arrDados['cd_projeto']                  = $cd_projeto;
                    $arrDados['cd_tipo_documentacao']        = $cd_tipo_documentacao;
                    $arrDados['tx_arq_documentacao_projeto'] = $novoNome;
                    $arrDados['tx_nome_arquivo']             = $nomeOriginal;
                    $arrDados['st_documentacao_controle']    = null;
                    $arrDados['dt_documentacao_projeto']     = date("Y-m-d H:i:s");

                    if(!$this->documentacaoProjeto->insert($arrDados))
                        throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_DADOS_DOCUMENTO'));
                }
            }
        }catch(Base_Exception_Alert $e){
            $arrReturn['error'  ] = true;
            $arrReturn['msg'    ] = $e->getMessage();
            $arrReturn['typeMsg'] = 2;
        }catch(Base_Exception_Error $e){
            $arrReturn['error'  ] = true;
            $arrReturn['msg'    ] = $e->getMessage();
            $arrReturn['typeMsg'] = 3;
        }catch(Exception $e){
            $arrReturn['error'  ] = true;
            $arrReturn['msg'    ] = $e->getMessage();
            $arrReturn['typeMsg'] = 3;
        }
		echo Zend_Json::encode($arrReturn);
	}
	
	public function downloadAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
        $data     = $this->_request->getParam('data');
        $projeto  = $this->_request->getParam('projeto');
        $tipo     = $this->_request->getParam('tipo');
	
        $arrDados   = $this->documentacaoProjeto->getDocumentoProposta($data, $projeto, $tipo);
		$objProjeto = $this->projetos->find($projeto)->current();
		
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
            die(Base_Util::getTranslator('L_MSG_ERRO_ARQUIVO_INEXISTENTE'));
        }

        header ("Content-type: octet/stream ");
        header ("Content-disposition: attachment; filename={$arrDados[0]['tx_nome_arquivo']}.{$exetensao};");
        header ("Content-Length: ".filesize($arquivo));
        readfile($arquivo);
        exit();
	}
	
	public function extensoesPermitidasAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_tipo_documentacao = $this->_request->getParam('cd_tipo_documentacao');
        $arrDados             = $this->tipoDocumentacao->getExtensoesDocumentacao($cd_tipo_documentacao);

		$arrExtensoes = explode(".",$arrDados[0]['tx_extensao_documentacao']);
		$extensoes = "";
		foreach($arrExtensoes as $conteudo){
			$extensoes .= "*.{$conteudo}, ";
		}
		$extensoes = substr($extensoes,0,-2);

		echo $extensoes; 
	}
}