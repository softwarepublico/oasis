$(document).ready(  function() {
    if($("#cd_contrato").val() != 0){
        getProjeto();
    }

    $("#cd_contrato").change(function() {
        if($(this).val() != 0){
            getProjeto();
        }
    });

    $("#cd_projeto").change(function() {
        if( $(this).val() != 0 ){
            montaGridDocumentacaoProjeto();
        }
    });
});

function getProjeto()
{
    $.ajax({
        type: "POST",
        url: systemName+"/"+systemNameModule+"/documentacao-projeto/pesquisa-projeto",
        data: "cd_contrato="+$("#cd_contrato").val(),
        success: function(retorno){
            $("#cd_projeto").html(retorno);
        }
    });
}

function montaGridDocumentacaoProjeto()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/"+systemNameModule+"/documentacao-projeto/grid-documentacao",
        data	: "cd_projeto="+$("#cd_projeto").val(),
        success	: function(retorno){
            $('#gridDocumentacaoProjeto').html(retorno);
        }
    });
}