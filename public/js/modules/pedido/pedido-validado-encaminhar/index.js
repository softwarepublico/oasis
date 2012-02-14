$(document).ready(function(){
    $('#container_autorizados_encaminhar').triggerTab(1);
    montaGridPedidoAutorizado();
});

function montaGridPedidoAutorizado()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-validado-encaminhar/grid-pedido-autorizado",
		success	: function(retorno){
			$("#grid_pedido_autorizado").html(retorno);
            desabilitaAbaPedido();
		}
	});
}

function recuperaHistoricoPedido(cd_solicitacao_pedido)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-validado-encaminhar/monta-encaminhamento-solicitacao",
		data	: {'cd_solicitacao_pedido': cd_solicitacao_pedido},
		success	: function(retorno){
			$("#form_historico_autorizado").html(retorno);
            habilitaAbaPedido();

		}
	});
}

function enviarPedidoComite()
{
    if(!validaForm('#div_encaminhar_pedido_comite')){return false}
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-validado-encaminhar/encaminhar-pedido",
		data	: $('#div_encaminhar_pedido_comite :input').serialize(),
        dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] === true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type'], function(){desabilitaAbaPedido(); montaGridPedidoAutorizado();});
            }
		}
	});
}

function habilitaAbaPedido()
{
    $("#li_formulario_pedido").show();
    $("#container_autorizados_encaminhar").triggerTab(2);
}

function desabilitaAbaPedido()
{
    $("#li_formulario_pedido").hide();
    $("#container_autorizados_encaminhar").triggerTab(1);
}