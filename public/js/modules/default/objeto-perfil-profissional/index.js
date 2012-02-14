$(document).ready(function(){
	
	$("#config_hidden_objeto_perfil_profissional").val('N');
	
	$("#cd_objeto_perfil_profissional").change(function() {
		if ($("#cd_objeto_perfil_profissional").val() != "-1") {
			pesquisaProfissionalObjetoContrato();
		}else{
			$("#treeviewMenu").empty();
			$("#cd_profissional_objeto_perfil").html("<option value=\"-1\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>");
		}
	});
	
	$("#cd_profissional_objeto_perfil").change(function() {
		if ($("#cd_profissional_objeto_perfil").val() != "-1") {
			montaTreeview();
		}else{
			$("#treeviewMenu").empty();
		}
	});
});

function montaTreeview()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/objeto-perfil-profissional/treeview",
		data	: "cd_objeto="+$("#cd_objeto_perfil_profissional").val()+
				  "&cd_profissional="+$("#cd_profissional_objeto_perfil").val(),
		success	: function(retorno) {
			$("#treeviewMenu").html(retorno).show();
		}
	});
}

function configAccordionAssociarProfissionalPerfil()
{
	if( $("#config_hidden_objeto_perfil_profissional").val() === 'N' ){
		if ($("#cd_objeto_perfil_profissional").val() != "-1") {
			pesquisaProfissionalObjetoContrato();
		}
		$("#config_hidden_objeto_perfil_profissional").val('S');
	}
}

function pesquisaProfissionalObjetoContrato()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/profissional-objeto-contrato/pesquisa-profissional-objeto-contrato",
		data	: "cd_objeto="+$("#cd_objeto_perfil_profissional").val(),
		success	: function(retorno) {
			$("#treeviewMenu").empty();
			$("#cd_profissional_objeto_perfil").html(retorno);
		}
	});
}