$(document).ready(function(){

    montaGridItemInventario();

	$('#btn_salvar_item_inventario').click(function(){
		salvarItemInventario();
	});
});

function salvarItemInventario()
{
    if( !validaForm('#item_inventario_form') ){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/item-inventario/salvar-item-inventario",
		data	: $('#item_inventario_form :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type']);
                limpaFormItemInventario();
			}
		}
	});
}

function limpaFormItemInventario()
{
	$('#item_inventario_form :input').val("");
    montaGridItemInventario();
}

function montaGridItemInventario()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/item-inventario/grid-item-inventario",
		success	: function(retorno){
			// atualiza a grid
			$("#gridItemInventario").html(retorno);
		}
	});
}

function excluirItemInventario(cd_item_inventario)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/item-inventario/excluir-item-inventario",
			data	: {"cd_item_inventario":cd_item_inventario},
            dataType: 'json',
            success	: function(retorno){
                if(retorno['erro'] == true){
                    alertMsg(retorno['msg'],retorno['type']);
                } else {
                    alertMsg(retorno['msg'],retorno['type']);
                    limpaFormItemInventario();
                }
            }
		});
	});
}

function recuperarItemInventario(cd_item_inventario)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/item-inventario/recuperar-item-inventario",
		data	: {"cd_item_inventario":cd_item_inventario},
		dataType: 'json',
		success	: function(retorno){
			$('#cd_item_inventario').val(retorno['cd_item_inventario']);
			$('#tx_item_inventario').val(retorno['tx_item_inventario']);
			$('#cd_area_atuacao_ti').val(retorno['cd_area_atuacao_ti']);
		}
	});
}

