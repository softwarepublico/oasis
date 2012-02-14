$(document).ready(function(){
    $('#cd_unidade_usuario').focus();

    $('#btCadastrar').click(function(){
        if(!validaForm('#formCadastroUsuarioPedido')){return false;}
        $('#formCadastroUsuarioPedido').submit();
    });
    
    $('#btCancelar').click(function(){
        window.location.href= systemName+"/"+systemNameModule+ '/index';
    });
    
    $('#btReset').click(function(){
        $('#formCadastroUsuarioPedido :input').not('select,button').val('');
        $('#formCadastroUsuarioPedido select').val('0');
    });
});