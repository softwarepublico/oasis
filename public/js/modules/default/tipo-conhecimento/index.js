$(document).ready(function(){
	montaGridTipoConhecimento();
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if( !validaForm("form#tipo_conhecimento") ){return false;}
		$("form#tipo_conhecimento").submit();
	});
});

function montaGridTipoConhecimento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-conhecimento/grid-tipo-conhecimento",
		success	: function(retorno){
			$('#gridTipoConhecimento').html(retorno);
		}
	});
}

function redirecionaTipoConhecimento(cd_tipo_conhecimento)
{
	window.location.href = systemName+"/tipo-conhecimento/editar/cd_tipo_conhecimento/"+cd_tipo_conhecimento;
}