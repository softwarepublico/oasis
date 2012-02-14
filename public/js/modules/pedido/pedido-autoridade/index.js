$(document).ready(function(){
    montaGridAutoridades();
});

function montaGridAutoridades()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-autoridade/grid-autoridade",
		success	: function(retorno){
			$("#grid_autoridade").html(retorno);
		}
	});
}

function gravaDesignacao(st_autoridade, cd_usuario_pedido)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-autoridade/salvar-autoridade",
        data    : {'cd_usuario_pedido': cd_usuario_pedido,
                   'st_autoridade'    : st_autoridade},
        dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] === true){
                alertMsg(retorno['msg'],retorno['type']);
            }else{
                alertMsg(retorno['msg'],retorno['type']);
                montaGridAutoridades();
            }
		}
	});
}