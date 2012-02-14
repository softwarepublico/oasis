var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {
	
//	if($("#cd_objeto").val() != 0){
//		getObjeto();
//	}

	$("#cd_objeto").change(function() {
		if($(this).val() != 0){
	//		getObjeto();
		}else{
			$("#cd_objeto").html(strOption);
			$("#relAtaReuniaoGeral").hide();
        }
    });
	
	
	$("#cd_objeto").change(function() {
		if($(this).val() != 0){
			gridAtaReuniaoGeral();
		}else{
			$("#relAtaReuniaoGeral").hide();
		}
	});
});

function getObjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/ata-de-reuniao-geral/pesquisa-objeto",
		data: "cd_objeto="+$("#cd_objeto").val(),
		success: function(retorno){
			$("#cd_objeto").html(retorno);
		}
	});
}

function gridAtaReuniaoGeral()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/ata-de-reuniao-geral/monta-ata",
		data: "cd_objeto="+$("#cd_objeto").val(),
		success: function(retorno){
			$("#relAtaReuniaoGeral").html(retorno);
			$("#relAtaReuniaoGeral").show();
		}
	});
}