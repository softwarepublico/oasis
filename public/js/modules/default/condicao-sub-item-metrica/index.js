$(document).ready(function(){

	var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";
    //adicionando as funcionalidades dos botões
    $("#btn_salvar_condicao_sub_item_metrica").click(function(){
        if( !validaForm("#formCondicaoSubItemMetrica") ){ return false; }
		salvarCondicaoSubItemMetrica();
    });
    $("#btn_alterar_condicao_sub_item_metrica").click(function(){
		if( !validaForm("#formCondicaoSubItemMetrica") ){ return false; }
        alterarCondicaoSubItemMetrica();
    });
    $("#btn_cancelar_condicao_sub_item_metrica").click(function(){
        limpaFormCondicaoSubItem();
    });

    $("#cmb_definicao_metrica_condicao_sub_item").change(function(){
        if( $(this).val() != 0 ){
            getComboItemMetricaCondicaoSubItem();
        }else{
            $("#cmb_item_metrica_condicao_sub_item"		).html(strOption);
            $("#cmb_sub_item_metrica_condicao_sub_item"	).html(strOption);
            $('#gridCondicaoSubItemMetrica'				).hide();
        }
    });

    $("#cmb_item_metrica_condicao_sub_item").change(function(){
        if( $(this).val() != 0 ){
            getComboSubItemMetrica();
        }else{
            $("#cmb_sub_item_metrica_condicao_sub_item"	).html(strOption);
            $('#gridCondicaoSubItemMetrica'				).hide();
        }
    });

    $("#cmb_sub_item_metrica_condicao_sub_item").change(function(){
        if( $(this).val() != 0 ){
            montaGridCondicaoSubItemMetrica();
        }else{
            $('#gridCondicaoSubItemMetrica').hide();
        }
    });
});

function getComboItemMetricaCondicaoSubItem()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/item-metrica/get-combo-item-metrica",
        data    : "cd_definicao_metrica="+$("#cmb_definicao_metrica_condicao_sub_item").val(),
		success : function(retorno){
			$('#cmb_item_metrica_condicao_sub_item').html(retorno);
		}
	});
}

function getComboSubItemMetrica()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/sub-item-metrica/get-combo-sub-item-metrica",
        data    : "cd_definicao_metrica="+$("#cmb_definicao_metrica_condicao_sub_item").val()+
                  "&cd_item_metrica="    +$("#cmb_item_metrica_condicao_sub_item"     ).val(),
		success : function(retorno){
			$('#cmb_sub_item_metrica_condicao_sub_item').html(retorno);
		}
	});
}

function montaGridCondicaoSubItemMetrica()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/condicao-sub-item-metrica/grid-condicao-sub-item-metrica",
        data    : "cd_definicao_metrica=" +$("#cmb_definicao_metrica_condicao_sub_item").val()+
                  "&cd_item_metrica="     +$('#cmb_item_metrica_condicao_sub_item'     ).val()+
                  "&cd_sub_item_metrica=" +$('#cmb_sub_item_metrica_condicao_sub_item' ).val(),
		success : function(retorno){
			$('#gridCondicaoSubItemMetrica').html(retorno);
			$('#gridCondicaoSubItemMetrica').show();
		}
	});
}

function salvarCondicaoSubItemMetrica()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/condicao-sub-item-metrica/salvar",
		data    : $("#formCondicaoSubItemMetrica :input").serialize(),
        dataType: 'json',
		success : function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
                montaGridCondicaoSubItemMetrica();
                limpaFormCondicaoSubItem();
	        }
		}
	});
}

function recuperaCondicaoSubItemMetrica( cd_condicao_sub_item_metrica, cd_sub_item_metrica, cd_item_metrica, cd_definicao_metrica )
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/condicao-sub-item-metrica/recuperar",
		data    : "cd_condicao_sub_item_metrica="+cd_condicao_sub_item_metrica+
                  "&cd_sub_item_metrica="        +cd_sub_item_metrica+
                  "&cd_item_metrica="            +cd_item_metrica +
                  "&cd_definicao_metrica="       +cd_definicao_metrica,
        dataType: 'json',
		success : function(retorno){

            $("#cd_condicao_sub_item_metrica"				   ).val(retorno['cd_condicao_sub_item_metrica']);
            $("#cd_sub_item_metrica_condicao_sub_item_hidden"  ).val(retorno['cd_sub_item_metrica'         ]);
            $("#cd_item_metrica_condicao_sub_item_hidden"      ).val(retorno['cd_item_metrica'             ]);
            $("#cd_definicao_metrica_condicao_sub_item_hidden" ).val(retorno['cd_definicao_metrica'        ]);

            $("#cmb_definicao_metrica_condicao_sub_item"       ).attr('disabled','disabled');
            $("#cmb_item_metrica_condicao_sub_item"            ).attr('disabled','disabled');
            $("#cmb_sub_item_metrica_condicao_sub_item"        ).attr('disabled','disabled');

            $("#tx_condicao_sub_item_metrica").val(retorno['tx_condicao_sub_item_metrica']);
            $("#ni_valor_condicao_satisfeita").val(retorno['ni_valor_condicao_satisfeita']);

            $("#btn_salvar_condicao_sub_item_metrica"  ).hide();
            $("#btn_alterar_condicao_sub_item_metrica" ).show();
            $("#btn_cancelar_condicao_sub_item_metrica").show();
		}
	});
}

function alterarCondicaoSubItemMetrica()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/condicao-sub-item-metrica/alterar",
		data    : $("#formCondicaoSubItemMetrica :input").serialize(),
        dataType: 'json',
		success : function(retorno){

			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
	            alertMsg(retorno['msg']);
                $("#btn_salvar_condicao_sub_item_metrica"  ).show();
                $("#btn_alterar_condicao_sub_item_metrica" ).hide();
                $("#btn_cancelar_condicao_sub_item_metrica").hide();
                montaGridCondicaoSubItemMetrica();
                limpaFormCondicaoSubItem();
	        }
		}
	});
}

function excluirCondicaoSubItemMetrica( cd_condicao_sub_item_metrica, cd_sub_item_metrica, cd_item_metrica, cd_definicao_metrica )
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/condicao-sub-item-metrica/excluir",
            data    : "cd_sub_item_metrica="          +cd_sub_item_metrica+
                      "&cd_item_metrica="             +cd_item_metrica+
                      "&cd_definicao_metrica="        +cd_definicao_metrica+
                      "&cd_condicao_sub_item_metrica="+cd_condicao_sub_item_metrica,
            dataType: 'json',
            success : function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['errorType']);
                }else{
                    alertMsg(retorno['msg']);
                    montaGridCondicaoSubItemMetrica();
                }
            }
        });
    });
}

function limpaFormCondicaoSubItem()
{
    $("#formCondicaoSubItemMetrica :input"  		  ).removeAttr('disabled');
    $("#cd_condicao_sub_item_metrica"       		  ).val('');
    $("#cd_definicao_metrica_condicao_sub_item_hidden").val('');
    $("#cd_item_metrica_condicao_sub_item_hidden"     ).val('');
    $("#cd_sub_item_metrica_condicao_sub_item_hidden" ).val('');

    $("#tx_condicao_sub_item_metrica").val('');
    $("#ni_valor_condicao_satisfeita").val('');

    $("#btn_alterar_condicao_sub_item_metrica"  ).hide();
    $("#btn_cancelar_condicao_sub_item_metrica" ).hide();
    $("#btn_salvar_condicao_sub_item_metrica"   ).show();
}







