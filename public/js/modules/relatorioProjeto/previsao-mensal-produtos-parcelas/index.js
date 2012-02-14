$(document).ready(  function() {

	//inicialização da combo
	if( $('#cd_contrato').val() != '0' ){
		comboGerente();
	}

	$('#cd_contrato').change(function(){
		comboGerente();
	});

	$('#cd_profissional_gerente').change(function(){
		comboProjetoGerente();
	});
	
	$('#cd_projeto').change(function(){
		comboProposta();
	});
	
	$('#cd_proposta').change(function(){
		comboParcela();
	});

    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoPrevisaoMensalProdutoParcela') , 'previsao-mensal-produtos-parcelas/generate' );
        return true;
    });
});

function comboGerente()
{
	$.ajax({
		type    : "POST",
		url     : systemName+"/"+systemNameModule+"/previsao-mensal-produtos-parcelas/combo-gerente",
		data    : {"cd_contrato" : $("#cd_contrato").val()},
		success : function(retorno){
			$("#cd_profissional_gerente").html(retorno);
		}
	});
}

function comboProjetoGerente()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/previsao-mensal-produtos-parcelas/combo-projeto-gerente",
		data: "cd_profissional_gerente="+$("#cd_profissional_gerente").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}

function comboProposta()
{
	$.ajax({
		type: "POST",
		url: systemName+"/proposta/pesquisa-proposta",
		data: "cd_projeto="+$("#cd_projeto").val(),
		success: function(retorno){
			$("#cd_proposta").html(retorno);
		}
	});
}

function comboParcela()
{
	$.ajax({
		type: "POST",
		url: systemName+"/criar-parcela/pesquisa-parcela",
		data: "cd_projeto="+$("#cd_projeto").val() + "& cd_proposta="+$("#cd_proposta").val(),
		success: function(retorno){
			$("#cd_parcela").html(retorno);
		}
	});
}