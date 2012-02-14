$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/informacao-tecnica/excluir",
				data	: "cd_projeto="+$("#cd_projeto").val()+"&cd_tipo_dado_tecnico="+$("#cd_tipo_dado_tecnico").val(),
				success	: function(retorno){
					alertMsg(retorno,'',function(){redirecionaInformacaoTecnica()});
				}
			});
		});
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#informacao_tecnica").submit();
	});
});

function redirecionaInformacaoTecnica()
{
    window.location.href = systemName+"/informacao-tecnica";
}