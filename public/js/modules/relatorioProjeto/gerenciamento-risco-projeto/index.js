$(document).ready(  function() {
	if($("#cd_contrato").val() != 0){
    	montaComboProjeto();
	}
    $('#tx_impacto').val($('#st_impacto_risco :selected').text());
    $('#st_impacto_risco').change(function(){
        $('#tx_impacto').val($('#st_impacto_risco :selected').text());
    });

    $('#cd_contrato').change(function(){
        $('#tx_contrato_objeto').val($('#cd_contrato :selected').text());
    	montaComboProjeto();
        montaComboEtapa();
    });

    $('#cd_projeto').change(function(){
        $('#tx_projeto').val($('#cd_projeto :selected').text());
        montaComboProposta();
    });
    
    $('#cd_etapa').change(function(){
    	montaComboAtividade();
    });

    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio($('#formRelatorioGerenciamentoRiscoProjeto') , 'gerenciamento-risco-projeto/risco-projeto' );
        return true;
    });
});

function montaComboProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/gerenciamento-risco-projeto/combo-projeto",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}


function montaComboEtapa()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/gerenciamento-risco-projeto/combo-etapa",
		data	: "cd_contrato="+$("#cd_contrato").val(),
		success	: function(retorno){
			$('#cd_etapa').html(retorno);
		}
	});
}

function montaComboAtividade()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/gerenciamento-risco-projeto/combo-atividade",
		data	: "cd_etapa="+$("#cd_etapa").val(),
		success	: function(retorno){
			$('#cd_atividade').html(retorno);
		}
	});
}

function montaComboProposta()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/gerenciamento-risco-projeto/combo-proposta",
		data: "cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			$("#cd_proposta").html(retorno);
		}
	});
}