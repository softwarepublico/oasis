$(document).ready(function() {
	$("#st_homologacao_proposta_A").click(function(){
		$("#lb_tx_obs_homologacao_proposta").removeClass('required');
	});

	$("#st_homologacao_proposta_N").click(function(){
		$("#lb_tx_obs_homologacao_proposta").addClass('required');
	});
});