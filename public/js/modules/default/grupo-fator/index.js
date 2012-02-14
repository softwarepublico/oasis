$(document).ready(function(){

	//inicialização da tela
	$('#alterarGrupoFator').hide();
	$('#cancelarGrupoFator').hide();
	montaGridGrupoFator();
	limpaDadosGrupoFator();
	
	$('#salvarGrupoFator').click(function(){
		validaDadosGrupofator("S");
	});
	$('#alterarGrupoFator').click(function(){
		validaDadosGrupofator("A");
	});
	$('#cancelarGrupoFator').click(function(){
		limpaDadosGrupoFator();
	});
});

function validaDadosGrupofator(cond)
{
	if($('#tx_grupo_fator').val() == ""){
		$('#tx_grupo_fator').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_grupo_fator'));
		return false;
	}
	if($('#ni_peso_grupo_fator').val() == ""){
		$('#ni_peso_grupo_fator').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#ni_peso_grupo_fator'));
		return false;
	}
	if($('#ni_ordem_grupo_fator').val() == ""){
		$('#ni_ordem_grupo_fator').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#ni_ordem_grupo_fator'));
		return false;
	}
	if(cond == "S"){
		salvarGrupoFator();
	} else {
		alterarGrupoFator();
	}
}

function salvarGrupoFator()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator/salvar-grupo-fator",
		data	: $('#grupo_fator :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosGrupoFator();
			montaGridGrupoFator();
			montaComboGrupoFator();
		}
	});
}

function alterarGrupoFator()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator/alterar-grupo-fator",
		data	: $('#grupo_fator :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosGrupoFator();
			montaGridGrupoFator();
			montaComboGrupoFator();
		}
	});
}

function montaGridGrupoFator()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator/grid-grupo-fator",
		success	: function(retorno){
			// atualiza a grid
			$("#gridGrupoFator").html(retorno);
		}
	});
}

function excluirGrupoFator(cd_grupo_fator)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/grupo-fator/excluir-grupo-fator",
			data	: "cd_grupo_fator="+cd_grupo_fator,
			success	: function(retorno){
				alertMsg(retorno);
				montaGridGrupoFator();
				montaComboGrupoFator();
			}
		});
	});
}

function recuperaGrupoFator(cd_grupo_fator)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/grupo-fator/recupera-grupo-fator",
		data	: "cd_grupo_fator="+cd_grupo_fator,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_grupo_fator'			).val(retorno['cd_grupo_fator']);
			$('#tx_grupo_fator'			).val(retorno['tx_grupo_fator']);
			$('#ni_peso_grupo_fator'	).val(retorno['ni_peso_grupo_fator']);
			$('#ni_ordem_grupo_fator'	).val(retorno['ni_ordem_grupo_fator']);
			$('#ni_indice_grupo_fator'	).val(retorno['ni_indice_grupo_fator']);
			$('#salvarGrupoFator'		).hide();
			$('#alterarGrupoFator'		).show();
			$('#cancelarGrupoFator'		).show();
		}
	});
}

function limpaDadosGrupoFator()
{
	$('#salvarGrupoFator'		).show();
	$('#alterarGrupoFator'		).hide();
	$('#cancelarGrupoFator'		).hide();
	$('#tx_grupo_fator'			).val("");
	$('#ni_peso_grupo_fator'	).val("");
	$('#ni_ordem_grupo_fator'	).val("");
	$('#ni_indice_grupo_fator'	).val("");
}