$(document).ready(function(){
	montaGridStatus();
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#status").submit();
	});
});

function montaGridStatus()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/status/grid-status",
		success	: function(retorno){
			$('#gridStatus').html(retorno);
		}
	});
}

function redirecionaStatus(cd_status)
{
	window.location.href = systemName+"/status/editar/cd_status/"+cd_status;
}