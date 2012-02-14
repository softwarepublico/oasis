$(document).ready(function(){
	$("#bt_consultar_solicitacao_projeto").click(function (){
		ajaxGridSolicitacaoServicoConsulta();
	});	
});

function ajaxGridSolicitacaoServicoConsulta() 
{
	if ($("#cd_unidade").val() == "0" && $("#tx_solicitante").val() == "" && $("#solicitacao").val() == "" && $("#dt_inicio").val() == "" && $("#dt_fim").val() == "" && $("#cd_profissional").val() == "-1" && $("#tx_solicitacao").val() == ""){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PARAMETRO_PESQUISA);
		return false;
	}

	if (($("#dt_inicio").val() != "" && $("#dt_fim").val() == "") || ($("#dt_inicio").val() == "" && $("#dt_fim").val() != "")){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_PERIODO_PESQUISA);
		return false;
	}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico-consulta-objeto-projeto/grid-solicitacao-servico-consulta",
		data	: "cd_unidade="+$("#cd_unidade").val()+
				  "&solicitacao="+$("#solicitacao").val()+
				  "&tx_solicitante="+$("#tx_solicitante").val()+
				  "&dt_inicio="+$("#dt_inicio").val()+
				  "&dt_fim="+$("#dt_fim").val()+
				  "&cd_profissional="+$("#cd_profissional").val()+
				  "&tx_solicitacao="+$("#tx_solicitacao").val()+
				  "&tipo_consulta="+$("input[name=tipo_consulta]:checked").val(),
		success: function(retorno){
			$("#gridSolicitacaoServicoConsulta").html(retorno);
		}
	});
}

function abreTabDetalheSolicitacao(cd_objeto, ni_solicitacao, ni_ano_solicitacao, tab_origem)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico-consulta-objeto-projeto/tab-detalhe-solicitacao",
		data	: "cd_objeto="+cd_objeto
				 +"&ni_solicitacao="+ni_solicitacao
				 +"&ni_ano_solicitacao="+ni_ano_solicitacao
				 +"&tab_origem="+tab_origem,
		success: function(retorno){
			$("#detalhe-solicitacao").html(retorno);
			$('#container-solicitacao-servico').triggerTab(3);
			$("#li-detalhe-solicitacao").css("display", "");
		}
	});
}