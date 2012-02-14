$(document).ready(function(){
	
	$("#div_dia_semana").hide();
    $("#div_dia_mes").hide();
    _label_ni_dia_mes_rotina    = $("#ni_dia_mes_rotina").prev('label');
    _label_ni_dia_semana_rotina = $("#ni_dia_semana_rotina").prev('label');
    
    $("#cd_area_atuacao_ti_rotina").change(function() {
		if ($(this).val() != 0) {
			montaGridRotina();
		}else{
			$("#gridRotina").html("").hide();
		}
	});
	
	$("#bt_cancelar_rotina").click(function() {
		limpaDadosRotina();
	});
		
	$("#bt_salvar_rotina").click(function(){
		salvaRotina();
	});

	$("#st_periodicidade_rotina").change(function(){
        switch ($("#st_periodicidade_rotina").val())
        {
            case 'D':
                $("#div_dia_semana").hide();
                $("#div_dia_mes").hide();
                $("#ni_dia_semana_rotina").val('');
                $("#ni_dia_mes_rotina").val('');
                _label_ni_dia_semana_rotina.removeClass('required');
                _label_ni_dia_mes_rotina.removeClass('required');
                break;
            case 'S':
                $("#div_dia_semana").show();
                $("#div_dia_mes").hide();
                $("#ni_dia_semana_rotina").val('');
                $("#ni_dia_mes_rotina").val('');
                _label_ni_dia_semana_rotina.addClass('required');
                _label_ni_dia_mes_rotina.removeClass('required');
                break;
            case 'M':
                $("#div_dia_semana").hide();
                $("#ni_dia_mes_rotina").val('');
                $("#ni_dia_semana_rotina").val('');
                $("#div_dia_mes").show();
                _label_ni_dia_mes_rotina.addClass('required');
                _label_ni_dia_semana_rotina.removeClass('required');
                break;
            default:
                $("#div_dia_semana").hide();
                $("#div_dia_mes").hide();
                $("#ni_dia_semana_rotina").val('');
                $("#ni_dia_mes_rotina").val('');
                _label_ni_dia_semana_rotina.removeClass('required');
                _label_ni_dia_mes_rotina.removeClass('required');
        }
	});

    
});

function montaGridRotina()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/rotina/grid-rotina",
		data	: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti_rotina").val()},
		success	: function(retorno){
			// atualiza a grid
			$("#gridRotina").html(retorno);
			$("#gridRotina").show();
		}
	});
}

function salvaRotina()
{
	if(!validaForm("#rotina")){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/rotina/salvar",
		data    : $('#rotina :input').serialize(),
		dataType: 'json',
		success: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaDadosRotina()');
			}
		}
	});
}

function limpaDadosRotina()
{
	$('#cd_rotina'                      ).val("");
	$('#tx_rotina'                      ).val("");
	$('#tx_hora_inicio_rotina'          ).val("");
	$('#st_periodicidade_rotina'		).val("");
	$('#ni_prazo_execucao_rotina'		).val("");
    $('#ni_dia_semana_rotina'           ).val("");
    $('#ni_dia_mes_rotina'              ).val("");
    $('#st_rotina_inativa').removeAttr('checked');
	 montaGridRotina();
}

function recuperaRotina(cd_rotina)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/rotina/recupera-rotina",
		data	: "cd_rotina="+cd_rotina,
		dataType:'json',
		success	: function(retorno){
			$('#cd_rotina'			     ).val(retorno['cd_rotina']);
			$('#tx_rotina'			     ).val(retorno['tx_rotina']);
			$('#tx_hora_inicio_rotina'	 ).val(retorno['tx_hora_inicio_rotina']);
			$('#st_periodicidade_rotina' ).val(retorno['st_periodicidade_rotina']);
			$('#ni_prazo_execucao_rotina').val(retorno['ni_prazo_execucao_rotina']);

            switch (retorno['st_periodicidade_rotina'])
            {
                case 'D':
                    $("#div_dia_semana").hide();
                    $("#div_dia_mes").hide();
                    $("#ni_dia_semana_rotina").val('');
                    $("#ni_dia_mes_rotina").val('');
                    break;
                case 'S':
                    $("#div_dia_semana").show();
                    $('#ni_dia_semana_rotina').val(retorno['ni_dia_semana_rotina']);
                    $("#ni_dia_mes_rotina").val('');
                    $("#div_dia_mes").hide();
                    break;
                case 'M':
                    $('#ni_dia_mes_rotina').val(retorno['ni_dia_mes_rotina']);
                    $("#div_dia_mes").show();
                    $("#div_dia_semana").hide();
                    $("#ni_dia_semana_rotina").val('');
                    break;
                default:
                    $("#div_dia_semana").hide();
                    $("#div_dia_mes").hide();
                    $("#ni_dia_semana_rotina").val('');
                    $("#ni_dia_mes_rotina").val('');
            }

            if (retorno['st_rotina_inativa'] == "S") {
				$('#st_rotina_inativa').attr('checked','checked');
			} else {
				$('#st_rotina_inativa').removeAttr('checked');
			}

		}
	});
}

function excluiRotina(cd_rotina)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/rotina/excluir",
			data	: {"cd_rotina" : cd_rotina},
            dataType: 'json',
            success: function(retorno){
                if(retorno['erro'] == true){
                    alertMsg(retorno['msg'],retorno['type']);
                } else {
                    alertMsg(retorno['msg'],retorno['type'],'limpaDadosRotina()');
                }
            }
		});
	});
}