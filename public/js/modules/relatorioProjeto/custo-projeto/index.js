var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {
	
	if($("#cd_contrato").val() != 0){
		getProjeto();
	}

	$("#cd_contrato").change(function() {
		if($(this).val() != 0){
			getProjeto();		
		}else{
			$("#cd_projeto").html(strOption);
		}
	});
	
	$('#btn_gerar').click( function() {
		validaDados();
	});

	$('#formRelatorio :radio').removeAttr('checked');
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/custo-projeto/pesquisa-projeto",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}

function validaDados(){
	if ($('#projeto').attr('checked') == true && $('#cd_projeto').val() != '-1' && $('#cd_contrato').val() != 0) {
		var pagina = "relatorio-projeto";
		if ($('#cd_projeto').val() == 0) {
			pagina = "relatorio-todos-projetos";
		}
		gerarRelatorio($('#formRelatorio'), 'custo-projeto/' + pagina
				+ '/parametros/' + $('#cd_contrato').val());
	} else if ($('#unidade').attr('checked') == true && $('#cd_unidade').val() != 0) {
		gerarRelatorio(
				$('#formRelatorio'),
				'custo-projeto/relatorio-projeto-unidade/parametros/' +
				$('#cd_unidade').val());
	} else {
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PARAMETRO_RELATORIO);
	}
}

function abreProjeto() {
	$('#comboProjeto').css('display', '');
	$('#comboUnidade').css('display', 'none');
}

function abreUnidade() {
	$('#comboProjeto').css('display', 'none');
	$('#comboUnidade').css('display', '');
}