$(document).ready(function(){

	limpaFormPapelProfissional();	
    
    $('#cd_area_atuacao_papel_profissional').change(function(){
		if($(this).val() != 0){
	    	montaGridPapelProfissional($(this).val());
		}else{
			$("#gridPapelProfissional").hide().html('');
		}
    });
	
	$("#btn_salvar_papel_profissional").click(function(){
		salvarPapelProfissional();
	});
	$("#btn_alterar_papel_profissional").click(function(){
		salvarPapelProfissional();
	});
	$("#btn_cancelar_papel_profissional").click(function(){
		limpaFormPapelProfissional();
	});
});

function recuperaPapelProfissional(cd_papel_profissional)
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/papel-profissional/recupera-papel-profissional",
        data    : {'cd_papel_profissional':cd_papel_profissional},
        success : function(ret){
            eval( 'var retorno = ' + ret );
            $('#cd_papel_profissional'		).val( retorno.cd_papel_profissional );
            $('#tx_papel_profissional'		).val( retorno.tx_papel_profissional );
            
			$("#btn_salvar_papel_profissional"  ).hide();
			$("#btn_alterar_papel_profissional" ).show();
			$("#btn_cancelar_papel_profissional").show();
        }
    });
}

function montaGridPapelProfissional(cd_area_atuacao_ti)
{
    $.ajax({
        type    : "POST",
        url     : systemName+"/papel-profissional/grid-papel-profissional",
        data    : {'cd_area_atuacao_ti':cd_area_atuacao_ti},
        success : function(retorno){
            $("#gridPapelProfissional").html(retorno);
            $("#gridPapelProfissional").show();
        }
    });
}

function salvarPapelProfissional()
{
    if(!validaForm("#form_papel_profissional")){return false;}
	
    $.ajax({
		type    : 'POST',
		url     : systemName+"/papel-profissional/salvar-papel-profissional",
		data    : $('#form_papel_profissional :input').serialize(),
		dataType: 'json',
		success : function(retorno){
   	        if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaFormPapelProfissional()');
				montaGridPapelProfissional($('#cd_area_atuacao_papel_profissional').val());
			}
		}
	});
}

function excluirPapelProfissional(cd_papel_profissional)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type    : "POST",
			url     : systemName+"/papel-profissional/excluir",
			data    : {'cd_papel_profissional':cd_papel_profissional},
			dataType: 'json',
			success : function(retorno){
				if(retorno[0] == true){
					alertMsg(retorno[1]['msg'],retorno[1]['tipo'],null, 200, 450);
				}else{
					alertMsg(retorno[1]['msg'],retorno[1]['tipo']);
					montaGridPapelProfissional($('#cd_area_atuacao_papel_profissional').val());
				}
			}
		});
	});
}

function limpaFormPapelProfissional()
{
	$('#form_papel_profissional :input' ).not("#cd_area_atuacao_papel_profissional")
										 .val('');
	$("#btn_salvar_papel_profissional"  ).show();
	$("#btn_alterar_papel_profissional" ).hide();
	$("#btn_cancelar_papel_profissional").hide();
}