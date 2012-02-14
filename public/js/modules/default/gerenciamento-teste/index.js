$(document).ready(function() {
    $('#container-gerenciamento_teste').show().tabs();
    removeTollTip();
    
    $("#cd_contrato_gerenciamento_teste").change(function(){
    	if ($("#cd_contrato_gerenciamento_teste").val() != 0){
    		gridProjetosExecucaoAjax();
    	} else {
    		$("#gridProjetosExecucao").html('');
    	}
    });
});

function gridProjetosExecucaoAjax()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/gerenciamento-teste/grid-projetos-execucao",
		data	: "cd_contrato="+$("#cd_contrato_gerenciamento_teste").val(),
		success	: function(retorno){
			$("#gridProjetosExecucao").html(retorno);
		}
	});
}

function habilitaAbaGerenciamentoTeste( aba , atualizaGrid )
{
    removeTollTip();

    $("#li_aba_casoDeUso"				).hide();
    $("#li_aba_casoDeUso_analise"		).hide();
    $("#li_aba_casoDeUso_solucao"		).hide();
    $("#li_aba_casoDeUso_homologacao"	).hide();
    $("#li_aba_requisito"				).hide();
    $("#li_aba_requisito_analise"		).hide();
    $("#li_aba_requisito_solucao"		).hide();
    $("#li_aba_requisito_homologacao"	).hide();
    $("#li_aba_regraNegocio"			).hide();
    $("#li_aba_regraNegocio_analise"	).hide();
    $("#li_aba_regraNegocio_solucao"	).hide();
    $("#li_aba_regraNegocio_homologacao").hide();

    var atualizaGrid = atualizaGrid?true:false;

    switch ( aba ) {
        case "casoDeUso":                var tab = 2;  var permissao = permissao_casoDeUso;                break;
        case "casoDeUso_analise":        var tab = 3;  var permissao = permissao_casoDeUso_analise;        break;
        case "casoDeUso_solucao":        var tab = 4;  var permissao = permissao_casoDeUso_solucao;        break;
        case "casoDeUso_homologacao":    var tab = 5;  var permissao = permissao_casoDeUso_homologacao;    break;
        case "requisito":                var tab = 6;  var permissao = permissao_requisito;                break;
        case "requisito_analise":        var tab = 7;  var permissao = permissao_requisito_analise;        break;
        case "requisito_solucao":        var tab = 8;  var permissao = permissao_requisito_solucao;        break;
        case "requisito_homologacao":    var tab = 9;  var permissao = permissao_requisito_homologacao;    break;
        case "regraNegocio":             var tab = 10; var permissao = permissao_regraNegocio;             break;
        case "regraNegocio_analise":     var tab = 11; var permissao = permissao_regraNegocio_analise;     break;
        case "regraNegocio_solucao":     var tab = 12; var permissao = permissao_regraNegocio_solucao;     break;
        case "regraNegocio_homologacao": var tab = 13; var permissao = permissao_regraNegocio_homologacao; break;
        default: 
            $('#container-gerenciamento_teste').triggerTab( 1 );
            $('#container-gerenciamento_teste :input').not('.pager :input').val('');
            return false;
    }

    if( !permissao ){ return false; }
    
    var tabSplit    = aba.split('_');
    var objAbaPai   = $("#li_aba_" + tabSplit[0] ).show();   
    var objAbaFilho = $("#li_aba_" + tabSplit[0] + ((tabSplit[1]!=undefined) ? '_' + tabSplit[1] : '') ).show();

    if( objAbaPai.attr('id') == objAbaFilho.attr('id') ) {
        //alert('não tem filho');
    }
    $('#container-gerenciamento_teste').triggerTab( tab );
    if( atualizaGrid ){
        montaGrid( aba );
    }
    return true;
}

function cadastraTeste( aba , cd_projeto , sigla_projeto )
{
    if( !habilitaAbaGerenciamentoTeste(aba,true) ){ return false; }

    $('#container-gerenciamento_teste #cd_projeto').val(cd_projeto);
    $('span.SigProjeto:visible').text(sigla_projeto);

    //montaGrid( aba );
}

//params.tx_modulo
//params.cd_modulo
//params.codigo_tabela
//params.descricao_tabela
//params.abaOrigem
function cadastraTesteSubAba( subAba,todosObrigatorios,params )
{
    aba = params.abaOrigem+ '_' +subAba;
    if( !habilitaAbaGerenciamentoTeste(aba) ){ return false; }

    $('span.Descricao:visible').text(params.descricao_tabela);
    $('input.codTabela:hidden').val(params.codigo_tabela);

    if( params.cd_modulo != undefined && params.tx_modulo != undefined ){
        $('span.DescricaoModulo:visible').text(params.tx_modulo);
        $('input.codTabelaModulo:hidden').val(params.cd_modulo);
    }

    if( todosObrigatorios ){
        alertMsg(i18n.L_VIEW_SCRIPT_ITEM_TESTE_OBRIGATORIO_PREENCHIDO,2,function(){montaGrid( aba )});
    } else {
        montaGrid( aba );
    }
}

