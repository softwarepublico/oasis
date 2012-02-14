$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/modulo/excluir",
				data	: "cd_modulo="+$("#cd_modulo").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaModulo()");
				}
			});
		});
	});
	
	$("#cd_etapa").change(function() {
		window.location.href = systemName+"/modulo/index/cd_projeto/" + $("#cd_projeto").val();	
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#modulo").submit();
	});
});

function redirecionaModulo()
{
    window.location.href = systemName+"/modulo/index/cd_projeto/" + $("#cd_projeto").val();
}