/**
 * Função para montar a grid das propostas abertas
 */
function gridProjetoPropostaExecucao(st_impacto) 
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/gerenciamento-risco/propostas-andamento-risco",
        data	: "impacto="+st_impacto,
        success	: function(retorno){
            $('#gridProjetosPropostaExecucao').html(retorno);
        }
    });
}

/**
 * Função que ira abrir a aba analise de risco
 * para montar a grid com as etapas e as atividades
 */
function gridAnaliseRisco(cd_projeto, cd_proposta, st_impacto) 
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/gerenciamento-risco/analise-item-risco",
        data	: "cd_projeto="+cd_projeto
				 +"&cd_proposta="+cd_proposta
				 +"&st_impacto="+st_impacto,
        success	: function(retorno){
            $('#gridAnaliseItemRisco').html(retorno);
            $('#container-gerenciamento-risco, #li_aba_AnaliseItemRisco').triggerTab(2).show();
        }
    });
}