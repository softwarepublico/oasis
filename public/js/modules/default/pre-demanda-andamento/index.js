$(document).ready(function(){

    executaPreDemandaAndamentoAjax();
	apresentaData($("#mesAnd").val(),$("#anoAnd").val(),"mesAnoDemandaAndamento");
	
	$("#mesAnd").change(function() {
		executaPreDemandaAndamentoAjax();
		apresentaData($("#mesAnd").val(),$("#anoAnd").val(),"mesAnoDemandaAndamento");
	});
	
	$("#anoAnd").change(function() {
		executaPreDemandaAndamentoAjax();
		apresentaData($("#mesAnd").val(),$("#anoAnd").val(),"mesAnoDemandaAndamento");
	});

	$("#cd_objeto_receptor").change(function() {
		if ($("#cd_objeto_receptor").val() != "0") {
			executaPreDemandaAndamentoAjax();
		}
	});

	// pega evento no onclick do botao
	$("#nova_pre_demanda").click(function(){
		window.location.href = systemName+"/pre-demanda";
	});
});

function executaPreDemandaAndamentoAjax() {
	$.ajax({
		type	: "POST",
		url		: systemName+"/pre-demanda-andamento/pesquisa-pre-demanda-andamento",
		data	: "mes="+$("#mesAnd").val()+
				  "&ano="+$("#anoAnd").val()+
				  "&cd_objeto_receptor="+$("#cd_objeto_receptor").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridPreDemandaAndamento").html(retorno);
		}
	});
}