$(document).ready(function(){
    $('#item_controle_baseline :input').val('');
    $('#btn_salvar').show();
    montaGridItemControleBaseline();
});

function redirecionaItemControleBaseline(cd_item_controle_baseline)
{
    $('#item_controle_baseline :input').val('');

    $.ajax({
        type    : "POST",
        url     : systemName+"/item-controle-baseline/redireciona-item-controle-baseline",
        data    : {'cd_item_controle_baseline':cd_item_controle_baseline},
        success : function(ret){
            eval( 'var retorno = ' + ret );
            $('#cd_item_controle_baseline').val( retorno.cd_item_controle_baseline );
            $('#tx_item_controle_baseline').val( retorno.tx_item_controle_baseline );
            
            $('#btn_salvar').hide();
            $('#btn_alterar, #btn_excluir').show();
        }
    });
}

function montaGridItemControleBaseline(){
    $.ajax({
        type    : "POST",
        url     : systemName+"/item-controle-baseline/grid-item-controle-baseline",
        success : function(retorno){
            $("#gridItemControleBaseline").html(retorno);
        }
    });
}

function salvarItemControleBaseline() {
    if(!validaForm()){return false;}
	var postUrl  = systemName+"/item-controle-baseline/salvar-item-controle-baseline";
    var postData = $('#item_controle_baseline :input').serialize(); 
    $.ajax({
		type    : 'POST',
		url     : postUrl,
		data    : postData,
		success : function(ret){
            eval( 'var retorno = ' + ret );
            if( typeof(retorno)=='object' ){
                alertMsg(retorno.msg,retorno.tipo,function(){
                    reiniciaForm();
                });
            } else {
                alertMsg(retorno,3,function(){
                    reiniciaForm();
                });
            } 
		}
	});
}

function excluirItemControleBaseline() {
    if(!validaForm()){return false;}
    confirmMsg(
        i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,
        function(){
            $.ajax({
                type    : "POST",
                url     : systemName+"/item-controle-baseline/excluir-item-controle-baseline",
                data    : {'cd_item_controle_baseline':$('#cd_item_controle_baseline').val()},
                success : function(ret){
                    eval( 'var retorno = ' + ret );
                    if( typeof(retorno)=='object' ){
                        alertMsg(retorno.msg,retorno.tipo,function(){
                            reiniciaForm();
                        });
                    } else {
                        alertMsg(retorno,3,function(){
                            reiniciaForm();
                        });
                    }  
                }
            });
        },
        function(){
            $('#tx_item_controle_baseline').focus();
        }
    );
}

function reiniciaForm() {
    $('#item_controle_baseline :input').val('');
    $('#btn_alterar, #btn_excluir').hide();
    $('#btn_salvar').show();
    montaGridItemControleBaseline();
}