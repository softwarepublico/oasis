$(document).ready(function(){

    montaGridPerguntas();

    $("#btn_nova_pergunta").click(function(){
        $('#form_cadastro_pergunta :input').val('');
        habilitaAbaCadastroPergunta();
    });

    $("#btn_cancelar_pergunta").click(function(){
        desabilitaAbaCadastroPergunta();
    });
    $("#btn_salvar_pergunta").click(function(){
        salvaPergunta();
    });
    
    desabilitaAbaCadastroPergunta();
});

function montaGridPerguntas()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-pergunta/grid-pergunta",
		success	: function(retorno){
			$("#grid_pergunta_pedido").html(retorno);
		}
	});
}

function salvaPergunta()
{
    if(!validaForm('#form_cadastro_pergunta')){return false};

    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-pergunta/salvar-pergunta",
		data	: $('#form_cadastro_pergunta :input').serialize(),
        dataType: 'json',
		success	: function(retorno){
            if(retorno['error'] == true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type'],function(){desabilitaAbaCadastroPergunta()});
                montaGridPerguntas();
            }
		}
	});
}

function recuperaPergunta(cd_pergunta_pedido)
{
    limpaFormularioPergunta();
    
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-pergunta/recupera-pergunta",
		data	: {'cd_pergunta_pedido':cd_pergunta_pedido},
        dataType: 'json',
		success	: function(retorno){

            $('#cd_pergunta_pedido').val(retorno['cd_pergunta_pedido']);
            $('#tx_titulo_pergunta').val(retorno['tx_titulo_pergunta']);
            $('#tx_ajuda_pergunta' ).val(retorno['tx_ajuda_pergunta' ]);

            $('#st_obriga_resposta'  ).val(retorno['st_obriga_resposta'  ]);
            $('#st_multipla_resposta').val(retorno['st_multipla_resposta']);

            habilitaAbaCadastroPergunta();
		}
	});
}

function excluirPerguntaPedido(cd_pergunta_pedido)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/"+systemNameModule+"/pedido-pergunta/excluir-pergunta",
            data	: {'cd_pergunta_pedido':cd_pergunta_pedido},
            dataType: 'json',
            success	: function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['type']);
                }else{
                    alertMsg(retorno['msg'],retorno['type']);
                    montaGridPerguntas();
                }
            }
        });
    });
}

function habilitaAbaCadastroPergunta()
{
    if($('#cd_pergunta_pedido').val() != ''){
        $('#btn_limpar_pergunta').hide();
    }

    $('#li_nova_pergunta'   ).show();
    $('#container_perguntas').triggerTab(2);
}

function desabilitaAbaCadastroPergunta()
{
    $('#btn_limpar_pergunta'          ).show();
    $('#li_nova_pergunta'             ).hide();
    $('#container_perguntas'          ).triggerTab(1);
    limpaFormularioPergunta();
}

function limpaFormularioPergunta()
{
    $('#form_cadastro_pergunta :input').val('');
}