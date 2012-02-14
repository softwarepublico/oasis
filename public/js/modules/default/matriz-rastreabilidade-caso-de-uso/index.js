var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(  function() {

	$("#tx_analise_matriz_rastreabilidade_caso_de_uso").val('');

	if($("#cd_contrato_matriz_caso_de_uso").val() != 0){
		getProjetoMatrizCasoDeUso();
	}

	$("#cd_contrato_matriz_caso_de_uso").change(function() {
		if($(this).val() != 0){
			getProjetoMatrizCasoDeUso();		
		}else{
			$("#cd_projeto_matriz_caso_de_uso").html(strOption);
			reiniciaTelaMatrizCasoDeUso();
		}
	});
	
    $('#cd_projeto_matriz_caso_de_uso').change( function(){
        if( $(this).val() > 0 ){
            geraRelatorioMatrizCasoDeUso();
        }else{
			reiniciaTelaMatrizCasoDeUso();
            return false;
        }
    });
    
    $('#btn_salvar_analise_matriz_rastreabilidade_caso_de_uso').click( function(){
    	if( !validaForm("#form_matriz_caso_de_uso") ){ return false; }
    	salvarAnaliseMatrizCasoDeUso();
    });

    $("#btn_fechar_analise_matriz_rastreabilidade_caso_de_uso").click( function(){
    	if( !validaForm("#form_matriz_caso_de_uso") ){ return false; }
    	fecharAnaliseMatrizCasoDeUso();
    });
    
    $('#btn_imprimir_analise_matriz_rastreabilidade_caso_de_uso').click( function(){
        gerarRelatorio( $('#form_matriz_caso_de_uso'), 'matriz-rastreabilidade-caso-de-uso/generate' );
        return true;
    });
});

function getProjetoMatrizCasoDeUso()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade/pesquisa-projeto",
		data	: "cd_contrato="+$("#cd_contrato_matriz_caso_de_uso").val(),
		success	: function(retorno){
			$("#cd_projeto_matriz_caso_de_uso").html(retorno);
		}
	});
}

function geraRelatorioMatrizCasoDeUso()
{
    $('#matriz_caso_de_uso').hide();
    $('#div_analise_rastreabilidade_caso_de_uso').hide();
	
    $.ajax({
		type    : "POST",
        data    : "cd_projeto="+$("#cd_projeto_matriz_caso_de_uso").val(),
		url     : systemName+"/matriz-rastreabilidade-caso-de-uso/matriz-rastreabilidade-caso-de-uso",
		success : function(retorno){
			if($.trim(retorno).length > 11){ //qtd caracter da string 'semRegistro'
				$('#matriz_caso_de_uso').html(retorno);
				$('#matriz_caso_de_uso').show();
    			
    			getAnaliseMatrizRastreabilidadeCasoDeUso();
    			
    			$('#div_analise_rastreabilidade_caso_de_uso').show();
    		}else{
    			alertMsg(i18n.L_VIEW_SCRIPT_SEM_DEPENDENCIA_REQUISITO_CASO_USO_PROJETO);
    		}
		}
	});
}

function getAnaliseMatrizRastreabilidadeCasoDeUso()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade/get-analise-matriz-rastreabilidade",
		data	: "cd_projeto="+$("#cd_projeto_matriz_caso_de_uso").val()+"&st_analise_matriz_rastreabilidade=RC",
		dataType: 'json',
		success : function(retorno){
			if(retorno[0] == true){
				$("#btn_fechar_analise_matriz_rastreabilidade_caso_de_uso"	).show();
				$("#cd_analise_matriz_rastreabilidade_caso_de_uso"			).val(retorno[1]['cd_analise_matriz_rastreab']);
	            $("#dt_analise_matriz_rastreabilidade_caso_de_uso"			).val(retorno[1]['dt_analise_matriz_rastreab']);
	            $("#tx_analise_matriz_rastreabilidade_caso_de_uso"			).val(retorno[1]['tx_analise_matriz_rastreab']);
	        }
		}
	});
}

function salvarAnaliseMatrizCasoDeUso()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade-caso-de-uso/salvar-analise-matriz-caso-de-uso",
		data	: $("#form_matriz_caso_de_uso :input").not("#cd_contrato_matriz_caso_de_uso").serialize(),
		dataType: 'json',
		success	: function(retorno){
			
			if(retorno[0]['error'] == true){
				alertMsg(retorno[0]['msg'],retorno[0]['errorType']);
			}else{
				alertMsg(retorno[0]['msg'], 1);
				
				if(retorno[1]['insert'] == true){
					$("#dt_analise_matriz_rastreabilidade_caso_de_uso").val(retorno[1]['dt_analise_matriz_rastreab']);
					$("#cd_analise_matriz_rastreabilidade_caso_de_uso").val(retorno[1]['cd_analise_matriz_rastreab']);
				}
				
				$("#btn_fechar_analise_matriz_rastreabilidade_caso_de_uso").show();
			}
		}
	});
}

function fecharAnaliseMatrizCasoDeUso()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/matriz-rastreabilidade-caso-de-uso/fechar-analise-matriz-rastreabilidade",
		data	: $("#form_matriz_caso_de_uso :input").not("#cd_contrato_matriz_caso_de_uso").serialize(),
		dataType: 'json',
		success : function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['errorType']);
			}else{
				alertMsg(retorno['msg']);
				$("#btn_fechar_analise_matriz_rastreabilidade_caso_de_uso"	).hide();
				
				$("#cd_analise_matriz_rastreabilidade_caso_de_uso"			).val('');
				$("#dt_analise_matriz_rastreabilidade_caso_de_uso"			).val('');
				$("#tx_analise_matriz_rastreabilidade_caso_de_uso"			).val('');
			}
		}
	});
}

function reiniciaTelaMatrizCasoDeUso()
{
    $('#matriz_caso_de_uso'										).hide();
	$('#div_analise_rastreabilidade_caso_de_uso'				).hide();
	$("#btn_fechar_analise_matriz_rastreabilidade_caso_de_uso"	).hide();
	$("#cd_analise_matriz_rastreabilidade_caso_de_uso"			).val('');
	$("#dt_analise_matriz_rastreabilidade_caso_de_uso"			).val('');
	$("#tx_analise_matriz_rastreabilidade_caso_de_uso"			).val('');
}