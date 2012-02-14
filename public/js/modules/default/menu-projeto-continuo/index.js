$(document).ready(function(){
/**
 * Tela de Projeto Contínuo
 */
 	//Botões da tela.
	$('#submitbuttonProjetoContinuado').hide();
	$('#adicionarProjetoContinuo').show();
	$('#alterarProjetoContinuo').hide();
	$('#cancelarProjetoContinuo').hide();
	
	$('#adicionarProjetoContinuo').click(function(){
		//função no script da tela de projeto continuo
		salvarProjetoContinuado();
	});
	$('#alterarProjetoContinuo').click(function(){
		alterarProjetoContinuado();
	});
	$('#cancelarProjetoContinuo').click(function(){
		cancelarProjetoContinuado();
	});
/**
 * Tela de Modulo Contínuo
 */
 	//Botões da tela.
	$('#submitbuttonModuloContinuado').hide();
 	$('#adicionarModuloContinuo'	 ).show();
 	$('#alterarModuloContinuo'		 ).hide();
 	$('#cancelarModuloContinuo'		 ).hide();
 	
 	$('#adicionarModuloContinuo').click(function(){
 		salvarModuloContinuo();
 	});
 	$('#alterarModuloContinuo').click(function(){
 		alterarModuloContinuo();
 	});
 	$('#cancelarModuloContinuo').click(function(){
 		cancelarModuloContinuo();
 	});
/**
 * Tela de Histórico Projeto Contínuo
 */
	//Botões da tela.
	$('#submitbuttonHistoricoProjetoContinuado'	).hide();
	$('#adicionarHistoricoProjetoContinuo'		).show();
	$('#alterarHistoricoProjetoContinuo'		).hide();
	$('#cancelarHistoricoProjetoContinuo'		).hide();
	
	$('#adicionarHistoricoProjetoContinuo').click(function(){
		salvarHistoricoContinuado();
	});
	$('#alterarHistoricoProjetoContinuo').click(function(){
		alterarHistoricoContinuado();
	});
	$('#cancelarHistoricoProjetoContinuo').click(function(){
		cancelarHistoricoContinuado();
	});
});