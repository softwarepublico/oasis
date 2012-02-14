$(document).ready(function(){
    $('#container_autorizados_encaminhar').triggerTab(1);
    desabilitaAbaValidarPedido();
    montaGridPedidos();
});

function montaGridPedidos()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-valida/grid-pedido",
		success	: function(retorno){
			$("#grid_pedidos").html(retorno);
            desabilitaAbaValidarPedido();
		}
	});
}

function recuperaPedido(cd_solicitacao_pedido)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-valida/formulario",
        data    : {'cd_solicitacao_pedido': cd_solicitacao_pedido},
		success	: function(retorno){
			$("#form_validar_pedido").html(retorno);
            habilitaAbaValidarPedido();
		}
	});
}

function validarPedido()
{
    if(!validaCampos()){return false}

    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-valida/encaminhar-pedido",
		data	: $('#formulario_validar_pedido :input').serialize(),
        dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] === true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type'], function(){desabilitaAbaValidarPedido(); montaGridPedidos();});
            }
		}
	});
}

function validaCampos()
{
    if($("input[name=status]:checked").val() == undefined){
        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array(i18n.L_VIEW_SCRIPT_ACAO)), 2);
        return false;
    }
    if($("#tx_descricao_historico").val() == ''){
        alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array(i18n.L_VIEW_SCRIPT_OBSERVACAO)), 2, function(){$("#tx_descricao_historico").focus()});
        return false;
    }
    return true;
}

function habilitaAbaValidarPedido()
{
    $("#li_formulario_validar"   ).show();
    $("#container_validar_pedido").triggerTab(2);
}

function desabilitaAbaValidarPedido()
{
    $("#li_formulario_validar"   ).hide();
    $("#container_validar_pedido").triggerTab(1);
}