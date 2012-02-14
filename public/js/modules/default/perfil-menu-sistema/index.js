function montaTreeviewPerfilMenuSistema()
{
	$("#treeviewMenu").hide();
	$("#treeviewMenu").html("");
	
	if ($("#cd_perfil_sistema").val() > "-1" && $("#st_perfil_menu").val() > "0") {
		
		// executa requisicao assincrona para buscar menus cadastrados para este perfil
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil-menu-sistema/treeview",
			data	: "cd_perfil="+$("#cd_perfil_sistema").val()+"&st_perfil_menu="+$("#st_perfil_menu").val(),
			success	: function(retorno){
				$("#treeviewMenu").html(retorno).show();
			}
		});
	}
}

$(document).ready(function(){
	$("#cd_perfil_sistema").change(function() {
		montaTreeviewPerfilMenuSistema();
	});
	
	$("#st_perfil_menu").change(function() {
		montaTreeviewPerfilMenuSistema();
	});
});