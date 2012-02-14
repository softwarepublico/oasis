$(document).ready(function(){
	$('#btn_salvar_dados_questionario').click(function(){
		salvaDadosQuestionario();
	});

	$('#btn_gerar_avalicao_qualidade').click(function(){
		gerarAvaliacaoQualidade();
	});
});

function salvaDadosQuestionario()
{
	if(!validaDadosQuestionario()){return false; }
	$.ajax({
		type	: "POST",
		url		: systemName+"/questionario/trata-dados-questionario",
		data	: $('#formQuestionarioForm :input').serialize()
				 +"&cd_projeto="+$('#cd_projeto_questionario').val()
				 +"&cd_proposta="+$('#cd_proposta_questionario').val(),
		success: function(retorno){
			alertMsg(retorno);
		}
	});
}

function gerarAvaliacaoQualidade(){
	confirmMsg(i18n.L_VIEW_SCRIPT_CALCULO_AVALIACAO_QUALIDADE_SALVAR_QUESTIONARIO, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/questionario/gera-avaliacao-qualidade",
			data	: "cd_projeto="+$('#cd_projeto_questionario').val()
					 +"&cd_proposta="+$('#cd_proposta_questionario').val(),
			dataType: 'json',
			success : function(retorno){
				alertMsg(retorno['msg'],retorno['type']);
			}
		});
	});
}