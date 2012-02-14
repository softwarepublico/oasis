var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {

	if($("#cd_contrato").val() != 0){
		getProjeto();
	}

	$("#cd_contrato").change(function() {
		if($(this).val() != 0){
			getProjeto();		
		}else{
			$("#cd_projeto"		).html(strOption);
			$('#cd_profissional').html(strOption);
			$('#cd_proposta'	).html(strOption);
		}
	});
	
	$('#cd_projeto').change(function(){
		if( $(this).val() != 0 ){
			getPropostaProjeto();
			getProfissionalProjeto();
		}else{
			$('#cd_profissional').html(strOption);
			$('#cd_proposta'	).html(strOption);
		}
	});
	
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorio') , 'historico-projeto/generate' );
        return true;
    });
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/historico-projeto/pesquisa-projeto",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}

function getPropostaProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/historico-projeto/get-proposta-projeto",
		data: "cd_projeto="+$('#cd_projeto').val(),
		success: function(retorno){
			$('#cd_proposta').html(retorno);
		}
	});
}

function getProfissionalProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/historico-projeto/get-profissional-projeto",
		data: "cd_projeto="+$('#cd_projeto').val(),
		success: function(retorno){
			$('#cd_profissional').html(retorno);
		}
	});
}