var statusContrato;

$(document).ready(function(){

    $("#cd_contrato_associar_item_inventario_contrato").change(function(){
        pesquisaInventarioContrato();
    });

    $("#cd_inventario_associar_item_inventario_contrato").change(function(){
        pesquisaItemInventarioContrato();
    });

	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_item_inventario_contrato").click(function() {
        if( statusContrato === 'A' ){

            var count = 0;
            var arriteminventarios = "[";
            $('#item_inventarios option:selected').each(function() {
                arriteminventarios += (arriteminventarios == "[") ? $(this).val() : "," + $(this).val();
                count++;
            });
            arriteminventarios += "]";

            if(count==0){
                alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ITEM_INVENTARIO_ASSOCIAR_CONTRATO);
                return false;
            }

            $.ajax({
                type: "POST",
                url: systemName+"/associar-item-inventario-contrato/associa-item-inventario",
                data: {'cd_contrato'     : $("#cd_contrato_associar_item_inventario_contrato").val(),
                       'cd_inventario'   : $("#cd_inventario_associar_item_inventario_contrato").val(),
                       'item_inventarios': arriteminventarios},
                dataType: 'json',
                async: false,
                success : function(retorno){
                  if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                  }else{
                    pesquisaItemInventarioContrato();
                  }
                }    
            });
        }else{
            alertMsg(i18n.L_VIEW_SCRIPT_CONTRATO_INATIVO_INALTERAVEL);
        }
	});

	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_item_inventario_contrato").click(function() {
        if( statusContrato === 'A' ){

            var count = 0;
            var arriteminventarios = "[";
            $('#item_inventarios_associados option:selected').each(function() {
                arriteminventarios += (arriteminventarios == "[") ? $(this).val() : "," + $(this).val();
                count++;
            });
            arriteminventarios += "]";
            if(count==0){
                alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_ITEM_INVENTARIO_DESASSOCIAR_CONTRATO);
                return false;
            }
            $.ajax({
                type: "POST",
                url: systemName+"/associar-item-inventario-contrato/desassocia-item-inventario",
                data    : {'cd_contrato'     : $("#cd_contrato_associar_item_inventario_contrato").val(),
                          'item_inventarios': arriteminventarios},
                dataType: 'json',
                success: function(retorno){
                if(retorno['error'] == true){
                    alertMsg(retorno['msg'],retorno['typeMsg']);
                }else{
                    pesquisaItemInventarioContrato();
                 }
                }  
            });
        }else{
            alertMsg(i18n.L_VIEW_SCRIPT_CONTRATO_INATIVO_INALTERAVEL);
        }
	});
});

function configAccordionAssociarItemInventarioContrato()
{
	if($("#config_hidden_associar_item_inventario_contrato").val() === 'N'){
		pesquisaItemInventarioContrato();
		$("#config_hidden_associar_item_inventario_contrato").val('S');		
	}
}

// Realiza a pesquisa dos item_inventarios associados ao contrato
function pesquisaItemInventarioContrato()
{
    if ($("#cd_contrato_associar_item_inventario_contrato").val() != "0") {
        $.ajax({
            type: "POST",
            url: systemName+"/associar-item-inventario-contrato/pesquisa-item-inventario",
            data: "cd_contrato="+$("#cd_contrato_associar_item_inventario_contrato").val(),
            dataType: 'json',
            success: function(retorno){
                item_inventario1 = retorno[0];
                item_inventario2 = retorno[1];
                $("#item_inventarios"           ).html(item_inventario1);
                $("#item_inventarios_associados").html(item_inventario2);
                statusContrato = retorno[2];
            }
        });
    }else{
        $("#item_inventarios"           ).empty();
        $("#item_inventarios_associados").empty();
	}
}

function pesquisaInventarioContrato()
{
   $.ajax({
		type: "POST",
		url: systemName+"/inventario/monta-combo-nome-inventario-contrato",
		data: {"cd_contrato" : $("#cd_contrato_associar_item_inventario_contrato").val()},
		success: function(retorno){
			$('#cd_inventario_associar_item_inventario_contrato').html(retorno);
		}
	});
}

function verificaContratoAtivo()
{
    var retorno = false;
    alertMsg(i18n.L_VIEW_SCRIPT_VERIFICAR_CONTRATO_ATIVO);
    return retorno;
}
