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

class MensageriaController extends Base_Controller_Action
{
	private $mensageria;
	private $cd_mensageria;
	private $objProfissionalObjetoContrato;
	private $objProfissionalMensageria;
	
	public function init()
	{
		parent::init();
		$this->mensageria 					 = new Mensageria($this->_request->getControllerName());
		$this->objProfissionalObjetoContrato = new ProfissionalObjetoContrato($this->_request->getControllerName());
		$this->objProfissionalMensageria	 = new ProfissionalMensageria($this->_request->getControllerName());
	}

	public function indexAction(){}

	public function mensagensAction(){}
	
	public function mensagemAction(){}
	
	/**
	 * Método que salva uma mensagem
	 */
	public function salvarMensagemAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$arrDados = $this->_request->getPost();
		
		$arrResult = array('error'=>false, 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));
		$db = Zend_Registry::get('db');
		$db->beginTransaction();
        try{
        	
			$erro = false;
			$erro = (!$erro) ? $this->insertMensagem($arrDados)				: true;
			$erro = (!$erro) ? $this->insertMensagemProfissional($arrDados)	: true;
	
        	if(!$erro){
	        	$db->commit();
	        }else{
	        	$db->rollBack();
	        }
        }catch(Base_Exception_Alert $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'	  ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'	  ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'	  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}
	
	private function insertMensagem(array $arrDados )
	{
		$erro = false;
		
		$this->cd_mensageria = $this->mensageria->getNextValueOfField('cd_mensageria');
		
		$novo 			       	= $this->mensageria->createRow();
		$novo->cd_mensageria	= $this->cd_mensageria; 
		$novo->cd_objeto		= $arrDados['cd_objeto_mensageria'];
		$novo->cd_perfil		= ($arrDados['cd_perfil_mensageria'] == -1) ? null : $arrDados['cd_perfil_mensageria'];
		$novo->dt_postagem		= (!empty ($arrDados['dt_postagem']    )) ? 
                                        new Zend_Db_Expr("{$this->mensageria->to_timestamp("'{$arrDados['dt_postagem']} 00:00:00'", 'DD/MM/YYYY HH24:MI:SS')}") : null;
		$novo->dt_encerramento	= (!empty ($arrDados['dt_encerramento'])) ? 
                                        new Zend_Db_Expr("{$this->mensageria->to_timestamp("'{$arrDados['dt_encerramento']} 23:59:59'", 'DD/MM/YYYY HH24:MI:SS')}") : null;
		$novo->tx_mensagem		= $arrDados['tx_mensagem'];
		
		if( !$novo->save()) {
			throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));
			$erro = true;
		}
		return $erro;
	}
	
	private function insertMensagemProfissional(array $arrDados )
	{
		$erro = false;
		
		$arrProfissional = $this->getProfissionalObjetoPerfil($arrDados['cd_objeto_mensageria'], $arrDados['cd_perfil_mensageria']);

		if(count($arrProfissional) == 0){
			throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_NAO_EXISTE_PROFISSIONAL_PARA_PERFIL'));
			$erro = true;
		}else{
			foreach( $arrProfissional as $res ){
				$novo					= $this->objProfissionalMensageria->createRow();
				$novo->cd_profissional	= $res['cd_profissional'];
				$novo->cd_mensageria 	= $this->cd_mensageria;
				
				if(!$novo->save()){
					throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ASSOCIAR_MENSAGEM_PROFISSIONAL'));
					$erro = true;
				}
			}
		}
		return $erro;
	}
	
	private function getProfissionalObjetoPerfil($cd_objeto, $cd_perfil)
	{
		$cd_perfil = ($cd_perfil == -1 ) ? null : $cd_perfil;
		$arrProfissional = $this->objProfissionalObjetoContrato->getProfissionalObjetoPerfil($cd_objeto,$cd_perfil);
		return $arrProfissional;
	}
	
	/**
	 * Método que altera os dados de uma mensagem
	 */
	public function alterarMensagemAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();

		$arrResult = array('error'=>false, 'errorType'=>'', 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO'));

		$db = Zend_Registry::get('db');
		$db->beginTransaction();

        try{
			$this->cd_mensageria = $arrDados['cd_mensageria'];

			$erro = false;
			$erro = (!$erro) ? $this->updateMensagem($arrDados)            : true;
			$erro = (!$erro) ? $this->excluiAssociacoesMensagem()	       : true;
			$erro = (!$erro) ? $this->insertMensagemProfissional($arrDados): true;

        	if(!$erro){
	        	$db->commit();
	        }else{
	        	$db->rollBack();
	        }
        }catch(Base_Exception_Alert $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 2;
            $arrResult['msg'	  ] = $e->getMessage();
        }catch(Base_Exception_Error $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'	  ] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'    ] = true;
            $arrResult['errorType'] = 3;
            $arrResult['msg'	  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	private function updateMensagem( array $arrDados )
	{
		$erro = false;
		$arrUpdate['cd_objeto'		]	= $arrDados['cd_objeto_mensageria'];
		$arrUpdate['cd_perfil'		]	= ($arrDados['cd_perfil_mensageria'] == -1) ? null : $arrDados['cd_perfil_mensageria'];
		$arrUpdate['dt_postagem'	]	= (!empty ($arrDados['dt_postagem'    ])) ? 
                                                new Zend_Db_Expr("{$this->mensageria->to_timestamp("'{$arrDados['dt_postagem'    ]} 00:00:00'", 'DD/MM/YYYY HH24:MI:SS')}") : null;
		$arrUpdate['dt_encerramento']	= (!empty ($arrDados['dt_encerramento'])) ? 
                                                new Zend_Db_Expr("{$this->mensageria->to_timestamp("'{$arrDados['dt_encerramento']} 23:59:59'", 'DD/MM/YYYY HH24:MI:SS')}") : null;
		$arrUpdate['tx_mensagem'	]	= $arrDados['tx_mensagem'];

		if( !$this->mensageria->update( $arrUpdate, "cd_mensageria = {$this->cd_mensageria}" ) ){
			throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_REGISTRO'));
			$erro = true;
		}
		return $erro;
	}

	private function excluiAssociacoesMensagem()
	{
		$erro = false;
		if( !$this->objProfissionalMensageria->delete("cd_mensageria = {$this->cd_mensageria}" ) ){
			throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_ATUALIZAR_PROFISSIONAL_MENSAGEM'));
			$erro = true;
		}
		return $erro;
	}

	/**
	 * Método que excluir a mensagem
	 */
	public function excluirMensagemAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$this->cd_mensageria	= $this->_request->getParam('cd_mensageria');
		$arrResult				= array('error'=>false, 'typeMsg'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));

		$db = Zend_Registry::get('db');

		$db->beginTransaction();
        try{
			$erro = false;
			$erro = (!$erro) ? $this->deleteDependenciaMensagem()	: true;
			$erro = (!$erro) ? $this->deleteMensagem()				: true;
        	if(!$erro){
	        	$db->commit();
	        }else{
	        	$db->rollBack();
	        }
        }catch(Base_Exception_Error $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'	] = $e->getMessage();
        }catch(Zend_Exception $e){
            $arrResult['error'  ] = true;
            $arrResult['typeMsg'] = 3;
            $arrResult['msg'	] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
        }
        echo Zend_Json_Encoder::encode($arrResult);
	}

	private function deleteDependenciaMensagem()
	{
		$erro = false;
		if(!$this->objProfissionalMensageria->delete("cd_mensageria = {$this->cd_mensageria}")){
			throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXCLUIR_DEPENDENCIA_MENSAGEM'));
			$erro = true;
		}
		return $erro;
	}

	private function deleteMensagem()
	{
		$erro = false;
		if(!$this->mensageria->delete("cd_mensageria = {$this->cd_mensageria}")){
			throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO'));
			$erro = true;
		}
		return $erro;
	}
	
	/**
	 * Método que recupera os dados de um treinamento expecífico
	 */
	public function recuperaDadosMensagemAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$arrDados = $this->_request->getPost();
		
		$rowSet = $this->mensageria->getMensagemEspecifica($arrDados['cd_mensageria']);

        $rowSet->dt_postagem     = Base_Util::converterDate(substr($rowSet->dt_postagem, 0, 10), 'YYYY-MM-DD', 'DD/MM/YYYY');
        $rowSet->dt_encerramento = Base_Util::converterDate(substr($rowSet->dt_encerramento, 0, 10), 'YYYY-MM-DD', 'DD/MM/YYYY');

		echo Zend_Json::encode($rowSet->toArray());
	}
	
	/**
	 * Método que recupera os dados para montar a grid
	 */
	public function gridMensageriaMensagensAction()
	{
		$this->_helper->layout->disableLayout();
		
		$mes = $this->_request->getParam('mes');
		$ano = $this->_request->getParam('ano');
		$mes = ($mes == 0)?date('m'):$mes;
		$ano = ($ano == 0)?date('Y'):$ano;
		$mes = substr("00".$mes,-2);
		
		$res			 = $this->mensageria->getAllMensagens($mes,$ano, true);
		$this->view->res = $res;
	}
}