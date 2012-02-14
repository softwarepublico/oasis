var podeCriarNovaAnalise;
$(document).ready(function() {

	$("#config_hidden_analise_execucao_projeto").val('N');

    podeCriarNovaAnalise = false;
    $('#container-analise_execucao_projeto').show().tabs();
    
    $("#cd_contrato_analise_execucao").change(function(){
    	if ($("#cd_contrato_analise_execucao").val() != 0){
		    montaComboProjetoAjax();
		}
    });

    $("#cd_projeto_execucao").change(function(){
    	 montaGridAnaliseExecucaoProjeto( $(this).val() );
    });
	
});

function verificaConfigAccordionAnaliseExecucaoProjeto()
{
	if ( ($("#config_hidden_analise_execucao_projeto").val() === 'N') && ( $("#cd_contrato_analise_execucao").val() != 0 ) ){
		cancelarAnaliseExecucaoProjeto();
		montaComboProjetoAjax();
	}
}


function habilitaAbaAnaliseExecucaoProjeto(peloBotaoNovo)
{
    var cd_projeto_execucao = $('#cd_projeto_execucao').val();
    if( cd_projeto_execucao == '0' || !cd_projeto_execucao ){
        showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_PROJETO_CRIAR_ANALISE,$('#cd_projeto_execucao'));
        $('#cd_projeto_execucao').focus();
        return cancelarAnaliseExecucaoProjeto(false);
    }
    if( peloBotaoNovo && !podeCriarNovaAnalise ){
        alertMsg(i18n.L_VIEW_SCRIPT_CRIAR_ANALISE_FECHAR_TODAS,'3');
        return false;
    }
	$('#dt_decisao_analise_execucao_img').show();
    $("#li_aba_analise_execucao_projeto").show();
    $('#container-analise_execucao_projeto').triggerTab(2);
    $('#btn_salvar_analise_execucao_projeto').show();
    $('#dt_decisao_analise_execucao').removeAttr('readonly');
    $('#tx_resultado_analise_execucao_editor, #tx_decisao_analise_execucao_editor').show();
    $('#div_resultado_analise_execucao').hide().empty();
    $('#div_decisao_analise_execucao').hide().empty();
    $('#tx_resultado_analise_execucao').wysiwyg('clear');
    $('#tx_decisao_analise_execucao').wysiwyg('clear');
    $('#dt_decisao_analise_execucao').val('');
    $('#dt_analise_execucao_projeto').val('');
}

function desabilitaAbaAnaliseExecucaoProjeto() {
    montaGridAnaliseExecucaoProjeto();
    $("#li_aba_analise_execucao_projeto").hide();
    $('#container-analise_execucao_projeto').triggerTab(1);
}

function montaGridAnaliseExecucaoProjeto( cd_projeto_execucao ) {
    if( cd_projeto_execucao == '0' || !cd_projeto_execucao  ){
        podeCriarNovaAnalise = false;
        return $("#gridAnaliseExecucaoProjeto").html('');
    }
    //$('#container-analise_execucao_projeto').triggerTab(1);
    $.ajax({
        type: "POST",
        url: systemName+"/analise-execucao-projeto/grid-analise-execucao-projeto",
        data: {'cd_projeto':cd_projeto_execucao},
        success: function(retorno){
            podeCriarNovaAnalise = true;
            $("#gridAnaliseExecucaoProjeto").html(retorno);
        }
    });
}

function montaComboProjetoAjax() {
    $.ajax({
        type: "POST",
        url: systemName+"/analise-execucao-projeto/monta-combo-projeto-execucao",
        data: {'cd_contrato':$("#cd_contrato_analise_execucao").val()},
        success: function(retorno){
            $("#cd_projeto_execucao").html(retorno);

			$("#config_hidden_analise_execucao_projeto").val('S');
        }
    });
}

function excluirAnaliseExecucaoProjeto( dt_analise_execucao_projeto ) {
    confirmMsg( i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/analise-execucao-projeto/excluir-analise-execucao-projeto",
            data    : {'dt_analise_execucao_projeto':dt_analise_execucao_projeto},
            success : function(ret){
                eval( 'var retorno = ' + ret );
                if( typeof(retorno)=='object' ){
                    alertMsg(retorno.msg,retorno.tipo,function(){
                        $('#cd_projeto_execucao').trigger('change');
                    });
                } else {
                    alertMsg(retorno,3,function(){
                        $('#cd_projeto_execucao').trigger('change');
                    });
                }  
            }
        });
    });
}

