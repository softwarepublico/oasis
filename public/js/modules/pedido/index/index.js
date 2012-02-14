$(document).ready(function(){
    $('#usuario').focus();
    
    //este botão é montado apenas quando K_LDAP_AUTENTICATE estiver 'N' 
    $('#btnCadastro').click(function(){
        window.location.href= systemName+"/"+systemNameModule+ '/index/cadastro';
    });
});