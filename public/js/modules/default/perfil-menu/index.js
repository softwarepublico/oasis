function montaTreeview()
{
	$("#treeviewMenu").hide();
	$("#treeviewMenu").html("");
	
	if ($("#cd_perfil_associar_perfil_objeto").val() > "-1" && $("#cd_objeto_associar_perfil_objeto").val() > "-1") {
		
		// executa requisicao assincrona para buscar menus cadastrados para este perfil
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil-menu/treeview",
			data	: "cd_perfil="+$("#cd_perfil_associar_perfil_objeto").val()+
					  "&cd_objeto="+$("#cd_objeto_associar_perfil_objeto").val(),
			success	: function(retorno){
				$("#treeviewMenu").html(retorno).show();
			}
		});
	}
}

$(document).ready(function(){
	$("#config_hidden_perfil_sistema_objeto_contrato").val('N');
	
	$("#cd_objeto_associar_perfil_objeto").change(function() {
		$("#cd_perfil_associar_perfil_objeto").val("-1");
		$("#treeviewMenu").hide();
		$("#treeviewMenu").html("");
	});

	$("#cd_perfil_associar_perfil_objeto").change(function() {
		if ($("#cd_objeto_associar_perfil_objeto").val() != "-1" && $("#cd_perfil_associar_perfil_objeto").val() != "-1") {
			montaTreeview();
		}
	});
	
});

