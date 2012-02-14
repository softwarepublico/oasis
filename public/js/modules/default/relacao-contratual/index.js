$(document).ready(function(){
	montaGridRelacaoContratual();
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/relacao-contratual/excluir",
				data	: "cd_relacao_contratual="+$("#cd_relacao_contratual").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaRelacaoContratualIndex()");
				}
			});
		});
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if(!validaForm()){return false; }
		$("form#relacao_contratual").submit();
	});
});

function montaGridRelacaoContratual()
{
	$.ajax({
		type: "POST",
		url: systemName+"/relacao-contratual/grid-relacao-contratual",
		success: function(retorno){
			$("#gridRelacaoContratual").html(retorno);
		}
	});
}

function redirecionaRelacaoContratualIndex()
{
	window.location.href = systemName+"/relacao-contratual";
}

function redirecionaRelacaoContratual(cd_relacao_contratual)
{
	window.location.href = systemName+"/relacao-contratual/editar/cd_relacao_contratual/"+cd_relacao_contratual;
}