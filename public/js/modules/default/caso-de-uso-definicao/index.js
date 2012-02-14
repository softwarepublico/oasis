$(document).ready(function(){
    
	$('#alterarDefinicao').hide();
	$('#cancelarDefinicao').hide();
	
	$('#adicionarDefinicao').click(function(){
		if( !validaForm('#tabelaDefinicao') ){ return false; }
		salvarDefinicao();
	});
	$('#alterarDefinicao').click(function(){
		if( !validaForm('#tabelaDefinicao') ){ return false; }
		alteraDefinicao();
	});
	$('#cancelarDefinicao').click(function(){
		limpaValueDefinicao();
	});
});

/*
 * Ajax para Salvar as definições
 */
function salvarDefinicao()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-definicao/salva-definicao-projeto",
		data: $('#tabelaDefinicao :input').serialize()
			  +"&cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			alertMsg(retorno);
			limpaValueDefinicao();
			ajaxpesquisaDefinicaoProjeto();
			//Atualiza a combo caso de uso na aba de Interação
			comboCasoDeUsoInteracao();
		}
	});
}

/*
 * Ajax para alterar as definições
 */
function alteraDefinicao(){
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-definicao/altera-caso-de-uso-projeto",
		data: $('#tabelaDefinicao :input').serialize()
		  	  +"&cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			alertMsg(retorno);
			limpaValueDefinicao();
			ajaxpesquisaDefinicaoProjeto();
			//Atualiza a combo caso de uso na aba de Interação
			comboCasoDeUsoInteracao();
		}
	});
}

/*
* Ajax para excluir um caso de uso
*/
function excluirDefinicao(cd_caso_de_uso, dt_versao_caso_de_uso)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type: "POST",
            url: systemName+"/caso-de-uso-definicao/excluir-caso-de-uso-projeto",
            data: "cd_caso_de_uso="+cd_caso_de_uso
                  +"&dt_versao_caso_de_uso="+dt_versao_caso_de_uso,
            success: function(retorno){
                alertMsg(retorno);
                ajaxpesquisaDefinicaoProjeto();
                //Atualiza a combo caso de uso na aba de Interação
                comboCasoDeUsoInteracao();
				limpaValueDefinicao();
            }
        });
    });
}

/*
* Ajax para pesquisar os casos de uso incluídos
*/
function ajaxpesquisaDefinicaoProjeto()
{	
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-definicao/pesquisa-definicao-projeto",
		data: "cd_projeto="+$("#cd_projeto").val()
			  +"&cd_modulo="+$('#cd_modulo').val(),
		success: function(retorno){
			$("#gridDefinicao").html(retorno);
		}
	});
}
/*
* Ajax para recupera os dados e apresentar nos respectivos campos
*/
function recuperaDefinicao(cd_caso_de_uso,cd_projeto,cd_modulo,dt_versao_caso_de_uso)
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-definicao/recupera-definicao-projeto",
		data: "cd_projeto="+cd_projeto
			   +"&cd_caso_de_uso="+cd_caso_de_uso
			   +"&cd_modulo="+cd_modulo
			   +"&dt_versao_caso_de_uso="+dt_versao_caso_de_uso,
		dataType:'json',
		success: function(retorno){
			$("#cd_caso_de_uso").val(retorno[0]['cd_caso_de_uso']);
			$("#cd_modulo").val(retorno[0]['cd_modulo']);
			$("#tx_caso_de_uso").val(retorno[0]['tx_caso_de_uso']);
			$("#ni_ordem_caso_de_uso").val(retorno[0]['ni_ordem_caso_de_uso']);
			$("#tx_descricao_caso_de_uso").val(retorno[0]['tx_descricao_caso_de_uso']);
			if(retorno[0]['st_fechamento_caso_de_uso'] != null){
				$("#st_fechamento_caso_de_uso").attr('checked','checked');
				$('#adicionarDefinicao').hide();
				$('#alterarDefinicao').hide();
				$('#cancelarDefinicao').removeClass('push-4').addClass('clear-l push-4').show();
			} else {
				$('#adicionarDefinicao').hide();
				$('#alterarDefinicao').show();
				$('#cancelarDefinicao').removeClass('clear-l push-4').addClass('push-4').show();
			}
			$("#dt_fechamento_caso_de_uso").val(retorno[0]['dt_fechamento_caso_de_uso']);
			$("#dt_versao_caso_de_uso").val(retorno[0]['dt_versao_caso_de_uso']);
		}
	});
}

function limpaValueDefinicao(){
	$('#cd_modulo'					).val("0");
	$('#cd_caso_de_uso'				).val("");
	$('#tx_caso_de_uso'				).val("");
	$('#ni_ordem_caso_de_uso'		).val("");
	$('#dt_versao_caso_de_uso'		).val("");
	$('#tx_descricao_caso_de_uso'	).val("");
	$('#st_fechamento_caso_de_uso'	).attr("checked","");
	
	$('#adicionarDefinicao'	).show();
	$('#alterarDefinicao'	).hide();
	$('#cancelarDefinicao'	).hide();
}