$(document).ready(function() {
	$("#cd_pre_projeto").change(function() {
		if ($("#cd_pre_projeto").val() == 'nenhum') {

			$("#dados_projeto :input").removeAttr('disabled');
			$("#dados_projeto"		 ).show();
			
		} else {
			$("#dados_projeto :input").val('').attr('disabled','disabled');
			$("#dados_projeto"		 ).hide();
		}
	});
		
	$('#tipo_proposta_e').click(function () {
		$('#cd_projeto'				 ).removeAttr('disabled');
		$('#cd_pre_projeto_evolutivo').removeAttr('disabled');
		$('#cd_pre_projeto'			 ).val('0').attr('disabled', 'disabled');
		$("#dados_projeto"			 ).hide();
		$("#dados_projeto :input"	 ).val('').attr('disabled', 'disabled');
	});
	
	$('#tipo_proposta_n').click(function () {
		$('#cd_projeto'				 ).val('0').attr('disabled', 'disabled');
		$("#tx_projeto"				 ).attr('disabled', 'disabled');
		$("#tx_sigla_projeto"		 ).attr('disabled', 'disabled');
		$("#cd_pre_projeto_evolutivo").attr('disabled', 'disabled');
		$('#cd_pre_projeto'			 ).removeAttr('disabled', 'disabled');
	});
	
	$('#cd_projeto').change(function () {
		$.ajax({
			type	: "POST",
			url		: systemName+"/pre-projeto-evolutivo/lista-pre-projeto-evolutivo",
			data	: "cd_projeto="+$('#cd_projeto').val(),
			success	: function(retorno){
				$('#cd_pre_projeto_evolutivo').html(retorno);
			}
		});
	});
});

function criarPropostaModal(){

	if( !validaModal() ){return false;}

	var postData = $('#formCriarProposta :input').serialize();
	$.post(systemName+'/proposta/criar-proposta',
	   postData,
	   function(response) {
		   alertMsg(response,'',"redirecionaGerenciarProjeto()");
	   }
	);
	return false;
}

function redirecionaGerenciarProjeto(){
    window.location.href = systemName+'/gerenciar-projetos';
}

function fechaProposta(cd_projeto, cd_proposta) {
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/proposta/fechar-proposta",
		data	: "cd_projeto="+cd_projeto+"&cd_proposta="+cd_proposta,
		success	: function(retorno){
			alertMsg(retorno,'',"redirecionaFecharProposta()");
			$('#container-gerenciarProjeto').triggerTab(2);
		}
	});
}

function redirecionaFecharProposta(){
    window.location.href = systemName+'/gerenciar-projetos';
}

function validaModal()
{
	//verifica se um dos dois tipos de proposta foram selecionados
	if( !$("#tipo_proposta_n").attr('checked') && !$("#tipo_proposta_e").attr('checked') ){
		alertMsg(i18n.L_VIEW_SCRIPT_TIPO_PROPOSTA_OBRIGATORIO);
		return false;
	}

	//valida as condições para uma nova proposta
	if( $("#tipo_proposta_n").attr('checked') && $("#cd_pre_projeto").val() == 0 ){
		alertMsg('Pré-Projeto é obrigatório.');
		return false;
	} else if( $("#tipo_proposta_n").attr('checked') && $("#cd_pre_projeto").val() == 'nenhum' ){
		if( $("#tx_projeto").val() == '' ){
			alertMsg(i18n.L_VIEW_SCRIPT_NOME_PROJETO_OBRIGATORIO);
			return false;
		}else if( $("#tx_sigla_projeto").val() == '' ){
			alertMsg(i18n.L_VIEW_SCRIPT_SIGLA_PROJETO_OBRIGATORIO);
			return false;
		}
	}

	//valida as condições para uma proposta evolutiva
	if( $("#tipo_proposta_e").attr('checked') && $("#cd_projeto").val() == 0 ){
		alertMsg(i18n.L_VIEW_SCRIPT_PROJETO_OBRIGATORIO);
		return false;
	}
	return true;
}