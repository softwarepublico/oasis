function submeteAutorizacaoPedido()
{
    if(!validaFormularioAutorizacaoPedido()){return false;}
    $("#form_autorizacao_pedido").submit();
}

function validaFormularioAutorizacaoPedido()
{
    if($("input[name=situacao]:checked").val() == undefined){
        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array(i18n.L_VIEW_SCRIPT_ACAO)), 2);
        return false;
    }
    if($("#observacao").val() == ''){
        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array(i18n.L_VIEW_SCRIPT_OBSERVACAO)), 2);
        return false;
    }
    return true;
}