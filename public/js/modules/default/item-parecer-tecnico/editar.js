$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/item-parecer-tecnico/excluir",
				data	: "cd_item_parecer_tecnico="+$("#cd_item_parecer_tecnico").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaItemParecerTecnico()");
				}
			});
		});
	});

	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if( !validaForm("#item_parecer_tecnico")){return false;}
		$("form#item_parecer_tecnico").submit();
	});
});

function redirecionaItemParecerTecnico()
{
    window.location.href = systemName+"/item-parecer-tecnico"
}