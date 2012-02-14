$(document).ready(function(){

    $('#cd_area_atuacao_ti_associacao').change(function(){
        comboEtapaAssociacao();
        $('#cd_atividade_objeto_contrato_1, #cd_atividade_objeto_contrato_2').empty()
    });

    $('#cd_objeto_associacao').change(function(){
        if($(this).val() != -1 ){
            if($('#cd_etapa_associacao').val() != 0){
                pesquisaAtividadeObjetoContrato();
            }
        }
    });

    $('#cd_etapa_associacao').change(function(){
        if($(this).val() != 0 ){
            if($('#cd_objeto_associacao').val() != -1){
                pesquisaAtividadeObjetoContrato();
            }else{
                showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO, $('#cd_objeto_associacao'));
                return false;
            }
        }else{
            $('#cd_atividade_objeto_contrato_1, #cd_atividade_objeto_contrato_2').empty()
        }
    });

    $("#add_atividade_objeto_contrato").click(function(){

        if(!validaForm('#form_associar_atividade_objeto_contrato')) return false;

        var count = 0;
        var arrAtividade = "[";
        $('#cd_atividade_objeto_contrato_1 option:selected').each(function(){
            arrAtividade += (arrAtividade == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrAtividade += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ATIVIDADE_ASSOCIAR_OBJETO)
            return false;
        }

        $.ajax({
            type    : "POST",
            url     : systemName+"/associar-atividade-objeto-contrato/associar-atividade",
            data    : {'cd_objeto' : $('#cd_objeto_associacao').val(),
                       'cd_etapa'  : $('#cd_etapa_associacao').val(),
                       'atividades': arrAtividade},
            dataType: 'json',
            async: false,
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaAtividadeObjetoContrato();
                }
            }
        });
    });

	$("#remove_atividade_objeto_contrato").click(function(){

        if(!validaForm('#form_associar_atividade_objeto_contrato'))return false;

        var count = 0;
        var arrAtividade = "[";
        $('#cd_atividade_objeto_contrato_2 option:selected').each(function() {
            arrAtividade += (arrAtividade == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrAtividade += "]";
        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ATIVIDADE_DESASSOCIAR_OBJETO)
            return false;
        }
        $.ajax({
            type    : "POST",
            url     : systemName+"/associar-atividade-objeto-contrato/desassociar-atividade",
            data    : {'cd_objeto' : $('#cd_objeto_associacao').val(),
                       'cd_etapa'  : $('#cd_etapa_associacao').val(),
                       'atividades': arrAtividade},
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaAtividadeObjetoContrato();
                }
            }
        });
	});
});

function comboEtapaAssociacao()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-atividade-objeto-contrato/combo-etapa",
        data    : {'cd_area_atuacao_ti' : $('#cd_area_atuacao_ti_associacao').val()},
        success : function(retorno){
            $('#cd_etapa_associacao').html(retorno);
        }
    });
}

function pesquisaAtividadeObjetoContrato()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-atividade-objeto-contrato/pesquisa-atividade-objeto-contrato",
        data    : {'cd_objeto'          : $('#cd_objeto_associacao').val(),
                   'cd_etapa'           : $('#cd_etapa_associacao').val()},
        dataType: 'json',
        success : function(retorno){
            $('#cd_atividade_objeto_contrato_1').html(retorno[0]);
            $('#cd_atividade_objeto_contrato_2').html(retorno[1]);
        }
    });
}