$(document).ready(function(){
	
	$("#submitbuttonMenu").click(function() {
		salvarMenu();
	});
	
	$("#bt_excluir").click(function() {
		excluirMenu();
	});
});

function salvarMenu()
{
	if(!validaForm("#form_menu")){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/menu/salvar",
		data    : $('#form_menu :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaFormMenu()');
			}
		}
	});
}

function limpaFormMenu(){
	montaTreeview();
	$('#cd_menu'		 ).val("");
	$('#cd_menu_pai'	 ).val("");
	$('#tx_menu'		 ).val("");
	$('#tx_modulo'		 ).val("");
	$('#tx_pagina'		 ).val("");
	$('#submitbuttonMenu').show();
	$('#bt_excluir'		 ).hide();
}

function recuperaMenu(cd_menu)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/menu/recupera-menu",
		data	: "cd_menu="+cd_menu,
		dataType:'json',
		success	: function(retorno){
			$("#container_perfil").triggerTab(2);
			$("#cd_menu").val(retorno['cd_menu']);
			var cd_menu_pai = (retorno['cd_menu_pai'] != null)?retorno['cd_menu_pai']:0;
			$("#cd_menu_pai").val(cd_menu_pai);
			$("#tx_menu"	).val(retorno['tx_menu']);
			$("#tx_modulo"	).val(retorno['tx_modulo']);
			$("#tx_pagina"	).val(retorno['tx_pagina']);
			
			//utilizados se estiver na tab
			$('#submitbuttonMenu').show();
			$('#bt_excluir'		 ).show();
		}
	});
}

function excluirMenu()
{
	confirmMsg('Deseja realmente excluir ?',function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/menu/excluir",
			data	: "cd_menu="+$("#cd_menu").val(),
			dataType: 'json',
			success	: function(retorno){
				if(retorno[0] == true){
					alertMsg(retorno[1],2,'limpaFormMenu()', 200, 470);
				}else{
					alertMsg(retorno[1],1, 'limpaFormMenu()');
				}
			}
		});
	});
}

function montaTreeview()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/menu/treeview-menu",
		success	: function(retorno){
			$('#treeEstrutura').html(retorno);
			$("#red").treeview({
				animated	: "fast",
				collapsed	: true,
				unique		: true,
				persist		: "cookie",
				toggle		: function() {
					window.console && console.log("%o was toggled", this);
				}
			});
		}
	});
}