$(document).ready(function(){
	$('#btn_gerar').click(function(){
		abreRelatorio();
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
		url: systemName+"/"+systemNameModule+"/rotina/combo-profissional",
		data: {"cd_objeto_contrato" : $("#cd_objeto_contrato").val()},
		success: function(retorno){
			$('#cd_profissional').html(retorno);
		}
	});
}


function abreRelatorio()
{
	if(!validaForm()){return false;}
	gerarRelatorio( $('#formRelatorioRotina'), 'rotina/rotina');
}
