$(document).ready(function() {
	
	//pega o click do botão cancelar execucao
	$("#btn_cancelar_execucao").click(function() {
		desabilitaAbaExecucao();
	});
	//pega o click do botão salvar execucao
	$("#btn_salvar_execucao").click(function() {
		salvarExecucao();
	});
});

function gerarExecucaoDecisao(dt_analise, cd_medicao, cd_box_inicio){
	$.ajax({
		type	: "POST",
		url		: systemName + "/medicao/recupera-dados-analise-medicao",
		data	: "cd_medicao=" + cd_medicao +
				  "&cd_box_inicio=" + cd_box_inicio +
				  "&dt_analise=" + dt_analise,
		dataType: 'json',
		success	: function(retorno){
		
			$('#cd_medicao').val(retorno['cd_medicao']);
			$('#cd_box_inicio').val(retorno['cd_box_inicio']);
			$('#hidden_dt_analise').val(retorno['dt_analise_medicao']);
			
			$('#desc_medicao_execucao').html(retorno['tx_medicao']);
			$('#desc_box_execucao').html(retorno['tx_titulo_box_inicio']);
			$('#desc_resultado_execucao').html(retorno['tx_resultado_analise_medicao']);
			$('#desc_dados_medicao_execucao').html(retorno['tx_dados_medicao']);
			$('#desc_decisao_execucao').html(retorno['tx_decisao']);
			$('#desc_data_decisao_execucao').html(retorno['dt_decisao_mask']);
			
			$('#st_decisao_executada').val('E');
			
			habilitaAbaExecucao();
		}
	});
}

function visualizarExecucao(dt_analise, cd_medicao, cd_box_inicio)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/recupera-dados-analise-medicao",
		data	: "cd_medicao="+cd_medicao+
				  "&cd_box_inicio="+cd_box_inicio+
				  "&dt_analise="+dt_analise,
		dataType: 'json',
		success	: function(retorno){
			
			configExibicaoExecucao();
			
			$('#desc_medicao_execucao'		).html(retorno['tx_medicao']);
			$('#desc_box_execucao'			).html(retorno['tx_titulo_box_inicio']);
			$('#desc_resultado_execucao'	).html(retorno['tx_resultado_analise_medicao']);
			$('#desc_dados_medicao_execucao').html(retorno['tx_dados_medicao']);
			$('#desc_decisao_execucao'		).html(retorno['tx_decisao']);
			$('#desc_data_decisao_execucao'	).html(retorno['dt_decisao_mask']);
			
			if(retorno['st_decisao_executada'] === "E"){
				$('#desc_st_decisao_executada').html(i18n.L_VIEW_SCRIPT_DECISAO_EXECUTADA).show();
			}else{
				$('#desc_st_decisao_executada').html(i18n.L_VIEW_SCRIPT_DECISAO_NAO_EXECUTADA).show();
			}
			
			$('#desc_dt_decisao_executada'	).html(retorno['dt_decisao_executada_mask']).show();
			$('#desc_tx_obs_decisao_executada'	).html(retorno['tx_obs_decisao_executada']).show();
			
			habilitaAbaExecucao();
		}
	});
}

function salvarExecucao()
{
	if( !validaForm('#conteiner_execucao') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/salvar-execucao",
		data	: $("#conteiner_execucao :input").serialize()+
				  "&dt_analise="+$('#hidden_dt_analise').val()+
				  "&cd_medicao="+$('#cd_medicao').val()+
				  "&cd_box_inicio="+$('#cd_box_inicio').val(),
		success: function(retorno){
			alertMsg(retorno);
			montaGridDecisaoMedicao();
			desabilitaAbaExecucao();
		}
	});
}

function configExibicaoExecucao()
{
	$('#st_decisao_executada'		).hide();
	$('#dt_decisao_executada'		).hide();
	$('#dt_decisao_executada_img'	).hide();
	$('#tx_obs_decisao_executada'	).hide();
	$('#btn_cancelar_execucao'		).addClass('clear-l');
	$('#lb_dt_decisao_executada'	).removeClass('required');
	$('#lb_tx_obs_decisao_executada').removeClass('required');
	$("#btn_salvar_execucao"		).hide();
}

function desabilitaAbaExecucao()
{
	$("#li_aba_execucao_decisao"	  ).hide();
	$('#container-medicao'			  ).triggerTab(6);
	$('#st_decisao_executada'		  ).removeAttr('checked').show();
	$('#dt_decisao_executada'		  ).val('');
	$('#tx_obs_decisao_executada'	  ).val('');
	$("#btn_salvar_execucao"	 	  ).show();
	$('#desc_st_decisao_executada'	  ).hide();
	$('#desc_dt_decisao_executada'	  ).hide();
	$('#desc_tx_obs_decisao_executada').hide();
	$('#dt_decisao_executada'		  ).show();
	$('#dt_decisao_executada_img'	  ).show();
	$('#tx_obs_decisao_executada'	  ).show();
	$('#lb_dt_decisao_executada'	  ).addClass('required');
	$('#lb_tx_obs_decisao_executada'  ).addClass('required');
	$('#btn_cancelar_execucao'		  ).removeClass('clear-l');
}

function habilitaAbaExecucao()
{
	$("#li_aba_execucao_decisao").show();
	$('#container-medicao'		).triggerTab(7);
}