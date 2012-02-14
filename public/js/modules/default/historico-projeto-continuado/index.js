$(document).ready(function(){
	if($("#cd_objeto_historico_continuado").val() != -1){
		montaGridHistoricoContinuado();
	}
	
	$("#cd_objeto_historico_continuado").change(function() {
		// Monta Grid
		if($(this).val() != -1){
			montaGridHistoricoContinuado();
			// Pesquisa projeto continuado (combo)
			montaComboProjetoContinuadoHistorico();
			// Pesquisa etapa (combo)
			montaComboEtapa();
		} else {
			$("#gridHistoricoContinuo").hide();
		}
	});
	
	// Modulo continuado (combo)
	$("#cd_projeto_continuado_historico_continuado").change(function() {
		montaComboModuloContinuado();
	});
	
	// Atividade (combo)
	$("#cd_etapa").change(function() {
		montaComboAtividade();
	});
	
	// pega evento no onclick do botao
	$("#submitbuttonHistoricoProjetoContinuado").click(function(){
		$("form#historico_projeto_continuado").submit();
	});
	
	$("#dt_fim_hist_projeto_continuado").blur(function() {
		validaData();
	});
});

function validaHistoricoContinuado()
{
	if($('#cd_objeto_historico_continuado').val() == "-1"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_objeto_historico_continuado'));
		$('#cd_objeto_historico_continuado').focus();
		return false;
	}
	if($('#cd_projeto_continuado_historico_continuado').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_projeto_continuado_historico_continuado'));
		$('#cd_projeto_continuado_historico_continuado').focus();
		return false;
	}
	if($('#cd_modulo_continuado_historico_continuado').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_modulo_continuado_historico_continuado'));
		$('#cd_modulo_continuado_historico_continuado').focus();
		return false;
	}
	if($('#cd_etapa').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_etapa'));
		$('#cd_etapa').focus();
		return false;
	}
	if($('#cd_atividade').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_atividade'));
		$('#cd_atividade').focus();
		return false;
	}
	if($('#dt_inicio_hist_proj_continuado').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#dt_inicio_hist_proj_continuado'));
		$('#dt_inicio_hist_proj_continuado').focus();
		return false;
	}
	if($('#dt_fim_hist_projeto_continuado').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#dt_fim_hist_projeto_continuado'));
		$('#dt_fim_hist_projeto_continuado').focus();
		return false;
	}
	if($('#tx_hist_projeto_continuado').wysiwyg('getContent').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_historico_projeto_continuado_editor'));
		var t = setTimeout('removeTollTip()',5000);
		return false;
	}
	if(!validaData()){return false;}
	return true;
}

function validaData()
{
	var nomeInicio = $('#dt_inicio_hist_proj_continuado').val();
	var nomeFim    = $('#dt_fim_hist_projeto_continuado').val();
	if(nomeInicio && nomeFim){
		var dataFim = parseInt(nomeFim.split( "/" )[2].toString()+nomeFim.split( "/" )[1].toString()+nomeFim.split( "/" )[0].toString()); 
		var dataInicio = parseInt( nomeInicio.split( "/" )[2].toString()+nomeInicio.split( "/" )[1].toString()+nomeInicio.split( "/" )[0].toString()); 

		if(dataFim < dataInicio){
			showToolTip(i18n.L_VIEW_SCRIPT_DT_FINAL_MENOR_INICIO, $('#dt_fim_hist_projeto_continuado'));
			$('#dt_fim_hist_projeto_continuado').focus();
			$('#dt_fim_hist_projeto_continuado').select();
			return false;
        }
	}
	return true;
}

function salvarHistoricoContinuado()
{
	if(!validaHistoricoContinuado()){ return false; }
	$.ajax({
		type: "POST",
		url: systemName+"/historico-projeto-continuado/salvar-historico-projeto-continuado",
		data: $('#historico_projeto_continuado :input').serialize(),
		success: function(retorno){
			montaGridHistoricoContinuado();
			cancelarHistoricoContinuado();
			alertMsg(retorno);
		}
	});
}

function alterarHistoricoContinuado()
{
	if(!validaHistoricoContinuado()){ return false; }
	$.ajax({
		type	: "POST",
		url		: systemName+"/historico-projeto-continuado/alterar-historico-projeto-continuado",
		data	: $('#historico_projeto_continuado :input').serialize(),
		success	: function(retorno){
			montaGridHistoricoContinuado();
			cancelarHistoricoContinuado();
			alertMsg(retorno);
		}
	});
}

