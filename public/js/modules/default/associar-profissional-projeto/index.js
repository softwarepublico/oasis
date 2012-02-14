// Todas as funcionalidades utilizam AJAX e sao implementadas com o Jquery
$(document).ready(function(){
	
	$('#config_hidden_alocar_profissional').val('N');

	// Ao selecionar uma opcao no combobox, dispara este evento que preenche os selects. 
	$("#cd_papel_profissional").change(function(){
		if($(this).val() != 0 ){
			pesquisaPorProjetoAjax();
		}else{
			$("#cd_profissional" ).empty();
			$("#cd_profissional2").empty();
		}
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add").click(function() {
		var profissionais = "["; 
		$('#cd_profissional option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
		});
		profissionais += "]";
		$.ajax({
			type: "POST",
			url: systemName+"/associar-profissional-projeto/associa-profissional-projeto",
			data: "cd_projeto="+$("#cd_projeto").val()+
				"&cd_papel_profissional="+$("#cd_papel_profissional").val()+
				"&profissionais="+profissionais,
			success: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaPorProjetoAjax();
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove").click(function() {
		var profissionais = "["; 
		$('#cd_profissional2 option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
		});
		profissionais += "]";
		$.ajax({
			type: "POST",
			url: systemName+"/associar-profissional-projeto/desassocia-profissional-projeto",
			data: "cd_projeto="+$("#cd_projeto").val()+
			"&cd_papel_profissional="+$("#cd_papel_profissional").val()+
			"&profissionais="+profissionais,
			success: function(retorno){
			    pesquisaPorProjetoAjax();
			}
		});
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#associar-profissional-projeto").submit();
	});
});

function verificaStatusAccordionAlocarProfissional()
{
	if( $("#config_hidden_alocar_profissional").val() === "N"){
		if($("#cd_papel_profissional").val() != 0 ){
			pesquisaPorProjetoAjax();
		}
	}
	$('#config_hidden_alocar_profissional').val('S');
}

// Realiza a pesquisa de profissionais por projeto e atualiza os selects.
function pesquisaPorProjetoAjax()
{
	if ($("#cd_projeto").val() != "0") {
		$.ajax({
			type: "POST",
			url: systemName+"/associar-profissional-projeto/pesquisa-profissional",
			data: "cd_projeto="+$("#cd_projeto").val()+"&cd_papel_profissional="+$("#cd_papel_profissional").val(),
			dataType: 'json',
			success: function(retorno){
				prof1 = retorno[0];
				prof2 = retorno[1];
				$("#cd_profissional" ).html(prof1);
				$("#cd_profissional2").html(prof2);

				if( $("#config_hidden_alocar_profissional").val() === "N"){
					$("#config_hidden_alocar_profissional").val("S");
				}
                montaGridProfissionalPapel();
			}
		});
	}
}

function montaGridProfissionalPapel()
{
    $.ajax({
        type: "POST",
        url: systemName+"/associar-profissional-projeto/grid-profissional-papel-profissional",
        data: {"cd_projeto":$("#cd_projeto").val()},
        success: function(retorno){
            $("#gridProfissionalPapelProfissional").html(retorno);
        }
    });
}

