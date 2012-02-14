$(document).ready(function(){
	montaGridUnidade();	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if(!validaForm()){return false; }
		$("form#unidade").submit();
	});
});

function redirecionaUnidade(cd_unidade)
{
	window.location.href = systemName+"/unidade/editar/cd_unidade/"+cd_unidade;
}

function montaGridUnidade(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/unidade/grid-unidade",
		success	: function(retorno){
			$("#gridUnidade").html(retorno);
		}
	});
}