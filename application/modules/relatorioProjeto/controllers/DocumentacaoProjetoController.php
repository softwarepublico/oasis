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

class RelatorioProjeto_DocumentacaoProjetoController extends Base_Controller_Action
{
    private $_objProjeto;
	private $_objContrato;
    private $_objContratoProjeto;
    private $_objDocumentacaoProjeto;
    
	public function init()
	{
		parent::init();
        $this->_objProjeto             = new Projeto();
        $this->_objContrato            = new Contrato();
        $this->_objContratoProjeto     = new ContratoProjeto();
        $this->_objDocumentacaoProjeto = new DocumentacaoProjeto();
	}

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_DOCUMENTACAO_PROJETO'));

        $cd_contrato = null;
        $comStatus	 = true;
        if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->arrContrato = $this->_objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
    }

    public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->_objContratoProjeto->listaProjetosContrato($cd_contrato, true);

		$options = '';
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		echo $options;
	}

    public function gridDocumentacaoAction()
	{
		$this->_helper->layout->disableLayout();

		$cd_projeto = $this->_request->getParam('cd_projeto');

		$arrDocumentacao = $this->_objDocumentacaoProjeto->getDadosDocumentacaoProjeto($cd_projeto);

		$i=0;
		foreach ($arrDocumentacao as $value) {
			if (is_null($value['tx_nome_arquivo']))
			{
				$arrDocumentacao[$i]['tx_nome_arquivo'] = "default";
			}
			$i++;
		}

		$this->view->res = $arrDocumentacao;
	}

	 public function downloadAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
        $data     = $this->_request->getParam('data');
        $projeto  = $this->_request->getParam('projeto');
        $tipo     = $this->_request->getParam('tipo');
	
        $arrDados   = $this->_objDocumentacaoProjeto->getDocumentoProposta($data, $projeto, $tipo);

		$arrDados[0]['tx_nome_arquivo'] = (is_null($arrDados[0]['tx_nome_arquivo']))?"default":$arrDados[0]['tx_nome_arquivo'];

		$objProjeto = $this->_objProjeto->find($projeto)->current();
		
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
            die();
        }
		
        header ("Content-type: octet/stream ");
        header ("Content-disposition: attachment; filename={$arrDados[0]['tx_nome_arquivo']}.{$exetensao};");
        header ("Content-Length: ".filesize($arquivo));
        readfile($arquivo);
        exit();
	}
}