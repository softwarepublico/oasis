$(document).ready(function(){
    getSchema($('#tx_adapter').val());
    $('#salvarConfigBanco').show();
    $('#alterarConfigBanco').hide();
    $('#aguarde').hide();
	
    $('#salvarConfigBanco').click(function(){
        if( !validaForm('#configuracao-banco-dados') ){ return false; }
        salvarDadosConfigBanco();
    });
    $('#alterarConfigBanco').click(function(){
        if( !validaForm('#configuracao-banco-dados') ){ return false; }
        salvarDadosConfigBanco();
    });
    $('#tx_adapter').change(function(){
        getSchema($('#tx_adapter').val());
    });
});

function config_form_dicionario_de_dados()
{
    if( $("#config_hidden_dicionairo_dados").val() == "N" ){
        if($('#tx_adapter').val() != '0'){
            if($('#tx_host').val() != ""){
                if($('#tx_username').val() != ""){
                    if($('#tx_password').val() != ""){
                        if($('#tx_password').val() != ""){
                            $('#salvarConfigBanco').hide();
                            $('#alterarConfigBanco').show();
                            montaSelect();
                        }
                    }
                }
            }
        }
    }
    $("#config_hidden_aba_documentar").val('S');
}
	
function getSchema(tx_adapter)
{
	if((tx_adapter == "Pdo_Pgsql") || (tx_adapter == "Pdo_Oci") || (tx_adapter == "Oracle")){
        $('#label_schema').addClass('required');
        $('#schema').show();
    } else {
        $('#label_schema').removeClass('required');
        $('#schema').hide();
        $('#tx_schema').val("");
    }
}

function salvarDadosConfigBanco()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/dicionario-de-dados/salvar-configuracoes-banco",
        data	: $('#configuracao-banco-dados :input').serialize()
                    +"&cd_projeto="+$("#cd_projeto").val(),
        dataType: 'json',
        success	: function(retorno){
            if(retorno['error'] == true){
                alertMsg(retorno['msg'],'2');
            } else {
                validaConfiguracao();
            }
        }
    });
}

function validaConfiguracao()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/dicionario-de-dados/get-conexao-projeto",
        data	: "cd_projeto="+$("#cd_projeto").val(),
        dataType: 'json',
        success	: function(retorno){
            alertMsg(retorno);
            if(retorno['error'] == true){
                alertMsg(retorno['msg'],retorno['type']);
            } else {
                alertMsg(retorno['msg'],retorno['type'],function(){
					montaSelect();
					$('#container-dicionarioDeDados').triggerTab(1);
				});
            }
        }
    });
}