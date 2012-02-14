$(document).ready(function(){
	$("#alterarAtor").hide();
	$("#cancelarAtor").hide();
	$("#adicionarAtor").click(function(){
		if( !validaForm('#tabelaAtor') ){ return false; }
		salvarAtor();
	});

	$("#alterarAtor").click(function(){
		if( !validaForm('#tabelaAtor') ){ return false; }
		alterarAtor();
	});
	
	$("#cancelarAtor").click(function(){
		limpaDadosAtor();
    });
});

function salvarAtor()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-ator/salva-ator-projeto",
		data: $('#tabelaAtor :input').serialize()
			  +"&cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			$("#tx_ator").val("");
			alertMsg(retorno);
			limpaDadosAtor();
			pesquisaAtorProjetoAjax();
			//Atualiza a combo Ator na aba de interação
			montaComboInteracaoAtor();
		}
	});
}

function alterarAtor()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-ator/altera-ator-projeto",
		data: $('#tabelaAtor :input').serialize()
		      +"&cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			$("#tx_ator").val("");
			alertMsg(retorno);
			pesquisaAtorProjetoAjax();
			limpaDadosAtor();
			//Atualiza a combo Ator na aba de interação
			montaComboInteracaoAtor();
		}
	});
}

function excluirAtorProjeto(cd_ator)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
        $.ajax({
            type: "POST",
            url: systemName+"/caso-de-uso-ator/excluir-ator-projeto",
            data: "cd_ator=" + cd_ator,
            success: function(retorno){
                alertMsg(retorno);
                pesquisaAtorProjetoAjax();
                limpaDadosAtor();
                //Atualiza a combo Ator na aba de interação
                montaComboInteracaoAtor();
            }
        });
    });
}

function pesquisaAtorProjetoAjax()
{	
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-ator/pesquisa-ator-projeto",
		data: "cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			$("#gridAtor").html(retorno);
		}
	});
}

function recuperaAtorProjetoAjax(cd_ator)
{	
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-ator/recupera-ator-projeto",
		data: "cd_ator="+cd_ator,
		success: function(retorno){
			$("#cd_ator").val(cd_ator);
			$("#tx_ator").val(retorno);
			
			$("#alterarAtor").show();
			$("#cancelarAtor").show();
			$("#adicionarAtor").hide();
		}
	});
}

function limpaDadosAtor()
{
	$("#tx_ator").val("");
	$("#cd_ator").val("");
	
    $("#alterarAtor").hide();
    $("#cancelarAtor").hide();
    $("#adicionarAtor").show();
}