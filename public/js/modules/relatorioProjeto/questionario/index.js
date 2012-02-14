var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {
	
	if($("#cd_contrato").val() != 0){
		getProjeto();
	}

	$("#cd_contrato").change(function() {
		if($(this).val() != 0){
			getProjeto();		
		}else{
			$("#cd_projeto" ).html(strOption);
			$("#cd_proposta").html(strOption);
		}
	});
	$('#cd_projeto').change(function(){
		if($(this).val() != 0){
			comboProposta();
		}else{
			$("#cd_proposta").html(strOption);
		}
	});

    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoQuestionario') , 'questionario/generate' );
        return true;
    });
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/questionario/pesquisa-projeto",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}

function comboProposta()
{
	$.ajax({
		type: "POST",
		url: systemName+"/proposta/pesquisa-proposta",
		data: "cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			$("#cd_proposta").html(retorno);
		}
	});
}