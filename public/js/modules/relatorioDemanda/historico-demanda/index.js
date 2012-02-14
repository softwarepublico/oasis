$(document).ready(function(){
	$('#btn_gerar').click(function(){
		if(!validaForm()){ return false; }
		gerarRelatorio( $('#formRelatorioDemandaHistorico'), 'historico-demanda/historico-demanda');
	});
});