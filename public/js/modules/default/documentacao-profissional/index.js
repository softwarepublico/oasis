$(document).ready(function(){
	$('#cd_profissional_documentacao').change(function(){
		if($('#cd_profissional_documentacao').val() != "0"){
			montaGridDocumentacaoProfissional();			
		} else {
			$('#gridDocumentacaoProfissional').hide();
		}
	});

	$('#cd_tipo_documentacao').change(function(){
		if($('#cd_tipo_documentacao').val() != "0"){
			extensoesDocumentoProfissional();
		} else {
			$('#divExtensao').css('display','none');
		}		
	});
});

function _fnUploadDocumentacaoProfissional(){
    if( validaDados() ){
        ajaxFileUpload({
            "url"           : systemName + '/documentacao-profissional/upload-file',
            "inputFile"     : $('#tx_arq_documentacao_profissional'),
            "data"          : $('#cd_profissional_documentacao').add('#cd_tipo_documentacao'),
            "fileSize"      : '10485760', //10MB
            "uploadDir"     : '/public/documentacao/profissional',
            "uploadUp"      : '/public/documentacao/profissional',
            "imgLoader"     : $("#imgLoadDocumentacaoProfissional"),
            "callback"      : montaGridDocumentacaoProfissional,
            "callbackError" : function(){return false;}
        });
    }else{
        return false;
    }
}

function validaDados()
{
	if($('#cd_profissional_documentacao').val() == "0"){
		$('#cd_profissional_documentacao').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_profissional_documentacao'));
		return false;
	}
	if($('#cd_tipo_documentacao').val() == ""){
		$('#cd_tipo_documentacao').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_tipo_documentacao'));
		return false;
	}
	if($('#tx_arq_documentacao_projeto').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_arq_documentacao_projeto'));
		$('#tx_arq_documentacao_projeto').focus();
		return false;
	}
	return true;
}

function montaGridDocumentacaoProfissional()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documentacao-profissional/grid-documentacao-profissional",
		data	: "cd_profissional="+$("#cd_profissional_documentacao").val(),
		success	: function(retorno){
			$('#gridDocumentacaoProfissional').html(retorno);
			$('#gridDocumentacaoProfissional').show();
		}
	});
}

function extensoesDocumentoProfissional()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documentacao-profissional/extensoes-permitidas",
		data	: "cd_tipo_documentacao="+$("#cd_tipo_documentacao").val(),
		success	: function(retorno){
			$('#extensoes').html(retorno);
			$('#divExtensao').css('display','');
		}
	});
}
