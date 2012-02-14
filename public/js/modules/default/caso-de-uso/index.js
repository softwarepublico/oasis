/**
 * Está função irá chamar todas as outras funções necessarias para o accordion de
 * caso de uso
 */
function config_form_caso_de_uso()
{
	if( $("#config_hidden_caso_de_uso").val() === "N" ){
		//Ator
		pesquisaAtorProjetoAjax();
		//Definição
		//inicialização inicial dos ajax e atributos
		ajaxpesquisaDefinicaoProjeto();
		//Interação
		//monta a combo do Ator
		montaComboInteracaoAtor();
		//comboCasoDeUsoInteracao();
		//Complemento
		comboCasoDeUso();
		//Fechamento caso de Uso
		tabFecharCasoDeUso();
		$("#config_hidden_caso_de_uso").val("S");
	} else {
        //Ator
		pesquisaAtorProjetoAjax();
		//Definição
		//inicialização inicial dos ajax e atributos
		ajaxpesquisaDefinicaoProjeto();
		//Interação
		//monta a combo do Ator
		montaComboInteracaoAtor();
		//comboCasoDeUsoInteracao();
		//Complemento
		comboCasoDeUso();
		//Fechamento caso de Uso
		tabFecharCasoDeUso();
    }
}

function tabFecharCasoDeUso(cd_modulo)
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso/tab-fechar-caso-de-uso",
		data: "cd_projeto="+$('#cd_projeto').val()
		      +"&cd_modulo="+cd_modulo,
		success: function(retorno){
			$('#FechamentoCasoDeUso').html(retorno);
		}
	});	
}

function fecharCasoDeUso(cd_caso_de_uso,dt_versao_caso_de_uso)
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso/fechar-caso-de-uso",
		data: "cd_caso_de_uso="+cd_caso_de_uso
			  +"&dt_versao_caso_de_uso="+dt_versao_caso_de_uso,
		success: function(retorno){
			alertMsg(retorno,'1');
			tabFecharCasoDeUso($('#cd_modulo_fechamento').val());
			ajaxpesquisaDefinicaoProjeto();
			openGrid();
			ajaxGridInteracao();
			comboCasoDeUsoInteracao();
			comboCasoDeUso();
		}
	});	
}