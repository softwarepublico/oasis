$(document).ready(function(){

    $('#cd_rotina_designacao_rotina').change(function(){
        if($(this).val() != 0 ){
            if($('#cd_area_atuacao_ti_rotina_designacao_rotina').val() != 0){
                pesquisaRotinaProfissional();
            }
        }
    });


    $("#add_profissional_designacao_rotina").click(function(){

        if(!validaForm('#form_designacao_rotina')) return false;

        var count = 0;
        var arrProfissional = "[";
        $('#cd_profissional_1 option:selected').each(function(){
            arrProfissional += (arrProfissional == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrProfissional += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PROFISSIONAL_ASSOCIAR)
            return false;
        }

        $.ajax({
            type    : "POST",
            url     : systemName+"/gerenciamento-rotina/associar-profissional",
            data    : {'cd_objeto' : $('#cd_objeto_designacao_rotina').val(),
                       'cd_rotina' : $('#cd_rotina_designacao_rotina').val(),
                       'profissionais': arrProfissional},
            dataType: 'json',
            async: false,
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaRotinaProfissional();
                }
            }
        });
    });

	$("#remove_profissional_designacao_rotina").click(function(){

        if(!validaForm('#form_designacao_rotina'))return false;

        var count = 0;
        var arrProfissional = "[";
        $('#cd_profissional_2 option:selected').each(function() {
            arrProfissional += (arrProfissional == "[") ? $(this).val() : "," + $(this).val();
            count++;
        });
        arrProfissional += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PROFISSIONAL_DESASSOCIAR)
            return false;
        }
        
        $.ajax({
            type    : "POST",
            url     : systemName+"/gerenciamento-rotina/desassociar-profissional",
            data    : {'cd_objeto' : $('#cd_objeto_designacao_rotina').val(),
                       'cd_rotina' : $('#cd_rotina_designacao_rotina').val(),
                       'profissionais': arrProfissional},
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaRotinaProfissional();
                }
            }
        });
	});
});

function pesquisaRotinaProfissional()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/gerenciamento-rotina/pesquisa-rotina-profissional",
        data    : {'cd_objeto' : $('#cd_objeto_designacao_rotina').val(),
                   'cd_rotina' : $('#cd_rotina_designacao_rotina').val()},
        dataType: 'json',
        success : function(retorno){
            $('#cd_profissional_1').html(retorno[0]);
            $('#cd_profissional_2').html(retorno[1]);
        }
    });
}