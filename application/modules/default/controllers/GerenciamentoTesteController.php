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

class GerenciamentoTesteController extends Base_Controller_Action
{
    private $objTipoDocumentacao;
    private $objPdf;
    private $objRel;
    private $objProposta;
    private $_pathDocTeste;

	public function init()
	{
		parent::init();
        $this->_pathDocTeste = DIRECTORY_SEPARATOR."public"
                              .DIRECTORY_SEPARATOR."documentacao"
                              .DIRECTORY_SEPARATOR."item-teste";

		Zend_Loader::loadClass('RelatorioProjetoHistoricoTeste',Base_Util::baseUrlModule('relatorioProjeto', 'models'));

        $this->objTipoDocumentacao = new TipoDocumentacao($this->_request->getControllerName());
        $this->objPdf              = new Base_Tcpdf_Pdf;
        $this->objRel              = new RelatorioProjetoHistoricoTeste();
        $this->objProposta         = new Proposta($this->_request->getControllerName());
	}

	public function indexAction()
	{
		$this->initView();
	}

	public function projetosExecucaoAction()
	{
		$this->initView();
	}
	
	public function gridProjetosExecucaoAction()
	{
        $this->_helper->layout->disableLayout();
        $cd_contrato                       = $this->_request->getParam('cd_contrato');
	    $this->view->gridProjetosExecucao  = $this->objProposta->getProjetosExecucaoSemEncerramentoProposta(false,$cd_contrato);

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

	public function formUploadFileAction()
    {
        $this->_helper->layout->disableLayout();
        $this->view->tipoDocumentacaoCombo = $this->objTipoDocumentacao->getTipoDocumentacao("T");
        $this->view->params = $this->_request->getParams();
    }

	public function formDownloadFileAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->_request->getParams();

        if ( $params['st_tipo_item_teste'] == 'C' ) {
            $tabelaAssociativaItemTesteDocumetacao = new ItemTesteCasoDeUsoDocumentacao($this->_request->getControllerName());

            $paramsGrid = array(
                'cd_caso_de_uso'            => $params['cd_caso_de_uso_default'],
                'cd_projeto'                => $params['cd_projeto_default'],
                'cd_modulo'                 => $params['cd_modulo_default'],
                'cd_item_teste_caso_de_uso' => $params['cd_item_teste_caso_de_uso'],
                'dt_versao_caso_de_uso'     => $params['dt_versao_caso_de_uso']
            );

        } else if ( $params['st_tipo_item_teste'] == 'R' ) {
            $tabelaAssociativaItemTesteDocumetacao = new ItemTesteRequisitoDocumentacao($this->_request->getControllerName());

            $paramsGrid = array(
                'cd_requisito'            => $params['cd_requisito_default'],
                'cd_projeto'              => $params['cd_projeto_default'],
                'cd_item_teste_requisito' => $params['cd_item_teste_requisito'],
                'dt_versao_requisito'     => $params['dt_versao_requisito']
            );
        } else if ( $params['st_tipo_item_teste'] == 'N' ) {
            $tabelaAssociativaItemTesteDocumetacao = new ItemTesteRegraNegocioDocumentacao($this->_request->getControllerName());

            $paramsGrid = array(
                'cd_regra_negocio'            => $params['cd_regra_negocio_default'],
                'cd_projeto_regra_negocio'    => $params['cd_projeto_default'],
                'cd_item_teste_regra_negocio' => $params['cd_item_teste_regra_negocio'],
                'dt_regra_negocio'            => $params['dt_regra_negocio']
            );
        } else {
            die(Zend_Json_Encoder::encode(Base_Util::getTranslator('L_MSG_ERRO_INESPERADO')));
        }

        $this->view->params = $params;
        $this->view->res    = $tabelaAssociativaItemTesteDocumetacao->getGrid($paramsGrid);
    }

