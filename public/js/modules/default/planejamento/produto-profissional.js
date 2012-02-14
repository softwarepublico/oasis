$('#aba_produto_profissional').ready(function(){
    
	$('#cd_contrato_planejamento_produto_profissional').change(function(){
		pesquisaProjetoPlanejamentoProdutoProfissional();
        limpaSeletores();
        $('#cd_proposta_planejamento_produto_profissional').empty();
	});

	$('#cd_projeto_planejamento_produto_profissional').change(function(){
		pesquisaPropostaProjetoPlanejamentoProdutoProfissional();
        limpaSeletores();
        $('#cd_parcela_planejamento_produto_profissional').empty();
        $('#cd_produto_planejamento_produto_profissional').empty();
	});

	$('#cd_proposta_planejamento_produto_profissional').change(function(){
		pesquisaParcelaPropostaProjetoPlanejamentoProdutoProfissional();
        limpaSeletores();
        $('#cd_produto_planejamento_produto_profissional').empty();
	});

	$('#cd_parcela_planejamento_produto_profissional').change(function(){
        //limpa o combo de profissional para buscar todos os produtos
        $("#cd_profissional_planejamento_produto_profissional").val('');
		pesquisaProdutoAssociadoProfissional();
        limpaSeletores();
	});

	$('#cd_produto_planejamento_produto_profissional').change(function(){
		pesquisaProfissionalProjetoPlanejamentoProdutoProfissional();
	});

    // ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_profissional_produto_profissional").click(function() {
        if(!validaForm('#aba_produto_profissional')){return false}

        var count    = 0;
		var profissionais = "[";
		$('#cd_profissional_planejamento_produto_profissional option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
            count++;
		});
		profissionais += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PRODUTO_ASSOCIAR_PROFISSIONAL)
            return false;
        }

		$.ajax({
			type    : "POST",
			url     : systemName+"/planejamento/associa-profissional-produto",
			data    : {'cd_projeto'     : $("#cd_projeto_planejamento_produto_profissional"     ).val(),
                       'cd_proposta'    : $("#cd_proposta_planejamento_produto_profissional"    ).val(),
                       'cd_parcela'     : $("#cd_parcela_planejamento_produto_profissional"     ).val(),
                       'cd_produto'     : $("#cd_produto_planejamento_produto_profissional"     ).val(),
                       'profissionais'       : profissionais},
			success : function(){
				// apos atualizar as tabelas, atualiza os selects
                pesquisaProfissionalProjetoPlanejamentoProdutoProfissional();
			}
		});
	});

	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_profissional_produto_profissional").click(function() {
        if(!validaForm('#aba_produto_profissional')){return false}

        var count    = 0;
		var profissionais = "[";
		$('#cd_profissional_planejamento_produto_profissional_associados option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
            count++;
		});
		profissionais += "]";

        if(count==0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PRODUTO_DESASSOCIAR_PROFISSIONAL)
            return false;
        }
		$.ajax({
			type    : "POST",
			url     : systemName+"/planejamento/desassocia-profissional-produto",
			data    : {'cd_projeto'     : $("#cd_projeto_planejamento_produto_profissional"     ).val(),
                       'cd_proposta'    : $("#cd_proposta_planejamento_produto_profissional"    ).val(),
                       'cd_parcela'     : $("#cd_parcela_planejamento_produto_profissional"     ).val(),
                       'cd_produto'     : $("#cd_produto_planejamento_produto_profissional").val(),
                       'profissionais'  : profissionais},
			success : function(){
			    pesquisaProfissionalProjetoPlanejamentoProdutoProfissional();
			}
		});
	});
});

function pesquisaProjetoPlanejamentoProdutoProfissional()
{
    if($("#cd_contrato_planejamento_produto_profissional").val() == 0){return false}

	$.ajax({
		type    : "POST",
		url     : systemName+"/planejamento/pesquisa-projeto",
		data    : {"cd_contrato":$("#cd_contrato_planejamento_produto_profissional").val()},
		success : function(retorno){
			$("#cd_projeto_planejamento_produto_profissional").html(retorno);
		}
	});
}

function pesquisaPropostaProjetoPlanejamentoProdutoProfissional()
{
	$.ajax({
		type    : "POST",
		url     : systemName+"/planejamento/pesquisa-proposta-projeto",
		data    : {"cd_projeto":$("#cd_projeto_planejamento_produto_profissional").val()},
		success : function(retorno){
			$("#cd_proposta_planejamento_produto_profissional").html(retorno);
		}
	});
}

function pesquisaParcelaPropostaProjetoPlanejamentoProdutoProfissional()
{
	$.ajax({
		type    : "POST",
		url     : systemName+"/planejamento/pesquisa-parcela-proposta-projeto",
		data    : {"cd_projeto":$("#cd_projeto_planejamento_produto_profissional").val(),
                   "cd_proposta": $("#cd_proposta_planejamento_produto_profissional").val()},
		success : function(retorno){
			$("#cd_parcela_planejamento_produto_profissional").html(retorno);
		}
	});
}

function pesquisaProdutoAssociadoProfissional()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/planejamento/pesquisa-produto",
        data    : {'cd_projeto'     : $("#cd_projeto_planejamento_produto_profissional"     ).val(),
                   'cd_proposta'    : $("#cd_proposta_planejamento_produto_profissional"    ).val(),
                   'cd_parcela'     : $("#cd_parcela_planejamento_produto_profissional"     ).val()},
        success : function(retorno){
            $("#cd_produto_planejamento_produto_profissional").html(retorno);
        }
    });

}

function pesquisaProfissionalProjetoPlanejamentoProdutoProfissional()
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/planejamento/pesquisa-profissional-produto",
        data    : {'cd_projeto'     : $("#cd_projeto_planejamento_produto_profissional"     ).val(),
                   'cd_proposta'    : $("#cd_proposta_planejamento_produto_profissional"    ).val(),
                   'cd_parcela'     : $("#cd_parcela_planejamento_produto_profissional"     ).val(),
                   'cd_produto'     : $("#cd_produto_planejamento_produto_profissional"     ).val()},
        dataType: 'json',
        success : function(retorno){
            $("#cd_profissional_planejamento_produto_profissional").html(retorno[0]);
            $("#cd_profissional_planejamento_produto_profissional_associados").html(retorno[1]);
        }
    });

}

function limpaSeletores()
{
    $("#cd_profissional_planejamento_produto_profissional"           ).empty();
    $("#cd_profissional_planejamento_produto_profissional_associados").empty();

}