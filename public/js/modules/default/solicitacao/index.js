$(document).ready(function(){
	$("#cd_objeto").change(function() {
		// Pesquisa tipo de solicitacao (combo)
		$.ajax({
			type	: "POST",
			url		: systemName+"/solicitacao/pesquisa-tipo-solicitacao",
			data	: "cd_objeto="+$("#cd_objeto").val(),
			success	: function(retorno){
				$("#st_solicitacao").html(retorno);
			}
		});
	});
    
    	// pega evento no onclick do botao
	$("#caddocorigembutton").click(function(){
		window.location.href = systemName+"/documento-origem";
	});
    
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if( !validaForm() ){ return false; }
		$("form#solicitacao").submit();	
	});
});