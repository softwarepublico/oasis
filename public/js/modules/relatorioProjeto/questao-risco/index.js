$(document).ready(  function() {
	if($("#cd_objeto").val() != 0){
    	montaComboEtapa();
    	montaComboAtividade();
	}

    $('#cd_objeto').change(function(){
    	montaComboEtapa();
    	montaComboAtividade();
    });
    
    $('#cd_etapa').change(function(){
    	montaComboAtividade();
    });

    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioQuestaoRisco') , 'questao-risco/relatorio-questao-risco' );
        return true;
    });
});

function montaComboEtapa()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/questao-risco/monta-combo-etapa",
		data	: "cd_objeto="+$("#cd_objeto").val(),
		success	: function(retorno){
			$('#cd_etapa').html(retorno);
		}
	});
}

function montaComboAtividade()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/questao-risco/monta-combo-atividade",
		data	: "cd_etapa="+$("#cd_etapa").val(),
		success	: function(retorno){
			$('#cd_atividade').html(retorno);
		}
	});
}