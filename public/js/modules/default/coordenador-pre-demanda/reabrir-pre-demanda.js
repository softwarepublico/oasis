
function salvarReaberturaPreDemanda()
{
	if( !validaForm("#formReabrirPreDemanda", true) ){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/coordenador-pre-demanda/reabrir",
		data	: $('#formReabrirPreDemanda :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			ajaxGridPreDemandaExecutada();
			closeDialog('dialog_reabrir_pre_demanda');
		}
	});
}

function ajaxGridPreDemandaExecutada() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/coordenador-pre-demanda/grid-pre-demanda-executada",
		data	: "mes="+$("#mes_pre_demanda_executada").val()
				 +"&ano="+$("#ano_pre_demanda_executada").val()
				 +"&cd_objeto_receptor="+$("#cd_objeto_receptor_executada").val(),
		success	: function(retorno){
			$("#gridPreDemandaExecutada").html(retorno);
			$('#mesAnoExecutada').html($('#mesAnoCoodenadorExecutada').val());
		}
	});
}