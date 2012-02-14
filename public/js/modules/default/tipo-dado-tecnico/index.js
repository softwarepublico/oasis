$(document).ready(function(){
	montaGridTipoDadoTecnico();	

	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#tipo_dado_tecnico").submit();
	});
});

function redirecionaTipoDadoTecnico(cd_tipo_dado_tecnico)
{
	window.location.href = systemName+"/tipo-dado-tecnico/editar/cd_tipo_dado_tecnico/"+cd_tipo_dado_tecnico;
}

function montaGridTipoDadoTecnico()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-dado-tecnico/grid-tipo-dado-tecnico",
		success	: function(retorno){
			// atualiza a grid
			$("#gridTipoDadoTecnico").html(retorno);
		}
	});
}