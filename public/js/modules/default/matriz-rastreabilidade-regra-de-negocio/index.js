var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {

	$("#tx_analise_matriz_rastreabilidade_regra_de_negocio").val('');

	if($("#cd_contrato_matriz_regra_de_negocio").val() != 0){
		getProjetoMatrizRegraDeNegocio();
	}

	$("#cd_contrato_matriz_regra_de_negocio").change(function() {
		if($(this).val() != 0){
			getProjetoMatrizRegraDeNegocio();		
		}else{
			$("#cd_projeto_matriz_regra_de_negocio").html(strOption);
			reiniciaTelaMatrizRegraDeNegocio();
		}
	});
	
    $('#cd_projeto_matriz_regra_de_negocio').change( function(){
        if( $(this).val() > 0 ){
            geraRelatorioMatrizRegraDeNegocio();
        }else{
			reiniciaTelaMatrizRegraDeNegocio();
            return false;
        }
    });
    
    $('#btn_salvar_analise_matriz_rastreabilidade_regra_de_negocio').click( function(){
    	if( !validaForm("#form_matriz_regra_de_negocio") ){ return false; }
    	salvarAnaliseMatrizRegraDeNegocio();
    });

    $("#btn_fechar_analise_matriz_rastreabilidade_regra_de_negocio").click( function(){
    	if( !validaForm("#form_matriz_regra_de_negocio") ){ return false; }
    	fecharAnaliseMatrizRegraDeNegocio();
    });
    
    $('#btn_imprimir_analise_matriz_rastreabilidade_regra_de_negocio').click( function(){
        gerarRelatorio( $('#form_matriz_regra_de_negocio'), 'matriz-rastreabilidade-regra-de-negocio/generate' );
        return true;
    });
});

function getProjetoMatrizRegraDeNegocio()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade/pesquisa-projeto",
		data	: "cd_contrato="+$("#cd_contrato_matriz_regra_de_negocio").val(),
		success	: function(retorno){
			$("#cd_projeto_matriz_regra_de_negocio").html(retorno);
		}
	});
}

function geraRelatorioMatrizRegraDeNegocio()
{
    $('#matriz_regra_de_negocio').hide();
    $('#div_analise_rastreabilidade_regra_de_negocio').hide();
	
    $.ajax({
		type    : "POST",
        data    : "cd_projeto="+$("#cd_projeto_matriz_regra_de_negocio").val(),
		url     : systemName+"/matriz-rastreabilidade-regra-de-negocio/matriz-rastreabilidade-regra-de-negocio",
		success : function(retorno){
			if($.trim(retorno).length > 11){ //qtd caracter da string 'semRegistro'
				$('#matriz_regra_de_negocio').html(retorno);
				$('#matriz_regra_de_negocio').show();
    			
    			getAnaliseMatrizRastreabilidadeRegraDeNegocio();
    			
    			$('#div_analise_rastreabilidade_regra_de_negocio').show();
    		}else{
    			alertMsg(i18n.L_VIEW_SCRIPT_SEM_DEPENDENCIA_REQUISITO_REGRA_NEGOCIO_PROJETO);
    		}
		}
	});
}

function getAnaliseMatrizRastreabilidadeRegraDeNegocio()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade/get-analise-matriz-rastreabilidade",
		data	: "cd_projeto="+$("#cd_projeto_matriz_regra_de_negocio").val()+"&st_analise_matriz_rastreabilidade=RN",
		dataType: 'json',
		success : function(retorno){
			if(retorno[0] == true){
				
				$("#btn_fechar_analise_matriz_rastreabilidade_regra_de_negocio"	).show();
				$("#cd_analise_matriz_rastreabilidade_regra_de_negocio"			).val(retorno[1]['cd_analise_matriz_rastreab']);
	            $("#dt_analise_matriz_rastreabilidade_regra_de_negocio"			).val(retorno[1]['dt_analise_matriz_rastreab']);
	            $("#tx_analise_matriz_rastreabilidade_regra_de_negocio"			).val(retorno[1]['tx_analise_matriz_rastreab']);
	        }
		}
	});
}

function salvarAnaliseMatrizRegraDeNegocio()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade-regra-de-negocio/salvar-analise-matriz-regra-de-negocio",
		data	: $("#form_matriz_regra_de_negocio :input").not("#cd_contrato_matriz_regra_de_negocio").serialize(),
		dataType: 'json',
		success	: function(retorno){
			
			if(retorno[0]['error'] == true){
				alertMsg(retorno[0]['msg'],retorno[0]['errorType']);
			}else{
				alertMsg(retorno[0]['msg'], 1);
				
				if(retorno[1]['insert'] == true){
					$("#dt_analise_matriz_rastreabilidade_regra_de_negocio").val(retorno[1]['dt_analise_matriz_rastreab']);
					$("#cd_analise_matriz_rastreabilidade_regra_de_negocio").val(retorno[1]['cd_analise_matriz_rastreab']);
				}
				
				$("#btn_fechar_analise_matriz_rastreabilidade_regra_de_negocio").show();
			}
		}
	});
}

function fecharAnaliseMatrizRegraDeNegocio()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade-regra-de-negocio/fechar-analise-matriz-rastreabilidade",
		data	: $("#form_matriz_regra_de_negocio :input").not("#cd_contrato_matriz_regra_de_negocio").serialize(),
		dataType: 'json',
		success : function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['errorType']);
			}else{
				alertMsg(retorno['msg']);
				$("#btn_fechar_analise_matriz_rastreabilidade_regra_de_negocio"	).hide();
				
				$("#cd_analise_matriz_rastreabilidade_regra_de_negocio"			).val('');
				$("#dt_analise_matriz_rastreabilidade_regra_de_negocio"			).val('');
				$("#tx_analise_matriz_rastreabilidade_regra_de_negocio"			).val('');
			}
		}
	});
}

function reiniciaTelaMatrizRegraDeNegocio()
{
    $('#matriz_regra_de_negocio'									).hide();
	$('#div_analise_rastreabilidade_regra_de_negocio'				).hide();
	$("#btn_fechar_analise_matriz_rastreabilidade_regra_de_negocio"	).hide();
	$("#cd_analise_matriz_rastreabilidade_regra_de_negocio"			).val('');
	$("#dt_analise_matriz_rastreabilidade_regra_de_negocio"			).val('');
	$("#tx_analise_matriz_rastreabilidade_regra_de_negocio"			).val('');
}