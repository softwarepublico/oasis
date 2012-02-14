$(document).ready(function(){
	$("#alterarComplemento").hide();
	$("#cancelarComplemento").hide();

	$("#cd_modulo_complemento").change(function(){
		comboCasoDeUso();
		openGrid();
	});
	$("#cd_caso_de_uso_complemento").change(function(){
		openGrid();
	});
	
	$("#adicionarComplemento").click(function(){
		if( !validaForm('#formComplemento')){ return false; }
		salvarComplementos();
	});
	$("#alterarComplemento").click(function(){
		if( !validaForm('#formComplemento')){ return false; }
		alterarComplementos();
	});
	$("#cancelarComplemento").click(function(){
		habilitaBotaoAdicionar();
		limpaValue();
	});
});

function openGrid()
{
	$('#st_fechamento_caso_de_uso_hidden_ex').val("");
	$('#st_fechamento_caso_de_uso_hidden_fa').val("");
	$('#st_fechamento_caso_de_uso_hidden_re').val("");
	
	if($("#cd_caso_de_uso_complemento").val() == "0"){
		$("#gridExcecao").hide('slow');
		$("#gridRegra").hide('slow');
		$("#gridFluxoAlternativo").hide('slow');
	} else if($("#cd_modulo_complemento").val() == "0"){
		$("#gridExcecao").hide('slow');
		$("#gridRegra").hide('slow');
		$("#gridFluxoAlternativo").hide('slow');
	} else {
		gridComplementoExececao();
		gridComplementoRegra();
		gridComplementoFluxoAlternativo();
		var t = window.setTimeout('habilitaBotaoAdicionar()',1000);
		limpaValue();
	}
}

/*
 * Funcionalidades da tela
 */
function salvarComplementos()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-complemento/salvar-complemento",
		data: $('#formComplemento :input').serialize()+"&cd_projeto="+$('#cd_projeto').val(),
		success: function(retorno){
			limpaValue();
			openGrid();
			alertMsg(retorno);
		}
	});
}

function alterarComplementos()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-complemento/alterar-complemento",
		data: $('#formComplemento :input').serialize()+"&cd_projeto="+$('#cd_projeto').val(),
		success: function(retorno){
			limpaValue();
			openGrid();
			alertMsg(retorno);
		}
	});
}

function excluirComplemento(cd_complemento, dt_versao_caso_de_uso)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type: "POST",
            url: systemName+"/caso-de-uso-complemento/excluir-complemento",
            data: "cd_complemento="+cd_complemento
                  +"&dt_versao_caso_de_uso="+dt_versao_caso_de_uso,
            success: function(retorno){
                alertMsg(retorno);
                openGrid();
            }
        });
    });
}

function ajaxRecuperaComplemento(cd_complemento, dt_versao_caso_de_uso)
{
	$("#cd_complemento").val(cd_complemento);
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-complemento/recupera-complemento",
		data: "cd_complemento="+cd_complemento
			  +"&dt_versao_caso_de_uso="+dt_versao_caso_de_uso,
		dataType: "json",
		success: function(retorno){
		$('#st_complemento_e').removeAttr('checked');
			$("#cd_modulo_complemento").val(retorno[0]['cd_modulo']);
			comboCasoDeUso();
			var t=setTimeout("$('#cd_caso_de_uso_complemento').val("+retorno[0]['cd_caso_de_uso']+")",1000);
			if(retorno[0]['st_complemento'] == 'E'){
				$('#st_complemento_e').attr('checked','checked');
			} else if(retorno[0]['st_complemento'] == 'R'){
				$('#st_complemento_r').attr('checked','checked');
			} else {
				$('#st_complemento_f').attr('checked','checked');
			}
			$("#ni_ordem_complemento").val(retorno[0]['ni_ordem_complemento']);
			$("#tx_complemento").val(retorno[0]['tx_complemento']);
			habilitaBotaoAlterar();
		}
	});
}

/*
 * Combo(s) da tela
 */
function comboCasoDeUso()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso/combo-group-caso-de-uso",
		data: "cd_projeto="+$("#cd_projeto").val()
			  +"&cd_modulo="+$("#cd_modulo_complemento").val(),
		success: function(retorno){
			$("#cd_caso_de_uso_complemento").html(retorno);
		}
	});
}

/*
 * Regras de botões da tela
 */
function limpaValue(){
	$('#cd_complemento').val("");
	$('#ni_ordem_complemento').val("");
	$('#st_complemento_r'	 ).attr('checked','');
	$('#st_complemento_f'	 ).attr('checked','');
	$('#tx_complemento'		 ).val("");
}

function habilitaBotaoAlterar()
{
	$("#adicionarComplemento").hide();
	if(($('#st_fechamento_caso_de_uso_hidden_ex').val() == "S") || ($('#st_fechamento_caso_de_uso_hidden_fa').val() == "S") || ($('#st_fechamento_caso_de_uso_hidden_re').val() == "S")){
		$("#alterarComplemento").hide();
		$("#cancelarComplemento").addClass('clear-l').show();
	} else {
		$("#cancelarComplemento").show();
		$("#alterarComplemento").show();
	}
}

function habilitaBotaoAdicionar()
{
	if(($('#st_fechamento_caso_de_uso_hidden_ex').val() == "S") || ($('#st_fechamento_caso_de_uso_hidden_fa').val() == "S") || ($('#st_fechamento_caso_de_uso_hidden_re').val() == "S")){
		$("#adicionarComplemento").hide();
	} else {
		$("#adicionarComplemento").show();
	}
	$("#alterarComplemento").hide();
	$("#cancelarComplemento").removeClass('clear-l').hide();
}

/*
 * Inicio das requisições da grid
 */
function gridComplementoExececao()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-complemento/grid-complemento-exececao",
		data: "cd_projeto="+$("#cd_projeto").val()
			  +"&cd_modulo="+$("#cd_modulo_complemento").val()
			  +"&cd_caso_de_uso="+$("#cd_caso_de_uso_complemento").val(),
		success: function(retorno){
			$("#gridExcecao").show('slow');
			$("#gridExcecao").html(retorno);
		}
	});
}

function gridComplementoRegra()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-complemento/grid-complemento-regra",
		data: "cd_projeto="+$("#cd_projeto").val()
			  +"&cd_modulo="+$("#cd_modulo_complemento").val()
			  +"&cd_caso_de_uso="+$("#cd_caso_de_uso_complemento").val(),
		success: function(retorno){
			$("#gridRegra").show('slow');
			$("#gridRegra").html(retorno);
		}
	});
}

function gridComplementoFluxoAlternativo()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-complemento/grid-complemento-fluxo-alternativo",
		data: "cd_projeto="+$("#cd_projeto").val()
			  +"&cd_modulo="+$("#cd_modulo_complemento").val()
			  +"&cd_caso_de_uso="+$("#cd_caso_de_uso_complemento").val(),
		success: function(retorno){
			$("#gridFluxoAlternativo").show('slow');
			$("#gridFluxoAlternativo").html(retorno);
		}
	});
}

/*
 * Fim das requisições da grid
 */