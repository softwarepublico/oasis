$(document).ready(function(){

    montaGridProjetoLegado();

    $("#btn_salvar_projeto_legado"  ).click(salvarProjetoLegado);
    $("#btn_alterar_projeto_legado" ).click(alterarProjetoLegado);
    $("#btn_cancelar_projeto_legado").click(limpaProjetoLegado);
});

function montaGridProjetoLegado()
{
	$('#gridProjetoLegado').hide('fast');
	$.ajax({
		type    : "POST",
		url     : systemName+"/projeto-legado/grid-projeto-legado",
		success : function(retorno){
			$('#gridProjetoLegado').html(retorno);
			$('#gridProjetoLegado').show('slow');
		}
	});
}

function salvarProjetoLegado()
{
    
    if( !validaForm("ProjetoLegadoForm") ){ return false; }
    	$.ajax({
		type    : "POST",
		url     : systemName+"/projeto-legado/salvar-projeto-legado",
		data    : $('#ProjetoLegadoForm ').serialize(),
        //dataType: 'json',
        success	: function(retorno){
			alertMsg(
				retorno,
				'',
				function(){
                			//$("#btn_cancelar_projeto_legado").trigger('click');
                            limpaProjetoLegado();
			                montaGridProjetoLegado();
			        }
			);
		}
	});
}

function recuperaProjetoLegado( cd_projeto )
{
    $.ajax({
		type    : "POST",
		url     : systemName+"/projeto-legado/recupera-projeto-legado",
		data    : {"cd_projeto":cd_projeto},
        	dataType: "json",
		success : function(retorno){
			$("#cd_projeto"                ).val(retorno['cd_projeto']);
			$("#tx_projeto"                ).val(retorno['tx_projeto']);
			$("#tx_sigla_projeto"          ).val(retorno['tx_sigla_projeto']);
			$("#tx_contexto_geral_projeto" ).val(retorno['tx_contexto_geral_projeto']);
			$("#tx_escopo_projeto"         ).val(retorno['tx_escopo_projeto']);
			$("#cd_unidade"                ).val(retorno['cd_unidade']);
			$("#tx_gestor_projeto"         ).val(retorno['tx_gestor_projeto']);
			$("#tx_obs_projeto"            ).val(retorno['tx_obs_projeto']);
			$("#st_prioridade_projeto"     ).val(retorno['st_prioridade_projeto']);
			$("#cd_gerente_projeto"        ).val(retorno['cd_profissional_gerente']);
			$("#tx_pub_alcancado_proj"     ).val(retorno['tx_publico_alcancado']);
			$("#st_impacto_projeto-"+retorno['st_impacto_projeto']).attr("checked", true);

			$("#btn_salvar_projeto_legado" ).hide();
			$("#btn_alterar_projeto_legado,#btn_cancelar_projeto_legado" ).show();
		}
	});
}

function alterarProjetoLegado()
{
    if( !validaForm("ProjetoLegadoForm") ){ return false; }
    
    $.ajax({
		type    : "POST",
		url     : systemName+"/projeto-legado/salvar-projeto-legado",
		data    : $("#ProjetoLegadoForm").serialize(),
        //	dataType: "json",
		success : function(retorno){
            
        alertMsg(
				retorno,
				'',
				function(){
                			//$("#btn_cancelar_projeto_legado").trigger('click');
                            limpaProjetoLegado();
			                montaGridProjetoLegado();
			        }
			);    
		}
	});
}

function limpaProjetoLegado()
{
    $("#cd_projeto"                ).val('');
    $("#tx_projeto"                ).val('');
    $("#tx_sigla_projeto"          ).val('');
    $("#tx_contexto_geral_projeto" ).val('');
    $("#tx_escopo_projeto"         ).val('');
    $("#cd_unidade"                ).val('');
    $("#tx_gestor_projeto"         ).val('');
    $("#tx_obs_projeto"            ).val('');
    $("#st_prioridade_projeto"     ).val('');
    $("#cd_gerente_projeto"        ).val('');
    $("#tx_pub_alcancado_proj"     ).val('');
    $("#st_impacto_projeto-I").attr("checked", false);
    $("#st_impacto_projeto-E").attr("checked", false);
    $("#st_impacto_projeto-A").attr("checked", false);

    $("#btn_salvar_projeto_legado"  ).show();
    $("#btn_alterar_projeto_legado,#btn_cancelar_projeto_legado" ).hide();
}



