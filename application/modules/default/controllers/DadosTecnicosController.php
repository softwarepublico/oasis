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

class DadosTecnicosController extends Base_Controller_Action 
{
	private $objDadostecnicos;
	private $objInformacoesTecnicas;
	private $objConhecimento;
	private $objConhecimentoProjeto;
	
	public function init()
	{
		parent::init();
		$this->objDadostecnicos       = new TipoDadoTecnico($this->_request->getControllerName());
		$this->objInformacoesTecnicas = new InformacaoTecnica($this->_request->getControllerName());
		$this->objConhecimento        = new Conhecimento($this->_request->getControllerName());
		$this->objConhecimentoProjeto = new ConhecimentoProjeto($this->_request->getControllerName());
	}
	
	public function indexAction()
	{}
	
	public function gridInformacoesTecnicasAction()
	{
		$this->_helper->layout->disableLayout();
		$cd_projeto = $this->_request->getParam('cd_projeto');
		
		$arrInformacoesTecnicas = $this->objInformacoesTecnicas->getDadosTecnicosInformacao($cd_projeto);
		$this->view->res = $arrInformacoesTecnicas; 
	}
	
	public function salvarInformacoesTecnicasAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			$i=0;
			$cd_projeto = $formData['cd_projeto'];
			unset($formData['cd_projeto']);
			
			foreach($formData as $key=>$conteudo){
				$cd_dados_tecnica = substr($key,30);
				$arrInformacoesTecnicas[$i]['cd_projeto']                     = $cd_projeto;
				$arrInformacoesTecnicas[$i]['cd_tipo_dado_tecnico']           = $cd_dados_tecnica;
				$arrInformacoesTecnicas[$i]['tx_conteudo_informacao_tecnica'] = trim($conteudo);
				$i++;
			}
			$this->objInformacoesTecnicas->salvaInformacaoTecnica($arrInformacoesTecnicas);
		}
		echo Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
	}
	
	public function pesquisaTiposConhecimentoAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		// Recupera os parametros enviados por get
		$cd_projeto = $this->_request->getParam('cd_projeto');
		$arrConhecimento = $this->objConhecimento->getTipoConhecimentoNaoUtilizado($cd_projeto);
		$arrComboConhecimentoNaoUtilizado = "";
		foreach($arrConhecimento as $key=>$value){
			$arrComboConhecimentoNaoUtilizado .= "<option value=\"{$value['codigo_conhecimento']}\">{$value['desc_tipo_conhecimento']}</option>";
		}

		$arrConhecimentoUtilizado = $this->objConhecimento->getTipoConhecimentoUtilizado($cd_projeto);
		$arrComboConhecimentoUtilizado = "";
		foreach($arrConhecimentoUtilizado as $chave=>$valor){
			$arrComboConhecimentoUtilizado .= "<option value=\"{$valor['codigo_conhecimento']}\">{$valor['desc_tipo_conhecimento']}</option>";
		}
		$retornaOsDois = array($arrComboConhecimentoNaoUtilizado,$arrComboConhecimentoUtilizado);
		
		echo Zend_Json_Encoder::encode($retornaOsDois);
	}
	
	public function pesquisaTiposConhecimentoEspecificoAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_projeto           = $this->_request->getParam('cd_projeto');
		$cd_tipo_conhecimento = $this->_request->getParam('cd_tipo_conhecimento');
		
		$arrConhecimento = $this->objConhecimento->getTipoConhecimentoNaoUtilizado($cd_projeto,$cd_tipo_conhecimento);
		$arrComboConhecimentoNaoUtilizado = "";
		foreach($arrConhecimento as $key=>$value){
			$arrComboConhecimentoNaoUtilizado .= "<option value=\"{$value['codigo_conhecimento']}\">{$value['desc_tipo_conhecimento']}</option>";
		}

		$arrConhecimentoUtilizado = $this->objConhecimento->getTipoConhecimentoUtilizado($cd_projeto,$cd_tipo_conhecimento);
		$arrComboConhecimentoUtilizado = "";
		foreach($arrConhecimentoUtilizado as $chave=>$valor){
			$arrComboConhecimentoUtilizado .= "<option value=\"{$valor['codigo_conhecimento']}\">{$valor['desc_tipo_conhecimento']}</option>";
		}
		$retornaOsDois = array($arrComboConhecimentoNaoUtilizado,$arrComboConhecimentoUtilizado);
		
		echo Zend_Json_Encoder::encode($retornaOsDois);
	}
	
	public function associaTipoConhecimentoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post			 = $this->_request->getPost();
		$cd_projeto		 = $post['cd_projeto'];
		$arrConhecimento = Zend_Json_Decoder::decode( str_ireplace("\\","",$post['conhecimento']) );
		
		foreach($arrConhecimento as $conhecimento) {
			$arrDados                   = explode('|',$conhecimento);
			$novo                       = $this->objConhecimentoProjeto->createRow();
			$novo->cd_tipo_conhecimento = (int)trim($arrDados[0]);
			$novo->cd_conhecimento      = (int)trim($arrDados[1]);
			$novo->cd_projeto           = $cd_projeto;
			$novo->save();
		}
	}
	
	public function desassociaTipoConhecimentoProjetoAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post			 = $this->_request->getPost();
		$cd_projeto		 = $post['cd_projeto'];
		$arrConhecimento = Zend_Json_Decoder::decode( str_ireplace("\\","",$post['conhecimento2']) );
		
		foreach ($arrConhecimento as $conhecimento) {
			$arrDados = explode('|',$conhecimento);
			$where    = "cd_projeto={$cd_projeto} and cd_tipo_conhecimento={$arrDados[0]} and cd_conhecimento={$arrDados[1]}";
			$this->objConhecimentoProjeto->delete($where);
		}
	}
}