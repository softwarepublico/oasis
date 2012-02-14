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

function comboContratoAtivo(id) {
    $.ajax({
		type	: "POST",
		url		: systemName+"/contrato/pesquisa-contrato-ativo",
		data    : "comSelecione=1&comDescricao=1",
		success	: function(retorno){
			$("#"+id).html(retorno);
		}
	});
}

function comboEmpresa(id, funcao) {
	$.ajax({
		type	: "POST",
		url		: systemName+"/empresa/combo-empresa",
		success	: function(retorno){
			$("#"+id).html(retorno);
			if (funcao != "undefined") {
				eval(funcao);
			}
		}
	});
}

function comboContratoAtivoObjeto(id) {
	$.ajax({
		type	: "POST",
		url		: systemName+"/contrato/pesquisa-contrato-ativo-objeto",
		success	: function(retorno){
			$("#"+id).html(retorno);
		}
	});
}

function comboGroupContrato(tipo_objeto, id) {
	$.ajax({
		type	: "POST",
		url		: systemName+"/contrato/combo-group-contrato",
		data	: "tipo_objeto="+tipo_objeto,
		success	: function(retorno){
			$("#"+id).html(retorno);
		}
	});
}

function comboObjetoContratoAtivo(id, tipo_objeto)
{
	var DataPost = (tipo_objeto == undefined) ? '' : {'tipo_objeto':tipo_objeto};

	$.ajax({
		type	: "POST",
		url		: systemName+"/objeto-contrato/pesquisa-objeto-contrato-ativo",
		data	: DataPost,
		success	: function(retorno){
			$("#"+id).html(retorno);
		}
	});
}

function comboAreaAtuacao(id)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/menu-contrato/combo-area-atuacao",
		success	: function(retorno){
			$("#"+id).html(retorno);
		}
	});
}