$(document).ready(function(){

	$("#cd_perfil_definicao_processo").change(function(){
		if($(this).val() != -1 ){
			pesquisaFuncionalidadeDefinicaoProcessoAjax();
		}else{
			$("#cd_funcionalidade_1" ).empty();
			$("#cd_funcionalidade_2").empty();
		}
	});

	$("#addFuncionalidadeDefinicaoProcesso").click(function() {
		var funcionalidades = "[";
		$('#cd_funcionalidade_1 option:selected').each(function() {
			funcionalidades += (funcionalidades == "[") ? $(this).val() : "," + $(this).val();
		});
		funcionalidades += "]";
		$.ajax({
			type: "POST",
			url: systemName+"/definicao-processo/associa-funcionalidade-definicao-processo",
			data: "st_definicao_processo="+$("#st_definicao_processo").val()+
			"&cd_perfil="+$("#cd_perfil_definicao_processo").val()+
			"&funcionalidades="+funcionalidades,
			success: function(){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaFuncionalidadeDefinicaoProcessoAjax();
			}
		});
	});

	$("#removeFuncionalidadeDefinicaoProcesso").click(function() {
		var funcionalidades = "[";
		$('#cd_funcionalidade_2 option:selected').each(function() {
			funcionalidades += (funcionalidades == "[") ? $(this).val() : "," + $(this).val();
		});
		funcionalidades += "]";
		$.ajax({
			type: "POST",
			url: systemName+"/definicao-processo/desassocia-funcionalidade-definicao-processo",
			data: "st_definicao_processo="+$("#st_definicao_processo").val()+
			"&cd_perfil="+$("#cd_perfil_definicao_processo").val()+
			"&funcionalidades="+funcionalidades,
			success: function(){
			    pesquisaFuncionalidadeDefinicaoProcessoAjax();
			}
		});
	});
});

function pesquisaFuncionalidadeDefinicaoProcessoAjax()
{
	$.ajax({
		type: "POST",
		url: systemName+"/definicao-processo/pesquisa-funcionalidade",
		data: "st_definicao_processo="+$("#st_definicao_processo").val()+
			"&cd_perfil_definicao_processo="+$("#cd_perfil_definicao_processo").val(),
		dataType: 'json',
		success: function(retorno){
			ret1 = retorno[0];
			ret2 = retorno[1];
			$("#cd_funcionalidade_1").html(ret1);
			$("#cd_funcionalidade_2").html(ret2);
		}
	});
}