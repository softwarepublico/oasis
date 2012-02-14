$(document).ready(function(){

	$('#div_teste label').addClass('float-l');
    limpaFormStatusAtendimento();
    montaGridStatusAtendimento();

    $("#btn_salvar_status_atendimento").click(function(){
		salvarStatusAtendimento();
    });
    $("#btn_cancelar_status_atendimento").click(function(){
		limpaFormStatusAtendimento();
    });

    $('#tx_rgb_status_atendimento').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            $(el).css('background-color','#'+hex);
            $(el).val(hex);
            $(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    }).bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
    });

	$('#st_status_atendimento-T').click(function(){
		$('#div_tempo_resposta').show();
	});
	$('#st_status_atendimento-P').click(function(){
		$('#div_tempo_resposta').hide();
		$('#ni_percent_tempo_resposta_ini').val('');
		$('#ni_percent_tempo_resposta_fim').val('');
	});

});

function salvarStatusAtendimento()
{
	if(!validaForm('#formStatusAtendimento')){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/status-atendimento/salvar-status-atendimento",
		data    : $('#formStatusAtendimento :input').serialize(),
        dataType: 'json',
		success: function(retorno){
			if(retorno['error'] == true ){
                alertMsg(retorno['msg'], retorno['typeMsg']);
            }else{
                alertMsg(retorno['msg'], retorno['typeMsg']);
                limpaFormStatusAtendimento();
                montaGridStatusAtendimento();
            }
		}
	});
}

function recuperarStatusAtendimento(cd_status_atendimento)
{
	$.ajax({
		type    : "POST",
		url     : systemName+"/status-atendimento/recuperar-status-atendimento",
		data    : {"cd_status_atendimento" :cd_status_atendimento},
        dataType: 'json',
		success: function(retorno){
            $('#cd_status_atendimento'    ).val(retorno['cd_status_atendimento']);
            $('#tx_status_atendimento'    ).val(retorno['tx_status_atendimento']);
            $('#tx_rgb_status_atendimento').val(retorno['tx_rgb_status_atendimento'])
                                   .css('background-color','#'+retorno['tx_rgb_status_atendimento']);
            $("#btn_cancelar_status_atendimento").show();
			switch (retorno['st_status_atendimento']) {
					case 'P':
						$('#st_status_atendimento-P').attr('checked','checked');
						$('#st_status_atendimento-T').removeAttr('checked');
						$('#div_tempo_resposta').hide();
						$('#ni_percent_tempo_resposta_ini').val('')
						$('#ni_percent_tempo_resposta_fim').val('')

						break;
					case 'T':
						$('#st_status_atendimento-T').attr('checked','checked');
						$('#st_status_atendimento-P').removeAttr('checked');
						$('#div_tempo_resposta').show();
						$('#ni_percent_tempo_resposta_ini').val(retorno['ni_percent_tempo_resposta_ini'])
						$('#ni_percent_tempo_resposta_fim').val(retorno['ni_percent_tempo_resposta_fim'])

						break;
				}
		}
	});
}

function excluirStatusAtendimento(cd_status_atendimento)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/status-atendimento/excluir-status-atendimento",
            data    : {"cd_status_atendimento" :cd_status_atendimento},
            dataType: 'json',
            success: function(retorno){
                if(retorno['error'] == true ){
                    alertMsg(retorno['msg'], retorno['typeMsg']);
                }else{
                    alertMsg(retorno['msg'], retorno['typeMsg']);
                    montaGridStatusAtendimento();
                }
            }
        });
	});
}


function montaGridStatusAtendimento()
{
	$.ajax({
		type: "POST",
		url: systemName+"/status-atendimento/grid-status-atendimento",
		success: function(retorno){
			$("#gridStatusAtendimento").html(retorno);
			$("#gridStatusAtendimento").show('slow');
		}
	});
}

function limpaFormStatusAtendimento()
{
    $('#formStatusAtendimento :input')
	.not('#st_status_atendimento-P')
	.not('#st_status_atendimento-T')
	.val('').css('background-color','#ffffff');
	$('#st_status_atendimento-P').attr('checked', 'checked');
	$('#div_tempo_resposta').hide();
}