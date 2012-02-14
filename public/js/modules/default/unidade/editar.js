$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/unidade/excluir",
				data	: "cd_unidade="+$("#cd_unidade").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaUnidade()");
				}
			});
		});
	});

	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if(!validaForm()){return false; }
		$("form#unidade").submit();
	});
});

function redirecionaUnidade()
{
    window.location.href = systemName+"/unidade";
}