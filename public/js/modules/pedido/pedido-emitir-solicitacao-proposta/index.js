$(document).ready(function(){
    gridSolicitacao();
    desabilitaAbas();
});

function gridSolicitacao(){
    $.ajax({
        type	: "POST",
        url		: systemName+"/"+systemNameModule+"/pedido-emitir-solicitacao-proposta/grid-solicitacao",
        success	: function(retorno){
            $('#grid_solicitacao').html(retorno);
        }
    });
}

/**
 * Emitir a Solicitação da proposta
 */
function emitirSolicitacao(cd_solicitacao_pedido){
    montaFormulario(cd_solicitacao_pedido);
    montaHistorico(cd_solicitacao_pedido);
    montaSolicitacaoPedido(cd_solicitacao_pedido);

    $('#container_emitir_solicitacao_proposta').enableTab(2)
                                               .enableTab(3)
                                               .enableTab(4);
}

function desabilitaAbas(){
    $('#container_emitir_solicitacao_proposta').tabs({disabled: [2,3,4]}).triggerTab(1);
    gridSolicitacao();
}

function montaFormulario(cd_solicitacao_pedido) {
    $.ajax({
        type	: "POST",
        url		: systemName+"/"+systemNameModule+"/pedido-emitir-solicitacao-proposta/formulario-pedido",
        data    : "cd_solicitacao_pedido="+cd_solicitacao_pedido,
        success	: function(retorno){
            $('#formulario_pedido').html(retorno);
        }
    });
}

function montaHistorico(cd_solicitacao_pedido) {
    $.ajax({
        type	: "POST",
        url		: systemName+"/"+systemNameModule+"/pedido-emitir-solicitacao-proposta/historico-pedido",
        data    : "cd_solicitacao_pedido="+cd_solicitacao_pedido,
        success	: function(retorno){
            $('#historico_pedido').html(retorno);
        }
    });
}

function montaSolicitacaoPedido(cd_solicitacao_pedido) {
    $.ajax({
        type	: "POST",
        url		: systemName+"/"+systemNameModule+"/pedido-emitir-solicitacao-proposta/solicitacao-pedido",
        data    : "cd_solicitacao_pedido="+cd_solicitacao_pedido,
        success	: function(retorno){
            $('#solicitacao_pedido').html(retorno);
            $('#container_emitir_solicitacao_proposta').triggerTab(4);
        }
    });
}

function cadastrarSolicitacaoPedido()
{
    if(!validaForm('#formSolicitacaoPedido')){ return false; }
    $.ajax({
        type	: "POST",
        url		: systemName+"/"+systemNameModule+"/pedido-emitir-solicitacao-proposta/cadastrar-solicitacao-pedido",
        data    : $('#formSolicitacaoPedido :input').serialize(),
        dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] === true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type'], function(){desabilitaAbas(); gridSolicitacao()});
            }
		}
    });
}