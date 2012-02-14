var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {

	if($("#cd_contrato").val() != 0){
		getProjeto();
	}

	$("#cd_contrato").change(function() {
		if($(this).val() != 0){
			getProjeto();
		}else{
			$("#cd_projeto" ).html(strOption);
			$("#cd_proposta").html(strOption);
		}
	});

	$("#cd_projeto").change(function() {
		if( $(this).val() != 0 ){
			getProposta();
		}else{
			$("#cd_proposta").html(strOption);
		}
	});

	$("#cd_proposta").change(function() {
		if( $(this).val() != 0 ){
			getDataHistoricoProposta();
		}else{
			$("#dt_historico_proposta").html(strOption);
		}
	});

    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioHistoricoProposta') , 'historico-proposta/historico-proposta' );
        return true;
    });
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/historico-proposta/pesquisa-projeto",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
			$("#cd_proposta").html(strOption);
		}
	});
}

function getProposta()
{
	// Pesquisa Proposta (combo)
	$.ajax({
		type: "POST",
		url: systemName+"/proposta/pesquisa-proposta",
		data: "cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			$("#cd_proposta").html(retorno);
		}
	});
}

function getDataHistoricoProposta()
{
	// Pesquisa Data Histórico Proposta (combo)
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/historico-proposta/pesquisa-data-historico-proposta",
		data: "cd_projeto="+$("#cd_projeto").val()+
              "&cd_proposta="+$("#cd_proposta").val(),
		success: function(retorno){
			$("#dt_historico_proposta").html(retorno);
		}
	});
}