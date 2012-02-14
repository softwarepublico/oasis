$(document).ready(function(){
	
	ajaxGridDemandaAndamento();
	ajaxGridDemandaExecutada();
	ajaxGridDemandaConcluida();
	
	var intervalID = window.setInterval('ajaxGridDemandaAndamento();', 300000);
	var intervalID = window.setInterval('ajaxGridDemandaExecutada();', 300000);
	var intervalID = window.setInterval('ajaxGridDemandaConcluida();', 300000);
	
	
	//Solicitações de Serviço Tipo Demanda a Encaminhar
	$("#mesDemandaAndamento").change(function(){
		if($(this).val() != 0){
			ajaxGridDemandaAndamento();
			apresentaData($(this).val(),$("#anoDemandaAndamento").val(),"mesAnoPainelDemanda");
		} else {
			apresentaData($(this).val(),$("#anoDemandaAndamento").val(),"mesAnoPainelDemanda");
		}
	});
	
	$("#anoDemandaAndamento").change(function(){

		if($(this).val() != 0){
			ajaxGridDemandaAndamento();
			apresentaData($("#mesDemandaAndamento").val(),$(this).val(),"mesAnoPainelDemanda");
		} else {
			apresentaData($("#mesDemandaAndamento").val(),$(this).val(),"mesAnoPainelDemanda");
		}
	});
	
	$("#cd_profissional_demanda").change(function(){
		ajaxGridDemandaAndamento();
		apresentaData($("#mesDemandaAndamento").val(),$("#anoDemandaAndamento").val(),"mesAnoPainelDemanda");
	});		
	
	//Solicitações Tipo Demanda Encaminhadas
	//e Executadas
	$("#mesDemandaExecutada").change(function(){
		if($("#mesDemandaExecutada").val() != 0){
			ajaxGridDemandaExecutada();
			apresentaData($("#mesDemandaExecutada").val(),$("#anoDemandaExecutada").val(),"mesAnoExecutada");
		} else {
			apresentaData($("#mesDemandaExecutada").val(),$("#anoDemandaExecutada").val(),"mesAnoExecutada");
		}
	});
	
	$("#anoDemandaExecutada").change(function(){
		if($("#anoDemandaExecutada").val() != 0){
			ajaxGridDemandaExecutada();
			apresentaData($("#mesDemandaExecutada").val(),$("#anoDemandaExecutada").val(),"mesAnoExecutada");
		} else {
			apresentaData($("#mesDemandaExecutada").val(),$("#anoDemandaExecutada").val(),"mesAnoExecutada");
		}
	});
	
	$("#cd_profissional_demanda_executada").change(function(){
		ajaxGridDemandaExecutada();
		apresentaData($("#mesDemandaExecutada").val(),$("#anoDemandaExecutada").val(),"mesAnoExecutada");
	});		
		
	//Demandas Concluidas
	$("#mesDemandaConcluida").change(function(){
		if($("#mesDemandaConcluida").val() != 0){
			ajaxGridDemandaConcluida();
			apresentaData($("#mesDemandaConcluida").val(),$("#anoDemandaConcluida").val(),"mesAnoConcluida");
		} else {
			apresentaData($("#mesDemandaConcluida").val(),$("#anoDemandaConcluida").val(),"mesAnoConcluida");
		}
	});
	
	$("#anoDemandaConcluida").change(function(){
		if($("#anoDemandaConcluida").val() != 0){
			ajaxGridDemandaConcluida();
			apresentaData($("#mesDemandaConcluida").val(),$("#anoDemandaConcluida").val(),"mesAnoConcluida");
		} else {
			apresentaData($("#mesDemandaConcluida").val(),$("#anoDemandaConcluida").val(),"mesAnoConcluida");
		}
	});	
	
	$("#cd_profissional_demanda_concluida").change(function(){
		ajaxGridDemandaConcluida();
		apresentaData($("#mesDemandaConcluida").val(),$("#anoDemandaConcluida").val(),"mesAnoConcluida");
	});		
	
	// pega evento no onclick do botao
	$("#nova_demanda").click(function(){
		window.location.href = systemName+"/demanda";
	})
	
	$("#bt_consultar").click(function (){
		ajaxGridSolicitacaoTipoDemandaConsulta();
	});		
});

