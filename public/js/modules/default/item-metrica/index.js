$(document).ready(function(){

    $("#cmb_definicao_metrica_item").change(function(){
        if( $(this).val() != 0 ){
            montaGridItemMetrica();
        }else{
            $('#gridItemMetrica').hide();
        }
    });

    $("#btn_salvar_item_metrica"  ).click(salvarItemMetrica);
    $("#btn_alterar_item_metrica" ).click(alterarItemMetrica);
    $("#btn_cancelar_item_metrica").click(limpaFormItemMetrica);
    $("#btn_cancelar_item_metrica").trigger('click');
});

function montaGridItemMetrica()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/item-metrica/grid-item-metrica",
        data    : "cd_definicao_metrica="+$("#cmb_definicao_metrica_item").val(),
		success : function(retorno){
			$('#gridItemMetrica').html(retorno);
			$('#gridItemMetrica').show();
		}
	});
}

function getComboItemMetrica()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/definicao-metrica/get-combo-definicao-metrica",
		success : function(retorno){
			$('#cmb_definicao_metrica_item'             ).html(retorno);
			$('#cmb_definicao_metrica_sub_item'         ).html(retorno);
			$('#cmb_definicao_metrica_condicao_sub_item').html(retorno);
			$('#cd_definicao_metrica_tipo_produto'      ).html(retorno);
		}
	});
}

function salvarItemMetrica()
{
    if( !validaForm("#itemMetrica") ){ return false; }
    $.ajax({
		type    : "POST",
		url     : systemName+"/item-metrica/salvar",
		data    : $("#itemMetrica :input").not('#cd_item_metrica').not('#cd_definicao_metrica_hidden').serialize(),
        dataType: 'json',
		success : function(retorno){
            alertMsg(retorno['msg'],retorno['errorType']);
			if(retorno['error'] == false){
                limpaFormItemMetrica();
                montaGridItemMetrica();
	        }
		}
	});
}

function recuperaItemMetrica( cd_item_metrica, cd_definicao_metrica )
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/item-metrica/recuperar",
		data    : "cd_item_metrica="+cd_item_metrica +"&cd_definicao_metrica="+cd_definicao_metrica,
        dataType: 'json',
		success : function(retorno){

            $("#cd_item_metrica"            ).val(retorno['cd_item_metrica']);
            $("#cmb_definicao_metrica_item" ).val(retorno['cd_definicao_metrica']).attr('disabled','disabled');
            $("#tx_item_metrica"            ).val(retorno['tx_item_metrica']);
            $("#tx_formula_item_metrica"    ).val(retorno['tx_formula_item_metrica']);
            $("#tx_variavel_item_metrica"   ).val(retorno['tx_variavel_item_metrica']);
            $("#ni_ordem_item_metrica"      ).val(retorno['ni_ordem_item_metrica']);
            $("#cd_definicao_metrica_hidden").val(retorno['cd_definicao_metrica']);

            $("#btn_salvar_item_metrica"  ).hide();
            $("#btn_alterar_item_metrica" ).show();
            $("#btn_cancelar_item_metrica").show();
		}
	});
}

function alterarItemMetrica()
{
    if( !validaForm("#itemMetrica") ){ return false; }
    $.ajax({
		type    : "POST",
		url     : systemName+"/item-metrica/alterar",
		data    : $("#itemMetrica :input").serialize(),
        dataType: 'json',
		success : function(retorno){
            alertMsg(retorno['msg'],retorno['errorType']);
			if(retorno['error'] == false){
                limpaFormItemMetrica();
                $("#btn_salvar_item_metrica"  ).show();
                $("#btn_alterar_item_metrica" ).hide();
                $("#btn_cancelar_item_metrica").hide();
                montaGridItemMetrica();
	        }
		}
	});
}

function excluirItemMetrica( cd_item_metrica, cd_definicao_metrica )
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/item-metrica/excluir",
            data    : {"cd_item_metrica":cd_item_metrica, "cd_definicao_metrica":cd_definicao_metrica},
            dataType: 'json',
            success : function(retorno){
                alertMsg(retorno['msg'],retorno['errorType']);
                if(retorno['error'] == false) montaGridItemMetrica();
            }
        });
    });
}

function limpaFormItemMetrica()
{
    $("#itemMetrica :input"         ).removeAttr('disabled');

    $("#cd_item_metrica"            ).val('');
    $("#cd_definicao_metrica_hidden").val('');

    $("#tx_item_metrica"         ).val('');
    $("#tx_variavel_item_metrica").val('');
    $("#tx_formula_item_metrica" ).val('');
    $("#ni_ordem_item_metrica"   ).val('');

    $("#btn_alterar_item_metrica"  ).hide();
    $("#btn_cancelar_item_metrica" ).hide();
    $("#btn_salvar_item_metrica"   ).show();
}