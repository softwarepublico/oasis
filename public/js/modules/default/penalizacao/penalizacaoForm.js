$(document).ready(function() { 
    $('#btn_salvar_penalizacao').click(function(){
    	salvaDadosPenalizacao();
    });
});

function salvaDadosPenalizacao()
{
	if(!validaCampos()){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/penalizacao/trata-dados-penalizacao",
		data	: $('#divFormPenalizacao :input').serialize()
				  +"&cd_contrato="+$('#cd_contrato_penalizacao').val()
				  +"&dt_penalizacao="+$('#dt_penalizacao').val(),
		success	: function(retorno){
			alertMsg(retorno);
			montaFormPenalizacao();
		}
	});
}

function excluiPenalizacao(dt_penalizacao, cd_contrato,cd_penalidade)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/penalizacao/excluir-penalizacao",
            data	: "dt_penalizacao="+dt_penalizacao
                     +"&cd_penalidade="+cd_penalidade
                     +"&cd_contrato="+cd_contrato,
            success	: function(retorno){
                $('#ni_qtd_ocorrencia_'+cd_penalidade).val("");
                $('#tx_obs_penalizacao_'+cd_penalidade).val("");
                $('#imgExcluir'+cd_penalidade).hide();
                alertMsg(retorno);
            }
        });
    });
}