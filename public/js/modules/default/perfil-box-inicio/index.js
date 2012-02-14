// Todas as funcionalidades utilizam AJAX e sao implementadas com o Jquery
$(document).ready(function(){

	$("#config_hidden_box").val('N');

	// Ao selecionar uma opcao no combobox, dispara este evento que preenche os selects. 
	$("#cd_perfil_box_inicio").change(function() {
		pesquisaPorObjetoAjax();
	});
	$("#cd_objeto_box_inicio").change(function() {
		pesquisaPorObjetoAjax();
	});

	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_box_inicio").click(function() {
		var boxInicial = "["; 
		$('#cd_box_inicio_combo option:selected').each(function() {
			boxInicial += (boxInicial == "[") ? $(this).val() : "," + $(this).val();
		});
		boxInicial += "]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil-box-inicio/associa-perfil-box-inicio",
			data	: "cd_perfil="+$("#cd_perfil_box_inicio").val()+"&cd_objeto="+$("#cd_objeto_box_inicio").val()+"&boxInicial="+boxInicial,
			success	: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaPorObjetoAjax();
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_box_inicio").click(function() {
		var boxInicial = "["; 
		$('#cd_box_inicio2 option:selected').each(function() {
			boxInicial += (boxInicial == "[") ? $(this).val() : "," + $(this).val();
		});
		boxInicial += "]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/perfil-box-inicio/desassocia-perfil-box-inicio",
			data	: "cd_perfil="+$("#cd_perfil_box_inicio").val()+
					  "&cd_objeto="+$("#cd_objeto_box_inicio").val()+
					  "&boxInicial="+boxInicial,
			success	: function(retorno){
			    pesquisaPorObjetoAjax();
			}
		});
	});
});

// Realiza a pesquisa para atualizar os selects.
function pesquisaPorObjetoAjax()
{
	if($("#cd_perfil_box_inicio").val() != '-1'){
		if($("#cd_objeto_box_inicio").val() != '-1'){
			$.ajax({
				type	: "POST",
				url		: systemName+"/perfil-box-inicio/pesquisa-box-inicial",
				data	:'cd_perfil='+$('#cd_perfil_box_inicio').val()+
						 '&cd_objeto='+$('#cd_objeto_box_inicio').val(),
				dataType: 'json',
				success	: function(retorno){
					box1 = retorno[0];
					box2 = retorno[1];
					$("#cd_box_inicio_combo").html(box1);
					$("#cd_box_inicio2").html(box2);
				}
			});
		}
	}
}