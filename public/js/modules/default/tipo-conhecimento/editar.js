$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/tipo-conhecimento/excluir",
				data	: "cd_tipo_conhecimento="+$("#cd_tipo_conhecimento").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaTipoConhecimento()");
				}
			});
		});
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if( !validaForm("form#tipo_conhecimento") ){return false;}
		$("form#tipo_conhecimento").submit();
	});
});

function redirecionaTipoConhecimento()
{
    window.location.href = systemName+"/tipo-conhecimento";
}