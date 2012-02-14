$(document).ready(function(){
	$('#btn_salvar_conhecimento').click(function(){
		salvarConhecimento();
	});
	
	$('#btn_pesq_conhecimento').click(function(){
		pesquisaDadosGrid();
	});
});

function salvarConhecimento()
{
	if(!validaForm()){return false}
	$.ajax({
		type: "POST",
		url: systemName+"/base-conhecimento/salvar-base-conhecimento",
		data: $('#formBaseConhecimento :input').serialize(),
		success: function(retorno){
			alertMsg(retorno);
			$('#formBaseConhecimento :input')
			.not('#tx_problema')
			.not('#tx_solucao')
			.val('');

			$("#tx_problema").wysiwyg('value','');
			$("#tx_solucao").wysiwyg('value','');
		}
	});
}

function pesquisaDadosGrid()
{
	$.ajax({
		type: "POST",
		url: systemName+"/base-conhecimento/grid-base-conhecimento",
		data: "cd_area_conhecimento="+$('#cd_area_conhecimento_pesq').val()
		      +"&tx_assunto="+$('#tx_assunto_pesq').val()
		      +"&tipo_consulta="+$("input[name=tipo_consulta]:checked").val(),
		success: function(retorno){
			$('#gridPesquisaBaseConhecimento').html(retorno);
		}
	});
}

function abreTabBaseConhecimentoSolucao(cd_base_conhecimento)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/base-conhecimento/tab-base-conhecimento-solucao",
		data	: "cd_base_conhecimento="+cd_base_conhecimento,
		success: function(retorno){
			$("#base-conhecimento-solucao"	 ).html(retorno);
			$('#container-baseConhecimento'  ).triggerTab(3);
			$("#li-base-conhecimento-solucao").css("display", "");
		}
	});
}