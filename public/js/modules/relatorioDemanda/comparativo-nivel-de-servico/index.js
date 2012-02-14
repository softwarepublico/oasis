$(document).ready(function(){
	$('#btn_gerar').click(function(){
		abreRelatorio();
	});

    $('#cd_objeto_contrato').change(function(){
        if($("#cd_objeto_contrato").val() != 0){
            comboProfissional();
            comboNivelDeServico();
        }
    });
})
function abreRelatorio()
{
	if(!validaForm()){ return false; }
	gerarRelatorio( $('#formRelatorioComparativoNivelDeServico'), 'comparativo-nivel-de-servico/comparativo-nivel-de-servico');
    return true;
}

/**
 * Função que recupera o profissional
 */
function comboProfissional()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/comparativo-nivel-de-servico/combo-profissional",
		data: "cd_objeto_contrato="+$("#cd_objeto_contrato").val(),
		success: function(retorno){
			$('#cd_profissional').html(retorno);
		}
	});
}

/**
 * Função que recupera o nível de serviço
 */
function comboNivelDeServico() {
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/comparativo-nivel-de-servico/combo-nivel-de-servico",
		data: "cd_objeto_contrato="+$("#cd_objeto_contrato").val(),
		success: function(retorno){
			$('#cd_nivel_servico').html(retorno);
		}
	});
}