$(document).ready(function(){

	$('#bt_excluir_projeto_previsto' ).hide();
	$('#bt_cancelar_projeto_previsto').hide();

	// pega evento no onclick do botao
	$("#bt_salvar_projeto_previsto").click(function(){
		salvaProjetoPrevisto();
	});
	$("#bt_cancelar_projeto_previsto").click(function() {
		$('#projeto_previsto :input'	 ).not('#cd_contrato_projeto_previsto').val('');
		$('#bt_excluir_projeto_previsto' ).hide();
		$('#bt_cancelar_projeto_previsto').hide();
	});


	$("#bt_excluir_projeto_previsto").click(function() {
		excluirProjetoPrevisto();
	});
	
	$("#cd_contrato_projeto_previsto").change(function() {
		if($("#cd_contrato_projeto_previsto").val() != "0"){
			$("#gridProjetoPrevisto").show();
			montaGridProjetoPrevisto();
		} else {
			$("#gridProjetoPrevisto").hide().html('');
		}
	});
});

function montaGridProjetoPrevisto(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-previsto/grid-projeto-previsto",
		data	: "cd_contrato="+$("#cd_contrato_projeto_previsto").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridProjetoPrevisto").html(retorno);
		}
	});
}

function excluirProjetoPrevisto()
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/projeto-previsto/excluir",
			data	: "cd_projeto_previsto="+$("#cd_projeto_previsto").val(),
			success	: function(retorno){
				alertMsg(retorno,'',"limpaDadosProjetoPrevisto()");
			}
		});
	});
}

function salvaProjetoPrevisto()
{
	if(!validaForm("#projeto_previsto")){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/projeto-previsto/salvar",
		data    : $('#projeto_previsto :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaDadosProjetoPrevisto()');
			}
		}
	});
}

function limpaDadosProjetoPrevisto()
{
	$('#cd_unidade_projeto_previsto'	).val("");
	$('#cd_projeto_previsto'			).val("");
	$('#tx_projeto_previsto'			).val("");
	$('#ni_horas_projeto_previsto'		).val("");
	$('#st_projeto_previsto'			).val("");
	$('#tx_descricao_projeto_previsto'	).val("");
	$('#cd_metrica_unidade_prevista_projeto_previsto'	).val("");
	$('#bt_excluir_projeto_previsto'	).hide();
	montaGridProjetoPrevisto();
}

function recuperaProjetoPrevisto(cd_projeto_previsto)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-previsto/recupera-projeto-previsto",
		data	: "cd_projeto_previsto="+cd_projeto_previsto,
		dataType:'json',
		success	: function(retorno){
			$('#cd_unidade_projeto_previsto'				 ).val(retorno['cd_unidade'					 ]);
			$('#cd_projeto_previsto'						 ).val(retorno['cd_projeto_previsto'			 ]);
			$('#tx_projeto_previsto'						 ).val(retorno['tx_projeto_previsto'			 ]);
			$('#ni_horas_projeto_previsto'					 ).val(retorno['ni_horas_projeto_previsto'	 ]);
			$('#st_projeto_previsto'						 ).val(retorno['st_projeto_previsto'			 ]);
			$('#tx_descricao_projeto_previsto'				 ).val(retorno['tx_descricao_projeto_previsto']);
			
			(retorno['cd_definicao_metrica']) ? $('#cd_metrica_unidade_prevista_projeto_previsto').val(retorno['cd_definicao_metrica']) : $('#cd_metrica_unidade_prevista_projeto_previsto').val('');

			$('#bt_excluir_projeto_previsto'	).show();
			$('#bt_cancelar_projeto_previsto'	).show();
		}
	});
}