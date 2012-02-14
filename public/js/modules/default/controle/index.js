$(document).ready(function(){
	/*
	* Accordions
	*/
	//  inicializando componente accordion
	$('.accordion-closed').each(function(){
		accordionToggle($(this));
	});
	
    montaComboGroupContratoAjax();
    
    $('#config_hidden_penalizacao').val('N');

});

function hideAllAccordions(){
	$(".accordion").each(function(){
		$(this).hide('slow');
		$(this).prev().removeClass('accordion-open').addClass('accordion-closed');
	});
}

function accordionToggle(objTitulo){
	objTitulo.click(function() {
	    if ( objTitulo.next().css('display') == 'none') {
	    	hideAllAccordions();
	    	objTitulo.next().show('slow');
	    	objTitulo.removeClass('accordion-closed').addClass('accordion-open');
	    	
	    } else {
	    	objTitulo.next().hide('slow');
	   		objTitulo.removeClass('accordion-open').addClass('accordion-closed');
	    }
	});
}

function montaComboGroupContratoAjax()
{
	var tipo_objeto = 'P';
	
	$.ajax({
		type: "POST",
		url: systemName+"/contrato/combo-group-contrato",
		data: {'tipo_objeto':tipo_objeto},
		success: function(retorno){
			$("#cd_contrato_controle_proposta"          ).html(retorno);
			$("#cd_contrato_controle_alteracao_proposta").html(retorno);
			$("#cd_contrato_controle_parcela"           ).html(retorno);
			$("#cd_contrato"                            ).html(retorno); //Tab Extrato Mensal
			$("#cd_contrato_questionario"               ).html(retorno);
			$("#cd_contrato_documentacao"               ).html(retorno);
			$("#cd_contrato_controle_encerramento_proposta").html(retorno); 
			$("#cd_contrato_avaliacao_qualidade"        ).html(retorno);
		}
	});
}