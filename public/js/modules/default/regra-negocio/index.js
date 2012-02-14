$(document).ready(function() {
    $('#container-regra_negocio').show().tabs();
});

function verificaStatusAccordionRegraDeNegocio()
{
	if( $("#config_hidden_regra_de_negocio").val() === "N" ){
		montaGridFechamentoVersao();
		montaGridRegraNegocio();
	}
}

function habilitaAbaRegraNegocio()
{
    $("#li_aba_regra_negocio"	).show();
    $('#container-regra_negocio').triggerTab(2);
    $('#tx_regra_negocio'		).focus();
}

function desabilitaAbaRegraNegocio()
{
    $("#li_aba_regra_negocio"	).hide();
    $('#container-regra_negocio').triggerTab(1);
}

function montaGridRegraNegocio()
{
    $('#container-regra_negocio').triggerTab(1);
    $.ajax({
        type	: "POST",
        url		: systemName+"/regra-negocio/grid-regra-negocio",
        data	: {'cd_projeto_regra_negocio':$('#cd_projeto_regra_negocio').val()},
        success	: function(retorno){
            $("#gridRegraNegocio").html(retorno);
            if( $("#config_hidden_regra_de_negocio").val() === "N" ){
            	$("#config_hidden_regra_de_negocio").val("S");
        	}
        }
    });
}

function excluirRegraNegocio( cd_regra_negocio )
{
    confirmMsg( i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type    : "POST",
            url     : systemName+"/regra-negocio/excluir-regra-negocio",
            data    : {'cd_regra_negocio':cd_regra_negocio},
            success : function(ret){
                eval( 'var retorno = ' + ret );
                if( typeof(retorno)=='object' ){
                    alertMsg(retorno.msg,retorno.tipo);
                } else {
                    alertMsg(retorno,3);
                }  
                montaGridRegraNegocio();
                montaGridFechamentoVersao();
            }
        });
    });
}

function recuperaRegraNegocio( cd_regra_negocio,ni_versao_regra_negocio )
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/regra-negocio/recupera-regra-negocio",
        data    : {'cd_regra_negocio':cd_regra_negocio,'ni_versao_regra_negocio':ni_versao_regra_negocio},
        success : function(ret){
            eval( 'var retorno = ' + ret );
            if( typeof(retorno)=='object' ){
                habilitaAbaRegraNegocio();
                retorno = retorno[0];
                $('#cd_regra_negocio').val( retorno.cd_regra_negocio );
                $('#tx_regra_negocio').val( retorno.tx_regra_negocio ).attr('readonly','readonly');//nome da regra não poderar se alterado!
                $('#tx_descricao_regra_negocio').wysiwyg('value', retorno.tx_descricao_regra_negocio);
                if(retorno.st_regra_negocio=='I'){
                    $('#st_regra_negocio').attr('checked','checked');
                } else {
                    $('#st_regra_negocio').removeAttr('checked');
                }
            } else {
                alertMsg(retorno,3,function(){
                    cancelarRegraNegocio();
                });
            }  
        }
    });
}

function salvarRegraNegocio()
{
    if( !validaForm('#container-regra_negocio') ){return false;}
    $.ajax({
        type    : "POST",
        url     : systemName+"/regra-negocio/salvar-regra-negocio",
        data    : $('#container-regra_negocio :input').serialize(),
        success : function(ret){
            eval( 'var retorno = ' + ret );
            if( typeof(retorno)=='object' ){
                alertMsg(retorno.msg,retorno.tipo,function(){
                    cancelarRegraNegocio();
                });
            } else {
                alertMsg(retorno,3,function(){cancelarRegraNegocio();});
            }
            montaGridRegraNegocio();
            montaGridFechamentoVersao();
        }
    });
}

function cancelarRegraNegocio()
{
    $('#cd_regra_negocio'			).val('');
    $('#tx_regra_negocio'			).val('').removeAttr('readonly');
    $('#tx_descricao_regra_negocio'	).wysiwyg('clear');
    $('#st_regra_negocio'			).removeAttr('checked');
    desabilitaAbaRegraNegocio();
}