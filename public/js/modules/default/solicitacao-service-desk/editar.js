$(document).ready(function(){

	$("#bt_excluir").click(function() {
        confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,excluirSolicitacao);
	});
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if( !validaForm('#SOLICITACAO_SERVICE_DESK') ){return false;}
		$("form#SOLICITACAO_SERVICE_DESK").submit();
	});
});

function excluirSolicitacao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/solicitacao-service-desk/excluir",
		data	: {"ni_solicitacao"    :$("#ni_solicitacao").val(),
                   "ni_ano_solicitacao":$("#ni_ano_solicitacao").val(),
                   "cd_objeto"         :$("#cd_objeto").val()},
		success	: function(retorno){
			alertMsg(retorno,2,function(){redireciona()});
		}
	});
}
function redireciona(){
    window.location.href = systemName+"/solicitacao-servico-service-desk";
}