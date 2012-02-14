$(document).ready(function() {
	$("#st_aceite_proposta_S").click(function(){
		$("#lb_tx_obs_aceite_proposta").removeClass('required');
	});

	$("#st_aceite_proposta_N").click(function(){
		$("#lb_tx_obs_aceite_proposta").addClass('required');
	});
});