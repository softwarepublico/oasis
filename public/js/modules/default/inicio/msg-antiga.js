$(document).ready( function() {
    $("#btn_pesquisar_msg").click( function() {
		if(!validaForm("#div_params_consulta_msg")){return false;}
		if(!validaPeriodo()){return false;}
        pesquisaMensagensLidas();
    });

    $("#mes_msg_final").change( function() {
		if($(this).val() != 0 ){
			$("#lb_ano_msg_final").addClass('required');
		}else{
			$("#lb_ano_msg_final").removeClass('required');
		}
    });
	
    $("#ano_msg_final").change( function() {
		if($(this).val() != 0 ){
			$("#lb_mes_msg_final").addClass('required');
		}else{
			$("#lb_mes_msg_final").removeClass('required');
		}
    });
});

function validaPeriodo()
{
	if( ($("#mes_msg_final").val() != 0) && ($("#mes_msg_final").val() < $("#mes_msg_inicial").val()) ){
		showToolTip(i18n.L_VIEW_SCRIPT_DT_MES_FINAL_MENOR_MES_INICIAL, $("#mes_msg_final"))
		return false;
	}
	if( ($("#ano_msg_final").val() != 0) && ($("#ano_msg_final").val() < $("#ano_msg_inicial").val()) ){
		showToolTip(i18n.L_VIEW_SCRIPT_DT_ANO_FINAL_MENOR_ANO_INICIAL, $("#ano_msg_final"))
		return false;
	}
	return true;
}

function pesquisaMensagensLidas()
{
	$.ajax({
		type	: 'POST',
		url		: systemName+'/inicio/pesquisa-mensagens-antigas',
		data	: $('#div_params_consulta_msg :input').serialize(),

		success	: function(retorno){
			$('#div_retorno_msg_antiga').html(retorno);
		}
	});
}