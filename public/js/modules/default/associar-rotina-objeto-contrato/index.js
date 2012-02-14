$(document).ready(function(){

    $('#cd_area_atuacao_ti_rotina_objeto_contrato').change(function(){
        if($(this).val() != 0 ){
            if($('#cd_objeto_rotina_objeto_contrato').val() != -1){
                pesquisaRotinaObjetoContrato();
            }
        }else{
            $('#cd_rotina_objeto_contrato_1, #cd_rotina_objeto_contrato_2').empty()
        }
    });

    $('#cd_objeto_rotina_objeto_contrato').change(function(){
        if($(this).val() != -1 ){
            if($('#cd_area_atuacao_ti_rotina_objeto_contrato').val() != 0){
                pesquisaRotinaObjetoContrato();
            }
        }
    });


    $("#add_rotina_objeto_contrato").click(function(){

        if(!validaForm('#form_associar_rotina_objeto_contrato')) return false;

        var count = 0;
        var arrRotina = "[";
        $('#cd_rotina_objeto_contrato_1 option:selected').each(function(){
            arrRotina += (arrRotina == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrRotina += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ROTINA_ASSOCIAR_OBJETO)
            return false;
        }

        $.ajax({
            type    : "POST",
            url     : systemName+"/associar-rotina-objeto-contrato/associar-rotina",
            data    : {'cd_objeto' : $('#cd_objeto_rotina_objeto_contrato').val(),
                       'rotinas': arrRotina},
            dataType: 'json',
            async: false,
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaRotinaObjetoContrato();
                }
            }
        });
    });

	$("#remove_rotina_objeto_contrato").click(function(){

        if(!validaForm('#form_associar_rotina_objeto_contrato'))return false;

        var count = 0;
        var arrRotina = "[";
        $('#cd_rotina_objeto_contrato_2 option:selected').each(function() {
            arrRotina += (arrRotina == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrRotina += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ROTINA_DESASSOCIAR_OBJETO)
            return false;
        }
        
        $.ajax({
            type    : "POST",
            url     : systemName+"/associar-rotina-objeto-contrato/desassociar-rotina",
            data    : {'cd_objeto' : $('#cd_objeto_rotina_objeto_contrato').val(),
                       'rotinas': arrRotina},
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaRotinaObjetoContrato();
                }
            }
        });
	});
});

function pesquisaRotinaObjetoContrato()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/associar-rotina-objeto-contrato/pesquisa-rotina-objeto-contrato",
        data    : {'cd_objeto'          : $('#cd_objeto_rotina_objeto_contrato').val(),
                   'cd_area_atuacao_ti' : $('#cd_area_atuacao_ti_rotina_objeto_contrato').val()},
        dataType: 'json',
        success : function(retorno){
            $('#cd_rotina_objeto_contrato_1').html(retorno[0]);
            $('#cd_rotina_objeto_contrato_2').html(retorno[1]);
        }
    });
}