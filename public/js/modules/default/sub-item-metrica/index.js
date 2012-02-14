$(document).ready(function(){
    
    $("#cmb_definicao_metrica_sub_item").change(function(){
        if( $(this).val() != 0 ){
            getComboItemMetricaSubItem();
        }else{
            $("#cmb_item_metrica_sub_item").html("<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>");
            $('#gridSubItemMetrica'		  ).hide();
        }
    });

    $("#cmb_item_metrica_sub_item").change(function(){
        if( $(this).val() != 0 ){
            montaGridSubItemMetrica();
        }else{
            $('#gridSubItemMetrica').hide();
        }
    });

	$("#st_interno").click(function(){
	    if($(this).attr("checked") == true ){
	        $("form#subItemMetrica .ni_ordem_sub_item_metrica_teste").removeClass('required');
	        $("#ni_ordem_sub_item_metrica"							).val('');
	    }else{
	        $("form#subItemMetrica .ni_ordem_sub_item_metrica_teste").addClass('required');
	    }
	});

    $("#btn_salvar_sub_item_metrica"  ).click(salvarSubItemMetrica);
    $("#btn_alterar_sub_item_metrica" ).click(alterarSubItemMetrica);
    $("#btn_cancelar_sub_item_metrica").click(limpaFormSubItemMetrica);
    $("#btn_cancelar_sub_item_metrica").trigger('click');
	
});

function getComboItemMetricaSubItem()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/item-metrica/get-combo-item-metrica",
        data    : {"cd_definicao_metrica":$("#cmb_definicao_metrica_sub_item").val()},
		success : function(retorno){
			$('#cmb_item_metrica_sub_item').html(retorno);
		}
	});
}

function montaGridSubItemMetrica()
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/sub-item-metrica/grid-sub-item-metrica",
        data    : {"cd_definicao_metrica":$("#cmb_definicao_metrica_sub_item").val(),
                  "cd_item_metrica"      :$('#cmb_item_metrica_sub_item').val()},
		success : function(retorno){
			$('#gridSubItemMetrica').html(retorno);
			$('#gridSubItemMetrica').show();
		}
	});
}

function salvarSubItemMetrica()
{
    if( !validaForm("#subItemMetrica") ){ return false; }
    $.ajax({
		type    : "POST",
		url     : systemName+"/sub-item-metrica/salvar",
		data    : $("#subItemMetrica :input").serialize(),
        dataType: 'json',
		success : function(retorno){
            alertMsg(retorno['msg'],retorno['errorType']);
			if(retorno['error'] == false){
                limpaFormSubItemMetrica();
                montaGridSubItemMetrica();
	        }
		}
	});
}

function recuperaSubItemMetrica( cd_sub_item_metrica, cd_item_metrica, cd_definicao_metrica )
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/sub-item-metrica/recuperar",
		data    : {"cd_sub_item_metrica" :cd_sub_item_metrica,
                   "cd_item_metrica"     :cd_item_metrica,
                   "cd_definicao_metrica":cd_definicao_metrica},
        dataType: 'json',
		success : function(retorno){

            $("#cmb_definicao_metrica_sub_item"        ).val(retorno['cd_definicao_metrica']).attr('disabled','disabled');
            $("#cmb_item_metrica_sub_item"             ).attr('disabled','disabled');

            $("#cd_sub_item_metrica"					).val(retorno['cd_sub_item_metrica']);
            $("#tx_sub_item_metrica"					).val(retorno['tx_sub_item_metrica']);
            $("#tx_variavel_sub_item_metrica"			).val(retorno['tx_variavel_sub_item_metrica']);
            $("#cd_definicao_metrica_sub_item_hidden"	).val(retorno['cd_definicao_metrica']);
            $("#cd_item_metrica_hidden"					).val(retorno['cd_item_metrica']);
            $("#ni_ordem_sub_item_metrica"				).val(retorno['ni_ordem_sub_item_metrica']);

            if( retorno['st_interno'] == 'S' ){
                $("#st_interno").attr('checked','checked');
				$("form#subItemMetrica .ni_ordem_sub_item_metrica_teste").removeClass('required');
            }else{
                $("#st_interno").removeAttr('checked');
            }

            $("#btn_salvar_sub_item_metrica"  ).hide();
            $("#btn_alterar_sub_item_metrica" ).show();
            $("#btn_cancelar_sub_item_metrica").show();
		}
	});
}

function alterarSubItemMetrica()
{
    if( !validaForm("#subItemMetrica") ){ return false; }
    $.ajax({
		type    : "POST",
		url     : systemName+"/sub-item-metrica/alterar",
		data    : $("#subItemMetrica :input").serialize(),
        dataType: 'json',
		success : function(retorno){
            alertMsg(retorno['msg'],retorno['errorType']);
			if(retorno['error'] == true){
                limpaFormSubItemMetrica();
                $("#btn_salvar_sub_item_metrica"  ).show();
                $("#btn_alterar_sub_item_metrica" ).hide();
                $("#btn_cancelar_sub_item_metrica").hide();
                montaGridSubItemMetrica();
	        }
		}
	});
}

function excluirSubItemMetrica( cd_sub_item_metrica, cd_item_metrica, cd_definicao_metrica )
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/sub-item-metrica/excluir",
            data    : {"cd_sub_item_metrica":cd_sub_item_metrica,
                      "cd_item_metrica"     :cd_item_metrica,
                      "cd_definicao_metrica":cd_definicao_metrica},
            dataType: 'json',
            success : function(retorno){
                alertMsg(retorno['msg'],retorno['errorType']);
                if(retorno['error'] == false) montaGridSubItemMetrica();
            }
        });
    });
}

function limpaFormSubItemMetrica()
{
    $("#subItemMetrica :input"         ).removeAttr('disabled');

    $("#cd_sub_item_metrica"           ).val('');
    $("#cd_item_metrica_hidden"        ).val('');
    $("#cd_definicao_metrica_sub_item_hidden"   ).val('');

    $("#tx_sub_item_metrica"           ).val('');
    $("#tx_variavel_sub_item_metrica"  ).val('');
    $("#ni_ordem_sub_item_metrica"     ).val('');
    $("#st_interno"                    ).removeAttr('checked');
    
    $("#btn_alterar_sub_item_metrica"  ).hide();
    $("#btn_cancelar_sub_item_metrica" ).hide();
    $("#btn_salvar_sub_item_metrica"   ).show();
	
	$("form#subItemMetrica .ni_ordem_sub_item_metrica_teste").addClass('required');
}