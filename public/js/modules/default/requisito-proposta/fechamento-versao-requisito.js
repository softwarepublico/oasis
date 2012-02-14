function montaGridFechamentoVersaoRequisito()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/requisito-proposta/grid-fechamento-versao-requisito",
        data	: {'cd_projeto':$('#cd_projeto').val()},
        success	: function(retorno){
            $("#gridFechamentoVersao").html(retorno);
        }
    });
}

function fecharVersaoRequisito( cd_requisito, dt_versao_requisito )
{
	var cd_projeto = $("#cd_projeto").val();
	
	$.ajax({
        type	: "POST",
        url		: systemName+"/requisito-proposta/fechar-versao-requisito",
        data	: "cd_projeto="+cd_projeto+"&cd_requisito="+cd_requisito+"&dt_versao_requisito="+dt_versao_requisito,
        success	: function(retorno){
            alertMsg(retorno);
			montaGridFechamentoVersaoRequisito();
			montaGridRequisitosProposta();
        }
    });
}