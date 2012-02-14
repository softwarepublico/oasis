$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/status/excluir",
				data	: "cd_status="+$("#cd_status").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaStatus()");
				}
			});
		});
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#status").submit();
	});
});

function redirecionaStatus()
{
    window.location.href = systemName+"/status";
}