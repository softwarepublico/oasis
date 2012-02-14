$(document).ready(function(){

    executaPreDemandaExecutadaAjax();
	//apresentaData($("#mesPreDemandaExecutada").val(),$("#anoPreDemandaExecutada").val(),"mesAnoDemandaExecutada");
	
	var intervalID = window.setInterval('executaPreDemandaExecutadaAjax();', 300000);

	$("#mesPreDemandaExecutada").change(function() {
		executaPreDemandaExecutadaAjax();
		apresentaData($("#mesPreDemandaExecutada").val(),$("#anoPreDemandaExecutada").val(),"mesAnoDemandaExecutada");
	});
	
	$("#anoPreDemandaExecutada").change(function() {
		executaPreDemandaExecutadaAjax();
		apresentaData($("#mesPreDemandaExecutada").val(),$("#anoPreDemandaExecutada").val(),"mesAnoDemandaExecutada");
	});

	$("#cd_objeto_receptor_executada").change(function() {
		if ($("#cd_objeto_receptor_executada").val() != "0") {
			executaPreDemandaExecutadaAjax();
		}
	});
	
	$('#bt_salvar_aceite_pre_demanda').click(function () {

		if($("input[name=st_aceite_pre_demanda]:checked").val() == undefined ){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_TIPO_AVALIACAO);
			return false;
		}	
		
		if($("input[name=st_aceite_pre_demanda]:checked").val() == 'N' && $("#tx_obs_aceite_pre_demanda").val() == ''){
			alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_MOTIVO_ACEITE_NEGATIVO);
			return false;
		}				

		var postData = $('#formAceitePreDemanda :input').serialize();

		$.ajax({
			type	: "POST",
			url		: systemName+'/pre-demanda-painel/aceitar-pre-demanda',
			data	: postData,
			success	: function(retorno){
		        alertMsg(retorno,'',"redirecionaPreDemandaPainel()");
			}
		});
	});	
	
	$('#bt_retornar').click(function (e) {
		window.location.href = systemName+"/pre-demanda-painel";
	});			
});
function redirecionaPreDemandaPainel(){
    window.location.href = systemName+"/pre-demanda-painel\#pre-demanda-executada";
}

function executaPreDemandaExecutadaAjax() {
	$.ajax({
		type	: "POST",
		url		: systemName+"/pre-demanda-painel/grid-pre-demanda-executada",
		data	: "mes="+$("#mesPreDemandaExecutada").val()+
				  "&ano="+$("#anoPreDemandaExecutada").val()+
				  "&cd_objeto_receptor="+$("#cd_objeto_receptor_executada").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridPreDemandaExecutada").html(retorno);
			$('#mesAnoExecutada').html($('#mesAnoPreDemandaExecutada').val());
		}
	});
}

function registrarAceitePreDemanda(cd_pre_demanda) {
	window.location.href = systemName+"/pre-demanda-painel/aceite-pre-demanda/cd_pre_demanda/"+cd_pre_demanda;
}