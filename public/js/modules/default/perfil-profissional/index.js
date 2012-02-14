$(document).ready(function(){

	limpaFormPerfilProfissional();
	
	$("#cd_area_atuacao_perfil_profissional").change(function() {
		if ($(this).val() != "0") {
			montaGridPerfilProfissional();
		} else {
			$("#gridPerfilProfissional").html("");
		}
	});
	
	$("#btn_salvar_perfil_profissional").click(function(){
		salvarPerfilProfissional();
	});
	
	$("#btn_alterar_perfil_profissional").click(function(){
		salvarPerfilProfissional();
	});
	
	$("#btn_cancelar_perfil_profissional").click(function(){
		limpaFormPerfilProfissional();
	});
});

function salvarPerfilProfissional()
{
	if( !validaForm("#form_perfil_profissional") ){return false; }
	
	$.ajax({
		type    : "POST",
		url     : systemName+"/perfil-profissional/salvar",
		data    : $('#form_perfil_profissional :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaFormPerfilProfissional()');
				montaGridPerfilProfissional();
			}
		}
	});
}

function recuperarPerfilProfissional(cd_perfil_profissional)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/perfil-profissional/recupera-perfil-profissional",
		data	: {"cd_perfil_profissional" : cd_perfil_profissional},
		dataType: 'json',
		success : function(retorno){
			
			$("#cd_perfil_profissional"			    ).val(retorno['cd_perfil_profissional']);
			$("#cd_area_atuacao_perfil_profissional").val(retorno['cd_area_atuacao_ti']);
			$("#tx_perfil_profissional"			    ).val(retorno['tx_perfil_profissional']);
			
			$("#btn_salvar_perfil_profissional"  ).hide();
			$("#btn_alterar_perfil_profissional" ).show();
			$("#btn_cancelar_perfil_profissional").show();
		}
	});
}

function excluirPerfilProfissional(cd_perfil_profissional)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil-profissional/excluir",
			data	: {"cd_perfil_profissional" : cd_perfil_profissional},
			dataType: 'json',
			success : function(retorno){
				if(retorno[0] == true){
					alertMsg(retorno[1]['msg'],retorno[1]['tipo'],null, 200, 450);
				}else{
					alertMsg(retorno[1]['msg'],retorno[1]['tipo']);
					montaGridPerfilProfissional();
				}
			}
		});
	});
}

function montaGridPerfilProfissional()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/perfil-profissional/grid-perfil-profissional",
		data	: {"cd_area_atuacao" : $("#cd_area_atuacao_perfil_profissional").val()},
		success	: function(retorno){
			$("#gridPerfilProfissional").html(retorno);
		}
	});
}

function limpaFormPerfilProfissional()
{
	$("#form_perfil_profissional :input" ).not("#cd_area_atuacao_perfil_profissional").val('');
	$("#btn_salvar_perfil_profissional"  ).show();
	$("#btn_alterar_perfil_profissional" ).hide();
	$("#btn_cancelar_perfil_profissional").hide();
}