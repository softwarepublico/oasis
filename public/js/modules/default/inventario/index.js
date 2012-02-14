$(document).ready(function() {
	$('#container-inventario').show().tabs().triggerTab(1);
    montaGridInventario();

	$('#cmd_area_atuacao_ti_inventario').change(montaGridInventario);
    
	$('#btn_novo_inventario').click(function(){
		limpaCamposInventario();
		habilitaAbaNovoInventario();
	});

	$('#btn_cancelar_inventario').click(function(){
		desabilitaAbaNovoInventario();
		limpaCamposInventario();
	});
    
	$('#btn_salvar_inventario').click(function(){
		if( !validaForm('#div_novo_inventario') ){return false;}
		salvarInventario();
	});

    $('#cd_area_atuacao_ti_form_inventario').change(function(){
		montaComboNomeInventario();
		montaComboItemInventario();
	});

    $('#cd_item_inventario_form_inventario').change(function(){
		montaComboItemInventariado();
		montaComboSubitemInventario();
	});

    $('#cd_item_inventariado_form_inventario').change(gridFormInventario);
    $('#cd_subitem_inventario_form_inventario').change(gridFormInventario);

	$('#btn_cancelar_item_inventariado').click(function(){
		$('#tx_item_inventariado_item_inventariado').add('#cd_item_inventariado').val('');
	});

	$('#btn_salvar_item_inventariado').click(function(){
		if( !validaForm('#div_item_inventariado') ){return false;}
		salvarItemInventariado();
	});

    $('#cd_area_atuacao_ti_item_inventariado').change(function(){
		montaComboItemInventario2();
		montaComboNomeInventario2();
	});

    $('#cd_item_inventario_item_inventariado').change(montaGridItemInventariado);
});

function salvarItemInventariado(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/salvar-item-inventariado",
		data	: $("#div_item_inventariado :input").serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['typeMsg']);
			}else{
				montaGridItemInventariado();
				$('#tx_item_inventariado_item_inventariado').add('#cd_item_inventariado').val('');
			}
		}
	});
}

function montaGridItemInventariado(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/grid-item-inventariado",
		data	: {"cd_inventario"       : $("#cd_inventario_item_inventariado").val(),
				   "cd_item_inventario"  : $("#cd_item_inventario_item_inventariado").val()},
		success	: function(retorno){
			$('#gridItemInventariado').html(retorno);
            $('#gridItemInventariado').show('slow');
		}
	});
}

function limpaCamposItemInventariado(){
	$('#div_item_inventariado :input').val('').removeAttr('disabled');
//	$('#tx_inventariado'	 ).wysiwyg('value','');
}

function excluirItemInventariado(cd_item_inventariado){
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/inventario/excluir-item-inventariado",
			data	: "cd_item_inventariado="+ cd_item_inventariado
				 +"&cd_inventario="+$("#cd_inventario_item_inventariado").val()
				 +"&cd_item_inventario="+$("#cd_item_inventario_item_inventariado").val(),
			dataType: 'json',
			success	: function(retorno){
				if(retorno['error'] == true){
					alertMsg(retorno['msg'],retorno['typeMsg']);
				}else{
					alertMsg(retorno['msg'],retorno['typeMsg']);
					montaGridItemInventariado();
				}
			}
		});
	});
}

function recuperarItemInventariado(cd_item_inventariado){
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/recuperar-item-inventariado",
		data	: {
                    "cd_item_inventariado" : cd_item_inventariado,
                    "cd_inventario"        : $("#cd_inventario_item_inventariado").val(),
                    "cd_item_inventario"   : $("#cd_item_inventario_item_inventariado").val()},
		dataType: 'json',
		success	: function(retorno){
			$('#cd_item_inventariado'                  ).val(retorno['cd_item_inventariado']);
			$('#tx_item_inventariado_item_inventariado').val(retorno['tx_item_inventariado']);
		}
	});
}



function montaComboItemInventariado(){
	$.ajax({
		type: "POST",
		url: systemName+"/inventario/monta-combo-item-inventariado",
		data: "cd_inventario="+$("#cd_inventario_form_inventario").val()
				 +"&cd_item_inventario="+$("#cd_item_inventario_form_inventario").val(),
		success: function(retorno){
			$('#cd_item_inventariado_form_inventario').html(retorno);
		}
	});
}

function montaComboSubitemInventario(){
	$.ajax({
		type: "POST",
		url: systemName+"/inventario/monta-combo-subitem-inventario",
		data: {"cd_inventario"        :$("#cd_inventario_form_inventario").val(),
			   "cd_item_inventario"   :$("#cd_item_inventario_form_inventario").val()},
		success: function(retorno){
			$('#cd_subitem_inventario_form_inventario').html(retorno);
		}
	});
}

