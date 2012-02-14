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

class DocumentacaoProjetoContinuoController extends Base_Controller_Action
{
	private $projetoContinuo;
	private $documentacaoProjetoContinuo;
    private $objTipoDocumentacao;
    private $_pathDocProjetoContinuo;

	public function init()
	{
		parent::init();
        $this->_pathDocProjetoContinuo = DIRECTORY_SEPARATOR . "public"
                                        .DIRECTORY_SEPARATOR . "documentacao"
                                        .DIRECTORY_SEPARATOR . "projeto-continuo";
		$this->projetoContinuo		       = new ProjetoContinuado($this->_request->getControllerName());
		$this->documentacaoProjetoContinuo = new DocumentacaoProjetoContinuo($this->_request->getControllerName());
        $this->objTipoDocumentacao         = new TipoDocumentacao($this->_request->getControllerName());
	}

	public function indexAction(){}
	
	public function extensoesPermitidasAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $cd_tipo_documentacao = $this->_request->getPost('cd_tipo_documentacao');
        $arrDados             = $this->objTipoDocumentacao->getExtensoesDocumentacao($cd_tipo_documentacao);
 
		$extensoes = array();
		foreach(explode(".",$arrDados[0]['tx_extensao_documentacao']) as $conteudo)
			$extensoes[] = "*.{$conteudo}";
		echo implode(',', $extensoes); 
	}
	
	public function gridDocumentacaoProjetoContinuoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->view->res = $this->documentacaoProjetoContinuo
                                ->getDadosDocumentacaoProjetoContinuo($this->_request->getPost());
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
        
		$cd_objeto            = $this->_request->getParam('cd_objeto_projeto_continuo_doc');
		$cd_projeto_continuo  = $this->_request->getParam('cd_projeto_continuo_doc');
		$cd_tipo_documentacao = $this->_request->getParam('cd_tipo_documentacao');
        $fileElementName      = $this->_request->getPost('fileName');
        $fileMaxSize          = $this->_request->getPost('fileSize',K_SIZE_200MB_EM_BYTE);
        
        // Consulta tipos de arquivos permitidos
        $tipos   = $this->objTipoDocumentacao->find($cd_tipo_documentacao)->current();
        $tipoDoc = explode(".", $tipos->tx_extensao_documentacao);
        
        try{
            $uploadValid = $this->helperUpload()->validaUpload( $fileElementName, $nomeOriginal, $extensao, $tipoDoc, $fileMaxSize);
            if( $uploadValid ){
                $projetoContinuo     = $this->projetoContinuo->find($cd_projeto_continuo, $cd_objeto)->current();
                $tx_projeto_continuo = str_replace("/", "-", $projetoContinuo->tx_projeto_continuado);
                $dataAgora           = date("YmdHis");

                // Cria o nome do arquivo
                $novoNome = $dataAgora . "_" . $tx_projeto_continuo . "_" . str_replace(" ", "_", $tipos->tx_tipo_documentacao) . ".$extensao";

                // Cria a estrutura de pastas e salva o arquivo
                $caminho = $_SERVER['DOCUMENT_ROOT'] 
                           . $this->_helper->BaseUrl->baseUrl() 
                           . $this->_pathDocProjetoContinuo;

                if (isset($_SERVER['WINDIR']))
                    $caminho = str_replace("/", "\\", $caminho);

                $projetoDir = DIRECTORY_SEPARATOR . $tx_projeto_continuo;

                chdir($caminho);
                if (!file_exists($caminho . $projetoDir))
                    mkdir($tx_projeto_continuo);

                chdir($caminho . $projetoDir);

                $returnUpload = move_uploaded_file($_FILES[$fileElementName]['tmp_name'],
                                $caminho
                                . $projetoDir
                                . DIRECTORY_SEPARATOR
                                . $novoNome); 

                if (!$returnUpload){
                    throw new Base_Exception_Error( Base_Util::getTranslator('L_MSG_ERRO_INESPERADO') );
                } else {
                    // Grava as informações do upload na tabela a_documentacao_projeto
                    chdir($_SERVER['DOCUMENT_ROOT']  . $this->_helper->BaseUrl->baseUrl());

                    $arrDados['cd_tipo_documentacao']         = $cd_tipo_documentacao;
                    $arrDados['cd_objeto']                    = $cd_objeto;
                    $arrDados['cd_projeto_continuado']        = $cd_projeto_continuo;
                    $arrDados['tx_arq_doc_projeto_continuo']  = $novoNome;
                    $arrDados['tx_nome_arquivo']              = $nomeOriginal;
                    $arrDados['dt_doc_projeto_continuo']     = date("Y-m-d H:i:s");

                    if(!$this->documentacaoProjetoContinuo->insert($arrDados))
                        throw new Base_Exception_Error( Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_DADOS_DOCUMENTO') );
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

	public function downloadProjetoContinuoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
        $data            = $this->_request->getParam('data');
        $objeto          = $this->_request->getParam('objeto');
        $projetoContinuo = $this->_request->getParam('projeto-continuo');
        $tipo            = $this->_request->getParam('tipo');
	
        $arrDados           = $this->documentacaoProjetoContinuo->getDocumentoProjetoContinuo($data, $projetoContinuo, $tipo);
		$objProjetoContinuo = $this->projetoContinuo->find($projetoContinuo, $objeto)->current();
		$tx_projeto_continuado = str_replace("/", "-", $objProjetoContinuo->tx_projeto_continuado);
	
		$arrExetensao = explode(".",$arrDados[0]['tx_arq_doc_projeto_continuo']);
//        echo "<pre>";print_r($arrExetensao);die( "<hr>ARQUIVO:<b>" . __FILE__ . "</b><br>LINHA:<b>" . __LINE__ . '</b>' );
		$exetensao    = $arrExetensao[1];

		unset($arrExetensao);
        $arquivo = SYSTEM_PATH_ABSOLUTE
                   .$this->_pathDocProjetoContinuo
        		   .DIRECTORY_SEPARATOR.$tx_projeto_continuado
				   .DIRECTORY_SEPARATOR.$arrDados[0]['tx_arq_doc_projeto_continuo'];

        if (!file_exists($arquivo)){
            die(Base_Util::getTranslator('L_MSG_ERRO_ARQUIVO_INEXISTENTE'));
        }

        header ("Content-type: octet/stream ");
        header ("Content-disposition: attachment; filename={$arrDados[0]['tx_arq_doc_projeto_continuo']};");
        header ("Content-Length: ".filesize($arquivo));
        readfile($arquivo);
        exit();
	}
}