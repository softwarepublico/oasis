$(document).ready(function(){
    $('#item_risco :input').val('');
    $('#btn_salvar').show();
    
    $('#cd_area_atuacao_ti_item_risco').change(function(){
        montaComboEtapaItemRisco();
        montaComboAtividadeItemRisco('S');
        $('#gridItemRisco').hide();
    });
    $('#cd_etapa_item_risco').change(function(){
        montaComboAtividadeItemRisco();
    });
    $('#cd_atividade_item_risco').change(function(){
        montaGridItemRisco();
    });
});

function redirecionaItemRisco(cd_item_risco) {
    $('#item_risco :input').not('#cd_area_atuacao_ti_item_risco').val('');
    $('#tx_descricao_item_risco').wysiwyg('clear');

    $.ajax({
        type    : "POST",
        url     : systemName+"/item-risco/redireciona-item-risco",
        data    : {'cd_item_risco':cd_item_risco},
        dataType: 'json',
        success : function(retorno){
            $('#cd_item_risco'          ).val( retorno['cd_item_risco'] );
            $('#cd_etapa_item_risco'    ).val( retorno['cd_etapa'] );
            $('#cd_atividade_item_risco').val( retorno['cd_atividade'] );
            $('#tx_item_risco'          ).val( retorno['tx_item_risco'] );
            $('#tx_descricao_item_risco').wysiwyg('value',retorno['tx_descricao_item_risco']);
            
            $('#btn_salvar').hide();
            $('#btn_alterar, #btn_excluir').show();
        }
    });
}

function montaGridItemRisco(){
    $.ajax({
        type    : "POST",
        url     : systemName+"/item-risco/grid-item-risco",
        data    : {"cd_etapa"     : $('#cd_etapa_item_risco').val(),
                   "cd_atividade" : $('#cd_atividade_item_risco').val()},
        success : function(retorno){
            $("#gridItemRisco").html(retorno);
            $("#gridItemRisco").show();
        }
    });
}

function salvarItemRisco() {
    if(!validaForm('#item_risco')){
        return false;
    }
    $.ajax({
        type    : 'POST',
        url     : systemName+"/item-risco/salvar-item-risco",
        data    : $('#item_risco :input').serialize(),
        dataType: 'json',
        success : function(retorno){
            alertMsg(retorno['msg'],retorno['tipo'],function(){
                reiniciaFormItemRisco();
            });
        }
    });
}

function excluirItemRisco() {
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/item-risco/excluir-item-risco",
            data    : {'cd_item_risco':$('#cd_item_risco').val()},
            dataType: 'json',
            success : function(retorno){
                alertMsg(retorno['msg'],retorno['tipo'],function(){
                    $('#tx_item_risco').focus();
                    reiniciaFormItemRisco();
                });
            }
        });
    });
}

function reiniciaFormItemRisco() {
    $('#cd_item_risco').val('');
    $('#tx_item_risco').val('');
    $('#tx_descricao_item_risco').wysiwyg('clear');
    $('#btn_alterar, #btn_excluir').hide();
    $('#btn_salvar').show();
    montaGridItemRisco();
}

function montaComboEtapaItemRisco()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/atividade/monta-combo-etapa",
        data	: {"cd_area_atuacao_ti":$("#cd_area_atuacao_ti_item_risco").val()},
        success	: function(retorno){
            $('#cd_etapa_item_risco').html(retorno);
        }
    });
}

function montaComboAtividadeItemRisco(zera)
{
    var cd_etapa = "";
    if(zera == 'S'){
        cd_etapa = 0;
    } else {
        cd_etapa = $("#cd_etapa_item_risco").val();
    }

    $.ajax({
        type	: "POST",
        url		: systemName+"/atividade/combo-atividade",
        data	: {"cd_etapa":cd_etapa},
        success	: function(retorno){
            $('#cd_atividade_item_risco').html(retorno);
        }
    });
}