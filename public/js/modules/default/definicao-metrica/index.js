$(document).ready(function(){
    limpaFormDefinicaoMetrica();
    $("#btn_salvar_definicao_metrica").click(function(){
		if( !validaForm("#definicaoMetrica") ){ return false; }
		salvarDefinicao();
	});

    $("#btn_alterar_definicao_metrica").click(function(){
		if( !validaForm("#definicaoMetrica") ){ return false; }
		alterarDefinicao();
	});

    $("#btn_cancelar_definicao_metrica").click(function(){
		limpaFormDefinicaoMetrica();
	});
});

function montaGridDefinicaoMetrica()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/definicao-metrica/grid-definicao-metrica",
		success	: function(retorno){
			$('#gridDefinicaoMetrica').html(retorno);
		}
	});
}

function salvarDefinicao()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/definicao-metrica/salvar",
		data    : $("#definicaoMetrica :input").serialize(),
        dataType: 'json',
		success : function(retorno){

			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
                limpaFormDefinicaoMetrica();
                montaGridDefinicaoMetrica();
                getComboItemMetrica(); // em item-metrica/index.js
	        }
		}
	});
}

function alterarDefinicao()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/definicao-metrica/alterar",
		data    : $("#definicaoMetrica :input").serialize(),
        dataType: 'json',
		success : function(retorno){

			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
                limpaFormDefinicaoMetrica();
                $("#btn_salvar_definicao_metrica"  ).show();
                $("#btn_alterar_definicao_metrica" ).hide();
                $("#btn_cancelar_definicao_metrica").hide();
                montaGridDefinicaoMetrica();
	        }
		}
	});
}

function recuperaDefinicao( cd_definicao )
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/definicao-metrica/recuperar",
		data    : "cd_definicao_metrica="+cd_definicao,
        dataType: 'json',
		success : function(retorno){

            $("#cd_definicao_metrica").val(retorno['cd_definicao_metrica']);
            $("#tx_nome_metrica"     ).val(retorno['tx_nome_metrica']).attr('disabled','disabled');
            $("#tx_sigla_metrica"    ).val(retorno['tx_sigla_metrica']);
            $("#tx_descricao_metrica").val(retorno['tx_descricao_metrica']);
            $("#tx_formula_metrica"  ).val(retorno['tx_formula_metrica']);
            $("#tx_sigla_unidade_metrica").val(retorno['tx_sigla_unidade_metrica']);
            $("#tx_unidade_metrica"  ).val(retorno['tx_unidade_metrica']);

            if( retorno['st_justificativa_metrica'] == 'S' ){
                $("#st_justificativa_metrica").attr('checked','checked');
            }else{
                $("#st_justificativa_metrica").removeAttr('checked');
            }

            $("#btn_salvar_definicao_metrica"  ).hide();
            $("#btn_alterar_definicao_metrica" ).show();
            $("#btn_cancelar_definicao_metrica").show();
		}
	});
}

function excluirDefinicao( cd_definicao_metrica )
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/definicao-metrica/excluir",
            data    : "cd_definicao_metrica="+cd_definicao_metrica,
            dataType: 'json',
            success : function(retorno){

                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['errorType']);
                }else{
                    alertMsg(retorno['msg']);
                    montaGridDefinicaoMetrica();
                    getComboItemMetrica(); // em item-metrica/index.js
                }
            }
        });
    });
}

function limpaFormDefinicaoMetrica()
{
    $("#definicaoMetrica :input"        ).removeAttr('disabled');

    $("#tx_nome_metrica"     			).val('');
    $("#tx_sigla_metrica"				).val('');
    $("#tx_descricao_metrica"			).val('');
    $("#tx_formula_metrica"				).val('');
    $("#tx_sigla_unidade_metrica"		).val('');
    $("#tx_unidade_metrica"				).val('');

    $("#btn_alterar_definicao_metrica"  ).hide();
    $("#btn_cancelar_definicao_metrica" ).hide();
    $("#btn_salvar_definicao_metrica"   ).show();
    $("#st_justificativa_metrica"       ).removeAttr('checked');
}