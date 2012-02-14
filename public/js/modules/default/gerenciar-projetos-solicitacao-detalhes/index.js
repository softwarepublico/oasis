$(document).ready(function(){
	$("#bt_ok").click(function (){
		$.ajax({
			type	: "POST",
			url		: systemName+"/gerenciar-projetos-solicitacao/grava-data-hora-leitura-solicitacao",
			data	: "cd_objeto="+$("#cd_objeto").val()+
                      "&ni_solicitacao="+$("#ni_solicitacao").val()+
                      "&ni_ano_solicitacao="+$("#ni_ano_solicitacao").val(),
			success	: function(retorno){
				alertMsg(retorno,'',"redireciona()");
			}
		});
	});
});

function redireciona()
{
    window.location.href = systemName+'/gerenciar-projetos';
}