$(document).ready(function(){
	
	$('#cd_contrato_questionario').change(function(){
		montaComboProjeto();
	    montaComboProposta();
	});
	
	$('#cd_projeto_questionario').change(function(){
		montaComboProposta();
		$('#montaQuestionario').hide().html('');
		//abreQuestionarioForm();
	});
		
	$('#cd_proposta_questionario').change(function(){
		abreQuestionarioForm();
	});
});

function validaDadosQuestionario()
{
    var msg = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;
	if($('#cd_projeto_questionario').val() == 0) {
		showToolTip(msg,$('#cd_projeto_questionario'));
		$('#cd_projeto_questionario').focus();
		return false;
	} 
	if($('#cd_proposta_questionario').val() == 0) {
		showToolTip(msg,$('#cd_proposta_questionario'));
		$('#cd_proposta_questionario').focus();
		return false;
	}
	return true;
}

function abreQuestionarioForm()
{
	if($('#cd_projeto_questionario').val() != "0" && $('#cd_proposta_questionario').val() != "0"){
		montaQuestionario();
	} else {
		$('#montaQuestionario').hide();
	}
}

function montaComboProjeto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/associar-projeto-contrato/pesquisa-projeto-contrato",
		data	: "cd_contrato="+$('#cd_contrato_questionario').val(),
		success	: function(retorno){
			$('#cd_projeto_questionario').html(retorno);
		}
	});	
}

function montaComboProposta()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/questionario/monta-combo-proposta",
		data	: "cd_projeto="+$('#cd_projeto_questionario').val(),
		dataType: 'json',
		success	: function(retorno){
			$('#cd_proposta_questionario').html(retorno);
		}
	});
}

function montaQuestionario()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/questionario/questionario-form",
		data	: "cd_projeto="+$('#cd_projeto_questionario').val()+
                  "&cd_proposta="+$('#cd_proposta_questionario').val(),
		success	: function(retorno){
			$('#montaQuestionario').html(retorno);
			$('#montaQuestionario').show('slow');
		}
	});
}