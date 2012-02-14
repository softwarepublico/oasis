$(document).ready(function(){

    $("#cd_definicao_metrica").change(function(){
        if($(this).val() != 0){
            montaCamposMetrica();
        }else{
            $("#montagemMetrica").hide();
        }
    });

	$("#config_hidden_metrica_dinamica").val('N');
});

function configMetricaDinamica() {

	verificaExistenciaMetricaPadrao();
//
//	if( $("#cd_definicao_metrica").val() != 0 ){
//		montaCamposMetrica();
//	}
	$("#config_hidden_metrica_dinamica").val('S');
}

function verificaExistenciaMetricaPadrao() {

	$.ajax({
        type    : "POST",
        url     : systemName+"/elaboracao-proposta/verifica-existencia-metrica-padrao",
		data	: "cd_projeto="+$("#cd_projeto").val(),
        dataType: 'json',
        success : function(retorno){

            if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{

				$("#cd_definicao_metrica").removeAttr('disabled');
				$("#bt_confirmar_metrica").removeAttr('disabled');

				if( $("#cd_definicao_metrica").val() != 0 ){
					montaCamposMetrica();
				}
	        }
        }
    });
}


function montaCamposMetrica()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/elaboracao-proposta/campos-metrica",
        data    : "cd_definicao_metrica="+$("#cd_definicao_metrica").val()+
                  "&cd_projeto=" +$("#cd_projeto").val()+
                  "&cd_proposta="+$("#cd_proposta").val(),
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

    $("#salvar_calculo_metrica").click(function(){
        salvarCalculoMetrica();
    });
}

function calcularValorMetrica()
{
	$("#valor_total_calculo_metrica").val('');
	$("#tx_calculo_metrica"			).html('');
	
	if( !validaCamposCalculadora() ){
		alertMsg(i18n.L_VIEW_SCRIPT_NENHUM_VALOR_CALCULO);
		return false;
	}

    $.ajax({
        type    : "POST",
        url     : systemName+"/elaboracao-proposta/calcular-valor-metrica",
        data    : $("#div_sub_item_metrica :input").serialize()+
				  "&cd_definicao_metrica="+$("#cd_definicao_metrica").val()+
				  "&cd_projeto="+$("#cd_projeto").val(),
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
                                     .not("#salvar_calculo_metrica")
                                     .not("#calcular_valor_metrica")
                                     .each(function(){
	    if($(this).val() != ''){
	        existeValor = true;
	    }
	});
	return existeValor;
}

function salvarCalculoMetrica()
{
    var totalHoras = $("#valor_total_calculo_metrica").val()
    
    if( totalHoras == "" ){
        alertMsg(i18n.L_VIEW_SCRIPT_CALCULO_METRICA_NAO_EFETUADO);
        return false;
    }
	if( totalHoras == 0 ){
        alertMsg(i18n.L_VIEW_SCRIPT_VALOR_CALCULO_METRICA_IGUAL_ZERO);
        return false;
	}

    var justificativa = '';

    //verifica se possui a justificativa para metrica
    if( $("#tx_justificativa_metrica").size() ){
        if($("#tx_justificativa_metrica").val() == ''){
            alertMsg(i18n.L_VIEW_SCRIPT_JUSTIFICATIVA_OBRIGATORIA);
            return false;
        }
        justificativa = "&tx_justificativa_metrica="+$("#tx_justificativa_metrica").val();
    }

	$.ajax({
        type    : "POST",
        url     : systemName+"/elaboracao-proposta/salvar-calculo-metrica",
        data    : $("#div_sub_item_metrica :input").serialize()+
                  "&cd_definicao_metrica="+$("#cd_definicao_metrica").val()+
                  "&cd_projeto=" +$("#cd_projeto").val()+
                  "&cd_proposta="+$("#cd_proposta").val()+justificativa,
        dataType: 'json',
        success : function(retorno){
            if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
                    atualizaTotalHorasProjeto();
	        }
        }
    });
}

function atualizaTotalHorasProjeto()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/elaboracao-proposta/get-total-horas-projeto",
        data    : "cd_projeto="  +$("#cd_projeto").val()+
                  "&cd_proposta="+$("#cd_proposta").val(),
        success : function(retorno){
            $("#valor_total_horas_metrica_dinamica").html(retorno);
            atualizaHorasDisponivelAjax();
            atualizaGridParcela();
            atualizaParcela();
        }
    });
}