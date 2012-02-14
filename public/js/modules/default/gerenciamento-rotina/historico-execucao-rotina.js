$(document).ready(function(){
	$('#btn_cancelar_historico_execucao_rotina').click(function(){
		var tab_origem = parseInt($('#tab_origem').val());
		$('#container_painel_rotina').triggerTab(tab_origem);
		$("#li-historico-rotina").css("display", "none");
	});
});
