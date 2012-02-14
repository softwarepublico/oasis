$(document).ready(function(){
	$("#bt_excluir").click(function() {
		if (confirm(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR)) {
			$.ajax({
				type: "POST",
				url: systemName+"/historico-projeto-continuado/excluir",
				data: "cd_historico_proj_continuado="+$("#cd_historico_proj_continuado").val(),
				success: function(retorno){
					alertMsg(retorno,'',"window.location.href = '"+systemName+"/historico-projeto-continuado'");
				}
			});
		}
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#historico_projeto_continuado").submit();
	});
});