$(document).ready(function(){
	$("#btn_button_calcular").click(function(){
		calcularRisco();
	});

	$("#btn_button_salvar_questionario").click(function(){
		if( !validaForm("#formQuestionarioAnaliseItemRisco") ){ return false; }
		salvaAnaliseRisco();
	});

	$("#st_nao_aplica_risco").change(function(){
       naoAplica();
	});	
});

function naoAplica()
{
	if($("#st_nao_aplica_risco").attr('checked') == true){
		$('#gridQuestionarioItem').hide();
    } else {
		$('#gridQuestionarioItem').show();
    }
}

function salvaAnaliseRisco()
{
	$.ajax({
        type   : "POST",
        url    : systemName+"/gerenciamento-risco/salva-questionario-analise",
        data   : $("#formQuestionarioAnaliseItemRisco :input").serialize()
                 +"&cd_projeto="+$('#cd_projeto_risco').val()
                 +"&cd_proposta="+$('#cd_proposta_risco').val(),
        dataType: 'json',         
        success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],atualizaGridRisco());
			}
        }
    });
}

function atualizaGridRisco()
{
	calcularRisco();
	gridProjetoPropostaExecucao($('#st_impacto_proposta').val());
	gridAnaliseRisco($('#cd_projeto_risco').val(), $('#cd_proposta_risco').val(), $('#st_impacto_risco').val());
	window.setTimeout("abrirAtividadeEtapa($('#cd_etapa_risco').val())",1000);
	window.setTimeout("abrirItensAtividade($('#cd_etapa_risco').val(), $('#cd_atividade_risco').val())",1000);
}

function calcularRisco()
{
	$.ajax({
        type    : "POST",
        url     : systemName+"/gerenciamento-risco/calcular-questionario",
        data    : $("#formQuestionarioAnaliseItemRisco :input").serialize()
                 +"&cd_projeto="+$('#cd_projeto_risco').val()
                 +"&cd_proposta="+$('#cd_proposta_risco').val(),
		dataType: 'json',
        success: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				$("#st_cor_projeto").removeAttr('class');
				$("#st_cor_tecnico").removeAttr('class');
				$("#st_cor_custo").removeAttr('class');
				$("#st_cor_cronograma").removeAttr('class');
				$("#st_cor_projeto").attr('class','float-l span-2 '+retorno['tx_cor_impacto_projeto_risco']);
				$("#st_cor_tecnico").attr('class','float-l span-2 '+retorno['tx_cor_impacto_tecnico_risco']);
				$("#st_cor_custo").attr('class','float-l span-2 '+retorno['tx_cor_impacto_custo_risco']);
				$("#st_cor_cronograma").attr('class','float-l span-2 '+retorno['tx_cor_impacto_cronog_risco']);
				
				//Inclui o valor nos campos 
	            $("#st_impacto_projeto_risco").val(retorno['st_impacto_projeto_risco'])
	            $("#st_impacto_tecnico_risco").val(retorno['st_impacto_tecnico_risco'])
	            $("#st_impacto_custo_risco").val(retorno['st_impacto_custo_risco'])
	            $("#st_impacto_cronograma_risco").val(retorno['st_impacto_cronograma_risco'])
			}
        }
    });
}