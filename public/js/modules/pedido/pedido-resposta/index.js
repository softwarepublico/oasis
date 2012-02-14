$(document).ready(function(){

    montaGridRespotas();

    $("#btn_limpar_resposta").show();

    $("#btn_cancelar_resposta").click(function(){
        $("#form_cadastro_resposta :input").val('');
        $("#btn_limpar_resposta").show();
    });

    $("#btn_salvar_resposta").click(function(){
        salvaResposta();
    });

});

function montaGridRespotas()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-resposta/grid-resposta",
		success	: function(retorno){
			$("#grid_resposta_pergunta_pedido").html(retorno);
		}
	});
}

function salvaResposta()
{
    if(!validaForm('#form_cadastro_resposta')){return false};

    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-resposta/salvar-resposta",
		data	: $('#form_cadastro_resposta :input').serialize(),
        dataType: 'json',
		success	: function(retorno){
            if(retorno['error'] == true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type']);
                montaGridRespotas();
                $("#form_cadastro_resposta :input").val('');
            }
		}
	});
}

function recuperaRespostaPedido(cd_resposta_pedido)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-resposta/recupera-resposta",
		data	: {'cd_resposta_pedido':cd_resposta_pedido},
        dataType: 'json',
		success	: function(retorno){
            $('#cd_resposta_pedido').val(retorno['cd_resposta_pedido']);
            $('#tx_titulo_resposta').val(retorno['tx_titulo_resposta']);
            $("#btn_limpar_resposta").hide();
		}
	});

}

function excluirRespostaPedido(cd_resposta_pedido)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/"+systemNameModule+"/pedido-resposta/excluir-resposta",
            data	: {'cd_resposta_pedido':cd_resposta_pedido},
            dataType: 'json',
            success	: function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['type']);
                }else{
                    alertMsg(retorno['msg'],retorno['type']);
                    montaGridRespotas();
                }
            }
        });
    });
}
