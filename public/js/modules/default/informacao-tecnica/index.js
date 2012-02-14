$(document).ready(function(){
	$("#cd_projeto").change(function() {
		if ($("#cd_projeto").val() != "0") {
			$.ajax({
				type	: "POST",
				url		: systemName+"/informacao-tecnica/listar",
				data	: "cd_projeto="+$("#cd_projeto").val(),
				success	: function(retorno){
					// atualiza a grid
					$("#grid").html(retorno);
				}
			});
		}
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#informacao_tecnica").submit();
	});
});