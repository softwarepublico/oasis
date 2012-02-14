$(document).ready(function(){
	
	$('#cd_tipo_conhecimento').change(function(){
		if($('#cd_tipo_conhecimento').val() != 0){
			pesquisaTipoConhecimentoEspecificoAjax();
		} else {
			pesquisaTipoConhecimentoAjax();
		}
		
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#addTipoConhecimento").click(function() {
		var conhecimento = "[\"";
		$('#cd_conhecimento1 option:selected').each(function() {
			conhecimento += (conhecimento == "[\"") ? $(this).val() : "\",\"" + $(this).val();
		});
		conhecimento += "\"]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/dados-tecnicos/associa-tipo-conhecimento-projeto",
			data	: "cd_projeto="+$("#cd_projeto").val()+"&conhecimento="+conhecimento,
			success	: function(retorno){
				if($('#cd_tipo_conhecimento').val() != 0){
					pesquisaTipoConhecimentoEspecificoAjax();
				} else {
					pesquisaTipoConhecimentoAjax();
				}
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#removeTipoConhecimento").click(function() {
		var conhecimento2 = "[\"";
		$('#cd_conhecimento2 option:selected').each(function() {
			conhecimento2 += (conhecimento2 == "[\"") ? $(this).val() : "\",\"" + $(this).val();
		});
		conhecimento2 += "\"]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/dados-tecnicos/desassocia-tipo-conhecimento-projeto",
			data	: "cd_projeto="+$("#cd_projeto").val()+"&conhecimento2="+conhecimento2,
			success	: function(retorno){
			    if($('#cd_tipo_conhecimento').val() != 0){
					pesquisaTipoConhecimentoEspecificoAjax();
				} else {
					pesquisaTipoConhecimentoAjax();
				}
			}
		});
	});
});

// Realiza a pesquisa de profissionais por projeto e atualiza os selects.
function pesquisaTipoConhecimentoAjax()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/dados-tecnicos/pesquisa-tipos-conhecimento",
		data	: "cd_projeto="+$("#cd_projeto").val(),
		dataType: 'json',
		success	: function(retorno){
			conh1 = retorno[0];
			conh2 = retorno[1];
			$("#cd_conhecimento1").html(conh1);
			$("#cd_conhecimento2").html(conh2);
			
			configuraInicializacaoAccordion();
		}
	});
}

function pesquisaTipoConhecimentoEspecificoAjax()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/dados-tecnicos/pesquisa-tipos-conhecimento-especifico",
		data	: "cd_projeto="+$("#cd_projeto").val()
				 +"&cd_tipo_conhecimento="+$("#cd_tipo_conhecimento").val(),
		dataType: 'json',
		success	: function(retorno){
			conh1 = retorno[0];
			conh2 = retorno[1];
			$("#cd_conhecimento1").html(conh1);
			$("#cd_conhecimento2").html(conh2);
			
			configuraInicializacaoAccordion();
		}
	});
}

function configuraInicializacaoAccordion()
{
	if( $("#config_hidden_dados_tecnicos_aba_conhecimento").val() === "N" ){
		$("#config_hidden_dados_tecnicos_aba_conhecimento").val("S");
	}
}