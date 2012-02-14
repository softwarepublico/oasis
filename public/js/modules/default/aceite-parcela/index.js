$(document).ready(function() {
	$("#st_aceite_parcela_S").click(function(){
		$("#lb_tx_obs_aceite_parcela").removeClass('required');
	});

	$("#st_aceite_parcela_N").click(function(){
		$("#lb_tx_obs_aceite_parcela").addClass('required');
	});
});