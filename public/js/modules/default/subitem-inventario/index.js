$(document).ready(function(){

    montaGridSubitemInventario();

    $('#cd_area_atuacao_ti').change(function(){
		montaComboItemInventario();
	});

	$('#cd_item_inventario').change(function(){
		montaGridSubitemInventario();
	});
    
	$('#btn_salvar_subitem_inventario').click(function(){
		salvarSubitemInventario();
	});
});

function montaComboItemInventario()
{
	$.ajax({
		type: "POST",
		url: systemName+"/subitem-inventario/monta-combo-item-inventario",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti").val()},
		success: function(retorno){
			$('#cd_item_inventario').html(retorno);
		}
	});
}

function salvarSubitemInventario()
{
    if( !validaForm('#subitem_inventario_form') ){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/subitem-inventario/salvar-subitem-inventario",
		data	: $('#subitem_inventario_form :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type']);
                limpaFormSubitemInventario();
			}
		}
	});
}

function limpaFormSubitemInventario()
{
	$('#subitem_inventario_form :input')
    .not("#cd_item_inventario")
    .not("#cd_area_atuacao_ti").val("");
    
    $('#st_info_chamado_tecnico'	).removeAttr('checked');

    montaGridSubitemInventario();
}

function montaGridSubitemInventario()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/subitem-inventario/grid-subitem-inventario",
        data	: {"cd_item_inventario":$("#cd_item_inventario").val()},
		success	: function(retorno){
			// atualiza a grid
			$("#gridSubitemInventario").html(retorno);
		}
	});
}

function excluirSubitemInventario(cd_item_inventario, cd_subitem_inventario)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/subitem-inventario/excluir-subitem-inventario",
			data	: {"cd_item_inventario":cd_item_inventario,
                       "cd_subitem_inventario":cd_subitem_inventario},
            dataType: 'json',
            success	: function(retorno){
                if(retorno['erro'] == true){
                    alertMsg(retorno['msg'],retorno['type']);
                } else {
                    alertMsg(retorno['msg'],retorno['type']);
                    limpaFormSubitemInventario();
                }
            }
		});
	});
}

function recuperarSubitemInventario(cd_item_inventario, cd_subitem_inventario)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/subitem-inventario/recuperar-subitem-inventario",
		data	: {"cd_item_inventario":cd_item_inventario,
                   "cd_subitem_inventario":cd_subitem_inventario},
		dataType: 'json',
		success	: function(retorno){
			$('#cd_area_atuacao_ti'          ).val(retorno['cd_area_atuacao_ti']);
			$('#cd_item_inventario_hidden'   ).val(retorno['cd_item_inventario']);
			$('#cd_item_inventario'          ).val(retorno['cd_item_inventario']);
			$('#cd_subitem_inventario'       ).val(retorno['cd_subitem_inventario']);
			$('#tx_subitem_inventario'       ).val(retorno['tx_subitem_inventario']);
            
            if ( retorno['st_info_chamado_tecnico'] == 'S') {
    			$('#st_info_chamado_tecnico').attr('checked','checked');
            }else{
    			$('#st_info_chamado_tecnico').removeAttr('checked');
            }
		}
	});
}

