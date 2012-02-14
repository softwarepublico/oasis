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

class DisponibilidadeServicoController extends Base_Controller_Action
{

	private $objDisponibilidadeServico;
	private $objDisponibilidadeServicoDocumentacao;
	private $objTipoDocumentacao;
	private $objObjetoContrato;
    private $_pathDocDispServico;

	public function init()
	{
		parent::init();
        $this->_pathDocDispServico = DIRECTORY_SEPARATOR . "public"
                                    .DIRECTORY_SEPARATOR . "documentacao"
                                    .DIRECTORY_SEPARATOR . "disponibilidade-servico";
		$this->objDisponibilidadeServico			 = new DisponibilidadeServico($this->_request->getControllerName());
		$this->objDisponibilidadeServicoDocumentacao = new DisponibilidadeServicoDocumentacao($this->_request->getControllerName());
		$this->objTipoDocumentacao					 = new TipoDocumentacao($this->_request->getControllerName());
		$this->objObjetoContrato					 = new ObjetoContrato($this->_request->getControllerName());
	}

	public function indexAction(){}

	public function salvarAction(){

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData	= $this->_request->getPost();

		$arrResult	= array('error'=>false,'typeMsg'=>1, 'msg'=>'');

		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			if(!empty($formData['cd_disponibilidade_servico'])) {
				$novo             = $this->objDisponibilidadeServico->fetchRow("cd_disponibilidade_servico= {$formData['cd_disponibilidade_servico']} and cd_objeto = {$formData['hidden_cd_objeto_disp_servico']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');

				$novo->cd_objeto						= $formData['hidden_cd_objeto_disp_servico'];
				$novo->dt_inicio_analise_disp_servico	= new Zend_Db_Expr("{$this->objDisponibilidadeServico->to_date("'{$formData['hidden_dt_inicio_analise_disponilidade_servico']}'", 'DD/MM/YYYY')}");
				$novo->dt_fim_analise_disp_servico		= new Zend_Db_Expr("{$this->objDisponibilidadeServico->to_date("'{$formData['hidden_dt_fim_analise_disponilidade_servico']}'", 'DD/MM/YYYY')}");
			} else {
				
				$this->verificaPeriodoAnalise( $formData['dt_inicio_analise_disp_servico'],
											   $formData['dt_fim_analise_disp_servico'],
											   $formData['cd_objeto_disp_servico']);

				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
				
				$novo									= $this->objDisponibilidadeServico->createRow();
				$novo->cd_disponibilidade_servico		= $this->objDisponibilidadeServico->getNextValueOfField('cd_disponibilidade_servico');
				$novo->cd_objeto						= $formData['cd_objeto_disp_servico'];
				$novo->dt_inicio_analise_disp_servico	= new Zend_Db_Expr("{$this->objDisponibilidadeServico->to_date("'{$formData['dt_inicio_analise_disp_servico']}'", 'DD/MM/YYYY')}");
				$novo->dt_fim_analise_disp_servico		= new Zend_Db_Expr("{$this->objDisponibilidadeServico->to_date("'{$formData['dt_fim_analise_disp_servico']}'", 'DD/MM/YYYY')}");
			}

			$novo->tx_analise_disp_servico		= $formData['tx_analise_disp_servico'];
			$novo->tx_parecer_disp_servico		= $formData['tx_parecer_disp_servico'];
			$novo->ni_indice_disp_servico		= ($formData['ni_indice_disp_servico'] == '') ? null : $formData['ni_indice_disp_servico'];

			$novo->save();
            $db->commit();
            
		} catch(Base_Exception_Alert $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 2;
			$arrResult['msg'	] = $e->getMessage();
            $db->rollBack();
		} catch(Zend_Exception $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	private function verificaPeriodoAnalise($dt_inicial, $dt_final, $cd_objeto){

		$select = $this->objDisponibilidadeServico->select()
												  ->from($this->objDisponibilidadeServico, array('cd_disponibilidade_servico',
																								 'cd_objeto',
																								 'dt_inicio_analise_disp_servico',
																								 'dt_fim_analise_disp_servico'))
												  ->where('cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE)
												  ->order('dt_fim_analise_disp_servico');

		$arrAnalise = $this->objDisponibilidadeServico->fetchAll($select);

		if($arrAnalise->valid()){

			foreach( $arrAnalise as $analise ){

				$dt_inicio_banco = date('d/m/Y', strtotime($analise->dt_inicio_analise_disp_servico));
				$dt_fim_banco	 = date('d/m/Y', strtotime($analise->dt_fim_analise_disp_servico));

                $arrValueMsg = array('value1'=>$dt_inicio_banco,
                                     'value2'=>$dt_fim_banco);

				//verifica se o periodo informado abrange um periodo já analisado
				if( $this->converteData($dt_inicio_banco) >= $this->converteData($dt_inicial) ){
					if( $this->converteData($dt_fim_banco) <= $this->converteData($dt_final) ){
						throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_PERIODO_ABRANGE_PERIODO_ANALISADO', $arrValueMsg));
					}
				}
				//verifica se o periodo informado esta contido em um periodo já analisado
				if( $this->converteData($dt_inicial) >= $this->converteData($dt_inicio_banco) ){
					if( $this->converteData($dt_final) <= $this->converteData($dt_fim_banco) ){
						throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_PERIODO_ABRANGIDO_PERIODO_ANALISADO', $arrValueMsg));
					}
				}

				//verifica se a data inicial está compreendida em um período já analisado
				if( ($this->converteData($dt_inicial) >= $this->converteData($dt_inicio_banco))){
					if($this->converteData($dt_inicial) <= $this->converteData($dt_fim_banco)){
						throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_DATA_INICIAL_COMPREENDE_PERIODO_ANALISADO', $arrValueMsg));
					}
				}

				if( ($this->converteData($dt_final) <= $this->converteData($dt_fim_banco))){
					if($this->converteData($dt_final) >= $this->converteData($dt_inicio_banco)){
						throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_DATA_FINAL_COMPREENDE_PERIODO_ANALISADO', $arrValueMsg));
					}
				}
 			}
 		}
	}

	private function converteData($data){
		$arrData = explode('/', $data);
		return ((((int)$arrData[2] * 100) + (int)$arrData[1]) * 100) + (int)$arrData[0];
	}

	public function excluirAction()
    {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData	= $this->_request->getPost();

		$arrResult	= array('error'=>false,'typeMsg'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));

		$erro = false;
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();

			$erro = (!$erro) ? $this->verificaExclusaoAnaliseDispServico( $formData['cd_objeto'], $formData['cd_analise'] ): true ;

			if( !$erro ){
				if(!$this->objDisponibilidadeServico->delete("cd_disponibilidade_servico = {$formData['cd_analise']} and cd_objeto = {$formData['cd_objeto']}")){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO'));
					$erro = true;
				}
			}
			if(!$erro){
				$db->commit();
			} else {
				$db->rollBack();
			}
		} catch(Base_Exception_Alert $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 2;
			$arrResult['msg'	] = $e->getMessage();
		} catch(Base_Exception_Error $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = $e->getMessage();
		} catch(Zend_Exception $e) {
			$arrResult['error'	] = true;
			$arrResult['typeMsg'] = 3;
			$arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	/**
	 * Função para verificar se existe dados na tabela associativa de documentacao
	 * no momento de exclusão de análise
	 *
	 * @param <int> $cd_objeto
	 * @param <int> $cd_analise
	 */
	private function verificaExclusaoAnaliseDispServico($cd_objeto, $cd_analise)
    {
		$erro	= false;
		$select = $this->objDisponibilidadeServicoDocumentacao->select()
															  ->where("cd_objeto = ?", $cd_objeto)
															  ->where("cd_disponibilidade_servico = ?", $cd_analise);
		
		$result = $this->objDisponibilidadeServicoDocumentacao->fetchAll($select);

		// se for valido é porque existe registros de associação
		if( $result->valid() ){
			throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_REGISTRO_VINCULO_DOCUMENTACAO'));
			$erro = true;
		}
		return $erro;
	}

	public function gridAnaliseDisponibilidadeAction()
    {
		$this->_helper->layout->disableLayout();

		$cd_objeto = $this->_request->getParam('cd_objeto', -1);
		$cd_objeto = ( $cd_objeto == -1 ) ? null : $cd_objeto;

		$this->view->res = $this->objDisponibilidadeServico->getAnaliseDisponibilidadeServico($cd_objeto);
	}

	public function gridDocumentacaoAnaliseDisponibilidadeAction()
    {
		$this->_helper->layout->disableLayout();
		$arrPost		 = $this->_request->getPost();
		$this->view->res = $this->objDisponibilidadeServicoDocumentacao->getDadosDocumentacaoDisponibilidadeServico($arrPost['cd_analise'], $arrPost['cd_objeto']);
	}

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

	public function recuperaDadosAnaliseAction()
    {

		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrPost	= $this->_request->getPost();
		$arrResult	= $this->objDisponibilidadeServico->getAnaliseDisponibilidadeServico($arrPost['cd_objeto'], $arrPost['cd_analise']);

        $arrResult[0]['dt_inicio_analise_disp_servico'] = date('d/m/Y', strtotime($arrResult[0]['dt_inicio_analise_disp_servico']));
        $arrResult[0]['dt_fim_analise_disp_servico'   ] = date('d/m/Y', strtotime($arrResult[0]['dt_fim_analise_disp_servico']));
        
		echo Zend_Json::encode($arrResult[0]);

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
        
		$cd_disponibilidade_servico	= $this->_request->getParam('cd_disponibilidade_servico_documentacao');
		$cd_objeto					= $this->_request->getParam('cd_objeto_disponibilidade_servico_documentacao');
		$cd_tipo_documentacao		= $this->_request->getParam('cd_tipo_documentacao_disp_servico');
		$fileElementName            = $this->_request->getPost('fileName');
        $fileMaxSize                = $this->_request->getPost('fileSize',K_SIZE_10MB_EM_BYTE);
        
        // Consulta tipos de arquivos permitidos
        $tipos   = $this->objTipoDocumentacao->find($cd_tipo_documentacao)->current();
        $tipoDoc = explode(".", $tipos->tx_extensao_documentacao);
        
        try{
            $uploadValid = $this->helperUpload()->validaUpload( $fileElementName, $nomeOriginal, $extensao, $tipoDoc, $fileMaxSize);
            if( $uploadValid ){
                $analiseDispServico = $this->objDisponibilidadeServico->find($cd_disponibilidade_servico, $cd_objeto)->current();
                $objetoContrato		= $this->objObjetoContrato->find($cd_objeto)->current();
                $dataAgora			= date("YmdHis");

                // Cria o nome do arquivo
                $novoNome = $dataAgora . "_(" . $analiseDispServico->dt_inicio_analise_disp_servico . "_" .$analiseDispServico->dt_fim_analise_disp_servico .")_" . str_replace(" ", "_", $tipos->tx_tipo_documentacao) . ".$extensao";

                //nome do objeto para criação da pasta das imagens
                $tx_objeto = str_replace(" ", "-", $objetoContrato->tx_objeto);

                // Cria a estrutura de pastas e salva o arquivo
                $caminho = $_SERVER['DOCUMENT_ROOT'] . $this->_helper->BaseUrl->baseUrl() . $this->_pathDocDispServico;

                if (isset($_SERVER['WINDIR']))
                    $caminho = str_replace("/", "\\", $caminho);

                chdir($caminho);

                $objetoDir = DIRECTORY_SEPARATOR . $tx_objeto;

                if (!file_exists($caminho . $objetoDir))
                    mkdir($tx_objeto);

                chdir($caminho . $objetoDir);

                $returnUpload = move_uploaded_file($_FILES[$fileElementName]['tmp_name'],
                                $caminho
                                . $objetoDir
                                . DIRECTORY_SEPARATOR
                                . $novoNome);

                if (!$returnUpload){
                    throw new Base_Exception_Error( Base_Util::getTranslator('L_MSG_ERRO_INESPERADO') );
                } else {
                    // Grava as informações do upload na tabela a_documentacao_projeto
                    chdir($_SERVER['DOCUMENT_ROOT']  . $this->_helper->BaseUrl->baseUrl());

                    $arrDados['cd_tipo_documentacao'		  ] = $cd_tipo_documentacao;
                    $arrDados['cd_disponibilidade_servico'	  ] = $cd_disponibilidade_servico;
                    $arrDados['cd_objeto'					  ] = $cd_objeto;
                    $arrDados['dt_doc_disponibilidade_servico'] = date("Y-m-d H:i:s");
                    $arrDados['tx_arquivo_disp_servico'		  ] = $novoNome;
                    $arrDados['tx_nome_arq_disp_servico'	  ] = $nomeOriginal;

                    if(!$this->objDisponibilidadeServicoDocumentacao->insert($arrDados))
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

	public function downloadDocumentoDisponibilidadeServicoAction(){
		
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $data						= $this->_request->getParam('data');
        $cd_tipo_documentacao		= $this->_request->getParam('tipo');
        $cd_objeto					= $this->_request->getParam('cd_objeto');
        $cd_disponibilidade_servico	= $this->_request->getParam('cd_disponibilidade_servico');


        $arrDadosDocumento	= $this->objDisponibilidadeServicoDocumentacao->find($cd_disponibilidade_servico, $cd_objeto, $cd_tipo_documentacao, $data)->current();
		$objetoContrato		= $this->objObjetoContrato->find($cd_objeto)->current();
		$tx_objeto			= str_replace(" ", "-", $objetoContrato->tx_objeto);

		$arrExetensao		= explode(".",$arrDadosDocumento->tx_arquivo_disp_servico);
		$exetensao			= $arrExetensao[1];
		unset($arrExetensao);

        $arquivo = SYSTEM_PATH_ABSOLUTE
                   .$this->_pathDocDispServico
        		   .DIRECTORY_SEPARATOR.$tx_objeto
				   .DIRECTORY_SEPARATOR.$arrDadosDocumento->tx_arquivo_disp_servico;

        if (!file_exists($arquivo)){
            die(Base_Util::getTranslator('L_MSG_ERRO_ARQUIVO_INEXISTENTE'));
        }
        header ("Content-type: octet/stream ");
        header ("Content-disposition: attachment; filename={$arrDadosDocumento->tx_nome_arq_disp_servico}.{$exetensao};");
        header ("Content-Length: ".filesize($arquivo));
        readfile($arquivo);
        exit();
	}
}