$(document).ready(function(){
//    desabilitaAbaPedido();
});

function recuperaHistoricoPedido(cd_solicitacao_pedido)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-encaminhar-recomendacao-comite/monta-encaminhamento-solicitacao",
		data	: {'cd_solicitacao_pedido': cd_solicitacao_pedido},
		success	: function(retorno){
			$("#form_pedido_comite_encaminhar").html(retorno);
            habilitaAbaPedido();
		}
	});
}

function enviarPedidoAnalisadoComite()
{
    if(!validaForm('#div_encaminhar_pedido_analisado_comite')){return false}
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-encaminhar-recomendacao-comite/encaminhar-pedido",
		data	: $('#div_encaminhar_pedido_analisado_comite :input').serialize(),
        dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] === true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type'], function(){desabilitaAbaPedido()});
                montaGridSolicitacaoComite();
            }
		}
	});
}

function habilitaAbaPedido()
{
    $("#li_formulario_pedido" ).show();
    $("#container-solicitacao").triggerTab(qtdAbas);
}

function desabilitaAbaPedido()
{
    $("#li_formulario_pedido" ).hide();
    $("#container-solicitacao").triggerTab(qtdAbas-1);
}