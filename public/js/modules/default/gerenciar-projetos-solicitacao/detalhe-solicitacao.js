$(document).ready(function(){
	$("#bt_fechar_tab_detalhe_solicitacao").click(function (){
		var tab_origem = parseInt($('#tab_origem').val());
		$('#container-gerenciarProjeto').triggerTab(tab_origem);
		$("#li-detalhe-solicitacao").css("display", "none");
	});		
});
