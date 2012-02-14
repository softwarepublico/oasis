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

class PapelProfissionalController extends Base_Controller_Action 
{
	private $objPapelProfissional;
	
	public function init()
	{
		parent::init();
		$this->objPapelProfissional = new PapelProfissional($this->_request->getControllerName());
	}

	public function indexAction()
	{}	

    public function gridPapelProfissionalAction()
    {
        $this->_helper->layout->disableLayout();
        
        $cd_area_atuacao_ti = $this->_request->getPost('cd_area_atuacao_ti',null);
        
        $rowSet             = $this->objPapelProfissional->pesquisaPapelProfissionalAreaAtuacao($cd_area_atuacao_ti);
        $this->view->res    = $rowSet;
    }
	
    public function recuperaPapelProfissionalAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
		
        $cd_papel_profissional = $this->_request->getPost('cd_papel_profissional',null);
		if( is_null($cd_papel_profissional) ){
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ALERT_ACESSO_NEGADO'),'tipo'=>'3')));
		}
		$result = false;
		if( !empty($cd_papel_profissional) ) {
            $rs = $this->objPapelProfissional->find($cd_papel_profissional)->current();
            if( !is_null($rs) ){
            	$result = $rs->toArray();
            }
		} else {
		    die(Zend_Json_Encoder::encode(array('msg'=>Base_Util::getTranslator('L_MSG_ERRO_RECUPERAR_REGISTRO'),'tipo'=>'3')));
		}
		
        echo Zend_Json_Encoder::encode($result);
    }

	public function salvarPapelProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData  = $this->_request->getPost();
		
		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			
			if(!empty($formData['cd_papel_profissional'])) {
				$novo             = $this->objPapelProfissional->fetchRow("cd_papel_profissional= {$formData['cd_papel_profissional']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo             				= $this->objPapelProfissional->createRow();
				$novo->cd_papel_profissional	= $this->objPapelProfissional->getNextValueOfField('cd_papel_profissional');
				$arrResult['msg']				= Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
			
			$novo->cd_area_atuacao_ti    = $formData['cd_area_atuacao_papel_profissional'];
			$novo->tx_papel_profissional = $formData['tx_papel_profissional'];
			
			$novo->save();
            $db->commit();
			
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$objPerfilProfissionalPapelProfissional = new PerfilProfissionalPapelProfissional($this->_request->getControllerName());
		
		$comAssociacao = false;
		
		$cd_papel_profissional = (int)$this->_request->getParam('cd_papel_profissional');
		
		//resultset de todos os papeis profissional associados ao perfil profissional a ser excluido
		$arrAssociacao = $objPerfilProfissionalPapelProfissional->getAssociacaoPapelProfissional( $cd_papel_profissional );

		if(count($arrAssociacao) > 0){
			$comAssociacao = true;
			$retorno['msg' ] = $this->montaMensagemPerfilPapelProfissional($arrAssociacao);
			$retorno['tipo'] = 2;
		}else{
			if ($this->objPapelProfissional->delete("cd_papel_profissional={$cd_papel_profissional}")) {
				$retorno['msg' ] = Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
				$retorno['tipo'] = 1;
			} else {
				$retorno['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
				$retorno['tipo'] = 3;
			}
		}
		echo Zend_Json::encode(array($comAssociacao, $retorno));
	}
	
	private function montaMensagemPerfilPapelProfissional($arrAssociacao)
	{
		$msg 	   = '';
		$qtdPerfil = count($arrAssociacao);
		$count     = 1;
		
		$msg .= "<div style=\"margin-left: 23px;\">";
		if($qtdPerfil <= 10){
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_PERFIL_ASSOCIADO',$qtdPerfil);
		}else{
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_PERFIL_ASSOCIADO_10',$qtdPerfil);
		}
		
		foreach($arrAssociacao as $perfil){
			if($count > 10){
				break;
			}
			$msg .= "&rArr; ".Base_Util::getTranslator('L_VIEW_PAPEL').": <b>{$perfil['tx_perfil_profissional']}</b><br/>";
			$count++;
		}
		return $msg .= "</div>";	
	}
}
