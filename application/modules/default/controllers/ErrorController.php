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

class ErrorController extends Zend_Controller_Action 
{
	public function errorAction()
	{
		$errors = $this->_request->getParam('error_handler');
		$e = $errors->exception;
		
        $error = strpos(strtolower($e->getMessage()),'dapter');
        if(!$error){
            $error = strpos($e->getMessage(),'host');
            if(!$error){
                $error = strpos($e->getMessage(),'password');
                if(!$error){
                    $error = strpos($e->getMessage(),'database');
                    if(!$error){
                        $error = strpos($e->getMessage(),'driver');
                        if($error){
                            $msg = Base_Util::getTranslator('L_MSG_ERRO_BANCO_DADOS_SEM_DRIVER_INSTALADO');
                        } else {
                            $msg = $e->getMessage();
                        }
                    } else {
                        $msg = Base_Util::getTranslator('L_MSG_ERRO_BANCO_DADOS_INEXISTENTE');
                    }
                } else {
                    $msg = Base_Util::getTranslator('L_MSG_ERRO_USUARIO_SENHA_ACESSO_INVALIDO');
                }
            } else {
                $msg = Base_Util::getTranslator('L_MSG_ERRO_NOME_SERVIDOR_INVALIDO');
            }
         } else {
            $msg = Base_Util::getTranslator('L_MSG_ERRO_BANCO_DADOS_ADAPTER_NAO_ESPECIFICADO');
        }

		//retira possíveis quebras de linha
		$msgLog = str_replace(array("\n\r","\n","\r")," ",$msg);
		$msgLog = str_replace("
		"," ",$msgLog);

		$objLog = new Base_Log();
		$objLog->escreveLogError($e->getTrace(), trim($msgLog));
		
		$this->view->errorMessage = $msg;
	}
}