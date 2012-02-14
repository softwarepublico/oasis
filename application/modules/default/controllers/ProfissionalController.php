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

class ProfissionalController extends Base_Controller_Action 
{
	private $profissional;
	
	public function init()
	{
		parent::init();
		$this->profissional = new Profissional($this->_request->getControllerName());
	}

	public function indexAction()
	{}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$formData = $this->_request->getPost();

		$arrResult = array('erro'=>false,'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO'));
		$erro = false;
		try {
			
			$this->profissional->getAdapter()->beginTransaction();
			
            $formData['tx_email_institucional'] = $this->_validateEmailInstitucional( $formData['tx_email_institucional'] );
            
			if(!empty($formData['cd_profissional'])) {
				$novo          = $this->profissional->fetchRow( array("cd_profissional = ?"=> $formData['cd_profissional']) );
				$arrResult['msg'] = Base_Util::getTranslator('L_MSG_SUCESS_ALTERACAO');
			} else {
				if(!$this->_isEmailCadastrado( $this->toLower($formData['tx_email_institucional']) )){
					$novo                  = $this->profissional->createRow();
					$novo->cd_profissional = $this->profissional->getNextValueOfField('cd_profissional');
				}
			}

			if (!$erro) {
				$dt_nascimento_profissional       = ($formData['dt_nascimento_profissional'])  ? new Zend_Db_Expr("{$this->profissional->to_date("'{$formData['dt_nascimento_profissional']}'", 'DD/MM/YYYY')}"):null;
				$dt_saida_profissional            = ($formData['dt_saida_profissional'])	   ? new Zend_Db_Expr("{$this->profissional->to_date("'{$formData['dt_saida_profissional']}'", 'DD/MM/YYYY')}"):null;
				$dt_inicio_trabalho			      = ($formData['dt_inicio_trabalho'])		   ? new Zend_Db_Expr("{$this->profissional->to_date("'{$formData['dt_inicio_trabalho']}'", 'DD/MM/YYYY')}"):null;

				$novo->cd_empresa                 = $formData['cd_empresa'];
				$novo->tx_profissional            = $this->toUpper($formData['tx_profissional']);
				$novo->cd_relacao_contratual      = $formData['cd_relacao_contratual'];
				$novo->tx_nome_conhecido          = $this->toUpper($formData['tx_nome_conhecido']);
				$novo->tx_telefone_residencial    = ($formData['tx_telefone_residencial'])?$formData['tx_telefone_residencial']:null;
				$novo->tx_celular_profissional    = ($formData['tx_celular_profissional'])?$formData['tx_celular_profissional']:null;
				$novo->tx_ramal_profissional      = $formData['tx_ramal_profissional'];
				$novo->tx_endereco_profissional   = ($formData['tx_endereco_profissional'])?$formData['tx_endereco_profissional']:null;
				$novo->dt_nascimento_profissional = $dt_nascimento_profissional;
				$novo->dt_inicio_trabalho         = $dt_inicio_trabalho;
				$novo->dt_saida_profissional      = $dt_saida_profissional;
				$novo->tx_email_institucional     = $this->toLower($formData['tx_email_institucional']);
				$novo->tx_email_pessoal           = ($formData['tx_email_pessoal'])? $this->toLower($formData['tx_email_pessoal']):null;
                
                if( 'N' === K_LDAP_AUTENTICATE ){
                    if($formData['st_nova_senha']){
                        $novo->st_nova_senha = "S";
                        $novo->tx_senha      = md5(SYSTEM_NAME);
                    } else {
                        $novo->st_nova_senha = null;
                    }
                }else{
                    $novo->st_nova_senha = "N";
                    $novo->tx_senha      = md5(SYSTEM_NAME);
                }

				$novo->cd_perfil                  = $formData['cd_perfil'];
				$novo->st_inativo                 = ($formData['st_inativo'])?"S":null;
				$novo->st_dados_todos_contratos   = ($formData['st_dados_todos_contratos'])?"S":null;

				$novo->save();
				$this->profissional->getAdapter()->commit();
			}
		} catch(Base_Exception_Alert $e) {
            $this->profissional->getAdapter()->rollBack();
			$arrResult['erro'] = true;
			$arrResult['type'] = 2;
			$arrResult['msg' ] = $e->getMessage();
		} catch(Base_Exception_Error $e) {
            $this->profissional->getAdapter()->rollBack();
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = $e->getMessage();
		} catch(Exception $e) {
            $this->profissional->getAdapter()->rollBack();
			$arrResult['erro'] = true;
			$arrResult['type'] = 3;
			$arrResult['msg' ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
		}
		echo Zend_Json_Encoder::encode($arrResult);
	}
	
	public function recuperaProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_profissional = $this->_request->getParam('cd_profissional');
	
		$res = $this->profissional->getDadosProfissional($cd_profissional);

        $res[0]['dt_inicio_trabalho']         = Base_Util::converterDate($res[0]['dt_inicio_trabalho'],'YYYY-MM-DD','DD/MM/YYYY');
        $res[0]['dt_nascimento_profissional'] = (!is_null($res[0]['dt_nascimento_profissional'])) ? Base_Util::converterDate($res[0]['dt_nascimento_profissional'],'YYYY-MM-DD','DD/MM/YYYY') : "";
        $res[0]['dt_saida_profissional']      = (!is_null($res[0]['dt_saida_profissional'     ])) ? Base_Util::converterDate($res[0]['dt_saida_profissional'],'YYYY-MM-DD','DD/MM/YYYY')      : "";

		echo Zend_Json::encode($res);
	}
	
	public function excluirAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		
		$cd_profissional = (int)$this->_request->getParam('cd_profissional');
		
		if($this->profissional->delete("cd_profissional = {$cd_profissional}")){
			echo Base_Util::getTranslator('L_MSG_SUCESS_EXCLUSAO');
		} else {
			echo Base_Util::getTranslator('L_MSG_ERRO_EXCLUSAO_REGISTRO');
		}
	}

	private function _isEmailCadastrado( $tx_email_institucional )
    {
        $res    = $this->profissional->fetchRow(array("tx_email_institucional = ?"=> $tx_email_institucional));

        if( count($res) > 0 ){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_PROFISSIONAL_CADASTRATO'));
        }
        return false;
    }
    
    private function _validateEmailInstitucional( $email )
    {
        if( 'S' === K_LDAP_AUTENTICATE ){
            //Realiza um filtro nos dados recebidos do post
            $filter              = new Zend_Filter_StripTags();
            $email = $filter->filter($email);
            if (strpos($email, "@") === false) {
                throw new Base_Exception_Alert( Base_Util::getTranslator( 'L_MSG_ALERT_DOMINIO_EMAIL_INSTITUCIONAL_OBRIGATORIO' ) );
            }else{
                if(!Zend_Validate::is($email,'EmailAddress')){
                    throw new Base_Exception_Alert( Base_Util::getTranslator( 'L_MSG_ALERT_EMAIL_INSTITUCIONAL_INVALIDO' ) );
                }
                $dominioMail = trim(substr($email, strpos( $email, '@' )));
                if( $dominioMail != K_DOMINIO_EMAIL_PADRAO){
                    throw new Base_Exception_Alert( Base_Util::getTranslator( 'L_MSG_ALERT_EMAIL_INSTITUCIONAL_FORA_PADRAO_CONFIGURADO', K_DOMINIO_EMAIL_PADRAO ) );
                }
            }
        }
        return $email;
    }
}