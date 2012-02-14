$(document).ready(function(){
    $('#div_valor_contrato').hide();

	$("#bt_salvar_custo_contrato_demanda").click(function(){
		validaValoresCustoContratoDemanda();
 	});
	$("#bt_salvar_custo_contrato_demanda_metrica").click(function(){
		salvarCustoContratoDemandaMetrica();
 	});

	$('#cd_contrato_custo_contrato_demanda').change(function(){
		if($('#cd_contrato_custo_contrato_demanda').val() != "0"){
			recuperaCustoContratoDemanda();
		} else {
			limpaDadosCustoContratoDemanda();
		}
	});
	$('#cd_contrato_custo_metrica').change(function(){
		if($('#cd_contrato_custo_metrica').val() != "0"){
            gridCustoMetrica();
        }      
	});
});

function recuperaCustoContratoDemanda()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/custo-contrato-demanda/recupera-custo-contrato-demanda",
		data	: "cd_contrato="+$("#cd_contrato_custo_contrato_demanda").val()+
				  "&ni_mes_custo_contrato_demanda="+$("#ni_mes_custo_contrato_demanda").val()+
				  "&ni_ano_custo_contrato_demanda="+$("#ni_ano_custo_contrato_demanda").val(),
		dataType: 'json',
		success	: function(retorno){
				var valor_contrato_demanda_mes = converteFloatMoeda(retorno['valor_contrato_demanda_mes']);
				var nf_total_multa = (retorno['nf_total_multa'])? converteFloatMoeda(retorno['nf_total_multa']) : '0,00';
				var nf_total_glosa = (retorno['nf_total_glosa'])? converteFloatMoeda(retorno['nf_total_glosa']) : '0,00';
				var nf_total_pago = (retorno['nf_total_pago'])? converteFloatMoeda(retorno['nf_total_pago']) : '0,00';
				$('#nf_total_multa'             ).val(nf_total_multa);
				$('#nf_total_glosa'             ).val(nf_total_glosa);
				$('#nf_total_pago'              ).val(nf_total_pago);
				$('#valor_contrato_demanda_mes' ).val(valor_contrato_demanda_mes);
				$('#div_valor_contrato'         ).show();
				$('#p_valor_contrato'           ).text(i18n.L_VIEW_SCRIPT_CIFRAO_MOEDA+valor_contrato_demanda_mes);
		}
	});
}

function limpaDadosCustoContratoDemanda()
{
	$('#cd_contrato_custo_contrato_demanda').val(0);
	$('#nf_total_multa').val("");
	$('#nf_total_glosa').val("");
	$('#nf_total_pago' ).val("");
	$('#div_valor_contrato').hide();
	$('#p_valor_contrato'  ).text('');
}

function limpaDadosCustoContratoDemandaPorMetrica()
{
	$('#cd_contrato_custo_metrica').val(0);
	$('#cd_projeto_custo_metrica').val(0);
    $('#nf_qtd_unidade_metrica' ).val("");
}

function salvarCustoContratoDemanda()
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DADO_GRAVADO_NAO_PODE_SER_EXCLUIDO_PROSSEGUIR,
		function(){
			$.ajax({
				type    : "POST",
				url     : systemName+"/custo-contrato-demanda/salvar",
				data    : $('#formCustoContratoDemanda :input').serialize(),
				dataType: 'json',
				success	: function(retorno){
					if(retorno['erro'] == true){
						alertMsg(retorno['msg'],retorno['type']);
					} else {
						alertMsg(retorno['msg'],retorno['type'],'limpaDadosCustoContratoDemanda()');
					}
				}
			});
		});
}
function salvarCustoContratoDemandaMetrica()
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DADO_GRAVADO_NAO_PODE_SER_EXCLUIDO_PROSSEGUIR,
		function(){
			$.ajax({
				type    : "POST",
				url     : systemName+"/custo-contrato-demanda/salvar-por-metrica",
				data    : $('#formCustoContratoDemandaPorMetrica :input').serialize(),
				dataType: 'json',
				success	: function(retorno){
					if(retorno['erro'] == true){
						alertMsg(retorno['msg'],retorno['type']);
					} else {
                        gridCustoMetrica();
						alertMsg(retorno['msg'],retorno['type'],'limpaDadosCustoContratoDemandaPorMetrica()');
					}
				}
			});
		});
}

function validaValoresCustoContratoDemanda()
{
	var total                      = parseFloat(transformaValor($('#nf_total_multa').val())) +
								     parseFloat(transformaValor($('#nf_total_glosa').val())) +
									 parseFloat(transformaValor($('#nf_total_pago').val()));
	var valor_contrato_demanda_mes = parseFloat(transformaValor($('#valor_contrato_demanda_mes' ).val()));
	var msg                        = "";
	var diferenca                  = parseFloat(valor_contrato_demanda_mes - total);

	if(total > valor_contrato_demanda_mes){
        var arrValueMsg = new Array(converteFloatMoeda(total),converteFloatMoeda(valor_contrato_demanda_mes));

		//msg = "A soma dos valores é maior que o valor contratado para o mês. (" + converteFloatMoeda(total) + " > " + converteFloatMoeda(valor_contrato_demanda_mes) + ")";
        msg = getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_SOMA_VALOR_MAIOR_VALOR_CONTRATADO_MES, arrValueMsg);
		alertMsg(msg);
		return false;
	}else{
		if(total < valor_contrato_demanda_mes)
		{
            var arrValueMsg = new Array(converteFloatMoeda(diferenca.toFixed(2)));
//            confirmMsg('Faltam R$' + converteFloatMoeda(diferenca.toFixed(2)) + ' para o valor contratado para o mês! Deseja prosseguir?',
            confirmMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_FALTA_VALOR_CONTRATADO_PARA_MES_CONTINUAR, arrValueMsg),
				function(){
					salvarCustoContratoDemanda();
				}
			);
		}else{
			salvarCustoContratoDemanda();
		}
	}
	
}

function transformaValor(valor)
{
	var novo       = valor.replace(".","");
	var novo_valor = novo.replace(",",".");
	return novo_valor;
}

function gridCustoMetrica()
{
	$.ajax({
		type    : "POST",
		url     : systemName+"/custo-contrato-demanda/grid-custo-contrato-metrica",
    	data    : $('#formCustoContratoDemandaPorMetrica :input').serialize(),
		success: function(retorno){
            $('#gridCustoContratoMetrica').html(retorno);
		}
	});
}
