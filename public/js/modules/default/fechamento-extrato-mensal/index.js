$(document).ready(function(){
	$("#cd_contrato").change(function() {
		if ($("#cd_contrato").val() != "0") {
			gridFechamentoExtratoMensalAjax();
		}
	});
});

function gridFechamentoExtratoMensalAjax() {
	$.ajax({
		type: "POST",
		url: systemName+"/fechamento-extrato-mensal/grid-fechamento-extrato-mensal",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			// atualiza a grid
			$("#gridFechamentoExtratoMensal").html(retorno);
		}
	});
}

function fechaExtratoMensal(cd_contrato, mes, ano, qtd_parcelas, horas_parcelas)
{
	if ($("#st_encerramento_proposta_"+mes+"_"+ano+"").val() == 'N'){
		var info = getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_PROPOSTA_SEM_ENCERRAMENTO, new Array(mes, ano));
		alertMsg(info);
		return false;
	}else{
		var info = getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_DESEJA_FECHAR_EXTRATO_MENSAL, new Array(mes, ano));

		confirmMsg(info, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/fechamento-extrato-mensal/fechar-extrato-mensal",
				data	: "cd_contrato="+cd_contrato+"&mes="+mes+"&ano="+ano+"&qtd_parcelas="+qtd_parcelas+"&horas_parcelas="+horas_parcelas,
				success	: function(retorno){
					alertMsg(retorno);
					gridFechamentoExtratoMensalAjax();
				}
			});
		});
	}

}





