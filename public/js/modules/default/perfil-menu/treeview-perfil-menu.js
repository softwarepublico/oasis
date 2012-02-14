$(document).ready(function(){

	var dados = '<?php echo Zend_Json_Encoder::encode($menu);?>';
	
	$("#red").treeview({
		animated	: "fast",
		collapsed	: false,
		unique		: true,
		persist		: "cookie",
		toggle		: function() {
			window.console && console.log("%o was toggled", this);
		}
	});
	
	$('#treeviewMenu input:checkbox').click( function() {
		//recupera o valor do id
		id 		          = $(this).attr('id');
    	strIdClickedCheck = "#"+id;

    	if ($(strIdClickedCheck).attr('checked')) {
			// grava o menu para o perfil
			associaMenu(id, "I");			
    	} else {
    		$(strIdClickedCheck).removeAttr('checked');
    		// deleta o menu para o perfil
    		associaMenu(id, "E");
    	}
	});		
});

function associaMenu(id, op)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/perfil-menu/salvar",
		data	: "cd_perfil="+$("#cd_perfil_associar_perfil_objeto").val()+
				  "&cd_menu="+id+
				  "&op="+op+
				  "&cd_objeto="+$("#cd_objeto_associar_perfil_objeto").val(),
		success: function(retorno){
		}
	});
}