$(document).ready(function() {
	// pega evento no onclick do botao
	$("#bt_salvar_encaminhar_pre_demanda").click(function(){
		if( !validaForm() ){return false;}
		encaminhaPreDemanda();
	});
	
	$("#bt_cancelar_encaminhar_pre_demanda").click(function(){
		window.location.href = systemName+"/coordenador-pre-demanda";
	});
});

function encaminhaPreDemanda()
{
	var postData = $('#formEncaminharPreDemanda :input').serialize();
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/encaminhar-pre-demanda/salvar-pre-demanda",
		data	: postData,
		success	: function(retorno){
			alertMsg(retorno,'',"window.location.href = '"+systemName+"/coordenador-pre-demanda'");
		}
	});
}