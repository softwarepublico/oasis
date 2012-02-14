$(document).ready(function(){
	
	$('#ano_penalizacao_justificativa').change(function(){
		apresentaData($('#mes_penalizacao_justificativa').val(),$('#ano_penalizacao_justificativa').val(),'mesAnoJustificativaPenalizacao');
		gridJustificativaPenalizacao();
	});
	$('#mes_penalizacao_justificativa').change(function(){
		apresentaData($('#mes_penalizacao_justificativa').val(),$('#ano_penalizacao_justificativa').val(),'mesAnoJustificativaPenalizacao');
		gridJustificativaPenalizacao();
	});
});

function gridJustificativaPenalizacao()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/penalizacao/grid-justificativa",
		data	: "ano="+$('#ano_penalizacao_justificativa').val()
				 +"&mes="+$('#mes_penalizacao_justificativa').val(),
		success	: function(retorno){
			$('#gridJustificativaPenalizacao').html(retorno);
		}
	});
}