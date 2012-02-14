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
			$("#cd_modulo").html(strOption);
		}
	});

	$("#cd_projeto").change(function() {
		if($(this).val() != 0){
			comboModulo();		
		}else{
			$("#cd_modulo").html(strOption);
		}
	});

    $('#btn_gerar').click( function(){
        if( !validaForm() ){return false;}
        gerarRelatorio( $('#formRelatorio') , 'caso-de-uso/generate' );
        return true;
          
    });
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/caso-de-uso/pesquisa-projeto",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}


function comboModulo()
{
	$.ajax({
		type: "POST",
		url: systemName+"/modulo/monta-combo-modulo",
		data: "cd_projeto="+$("#cd_projeto").val()+"&comTodos=true",
		success: function(retorno){
			$("#cd_modulo").html(retorno);
		}
	});
}