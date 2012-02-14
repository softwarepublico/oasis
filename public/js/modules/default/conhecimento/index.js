$(document).ready(function(){

	if ($("#cd_tipo_conhecimento").val() != "0") {
		montaGridConhecimento();		
	} else {
		$("#gridConhecimento").hide();	
	}
	$("#cd_tipo_conhecimento").change(function() {
		if ($("#cd_tipo_conhecimento").val() != "0") {
			montaGridConhecimento();		
		} else {
			$("#gridConhecimento").hide();	
		}
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#conhecimento").submit();
		montaGridConhecimento();
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
	window.location.href = systemName+"/conhecimento/editar/cd_conhecimento/"+cd_conhecimento;
}