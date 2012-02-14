$(document).ready(function(){
    montaComboPergunta();

    $('#cd_pergunta_pedido').change(function(){
        if($(this).val() != 0){
            getDescricaoAjudaPergunta($(this).val());
        }else{
            $('#tx_ajuda_pergunta'   ).html('');
			$('#lb_tx_ajuda_pergunta').hide();
        }
        montaGridOpcaoResposta();
    });

    $('#btn_cancelar_opcao').click(function(){
        limparFormulario();
    });

    $('#btn_salvar_opcao').click(function(){
        salvarOpcaoResposta();
    });

});

function montaComboPergunta()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-opcao/monta-combo-pergunta",
		success	: function(retorno){
			$("#cd_pergunta_pedido").html(retorno);
		}
	});
}

function getDescricaoAjudaPergunta(cd_pergunta)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-opcao/get-dados-pergunta",
        data    : {'cd_pergunta' : cd_pergunta},
        dataType: 'json',
		success	: function(retorno){
            if($.trim(retorno['tx_ajuda_pergunta']) != ''){
                $("#tx_ajuda_pergunta"   ).html(retorno['tx_ajuda_pergunta']);
                $("#lb_tx_ajuda_pergunta").show();
            }
		}
	});
}

function montaGridOpcaoResposta()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-opcao/grid-opcao-resposta",
        data    : {'cd_pergunta' : $('#cd_pergunta_pedido').val()},
		success	: function(retorno){
			$("#grid_opcao_resposta").html(retorno);
		}
	});
}

function salvarOpcaoResposta()
{
    if(!validaForm('#form_opcao_resposta')){return false}
    
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-opcao/salvar-opcao-resposta",
        data    : $('#form_opcao_resposta').serialize(),
        dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] === true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type'], function(){montaGridOpcaoResposta(); limparFormulario();});
            }
		}
	});
}

function limparFormulario()
{
    $('#form_opcao_resposta :input').not('#cd_pergunta_pedido').val('');
}

function recuperarOpcaoResposta(cd_pergunta_pedido, cd_resposta_pedido)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-opcao/recuperar-opcao-resposta",
        data    : {'cd_pergunta_pedido':cd_pergunta_pedido,
                   'cd_resposta_pedido':cd_resposta_pedido},
        dataType: 'json',
		success	: function(retorno){
			$('#cd_resposta_pedido').val(retorno['cd_resposta_pedido']);
			$('#st_resposta_texto' ).val(retorno['st_resposta_texto' ]);
			$('#ni_ordem_apresenta').val(retorno['ni_ordem_apresenta']);
		}
	});
}

function excluirOpcaoResposta(cd_pergunta_pedido, cd_resposta_pedido)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/"+systemNameModule+"/pedido-opcao/excluir-opcao-resposta",
            data    : {'cd_pergunta_pedido':cd_pergunta_pedido,
                       'cd_resposta_pedido':cd_resposta_pedido},
            dataType: 'json',
            success	: function(retorno){
                if(retorno['error'] === true){
                    alertMsg(retorno['msg'],retorno['type']);
                }else{
                    alertMsg(retorno['msg'],retorno['type'], function(){montaGridOpcaoResposta();});
                }
            }
        });
    });
}