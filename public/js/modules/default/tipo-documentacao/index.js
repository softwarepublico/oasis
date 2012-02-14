$(document).ready(function(){
	
	//inicialização da tela
	limpaDadosTipoDocumentacao();
	$('#alterarTipoDocumentacao').hide();
	$('#cancelarTipoDocumentacao').hide();
	montaGridTipoDocumentacao();
	
	
	$('#salvarTipoDocumentacao').click(function(){
		validaDados('S');
	});
	$('#alterarTipoDocumentacao').click(function(){
		validaDados('A');
	});
	$('#cancelarTipoDocumentacao').click(function(){
		limpaDadosTipoDocumentacao();
	});
});

function validaDados(cond)
{
    var msg = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;

	if($('#tx_tipo_documentacao').val() == ""){
		$('#tx_tipo_documentacao').focus();
		showToolTip(msg,$('#tx_tipo_documentacao'));
		return false;		
	}
	if($('#tx_extensao_documentacao').val() == ""){
		$('#tx_extensao_documentacao').focus();
		showToolTip(msg,$('#tx_extensao_documentacao'));
		return false;		
	}
	
	if(cond == "S"){
		salvarTipoDocumentacao();
	} else {
		alterarTipoDocumentacao();
	}
}

function salvarTipoDocumentacao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-documentacao/salvar-tipo-documentacao",
		data	: $('#tipo_documentacao :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosTipoDocumentacao();
			montaGridTipoDocumentacao()
		}
	});
}

function alterarTipoDocumentacao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-documentacao/alterar-tipo-documentacao",
		data	: $('#tipo_documentacao :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosTipoDocumentacao();
			montaGridTipoDocumentacao();
		}
	});
}

function excluirTipoDocumentacao(cd_tipo_documentacao)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/tipo-documentacao/excluir-tipo-documentacao",
            data	: $('#tipo_documentacao :input').serialize(),
            success	: function(retorno){
                alertMsg(retorno);
                montaGridTipoDocumentacao();
            }
        });
    });
}

function recuperaTipoDocumentacao(cd_tipo_documentacao)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-documentacao/recupera-tipo-documentacao",
		data	: "cd_tipo_documentacao="+cd_tipo_documentacao,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_tipo_documentacao'		).val(retorno['cd_tipo_documentacao']);
			$('#tx_tipo_documentacao'		).val(retorno['tx_tipo_documentacao']);
			$('#tx_extensao_documentacao'	).val(retorno['tx_extensao_documentacao']);

			$('#st_classificacao'	        ).val(retorno['st_classificacao']);

			$('#salvarTipoDocumentacao'		).hide();
			$('#alterarTipoDocumentacao'	).show();
			$('#cancelarTipoDocumentacao'	).show();
		}
	});
}

function montaGridTipoDocumentacao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-documentacao/grid-tipo-documentacao",
		success	: function(retorno){
			$('#gridTipoDocumentacao').html(retorno);
		}
	});
}

function limpaDadosTipoDocumentacao()
{
	$('#tx_tipo_documentacao'		).val("");
	$('#tx_extensao_documentacao'	).val("");
	$('#st_classificacao'			).val('');
	$('#salvarTipoDocumentacao'		).show();
	$('#alterarTipoDocumentacao'	).hide();
	$('#cancelarTipoDocumentacao'	).hide();
}