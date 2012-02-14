$(document).ready(function(){

    $("#cd_definicao_metrica").change(function(){
        if($(this).val() != 0){
            montaCamposCalculadora();
        }else{
            $("#montagemMetrica").hide();
        }
    });
});

function verificaExistenciaMetricaPadrao() {

	$.ajax({
        type    : "POST",
        url     : systemName+"/elaboracao-proposta/verifica-existencia-metrica-padrao",
        dataType: 'json',
        success : function(retorno){

            if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
				$("#cd_definicao_metrica").removeAttr('disabled');
	        }
        }
    });
}


function montaCamposCalculadora()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/calculadora-metrica/monta-campos-calculadora",
        data    : "cd_definicao_metrica="+$("#cd_definicao_metrica").val(),
        success : function(retorno){
            $("#montagemMetrica").html(retorno);
            $("#montagemMetrica").show();
            setClickButtons();
        }
    });
}

function setClickButtons()
{
    $("#calcular_valor_metrica").click(function(){
        calcularValorMetrica();
    });
}

function calcularValorMetrica()
{
	$("#valor_total_calculo_metrica").val('');
	
	if( !validaCamposCalculadora() ){
		alertMsg(i18n.L_VIEW_SCRIPT_NENHUM_VALOR_CALCULO);
		return false;
	}
	
    $.ajax({
        type    : "POST",
        url     : systemName+"/elaboracao-proposta/calcular-valor-metrica",
        data    : $("#div_sub_item_metrica :input").serialize()+"&cd_definicao_metrica="+$("#cd_definicao_metrica").val(),
        dataType: 'json',
        success : function(retorno){
            if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }
			$("#valor_total_calculo_metrica").val(retorno['unidade_metrica']);
            $("#tx_calculo_metrica"			).html(retorno['tx_calculo_metrica']);
        }
    });
}

function validaCamposCalculadora()
{
	var existeValor = false;
	
	$("#div_sub_item_metrica :input").not("#valor_total_calculo_metrica")
									 .not("#calcular_valor_metrica")
									 .each(function(){
	    if($(this).val() != ''){
	        existeValor = true;
	    }
	});
	return existeValor;
}