function gridItemInventariado(){
	if($('#cd_inventario_item_inventariado').val() == 0){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_inventario_item_inventariado'));
		return false;
	}
	if($('#cd_item_inventario_item_inventariado').val() == 0){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_item_inventario_item_inventariado'));
		return false;
	}
    
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/grid-item-inventariado",
		data	: "cd_area_atuacao_ti="+$("#cd_area_atuacao_ti_item_inventariado").val()
				 +"&cd_inventario="+$("#cd_inventario_item_inventariado").val()
				 +"&cd_item_inventario="+$("#cd_item_inventario_item_inventariado").val(),
		success: function(retorno){
			$('#gridItemInventariado').html(retorno);
			$('#gridItemInventariado').show();
		}
	});
   
	return true;
}

function salvaItemInventariado(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/salva-dados-item-inventariado",
		data	: $('#itemInventariado :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
		}
	});

}

function montaGridInventario(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/grid-inventario",
		data	: "cd_area_atuacao_ti="+$('#cmd_area_atuacao_ti_inventario').val(),
		success	: function(retorno){
			$('#grid_inventario').html(retorno);
		}
	});
}

function salvarInventario(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/salvar-inventario",
		data	: $("#div_novo_inventario :input").serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['typeMsg']);
			}else{
				alertMsg(retorno['msg'],retorno['typeMsg'],'desabilitaAbaNovoInventario()');
				montaGridInventario();
				limpaCamposInventario();
			}
		}
	});
}

function limpaCamposInventario(){
	$('#div_novo_inventario :input').val('').removeAttr('disabled');
	$('#tx_desc_inventario'	 ).wysiwyg('value','');
	$('#tx_obs_inventario'	 ).wysiwyg('value','');
}

function excluiInventario(cd_inventario){
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/inventario/excluir",
			data	: {'cd_inventario': cd_inventario},
			dataType: 'json',
			success	: function(retorno){
				if(retorno['error'] == true){
					alertMsg(retorno['msg'],retorno['typeMsg']);
				}else{
					alertMsg(retorno['msg'],retorno['typeMsg']);
					montaGridInventario();
				}
			}
		});
	});
}

function recuperaDadosInventario(cd_inventario)
{

	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/recupera-dados-inventario",
		data	: {'cd_inventario': cd_inventario},
		dataType: 'json',
		success	: function(retorno){
			$('#cd_inventario'				  ).val(retorno['cd_inventario']);
			$('#cd_area_atuacao_ti_inventario').val(retorno['cd_area_atuacao_ti']);
			$('#tx_inventario'                ).val(retorno['tx_inventario']);
			$('#tx_desc_inventario'		      ).wysiwyg('value',retorno['tx_desc_inventario']);
			$('#tx_obs_inventario'		      ).wysiwyg('value',retorno['tx_obs_inventario']);

			habilitaAbaNovoInventario();
		}
	});
}

function habilitaAbaNovoInventario(){

	$('#li_novo_inventario'  ).show();
	$('#container-inventario').triggerTab(2);
}

function desabilitaAbaNovoInventario(){

	$('#li_novo_inventario'  ).hide();
	$('#container-inventario').triggerTab(1);
}

function montaComboItemInventario()
{
	$.ajax({
		type: "POST",
		url: systemName+"/inventario/monta-combo-item-inventario",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti_form_inventario").val()},
		success: function(retorno){
			$('#cd_item_inventario_form_inventario').html(retorno);
		}
	});
}

function montaComboItemInventario2()
{
	$.ajax({
		type: "POST",
		url: systemName+"/inventario/monta-combo-item-inventario",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti_item_inventariado").val()},
		success: function(retorno){
			$('#cd_item_inventario_item_inventariado').html(retorno);
		}
	});
}

function montaComboNomeInventario()
{
	$.ajax({
		type: "POST",
		url: systemName+"/inventario/monta-combo-nome-inventario",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti_form_inventario").val()},
		success: function(retorno){
			$('#cd_inventario_form_inventario').html(retorno);
		}
	});
}

function montaComboNomeInventario2()
{
	$.ajax({
		type: "POST",
		url: systemName+"/inventario/monta-combo-nome-inventario",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti_item_inventariado").val()},
		success: function(retorno){
			$('#cd_inventario_item_inventariado').html(retorno);
		}
	});
}

function gridFormInventario()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/grid-form-inventario",
		data	: {"cd_area_atuacao_ti"   : $("#cd_area_atuacao_ti_form_inventario").val(),
				   "cd_inventario"        : $("#cd_inventario_form_inventario").val(),
				   "cd_item_inventario"   : $("#cd_item_inventario_form_inventario").val(),
				   "cd_subitem_inventario": $("#cd_subitem_inventario_form_inventario").val(),
				   "cd_item_inventariado" : $("#cd_item_inventariado_form_inventario").val()},
		success: function(retorno){
			$('#gridFormInventario').html(retorno);
			$('#gridFormInventario').show();
		}
	});
	return true;
}

function salvaFormInventario()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/inventario/salva-dados-form-inventario",
		data	: $('#formInventario :input').serialize(),
        dataType: 'json',
		success	: function(retorno){
            alertMsg(retorno['msg'],retorno['typeMsg']);
		}
	});
}
