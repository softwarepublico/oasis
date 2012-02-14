$(document).ready(function(){
	if($("#st_tipo_item_teste").val() != 0){
		montaGridItemTeste();
	}
	
	$("#st_tipo_item_teste").change(function(){
		if($(this).val() != 0 ){
			montaGridItemTeste();
			getNextOrdemByTipo();
		}else{
			$("#gridItemTeste"		).hide();
			$("#ni_ordem_item_teste").val('');
			$("#tx_item_teste"		).val('');
		}
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
        if( !validaForm("form#item_teste") ){return false;}
		$("form#item_teste").submit();
	});
	
	$("#newbutton").click(function(){
		redirecionaItemTeste();
	});
});

function redirecionaItemTeste(cd_item_teste)
{
	if( cd_item_teste ){
        window.location.href = systemName+"/item-teste/editar/cd_item_teste/"+cd_item_teste;
    } else {
	   window.location.href = systemName+"/item-teste";
    }
}

function getNextOrdemByTipo(){
    if( $('#st_tipo_item_teste').val() != 0 ){
    	$.ajax({
    		type	: "POST",
    		url		: systemName+"/item-teste/get-next-ordem-item-teste-por-tipo",
            data	: {'st_tipo_item_teste':$('#st_tipo_item_teste').val()},
    		success	: function(retorno){
    			$("#ni_ordem_item_teste").val(retorno);
    		}
    	});
    } else {
        $("#ni_ordem_item_teste").val('');
    }
} 

function montaGridItemTeste(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/item-teste/grid-item-teste",
        data	: {'st_tipo_item_teste':$('#st_tipo_item_teste').val()},
		success	: function(retorno){
			// atualiza a grid
			$("#gridItemTeste").html(retorno);
			$("#gridItemTeste").show();
		}
	});
}