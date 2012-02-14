$(document).ready(function() { 
	
	//pega o click do botão nova medicao
	$("#btn_nova_medicao").click(function() {
		habilitaAbaNovaMedicao();
	});
	//pega o click do botão salvar medicao
	$("#btn_salvar_medicao").click(function() {
		salvarNovaMedicao();
	});
	//pega o click do botão alterar medicao
	$("#btn_alterar_medicao").click(function() {
		alterarMedicao();
	});
	//pega o click do botão cancelar medicao
	$("#btn_cancelar_medicao").click(function() {
		desabilitaAbaNovaMedicao();
	});
});

function montaGridMedicoes()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/grid-medicoes",
		success	: function(retorno){
			// atualiza a grid
			$("#gridMedicoes").html(retorno);
		}
	});
}

function recuperaDadosMedicao(cd_medicao)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/recupera-dados-medicao",
		data	: "cd_medicao="+cd_medicao,
		dataType: 'json',
		success	: function(retorno){
			
			$('#cd_medicao'				).val(retorno['cd_medicao']);
			$('#tx_medicao'				).val(retorno['tx_medicao']);
			$('#tx_objetivo_medicao'	).wysiwyg('value',retorno['tx_objetivo_medicao']);
			$('#tx_procedimento_coleta'	).wysiwyg('value',retorno['tx_procedimento_coleta']);
			$('#tx_procedimento_analise').wysiwyg('value',retorno['tx_procedimento_analise']);
			$('#nivel_estrategico'		).val('E');
			$('#nivel_tecnico'			).val('T');
			
			if(retorno['st_nivel_medicao'] === "E"){
				$('#nivel_estrategico').attr('checked','checked');
			}else{
				$('#nivel_tecnico').attr('checked','checked');
			}
			
			$('#btn_alterar_medicao').show();
			$('#btn_salvar_medicao').hide();
			habilitaAbaNovaMedicao();
		}
	});
}

function salvarNovaMedicao()
{
	if( !validaForm('#div_nova_medicao') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/salvar-nova-medicao",
		data	: $("#div_nova_medicao :input").serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridMedicoes();
			limpaInputsNovaMedicao();
			desabilitaAbaNovaMedicao();
			carregaComboMedicaoMedida();
		}
	});
}

function alterarMedicao()
{
	if( !validaForm('#div_nova_medicao') ){return false;}

	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/alterar-medicao",
		data	: $("#div_nova_medicao :input").serialize()+"&cd_medicao="+$('#cd_medicao').val(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridMedicoes();
			desabilitaAbaNovaMedicao();
		}
	});
}

function habilitaAbaNovaMedicao()
{
	$("#li_aba_nova_medicao").show();
	$('#container-medicao').triggerTab(2);
}

function desabilitaAbaNovaMedicao()
{
	$("#li_aba_nova_medicao").hide();
	$('#container-medicao').triggerTab(1);
	limpaInputsNovaMedicao();
}

function limpaInputsNovaMedicao()
{
	$("#div_nova_medicao :input").val('');
	$("#cd_medicao"				).val('');
	$('#tx_objetivo_medicao'	).wysiwyg('value','');
	$('#tx_procedimento_coleta'	).wysiwyg('value','');
	$('#tx_procedimento_analise').wysiwyg('value','');
	$('#nivel_estrategico'		).attr('checked','checked');
	$('#nivel_estrategico'		).val('E');
	$('#nivel_tecnico'			).val('T');
	$('#btn_alterar_medicao'	).hide();
	$('#btn_salvar_medicao'		).show();
}