function montaGrid( aba )
{
    removeTollTip();
    switch (aba) {
        case "casoDeUso": 
            var url  = systemName + "/gerenciamento-teste-caso-uso/grid-gerenciamento-teste-caso-uso";
            var data = {'cd_projeto':$('#container-gerenciamento_teste #cd_projeto').val()};
            break;
        case "casoDeUso_analise":        
            var url  = systemName + "/gerenciamento-teste-caso-uso-analise/grid-gerenciamento-teste-caso-uso-analise";
            var data = {
                'cd_caso_de_uso' : $('#cd_casoDeUso_analise').val(),
                'cd_modulo'      : $('#cd_casoDeUso_analise__cd_modulo').val(),
                'cd_projeto'     : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        case "casoDeUso_solucao":        
            var url  = systemName + "/gerenciamento-teste-caso-uso-solucao/grid-gerenciamento-teste-caso-uso-solucao";
            var data = {
                'cd_caso_de_uso' : $('#cd_casoDeUso_solucao').val(),
                'cd_modulo'      : $('#cd_casoDeUso_solucao__cd_modulo').val(),
                'cd_projeto'     : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        case "casoDeUso_homologacao":    
            var url  = systemName + "/gerenciamento-teste-caso-uso-homologacao/grid-gerenciamento-teste-caso-uso-homologacao";
            var data = {
                'cd_caso_de_uso' : $('#cd_casoDeUso_homologacao').val(),
                'cd_modulo'      : $('#cd_casoDeUso_homologacao__cd_modulo').val(),
                'cd_projeto'     : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        case "requisito": 
            var url  = systemName + "/gerenciamento-teste-requisito/grid-gerenciamento-teste-requisito";
            var data = {'cd_projeto':$('#container-gerenciamento_teste #cd_projeto').val()};
            break;
        case "requisito_analise":        
            var url  = systemName + "/gerenciamento-teste-requisito-analise/grid-gerenciamento-teste-requisito-analise";
            var data = {
                'cd_requisito' : $('#cd_requisito_analise').val(),
                'cd_projeto'   : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        case "requisito_solucao":        
            var url  = systemName + "/gerenciamento-teste-requisito-solucao/grid-gerenciamento-teste-requisito-solucao";
            var data = {
                'cd_requisito' : $('#cd_requisito_solucao').val(),
                'cd_projeto'   : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        case "requisito_homologacao":    
            var url  = systemName + "/gerenciamento-teste-requisito-homologacao/grid-gerenciamento-teste-requisito-homologacao";
            var data = {
                'cd_requisito' : $('#cd_requisito_homologacao').val(),
                'cd_projeto'   : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        case "regraNegocio": 
            var url  = systemName + "/gerenciamento-teste-regra-negocio/grid-gerenciamento-teste-regra-negocio";
            var data = {'cd_projeto':$('#container-gerenciamento_teste #cd_projeto').val()};
            break;
        case "regraNegocio_analise":     
            var url  = systemName + "/gerenciamento-teste-regra-negocio-analise/grid-gerenciamento-teste-regra-negocio-analise";
            var data = {
                'cd_regra_negocio': $('#cd_regraNegocio_analise').val(),
                'cd_projeto'      : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        case "regraNegocio_solucao":     
            var url  = systemName + "/gerenciamento-teste-regra-negocio-solucao/grid-gerenciamento-teste-regra-negocio-solucao";
            var data = {
                'cd_regra_negocio': $('#cd_regraNegocio_solucao').val(),
                'cd_projeto'      : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        case "regraNegocio_homologacao":
            var url  = systemName + "/gerenciamento-teste-regra-negocio-homologacao/grid-gerenciamento-teste-regra-negocio-homologacao";
            var data = {
                'cd_regra_negocio': $('#cd_regraNegocio_homologacao').val(),
                'cd_projeto'      : $('#container-gerenciamento_teste #cd_projeto').val()
            };
            break;
        default: 
            return false;
    }
    
    var objGrid = $("#grid_" + aba + "_gerenciamentoTeste");
//    objGrid.find('textarea').val('');
    objGrid.empty();
    if( $('#container-gerenciamento_teste #cd_projeto').val() == '' || !$('#container-gerenciamento_teste #cd_projeto').val()  ){
        objGrid.empty();
        return false;
    }
    $.ajax({
        type	: "POST",
        url		: url,
        data	: data,
        success	: function(retorno){
            objGrid.empty().html(retorno);
            // alterando o paginador da grid
            /*objGrid.find('select.pagesize')
                   .html('<option selected="selected" value="3">3</option>'+
                         '<option value="5">5</option>'+
                         '<option value="10">10</option>')
                   .trigger('change');*/ 
            objGrid.find('textarea').each( function(){
                // tamanho dos textareas
                //$(this).height('150px'); 
                // faz rolar todos os textareas para baixo!
                $(this).get(0).scrollTop = 999999;
            });
        }
    });
}

function salvarGerenciamentoTeste(aba , idLinha, objTextarea, homologar)
{
    removeTollTip();
    var idLinha     = (idLinha)     ? ' #'+idLinha : '';
    var objTextarea = (objTextarea) ? objTextarea  : false;
    var homologar   = (homologar)   ? homologar    : false;
    switch (aba) {
        case "casoDeUso_analise":
            var abaPai = 'casoDeUso';
            var urlHomo     = systemName + "/gerenciamento-teste-caso-uso-analise/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-caso-uso-analise/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-caso-uso-analise/save";
            var idContainer = '#grid_casoDeUso_analise_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto='     + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_caso_de_uso=' + $('#cd_casoDeUso_analise').val();
                data += '&cd_modulo='      + $('#cd_casoDeUso_analise__cd_modulo').val();

            var permissao = permissao_casoDeUso_analise;
            break;
        case "casoDeUso_solucao":
            var abaPai = 'casoDeUso';
            var urlHomo     = systemName + "/gerenciamento-teste-caso-uso-solucao/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-caso-uso-solucao/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-caso-uso-solucao/save";
            var idContainer = '#grid_casoDeUso_solucao_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto='     + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_caso_de_uso=' + $('#cd_casoDeUso_solucao').val();
                data += '&cd_modulo='      + $('#cd_casoDeUso_solucao__cd_modulo').val();

            var permissao = permissao_casoDeUso_solucao;
            break;
        case "casoDeUso_homologacao":
            var abaPai = 'casoDeUso';
            var urlHomo     = systemName + "/gerenciamento-teste-caso-uso-homologacao/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-caso-uso-homologacao/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-caso-uso-homologacao/save";
            var idContainer = '#grid_casoDeUso_homologacao_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto='     + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_caso_de_uso=' + $('#cd_casoDeUso_homologacao').val();
                data += '&cd_modulo='      + $('#cd_casoDeUso_homologacao__cd_modulo').val();

            var permissao = permissao_casoDeUso_homologacao;
            break;
        case "requisito_analise":
            var abaPai = 'requisito';
            var urlHomo     = systemName + "/gerenciamento-teste-requisito-analise/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-requisito-analise/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-requisito-analise/save";
            var idContainer = '#grid_requisito_analise_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto='   + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_requisito=' + $('#cd_requisito_analise').val();

            var permissao = permissao_requisito_analise;
            break;
        case "requisito_solucao":
            var abaPai = 'requisito';
            var urlHomo     = systemName + "/gerenciamento-teste-requisito-solucao/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-requisito-solucao/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-requisito-solucao/save";
            var idContainer = '#grid_requisito_solucao_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto='   + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_requisito=' + $('#cd_requisito_solucao').val();

            var permissao = permissao_requisito_solucao;
            break;
        case "requisito_homologacao":
            var abaPai = 'requisito';
            var urlHomo     = systemName + "/gerenciamento-teste-requisito-homologacao/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-requisito-homologacao/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-requisito-homologacao/save";
            var idContainer = '#grid_requisito_homologacao_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto='   + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_requisito=' + $('#cd_requisito_homologacao').val();

            var permissao = permissao_requisito_homologacao;
            break;
        case "regraNegocio_analise":
            var abaPai = 'regraNegocio';
            var urlHomo     = systemName + "/gerenciamento-teste-regra-negocio-analise/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-regra-negocio-analise/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-regra-negocio-analise/save";
            var idContainer = '#grid_regraNegocio_analise_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto_regra_negocio=' + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_regra_negocio='         + $('#cd_regraNegocio_analise').val();

            var permissao = permissao_regraNegocio_analise;
            break;
        case "regraNegocio_solucao":
            var abaPai = 'regraNegocio';
            var urlHomo     = systemName + "/gerenciamento-teste-regra-negocio-solucao/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-regra-negocio-solucao/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-regra-negocio-solucao/save";
            var idContainer = '#grid_regraNegocio_solucao_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto_regra_negocio=' + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_regra_negocio='         + $('#cd_regraNegocio_solucao').val();

            var permissao = permissao_regraNegocio_solucao;
            break;
        case "regraNegocio_homologacao":
            var abaPai = 'regraNegocio';
            var urlHomo     = systemName + "/gerenciamento-teste-regra-negocio-homologacao/approve";
            var urlNaoHomo  = systemName + "/gerenciamento-teste-regra-negocio-homologacao/not-approve";
            var urlSave     = systemName + "/gerenciamento-teste-regra-negocio-homologacao/save";
            var idContainer = '#grid_regraNegocio_homologacao_gerenciamentoTeste' + idLinha;
            var data  = $( idContainer + ' :input' ).serialize(); 
                data += '&cd_projeto_regra_negocio=' + $('#container-gerenciamento_teste #cd_projeto').val();
                data += '&cd_regra_negocio='         + $('#cd_regraNegocio_homologacao').val();

            var permissao = permissao_regraNegocio_homologacao;
            break;
        default: 
            $('#container-gerenciamento_teste').triggerTab( 1 );
            $('#container-gerenciamento_teste :input').not('.pager :input').val('');
            return false;
    }
    
    var podeSalvar = true;
    
    if( objTextarea && objTextarea.val() == '' ){
        showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,objTextarea);
        return false;
    } else {
        $( idContainer + ' textarea' ).not(':readonly').each(function(i){
            if(podeSalvar){ 
                if($.trim($(this).val())==''){
                    showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$(this));
                    podeSalvar = false;
                }
            }
        });
    }
    
    if( !podeSalvar ){ return false; }
    if( !permissao  ){ return false; }
    if( !homologar  ){
        var url = urlSave;
    } else if( homologar=='H' ){
        var url = urlHomo;
    } else if( homologar=='N' ){
        var url = urlNaoHomo;
    }
    
    $.ajax({
        type    : 'POST',
        url     : url,
        data    : data,
        success : function(ret){
            eval( 'var retorno = ' + ret );
            if( typeof(retorno)=='object' ){
                alertMsg(retorno.msg,retorno.tipo,function(){
                    habilitaAbaGerenciamentoTeste(aba,true);
                });
            } else {
                alertMsg(retorno,3,function(){
                    habilitaAbaGerenciamentoTeste(abaPai,true);
                });
            } 
        }
    });
}

function abreHistorico( jsonData , aba)
{
    jsonData.aba = aba;
    var inputs = '';
    for ( var i in jsonData ){
        inputs += '<input type="hidden" id="'+i+'" name="'+i+'" value="'+jsonData[i]+'" />';
    }
    $('body').append('<form id="myForm">'+inputs+'</form>');
    gerarRelatorio( $('#myForm') , 'gerenciamento-teste/historico' );
    $('#myForm').remove();
    return true;
}

function abreDialogDownload( jsonData , aba)
{
    jsonData.aba = aba;
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_download_imagem_teste');}+'};');
    loadDialog({
        id       : 'dialog_download_imagem_teste',
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_LISTA_ANEXO_RELATO_TESTE,
        url      : systemName + '/gerenciamento-teste/form-download-file',
        data     : jsonData,
        buttons  : buttons
    });
}

function abreDialogUpload( jsonData , objTextArea, aba)
{
    jsonData.textAreaPreenchido = $.trim(objTextArea.val());
    jsonData.aba = aba;
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_upload_imagem_teste');}+', "'+i18n.L_VIEW_SCRIPT_BTN_ANEXAR_IMAGEM+'": uploadImagemTeste };');
    loadDialog({ 
        id       : 'dialog_upload_imagem_teste',
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_ANEXAR_IMAGEM_RELATO_TESTE,
        url      : systemName + '/gerenciamento-teste/form-upload-file',
        data     : jsonData,
        height   : 250,
        width    : 700,
        buttons  : buttons
    });
}


function uploadImagemTeste(){
    if( validaUpload() ){
        ajaxFileUpload({
            "url"           : systemName + '/gerenciamento-teste/upload-file',
            "inputFile"     : $('#tx_arquivo_form_upload_file'),
            "data"          : $(inptusUploadValue),
            "fileSize"      : '10485760',
            "uploadDir"     : '/public/documentacao/item-teste',
            "uploadUp"      : '/public/documentacao/item-teste',
            "callback"      : function(){successUpload(abaSuccessUpload);},
            "callbackError" : errorUpload
        });
    }else{
        return false;
    }
}


function validaUpload(){
    return validaForm('#dialog_upload_imagem_teste',true);
}

function successUpload(aba){
    montaGrid(aba);
    closeDialog('dialog_upload_imagem_teste');
}

function errorUpload(){
    //closeDialog('dialog_upload_imagem_teste');
}

function verificaSePodeDigitar(objInputCheck,objInputAct)
{
    //var checkValue = objInputCheck.text();
    var checkValue = objInputCheck.val();
    if( $.trim(checkValue) == '' ){
        alertMsg( i18n.L_VIEW_SCRIPT_CAMPO_ANTERIOR_BRANCO_ESTE_NAO_PODE_SER_PREENCHIDO, 3, function(){objInputAct.val('');});
    }
}