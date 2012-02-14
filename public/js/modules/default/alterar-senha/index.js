$(document).ready(function() {
	$('#btn_salvar_senha').click(function(){
		//if( !validaForm() ){ return false;}
		if($('#tx_senha_confirmada').val() == $('#tx_nova_senha').val()){
			salvarAlteraSenha();
		} else {
			alertMsg(i18n.L_VIEW_SCRIPT_CONFIRMACAO_SENHA_INCORRETA);
		}
	});
	
});

function salvarAlteraSenha()
{
	$.ajax({
		type: "POST",
		url: systemName+"/alterar-senha/salvar-alterar-senha",
		data: $('#formAlterarSenha :input').serialize(),
		success: function(retorno){
			alertMsg(retorno,'',"redirecionaAlterarSenha()");
		}
	});
}

/**
 * redireciona a tela
 */
function redirecionaAlterarSenha() {
    window.location.href = systemName+"/auth/logout";
}