$(document).ready(function() {
	$('#container-disponibilidade-servico').show().tabs().triggerTab(1);
	$('#config_hidden_disponibilidade_servico').val('N');

	$('#btn_salvar_analise_disp_servico').click(function(){
		if( !validaForm('#div_nova_analise_disp_servico') ){return false;}
		if( !validaPeriodo($('#dt_inicio_analise_disp_servico'), $('#dt_fim_analise_disp_servico')) ){return false;}
		salvarAnaliseDisponibilidadeServico();
	});

	$('#cmd_objeto_disponibilidade_servico').change(montaGridAnaliseDisponibilidadeServico);
	$('#btn_nova_analise_disponibilidade').click(function(){
		limpaCamposFormAnaliseDispoServico();
		habilitaAbaNovaAnalise();
	});
	$('#btn_cancelar_analise_disp_servico').click(function(){
		desabilitaAbaNovaAnalise();
		limpaCamposFormAnaliseDispoServico();
	});

	$('#btn_cancelar_anexo_documentacao_disp_servico').click(desabilitaAbaDocumentacaoAnalise);

	$('#cd_tipo_documentacao_disp_servico').change(function(){
		if($('#cd_tipo_documentacao_disp_servico').val() != "0"){
			extensoesDocumentoProfissional();
		} else {
			$('#divExtensao').hide();
		}
	});
});

function _fnUploadDocumentacaoDispServico(){
    if( validaDadosDocumentacaoDispServico() ){
        ajaxFileUpload({
            "url"           : systemName + '/disponibilidade-servico/upload-file',
            "inputFile"     : $('#tx_arquivo_disp_servico'),
            "data"          : $('#cd_disponibilidade_servico_documentacao')
                                    .add('#cd_objeto_disponibilidade_servico_documentacao')
                                    .add('#cd_tipo_documentacao_disp_servico'),
            "fileSize"      : '1048576',
            "uploadDir"     : 'public/documentacao/disponibilidade-servico',
            "uploadUp"      : 'public/documentacao/disponibilidade-servico',
            "imgLoader"     : $("#imgLoadAnaliseDisponibilidade"),
            "callback"      : function(){montaGridDocumentacaoDispServico();},
            "callbackError" : function(){return false;}
        });
    }else{
        return false;
    }
}

function configDisponibilidadeServico(){

	montaGridAnaliseDisponibilidadeServico();
	$('#config_hidden_disponibilidade_servico').val('S');
}

function montaGridAnaliseDisponibilidadeServico(){

	$.ajax({
		type	: "POST",
		url		: systemName+"/disponibilidade-servico/grid-analise-disponibilidade",
		data	: "cd_objeto="+$('#cmd_objeto_disponibilidade_servico').val(),
		success	: function(retorno){
			$('#grid_analise_disponibilidade').html(retorno);
		}
	});
}

function salvarAnaliseDisponibilidadeServico(){

	$.ajax({
		type	: "POST",
		url		: systemName+"/disponibilidade-servico/salvar",
		data	: $("#div_nova_analise_disp_servico :input").serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['typeMsg']);
			}else{
				alertMsg(retorno['msg'],retorno['typeMsg'],'desabilitaAbaNovaAnalise()');
				montaGridAnaliseDisponibilidadeServico();
				limpaCamposFormAnaliseDispoServico();
			}
		}
	});
}

function limpaCamposFormAnaliseDispoServico(){

	$('#div_nova_analise_disp_servico :input').val('').removeAttr('disabled');
	$('#tx_analise_disp_servico'	 ).wysiwyg('value','');
	$('#tx_parecer_disp_servico'	 ).wysiwyg('value','');
}

function excluiAnaliseDispServico(cd_objeto, cd_analise){

	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/disponibilidade-servico/excluir",
			data	: {'cd_objeto': cd_objeto, 'cd_analise': cd_analise},
			dataType: 'json',
			success	: function(retorno){
				if(retorno['error'] == true){
					alertMsg(retorno['msg'],retorno['typeMsg']);
				}else{
					alertMsg(retorno['msg'],retorno['typeMsg']);
					montaGridAnaliseDisponibilidadeServico();
				}
			}
		});
	});
}