function fecharAnaliseExecucaoProjeto( dt_analise_execucao_projeto ) {
    confirmMsg( i18n.L_VIEW_SCRIPT_FECHAR_ANALISE_EXECUCAO, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/analise-execucao-projeto/fechar-analise-execucao-projeto",
            data    : {'dt_analise_execucao_projeto':dt_analise_execucao_projeto},
            success : function(ret){
                eval( 'var retorno = ' + ret );
                if( typeof(retorno)=='object' ){
                    alertMsg(retorno.msg,retorno.tipo,function(){
                        $('#cd_projeto_execucao').trigger('change');
                    });
                } else {
                    alertMsg(retorno,3,function(){
                        $('#cd_projeto_execucao').trigger('change');
                    });
                }  
            }
        });
    });
}

function recuperaAnaliseExecucaoProjeto( dt_analise_execucao_projeto , podeAlterar ) {
    $.ajax({
        type    : "POST",
        url     : systemName+"/analise-execucao-projeto/recupera-analise-execucao-projeto",
        data    : {'dt_analise_execucao_projeto':dt_analise_execucao_projeto},
        success : function(ret){
            eval( 'var retorno = ' + ret );
            if( typeof(retorno)=='object' ){
                habilitaAbaAnaliseExecucaoProjeto();
                retorno = retorno[0];
                if( podeAlterar ){
                    $('#btn_salvar_analise_execucao_projeto').show();
                    $('#dt_decisao_analise_execucao'        ).removeAttr('readonly');
					$('#dt_decisao_analise_execucao_img'    ).show();
                    $('#tx_resultado_analise_execucao_editor, #tx_decisao_analise_execucao_editor').show();
                    $('#div_resultado_analise_execucao'     ).hide().empty();
                    $('#div_decisao_analise_execucao'       ).hide().empty();
                } else {
                    $('#btn_salvar_analise_execucao_projeto').hide();
                    $('#dt_decisao_analise_execucao'        ).attr('readonly','readonly');
                    $('#dt_decisao_analise_execucao_img'    ).hide();
                    $('#tx_resultado_analise_execucao_editor, #tx_decisao_analise_execucao_editor').hide();
                    $('#div_resultado_analise_execucao'     ).show().html( retorno.tx_resultado_analise_execucao);
                    $('#div_decisao_analise_execucao'       ).show().html( retorno.tx_decisao_analise_execucao);
                }                
                $('#dt_analise_execucao_projeto'            ).val( retorno.dt_analise_execucao_projeto );
                $('#dt_decisao_analise_execucao'            ).val( retorno.dt_decisao_analise_execucao );
                $('#tx_resultado_analise_execucao'          ).wysiwyg('value', retorno.tx_resultado_analise_execucao);
                $('#tx_decisao_analise_execucao'            ).wysiwyg('value', retorno.tx_decisao_analise_execucao);
            } else {
                alertMsg(retorno,3,function(){
                    cancelarAnaliseExecucaoProjeto(false);
                });
            }  
        }
    });
}

function salvarAnaliseExecucaoProjeto() {
    if( !validaForm('#container-analise_execucao_projeto') ){return false;}
    $.ajax({
        type    : "POST",
        url     : systemName+"/analise-execucao-projeto/salvar-analise-execucao-projeto",
        data    : $('#container-analise_execucao_projeto :input').serialize(),
        success : function(ret){
            eval( 'var retorno = ' + ret  );
            if( typeof(retorno)=='object' ){
                alertMsg(retorno.msg,retorno.tipo,function(){
                    cancelarAnaliseExecucaoProjeto(true);
                    montaGridAnaliseExecucaoProjeto( $("#cd_projeto_execucao").val() );
                });
            } else {
                alertMsg(retorno,3,function(){cancelarAnaliseExecucaoProjeto(false)});
            }
        }
    });
}

function cancelarAnaliseExecucaoProjeto(naoAltera) {
    var naoAltera = (naoAltera)?true:false;
    if(!naoAltera){
        $('#cd_projeto_execucao').val('');
    }
    $('#tx_resultado_analise_execucao').wysiwyg('clear');
    $('#tx_decisao_analise_execucao').wysiwyg('clear');
    $('#dt_decisao_analise_execucao').val('');
    $('#dt_analise_execucao_projeto').val('');
    desabilitaAbaAnaliseExecucaoProjeto();
}