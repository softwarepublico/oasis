$(document).ready(function(){
	
	if( $("#cd_objeto_reuniao_geral_profissional").val() != '-1' ){
		getProfissionaisObjeto();
	}
	$("#cd_objeto_reuniao_geral_profissional").change(function(){
        $('#cd_profissional_combo').empty();
        $('#cd_profissional2'	  ).empty();
		if($(this).val() != '-1' ){
			getProfissionaisObjeto();
		} else {
			$("#cd_reuniao_geral_profissional").html("<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>");
		}
	});

	// Ao selecionar uma opcao no combobox, dispara este evento que preenche os selects. 
	$("#cd_reuniao_geral_profissional").change(function(){
		if($(this).val() != 0){
			pesquisaPorObjetoAjax();
		}else{
			$('#cd_profissional_combo').empty();
			$('#cd_profissional2'	  ).empty();
		}
	});


	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add").click(function() {
		if( !validaForm("#div_combos_reuniao_geral_profissional") ){return false;}
		
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
			url		: systemName+"/reuniao-geral-profissional/associa-reuniao-geral-profissional",
			data	: "cd_reuniao_geral="+$("#cd_reuniao_geral_profissional").val()+"&cd_objeto="+$("#cd_objeto_reuniao_geral_profissional").val()+"&profissionais="+profissionais,
			success	: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaPorObjetoAjax();
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove").click(function() {
		if( !validaForm("#div_combos_reuniao_geral_profissional") ){return false;}
		
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
			url		: systemName+"/reuniao-geral-profissional/desassocia-reuniao-geral-profissional",
			data	: "cd_reuniao_geral="+$("#cd_reuniao_geral_profissional").val()+
                      "&cd_objeto="+$("#cd_objeto_reuniao_geral_profissional").val()+
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
		url		: systemName+"/reuniao-geral-profissional/pesquisa-profissional",
		data	: "cd_reuniao_geral="+$("#cd_reuniao_geral_profissional").val(),
		dataType: 'json',
		success	: function(retorno){
			prof1 = retorno[0];
			prof2 = retorno[1];
			$("#cd_profissional_combo").html(prof1);
			$("#cd_profissional2"	  ).html(prof2);
		}
	});
}

function getProfissionaisObjeto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/reuniao-geral-profissional/pesquisa-reuniao-geral-objeto",
		data	: "cd_objeto="+$("#cd_objeto_reuniao_geral_profissional").val(),
		success	: function(retorno){
			$("#cd_reuniao_geral_profissional").html(retorno);
		}
	});
}