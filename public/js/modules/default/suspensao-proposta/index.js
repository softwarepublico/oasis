// Todas as funcionalidades utilizam AJAX e sao implementadas com o Jquery
$(document).ready(function(){

	// Ao selecionar uma opcao no combobox, dispara este evento que preenche os selects.
	$("#cd_contrato_suspensao_proposta").change(function() {
		pesquisaPropostaAjax();
	});

	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_suspensao_proposta").click(function() {
		var propostas = "[\"";
		$('#cd_projeto_proposta_suspensao_proposta_1 option:selected').each(function() {
			propostas += (propostas == "[\"") ? $(this).val() : "\",\"" + $(this).val();
		});
		propostas += "\"]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/suspensao-proposta/suspende-proposta",
			data	: "cd_contrato="+$("#cd_contrato_suspensao_proposta").val()+
					  "&propostas="+propostas,
			success: function(retorno){
				//altera o valor do hidden de configuração do accordion de perfil profissional
				$("#config_hidden_suspensao_proposta").val('N');
				
				// apos atualizar as tabelas, atualiza os selects
				pesquisaPropostaAjax();
			}
		});
	});
	
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_suspensao_proposta").click(function() {
		var propostas = "[\"";
		$('#cd_projeto_proposta_suspensao_proposta_2 option:selected').each(function() {
			propostas += (propostas == "[\"") ? $(this).val() : "\",\"" + $(this).val();
		});
		propostas += "\"]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/suspensao-proposta/retira-suspensao-proposta",
			data	: "cd_contrato="+$("#cd_contrato_suspensao_proposta").val()+
					  "&propostas="+propostas,
			success: function(retorno){
				//altera o valor do hidden de configuração do accordion de perfil profissional
				$("#config_hidden_suspensao_proposta").val('N');

				// apos atualizar as tabelas, atualiza os selects
				pesquisaPropostaAjax();
			}
		});
	});
});

// Realiza a pesquisa de profissionais por projeto e atualiza os selects.
function pesquisaPropostaAjax()
{
	if ($("#cd_contrato_suspensao_proposta").val() != 0) {
		$.ajax({
			type	: "POST",
			url		: systemName+"/suspensao-proposta/pesquisa-proposta",
			data	: "cd_contrato="+$("#cd_contrato_suspensao_proposta").val(),
			dataType: 'json',
			success	: function(retorno){
				proj1 = retorno[0];
				proj2 = retorno[1];
				$("#cd_projeto_proposta_suspensao_proposta_1").html(proj1);
				$("#cd_projeto_proposta_suspensao_proposta_2").html(proj2);
			}
		});
	} else {
		$("#cd_projeto_proposta_suspensao_proposta_1").empty();
		$("#cd_projeto_proposta_suspensao_proposta_2").empty();
	}
}

