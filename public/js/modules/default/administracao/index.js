var _btnExecutar;

$(document).ready(function() {
	//  inicializando componente accordion
	$('.accordion-closed').each(function(){
		accordionToggle($(this));
	});

    _btnExecutar = $('#btn_executar');
    _btnExecutar.click(_fnExecutaSelect);
});

  
/*
 * Accordions
 */
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

function _fnExecutaSelect(){
    $.ajax({
		type	: "POST",
		url		: systemName+"/administracao/grid-executa",
		data	: $("#tx_select").serialize(),
		success	: function(retorno){
            $('#grid_resultado_select').html(retorno);
		}
	});
    
    
}