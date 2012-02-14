$(document).ready(function() {
	
	$("#st_homologacao_parcela_A").click(function(){
		$("#lb_tx_obs_homologacao_parcela").removeClass('required');
	});

	$("#st_homologacao_parcela_N").click(function(){
		$("#lb_tx_obs_homologacao_parcela").addClass('required');
	});
});