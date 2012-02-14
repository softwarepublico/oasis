$(document).ready(function(){
	montaGridMedida();	
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/medida/excluir",
				data	: "cd_medida="+$("#cd_medida").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaMedidaMedida()");
				}
			});
		});
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if(!validaForm()){return false; }
		$("form#medida").submit();
	});
});

function redirecionaMedidaMedida()
{
    window.location.href = systemName+"/medida";
}

function redirecionaMedida(cd_medida)
{
	window.location.href = systemName+"/medida/editar/cd_medida/"+cd_medida;
}

function montaGridMedida()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/medida/grid-medida",
		success	: function(retorno){
			$("#gridMedida").html(retorno);
		}
	});
}