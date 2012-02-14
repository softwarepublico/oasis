$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/tipo-dado-tecnico/excluir",
				data	: "cd_tipo_dado_tecnico="+$("#cd_tipo_dado_tecnico").val(),
				success	: function(retorno){
					alertMsg(retorno);
				}
			});
		});
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#tipo_dado_tecnico").submit();
	});
});

function redirecionaTipoDadoTecnico()
{
    window.location.href = systemName+"/tipo-dado-tecnico";
}