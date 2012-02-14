$(document).ready(function() { 
	
	//pega o click do botão nova medicao
	$("#btn_nova_analise_medicao").click(function() {
		limpaInputsNovaAnaliseMedicao();
		habilitaAbaNovaAnaliseMedicao();
		carregaCombosAnaliseMedicao();
	});
	
	//pega o click do botão salvar analise medicao
	$("#btn_salvar_analise_medicao").click(function() {
		salvarNovaAnaliseMedicao();
	});
	
	//pega o click do botão alterar analise medicao
	$("#btn_alterar_analise_medicao").click(function() {
		alterarAnaliseMedicao();
	});

	//pega o click do botão cancelar analise medicao
	$("#btn_cancelar_analise_medicao").click(function() {
		desabilitaAbaNovaAnaliseMedicao();
	});
});

function montaGridAnaliseMedicao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/grid-analise-medicao",
		success	: function(retorno){
			// atualiza a grid
			$("#gridAnaliseMedicao").html(retorno);
		}
	});
}

function carregaCombosAnaliseMedicao()
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/medicao/carrega-combos-analise-medicao",
		dataType : 'json',
		success  : function(retorno){
			
            var medicao = retorno[0];
			var box		= retorno[1];
			$("#cmb_medicao"	).html(medicao);
			$("#cmb_box_inicio"	).html(box);
		}
	});
}

function salvarNovaAnaliseMedicao()
{
	if( !validaForm('#div_nova_analise_medicao') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/salvar-nova-analise-medicao",
		data	: $("#div_nova_analise_medicao :input").serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridAnaliseMedicao();
			desabilitaAbaNovaAnaliseMedicao();
		}
	});
}

function recuperaDadosAnaliseMedicao(dt_analise, cd_medicao, cd_box_inicio)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/recupera-dados-analise-medicao",
		data	: "cd_medicao="+cd_medicao+
                  "&cd_box_inicio="+cd_box_inicio+
                  "&dt_analise="+dt_analise,
		dataType: 'json',
		success	: function(retorno){
			
			desabilitaCombos();
			
			$('#descMedicao').html(retorno['tx_medicao']).show();
			$('#descBox').html(retorno['tx_titulo_box_inicio']).show();
			
			$('#cd_medicao'			 		 ).val(retorno['cd_medicao']);
			$('#cd_box_inicio'			 	 ).val(retorno['cd_box_inicio']);
			$('#hidden_dt_analise'			 ).val(retorno['dt_analise_medicao']);
			
			$('#tx_resultado_analise_medicao').val(retorno['tx_resultado_analise_medicao']);
			$('#tx_dados_medicao'			 ).val(retorno['tx_dados_medicao']);
			
			$('#btn_alterar_analise_medicao').show();
			$('#btn_salvar_analise_medicao'	).hide();
			
			habilitaAbaNovaAnaliseMedicao();
		}
	});
}

function alterarAnaliseMedicao()
{
	if( !validaForm('#div_nova_analise_medicao') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/medicao/alterar-analise-medicao",
		data	: $("#div_nova_analise_medicao :input").serialize()+
                  "&dt_analise="+$('#hidden_dt_analise').val()+
                  "&cd_medicao="+$('#cd_medicao').val()+
                  "&cd_box_inicio="+$('#cd_box_inicio').val(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridAnaliseMedicao();
			limpaInputsNovaAnaliseMedicao();
		}
	});
}

function habilitaAbaNovaAnaliseMedicao()
{
	$("#li_aba_nova_analise_medicao").show();
	$('#container-medicao').triggerTab(4);
}

function desabilitaAbaNovaAnaliseMedicao()
{
	$("#li_aba_nova_analise_medicao").hide();
	$('#container-medicao').triggerTab(3);
	limpaInputsNovaAnaliseMedicao();
}

function limpaInputsNovaAnaliseMedicao()
{
	$('#hidden_dt_analise'			 	).val('');
	$("#div_nova_analise_medicao :input").val('');
	$('#btn_alterar_analise_medicao'	).hide();
	$('#btn_salvar_analise_medicao'		).show();
	
	$('#cmb_medicao'	).show();
	$('#cmb_box_inicio'	).show();
	$('#descMedicao'	).html("").hide();
	$('#descBox'		).html("").hide();
	
	$('#lb_medicao'		).addClass('required');
	$('#lb_box'			).addClass('required');
}

function desabilitaCombos()
{
	$('#cmb_medicao'	).hide();
	$('#cmb_box_inicio'	).hide();
	
	$('#lb_medicao'		).removeClass('required');
	$('#lb_box'			).removeClass('required');
}