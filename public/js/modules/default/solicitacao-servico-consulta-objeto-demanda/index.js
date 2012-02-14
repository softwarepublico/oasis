$(document).ready(function(){
	$("#bt_consultar").click(function (){
		ajaxGridSolicitacaoTipoDemandaConsulta();
	});			
});

function ajaxGridSolicitacaoTipoDemandaConsulta() 
{
	if ($("#cd_unidade_consulta_tipo_demanda").val() == "0" &&
        $("#tx_solicitante").val() == "" &&
        $("#dt_inicio_consulta_tipo_demanda").val() == "" &&
        $("#dt_fim_consulta_tipo_demanda").val() == "" &&
        $("#cd_profissional").val() == "-1" &&
        $("#solicitacao").val() == "" &&
        $("#tx_demanda").val() == "")
    {
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PARAMETRO_PESQUISA);
		return false;
	}

	if (($("#dt_inicio_consulta_tipo_demanda").val() != "" && 
         $("#dt_fim_consulta_tipo_demanda").val() == "") ||
        ($("#dt_inicio_consulta_tipo_demanda").val() == "" &&
         $("#dt_fim_consulta_tipo_demanda").val() != "")
        ){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_PERIODO_PESQUISA);
		return false;
	}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico-consulta-objeto-demanda/grid-solicitacao-tipo-demanda-consulta",
		data	: {'cd_unidade'     : $("#cd_unidade_consulta_tipo_demanda").val(),
                   'tx_solicitante' : $("#tx_solicitante"                  ).val(),
                   'dt_inicio'      : $("#dt_inicio_consulta_tipo_demanda" ).val(),
                   'dt_fim'         : $("#dt_fim_consulta_tipo_demanda"    ).val(),
                   'cd_profissional': $("#cd_profissional"                 ).val(),
                   'solicitacao'    : $("#solicitacao"                     ).val(),
                   'tx_demanda'     : $("#tx_demanda"                      ).val(),
                   'tipo_consulta'  : $("input[name=tipo_consulta]:checked").val()},
		success: function(retorno){
			$("#gridSolicitacaoTipoDemandaConsulta").html(retorno);
		}
	});
}

function abreTabProfissionalDesignado(cd_demanda, cd_profissional, tab_origem)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico-consulta-objeto-demanda/tab-profissional-designado",
		data	: "cd_demanda="+cd_demanda
				 +"&cd_profissional="+cd_profissional
				 +"&tab_origem="+tab_origem,
		success	: function(retorno){
			$("#profissional-designado"			).html(retorno);
			$('#container-solicitacao-servico'	).triggerTab(3);
			$("#li-profissional-designado"		).css("display", "");
		}
	});
}

function abreTabDetalheSolicitacaoDemanda(cd_demanda, tab_origem)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-servico-consulta-objeto-demanda/tab-detalhe-solicitacao",
		data	: "cd_demanda="+cd_demanda
				 +"&tab_origem="+tab_origem,
		success	: function(retorno){
			$("#detalhe-solicitacao"			).html(retorno);
			$('#container-solicitacao-servico'	).triggerTab(4);
			$("#li-detalhe-solicitacao"			).css("display", "");
		}
	});
}