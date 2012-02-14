$(document).ready(function(){
     
    $("#cd_projeto_status").change(montaStatusProjeto);
    $("#btn_salvar_status_projeto").click(salvarStatusProjeto);

    
});

function montaStatusProjeto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciar-projetos/get-status-projeto",
		data	: {"cd_projeto":$("#cd_projeto_status").val()},
		success	: function(retorno){
            $("#cd_status_projeto").html(retorno);
		}
	});
}

function salvarStatusProjeto()
{
	if($("#cd_status_projeto")== ''){return false}
	$.ajax({
		type    : "POST",
		url     : systemName+"/status-projeto/salvar-status-projeto",
	    data    : {"cd_projeto"       : $('#cd_projeto_status').val(),
		           "cd_status_projeto": $('#cd_status_projeto').val()},
		dataType: 'json',
		success	: function(retorno){
			alertMsg(retorno['msg'],retorno['typeMsg']);
            
			if(retorno['error'] == false){
                $('#cd_projeto_status').val(0);
                $('#cd_status_projeto').empty();
			}
		}
	});
}
