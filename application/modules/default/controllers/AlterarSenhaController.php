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

class AlterarSenhaController extends Base_Controller_Action
{
	private $objProfissional;
	
	public function init()
	{
		parent::init();
		$this->objProfissional = new Profissional($this->_request->getControllerName());
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_ALTERACAO_SENHA'));

		if(count($_SESSION) > 0 && isset ($_SESSION['oasis_logged'])){
			$tx_nome_conhecido = $_SESSION['oasis_logged'][0]['tx_nome_conhecido'];
			
			$this->view->tx_nome_usuario        = ($tx_nome_conhecido)?$tx_nome_conhecido:$_SESSION['oasis_logged'][0]['tx_profissional'];
			$this->view->tx_email_institucional = $_SESSION['oasis_logged'][0]['tx_email_institucional'];
			$this->view->nova_senha             = $_SESSION['oasis_logged'][0]['st_nova_senha'];
		}
		if(count($_SESSION) > 0){
			if(array_key_exists('oasis_logged', $_SESSION)){
				if($_SESSION['oasis_logged'][0]['st_nova_senha'] == 'S'){
					session_destroy();
					$_SESSION = array();
				}
			}
		}
	}
	
	public function salvarAlterarSenhaAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		//variaveis recebidas do ajax
		$tx_profissional     = $this->_request->getParam('tx_email_institucional');
		$tx_senha            = $this->_request->getParam('tx_senha');
		$tx_nova_senha_ajax  = $this->_request->getParam('tx_nova_senha');
		$tx_senha_confirmada = $this->_request->getParam('tx_senha_confirmada');
		//criptografando senha
		$tx_senha      = md5($tx_senha);
		$tx_nova_senha = md5($tx_nova_senha_ajax); 
		//condição que verifica se a nova senha e igual a senha confirmada.
		if($tx_nova_senha_ajax == $tx_senha_confirmada){
			//verifica se o e-mail e corporativo
			$email = strpos($tx_profissional,'@');
			if(!$email){
				$tx_profissional = $tx_profissional.K_DOMINIO_EMAIL_PADRAO; 
			}
			//recupera o profissional
			$arrProfissional = $this->validaUsuario($tx_profissional);
			//condição para alterar senha do usuário JÁ CADASTRADO
			if($arrProfissional[0]['condicao'] == 3){
				if($arrProfissional[0]['tx_senha'] == $tx_senha){
					$return = $this->objProfissional->alteraSenhaProfissional($arrProfissional[0]['cd_profissional'],$tx_nova_senha);
					if($return){
						echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERAR_SENHA');
					} else {
						echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_SENHA');
					}
				} else {
					echo Base_Util::getTranslator('L_MSG_ALERT_SENHA_INFORMADA_NAO_CONFERE_SENHA_ATUAL');
				}
			} else if($arrProfissional[0]['condicao'] == 2){// condição altera senha 1º acesso do usuário
				$return = $this->objProfissional->alteraSenhaProfissional($arrProfissional[0]['cd_profissional'], $tx_nova_senha);
				if($return){
                    echo Base_Util::getTranslator('L_MSG_SUCESS_ALTERAR_SENHA');
				} else {
                    echo Base_Util::getTranslator('L_MSG_ERRO_ALTERAR_SENHA');
				}
			} else {
                echo Base_Util::getTranslator('L_MSG_ALERT_USUARIO_NAO_LOCALIZADO');
			}
		} else {
            echo Base_Util::getTranslator('L_MSG_ERRO_CONFIRMACAO_SENHA');
		}
	}
	
	protected function validaUsuario($tx_profissional)
    {
		$arrProfissional = $this->objProfissional->validaUsuarioCad($tx_profissional);
		if($arrProfissional){
			if(trim($arrProfissional[0]['tx_senha']) != "" && ($arrProfissional[0]['st_nova_senha'] != "S")){
				$arrProfissional[0]['condicao'] = 3;//retorno para alterar a senha do usuário
			} else {
				$arrProfissional[0]['condicao'] = 2;//retorno para alterar só a senha 1º acesso do usuário
			}
		} else {
			$arrProfissional[0]['condicao'] = 1;//retorno de erro
		}
		return $arrProfissional; 
	}
}
