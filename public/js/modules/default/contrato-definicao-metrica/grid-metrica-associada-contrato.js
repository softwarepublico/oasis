$(document).ready(function(){
	$("#btn_salvar_config_fator_metrica").click(function(){
		salvarDadosFatoresMetricaContrato();
	});
});

function salvarDadosFatoresMetricaContrato()
{
	if( !validaCamposFator() ){return false;}

	$.ajax({
		type	: "POST",
		url		: systemName+"/contrato-definicao-metrica/salvar-dados-fator-metrica",
		data	: $("#tableMetricaAssociadaContrato :input").serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['errorType']);
			}else{
				alertMsg(retorno['msg'],'',function(){pesquisaPorContratoAjax()});
			}
		}
	});
}

function validaCamposFator()
{
	var retorno = true;

	if ( $("#tableMetricaAssociadaContrato input[name=st_padrao]:checked").val() == undefined ) {
		alertMsg(i18n.L_VIEW_SCRIPT_DEFINICAO_METRICA_PADRAO_OBRIGATORIO,2);
		retorno = false;
	}
	$("#tableMetricaAssociadaContrato :text").not(':disabled').each(function(){
		if( $(this).val() == '' ){
			showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO, $("#"+$(this).attr('id')));
			retorno = false;
		}
	});
	return retorno;
}