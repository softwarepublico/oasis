$(document).ready(function(){
	$('#cd_contrato_documentacao').change(function(){
		if($('#cd_contrato_documentacao').val() != "0"){
			montaGridDocumentacaoContrato();
		} else {
			$('#gridDocumentacaoContrato').hide();
		}
	});

	$('#cd_tipo_documentacao').change(function(){
		if($('#cd_tipo_documentacao').val() != "0"){
			extensoesDocumentoContrato();
		} else {
			$('#divExtensao').css('display','none');
		}		
	});
});

function _fnUploadDocumentacaoContrato(){
    if( validaDados() ){
        ajaxFileUpload({
            "url"           : systemName + '/documentacao-contrato/upload-file',
            "inputFile"     : $('#tx_arq_documentacao_contrato'),
            "data"          : $('#cd_contrato_documentacao').add('#cd_tipo_documentacao'),
            "fileSize"      : '209715200',
            "uploadDir"     : '/public/documentacao/contrato',
            "uploadUp"      : '/public/documentacao/contrato',
            "imgLoader"     : $("#imgLoadDocumentacaoContrato"),
            "callback"      : montaGridDocumentacaoContrato,
            "callbackError" : function(){return false;}
        });
    }else{
        return false;
    }
}

function validaDados()
{
	if($('#cd_contrato_documentacao').val() == "0"){
		$('#cd_contrato_documentacao').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_contrato_documentacao'));
		return false;
	}
	if($('#cd_tipo_documentacao').val() == ""){
		$('#cd_tipo_documentacao').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_tipo_documentacao'));
		return false;
	}
	if($('#tx_arq_documentacao_contrato').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_arq_documentacao_contrato'));
		$('#tx_arq_documentacao_contrato').focus();
		return false;
	}
	return true;
}

function montaGridDocumentacaoContrato()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documentacao-contrato/grid-documentacao-contrato",
		data	: "cd_contrato="+$("#cd_contrato_documentacao").val(),
		success	: function(retorno){
			$('#gridDocumentacaoContrato').html(retorno);
			$('#gridDocumentacaoContrato').show();
		}
	});
}

function extensoesDocumentoContrato()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documentacao-contrato/extensoes-permitidas",
		data	: "cd_tipo_documentacao="+$("#cd_tipo_documentacao").val(),
		success	: function(retorno){
			$('#extensoes').html(retorno);
			$('#divExtensao').css('display','');
		}
	});
}