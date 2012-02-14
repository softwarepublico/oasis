$(document).ready(function(){
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/projeto/excluir",
				data	: "cd_projeto="+$("#cd_projeto").val(),
				success	: function(retorno){
					alertMsg(retorno,'',"redirecionaProjetoListar()");
				}
			});
		});
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
        if(!validaForm('#form_descricao_proposta')){ return false;}
        $("form#projeto").submit();
	});
});

function redirecionaProjetoListar()
{
    window.location.href = systemName+"/projeto/listar";
}