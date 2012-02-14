$(document).ready(function(){
	montaGridItemParecerTecnico();

	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if( !validaForm("#item_parecer_tecnico")){return false;}
		$("form#item_parecer_tecnico").submit();
	});
});

function redirecionaItemParecerTecnico(cd_item_parecer_tecnico)
{
	window.location.href = systemName+"/item-parecer-tecnico/editar/cd_item_parecer_tecnico/"+cd_item_parecer_tecnico;
}

function montaGridItemParecerTecnico()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/item-parecer-tecnico/grid-item-parecer-tecnico",
		success	: function(retorno){
			// atualiza a grid
			$("#gridItemParecerTecnico").html(retorno);
		}
	});
}