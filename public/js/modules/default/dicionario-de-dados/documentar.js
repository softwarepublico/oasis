$(document).ready(function(){
    $('#alterarDocumentacao').hide();
    $('#tx_tabelas').change(function(){
        montaColunas();
    });
	
    $('#salvarDocumentacao').click(function(){
        salvarDocumentacao();
    });
});

function verificaConfiguracaoAbaDocumentar()
{
    if( $("#config_hidden_aba_documentar").val() != "N" ){
        montaSelect();
    }
}

function montaSelect()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/dicionario-de-dados/monta-select-tabelas",
        data	: "cd_projeto="+$("#cd_projeto").val(),
        dataType: 'json',
        success	: function(retorno){
            if(retorno['error'] == true){
                alertMsg(retorno['msg'],retorno['type']);
            } else {
                $('#tx_tabelas').html(retorno['tabelas']);
            }
        }
    });
}

function montaColunas()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/dicionario-de-dados/recupera-dados-tabela-coluna",
        data	: "cd_projeto="+$("#cd_projeto").val()
				  +"&tx_schema="+$("#tx_schema").val()
		          +"&tx_tabelas="+$("#tx_tabelas").val(),
        success	: function(retorno){
            $('#dadosColunas').html(retorno);
			
            if( $("#config_hidden_aba_documentar").val() === "N" ){
                $("#config_hidden_aba_documentar").val("S");
            }
        }
    });
}

function salvarDocumentacao()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/dicionario-de-dados/salvar-documentacao",
        data	: $('#formDocumentar :input').serialize(),
        success	: function(retorno){
            retorno = parseInt(retorno);
            if(retorno == 1){
                alertMsg(i18n.L_VIEW_SCRIPT_ERRO_CADASTRO_DADOS);
            } else {
                alertMsg(i18n.L_VIEW_SCRIPT_DADOS_CADASTRADOS_SUCESSO);
            }
        }
    });
}