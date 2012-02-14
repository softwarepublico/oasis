var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {

	getProjeto();
	
	$("#cd_contrato").change(function(){
		getProjeto();
	});

	$('#cd_projeto').change(function(){
		getProposta();
	});

    $('#btn_gerar').click( function(){
    	if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoAlocacaoRecurso') , 'alocacao-recurso/generate' );
        return true;
    });
});

function getProjeto()
{
	if($("#cd_contrato").val() != 0 ){
		$.ajax({
			type: "POST",
			url: systemName+"/"+systemNameModule+"/alocacao-recurso/pesquisa-projeto",
			data: "cd_contrato="+$("#cd_contrato").val(),
			success: function(retorno){
				$("#cd_projeto").html(retorno);
			}
		});
	}else{
		$("#cd_projeto" ).html(strOption);
		$("#cd_proposta").html(strOption);
		return false;
	}
}

function getProposta()
{
	if ($("#cd_projeto").val() != 0) {
		$.ajax({
			type: "POST",
			url: systemName + "/proposta/pesquisa-proposta",
			data: "cd_projeto=" + $("#cd_projeto").val(),
			success: function(retorno){
				$("#cd_proposta").html(retorno);
			}
		});
	}else{
		$("#cd_proposta").html(strOption);
		return false;
	}
}