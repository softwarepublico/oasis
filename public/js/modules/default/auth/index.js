$(document).ready(function(){
	$(this).bind('keypress',function(e){
		e=e||window.event;
		var k=e.charCode||e.keyCode||e.which;
		if( k == 13 ){
			enviaObjetoContrato();
		}
	});
	$('#contrato_inativo').click(function(){
		getObjetoContratoInativo();
	});
	
	if($('#st_inativo').val() != "S"){
		$('#id_contrato_inativo').show();		
	}
	
});

function enviaObjetoContrato()
{
	$.ajax({
		type: "POST",
		url: systemName+"/auth/inicia-sistema",
		data: $('#formObjeto :input').serialize(),
		success: function(retorno){
			window.location.href = retorno;
		}
	});
}

function getObjetoContratoInativo()
{
	window.location.href = window.location.href+"/st_inativo/S";
}