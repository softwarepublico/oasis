$(document).ready(function(){
	$("#cd_objeto").change(function() {
    	$.ajax({
			type	: "POST",
			url		: systemName+"/solicitacao-service-desk/pesquisa-item-inventariado-service-desk",
			data	: {"cd_objeto":$(this).val()},
			success	: function(retorno){
				$("#cd_item_inventariado").html(retorno);
			}
		});
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if( !validaForm('#SOLICITACAO_SERVICE_DESK') ){ return false; }
		$("form#SOLICITACAO_SERVICE_DESK").submit();	
	});
});