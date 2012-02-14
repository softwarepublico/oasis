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

class AcompanhamentoFiscalizacaoController extends Base_Controller_Action
{

	private $_objContrato;
	private $_objTipoDocumantacao;

	public function init()
	{
		parent::init();
		$this->_objContrato         = new Contrato($this->_request->getControllerName());
		$this->_objTipoDocumentacao = new TipoDocumentacao($this->_request->getControllerName());
	}

	public function indexAction()
	{
		//condição que checa a permissão para acessar o accordion de Análise de Execução
		if (ChecaPermissao::possuiPermissao('analise-execucao-projeto') === true){
			//metodo passa todas as informações necessarias para a tela de Análise de Execução
			$this->accordionAnaliseExecucaoProjeto();
		}
		//condição que checa a permissão para acessar o accordion de Gerenciamento de Teste
		if (ChecaPermissao::possuiPermissao('acompanhamento-fiscalizacao-documentacao') === true){
			//metodo passa todas as informações necessarias para a tela de Gerenciamento de Teste
			$this->accordionDocumentacao();
		}
	}
	

	/**
	 * Método que manda as informações da tela de Análise de Execução
	 */
	private function accordionAnaliseExecucaoProjeto()
	{
		$this->view->arrContratoAnaliseExecucao = $this->_objContrato->getContratoAtivoObjeto(true, 'P');
	}

	/**
	 * Método que manda as informações da tela de Documentacao
	 */
	private function accordionDocumentacao()
	{
		$this->view->arrContratoDocumentacao  = $this->_objContrato->getContratoAtivoObjeto(true, 'P');
		$this->view->arrComboTipoDocumentacao = $this->_objTipoDocumentacao->getTipoDocumentacao('A', true);
        
	}


}