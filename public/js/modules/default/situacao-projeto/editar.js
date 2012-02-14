$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type: "POST",
				url: systemName+"/situacao-projeto/excluir",
				data: "cd_situacao_projeto="+$("#cd_situacao_projeto").val(),
				success: function(retorno){
					alertMsg(retorno,'',"redirecionaSituacaoProjeto()");
				}
			});
		});
	});
	
	$("#cd_projeto").change(function() {
		window.location.href = systemName+"/situacao-projeto/index/cd_projeto/" + $("#cd_projeto").val();	
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#situacao_projeto").submit();
	});
});

function redirecionaSituacaoProjeto()
{
    window.location.href = systemName+"/situacao-projeto/index/cd_projeto/" + $("#cd_projeto").val();
}