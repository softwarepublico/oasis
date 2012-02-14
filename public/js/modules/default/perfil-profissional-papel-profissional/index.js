// Todas as funcionalidades utilizam AJAX e sao implementadas com o Jquery
$(document).ready(function(){
	// Ao selecionar uma opcao no combobox, dispara este evento que preenche o combobox de perfil. 
	$("#cd_area_atuacao_associa_perfil_papel_profissional").change(function(){
		if($(this).val() != -1){
			getPerfilProfissionalAjax();
		}
	});

	// Ao selecionar uma opcao no combobox, dispara este evento que preenche os selects. 
	$("#cd_perfil_profissional_associa_perfil_papel_profissional").change(function(){
		pesquisaPorPerfilProfissionalAjax();
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_perfil_papel_profissional").click(function() {

        if(!validaForm('#div_associa_perfil_papel_profissional')){return false}
        
		var count  = 0;
		var papeis = "["; 
		$('#cd_papel_profissional1 option:selected').each(function() {
			papeis += (papeis == "[") ? $(this).val() : "," + $(this).val();
			count++;
		});
		papeis += "]";
		
		if(count == 0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PAPEL_ASSOCIAR_PERFIL);
            return false;
        }
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil-profissional-papel-profissional/associa-perfil-profissional-papel-profissional",
			data	: {"cd_perfil_profissional": $("#cd_perfil_profissional_associa_perfil_papel_profissional").val(),
					   "papeis"                : papeis},
			success	: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaPorPerfilProfissionalAjax();
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_perfil_papel_profissional").click(function() {
		if(!validaForm('#div_associa_perfil_papel_profissional')){return false}
        
		var count  = 0;
		var papeis = "["; 
		$('#cd_papel_profissional2 option:selected').each(function() {
			papeis += (papeis == "[") ? $(this).val() : "," + $(this).val();
			count++;
		});
		papeis += "]";
		
		if(count == 0){
            alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PAPEL_DESASSOCIAR_PERFIL);
            return false;
        }
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil-profissional-papel-profissional/desassocia-perfil-profissional-papel-profissional",
			data	: {"cd_perfil_profissional": $("#cd_perfil_profissional_associa_perfil_papel_profissional").val(),
					   "papeis"                : papeis},
			success	: function(retorno){
			    pesquisaPorPerfilProfissionalAjax();
			}
		});
	});
});

// Realiza a pesquisa de papeis por projeto e atualiza os selects.
function pesquisaPorPerfilProfissionalAjax()
{
	if ($("#cd_perfil_profissional_associa_perfil_papel_profissional").val() != "-1") {
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil-profissional-papel-profissional/pesquisa-papel-profissional",
			data	: {"cd_area_atuacao_ti"     : $("#cd_area_atuacao_associa_perfil_papel_profissional"       ).val(),
					   "cd_perfil_profissional" : $("#cd_perfil_profissional_associa_perfil_papel_profissional").val()},
			dataType: 'json',
			success	: function(retorno){
				$("#cd_papel_profissional1").html(retorno[0]);
				$("#cd_papel_profissional2").html(retorno[1]);
			}
		});
	}
}

function getPerfilProfissionalAjax()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/perfil-profissional-papel-profissional/get-perfil-profissional",
		data	: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_associa_perfil_papel_profissional").val()},
		success	: function(retorno){
			$("#cd_perfil_profissional_associa_perfil_papel_profissional").html(retorno);
		}
	});
}
