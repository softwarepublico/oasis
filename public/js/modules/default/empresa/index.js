$(document).ready(function(){

	limpaFormEmpresa();	
	
	// pega evento no onclick dos botões
	$("#btn_salvar_empresa").click(function(){
		salvarEmpresa();
	});
	$("#btn_alterar_empresa").click(function(){
		salvarEmpresa();
	});
	$("#btn_cancelar_empresa").click(function(){
		limpaFormEmpresa();
	});
});

function salvarEmpresa()
{
	if(!validaForm("#formulario_empresa")){return false}
	$.ajax({
		type    : "POST",
		url     : systemName+"/empresa/salvar",
		data    : $('#formulario_empresa :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaFormEmpresa()');
				montaGridEmpresa();
				$("#config_hidden_contratos").val('N');
				
				if( perm_contato_empresa === "S"){
					montaComboEmpresaContatoEmpresa();
				}
			}
		}
	});
}

function limpaFormEmpresa()
{
	$("#formulario_empresa :input"	).val('');
	$("#btn_salvar_empresa"			).show();
	$("#btn_alterar_empresa"		).hide();
	$("#btn_cancelar_empresa"		).hide();

}

function montaGridEmpresa()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/empresa/grid-empresa",
		success	: function(retorno){
			$('#gridEmpresa').html(retorno);
		}
	});
}

function recuperarEmpresa(cd_empresa)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/empresa/recuperar-empresa",
		data	: "cd_empresa="+cd_empresa,
		dataType: 'json',
		success : function(retorno){
			
			$("#cd_empresa"			).val(retorno['cd_empresa']);
			$("#tx_empresa"			).val(retorno['tx_empresa']);
			$("#tx_cnpj_empresa"	).val(retorno['tx_cnpj_empresa']);
			$("#tx_endereco_empresa").val(retorno['tx_endereco_empresa']);
			$("#tx_telefone_empresa").val(retorno['tx_telefone_empresa']);
			$("#tx_fax_empresa"		).val(retorno['tx_fax_empresa']);
			$("#tx_email_empresa"	).val(retorno['tx_email_empresa']);

			$("#btn_salvar_empresa"	 ).hide();
			$("#btn_alterar_empresa" ).show();
			$("#btn_cancelar_empresa").show();		
		}
	});
}

function excluirEmpresa(cd_empresa)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/empresa/excluir",
			data	: "cd_empresa="+cd_empresa,
			dataType: 'json',
			success : function(retorno){
				if(retorno['atualiza'] == true){
					alertMsg(retorno['msg'], retorno['tipo']);
					montaGridEmpresa();
					$("#config_hidden_contratos").val('N');
					if( perm_contato_empresa === "S"){
						montaComboEmpresaContatoEmpresa();
					}
				}else{
					alertMsg(retorno['msg'], retorno['tipo']);
				}
			}
		});
	});
}