    public function uploadFileAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $arrReturn = array(
            'error'  => false, 
            'msg'    => Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO_ARQUIVO'),
            'typeMsg'=> 1
        );
        
        $params           = $this->_request->getParams();
        $fileElementName  = $this->_request->getPost('fileName');
        $fileMaxSize      = $this->_request->getPost('fileSize',K_SIZE_10MB_EM_BYTE);
        
        $tipos   = $this->objTipoDocumentacao->find($params['cd_tipo_documentacao__form_upload_file'])->current();
        $tipoDoc = explode(".", $tipos->tx_extensao_documentacao);
        
        try{
            $uploadValid = $this->helperUpload()->validaUpload( $fileElementName, $nomeOriginal, $extensao, $tipoDoc, $fileMaxSize);
            if( $uploadValid ){
                $dataAgora = date("YmdHis");
                // Cria o nome do arquivo
                $novoNome  = $dataAgora;
                $novoNome .= "__";
                $novoNome .= str_replace(" ", "_", $tipos->tx_tipo_documentacao);
                $novoNome .= "__";
                $novoNome .= "item_{$params['cd_item_teste__form_upload_file']}";
                $novoNome .= ".$extensao";

                // Cria o caminho
                $caminho  = $_SERVER['DOCUMENT_ROOT'];
                $caminho .= $this->_helper->BaseUrl->baseUrl();
                $caminho .= $this->_pathDocTeste;

                $retornoOk = true;
                // Define a sub-pasta
                if( $params['st_tipo_item_teste__form_upload_file'] == 'C' ){
                    $subpasta = 'caso-de-uso';
                    $tabelaAssociativaItemTesteDocumetacao = new ItemTesteCasoDeUsoDocumentacao($this->_request->getControllerName());
                    $tabelaAssociativaItemTeste            = new ItemTesteCasoDeUso($this->_request->getControllerName());
                    $tabela                                = new CasoDeUso($this->_request->getControllerName());
                } else if ($params['st_tipo_item_teste__form_upload_file'] == 'R' ){
                    $subpasta = 'requisito';
                    $tabelaAssociativaItemTesteDocumetacao = new ItemTesteRequisitoDocumentacao($this->_request->getControllerName());
                    $tabelaAssociativaItemTeste            = new ItemTesteRequisito($this->_request->getControllerName());
                    $tabela                                = new Requisito($this->_request->getControllerName());
                } else if( $params['st_tipo_item_teste__form_upload_file'] == 'N' ){
                    $subpasta = 'regra-negocio';
                    $tabelaAssociativaItemTesteDocumetacao = new ItemTesteRegraNegocioDocumentacao($this->_request->getControllerName());
                    $tabelaAssociativaItemTeste            = new ItemTesteRegraNegocio($this->_request->getControllerName());
                    $tabela                                = new RegraNegocio($this->_request->getControllerName());
                } else {
                    $subpasta  = false;
                    $retornoOk = false;
                }
                if( !$subpasta ){
                    throw new Base_Exception_Error( Base_Util::getTranslator('L_MSG_ERRO_DEFINICAO_SUBPASTA') );
                } else {
                    $caminho .= "/{$subpasta}";
                    $caminho .= "/{$novoNome}";
                    if (isset($_SERVER['WINDIR'])) {
                        $caminho = str_replace("/", "\\", $caminho);
                    }
                    $returnUpload = move_uploaded_file( $_FILES[$fileElementName]['tmp_name'] , $caminho );
                    if (!$returnUpload){
                        throw new Base_Exception_Error( Base_Util::getTranslator('L_MSG_ERRO_INESPERADO') );
                    } else {
                        $tabela->getDefaultAdapter()->beginTransaction();

                        // Grava as informações do upload na tabela associativa em CASO DE USO
                        if ($retornoOk && $subpasta == 'caso-de-uso') {
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_arq_item_teste_caso_de_uso"] = $tabelaAssociativaItemTesteDocumetacao->getNextValueOfField("cd_arq_item_teste_caso_de_uso");
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_item_teste"]                 = $params["cd_item_teste__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_caso_de_uso"]                = $params["cd_caso_de_uso_default__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_projeto"]                    = $params["cd_projeto_default__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_modulo"]                     = $params["cd_modulo_default__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_tipo_documentacao"]          = $params["cd_tipo_documentacao__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["tx_nome_arq_teste_caso_de_uso"] = $novoNome;
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["tx_arq_item_teste_caso_de_uso"] = $caminho;
                            if( !empty($params["dt_versao_caso_de_uso__form_upload_file"]) ){
                                $dt = $params["dt_versao_caso_de_uso__form_upload_file"];
                            } else {
                                $dtRes = $tabela->getUltimaVersaoCasoDeUso(
                                    $params["cd_caso_de_uso_default__form_upload_file"],
                                    $params["cd_projeto_default__form_upload_file"],
                                    $params["cd_modulo_default__form_upload_file"]
                                );
                                $dt = $dtRes['dt_versao_caso_de_uso'];
                            }
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["dt_versao_caso_de_uso"] = $dt;

                            if( !empty($params["textAreaPreenchido__form_upload_file"]) ){
                                $arr['tx_analise'] = $params["textAreaPreenchido__form_upload_file"];
                            }

                            if( !empty($params["cd_item_teste_caso_de_uso__form_upload_file"]) ){
                                $cd_item_caso_de_uso = $params["cd_item_teste_caso_de_uso__form_upload_file"];

                                $where = array(
                                    "cd_item_teste_caso_de_uso = ?" => $cd_item_caso_de_uso,
                                    "cd_caso_de_uso = ?"            => $params["cd_caso_de_uso_default__form_upload_file"],
                                    "cd_projeto = ?"                => $params["cd_projeto_default__form_upload_file"],
                                    "cd_item_teste = ?"             => $params["cd_item_teste__form_upload_file"],
                                    "dt_versao_caso_de_uso = ?"     => $dt,
                                    "cd_modulo = ?"                 => $params["cd_modulo_default__form_upload_file"]
                                );
                                if( !$tabelaAssociativaItemTeste->update($arr,$where) ){
                                    $retornoOk = false;
                                }

                            } else {
                                $cd_item_caso_de_uso = $tabelaAssociativaItemTeste->getNextValueOfField('cd_item_teste_caso_de_uso');

                                $arr['cd_item_teste_caso_de_uso'] = $cd_item_caso_de_uso;
                                $arr['cd_caso_de_uso']            = $params["cd_caso_de_uso_default__form_upload_file"];
                                $arr['cd_projeto']                = $params["cd_projeto_default__form_upload_file"];
                                $arr['cd_item_teste']             = $params["cd_item_teste__form_upload_file"];
                                $arr['dt_versao_caso_de_uso']     = $dt;
                                $arr['cd_modulo']                 = $params["cd_modulo_default__form_upload_file"];

                                if( !$tabelaAssociativaItemTeste->insert($arr) ){
                                    $retornoOk = false;
                                }

                            }
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_item_teste_caso_de_uso"] = $cd_item_caso_de_uso;
                        }// fim - if ($retornoOk && $subpasta == 'caso-de-uso')
                         else
                        // Grava as informações do upload na tabela associativa em REQUISITO
                        if ($retornoOk && $subpasta == 'requisito') {
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_arq_item_teste_requisito"] = $tabelaAssociativaItemTesteDocumetacao->getNextValueOfField("cd_arq_item_teste_requisito");
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_item_teste"]                   = $params["cd_item_teste__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_requisito"]                    = $params["cd_requisito_default__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_projeto"]                      = $params["cd_projeto_default__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_tipo_documentacao"]            = $params["cd_tipo_documentacao__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["tx_nome_arq_teste_requisito"] = $novoNome;
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["tx_arq_item_teste_requisito"] = $caminho;
                            if( !empty($params["dt_versao_requisito__form_upload_file"]) ){
                                $dt = $params["dt_versao_requisito__form_upload_file"];
                            } else {
                                $dtRes = $tabela->getUltimaVersaoRequisito(
                                    $params["cd_projeto_default__form_upload_file"],
                                    $params["cd_requisito_default__form_upload_file"]
                                );
                                $dt = $dtRes[0]['dt_versao_requisito'];
                            }
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["dt_versao_requisito"] = $dt;

                            if( !empty($params["textAreaPreenchido__form_upload_file"]) ){
                                $arr['tx_analise'] = $params["textAreaPreenchido__form_upload_file"];
                            }

                            if( !empty($params["cd_item_teste_requisito__form_upload_file"]) ){
                                $cd_item_requisito = $params["cd_item_teste_requisito__form_upload_file"];

                                $where = array(
                                    "cd_item_teste_requisito = ?" => $cd_item_requisito,
                                    "cd_requisito = ?"            => $params["cd_requisito_default__form_upload_file"],
                                    "cd_projeto = ?"              => $params["cd_projeto_default__form_upload_file"],
                                    "cd_item_teste = ?"           => $params["cd_item_teste__form_upload_file"],
                                    "dt_versao_requisito = ?"     => $dt
                                );
                                if( !$tabelaAssociativaItemTeste->update($arr,$where) ){
                                    $retornoOk = false;
                                }

                            } else {
                                $cd_item_requisito = $tabelaAssociativaItemTeste->getNextValueOfField('cd_item_teste_requisito');

                                $arr['cd_item_teste_requisito'] = $cd_item_requisito;
                                $arr['cd_requisito']            = $params["cd_requisito_default__form_upload_file"];
                                $arr['cd_projeto']              = $params["cd_projeto_default__form_upload_file"];
                                $arr['cd_item_teste']           = $params["cd_item_teste__form_upload_file"];
                                $arr['dt_versao_requisito']     = $dt;

                                if( !$tabelaAssociativaItemTeste->insert($arr) ){
                                    $retornoOk = false;
                                }

                            }
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_item_teste_requisito"] = $cd_item_requisito;
                        }// fim - if ($retornoOk && $subpasta == 'caso-de-uso')
                         else
                        // Grava as informações do upload na tabela associativa em REGRA DE NEGÓCIO
                        if ($retornoOk && $subpasta == 'regra-negocio') {
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_arq_item_teste_regra_neg"]   = $tabelaAssociativaItemTesteDocumetacao->getNextValueOfField("cd_arq_item_teste_regra_neg");
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_item_teste"]                 = $params["cd_item_teste__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_regra_negocio"]              = $params["cd_regra_negocio_default__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_projeto_regra_negocio"]      = $params["cd_projeto_default__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_tipo_documentacao"]          = $params["cd_tipo_documentacao__form_upload_file"];
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["tx_nome_arq_teste_regra_negoc"] = $novoNome;
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["tx_arq_item_teste_regra_negoc"] = $caminho;
                            if( !empty($params["dt_regra_negocio__form_upload_file"]) ){
                                $dt = $params["dt_regra_negocio__form_upload_file"];
                            } else {
                                $dtRes = $tabela->getUltimaVersaoRegraNegocio(
                                    $params["cd_regra_negocio_default__form_upload_file"],
                                    $params["cd_projeto_default__form_upload_file"]
                                );
                                $dt = $dtRes[0]['dt_regra_negocio'];
                            }
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["dt_regra_negocio"] = $dt;

                            if( !empty($params["textAreaPreenchido__form_upload_file"]) ){
                                $arr['tx_analise'] = $params["textAreaPreenchido__form_upload_file"];
                            }

                            if( !empty($params["cd_item_teste_regra_negocio__form_upload_file"]) ){
                                $cd_item_regra_negocio = $params["cd_item_teste_regra_negocio__form_upload_file"];

                                $where = array(
                                    "cd_item_teste_regra_negocio = ?" => $cd_item_regra_negocio,
                                    "cd_regra_negocio = ?"            => $params["cd_regra_negocio_default__form_upload_file"],
                                    "cd_projeto_regra_negocio = ?"    => $params["cd_projeto_default__form_upload_file"],
                                    "cd_item_teste = ?"               => $params["cd_item_teste__form_upload_file"],
                                    "dt_regra_negocio = ?"            => $dt
                                );
                                if( !$tabelaAssociativaItemTeste->update($arr,$where) ){
                                    $retornoOk = false;
                                }

                            } else {
                                $cd_item_regra_negocio = $tabelaAssociativaItemTeste->getNextValueOfField('cd_item_teste_regra_negocio');

                                $arr['cd_item_teste_regra_negocio'] = $cd_item_regra_negocio;
                                $arr['cd_regra_negocio']            = $params["cd_regra_negocio_default__form_upload_file"];
                                $arr['cd_projeto_regra_negocio']    = $params["cd_projeto_default__form_upload_file"];
                                $arr['cd_item_teste']               = $params["cd_item_teste__form_upload_file"];
                                $arr['dt_regra_negocio']            = $dt;

                                if( !$tabelaAssociativaItemTeste->insert($arr) ){
                                    $retornoOk = false;
                                }
                            }
                            $arrInsertTabelaAssociativaItemTesteDocumetacao["cd_item_teste_regra_negocio"] = $cd_item_regra_negocio;
                        }// fim - if ($retornoOk && $subpasta == 'regra-negocio')

                        if($retornoOk){
                            if( !$tabelaAssociativaItemTesteDocumetacao->insert($arrInsertTabelaAssociativaItemTesteDocumetacao) )
                                 throw new Base_Exception_Error( Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_ARQUIVO') );  
                        }else{
                            throw new Base_Exception_Error( Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_ARQUIVO') );
                        }
                        $tabela->getDefaultAdapter()->commit();
                    }
                }
            }
        }catch(Base_Exception_Alert $e){
            $tabela->getDefaultAdapter()->rollBack();
            $arrReturn['error'  ] = true;
            $arrReturn['msg'    ] = $e->getMessage();
            $arrReturn['typeMsg'] = 2;
        }catch(Base_Exception_Error $e){
            $tabela->getDefaultAdapter()->rollBack();
            $arrReturn['error'  ] = true;
            $arrReturn['msg'    ] = $e->getMessage();
            $arrReturn['typeMsg'] = 3;
        }catch(Exception $e){
            $tabela->getDefaultAdapter()->rollBack();
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

        $codigo = $this->_request->getParam('cd',null);
        $tipo   = $this->_request->getParam('tipo',null);

        if( is_null($codigo) || is_null($tipo) ){
            die(Zend_Json_Encoder::encode('ERRO!'));
        }

        if ( $tipo == 'C' ) {
            $tabelaAssociativaItemTesteDocumetacao = new ItemTesteCasoDeUsoDocumentacao($this->_request->getControllerName());
        } else if ( $tipo == 'R' ) {
            $tabelaAssociativaItemTesteDocumetacao = new ItemTesteRequisitoDocumentacao($this->_request->getControllerName());
        } else if ( $tipo == 'N' ) {
            $tabelaAssociativaItemTesteDocumetacao = new ItemTesteRegraNegocioDocumentacao($this->_request->getControllerName());
        } else {
            die(Zend_Json_Encoder::encode(Base_Util::getTranslator('L_MSG_ERRO_INESPERADO')));
        }

        $arrArquivo = $tabelaAssociativaItemTesteDocumetacao->getArquivoDonwload($codigo);

        if (!file_exists($arrArquivo['caminho'])){
            die(Base_Util::getTranslator('L_MSG_ERRO_INESPERADO'));
        }
        header ("Content-type: octet/stream ");
        header ("Content-disposition: attachment; filename=Anexo_Item_Teste.{$arrArquivo['extensao']};");
        header ("Content-Length: ".filesize($arrArquivo['caminho']));
        readfile($arrArquivo['caminho']);
        exit();
    }

    public function historicoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $params = $this->_request->getParams();
        //Condição para gerar o histórico de Caso de Uso
        if ( $params['st_tipo_item_teste'] == 'C' ) {
            $paramsCasoDeUso = array(
                'cd_caso_de_uso'            => $params['cd_caso_de_uso_default'],
                'cd_projeto'                => $params['cd_projeto_default'],
                'cd_modulo'                 => $params['cd_modulo_default'],
                'dt_versao_caso_de_uso'     => ($params['dt_versao_caso_de_uso'] != "")?"{$this->objRel->to_timestamp("'{$params['dt_versao_caso_de_uso']}'",'YYYY-MM-DD HH24:MI:SS')}":"",
                'cd_item_teste'             => $params['cd_item_teste']
            );
            $arrDados = $this->objRel->historicoTesteCasoDeUso($paramsCasoDeUso);
            $this->geraHistoricoTesteCasoDeUso($arrDados);
            
        //Condição para gerar o histórico de requisitos
        } else if ( $params['st_tipo_item_teste'] == 'R' ) {
            $paramsRequisito = array(
                'cd_requisito'            => $params['cd_requisito_default'],
                'cd_projeto'              => $params['cd_projeto_default'],
                'dt_versao_requisito'     => ($params['dt_versao_requisito'] != "")?"{$this->objRel->to_timestamp("'{$params['dt_versao_requisito']}'",'YYYY-MM-DD HH24:MI:SS')}":"",
                'cd_item_teste'           => $params['cd_item_teste']
            );
            $arrDados = $this->objRel->historicoTesteRequisito($paramsRequisito);
            $this->gerarHistoricoTesteRequisito($arrDados);
        //Condição que gerar o histórico de Negocio
        } else if ( $params['st_tipo_item_teste'] == 'N' ) {
            $tabelaAssociativaItemTeste = new ItemTesteRegraNegocio($this->_request->getControllerName());

            $paramsNegocio = array(
                'cd_regra_negocio'            => $params['cd_regra_negocio_default'],
                'cd_projeto_regra_negocio'    => $params['cd_projeto_default'],
                'dt_regra_negocio'            => ($params['dt_regra_negocio'] != "")?"{$this->objRel->to_timestamp("'{$params['dt_regra_negocio']}'",'YYYY-MM-DD HH24:MI:SS')}":"",
                'cd_item_teste'               => $params['cd_item_teste']
            );
            $arrDados = $this->objRel->historicoTesteRegraNegocio($paramsNegocio);
            $this->gerarHistoricoTesteRegraNegocio($arrDados);
        } 
    }

    private function geraHistoricoTesteCasoDeUso(array $arrCasoDeUso)
    {
        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_HISTORICO_TESTE_CASO_DE_USO'),K_CREATOR_SYSTEM. ', ' . Base_Util::getTranslator('L_MENU_GERENCIAMENTO_TESTE').', '.Base_Util::getTranslator('L_VIEW_CASO_DE_USO'));
        $this->objPdf->SetDisplayMode("real");
        // set font
        $this->objPdf->AddPage();

        if($arrCasoDeUso){

            $w = array(80,100);
            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell(PDF_MARGIN_LEFT, 6, "Projeto:", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell(PDF_MARGIN_LEFT, 6, $arrCasoDeUso[0]['tx_projeto'], '');
            $this->objPdf->Ln(7);

            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_SIGLA_PROJETO').":", '');
            $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_ITEM_TESTE').":", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell($w[0], 6, $arrCasoDeUso[0]['tx_sigla_projeto'], '');
            $this->objPdf->Cell($w[1], 6, $arrCasoDeUso[0]['tx_item_teste'], '');
            $this->objPdf->Ln(7);

            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_CASO_DE_USO').":", '');
            $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_MODULO').":", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell($w[0], 6, $arrCasoDeUso[0]['tx_caso_de_uso'], '');
            $this->objPdf->Cell($w[1], 6, $arrCasoDeUso[0]['tx_modulo'], '');
            $this->objPdf->Ln(5);

            $qtd = count($arrCasoDeUso)-1;

            $this->objPdf->Cell(180,6,"",'B',1);
            $this->objPdf->Ln(5);
            foreach($arrCasoDeUso as $key=>$value){
                if($value['st_analise'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, "Análise", '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_ANALISE').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_analise'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_analise'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_ANALISE').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_analise'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, "Análise", '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_ANALISE'),'',1);
                }

                if($value['st_solucao'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(180, 6, "Solução", '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_SOLUCAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_solucao'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_solucao'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_SOLUCAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_solucao'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_MENU_GERENCIAMENTO_TESTE_CASO_USO_SOLUCAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_SOLUCAO'),'',1);
                }

                if($value['st_homologacao'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_HOMOLOGACAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_HOMOLOGACAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_homologacao'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_homologacao'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_HOMOLOGACAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_homologacao'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_HOMOLOGACAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_HOMOLOGACAO'),'',1);
                }
                if($qtd != $key){
                    $this->objPdf->Cell(180,6,"",'B',1);
                    $this->objPdf->Ln(5);
                }
            }
        }else{
            $this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        //Close and output PDF document
        $this->objPdf->Output('historico_teste_caso_de_uso.pdf', 'I');
    }

    private function gerarHistoricoTesteRequisito(array $arrRequisito)
    {
        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_HISTORICO_TESTE_REQUISITOS'),K_CREATOR_SYSTEM.','.Base_Util::getTranslator('L_MENU_GERENCIAMENTO_TESTE').','.Base_Util::getTranslator('L_MENU_GERENCIAMENTO_TESTE_REQUISITO'));
        $this->objPdf->SetDisplayMode("real");
        // set font
        $this->objPdf->AddPage();

        if($arrRequisito){

            $w = array(80,100);
            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell(PDF_MARGIN_LEFT, 6, Base_Util::getTranslator('L_VIEW_PROJETO').":", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell(PDF_MARGIN_LEFT, 6, $arrRequisito[0]['tx_projeto'], '');
            $this->objPdf->Ln(7);

            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_SIGLA_PROJETO').":", '');
            $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_ITEM_TESTE').":", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell($w[0], 6, $arrRequisito[0]['tx_sigla_projeto'], '');
            $this->objPdf->Cell($w[1], 6, $arrRequisito[0]['tx_item_teste'], '');
            $this->objPdf->Ln(7);

            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_REQUISITO').":", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell($w[1], 6, $arrRequisito[0]['tx_requisito'], '');
            $this->objPdf->Ln(5);

            $qtd = count($arrRequisito)-1;
            $this->objPdf->Cell(180,6,"",'B',1);
            $this->objPdf->Ln(5);
            foreach($arrRequisito as $key=>$value){
                if($value['st_analise'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_ANALISE'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_ANALISE').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_analise'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_analise'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_ANALISE').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_analise'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_ANALISE'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_ANALISE'),'',1);
                }

                if($value['st_solucao'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_SOLUCAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6,  Base_Util::getTranslator('L_VIEW_DATA_SOLUCAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_solucao'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_solucao'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_SOLUCAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_solucao'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6,  Base_Util::getTranslator('L_VIEW_SOLUCAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_SOLUCAO'),'',1);
                }

                if($value['st_homologacao'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_HOMOLOGACAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_HOMOLOGACAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_homologacao'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_homologacao'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_HOMOLOGACAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_homologacao'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_HOMOLOGACAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_HOMOLOGACAO'),'',1);
                }
                if($qtd != $key){
                    $this->objPdf->Cell(180,6,"",'B',1);
                    $this->objPdf->Ln(5);
                }
            }
        }else{
            $this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        //Close and output PDF document
        $this->objPdf->Output('historico_teste_requisitos.pdf', 'I');
    }

    private function gerarHistoricoTesteRegraNegocio(array $arrRegraNegocio)
    {
        $this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_HISTORICO_TESTE_REGRA_DE_NEGOCIO'),K_CREATOR_SYSTEM.','.Base_Util::getTranslator('L_MENU_GERENCIAMENTO_TESTE'));
        $this->objPdf->SetDisplayMode("real");
        // set font
        $this->objPdf->AddPage();

        if($arrRegraNegocio){

            $w = array(80,100);
            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell(PDF_MARGIN_LEFT, 6, Base_Util::getTranslator('L_VIEW_PROJETO').":", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell(PDF_MARGIN_LEFT, 6, $arrRegraNegocio[0]['tx_projeto'], '');
            $this->objPdf->Ln(7);

            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_SIGLA_PROJETO').":", '');
            $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_ITEM_TESTE').":", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell($w[0], 6, $arrRegraNegocio[0]['tx_sigla_projeto'], '');
            $this->objPdf->Cell($w[1], 6, $arrRegraNegocio[0]['tx_item_teste'], '');
            $this->objPdf->Ln(7);

            $this->objPdf->SetFont('helvetica', 'B',8);
            $this->objPdf->Cell($w[0], 6, "Regra de Negócio:", '');
            $this->objPdf->Ln(4);
            $this->objPdf->SetFont('helvetica', '',8);
            $this->objPdf->Cell($w[1], 6, $arrRegraNegocio[0]['tx_regra_negocio'], '');
            $this->objPdf->Ln(5);

            $qtd = count($arrRegraNegocio)-1;

            $this->objPdf->Cell(180,6,"",'B',1);
            $this->objPdf->Ln(5);
            foreach($arrRegraNegocio as $key=>$value){
                if($value['st_analise'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_ANALISE'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_ANALISE').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_analise'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_analise'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_ANALISE').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_analise'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_ANALISE'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_ANALISE'),'',1);
                }

                if($value['st_solucao'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_SOLUCAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_SOLUCAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_solucao'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_solucao'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_SOLUCAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_solucao'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_SOLUCAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_SOLUCAO'),'',1);
                }

                if($value['st_homologacao'] == 'S'){
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(180, 6, Base_Util::getTranslator('L_VIEW_HOMOLOGACAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_PROFISSIONAL').":",'');
                    $this->objPdf->Cell($w[1], 6, Base_Util::getTranslator('L_VIEW_DATA_HOMOLOGACAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_profissional_homologacao'],'');
                    $this->objPdf->Cell($w[1], 6, $value['dt_homologacao'],'');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', 'B',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_VIEW_DESCRICAO_HOMOLOGACAO').":",'');
                    $this->objPdf->Ln(4);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, $value['tx_homologacao'],'');
                    $this->objPdf->Ln(7);
                } else {
                    $this->objPdf->SetFont('helvetica', 'B',10);
                    $this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_HOMOLOGACAO'), '');
                    $this->objPdf->Ln(7);
                    $this->objPdf->SetFont('helvetica', '',8);
                    $this->objPdf->Cell($w[0], 6, Base_Util::getTranslator('L_MSG_ALERT_NAO_POSSUI_INFORMACOES_HOMOLOGACAO'),'',1);
                }
                if($qtd != $key){
                    $this->objPdf->Cell(180,6,"",'B',1);
                    $this->objPdf->Ln(5);
                }
            }
        }else{
            $this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        //Close and output PDF document
        $this->objPdf->Output('historico_teste_regra_negocio.pdf', 'I');
    }
 }