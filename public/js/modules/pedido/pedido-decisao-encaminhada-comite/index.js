$(document).ready(function(){
    montaGridPedidosEncaminhadoComite();
});

function montaGridPedidosEncaminhadoComite()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-decisao-encaminhada-comite/grid-pedido-encaminhado-comite",
		success	: function(retorno){
			$("#grid_pedidos_encaminhados_comite").html(retorno);
            desabilitaAbaEncaminharPedidoFinilizar();
		}
	});
}

function recuperaPedido(cd_solicitacao_pedido)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-decisao-encaminhada-comite/formulario",
        data    : {'cd_solicitacao_pedido': cd_solicitacao_pedido},
		success	: function(retorno){
			$("#div_encaminhar_pedido_finalizar_processo").html(retorno);
            habilitaAbaEncaminharPedidoFinilizar();
		}
	});
}

function encaminharPedidoFinalizar()
{
    if(!validaCampos()){return false}

    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-decisao-encaminhada-comite/encaminhar-pedido",
		data	: $('#form_encaminhar_pedido_finalizar_processo :input').serialize(),
        dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] === true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type'], function(){desabilitaAbaEncaminharPedidoFinilizar(); montaGridPedidosEncaminhadoComite();});
            }
		}
	});
}

function validaCampos()
{
    if($("input[name=status]:checked").val() == undefined){
        alertMsg(alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array(i18n.L_VIEW_SCRIPT_ACAO))),2);
        return false;
    }
    if($("#tx_descricao_historico").val() == ''){
        alertMsg(alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO_VARIAVEL, new Array(i18n.L_VIEW_SCRIPT_OBSERVACAO))),2,function(){$("#tx_descricao_historico").focus()});
        return false;
    }
    return true;
}

function habilitaAbaEncaminharPedidoFinilizar()
{
    $("#li_encaminhar_pedido_finalizar"      ).show();
    $("#container_decisao_encaminhada_comite").triggerTab(2);
}
function desabilitaAbaEncaminharPedidoFinilizar()
{
    $("#li_encaminhar_pedido_finalizar"      ).hide();
    $("#container_decisao_encaminhada_comite").triggerTab(1);
}