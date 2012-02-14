$('#formPlanejamento').ready(function(){

	$('#cd_projeto_execucao_produto_parcela').change(function(){
		comboProposta();
	});
	$('#cd_proposta_execucao_produto_parcela').change(function(){
		comboParcela();
	});
	$('#cd_parcela_execucao_produto_parcela').change(function(){
		gridExecucaoProdutoParcela();
	});
	
	$('#btn_salvar_execucao_produto_parcela').click(function(){
		salvaPlanejamento();
	});
});

function salvaPlanejamento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/planejamento/salva-dados-planejamento",
		data	: $('#formPlanejamento :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
		}
	});
}

function gridExecucaoProdutoParcela()
{
	if($('#cd_proposta_execucao_produto_parcela').val() == 0){
		showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_PROPOSTA,$('#cd_proposta_execucao_produto_parcela'));
		return false;
	}
	if($('#cd_parcela_execucao_produto_parcela').val() == 0){
		showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_PARCELA,$('#cd_parcela_execucao_produto_parcela'));
		return false;
	}
	$.ajax({
		type	: "POST",
		url		: systemName+"/execucao-produto-parcela/grid-execucao-produto-parcela",
		data	: "cd_projeto="+$("#cd_projeto_execucao_produto_parcela").val()
				  +"&cd_proposta="+$("#cd_proposta_execucao_produto_parcela").val()
				  +"&cd_parcela="+$("#cd_parcela_execucao_produto_parcela").val(),
		success	: function(retorno){
			$('#gridExecucaoProdutoParcela').html(retorno);
			$('#gridExecucaoProdutoParcela').show();		
		}
	});
	return true;
}

function comboProposta()
{
	if ($("#cd_projeto_execucao_produto_parcela").val() != 0){
		$.ajax({
			type	: "POST",
			url		: systemName + "/proposta/pesquisa-proposta",
			data	: "cd_projeto=" + $("#cd_projeto_execucao_produto_parcela").val(),
			success	: function(retorno){
				$('#cd_proposta_execucao_produto_parcela').html(retorno);
			}
		});
	}
}

function comboParcela()
{
	if ($("#cd_projeto_execucao_produto_parcela").val() != 0 && $("#cd_proposta_execucao_produto_parcela").val() != 0){
		$.ajax({
			type	: "POST",
			url		: systemName + "/criar-parcela/pesquisa-parcela",
			data	: "cd_projeto=" + $("#cd_projeto_execucao_produto_parcela").val()+
					  "&cd_proposta=" + $("#cd_proposta_execucao_produto_parcela").val(),
			success: function(retorno){
				$('#cd_parcela_execucao_produto_parcela').html(retorno);
			}
		});
	}
}