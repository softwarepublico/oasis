$(document).ready(function(){
	
	if( $("#cd_projeto_reuniao_profissional").val() != 0 ){
		getProfissionaisProjeto();
	}
	
	$("#cd_projeto_reuniao_profissional").change(function(){
		if($(this).val() != 0 ){
			getProfissionaisProjeto();
		} else {
			$("#cd_reuniao_profissional").html("<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>");
			$('#cd_profissional_combo'	).empty();
			$('#cd_profissional2'		).empty();
		}
	});

	// Ao selecionar uma opcao no combobox, dispara este evento que preenche os selects. 
	$("#cd_reuniao_profissional").change(function(){
		if($(this).val() != 0){
			pesquisaPorObjetoAjax();
		}else{
			$('#cd_profissional_combo'	).empty();
			$('#cd_profissional2'		).empty();
		}
	});

	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add").click(function() {
		if( !validaForm("#div_combos_reuniao_profissional") ){return false;}
		
		var count 	      = 0;
		var profissionais = "["; 
		$('#cd_profissional_combo option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
			count++;
		});
		profissionais += "]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PROFISSIONAL_ASSOCIAR);
			return false;
		}
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/reuniao-profissional/associa-reuniao-profissional",
			data	: "cd_reuniao="+$("#cd_reuniao_profissional").val()+"&cd_projeto="+$("#cd_projeto_reuniao_profissional").val()+"&profissionais="+profissionais,
			success	: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaPorObjetoAjax();
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove").click(function() {
		if( !validaForm("#div_combos_reuniao_profissional") ){return false;}
		
		var count 	      = 0;
		var profissionais = "["; 
		$('#cd_profissional2 option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
			count++;
		});
		profissionais += "]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PROFISSIONAL_DESASSOCIAR);
			return false;
		}
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/reuniao-profissional/desassocia-reuniao-profissional",
			data	: "cd_reuniao="+$("#cd_reuniao_profissional").val()+
                      "&cd_projeto="+$("#cd_projeto_reuniao_profissional").val()+
                      "&profissionais="+profissionais,
			success	: function(retorno){
			    pesquisaPorObjetoAjax();
			}
		});
	});
});

// Realiza a pesquisa de profissionais por projeto e atualiza os selects.
function pesquisaPorObjetoAjax()
{		
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao-profissional/pesquisa-profissional",
		data	: "cd_reuniao="+$("#cd_reuniao_profissional").val(),
		dataType: 'json',
		success	: function(retorno){
			prof1 = retorno[0];
			prof2 = retorno[1];
			$("#cd_profissional_combo").html(prof1);
			$("#cd_profissional2"	  ).html(prof2);
		}
	});
}

function getProfissionaisProjeto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao-profissional/pesquisa-reuniao-projeto",
		data	: "cd_projeto="+$("#cd_projeto_reuniao_profissional").val(),
		success	: function(retorno){
			$("#cd_reuniao_profissional").html(retorno);
		}
	});
}