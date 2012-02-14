var statusContrato;

$(document).ready(function(){

    $("#cd_contrato_associar_projeto_contrato").change(function(){
        pesquisaProjetoContrato();
    });

	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_projeto_contrato").click(function() {
        if( statusContrato === 'A' ){

            var count = 0;
            var arrProjetos = "[";
            $('#projetos option:selected').each(function() {
                arrProjetos += (arrProjetos == "[") ? $(this).val() : "," + $(this).val();
                count++;
            });
            arrProjetos += "]";

            if(count==0){
                alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PROJETO_ASSOCIAR_CONTRATO);
                return false;
            }

            $.ajax({
                type: "POST",
                url: systemName+"/associar-projeto-contrato/associa-projeto",
                data: "cd_contrato="+$("#cd_contrato_associar_projeto_contrato").val()+"&projetos="+arrProjetos,
                success: function(){
                    pesquisaProjetoContrato();
                }
            });
        }else{
            alertMsg(i18n.L_VIEW_SCRIPT_CONTRATO_INATIVO_INALTERAVEL);
        }
	});

	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_projeto_contrato").click(function() {
        if( statusContrato === 'A' ){

            var count = 0;
            var arrProjetos = "[";
            $('#projetos_associados option:selected').each(function() {
                arrProjetos += (arrProjetos == "[") ? $(this).val() : "," + $(this).val();
                count++;
            });
            arrProjetos += "]";
            if(count==0){
                alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PROJETO_DESASSOCIAR_CONTRATO);
                return false;
            }
            $.ajax({
                type: "POST",
                url: systemName+"/associar-projeto-contrato/desassocia-projeto",
                data: "cd_contrato="+$("#cd_contrato_associar_projeto_contrato").val()+"&projetos="+arrProjetos,
                success: function(){
                    pesquisaProjetoContrato();
                }
            });
        }else{
            alertMsg(i18n.L_VIEW_SCRIPT_CONTRATO_INATIVO_INALTERAVEL);
        }
	});
});

function configAccordionAssociarProjetoContrato()
{
	if($("#config_hidden_associar_projeto_contrato").val() === 'N'){
		pesquisaProjetoContrato();
		$("#config_hidden_associar_projeto_contrato").val('S');		
	}
}

// Realiza a pesquisa dos projetos associados ao contrato
function pesquisaProjetoContrato()
{
    if ($("#cd_contrato_associar_projeto_contrato").val() != "0") {
        $.ajax({
            type: "POST",
            url: systemName+"/associar-projeto-contrato/pesquisa-projeto",
            data: "cd_contrato="+$("#cd_contrato_associar_projeto_contrato").val(),
            dataType: 'json',
            success: function(retorno){
                projeto1 = retorno[0];
                projeto2 = retorno[1];
                $("#projetos"           ).html(projeto1);
                $("#projetos_associados").html(projeto2);
                statusContrato = retorno[2];
            }
        });
    }else{
        $("#projetos"           ).empty();
        $("#projetos_associados").empty();
	}
}

function verificaContratoAtivo()
{
    var retorno = false;
    alertMsg(i18n.L_VIEW_SCRIPT_VERIFICAR_CONTRATO_ATIVO);
    return retorno;
}
