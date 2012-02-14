$(document).ready(function(){

	$("#bt_cancelar_alocacao_recurso_proposta_anterior").click(function() {
		$('#container-proposta').triggerTab(4);
		$("#li-alocacao").css("display", "none");
	});
	
	$("#bt_salvar_alocacao_recurso_proposta_anterior").click(function() {
		
		if( !validaAlocacao() ){ return false; }
		
		var postData = $('#formAlocacaoRecursoPropostaAnterior :input').serialize();

		$.ajax({
			type: "POST",
			url: systemName+"/alocacao-recurso-proposta-contrato-anterior/salvar",
			data: postData,
			success: function(retorno){
				alertMsg(retorno,2,"fechaTabAlocacao()");
			}
		});
	});

	if ($('#st_parcela_orcamento').val() == '') {
		$('#formAlocacaoRecursoProposta :input').each(function(){
			if(this.name.substr(0, 15) == 'modulo_proposta'){
				this.disabled = true;
			}
		});
	}
})


function verificaAlocacao() 
{
    var resultado        = true;
	var soma_horas       = 0;
	var saldo            = 0;
	var valor_alocado    = 0;
	
	$('#tableProjetosPrevistos :input[id^=cd_projeto_previsto_]').each(function(){
		if ($(this).val() > 0)
		{
			id = $(this).attr('id');
			codigo = id.replace(/cd_projeto_previsto_/, "");
			
			valor_alocado = $(this).val();
			saldo         = $('#saldo_'+codigo).val();
			
			if (parseFloat(valor_alocado) > parseFloat(saldo)){
                var arrValues = new Array(valor_alocado,saldo);
			  	//alertMsg("Valor Alocado ("+valor_alocado+") é Maior que o Saldo do Projeto ("+saldo+")!");
                alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_VALOR_ALOCADO_MAIOR_SALDO_PROJETO, arrValues));
				resultado = false;
			}
			soma_horas += parseFloat(valor_alocado);
		}
	});
	
    $('#soma_horas').val(soma_horas);
	return resultado;
}

function validaAlocacao()
{
     var resultado = verificaAlocacao();
	 if (resultado == true){
		 if (Math.abs(parseFloat($('#soma_horas').val())) < Math.abs(parseFloat($('#nu_horas_proposta').val()))){
			var sobra = parseFloat($('#nu_horas_proposta').val()) - parseFloat($('#soma_horas').val());

            var arrValueMsg = new Array(sobra);
            //confirmMsg('Faltam '+sobra+' horas para completar proposta! Deseja prosseguir?', function(){
            confirmMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_FALTAM_HORAS_COMPLETAR_PROPOSTA, arrValueMsg), function(){
				resultado = true;
			},function(){
				resultado = false;
			});
		 }
		 if (Math.abs(parseFloat($('#soma_horas').val())) > Math.abs(parseFloat($('#nu_horas_proposta').val()))){

             var arrValueMsg2 = new array($('#soma_horas').val(),$('#nu_horas_proposta').val());
			 if (parseFloat($('#soma_horas').val()) > 0){
                alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_HORA_ALOCADA_MAIOR_HORA_PROPOSTA, arrValueMsg2));
//				alertMsg("Número de Horas Alocado ("+$('#soma_horas').val()+") é maior que Horas da Proposta ("+$('#nu_horas_proposta').val()+")!");
				resultado = false;
			 }
			 else{
                 alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_HORA_ALOCADA_MENOR_HORA_PROPOSTA, arrValueMsg2));
				//alertMsg("Número de Horas Alocado ("+$('#soma_horas').val()+") é menor que Horas da Proposta ("+$('#nu_horas_proposta').val()+")!");
				resultado = false;
			 }
		 }
		 if (parseFloat($('#soma_horas').val()) > parseFloat($('#soma_total_contrato').val())){
             var arrValueMsg3 = new array($('#soma_horas').val(),$('#soma_total_contrato').val());
             alertMsg(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_HORAS_ALOCADAS_MAIOR_HORAS_ALOCADAS_DENTRO_CONTRATO, arrValueMsg3));
//			 alertMsg("Número de Horas Alocado ("+$('#soma_horas').val()+") é maior que Horas a Serem Alocadas Dentro do Contrato ("+$('#soma_total_contrato').val()+")!");
			 resultado = false;
		 }
		 
		return resultado;
	 }	
}

function SomaHoras() {
	var horas     = 0;
	var total     = 0;

	$('#tableProjetosPrevistos :input[id^=cd_projeto_previsto_]').each(function(){
		if ($(this).val() > 0)
		{
			horas = $(this).val();
			
			if (horas == ''){
				horas = 0;
			}
			total += parseFloat(horas);
		}
	});
	
	$('#total').val(total);
}

function fechaTabAlocacao()
{
	$('#container-proposta').triggerTab(4);
	$("#li-alocacao").css("display", "none");
	gridAlocacaoRecursoPropostaContratoAnteriorAjax();
}
