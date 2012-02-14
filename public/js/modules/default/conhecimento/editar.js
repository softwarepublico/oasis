$(document).ready(function(){
	if ($("#cd_tipo_conhecimento").val() != "0") {
		montaGridConhecimento();		
	} else {
		$("#gridConhecimento").hide();	
	}

	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
			$.ajax({
				type: "POST",
				url: systemName+"/conhecimento/excluir",
				data: "cd_conhecimento="+$("#cd_conhecimento").val(),
				success: function(retorno){
					alertMsg(retorno,'',"window.location.href = '"+systemName+"/conhecimento'");
				}
			});
		});
	});
	
	$("#cd_tipo_conhecimento").change(function() {
		window.location.href = systemName+"/conhecimento/index/cd_tipo_conhecimento/" + $("#cd_tipo_conhecimento").val();	
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#conhecimento").submit();
	});
});

function montaGridConhecimento()
{
	$.ajax({
		type: "POST",
		url: systemName+"/conhecimento/grid-conhecimento",
		data: "cd_tipo_conhecimento="+$("#cd_tipo_conhecimento").val(),
		success: function(retorno){
			$("#gridConhecimento").html(retorno);
			$("#gridConhecimento").show('slow');
		}
	});
}

function alterarConhecimento(cd_conhecimento)
{
	window.location.href = "./"+cd_conhecimento;
}