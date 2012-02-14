$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/historico/excluir-historico",
				data	: "cd_projeto="+$("#cd_projeto").val()+"&cd_proposta="+$("#cd_proposta").val(),
                dataType: 'json',
				success	: function(retorno){
                        alertMsg(retorno['msg'],retorno['type'],"window.location.href = '"+systemName+"/historico/index/cd_projeto/" + ("#cd_projeto").val()+"'");
				}
			});
        });
	});
	
	$("#cd_projeto").change(function() {
		window.location.href = systemName+"/historico/index/cd_projeto/" + $("#cd_projeto").val();	
	});
	
	$("#cd_proposta").change(function() {
		window.location.href = systemName+"/historico/index/cd_proposta/" + $("#cd_proposta").val();	
	});
	
	// pega evento no onclick do botao
	$("#submitbuttonHistorico").click(function(){
		$("form#historico").submit();
	});
});