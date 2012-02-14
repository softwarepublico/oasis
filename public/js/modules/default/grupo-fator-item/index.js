$(document).ready(function(){

	//inicialização da tela
	$('#alterarGrupoFatorItem').hide();
	$('#cancelarGrupoFatorItem').hide();
	$('#cd_grupo_fator_combo').change(function(){
		if($('#cd_grupo_fator_combo').val() != "0"){
			montaGridGrupoFatorItem();
		} else {
			$('#gridGrupoFatorItem').hide();
		}
	});
	
	montaComboGrupoFator(); 
	limpaDadosGrupoFatorItem();
	
	$('#salvarGrupoFatorItem').click(function(){
		validaDadosGrupofatorItem("S");
	});
	$('#alterarGrupoFatorItem').click(function(){
		validaDadosGrupofatorItem("A");
	});
	$('#cancelarGrupoFatorItem').click(function(){
		limpaDadosGrupoFatorItem();
	});
	
});

function validaDadosGrupofatorItem(cond)
{
	if($('#cd_grupo_fator_combo').val() == "0"){
		$('#cd_grupo_fator_combo').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_grupo_fator_combo'));
		return false;
	}
	if($('#tx_item_grupo_fator').val() == ""){
		$('#tx_item_grupo_fator').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_item_grupo_fator'));
		return false;
	}
	if($('#ni_ordem_item_grupo_fator').val() == ""){
		$('#ni_ordem_item_grupo_fator').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#ni_ordem_item_grupo_fator'));
		return false;
	}
	if(cond == "S"){
		salvarGrupoFatorItem();
	} else {
		alterarGrupoFatorItem();
	}
}

function salvarGrupoFatorItem()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator-item/salvar-grupo-fator-item",
		data	: $('#grupo_fator_item :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosGrupoFatorItem();
			montaGridGrupoFatorItem();
		}
	});
}

function alterarGrupoFatorItem()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator-item/alterar-grupo-fator-item",
		data	: $('#grupo_fator_item :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosGrupoFatorItem();
			montaGridGrupoFatorItem();
		}
	});
}

function montaGridGrupoFatorItem()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator-item/grid-grupo-fator-item",
		data	: "cd_grupo_fator="+$('#cd_grupo_fator_combo').val(),
		success	: function(retorno){
			$("#gridGrupoFatorItem").html(retorno);
			$('#gridGrupoFatorItem').show();
		}
	});
}

function excluirGrupoFatorItem(cd_item_grupo_fator)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/grupo-fator-item/excluir-grupo-fator-item",
			data	: "cd_item_grupo_fator="+cd_item_grupo_fator,
			success	: function(retorno){
				alertMsg(retorno);
				montaGridGrupoFatorItem();
			}
		});
	});
}

function recuperaGrupoFatorItem(cd_item_grupo_fator)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator-item/recupera-grupo-fator-item",
		data	: "cd_item_grupo_fator="+cd_item_grupo_fator,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_item_grupo_fator').val(retorno['cd_item_grupo_fator']);
			$('#cd_grupo_fator_combo').val(retorno['cd_grupo_fator']);
			$('#tx_item_grupo_fator').val(retorno['tx_item_grupo_fator']);
			$('#ni_ordem_item_grupo_fator').val(retorno['ni_ordem_item_grupo_fator']);
			$('#salvarGrupoFatorItem').hide();
			$('#alterarGrupoFatorItem').show();
			$('#cancelarGrupoFatorItem').show();
		}
	});
}

function limpaDadosGrupoFatorItem()
{
	$('#salvarGrupoFatorItem'	).show();
	$('#alterarGrupoFatorItem'	).hide();
	$('#cancelarGrupoFatorItem'	).hide();
	$('#tx_item_grupo_fator'	).val("");
	$('#ni_ordem_item_grupo_fator').val("");
}

function montaComboGrupoFator()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator-item/monta-combo-grupo-fator",
		success	: function(retorno){
			$('#cd_grupo_fator_combo').html(retorno);
		}
	});
}