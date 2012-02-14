$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/modulo-continuado/excluir",
				data	: "cd_modulo_continuado="+$("#cd_modulo_continuado").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaModuloContinuado()");
				}
			});
		});
	});
	
	$("#cd_objeto").change(function() {
		window.location.href = systemName+"/modulo-continuado/index/cd_objeto/" + $("#cd_objeto").val();	
	});
	
	$("#cd_projeto_continuado").change(function() {
		window.location.href = systemName+"/modulo-continuado/index/cd_projeto_continuado/" + $("#cd_projeto_continuado").val();	
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#modulo_continuado").submit();
	});
});

function redirecionaModuloContinuado()
{
    window.location.href = systemName+"/modulo-continuado/index/cd_objeto/" + $("#cd_objeto").val()+"/cd_projeto_continuado/" + $("#cd_projeto_continuado").val();
}