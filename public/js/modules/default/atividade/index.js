$(document).ready(function(){
	
	$('#cd_area_atuacao_ti_atividade').change(function(){
		montaComboEtapa();
	});

	$("#cd_etapa_atividade").change(function() {
		if ($(this).val() != 0) {
			montaGridAtividade()
		} else {
			$('#gridAtividade').hide();
		}
	});

	$("#bt_cancelar_atividade").click(function() {
        limpaDadosAtividade();
	});

	$("#bt_salvar_atividade").click(function(){
		salvaAtividade();
	});
});

function montaComboEtapa()
{
	$.ajax({
		type: "POST",
		url: systemName+"/atividade/monta-combo-etapa",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti_atividade").val()},
		success: function(retorno){
			$('#cd_etapa_atividade').html(retorno);
		}
	});
}

function montaGridAtividade()
{
	$.ajax({
		type: "POST",
		url: systemName+"/atividade/grid-atividade",
		data: {"cd_etapa" : $("#cd_etapa_atividade").val()},
		success: function(retorno){
			$("#gridAtividade").html(retorno);
			$('#gridAtividade').show('slow');
		}
	});
}

function salvaAtividade()
{
	if(!validaForm("#atividade")){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/atividade/salvar",
		data    : $('#atividade :input').not('#cd_area_atuacao_ti_atividade').serialize(),
		dataType: 'json',
		success: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaDadosAtividade()');
                montaGridAtividade();
			}
		}
	});
}

function limpaDadosAtividade()
{
	$('#cd_atividade'			).val("");
	$('#ni_ordem_atividade'		).val("");
	$('#tx_descricao_atividade'	).val("");
	$('#tx_atividade'			).val("");
}

function recuperaAtividade(cd_atividade)
{
	$.ajax({
		type: "POST",
		url: systemName+"/atividade/recupera-atividade",
		data: "cd_atividade="+cd_atividade,
		dataType:'json',
		success: function(retorno){
            $('#cd_atividade'			).val(retorno['cd_atividade']);
            $('#ni_ordem_atividade'		).val(retorno['ni_ordem_atividade']);
            $('#tx_descricao_atividade'	).val(retorno['tx_descricao_atividade']);
            $('#tx_atividade'			).val(retorno['tx_atividade']);
		}
	});
}

function excluiAtividade(cd_atividade)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type: "POST",
            url: systemName+"/atividade/excluir",
            data: {"cd_atividade" : cd_atividade},
            dataType: 'json',
            success: function(retorno){
                if(retorno['erro'] == true){
                    alertMsg(retorno['msg'],retorno['type']);
                } else {
                    alertMsg(retorno['msg'],retorno['type'],'montaGridAtividade()');
                }
            }
        });
    });
}