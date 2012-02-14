$(document).ready(function() {
	$('#container-solicitacao').show().tabs();
});

function montaGridAutorizacaoSolicitacao()
{
     $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-solicitacao/autorizar",
		success	: function(retorno){
			$("#divAutorizacaoSolicitacao").html(retorno);
		}
	});
}

function montaGridSolicitacaoComite()
{
     $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-encaminhar-recomendacao-comite/grid-pedido-comite",
		success	: function(retorno){
			$("#divAutorizacaoSolicitacaoComite").html(retorno);
		}
	});
}