function ajaxGridInformacoesTecnicas()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/dados-tecnicos/grid-informacoes-tecnicas",
		data	: "cd_projeto="+$("#cd_projeto").val(),
		success	: function(retorno){
			$("#gridInformacoesTecnicas").html(retorno);
			
			if( $("#config_hidden_dados_tecnicos_aba_info_tec").val() === "N" ){
				$("#config_hidden_dados_tecnicos_aba_info_tec").val("S");
			}
		}
	});
}

function salvaDadosTecnicos()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/dados-tecnicos/salvar-informacoes-tecnicas",
		data	: $('#formInformacoesTecnicas :input').serialize()+"&cd_projeto="+$('#cd_projeto').val(),
		success	: function(retorno){
			alertMsg(retorno);
		}
	});
}