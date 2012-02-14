$(document).ready(function(){
	gridContatoEmpresaAjax();
	
	$("#bt_excluir").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
			$.ajax({
				type: "POST",
				url: systemName+"/contato-empresa/excluir",
				data: "cd_contato_empresa="+$("#cd_contato_empresa").val(),
				success: function(retorno){
					alertMsg(retorno,1,"window.location.href = '"+systemName+"/contato-empresa/index/cd_empresa/" + $("#cd_empresa").val()+"'");
				}
			});
		});
	});
	
	$("#cd_empresa").change(function() {
		window.location.href = systemName+"/contato-empresa/index/cd_empresa/"+$("#cd_empresa").val();	
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		if(!validaForm()){return false; }
		$("form#contato_empresa").submit();
	});
});

function gridContatoEmpresaAjax()
{
	if ($("#cd_empresa").val() != "0") {
		$.ajax({
			type: "POST",
			url: systemName+"/contato-empresa/grid-contato-empresa",
			data: "cd_empresa="+$("#cd_empresa").val(),
			success: function(retorno){
				// atualiza a grid
				$("#grid-contato-empresa").html(retorno);
				$("#grid-contato-empresa").show();
			}
		});
	}else{
		$("#grid-contato-empresa").hide();
	}	
}