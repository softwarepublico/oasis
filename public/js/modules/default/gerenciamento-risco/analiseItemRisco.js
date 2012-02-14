/**
 * Função para abrir as atividades da etapa
 */
function abrirAtividadeEtapa(cd_etapa) {
	removeTollTip();
	if ($("#atividade_"+cd_etapa).size()) {
		if ($("#" + cd_etapa + "_img").hasClass("menos")) {
			$('#atividade_' + cd_etapa).toggle();
			$("#" + cd_etapa + "_img").removeClass("menos").addClass("mais");
		} else {
			$('#atividade_' + cd_etapa).fadeIn(1500);
			$("#" + cd_etapa + "_img").removeClass("mais").addClass("menos");
		}
	} else {
		showToolTip(i18n.L_VIEW_SCRIPT_SEM_ATIVIDADE_PARA_ETAPA, $("#"+cd_etapa+"_img"));
	}
}

function abrirItensAtividade(cd_etapa, cd_atividade) 
{
	removeTollTip();
	if($("#"+cd_etapa+"_"+cd_atividade+"_item").size()){
		if($("#"+cd_etapa+"_"+cd_atividade+"_img").hasClass("menos")){
			$("#"+cd_etapa+"_"+cd_atividade+"_item").fadeOut(250);
			$("#"+cd_etapa+"_"+cd_atividade+"_img").removeClass("menos").addClass("mais");
		} else {
			$("#"+cd_etapa+"_"+cd_atividade+"_item").fadeIn(1500);
			$("#"+cd_etapa+"_"+cd_atividade+"_img").removeClass("mais").addClass("menos");
		}
	} else {
		showToolTip(i18n.L_VIEW_SCRIPT_SEM_ITEM_RISCO_PARA_ATIVIDADE, $("#"+cd_etapa+"_"+cd_atividade+"_img"));
	}
}

function abrirQuestionarioItemRisco(cd_item_risco, cd_etapa, cd_atividade)
{
	 $.ajax({
        type: "POST",
        url: systemName+"/gerenciamento-risco/questionario-analise-item-risco",
        data: "cd_etapa="+cd_etapa
              +"&cd_atividade="+cd_atividade
              +"&cd_item_risco="+cd_item_risco
              +"&cd_projeto="+$('#cd_projeto_risco').val()
              +"&cd_proposta="+$('#cd_proposta_risco').val(),
        success: function(retorno){
			$('#questionarioAnaliseItemRisco').html(retorno);
	        $("#cd_etapa_risco").val(cd_etapa);
			$("#cd_atividade_risco").val(cd_atividade);
			$("#cd_item_risco").val(cd_item_risco);
			$('#container-gerenciamento-risco, #li_aba_questionarioAnaliseRisco').triggerTab(3).show();
			$('#container-gerenciamento-risco, #li_aba_analiseRisco').show();
			abreAnaliseRisco();
        }
    });	
}

/**
 * Função para abrir as atividades da etapa
 */
function gridQuestionarioItemRisco(cd_item_risco, cd_etapa, cd_atividade)
{
	 $.ajax({
        type	: "POST",
        url		: systemName+"/gerenciamento-risco/grid-questionario-item-risco",
        data	: "cd_etapa="+cd_etapa+"&cd_atividade="+cd_atividade+"&cd_item_risco="+cd_item_risco,
        success	: function(retorno){
			$('#gridQuestionarioItem').html(retorno);
        }
    });
}

function abreAnaliseRisco()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/gerenciamento-risco/analise-risco",
		data    : "cd_etapa="+$('#cd_etapa_risco').val()
				 +"&cd_atividade="+$('#cd_atividade_risco').val()
				 +"&cd_item_risco="+$('#cd_item_risco').val()
                 +"&cd_projeto="+$('#cd_projeto_risco').val()
                 +"&cd_proposta="+$('#cd_proposta_risco').val(),
        success: function(retorno){
			$('#analiseRisco').html(retorno);
        }
    });
}