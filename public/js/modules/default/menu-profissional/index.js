$(document).ready(function() {
	//  inicializando componente accordion
	$('.accordion-closed').each(function(){
		accordionToggle($(this));
	});
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