$(document).ready(function(){
    $("#cd_objeto_projeto_continuo_doc").change(function() {
		if($(this).val() != '-1' ){
			// Pesquisa projeto continuado (combo)
			montaComboProjetoContinuadoModulo();
		}else{
			$("#cd_projeto_continuo_doc").empty();
		}
	});
    
    $('#cd_projeto_continuo_doc').change(function(){
		if($('#cd_projeto_continuo_doc').val() != "0"){
			montaGridDocumentacaoProjetoContinuo();
		} else {
			$('#gridDocumentacaoProjetoContinuo').hide();
		}
	});

	$('#cd_tipo_documentacao').change(function(){
		if($('#cd_tipo_documentacao').val() != "0"){
			extensoesDocumentoProjetoContinuo();
		} else {
			$('#divExtensao').css('display','none');
		}		
	});
});

function _fnUploadDocumentacaoProjetoContinuo(){
    if( validaDados() ){
        ajaxFileUpload({
            "url"           : systemName + '/documentacao-projeto-continuo/upload-file',
            "inputFile"     : $('#tx_arq_doc_projeto_continuo'),
            "data"          : $('#cd_objeto_projeto_continuo_doc').add('#cd_projeto_continuo_doc').add('#cd_tipo_documentacao'),
            "fileSize"      : '209715200',
            "uploadDir"     : '/public/documentacao/projeto-continuo',
            "uploadUp"     : '/public/documentacao/projeto-continuo',
            "imgLoader"     : $("#imgLoadDocumentacaoProjetoContinuo"),
            "callback"      : montaGridDocumentacaoProjetoContinuo,
            "callbackError" : function(){return false;}
        });
    }else{
        return false;
    }
}

function validaDados()
{
	if($('#cd_projeto_continuo_doc').val() == "0"){
		$('#cd_projeto_continuo_doc').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_projeto_continuo_doc'));
		return false;
	}
	if($('#cd_tipo_documentacao').val() == ""){
		$('#cd_tipo_documentacao').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_tipo_documentacao'));
		return false;
	}
	if($('#tx_arq_doc_projeto_continuo').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_arq_doc_projeto_continuo'));
		$('#tx_arq_doc_projeto_continuo').focus();
		return false;
	}
	return true;
}

function montaGridDocumentacaoProjetoContinuo()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documentacao-projeto-continuo/grid-documentacao-projeto-continuo",
		data	: "cd_objeto="+$("#cd_objeto_projeto_continuo_doc").val()+
		          "&cd_projeto_continuo="+$("#cd_projeto_continuo_doc").val(),
		success	: function(retorno){
			$('#gridDocumentacaoProjetoContinuo').html(retorno);
			$('#gridDocumentacaoProjetoContinuo').show();
		}
	});
}

function extensoesDocumentoProjetoContinuo()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/documentacao-projeto-continuo/extensoes-permitidas",
		data	: "cd_tipo_documentacao="+$("#cd_tipo_documentacao").val(),
		success	: function(retorno){
			$('#extensoes').html(retorno);
			$('#divExtensao').css('display','');
		}
	});
}

function montaComboProjetoContinuadoModulo()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-continuado/pesquisa-projeto-continuado",
		data	: "cd_objeto="+$("#cd_objeto_projeto_continuo_doc").val(),
		success	: function(retorno){
			$("#cd_projeto_continuo_doc").html(retorno);
		}
	});
}