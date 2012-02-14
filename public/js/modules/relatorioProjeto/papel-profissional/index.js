$(document).ready(  function() {

	if($("#cd_contrato").val() != 0){
		getProjeto();
        getPapel();
	}

	$("#cd_contrato").change(function() {
		if($(this).val() != 0){
			getProjeto();
            getPapel();
		}
	});
	
    $('#btn_gerar').click( function(){
        if( !validaForm('#form_rel_papel_profissional') ){ return false; }
        gerarRelatorio( $('#form_rel_papel_profissional') , 'papel-profissional/papel-profissional' );
        return true;
    });
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/papel-profissional/pesquisa-projeto",
		data: {"cd_contrato":$("#cd_contrato").val()},
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}

function getPapel()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/papel-profissional/pesquisa-papel",
		data: {"cd_contrato":$("#cd_contrato").val()},
		success: function(retorno){
			$("#cd_papel_profissional").html(retorno);
		}
	});
}