$(document).ready(function(){
	$("#cd_contrato_fechamento_demanda").change(function() {
		if ($("#cd_contrato_fechamento_demanda").val() != "0") {
			gridFechamentoExtratoMensalDemandaAjax();
		}
	});
});

function gridFechamentoExtratoMensalDemandaAjax() {
	$.ajax({
		type: "POST",
		url: systemName+"/fechamento-extrato-mensal-demanda/grid-fechamento-extrato-mensal-demanda",
		data: "cd_contrato="+$("#cd_contrato_fechamento_demanda").val(),
		success: function(retorno){
			// atualiza a grid
			$("#gridFechamentoExtratoMensalDemanda").html(retorno);
		}
	});
}

function fechaExtratoMensalDemanda(cd_contrato, mes, ano, qtd_parcelas, horas_parcelas)
{
		var info = getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_DESEJA_FECHAR_EXTRATO_MENSAL, new Array(mes, ano));

		confirmMsg(info, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/fechamento-extrato-mensal-demanda/fechar-extrato-mensal-demanda",
				data	: "cd_contrato="+cd_contrato+"&mes="+mes+"&ano="+ano+"&qtd_parcelas="+qtd_parcelas+"&horas_parcelas="+horas_parcelas,
				success	: function(retorno){
					alertMsg(retorno);
					gridFechamentoExtratoMensalDemandaAjax();
				}
			});
		});
}





