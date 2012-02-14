function montaGridFechamentoVersao()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/regra-negocio/grid-fechamento-versao",
        data	: {'cd_projeto_regra_negocio':$('#cd_projeto').val()},
        success	: function(retorno){
            $("#gridFechamentoVersao").html(retorno);
        }
    });
}

function fecharVersaoRegraNegocio( cd_regra_negocio, dt_regra_negocio )
{
	var cd_projeto = $("#cd_projeto").val();
	
	$.ajax({
        type	: "POST",
        url		: systemName+"/regra-negocio/fechar-versao-regra-negocio",
        data	: "cd_projeto_regra_negocio="+cd_projeto+
				  "&cd_regra_negocio="+cd_regra_negocio+
				  "&dt_regra_negocio="+dt_regra_negocio,
        success	: function(retorno){
            alertMsg(retorno);
			montaGridFechamentoVersao()
        }
    });
}