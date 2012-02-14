var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {

	$("#tx_analise_matriz_rastreabilidade_requisito").val('');
	
	if($("#cd_contrato_matriz_requisito").val() != 0){
		getProjetoMatrizRequisito();
	}

	$("#cd_contrato_matriz_requisito").change(function() {
		if($(this).val() != 0){
			getProjetoMatrizRequisito();		
		}else{
			$("#cd_projeto_matriz_requisito").html(strOption);
			reiniciaTelaMatrizRequisito();
		}
	});
	
    $('#cd_projeto_matriz_requisito').change( function(){
        if( $(this).val() > 0 ){
            geraRelatorioMatrizRequisito();
        }else{
			reiniciaTelaMatrizRequisito();
            return false;
        }
    });
    
    $('#btn_salvar_analise_matriz_rastreabilidade_requisito').click( function(){
    	if( !validaForm("#form_matriz_requisito") ){ return false; }
    	salvarAnaliseMatrizRequisito();
    });

    $("#btn_fechar_analise_matriz_rastreabilidade_requisito").click( function(){
    	if( !validaForm("#form_matriz_requisito") ){ return false; }
    	fecharAnaliseMatrizRequisito();
    });
    
    $('#btn_imprimir_analise_matriz_rastreabilidade_requisito').click( function(){
        gerarRelatorio( $('#form_matriz_requisito'), 'matriz-rastreabilidade-requisito/generate' );
        return true;
    });
});

function getProjetoMatrizRequisito()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade/pesquisa-projeto",
		data	: "cd_contrato="+$("#cd_contrato_matriz_requisito").val(),
		success	: function(retorno){
			$("#cd_projeto_matriz_requisito").html(retorno);
		}
	});
}

function geraRelatorioMatrizRequisito()
{
    $('#matriz_requisito').hide();
    $('#div_analise_rastreabilidade_requisito').hide();
    
    $.ajax({
		type    : "POST",
        data    : "cd_projeto="+$("#cd_projeto_matriz_requisito").val(),
		url     : systemName+"/matriz-rastreabilidade-requisito/matriz-rastreabilidade-requisito",
		success : function(retorno){
    	
    		if($.trim(retorno).length > 11){ //qtd caracter da string 'semRegistro'
    			$('#matriz_requisito').html(retorno);
    			$('#matriz_requisito').show();
    			
    			getAnaliseMatrizRastreabilidadeRequisito();
    			
    			$('#div_analise_rastreabilidade_requisito').show();
    		}else{
    			alertMsg(i18n.L_VIEW_SCRIPT_SEM_DEPENDENCIA_REQUISITO_PROJETO);
    		}
		}
	});
}

function getAnaliseMatrizRastreabilidadeRequisito()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade/get-analise-matriz-rastreabilidade",
		data	: "cd_projeto="+$("#cd_projeto_matriz_requisito").val()+"&st_analise_matriz_rastreabilidade=RR",
		dataType: 'json',
		success : function(retorno){
			if(retorno[0] == true){
				$("#btn_fechar_analise_matriz_rastreabilidade_requisito").show();

				$("#cd_analise_matriz_rastreabilidade_requisito"		).val(retorno[1]['cd_analise_matriz_rastreab']);
	            $("#dt_analise_matriz_rastreabilidade_requisito"		).val(retorno[1]['dt_analise_matriz_rastreab']);
	            $("#tx_analise_matriz_rastreabilidade_requisito"		).val(retorno[1]['tx_analise_matriz_rastreab']);
	        }
		}
	});
}

function salvarAnaliseMatrizRequisito()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade-requisito/salvar-analise-matriz-requisito",
		data	: $("#form_matriz_requisito :input").not("#cd_contrato_matriz_requisito").serialize(),
		dataType: 'json',
		success	: function(retorno){
			
			if(retorno[0]['error'] == true){
				alertMsg(retorno[0]['msg'],retorno[0]['errorType']);
			}else{
				alertMsg(retorno[0]['msg'], 1);

				if (retorno[1]['insert'] == true) {
					$("#cd_analise_matriz_rastreabilidade_requisito").val(retorno[1]['cd_analise_matriz_rastreab']);
					$("#dt_analise_matriz_rastreabilidade_requisito").val(retorno[1]['dt_analise_matriz_rastreab']);
				}
				$("#btn_fechar_analise_matriz_rastreabilidade_requisito").show();
			}
		}
	});
}

function fecharAnaliseMatrizRequisito()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade-requisito/fechar-analise-matriz-rastreabilidade",
		data	: $("#form_matriz_requisito :input").not("#cd_contrato_matriz_requisito").serialize(),
		dataType: 'json',
		success : function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['errorType']);
			}else{
				alertMsg(retorno['msg']);
				$("#btn_fechar_analise_matriz_rastreabilidade_requisito").hide();
				
				$("#cd_analise_matriz_rastreabilidade_requisito"		).val('');
				$("#dt_analise_matriz_rastreabilidade_requisito"		).val('');
				$("#tx_analise_matriz_rastreabilidade_requisito"		).val('');
			}
		}
	});
}

function reiniciaTelaMatrizRequisito()
{
    $('#matriz_requisito'										).hide();
	$('#div_analise_rastreabilidade_requisito'					).hide();
	$("#btn_fechar_analise_matriz_rastreabilidade_requisito"	).hide();
	$("#cd_analise_matriz_rastreabilidade_requisito"			).val('');
	$("#dt_analise_matriz_rastreabilidade_requisito"			).val('');
	$("#tx_analise_matriz_rastreabilidade_requisito"			).val('');
}