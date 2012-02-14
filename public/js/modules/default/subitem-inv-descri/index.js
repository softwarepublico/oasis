
var _btnSalvar, _btnCancelar;

$(document).ready(function(){

   // _btnCancelar = $('#btn_cancelar_subitem_inv_descri');
    _btnSalvar   = $('#btn_salvar_subitem_inv_descri');

    $('#cd_area_atuacao_ti'   ).change(montaComboItemInventario);
	$('#cd_item_inventario'   ).change(montaComboSubitemInventario);
	$('#cd_subitem_inventario').change(montaGridSubitemInvDescri);
	_btnSalvar.bind('click',salvarSubitemInvDescri);
    //_btnCancelar.bind('click',limpaFormSubitemInvDescri);
});

function montaComboItemInventario(){
	$.ajax({
		type: "POST",
		url: systemName+"/subitem-inventario/monta-combo-item-inventario",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti").val()},
		success: function(retorno){
			$('#cd_item_inventario').html(retorno);
		}
	});
}

function montaComboSubitemInventario(){

	$.ajax({
		type: "POST",
		url: systemName+"/subitem-inv-descri/monta-combo-subitem-inventario",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti").val(),
               "cd_item_inventario" : $("#cd_item_inventario").val()},
		success: function(retorno){
			$('#cd_subitem_inventario').html(retorno);
		}
	});
}

function salvarSubitemInvDescri(){
    if( !validaForm('#subitem_inv_descri_form') ){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/subitem-inv-descri/salvar-subitem-inv-descri",
		data	: $('#subitem_inv_descri_form :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type']);
                limpaFormSubitemInvDescri();
			}
		}
	});
}

function limpaFormSubitemInvDescri(){
    
    $('#subitem_inv_descri_form :input')
    .not("#cd_subitem_inventario")
    .not("#cd_item_inventario")
    .not("#cd_area_atuacao_ti").val("");

    montaGridSubitemInvDescri();
}

function montaGridSubitemInvDescri(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/subitem-inv-descri/grid-subitem-inv-descri",
        data	: {"cd_item_inventario":$("#cd_item_inventario").val(),
                   "cd_subitem_inventario":$("#cd_subitem_inventario").val(),
                   "cd_subitem_inv_descri":$("#cd_subitem_inv_descri").val()},
		success	: function(retorno){
			// atualiza a grid
			$("#gridSubitemInvDescri").html(retorno);
		}
	});
}

function excluirSubitemInvDescri(cd_item_inventario, cd_subitem_inventario,cd_subitem_inv_descri){
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/subitem-inv-descri/excluir-subitem-inv-descri",
			data	: {"cd_item_inventario"   :cd_item_inventario,
                       "cd_subitem_inventario":cd_subitem_inventario,
                       "cd_subitem_inv_descri":cd_subitem_inv_descri},
            dataType: 'json',
            success	: function(retorno){
                if(retorno['erro'] == true){
                    alertMsg(retorno['msg'],retorno['type']);
                } else {
                    alertMsg(retorno['msg'],retorno['type']);
                    limpaFormSubitemInvDescri();
                }
            }
		});
	});
}

function recuperarSubitemInvDescri(cd_item_inventario, cd_subitem_inventario, cd_subitem_inv_descri){
	$.ajax({
		type	: "POST",
		url		: systemName+"/subitem-inv-descri/recuperar-subitem-inv-descri",
		data	: {"cd_item_inventario"   :cd_item_inventario,
                   "cd_subitem_inventario":cd_subitem_inventario,
                   "cd_subitem_inv_descri":cd_subitem_inv_descri},
		dataType: 'json',
		success	: function(retorno){
            $('#cd_subitem_inv_descri').val(retorno.cd_subitem_inv_descri);
			$('#cd_area_atuacao_ti'   ).val(retorno.cd_area_atuacao_ti);
			$('#cd_item_inventario'   ).val(retorno.cd_item_inventario);
			$('#cd_subitem_inventario').val(retorno.cd_subitem_inventario);
			$('#tx_subitem_inv_descri').val(retorno.tx_subitem_inv_descri);
			$('#ni_ordem'             ).val(retorno.ni_ordem);
		}
	});
}

