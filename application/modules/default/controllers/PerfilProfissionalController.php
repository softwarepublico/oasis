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

class PerfilProfissionalController extends Base_Controller_Action
{
    private $perfilProfissional;

    public function init()
    {
        parent::init();
        $this->perfilProfissional = new PerfilProfissional($this->_request->getControllerName());
    }

    public function indexAction(){}

    public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData  = $this->_request->getPost();
		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>'');
		try {
			$db = Zend_Registry::get('db');
			$db->beginTransaction();
			
			if(!empty($formData['cd_perfil_profissional'])) {
				$novo             = $this->perfilProfissional->fetchRow("cd_perfil_profissional = {$formData['cd_perfil_profissional']}");
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				$novo             				= $this->perfilProfissional->createRow();
				$novo->cd_perfil_profissional	= $this->perfilProfissional->getNextValueOfField('cd_perfil_profissional');
				$arrResult['msg']				= Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO');
			}
			
			$novo->cd_area_atuacao_ti				= $formData['cd_area_atuacao_perfil_profissional'];
			$novo->tx_perfil_profissional   		= $formData['tx_perfil_profissional'];
			
			$novo->save();
            $db->commit();
		} catch(Zend_Exception $e) {
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg'] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
            $db->rollBack();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}

	public function recuperaPerfilProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_perfil_profissional = $this->_request->getParam('cd_perfil_profissional');
		$res                    = $this->perfilProfissional->find($cd_perfil_profissional)->current()->toArray();
		
		echo Zend_Json::encode($res);
	}

	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$objPerfilProfissionalPapelProfissional = new PerfilProfissionalPapelProfissional($this->_request->getControllerName());
		$comAssociacao = false;
		
		$cd_perfil_profissional = (int)$this->_request->getParam('cd_perfil_profissional');
		
		//resultset de todos os papeis profissional associados ao perfil profissional a ser excluido
		$arrAssociacao = $objPerfilProfissionalPapelProfissional->getAssociacaoPerfilProfissional( $cd_perfil_profissional );

		if(count($arrAssociacao) > 0){
			$comAssociacao = true;
			$retorno['msg' ] = $this->montaMensagemPerfilPapelProfissional($arrAssociacao);
			$retorno['tipo'] = 2;
		}else{
			if ($this->perfilProfissional->delete("cd_perfil_profissional={$cd_perfil_profissional}")) {
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
		$msg 	 = '';
		$qtdPapel = count($arrAssociacao);
		$count   = 1;
		
		$msg .= "<div style=\"margin-left: 23px;\">";
		if($qtdPapel <= 10){
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_PERFIL_ASSOCIADO',$qtdPapel);
		}else{
			$msg .= Base_Util::getTranslator('L_MSG_ALERT_PERFIL_ASSOCIADO_10');
		}
		
		foreach($arrAssociacao as $papel){
			if($count > 10){
				break;
			}
			$msg .= "&rArr; ".Base_Util::getTranslator('L_VIEW_PAPEL').": <b>{$papel['tx_papel_profissional']}</b><br/>";
			$count++;
		}
		return $msg .= "</div>";	
	}

	public function gridPerfilProfissionalAction()
    {
        $this->_helper->layout->disableLayout();
        $cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao', 0);
        
        $rowSet          = $this->perfilProfissional->pesquisaPerfilProfissionalAreaAtuacao($cd_area_atuacao_ti);
        $this->view->res = $rowSet;
    }
    
    //comentado para ver se dá erro em algum lugar pois tirei o parametro do metod
//	public function gridPerfilProfissionalAction($cd_area_atuacao_ti = null)
//    {
//        if(is_null($cd_area_atuacao_ti)){
//            $this->_helper->layout->disableLayout();
//            // recupera o parametro
//            $cd_area_atuacao_ti = $this->_request->getParam('cd_area_atuacao', 0);
//        }
//
//        $rowSet          = $this->perfilProfissional->pesquisaPerfilProfissionalAreaAtuacao($cd_area_atuacao_ti);
//        $this->view->res = $rowSet;
//    }
    
    public function pesquisaPerfilProfissionalAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        
        $cd_objeto = (int)$this->_request->getParam('cd_objeto', 0);
        $cd_objeto = ($cd_objeto != -1)?$cd_objeto:0;

        $res = $this->perfilProfissional->getPerfilProfissional($cd_objeto);
        
        $strOptions = "";
        if(count($res) > 0){
	        foreach ($res as $cd_projeto_continuado => $tx_sigla_projeto_continuado) {
	            $strOptions .= "<option label=\"{$tx_sigla_projeto_continuado}\" value=\"{$cd_projeto_continuado}\">{$tx_sigla_projeto_continuado}</option>";            
	        }
        }
        echo $strOptions;
    }
}