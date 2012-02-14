$(document).ready(function(){
    $('#questao_analise_risco :input').val('');
    $('#btn_salvar_questao_analise_risco').show();
    
    $('#cd_area_atuacao_ti_questao_analise_risco').change(function(){
    	montaComboEtapaQuestaoAnaliseRisco();
    	montaComboAtividadeQuestaoAnaliseRisco('S');
    	montaComboItemRiscoQuestaoAnaliseRisco('S');
        $("#gridQuestaoAnaliseRisco").hide();
    });
    $('#cd_etapa_questao_analise_risco').change(function(){
    	montaComboAtividadeQuestaoAnaliseRisco();
    	montaComboItemRiscoQuestaoAnaliseRisco('S');
    });
    $('#cd_atividade_questao_analise_risco').change(function(){
    	montaComboItemRiscoQuestaoAnaliseRisco();
    });
    $('#cd_item_risco_questao_analise_risco').change(function(){
    	montaGridQuestaoAnaliseRisco();
    });
});

function redirecionaQuestaoAnaliseRisco(cd_questao_analise_risco) {
	$('#questao_analise_risco :input')
		.not('#cd_area_atuacao_ti_questao_analise_risco')
		.not('#cd_etapa_questao_analise_risco')
		.not('#cd_atividade_questao_analise_risco')
		.not('#cd_item_risco_questao_analise_risco')
		.val('');

    $.ajax({
        type    : "POST",
        url     : systemName+"/questao-analise-risco/redireciona-questao-analise-risco",
        data    : {'cd_questao_analise_risco':cd_questao_analise_risco},
        success : function(ret){
            eval( 'var retorno = ' + ret );
            $('#cd_questao_analise_risco').val( retorno.cd_questao_analise_risco );
            $('#tx_questao_analise_risco').val( retorno.tx_questao_analise_risco );
            $('#ni_peso_questao_analise_risco').val( retorno.ni_peso_questao_analise_risco );
            $('#tx_obj_questao_analise_risco').val( retorno.tx_obj_questao_analise_risco );
            
            $('#btn_salvar_questao_analise_risco').hide();
            $('#btn_alterar_questao_analise_risco, #btn_excluir_questao_analise_risco').show();
        }
    });
}

function montaGridQuestaoAnaliseRisco(){
    $.ajax({
        type    : "POST",
        url     : systemName+"/questao-analise-risco/grid-questao-analise-risco",
        data    : "cd_etapa="+$('#cd_etapa_questao_analise_risco').val()
                  +"&cd_atividade="+$('#cd_atividade_questao_analise_risco').val()
                  +"&cd_item_servico="+$('#cd_item_servico_questao_analise_risco').val(),
        success : function(retorno){
            $("#gridQuestaoAnaliseRisco").html(retorno);
            $("#gridQuestaoAnaliseRisco").show();
        }
    });
}

function salvarQuestaoAnaliseRisco(){
	
    if( !validaForm('#questao_analise_risco') ){ return false; }
    
	var postUrl  = systemName+"/questao-analise-risco/salvar-questao-analise-risco";
    var postData = $('#questao_analise_risco :input').serialize(); 
    $.ajax({
		type    : 'POST',
		url     : postUrl,
		data    : postData,
		success : function(ret){
            eval( 'var retorno = ' + ret );
            if( typeof(retorno)=='object' ){
                alertMsg(retorno.msg,retorno.tipo,function(){
                    reiniciaFormQuestaoAnaliseRisco();
                });
            } else {
                alertMsg(retorno,3,function(){
                    reiniciaFormQuestaoAnaliseRisco();
                });
            } 
		}
	});
}

function excluirQuestaoAnaliseRisco() 
{
    if(!validaForm('#questao_analise_risco')){return false;}
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type    : "POST",
			url     : systemName+"/questao-analise-risco/excluir-questao-analise-risco",
			data    : {'cd_questao_analise_risco':$('#cd_questao_analise_risco').val()},
			success : function(ret){
				eval( 'var retorno = ' + ret );
				if( typeof(retorno)=='object' ){
					alertMsg(retorno.msg,retorno.tipo,function(){
						reiniciaFormQuestaoAnaliseRisco();
					});
				} else {
					alertMsg(retorno,3,function(){
						reiniciaFormQuestaoAnaliseRisco();
					});
				}
			}
		});
	}, function(){
			$('#tx_questao_analise_risco').focus();
	   }
	);
}

function reiniciaFormQuestaoAnaliseRisco() {
    $('#cd_questao_analise_risco'			).val('');
    $('#tx_questao_analise_risco'			).val('');
    $('#ni_peso_questao_analise_risco'		).val('');
    $('#tx_obj_questao_analise_risco'       ).val('');
    $('#btn_alterar_questao_analise_risco'	).hide();
    $('#btn_excluir_questao_analise_risco'	).hide();
    $('#btn_salvar_questao_analise_risco'	).show();
    montaGridQuestaoAnaliseRisco();
}

function montaComboEtapaQuestaoAnaliseRisco()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/atividade/monta-combo-etapa",
		data	: "cd_area_atuacao_ti="+$("#cd_area_atuacao_ti_questao_analise_risco").val(),
		success	: function(retorno){
			$('#cd_etapa_questao_analise_risco').html(retorno);
		}
	});
}

function montaComboAtividadeQuestaoAnaliseRisco(zera)
{
    var cd_etapa_questao_analise_risco = "";
    if(zera == 'S'){
        cd_etapa_questao_analise_risco = 0;
    } else {
        cd_etapa_questao_analise_risco = $("#cd_etapa_questao_analise_risco").val();
    }

	$.ajax({
		type	: "POST",
		url		: systemName+"/atividade/combo-atividade",
		data	: "cd_etapa="+cd_etapa_questao_analise_risco,
		success	: function(retorno){
			$('#cd_atividade_questao_analise_risco').html(retorno);
		}
	});	
}

function montaComboItemRiscoQuestaoAnaliseRisco(zera)
{
    var cd_etapa_questao_analise_risco = "";
    var cd_atividade_questao_analise_risco = "";
    if(zera == 'S'){
        cd_etapa_questao_analise_risco = 0;
        cd_atividade_questao_analise_risco = 0;
    } else {
        cd_etapa_questao_analise_risco = $("#cd_etapa_questao_analise_risco").val();
        cd_atividade_questao_analise_risco = $("#cd_atividade_questao_analise_risco").val();
    }
	$.ajax({
		type	: "POST",
		url		: systemName+"/item-risco/combo-item-risco",
		data	: "cd_etapa="+cd_etapa_questao_analise_risco
				 +"&cd_atividade="+cd_atividade_questao_analise_risco,
		success	: function(retorno){
			$('#cd_item_risco_questao_analise_risco').html(retorno);
		}
	});	
}