var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {

	if($("#cd_contrato").val() != 0){
		getProjeto();
	}

	$("#cd_contrato").change(function() {
		if($(this).val() != 0){
			getProjeto();	
            getStParcelaOrcamento();
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
       if( $(this).val() == 1 && $("#tem_parcela_orcamento").val() == 'S'){
            $("#chk_parcela_orcamento").show();
        }else{
            $("#chk_parcela_orcamento").hide();
        }
    });
    
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorio') , 'documento-proposta/documento-proposta' );
        return true;
    });
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/documento-proposta/pesquisa-projeto",
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

function getStParcelaOrcamento()
{
    $.ajax({
        type: "POST",
        url: systemName+"/"+systemNameModule+"/documento-proposta/pesquisa-st-parcela-orcamento",
        data: "cd_contrato="+$("#cd_contrato").val(),
        dataType: 'json',
        success: function(retorno){
            $("#tem_parcela_orcamento").val(retorno);
        }
    });
}