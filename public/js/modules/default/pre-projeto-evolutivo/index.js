$(document).ready(function(){

	$('#submitbuttonHistorico'			  ).hide();
	$('#adicionarPreProjetoEvolutivo'	  ).show();
	$('#alterarPreProjetoEvolutivo'		  ).hide();
	$('#cancelarPreProjetoEvolutivo'	  ).show();
	$('#excluirPreProjetoEvolutivo'		  ).hide();
	$('#cd_projeto_pre_projeto_evolutivo' ).val("0");
	$('#tx_objetivo_pre_proj_evol'        ).val("");
	
	$('#adicionarPreProjetoEvolutivo').click(function(){
		salvarDadosPreProjetoEvolutivo();
	});
	$('#alterarPreProjetoEvolutivo').click(function(){
		alterarDadosPreProjetoEvolutivo();
	});
	$('#cancelarPreProjetoEvolutivo').click(function(){
		redireciona();
	});
	$('#excluirPreProjetoEvolutivo').click(function(){
		excluirDadosPreProjetoEvolutivo();
	});
	
	if ($("#cd_pre_projeto_evolutivo").val() != ""){
		recuperaDadosPreProjetoEvolutivo();
	}
});

function salvarDadosPreProjetoEvolutivo()
{
	if(!validaPreProjetoEvolutivo()){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/pre-projeto-evolutivo/salvar-dados-pre-projeto-evolutivo",
		data	: $('#pre_projeto_evolutivo :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno,2,'redireciona()');
		}
	});
}

function recuperaDadosPreProjetoEvolutivo()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/pre-projeto-evolutivo/recupera-pre-projeto-evolutivo",
		data	: "cd_pre_projeto_evolutivo="+$("#cd_pre_projeto_evolutivo").val(),
		dataType:'json',
		success	: function(retorno){
			$("#cd_pre_projeto_evolutivo"			).val(retorno['cd_pre_projeto_evolutivo']);
			$("#cd_projeto"							).val(retorno['cd_projeto']);
			$("#tx_pre_projeto_evolutivo"			).val(retorno['tx_pre_projeto_evolutivo']);
			$("#tx_objetivo_pre_proj_evol"	).val(retorno['tx_objetivo_pre_proj_evol']);
			
			//utilizados se estiver na tab
			$('#adicionarPreProjetoEvolutivo'	).hide();
			$('#alterarPreProjetoEvolutivo'		).show();
			$('#cancelarPreProjetoEvolutivo'	).show();
			$('#excluirPreProjetoEvolutivo'		).show();
		}
	});
}

function alterarDadosPreProjetoEvolutivo()
{
	if(!validaPreProjetoEvolutivo()){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/pre-projeto-evolutivo/alterar-dados-pre-projeto-evolutivo",
		data	: $('#pre_projeto_evolutivo :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno,2,'redireciona()');
		}
	});
}

function excluirDadosPreProjetoEvolutivo()
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/pre-projeto-evolutivo/excluir-pre-projeto-evolutivo",
            data	: "cd_pre_projeto_evolutivo="+$('#cd_pre_projeto_evolutivo').val(),
            success	: function(retorno){
                alertMsg(retorno,2,'redireciona()');
            }
        });
    });
}

function validaPreProjetoEvolutivo()
{
	if ($('#cd_projeto').val() == '0'){
		alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PROJETO);
		return false;
	}
	if ($('#tx_objetivo_pre_proj_evol').val() == ''){
		alertMsg(i18n.L_VIEW_SCRIPT_INDIQUE_OBJETIVO_PRE_PROJETO_EVOLUTIVO);
		return false;
	}
	return true;
}

function redireciona()
{
	window.location.href = systemName+"/gerenciar-projetos#preProjeto";
}