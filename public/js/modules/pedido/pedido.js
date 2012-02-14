$(document).ready(function(){
    menu  = '<li><a href="'+systemName+'/public/documentacao/geral/Info-OASIS-pedido.pdf" target="_blank">'+i18n.L_VIEW_SCRIPT_INFORMACOES_GERAIS+'</a></li>';
    menu += '<li class="ultimo branco"><a href="'+systemName+'/pedido/index/logout">'+i18n.L_VIEW_SCRIPT_SAIR+'</a></li>';

    $('#cabeca ul').html(menu);
    $('#nomeUsuario').css('margin-top',25);
    $('#login').focus();
});