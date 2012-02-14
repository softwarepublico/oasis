$(document).ready(function(){
	$('#btn_gerar').click(function(){
		abreRelatorio();
	});
    $('#mes').change(function(){
        if($(this).val() != '0'){
            $('#lb_ano').addClass('required');
        }else{
            $('#lb_ano').removeClass('required');
        }
    });
    $('#ano').change(function(){
        if($(this).val() != '0'){
            $('#lb_mes').addClass('required');
        }else{
            $('#lb_mes').removeClass('required');
        }
    });

    $('#cd_objeto_contrato').change(function(){
        if($("#cd_objeto_contrato").val() != 0){
            comboProfissional();
        }
    });

});

function comboProfissional()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/demanda-em-aberto/combo-profissional",
		data: {"cd_objeto_contrato" : $("#cd_objeto_contrato").val()},
		success: function(retorno){
			$('#cd_profissional').html(retorno);
		}
	});
}


function abreRelatorio()
{
	if(!validaSelecao()){return false;}
	if(!validaForm()){return false;}
	gerarRelatorio( $('#formRelatorioDemandaEmAberto'), 'demanda-em-aberto/demanda-em-aberto');
}

function validaSelecao()
{
    if( ($('#cd_profissional').val() == '-1') &&
        ($('#mes').val() == '0') &&
        ($('#ano').val() == '0') ){
        alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PARAMETRO_PESQUISA);
        return false;
    }
    return true;
}