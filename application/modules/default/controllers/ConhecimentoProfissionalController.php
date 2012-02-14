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

class ConhecimentoProfissionalController extends Base_Controller_Action 
{
	private $objProfissional;
	private $objTipoConhecimento;
	private $objConhecimento;
	private $objProfissionalConhecimento;
	
	public function init()
	{
		parent::init();
		$this->objProfissional             = new Profissional($this->_request->getControllerName());
		$this->objTipoConhecimento         = new TipoConhecimento($this->_request->getControllerName());
		$this->objConhecimento             = new Conhecimento($this->_request->getControllerName());
		$this->objProfissionalConhecimento = new ProfissionalConhecimento($this->_request->getControllerName());
	}

	public function indexAction()
	{
	    $this->view->arrComboProfissional     = $this->objProfissional->getProfissional(true);
	    $this->view->arrComboTipoConhecimento = $this->objTipoConhecimento->getComboTipoConhecimento(true);
	    $this->view->arrComboConhecimento1    = array();
	    $this->view->arrComboConhecimento2    = array();
	    
	    $this->initView();
	}	

	public function pesquisaConhecimentoAction()
	{
		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        // Post...
		$cd_profissional      = $this->_request->getPost('cd_profissional',null);
		$cd_tipo_conhecimento = $this->_request->getPost('cd_tipo_conhecimento',null);
		
		if( is_null($cd_profissional) ){
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}

		$arrComboConhecimentoNaoUtilizado = "";
		$arrComboConhecimentoUtilizado = ""; 

		$rowSetConhecimento = $this->objProfissionalConhecimento->getConhecimentoProfissional($cd_profissional,$cd_tipo_conhecimento,false);
		foreach($rowSetConhecimento as $row){
//			$arrComboConhecimentoNaoUtilizado .= "<option value=\"{$row->cd_conhecimento}\">{$row->tx_conhecimento}</option>";
            $cdconhecimento = $row->cd_tipo_conhecimento.'.'.$row->cd_conhecimento;
            $txconhecimento = $row->tx_tipo_conhecimento.' - '.$row->tx_conhecimento;
			$arrComboConhecimentoNaoUtilizado .= "<option value=\"{$cdconhecimento}\">{$txconhecimento}</option>";
		}

        $rowSetConhecimentoUtilizado = $this->objProfissionalConhecimento->getConhecimentoProfissional($cd_profissional,$cd_tipo_conhecimento,true);
		foreach($rowSetConhecimentoUtilizado as $row){
//			$arrComboConhecimentoUtilizado .= "<option value=\"{$row->cd_conhecimento}\">{$row->tx_conhecimento}</option>";
            $cdconhecimento = $row->cd_tipo_conhecimento.'.'.$row->cd_conhecimento;
            $txconhecimento = $row->tx_tipo_conhecimento.' - '.$row->tx_conhecimento;
			$arrComboConhecimentoUtilizado .= "<option value=\"{$cdconhecimento}\">{$txconhecimento}</option>";
		}
		$retornaOsDois = array($arrComboConhecimentoNaoUtilizado,$arrComboConhecimentoUtilizado);

		echo Zend_Json_Encoder::encode($retornaOsDois);
	}
/*	
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
*/
	public function associaConhecimentoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		$cd_profissional = $post['cd_profissional'];
		$arrConhecimento = Zend_Json_Decoder::decode($post['conhecimento']);
		foreach($arrConhecimento as $conhecimento) {
			$arrDados                   = explode('.',$conhecimento);
			$novo                       = $this->objProfissionalConhecimento->createRow();
			$novo->cd_tipo_conhecimento = (int)$arrDados[0];
			$novo->cd_conhecimento      = (int)$arrDados[1];
			$novo->cd_profissional      = $cd_profissional;
			$novo->save();
		}
	}
	
	public function desassociaConhecimentoProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		$cd_profissional = $post['cd_profissional'];
		$cd_tipo_conhecimento = $post['cd_tipo_conhecimento'];
		$arrConhecimento = Zend_Json_Decoder::decode($post['conhecimento']);

		foreach ($arrConhecimento as $conhecimento) {
			$arrDados = explode('.',$conhecimento);
			$where    = "cd_profissional = {$cd_profissional} and cd_tipo_conhecimento = {$arrDados[0]} and cd_conhecimento = {$arrDados[1]}";
			$this->objProfissionalConhecimento->delete($where);
		}
	}
}