function recuperaDadosAnaliseDispServico(cd_objeto, cd_analise)
{

	$.ajax({
		type	: "POST",
		url		: systemName+"/disponibilidade-servico/recupera-dados-analise",
		data	: {'cd_objeto': cd_objeto, 'cd_analise': cd_analise},
		dataType: 'json',
		success	: function(retorno){

			$('#cd_disponibilidade_servico'				).val(retorno['cd_disponibilidade_servico']);

			$('#hidden_cd_objeto_disp_servico'					).val(retorno['cd_objeto']);
			$('#hidden_dt_inicio_analise_disponilidade_servico' ).val(retorno['dt_inicio_analise_disp_servico']);
			$('#hidden_dt_fim_analise_disponilidade_servico'	).val(retorno['dt_fim_analise_disp_servico']);

			$('#cd_objeto_disp_servico'         ).val(retorno['cd_objeto']).attr('disabled','disabled');
			$('#dt_inicio_analise_disp_servico' ).val(retorno['dt_inicio_analise_disp_servico']).attr('disabled','disabled');
			$('#dt_fim_analise_disp_servico'	).val(retorno['dt_fim_analise_disp_servico']).attr('disabled','disabled');
			$('#tx_analise_disp_servico'		).wysiwyg('value',retorno['tx_analise_disp_servico']);
			$('#tx_parecer_disp_servico'		).wysiwyg('value',retorno['tx_parecer_disp_servico']);
			$('#ni_indice_disp_servico'         ).val(retorno['ni_indice_disp_servico']);

			habilitaAbaNovaAnalise();
		}
	});
}

//Inicio dos métodos relativos ao upload de arquivos
function anexarDocumentacaoAnaliseDispServico(cd_objeto, cd_analise){
	$('#cd_disponibilidade_servico_documentacao'		).val(cd_analise);
	$('#cd_objeto_disponibilidade_servico_documentacao'	).val(cd_objeto);
	$('#grid_documentacao_analise_disponibilidade'		).html('');
	montaGridDocumentacaoDispServico(cd_objeto, cd_analise);
	habilitaAbaDocumentacaoAnalise();
}

function extensoesDocumentoProfissional()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/disponibilidade-servico/extensoes-permitidas",
		data	: "cd_tipo_documentacao="+$("#cd_tipo_documentacao_disp_servico").val(),
		success	: function(retorno){
			$('#extensoes'	).html(retorno);
			$('#divExtensao').show();
		}
	});
}

function validaDadosDocumentacaoDispServico(){

	if($('#cd_tipo_documentacao_disp_servico').val() == 0){
		$('#cd_tipo_documentacao_disp_servico').focus();
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_tipo_documentacao_disp_servico'));
		return false;
	}
	if($('#tx_arquivo_disp_servico').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_arquivo_disp_servico'));
		$('#tx_arquivo_disp_servico').focus();
		return false;
	}
	return true;
}

function montaGridDocumentacaoDispServico(cd_objeto, cd_analise){
	$('#cd_tipo_documentacao_disp_servico').val('');
	$('#tx_arquivo_disp_servico'          ).val('');
	$('#divExtensao'					  ).hide();

	var cd_analise = (cd_analise == undefined) ? $('#cd_disponibilidade_servico_documentacao'		).val() : cd_analise;
	var cd_objeto = (cd_objeto == undefined)   ? $('#cd_objeto_disponibilidade_servico_documentacao').val() : cd_objeto;

	$.ajax({
		type	: "POST",
		url		: systemName+"/disponibilidade-servico/grid-documentacao-analise-disponibilidade",
		data	: {"cd_objeto": cd_objeto, 
                   "cd_analise": cd_analise},
		success	: function(retorno){
			$('#grid_documentacao_analise_disponibilidade').html(retorno);
		}
	});
}

//Fim metodos referentes ao upload de arquivo

function habilitaAbaNovaAnalise(){

	$('#li_nova_analise_disponibilidade'  ).show();
	$('#container-disponibilidade-servico').triggerTab(2);
}

function desabilitaAbaNovaAnalise(){

	$('#li_nova_analise_disponibilidade'  ).hide();
	$('#container-disponibilidade-servico').triggerTab(1);
}

function habilitaAbaDocumentacaoAnalise(){

	$('#li_documento_analise_disponibilidade').show();
	$('#container-disponibilidade-servico').triggerTab(3);
}

function desabilitaAbaDocumentacaoAnalise(){

	$('#li_documento_analise_disponibilidade'		).hide();
	$('#container-disponibilidade-servico'			).triggerTab(1);
	$('#form_documento_analise_disp_servico :input'	).val('');
	$('#grid_documentacao_analise_disponibilidade'	).html('');
	$('#divExtensao'								).hide();
}

function desabilitaAbasDispServico(){

	desabilitaAbaDocumentacaoAnalise();
	desabilitaAbaNovaAnalise();
}