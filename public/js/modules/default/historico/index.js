$(document).ready(function(){
	$("#cd_projeto_historico").change(function() { 	
		// Pesquisa Proposta (combo)
		$.ajax({
			type	: "POST",
			url		: systemName+"/proposta/pesquisa-proposta",
			data	: "cd_projeto="+$("#cd_projeto_historico").val(),
			success	: function(retorno){
				$("#cd_proposta_historico").html(retorno);
			}
		});
	});

	// Pesquisa Modulo (combo)
	$("#cd_proposta_historico").change(function() {
		ajaxGridHistorico();
		$.ajax({
			type	: "POST",
			url		: systemName+"/modulo/pesquisa-modulo-proposta",
			data	: "cd_projeto="+$("#cd_projeto_historico").val()+"&cd_proposta="+$("#cd_proposta_historico").val(),
			success	: function(retorno){
				$("#cd_modulo_historico").html(retorno);
			}
		});
	});
	
	// Pesquisa atividade (combo)
	$("#cd_etapa_historico").change(function() {
		montaComboAtividade();
	});
	
	//Ao clicar na combo proposta mostra a grid\
	$("#cd_proposta_historico").change(function() {
		if ($("#cd_proposta_historico").val() != "0") {
			$.ajax({
				type	: "POST",
				url		: systemName+"/historico/grid-historico",
				data	: "cd_projeto="+$("#cd_projeto_historico").val()+"&cd_proposta="+$("#cd_proposta_historico").val(),
				success	: function(retorno){
					//atualiza a grid
					$("#gridHistorico").html(retorno);
				}
			});
		}
	});
	
	// pega evento no onclick do botao
	$("#submitbuttonHistorico").click(function(){
		$("form#historico").submit();
	});
});

function ajaxGridHistorico() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/historico/grid-historico",
		data	: "cd_projeto="+$("#cd_projeto_historico").val()+"&cd_proposta="+$("#cd_proposta_historico").val(),
		success	: function(retorno){
			$("#gridHistorico").html(retorno);
		}
	});
}

function excluirHistorico(cd_historico)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/historico/excluir-historico",
			data	: "cd_historico="+cd_historico,
			success	: function(retorno){
				alertMsg(retorno);
				limpaValueHistorico();
				ajaxGridHistorico();
			}
		});
    });
}

function recuperaHistorico(cd_historico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/historico/recupera-historico",
		data	: "cd_historico="+cd_historico,
		dataType:'json',
		success	: function(retorno){
			$("#cd_historico"         ).val(retorno[0]['cd_historico']);
			$("#cd_projeto_historico" ).val(retorno[0]['cd_projeto']);
			$("#cd_proposta_historico").val(retorno[0]['cd_proposta']);
			$("#cd_etapa_historico"   ).val(retorno[0]['cd_etapa']);
			montaComboAtividade();
			var t=setTimeout("$('#cd_atividade_historico').val("+retorno[0]['cd_atividade']+")",1000);
			$("#cd_modulo_historico").val(retorno[0]['cd_modulo']);
			$("#tx_historico"       ).wysiwyg('value',retorno[0]['tx_historico']);
			$("#dt_inicio_historico").val(retorno[0]['dt_inicio_historico']);
			$("#dt_fim_historico"   ).val(retorno[0]['dt_fim_historico']);
			
			//utilizados se estiver na tab
			$('#adicionarHistorico' ).hide();
			$('#alterarHistorico'   ).show();
			$('#cancelarHistorico'  ).show();
		}
	});
}

function limpaValueHistorico(){
	$('#cd_historico'			).val("");
	//$('#cd_projeto_historico').val("0");
	//$('#cd_proposta_historico').val("0");
	$('#cd_modulo_historico'	).val("0");
	$('#cd_atividade_historico'	).val("0");
	$('#tx_historico'			).wysiwyg('clear');
	$('#dt_inicio_historico'	).val("");
	$('#dt_fim_historico'		).val("");
	$('#adicionarHistorico'		).show();
	$('#alterarHistorico'		).hide();
	$('#cancelarHistorico'		).hide();
}

function montaComboAtividade()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/atividade/pesquisa-atividade",
		data	: "cd_etapa="+$("#cd_etapa_historico").val(),
		success	: function(retorno){
			$("#cd_atividade_historico").html(retorno);
		}
	});
}

function validaHistorico()
{
	if($('#cd_projeto_historico').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_projeto_historico'));
		$('#cd_projeto_historico').focus();
		return false
	}
	if($('#cd_proposta_historico').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_proposta_historico'));
		$('#cd_proposta_historico').focus();
		return false
	}
	if($('#cd_modulo_historico').val() == "0" || $('#cd_modulo_historico').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_modulo_historico'));
		$('#cd_modulo_historico').focus();
		return false
	}
	if($('#cd_etapa_historico').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_etapa_historico'));
		$('#cd_etapa_historico').focus();
		return false
	}
	if($('#cd_atividade_historico').val() == "0" || $('#cd_atividade_historico').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_atividade_historico'));
		$('#cd_atividade_historico').focus();
		return false
	}
	if($('#dt_inicio_historico').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#dt_inicio_historico'));
		$('#dt_inicio_historico').focus();
		return false
	}
	if($('#dt_fim_historico').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#dt_fim_historico'));
		$('#dt_fim_historico').focus();
		return false
	}
	if($('#tx_historico').wysiwyg('getContent').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_historico_editor'));
		var t = setTimeout('removeTollTip()',5000);
		return false
	}
	if(!validaDatadt_fim_historico()){return false;}
	
	return true;
}