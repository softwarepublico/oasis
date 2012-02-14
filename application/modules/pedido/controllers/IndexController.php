<?php
class Pedido_IndexController extends Base_Controller_ActionPedido
{
    private $_objUnidade;
    private $_objUsuario;

    public function init()
    {
        parent::init();
        Zend_Loader::loadClass('Unidade', Base_Util::baseUrlModule('default', 'models'));

        $this->_objUnidade = new Unidade();
        $this->_objUsuario = new UsuarioPedido();
    }

    public function indexAction()
    {
   		if(!isset($_SESSION['oasis_pedido'])){
			$this->_redirect("/{$this->_module}/index/login");
        }
        $this->_forward('index', 'pedido-solicitacao', 'pedido');
    }

    public function cadastroAction()
    {
        $form = new UsuarioPedidoForm();
        
        $form->getElement('cd_unidade_usuario')->addMultiOptions($this->_objUnidade->getUnidade(true));
        
        if('S' == K_LDAP_AUTENTICATE){
            $form->removeElement('tx_senha_acesso');
            $form->removeElement('tx_senha_acesso_confirma');
        }
        
        if($this->_request->isPost()){
            
            $form->populate( $this->_request->getPost() );
            
            $formData = $form->getValues();
            
            //Valida os dados necessários
            $validate = $this->_validaDadosCadastro($formData);
            
            if($validate){
                $arrResult = array('error'=>0, 'type'=>1, 'msg'=>Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO_USUARIO'));
                try{
                    $this->_objUsuario->getDefaultAdapter()->beginTransaction();

                    $this->_objUsuario->salvarUsuario($formData);
                    $this->_objUsuario->getDefaultAdapter()->commit();
                }catch(Base_Exception_Error $e){
                    $arrResult['error'] = 1;
                    $arrResult['type' ] = 3;
                    $arrResult['msg'  ] = $e->getMessage();
                    $this->_objUsuario->getDefaultAdapter()->rollBack();
                }catch(Exception $e){
                    $arrResult['error'] = 1;
                    $arrResult['type' ] = 3;
                    $arrResult['msg'  ] = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO'). $e->getMessage();
                    $this->_objUsuario->getDefaultAdapter()->rollBack();
                }
                $scriptInicial = "
                    $(document).ready(function(){
                        var error = '{$arrResult['error']}'
                        if(error == 1){
                            alertMsg('{$arrResult['msg']}',{$arrResult['type']});
                        }else{
                            $('#formCadastroUsuarioPedido :input').not('select,button').val('');
                            $('#formCadastroUsuarioPedido select').val('0');
                            alertMsg('{$arrResult['msg']}',{$arrResult['type']},function(){
                                window.location.href= systemName+'/'+systemNameModule+ '/index';
                            });
                        }
                    });
                ";
                $this->view->headScript()->appendScript($scriptInicial);
            }
        }
        $this->view->form = $form;
    }

    private function _validaDadosCadastro(&$formData)
    {
        //Condição que verifica dados obrigatórios
        $dadosVazio = false;
        foreach($formData as $key=>$value){
            if(trim($value) == ""){
                $dadosVazio = true;
                break;
            }
        }

        if($dadosVazio){
            $this->view->errorMessageCadastro = Base_Util::getTranslator('L_MSG_ALERT_CAMPO_OBRIGATORIO_NAO_PREENCHIDO');
            return false;
        }

        if( 'N' == K_LDAP_AUTENTICATE ){
            //Condição que verifica se a senha está confirmada corretamente
            if($formData['tx_senha_acesso'] !== $formData['tx_senha_acesso_confirma']){
                $this->view->errorMessageCadastro = Base_Util::getTranslator('L_MSG_ALERT_CONFIRMACAO_SENHA_INCORRETA');
                return false;
            }

            //Condição que valida o e-mail e a senha do usuário
            $arrDados = Base_Controller_Action_Helper_Util::validateInformation($formData, 'tx_email_institucional', 'tx_senha_acesso');
            if(count($arrDados) == 0){
                $this->view->errorMessageCadastro = Base_Util::getTranslator('L_MSG_ALERT_EMAIL_INVALIDO');
                return false;
            }
        }else{
            
            //Realiza um filtro nos dados recebidos do post
            $filter              = new Zend_Filter_StripTags();
            $formData['tx_email_institucional'] = $filter->filter($formData['tx_email_institucional']);
            if (strpos($formData['tx_email_institucional'], "@") === false) {
                $formData['tx_email_institucional'] = $formData['tx_email_institucional'] . K_DOMINIO_EMAIL_PADRAO;
            }
            
            if(!Zend_Validate::is($formData['tx_email_institucional'],'EmailAddress')){
                $this->view->errorMessageCadastro = 'Email informado é inválido';
                return false;
            }elseif( K_DOMINIO_EMAIL_PADRAO != trim(substr($formData['tx_email_institucional'], strpos( $formData['tx_email_institucional'], '@' ))) ){
                $this->view->errorMessageCadastro = 'Email informado não é um email institucional';
                return false;
            }
        }

        //Condição que verifica se o usuário já esta cadastrado
        $arrParam   = array('tx_email_institucional = ? '=>$formData['tx_email_institucional']);
        $objUsuario = $this->_objUsuario->getUsuario($arrParam);
        if($objUsuario->valid()){
            $this->view->errorMessageCadastro = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_CADASTRADO');
            return false;
        }

        return true;
    }

	public function loginAction()
    {
        $this->view->labelUserLogin = Base_Util::getTranslator('L_VIEW_EMAIL');
        $this->view->hasBtnCadastro = true;
        if('S' == K_LDAP_AUTENTICATE){
            $this->view->labelUserLogin = Base_Util::getTranslator('L_VIEW_USUARIO');
            $this->view->hasBtnCadastro = false;
        }
        
        if($this->_request->isPost()){
            //Obtém dados do formulário
            $formData = $this->_request->getPost();

            if(empty($formData['usuario']) && empty($formData['senha'])){
                $this->view->errorMessage = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_SENHA_OBRIGATORIO');
            } else {
                $tableName = KT_S_USUARIO_PEDIDO;
                if(!is_null(K_SCHEMA)){
                   $tableName = K_SCHEMA . "." . $tableName;
                }
                
                $auth = Zend_Auth::getInstance();
                if( 'S' === K_LDAP_AUTENTICATE ){
                    
                    $ldapConfig = Zend_Registry::get('config')->ldap;
                    $adapter    = new Zend_Auth_Adapter_Ldap($ldapConfig->toArray(),$formData['usuario'],$formData['senha']);
                    $result     = $auth->authenticate($adapter);
                    
                    if( !$result->isValid() ){
                        $arrError = $result->getMessages();
                        $this->view->errorMessage = $arrError[0];
                    }else{
                        $accountObject = $adapter->getAccountObject();
                        $arrData = $this->_helper->util->getUserAuthLdap( $accountObject, 'UsuarioPedido', 'tx_email_institucional','tx_senha_acesso' );
                        
                        if( true === $arrData['error'] ){
                            if($arrData['error_type'] == 1){ //usuário não cadastrado, redirecionar para cadastro
                                $scriptInicial = "
                                    $(document).ready(function(){
                                        alertMsg('{$arrData['error_msg']}',2,function(){
                                            window.location.href= systemName+'/'+systemNameModule+ '/index/cadastro';
                                        });
                                    });
                                ";
                                $this->view->headScript()->appendScript($scriptInicial);
                            }else{
                                $this->view->errorMessage = $arrData['error_msg'];
                            }
                        }else{
                            $this->_openSession($arrData['row']);
                        }
                    }
                }else{
                    //valida os dados do login
                    $formData = Base_Controller_Action_Helper_Util::validateInformation($formData,'usuario','senha');
                    //Condição que verifica se os dados estão corretos e validos
                    if(count($formData) > 0){
                        //Inicialica a autenticação do usuário
                        $authAdapter = $this->_helper->util->getAuthAdapter($formData, $tableName, 'tx_email_institucional', 'tx_senha_acesso');
                        $result      = $auth->authenticate($authAdapter);

                        if(!$result->isValid()){
                            $this->view->errorMessage = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_SENHA_INVALIDOS');
                        } else {
                            //Retira a senha para colocar na sessão
                            $data = $authAdapter->getResultRowobject(null,'tx_senha_acesso');
                            $this->_openSession($data);
                        }
                    } else {
                        $this->view->errorMessage = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_SENHA_INVALIDOS');
                    }
                }
            }
        }
        
        
        
        
//		$this->view->unidades = $this->_objUnidade->getUnidade(true);
	}

    private function _openSession($objData)
    {
        foreach($objData as $key=>$value){
            $_SESSION['oasis_pedido'][$key] = $value;
        }
        $this->_redirect("{$this->_module}/index");
    }

  	public function logoutAction()
    {
        $lang = $_SESSION['language'];
        //Destroi a sessão
        session_destroy();

        //Limpa a variável da sessão
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $_SESSION['language'] = $lang;
        $this->_redirect("/{$this->_module}");
	}
}