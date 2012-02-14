$(document).ready(function(){
	$("#bt_fechar_tab_solicitacao_demanda").click(function (){
		var tab_origem = parseInt($('#tab_origem').val());
		$('#container-solicitacao-servico').triggerTab(tab_origem);
		$("#li-detalhe-solicitacao").css("display", "none");
	});		
});

