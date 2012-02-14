$(document).ready(function(){
	/*$("#tabelaEncerramentoProposta").tablesorter({ 
        // enable debug mode 
        //debug: true 
    })*/
    
    $("#cd_contrato_controle_encerramento_proposta").change(function(){
    	if ($("#cd_contrato_controle_encerramento_proposta").val() != 0){
	    	gridControleEncerramentoPropostaAjax();
    	} else {
    		$("#gridControleEncerramentoProposta").html('');
    	}
    });
});

function gridControleEncerramentoPropostaAjax() {

	$.ajax({
		type: "POST",
		url: systemName+"/controle-encerramento-proposta/grid-controle-encerramento-proposta",
		data: "cd_contrato="+$("#cd_contrato_controle_encerramento_proposta").val(),
		success: function(retorno){
			$("#gridControleEncerramentoProposta").html(retorno);
		}
	});
}

function encerraProposta(cd_projeto, cd_proposta) {
	
	$.ajax({
		type: "POST",
		url: systemName+"/proposta/encerrar-proposta",
		data: "cd_projeto="+cd_projeto+"&cd_proposta="+cd_proposta,
		success: function(retorno){
			alertMsg(retorno,'',"gridControleEncerramentoPropostaAjax()");
		}
	});
}
