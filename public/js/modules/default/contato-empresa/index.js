$(document).ready(function(){
	
	limpaFormContatoEmpresa();
	
	$("#cd_empresa_contato_empresa").change(function() {
		if($(this).val() != 0){
			gridContatoEmpresaAjax();
		}else{
			$("#grid-contato-empresa").hide();
		}
	});
	
	$("#btn_salvar_contato_empresa").click(function(){
		salvarContatoEmpresa();
	});
	
	$("#btn_alterar_contato_empresa").click(function(){
		salvarContatoEmpresa();
	});
	
	$("#btn_cancelar_contato_empresa").click(function(){
		limpaFormContatoEmpresa();
	});
	
});

function salvarContatoEmpresa()
{
	if(!validaForm("#form_contato_empresa")){return false; }
	$.ajax({
		type    : "POST",
		url     : systemName+"/contato-empresa/salvar",
		data    : $('#form_contato_empresa :input').serialize(),
		dataType: 'json',
		success: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaFormContatoEmpresa()');
				gridContatoEmpresaAjax();
			}
		}
	});
}

function montaComboEmpresaContatoEmpresa()
{
	$.ajax({
		type: "POST",
		url: systemName+"/contato-empresa/get-empresas",
		success: function(retorno){
			$("#cd_empresa_contato_empresa").html(retorno);
		}
	});
}

function gridContatoEmpresaAjax()
{
	$.ajax({
		type: "POST",
		url: systemName+"/contato-empresa/grid-contato-empresa",
		data: "cd_empresa="+$("#cd_empresa_contato_empresa").val(),
		success: function(retorno){
			// atualiza a grid
			$("#grid-contato-empresa").html(retorno);
			$("#grid-contato-empresa").show();
		}
	});
}

function recuperarContatoEmpresa(cd_contato_empresa)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/contato-empresa/recuperar-contato-empresa",
		data	: "cd_contato_empresa="+cd_contato_empresa,
		dataType: 'json',
		success : function(retorno){
			
			$("#cd_contato_empresa"			).val(retorno['cd_contato_empresa']);
			$("#cd_empresa_contato_empresa"	).val(retorno['cd_empresa']);
			$("#tx_contato_empresa"			).val(retorno['tx_contato_empresa']);
			$("#tx_telefone_contato"		).val(retorno['tx_telefone_contato']);
			$("#tx_email_contato"			).val(retorno['tx_email_contato']);
			$("#tx_celular_contato"			).val(retorno['tx_celular_contato']);
			$("#tx_obs_contato"				).val(retorno['tx_obs_contato']);

			$("#btn_salvar_contato_empresa"	 ).hide();
			$("#btn_alterar_contato_empresa" ).show();
			$("#btn_cancelar_contato_empresa").show();		
		}
	});
}

function excluirContatoEmpresa(cd_contato_empresa)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/contato-empresa/excluir",
			data	: "cd_contato_empresa="+cd_contato_empresa,
			dataType: 'json',
			success : function(retorno){
				if(retorno[0] == true){
					alertMsg(retorno[1],2,null, 200, 470);
				}else{
					alertMsg(retorno[1],1);
					gridContatoEmpresaAjax();
				}
			}
		});
	});
}

function limpaFormContatoEmpresa()
{
	$("#form_contato_empresa :input" ).not("#cd_empresa_contato_empresa").val('');
	$("#btn_salvar_contato_empresa"  ).show();
	$("#btn_alterar_contato_empresa" ).hide();
	$("#btn_cancelar_contato_empresa").hide();
}