function cancelarHistoricoContinuado()
{
    $(".toolTip").remove();
	$('#adicionarHistoricoProjetoContinuo'	).show();
	$('#alterarHistoricoProjetoContinuo'	).hide();
	$('#cancelarHistoricoProjetoContinuo'	).hide();
	
	$('#cd_projeto_continuado_historico_continuado').val("0");
	$('#cd_modulo_continuado_historico_continuado' ).val("0");
	$('#cd_etapa'	 ).val("0");
	$('#cd_atividade').val("0");
	$('#dt_inicio_hist_proj_continuado' ).val("");
	$('#dt_fim_hist_projeto_continuado'	).val("");
	$('#tx_hist_projeto_continuado'		).wysiwyg("clear");
}

function excluirHistoricoContinuado(cd_historico_proj_continuado)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/historico-projeto-continuado/excluir",
            data	: "cd_historico_proj_continuado="+cd_historico_proj_continuado,
            success	: function(retorno){
                montaGridHistoricoContinuado();
                cancelarHistoricoContinuado();
                alertMsg(retorno);
            }
        });
    });
}

function montaGridHistoricoContinuado()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/historico-projeto-continuado/grid-historico-projeto-continuado",
		data	: "cd_objeto="+$('#cd_objeto_historico_continuado').val(),
		success	: function(retorno){
			$("#gridHistoricoContinuo").html(retorno);
			$("#gridHistoricoContinuo").show();
		}
	});
}

function recuperaHistoricoContinuado(cd_historico_proj_continuado)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/historico-projeto-continuado/recupera-historico-projeto-continuado",
		data	: "cd_historico_proj_continuado="+cd_historico_proj_continuado,
		dataType: 'json',
		success	: function(retorno){
			$('#adicionarHistoricoProjetoContinuo').hide();
			$('#alterarHistoricoProjetoContinuo').show();
			$('#cancelarHistoricoProjetoContinuo').show();
				
			$('#cd_historico_proj_continuado').val(retorno[0]['cd_historico_proj_continuado']);
			$('#cd_objeto_continuado_historico_continuado').val(retorno[0]['cd_objeto']);
			montaComboProjetoContinuadoHistorico();
			montaComboEtapa();	
			var p=setTimeout("$('#cd_projeto_continuado_historico_continuado').val("+retorno[0]['cd_projeto_continuado']+")",1000);
			var e=setTimeout("$('#cd_etapa').val("+retorno[0]['cd_etapa']+")",2000);
			var fp=setTimeout("montaComboModuloContinuado()",1500);
			var fe=setTimeout("montaComboAtividade()",2000);
			var m=setTimeout("$('#cd_modulo_continuado_historico_continuado').val("+retorno[0]['cd_modulo_continuado']+")",3000);
			var a=setTimeout("$('#cd_atividade').val("+retorno[0]['cd_atividade']+")",4000);
			$('#dt_inicio_hist_proj_continuado').val(retorno[0]['dt_inicio_hist_proj_continuado']);
			$('#dt_fim_hist_projeto_continuado').val(retorno[0]['dt_fim_hist_projeto_continuado']);
			$('#tx_hist_projeto_continuado').wysiwyg('value',retorno[0]['tx_hist_projeto_continuado']);
		}
	});
}

function montaComboProjetoContinuadoHistorico()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-continuado/pesquisa-projeto-continuado",
		data	: "cd_objeto="+$("#cd_objeto_historico_continuado").val(),
		success	: function(retorno){
			$("#cd_projeto_continuado_historico_continuado").html(retorno);
		}
	});
}

function montaComboEtapa()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/etapa/pesquisa-etapa",
		data	: "cd_objeto="+$("#cd_objeto_historico_continuado").val(),
		success	: function(retorno){
			$("#cd_etapa").html(retorno);
		}
	});
}

function montaComboModuloContinuado()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/modulo-continuado/pesquisa-modulo-continuado",
		data	: "cd_projeto_continuado="+$("#cd_projeto_continuado_historico_continuado").val(),
		success	: function(retorno){
			$("#cd_modulo_continuado_historico_continuado").html(retorno);
		}
	});
}

function montaComboAtividade()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/atividade/pesquisa-atividade",
		data	: "cd_etapa="+$("#cd_etapa").val(),
		success	: function(retorno){
			$("#cd_atividade").html(retorno);
		}
	});
}