$(document).ready(function(){
	$("#btn_button_salvar_analise_risco").click(function(){
		if( !validaForm("#formAnaliseRisco") ){ return false; }
		salvaAnaliseDoRisco();
	});
	
	$("#btn_button_fechar_analise_risco").click(function(){
		fecharAnaliseRisco();
	});
});

function salvaAnaliseDoRisco()
{
	$.ajax({
        type   : "POST",
        url    : systemName+"/gerenciamento-risco/salvar-analise",
        data   : $("#formAnaliseRisco :input").serialize()
                 +"&cd_etapa="+$('#cd_etapa_risco').val()
                 +"&cd_atividade="+$('#cd_atividade_risco').val()
                 +"&cd_item_risco="+$('#cd_item_risco').val()
                 +"&cd_projeto="+$('#cd_projeto_risco').val()
                 +"&cd_proposta="+$('#cd_proposta_risco').val(),
        dataType: 'json',
        success: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type']);
			}
        }
    });
}

function fecharAnaliseRisco()
{
	confirmMsg(i18n.L_VIEW_SCRIPT_FECHAR_ITEM_RISCO, function(){
		$.ajax({
	        type   : "POST",
	        url    : systemName+"/gerenciamento-risco/fechar-analise-risco",
	        data   : "cd_etapa="+$('#cd_etapa_risco').val()
	                 +"&cd_atividade="+$('#cd_atividade_risco').val()
	                 +"&cd_item_risco="+$('#cd_item_risco').val()
	                 +"&cd_projeto="+$('#cd_projeto_risco').val()
	                 +"&cd_proposta="+$('#cd_proposta_risco').val(),
	        dataType: 'json',
	        success: function(retorno){
				if(retorno['erro'] == true){
					alertMsg(retorno['msg'],retorno['type']);
				} else {
					alertMsg(retorno['msg'],retorno['type']);
				}
	        }
	    });		
	});
}