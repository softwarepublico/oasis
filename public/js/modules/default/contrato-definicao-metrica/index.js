// Todas as funcionalidades utilizam AJAX e sao implementadas com o Jquery
var st_contrato = "";

$(document).ready(function(){

	$("#config_hidden_associar_metrica_contrato").val('N');
	
	// Ao selecionar uma opcao no combobox, dispara este evento que preenche os selects. 
	$("#cd_contrato_associa_metrica_contrato").change(function(){
		pesquisaPorContratoAjax();
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_metrica_contrato").click(function() {
		if (st_contrato == 'A'){
			var metricas = "["; 
			$('#cd_definicao_metrica option:selected').each(function() {
				metricas += (metricas == "[") ? $(this).val() : "," + $(this).val();
			});
			metricas += "]";
			$.ajax({
				type	: "POST",
				url		: systemName+"/contrato-definicao-metrica/associa-contrato-definicao-metrica",
				data	: "cd_contrato="+$("#cd_contrato_associa_metrica_contrato").val()+"&metricas="+metricas,
				success	: function(retorno){
					// apos atualizar as tabelas, atualiza os selects
					pesquisaPorContratoAjax();
				}
			});
		} else {
			alertMsg(i18n.L_VIEW_SCRIPT_CONTRATO_INATIVO_INALTERAVEL);
		}
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_metrica_contrato").click(function() {
		if (st_contrato == 'A'){

			//verifica se a metrica a ser excluída é a métrica padrão
			//antes de excluir
			var metricas = "[";
			$('#cd_definicao_metrica2 option:selected').each(function() {
				metricas += (metricas == "[") ? $(this).val() : "," + $(this).val();
			})
			metricas += "]";
			var cd_contrato = $("#cd_contrato_associa_metrica_contrato").val()
			$.ajax({
				type	: "POST",
				url		: systemName+"/contrato-definicao-metrica/verifica-metrica-padrao",
				data	: "cd_contrato="+cd_contrato+"&metricas="+metricas,
				dataType: 'json',
				success	: function(retorno){
				    if(retorno['comPergunta'] == true){
						confirmMsg(retorno['pergunta'], function(){desassociaDefinicaoMetrica( cd_contrato, metricas );});
					}else{
						desassociaDefinicaoMetrica( cd_contrato, metricas );
					}
				}
			});
		} else {
			alertMsg(i18n.L_VIEW_SCRIPT_CONTRATO_INATIVO_INALTERAVEL);
		}
	});
});

function desassociaDefinicaoMetrica( cd_contrato, metricas )
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/contrato-definicao-metrica/desassocia-contrato-definicao-metrica",
		data	: "cd_contrato="+cd_contrato+"&metricas="+metricas,
		success	: function(retorno){
			pesquisaPorContratoAjax();
		}
	});
}


// Realiza a pesquisa de metricas por projeto e atualiza os selects.
function pesquisaPorContratoAjax()
{	
	if ($("#cd_contrato_associa_metrica_contrato").val() != 0) {
		$.ajax({
			type	: "POST",
			url		: systemName+"/contrato-definicao-metrica/pesquisa-definicao-metrica",
			data	: "cd_contrato="+$("#cd_contrato_associa_metrica_contrato").val(),
			dataType: 'json',
			success	: function(retorno){
				ret1 = retorno[0];
				ret2 = retorno[1];
				ret3 = retorno[2];
				$("#cd_definicao_metrica").html(ret1);
				$("#cd_definicao_metrica2").html(ret2);
				st_contrato = ret3;
				montaGridMetricaAssociada();
			}
		});
	}else{
		$("#cd_definicao_metrica"		 ).empty();
		$("#cd_definicao_metrica2"		 ).empty();
		$("#gridMetricaAssociadaContrato").html('');
	}
}

function montaGridMetricaAssociada()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/contrato-definicao-metrica/grid-metrica-associada-contrato",
		data	: "cd_contrato="+$("#cd_contrato_associa_metrica_contrato").val(),
		success	: function(retorno){
			$("#gridMetricaAssociadaContrato").html(retorno);
		}
	});
}

function configAccordionAssociarMetricaContrato()
{
	if($("#config_hidden_associar_metrica_contrato").val() === 'N'){
		pesquisaPorContratoAjax();
		$("#config_hidden_associar_metrica_contrato").val('S');
	}
}

function habilitaCampoFator( id_desabilitar )
{
	$("#gridMetricaAssociadaContrato :text").removeAttr('disabled');
	$("#"+id_desabilitar).attr('disabled','disabled');
	return true;
}