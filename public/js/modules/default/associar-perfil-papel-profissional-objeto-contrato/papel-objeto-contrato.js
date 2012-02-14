$(document).ready(function(){
    $("#cd_papel_profissional_objeto_contrato_1").empty();
    $("#cd_papel_profissional_objeto_contrato_2").empty();

    if(($("#cd_area_atuacao_associar_papel_profissional_objeto_contrato").val() != '0') && ($("#cd_objeto_associar_papel_profissional_objeto_contrato").val() != '-1')){
        pesquisaPapelProfissionalObjetoContrato();
    }

    $("#cd_objeto_associar_papel_profissional_objeto_contrato").change(function(){
        if(($(this).val() != '-1') && ($("#cd_area_atuacao_associar_papel_profissional_objeto_contrato").val() != '0')){
            pesquisaPapelProfissionalObjetoContrato();
        }
    });
    $("#cd_area_atuacao_associar_papel_profissional_objeto_contrato").change(function(){
        if(($(this).val() != '0') && ($("#cd_objeto_associar_papel_profissional_objeto_contrato").val() != '-1')){
            pesquisaPapelProfissionalObjetoContrato();
        }
    });

    $("#add_papel_profissional_objeto_contrato").click(function(){

        if(!validaForm('#div_associar_papel_profissional_objeto_contrato'))return false;

        var count = 0;
        var arrPapel = "[";
        $('#cd_papel_profissional_objeto_contrato_1 option:selected').each(function(){
            arrPapel += (arrPapel == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrPapel += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PAPEL_ASSOCIAR_OBJETO)
            return false;
        }

        $.ajax({
            type    : "POST",
            url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/associar-papel-objeto-contrato",
            data    : {'cd_objeto': $("#cd_objeto_associar_papel_profissional_objeto_contrato").val(),
                       'papeis'   : arrPapel},
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaPapelProfissionalObjetoContrato();
                }
            }
        });
	});

	$("#remove_papel_profissional_objeto_contrato").click(function(){

        if(!validaForm('#div_associar_papel_profissional_objeto_contrato'))return false;

        var count = 0;
        var arrPapel = "[";
        $('#cd_papel_profissional_objeto_contrato_2 option:selected').each(function() {
            arrPapel += (arrPapel == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrPapel += "]";
        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PAPEL_DESASSOCIAR_OBJETO)
            return false;
        }
        $.ajax({
            type    : "POST",
            url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/desassociar-papel-objeto-contrato",
            data    : {'cd_objeto': $("#cd_objeto_associar_papel_profissional_objeto_contrato").val(),
                       'papeis'   : arrPapel},
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaPapelProfissionalObjetoContrato();
                }
            }
        });
	});
});

function pesquisaPapelProfissionalObjetoContrato()
{
    $("#grid_associar_papel_profissional_objeto_contrato").html('');
    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/pesquisa-papel-objeto-contrato",
        data    : {"cd_objeto"          : $("#cd_objeto_associar_papel_profissional_objeto_contrato").val(),
                   "cd_area_atuacao_ti" : $("#cd_area_atuacao_associar_papel_profissional_objeto_contrato").val()},
        dataType: 'json',
        success : function(retorno){
            $("#cd_papel_profissional_objeto_contrato_1").html(retorno[0]);
            $("#cd_papel_profissional_objeto_contrato_2").html(retorno[1]);

            montaGridPapelProfissionalObjetoContrato();
        }
    });
}

function montaGridPapelProfissionalObjetoContrato()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/grid-papel-profissional-objeto-contrato",
        data    : {"cd_objeto"          : $("#cd_objeto_associar_papel_profissional_objeto_contrato").val(),
                   "cd_area_atuacao_ti" : $("#cd_area_atuacao_associar_papel_profissional_objeto_contrato").val()},
        success : function(retorno){
            $("#grid_associar_papel_profissional_objeto_contrato").html(retorno);
        }
    });

}

function salvarDescricaoPapelProfissionalObjetoContrato(id_input, cd_objeto, cd_papel_profissional)
{
    if($('#'+id_input).val() == ''){
        showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO, $('#'+id_input));
        return false;
    }

    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/salvar-descricao-papel-profissional",
        data    : {'cd_objeto'             : cd_objeto,
                   'cd_papel_profissional' : cd_papel_profissional,
                   'tx_descricao_papel_prof': $('#'+id_input).val()},
        dataType: 'json',
        success : function(retorno){
            if(retorno['error'] == true){
                alertMsg(retorno['msg'],retorno['typeMsg']);
            }else{
                alertMsg(retorno['msg'],retorno['typeMsg'],'montaGridPapelProfissionalObjetoContrato()');
            }
        }
    });
}