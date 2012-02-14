var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {
	
	if($("#cd_contrato").val() != 0){
		getProjeto();
	}

	$("#cd_contrato").change(function() {
		if($(this).val() != 0){
			getProjeto();		
		}else{
			$("#cd_projeto").html(strOption);
			$("#relAtaReuniao").hide();
		}
	});
	
	$("#cd_projeto").change(function() {
		if($(this).val() != 0){
			gridAtaReuniao();
		}else{
			$("#relAtaReuniao").hide();
		}
	});
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/ata-de-reuniao/pesquisa-projeto",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}

function gridAtaReuniao()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/ata-de-reuniao/monta-ata",
		data: "cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			$("#relAtaReuniao").html(retorno);
			$("#relAtaReuniao").show();
		}
	});
}