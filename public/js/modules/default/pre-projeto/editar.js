$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/pre-projeto/excluir",
				data	: "cd_pre_projeto="+$("#cd_pre_projeto").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaPreProjeto()");
				}
			});
		});
	});

	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#pre_projeto").submit();
	});
});

function redirecionaPreProjeto(){
    window.location.href = systemName+"/pre-projeto";
}