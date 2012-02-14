$(document).ready(function(){
	$('#cd_tipo_documentacao').change(function(){
		if($('#cd_tipo_documentacao').val() != "0"){
			extensoesDocumentoProposta();
		} else {
			$('#divExtensao').hide();
		}
	});
});

function verificaStatusAccordionArquivosProposta(){
	if( $("#config_hidden_arquivos_proposta").val() === "N" ){
		montaGridDocumentacaoProjeto();
	}
}

function _fnUploadDocumentacaoProjeto(){
    if( validaDados() ){
        ajaxFileUpload({
            "url"           : systemName + '/documentacao-projeto/upload-file',
            "inputFile"     : $('#tx_arq_documentacao_projeto'),
            "data"          : $('#cd_projeto').add('#cd_proposta').add('#cd_tipo_documentacao'),
            "fileSize"      : '1048576',
            "uploadDir"     : '/public/documentacao/projeto',
            "imgLoader"     : $("#imgLoad"),
            "callback"      : montaGridDocumentacaoProjeto,
            "callbackError" : function(){return false;}
        });
    }else{
        return false;
    }
}

function validaDados(){
	if($('#cd_tipo_documentacao').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_tipo_documentacao'));
		$('#cd_tipo_documentacao').focus();
		return false;
	}else if($('#tx_arq_documentacao_projeto').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_arq_documentacao_projeto'));
		$('#tx_arq_documentacao_projeto').focus();
		return false;
	}
	return true;
}

function montaGridDocumentacaoProjeto(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/documentacao-projeto/grid-documentacao",
		data	: "cd_projeto="+$("#cd_projeto").val(),
		success	: function(retorno){
			$('#gridDocumentacaoProjeto').html(retorno);
			$("#config_hidden_arquivos_proposta").val("S");
		}
	});
}

function extensoesDocumentoProposta(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/documentacao-projeto/extensoes-permitidas",
		data	: "cd_tipo_documentacao="+$("#cd_tipo_documentacao").val(),
		success	: function(retorno){
			$('#extensoes').html(retorno);
			$('#divExtensao').show();
		}
	});
}