function ajaxGridDemandaAndamento() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/painel-demanda/grid-demanda-andamento",
		data	: "mes="+$("#mesDemandaAndamento").val()
			     +"&ano="+$("#anoDemandaAndamento").val()
			     +"&cd_profissional="+$("#cd_profissional_demanda").val(),
		success	: function(retorno){
			$("#gridDemandaAndamento").html(retorno);
			$('#mesAnoAndamento'	 ).html($('#mesAnoDemandaAndamento').val());
		}
	});
}

function ajaxGridDemandaExecutada() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/painel-demanda/grid-demanda-executada",
		data	: "mes="+$("#mesDemandaExecutada").val()
			     +"&ano="+$("#anoDemandaExecutada").val()
			     +"&cd_profissional="+$("#cd_profissional_demanda_executada").val(),
		success	: function(retorno){
			$("#gridDemandaExecutada").html(retorno);
			$('#mesAnoExecutada').html($('#mesAnoDemandaExecutada').val());
		}
	});
}

function ajaxGridDemandaConcluida() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/painel-demanda/grid-demanda-concluida",
		data	: "mes="+$("#mesDemandaConcluida").val()
				 +"&ano="+$("#anoDemandaConcluida").val()
				 +"&cd_profissional="+$("#cd_profissional_demanda_concluida").val(),
		success	: function(retorno){
			$("#gridDemandaConcluida").html(retorno);
			$('#mesAnoConcluida').html($('#mesAnoDemandaConcluida').val());
		}
	});
}

function abreTabProfissionalDesignado(cd_demanda, cd_profissional, tab_origem)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/painel-demanda/tab-profissional-designado",
		data	: "cd_demanda="+cd_demanda
				 +"&cd_profissional="+cd_profissional
				 +"&tab_origem="+tab_origem,
		success: function(retorno){
			$("#profissional-designado"	  ).html(retorno);
			$('#containerDemanda'		  ).triggerTab(4);
			$("#li-profissional-designado").css("display", "");
		}
	});
}

function concluiDemanda(cd_demanda)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/painel-demanda/concluir-demanda",
		data	: "cd_demanda="+cd_demanda,
		success	: function(retorno){
			alertMsg(retorno,'',"redirecionaPainelDemanda()");
			ajaxGridDemandaExecutada();
			ajaxGridDemandaConcluida();
		}
	});
}

function redirecionaPainelDemanda()
{
    window.location.href = systemName+"/painel-demanda\#demanda-executada";
}

function encaminhaDemandaParaOutroProfissional(cd_demanda) {
	window.location.href = systemName+"/demanda/index/cd_demanda/"+cd_demanda;
}

function reencaminhaDemanda(cd_demanda) {
	window.location.href = systemName+"/painel-demanda/reencaminhar-demanda/cd_demanda/"+cd_demanda;
}

function ajaxGridSolicitacaoTipoDemandaConsulta() 
{
	if ($("#cd_unidade").val() == "0" && $("#tx_solicitante").val() == "" && $("#dt_inicio").val() == "" && $("#dt_fim").val() == "" && $("#cd_profissional").val() == "-1" && $("#tx_demanda").val() == "")
	{
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PARAMETRO_PESQUISA);
		return false;
	}
	if (($("#dt_inicio").val() != "" && $("#dt_fim").val() == "") || ($("#dt_inicio").val() == "" && $("#dt_fim").val() != ""))
	{
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_PERIODO_PESQUISA);
		return false;
	}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-tipo-demanda/grid-solicitacao-tipo-demanda-consulta",
		data	: "cd_unidade="+$("#cd_unidade").val()+
				  "&tx_solicitante="+$("#tx_solicitante").val()+
				  "&dt_inicio="+$("#dt_inicio").val()+
				  "&dt_fim="+$("#dt_fim").val()+
				  "&cd_profissional="+$("#cd_profissional").val()+
				  "&tx_demanda="+$("#tx_demanda").val()+
				  "&tipo_consulta="+$("input[name=tipo_consulta]:checked").val(),
		success	: function(retorno){
			$("#gridSolicitacaoTipoDemandaConsulta").html(retorno);
		}
	});
}