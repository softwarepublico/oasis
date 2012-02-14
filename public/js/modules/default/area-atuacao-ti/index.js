$(document).ready(function(){
    
    limpaFormAreaAtuacao();
    gridAreaAtuacao();
    
    $('#btn_salvar_area_atuacao_ti').click(function(){
        salvarAreaAtuacao();
    });
    $('#btn_cancelar_area_atuacao_ti').click(function(){
        limpaFormAreaAtuacao();
    });
});

function gridAreaAtuacao()
{
	$.ajax({
		type    : "POST",
		url     : systemName+"/area-atuacao-ti/grid-area-atuacao-ti",
		success: function(retorno){
            $('#gridAreaAtuacaoTi').html(retorno);
		}
	});
}

function salvarAreaAtuacao()
{
    if(!validaForm('#formAreaAtuacaoTi')){return false}
	$.ajax({
		type    : "POST",
		url     : systemName+"/area-atuacao-ti/salvar",
		data    : $('#formAreaAtuacaoTi :input').serialize(),
        dataType: 'json',
		success: function(retorno){
			if(retorno['error'] == true ){
                alertMsg(retorno['msg'], retorno['typeMsg']);
            }else{
                alertMsg(retorno['msg'], retorno['typeMsg']);
                gridAreaAtuacao();
                limpaFormAreaAtuacao();
            }
		}
	});
}

function excluirAreaAtuacao(cd_area_atuacao_ti)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/area-atuacao-ti/excluir",
            data    : {'cd_area_atuacao_ti':cd_area_atuacao_ti},
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true ){
                    alertMsg(retorno['msg'], retorno['typeMsg']);
                }else{
                    alertMsg(retorno['msg'], retorno['typeMsg']);
                    gridAreaAtuacao();
                }
            }
        });
    });
}

function recuperarAreaAtuacao(cd_area_atuacao_ti)
{
	$.ajax({
		type    : "POST",
		url     : systemName+"/area-atuacao-ti/recuperar",
		data    : {'cd_area_atuacao_ti':cd_area_atuacao_ti},
        dataType: 'json',
		success : function(retorno){
			
            $('#cd_area_atuacao_ti'          ).val(retorno['cd_area_atuacao_ti']);
            $('#tx_area_atuacao_ti'          ).val(retorno['tx_area_atuacao_ti']);
            $('#btn_cancelar_area_atuacao_ti').show();
		}
	});
}

function limpaFormAreaAtuacao()
{
    $('#formAreaAtuacaoTi :input'    ).val('');
    $('#btn_cancelar_area_atuacao_ti').hide();
}