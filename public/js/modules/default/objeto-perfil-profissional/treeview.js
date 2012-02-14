$(document).ready(function(){

	var dados = '<?php echo Zend_Json_Encoder::encode($menu);?>;';
	
	$("#treeviewObjetoPerfilProfissional").treeview({
		animated	: "fast",
		collapsed	: false,
		unique		: true,
		persist		: "cookie",
		toggle		: function() {
			window.console && console.log("%o was toggled", this);
		}
	});

	$('#form_perfil_profissional input:checkbox').click( function() {
		id 	              = $(this).attr('id');
    	strIdClickedCheck = "#"+id;
    	if ($(strIdClickedCheck).attr('checked')) {
			// grava o menu para o profissional
			associaMenu(id, "I");			
    	} else {
    		$(strIdClickedCheck).removeAttr('checked');
    		// deleta o menu para o profissional
    		associaMenu(id, "E");
    	}
	});
});

function associaMenu(id, op)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/objeto-perfil-profissional/salvar",
		data	: "cd_objeto="+$("#cd_objeto_perfil_profissional").val()+
				  "&cd_menu="+id+
				  "&op="+op+
				  "&cd_profissional="+$("#cd_profissional_objeto_perfil").val(),
		success	: function(retorno){
		}
	});
}

