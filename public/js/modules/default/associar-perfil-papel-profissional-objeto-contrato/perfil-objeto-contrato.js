$(document).ready(function(){

    $("#cd_perfil_profissional_objeto_contrato_1").empty();
    $("#cd_perfil_profissional_objeto_contrato_2").empty();

    if(($("#cd_area_atuacao_associar_perfil_profissional_objeto_contrato").val() != '0') && ($("#cd_objeto_associar_perfil_profissional_objeto_contrato").val() != '-1')){
        pesquisaPerfilProfissionalObjetoContrato();
    }
    
    $("#cd_objeto_associar_perfil_profissional_objeto_contrato").change(function(){
        if(($(this).val() != '-1') && ($("#cd_area_atuacao_associar_perfil_profissional_objeto_contrato").val() != '0')){
            pesquisaPerfilProfissionalObjetoContrato();
        }
    });
    $("#cd_area_atuacao_associar_perfil_profissional_objeto_contrato").change(function(){
        if(($(this).val() != '0') && ($("#cd_objeto_associar_perfil_profissional_objeto_contrato").val() != '-1')){
            pesquisaPerfilProfissionalObjetoContrato();
        }
    });


    $("#add_perfil_profissional_objeto_contrato").click(function(){

        if(!validaForm('#div_associar_perfil_profissional_objeto_contrato'))return false;

        var count = 0;
        var arrPerfil = "[";
        $('#cd_perfil_profissional_objeto_contrato_1 option:selected').each(function(){
            arrPerfil += (arrPerfil == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrPerfil += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PERFIL_ASSOCIAR_OBJETO)
            return false;
        }

        $.ajax({
            type    : "POST",
            url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/associar-perfil-objeto-contrato",
            data    : {'cd_objeto': $("#cd_objeto_associar_perfil_profissional_objeto_contrato").val(),
                       'perfis'   : arrPerfil},
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaPerfilProfissionalObjetoContrato();
                }
            }
        });
	});

	$("#remove_perfil_profissional_objeto_contrato").click(function(){

        if(!validaForm('#div_associar_perfil_profissional_objeto_contrato'))return false;

        var count = 0;
        var arrPerfil = "[";
        $('#cd_perfil_profissional_objeto_contrato_2 option:selected').each(function() {
            arrPerfil += (arrPerfil == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrPerfil += "]";
        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PERFIL_DESASSOCIAR_OBJETO)
            return false;
        }
        $.ajax({
            type    : "POST",
            url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/desassociar-perfil-objeto-contrato",
            data    : {'cd_objeto': $("#cd_objeto_associar_perfil_profissional_objeto_contrato").val(),
                       'perfis'   : arrPerfil},
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaPerfilProfissionalObjetoContrato();
                }
            }
        });
	});
});

function pesquisaPerfilProfissionalObjetoContrato()
{
    $("#grid_associar_perfil_profissional_objeto_contrato").html('');
    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/pesquisa-perfil-objeto-contrato",
        data    : {"cd_objeto"          : $("#cd_objeto_associar_perfil_profissional_objeto_contrato").val(),
                   "cd_area_atuacao_ti" : $("#cd_area_atuacao_associar_perfil_profissional_objeto_contrato").val()},
        dataType: 'json',
        success : function(retorno){
            $("#cd_perfil_profissional_objeto_contrato_1").html(retorno[0]);
            $("#cd_perfil_profissional_objeto_contrato_2").html(retorno[1]);

            montaGridPerfilProfissionalObjetoContrato();
        }
    });
}

function montaGridPerfilProfissionalObjetoContrato()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/grid-perfil-profissional-objeto-contrato",
        data    : {"cd_objeto"          : $("#cd_objeto_associar_perfil_profissional_objeto_contrato").val(),
                   "cd_area_atuacao_ti" : $("#cd_area_atuacao_associar_perfil_profissional_objeto_contrato").val()},
        success : function(retorno){
            $("#grid_associar_perfil_profissional_objeto_contrato").html(retorno);
        }
    });
}

function salvarDescricaoPerfilProfissionalObjetoContrato(id_input, cd_objeto, cd_perfil_profissional)
{
    if($('#'+id_input).val() == ''){
        showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO, $('#'+id_input));
        return false;
    }

    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-perfil-papel-profissional-objeto-contrato/salvar-descricao-perfil-profissional",
        data    : {'cd_objeto'              : cd_objeto,
                   'cd_perfil_profissional' : cd_perfil_profissional,
                   'tx_descricao_perfil_prof': $('#'+id_input).val()},
        dataType: 'json',
        success : function(retorno){
            if(retorno['error'] == true){
                alertMsg(retorno['msg'],retorno['typeMsg']);
            }else{
                alertMsg(retorno['msg'],retorno['typeMsg'],'montaGridPerfilProfissionalObjetoContrato()');
            }
        }
    });
}