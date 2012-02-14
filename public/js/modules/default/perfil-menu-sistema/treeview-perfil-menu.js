$(document).ready(function(){

	var dados = '<?php echo Zend_Json_Encoder::encode($menu);?>';
	
	$("#treeviewPerfilMenuSistema").treeview({
		animated	: "fast",
		collapsed	: false,
		unique		: true,
		persist		: "cookie",
		toggle		: function() {
			window.console && console.log("%o was toggled", this);
		}
	});
	
	$('#treeviewPerfilMenuSistema input:checkbox').click( function() {
	
		//recupera o valor do id
		id 		          = $(this).attr('id');
    	strIdClickedCheck = "#"+id;

    	if ($(strIdClickedCheck).attr('checked')) {
			// grava o menu para o perfil
			associaMenuPerfilSistema(id, "I");			
    	} else {
    		$(strIdClickedCheck).removeAttr('checked');
    		// deleta o menu para o perfil
    		associaMenuPerfilSistema(id, "E");
    	}
	});		
});

function associaMenuPerfilSistema(id, op)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/perfil-menu-sistema/salvar",
		data	: "cd_perfil="+$("#cd_perfil_sistema").val()+
				  "&cd_menu="+id+
				  "&op="+op+
				  "&st_perfil_menu="+$("#st_perfil_menu").val(),
		success	: function(retorno){
		}
	});
}