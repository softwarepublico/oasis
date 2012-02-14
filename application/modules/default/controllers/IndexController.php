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

class IndexController extends Base_Controller_Action
{
	public function indexAction()
	{
        $this->_installation();
        
        if(array_key_exists('oasis_logged',$_SESSION)){
            if ($_SERVER['HTTP_HOST'] == $_SESSION['oasis_logged'][0]['host'] ) {
                if(count($_SESSION) > 0){                
                    if($_SESSION['oasis_logged'][0]['st_nova_senha'] == "S"){
                        $this->_redirect("/auth/logout");
                    } else {
                        $this->_redirect("/inicio");
                    }
                }
            }
        }
	    $errorMessage = $this->_request->getParam('error');

	    if (is_null($errorMessage)) {
	        $errorMessage = "";
	    } else {

	        switch($errorMessage){
				case '1'://Erro de senha ou de usu�rio
					$errorMessage = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_SENHA_INVALIDOS');
					break;
				case '2'://Erro de usuário sem perfil
					$errorMessage = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_SEM_PERFIL');
					break;
				case '3'://Erro de usuário sem contrato
					$errorMessage = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_SEM_CONTRATO');
					break;
				case '4'://erro de usuário sem objeto
					$errorMessage = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_SEM_OBJETO');
					break;
				case '5'://Usuário inativo no sistema
					$errorMessage = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_INATIVO');
					break;
				case '6':
					$errorMessage = Base_Util::getTranslator('L_MSG_ALERT_SESSAO_EXPIROU');
					break;
				case '7':
					$errorMessage = Base_Util::getTranslator('L_MSG_ALERT_NECESSARIO_USUARIO_SENHA');
					break;
                case '8':
                    $errorMessage = Base_Util::getTranslator('L_MSG_ALERT_INFORMACOES_INCORRETAS');
                case '9':
                    $errorMessage = base64_decode($this->_request->getParam('ldap_error'));
					break;
			}
	    }
        
	    $this->view->errorMessage = $errorMessage;
	}

    private function _installation()
    {
        if(K_INSTALL == "N"){
            $this->_redirect("/install");
        }
    }
}