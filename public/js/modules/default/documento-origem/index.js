$(document).ready(function(){
	montaGridDocumentoOrigem();
	configDocumentoOrigem();
	
	// pega evento no onclick dos botões
	$("#btn_salvar_documento_origem").click(function(){
		if( !validaForm() ){ return false; }
		salvarDocumentoOrigem();
	});

	$('#btn_alterar_documento_origem').click(function(){
		if( !validaForm() ){ return false; }
		alterarDocumentoOrigem();
	});
});

function salvarDocumentoOrigem()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documento-origem/salvar",
		data	: $("form :input").serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridDocumentoOrigem();
			configDocumentoOrigem();
		}
	});
}

function alterarDocumentoOrigem()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documento-origem/alterar",
		data	: $("form :input").serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			montaGridDocumentoOrigem();
			configDocumentoOrigem();
		}
	});
}

function excluirDocumentoOrigem(cd_documento_origem)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName + "/documento-origem/excluir",
			data	: "cd_documento_origem=" + cd_documento_origem,
			success	: function(retorno){
				alertMsg(retorno);
				montaGridDocumentoOrigem();
				configDocumentoOrigem();
			}
		});
	});
}

function montaGridDocumentoOrigem()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documento-origem/grid-documento-origem",
		success	: function(retorno){
			$('#gridDocumentoOrigem').html(retorno);
		}
	});
}

function recuperaDocumentoOrigem(cd_documento_origem)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documento-origem/recupera-dados",
		data	: "cd_documento_origem="+cd_documento_origem,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_documento_origem'		 ).val(retorno['cd_documento_origem']);
			$('#tx_documento_origem'		 ).val(retorno['tx_documento_origem']);
			$('#dt_documento_origem'		 ).val(retorno['dt_documento_origem']);
			$('#tx_obs_documento_origem'	 ).val(retorno['tx_obs_documento_origem']);
			$('#btn_alterar_documento_origem').show();
			$('#btn_salvar_documento_origem' ).hide();
		}
	});
}

function configDocumentoOrigem()
{
	$('#cd_documento_origem'			).val("");
	$('#tx_documento_origem'			).val("");
	$('#dt_documento_origem'			).val("");
	$('#tx_obs_documento_origem'		).val("");
	$('#btn_alterar_documento_origem'   ).hide();
	$('#btn_salvar_documento_origem'	).show();
}