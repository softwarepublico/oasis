$(document).ready( function() {
	$('#btn_gerar').click( function() {
		validaDados();
	});
	
	$('#medicao').removeAttr('checked')
	$('#box').removeAttr('checked')
})

function validaDados() {
	if ($('#medicao').attr('checked') == true && $('#cd_medicao').val() != '0') {
		gerarRelatorio($('#formRelatorioProjetoAnaliseMedicao'), 'analise-medicao/analise-medicao');
				
	} else if ($('#box').attr('checked') == true && $('#cd_box_inicio').val() != 0) {
		gerarRelatorio($('#formRelatorioProjetoAnaliseMedicao'), 'analise-medicao/analise-medicao-box-inicio');
	} else {
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PARAMETRO_RELATORIO);
	}
}

function abreMedicao() {
	$('#comboMedicao'	).css('display', '');
	$('#comboBoxInicio'	).css('display', 'none');
	$('#cd_medicao'		).removeAttr('disabled');
	$('#cd_box_inicio'	).attr('disabled','disabled');
}

function abreBox() {
	$('#comboMedicao'	).css('display', 'none');
	$('#comboBoxInicio'	).css('display', '');
	$('#cd_box_inicio'	).removeAttr('disabled');
	$('#cd_medicao'		).attr('disabled','disabled');
}