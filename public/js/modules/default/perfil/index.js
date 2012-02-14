$(document).ready(function(){
	limpaFormPerfil();

	$("#submitbutton").click(function(){
		if(!validaForm("#perfil")){return false;}
		salvarPerfil();
	});
	
	$("#btn_alterar_perfil"	).click(function(){
		if(!validaForm("#perfil")){return false;}
		salvarPerfil();
	});
	
	$("#btn_cancelar_perfil").click(function(){
		limpaFormPerfil();
	});
});

function recuperaPerfil(cd_perfil)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/perfil/recupera-perfil",
		data	: "cd_perfil="+cd_perfil,
		dataType: 'json',
		success : function(retorno){
			
			$("#cd_perfil"			).val(retorno['cd_perfil']);
			$("#tx_perfil"			).val(retorno['tx_perfil']);
			
			$("#submitbutton"		).hide();
			$("#btn_alterar_perfil"	).show();
			$("#btn_cancelar_perfil").show();		
		}
	});
}

function montaGridPerfil(){
	$.ajax({
		type	: "POST",
		url		: systemName+"/perfil/grid-perfil",
		success	: function(retorno){
			// atualiza a grid
			$("#gridPerfil").html(retorno);
		}
	});
}

function salvarPerfil()
{
	$.ajax({
		type    : "POST",
		url     : systemName+"/perfil/salvar",
		data    : $('#perfil :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaFormPerfil()');
				montaGridPerfil();
			}
		}
	});
}

function excluirPerfil(cd_perfil)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil/excluir",
			data	: "cd_perfil="+cd_perfil,
			dataType: 'json',
			success : function(retorno){
				if(retorno[0] == true){
					alertMsg(retorno[1],2,null, 200, 470);
				}else{
					alertMsg(retorno[1],1);
					montaGridPerfil();
				}
			}
		});
	});
}

function limpaFormPerfil()
{
	$('#perfil :input'		).val('');
	$("#submitbutton"		).show();
	$("#btn_alterar_perfil"	).hide();
	$("#btn_cancelar_perfil").hide();
}