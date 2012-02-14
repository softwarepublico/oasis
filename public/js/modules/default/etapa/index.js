$(document).ready(function(){
	
	$("#cd_area_atuacao_ti_etapa").change(function() {
		if ($(this).val() != 0) {
			montaGridEtapa();
		}else{
			$("#gridEtapa").html("").hide();
		}
	});
	
	$("#bt_cancelar_etapa").click(function() {
		limpaDadosEtapa();
	});
		
	$("#bt_salvar_etapa").click(function(){
		salvaEtapa();
	});
});

function montaGridEtapa()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/etapa/grid-etapa",
		data	: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti_etapa").val()},
		success	: function(retorno){
			// atualiza a grid
			$("#gridEtapa").html(retorno);
			$("#gridEtapa").show();
		}
	});
}

function salvaEtapa()
{
	if(!validaForm("#etapa")){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/etapa/salvar",
		data    : $('#etapa :input').serialize(),
		dataType: 'json',
		success: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaDadosEtapa()');
			}
		}
	});
}

function limpaDadosEtapa()
{
	$('#cd_etapa'			).val("");
	$('#tx_etapa'			).val("");
	$('#tx_descricao_etapa'	).val("");
	$('#ni_ordem_etapa'		).val("");
	 montaGridEtapa();
}

function recuperaEtapa(cd_etapa)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/etapa/recupera-etapa",
		data	: "cd_etapa="+cd_etapa,
		dataType:'json',
		success	: function(retorno){
			$('#cd_etapa'			).val(retorno['cd_etapa']);
			$('#tx_etapa'			).val(retorno['tx_etapa']);
			$('#tx_descricao_etapa'	).val(retorno['tx_descricao_etapa']);
			$('#ni_ordem_etapa'		).val(retorno['ni_ordem_etapa']);
		}
	});
}

function excluiEtapa(cd_etapa)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/etapa/excluir",
			data	: {"cd_etapa" : cd_etapa},
            dataType: 'json',
            success: function(retorno){
                if(retorno['erro'] == true){
                    alertMsg(retorno['msg'],retorno['type']);
                } else {
                    alertMsg(retorno['msg'],retorno['type'],'limpaDadosEtapa()');
                }
            }
		});
	});
}