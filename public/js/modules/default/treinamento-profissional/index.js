$(document).ready(function(){

	//carrega os proficionais caso ja estejam selecionados os combos em um eventual reload da tela
	if(($('#cd_objeto').val() != -1) && ($('#cd_treinamento').val() != 0)){
		pesquisaTreinamentoProfissional();
	}
	
	//ao selecionar uma opção no combo de objeto será 
	$('#cd_objeto').change(function(){
		$('#cd_treinamento').val(0);
		limpaSeletores();
	});
	
	$('#cd_treinamento').change(function(){
		if ($('#cd_objeto').val() != -1) {
			if($('#cd_treinamento').val() == 0){
				limpaSeletores();
				return false;
			}
			if ($('#cd_treinamento').val() != 0) {
				pesquisaTreinamentoProfissional();
			}
		}else{
			if($('#cd_treinamento').val() != 0){
				alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_OBJETO);
				$('#cd_treinamento').val(0);
			}
		}
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_treinamento_profissional").click(function() {
		
		//verifica se foi selecionado alguma opção nos combos 
		if( $('#cd_objeto').val() == -1 && $('#cd_treinamento').val() == 0 || ($('#cd_objeto').val() != -1 && $('#cd_treinamento').val() == 0 ) ){
			return false;
		}
		
		var count = 0;
		var profissionais = "["; 
		$('#cd_profissional_1 option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
			count++;
		});
		profissionais += "]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_PROFISSIONAL_ASSOCIAR);
			return false;
		}
		$.ajax({
			type	: "POST",
			url		: systemName+"/treinamento-profissional/associa-treinamento-profissional",
			data	: "cd_treinamento="+$("#cd_treinamento").val()+"&profissionais="+profissionais,
			success	: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaTreinamentoProfissional();
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_treinamento_profissional").click(function() {
		//verifica se foi selecionado alguma opção nos combos 
		if( $('#cd_objeto').val() == -1 && $('#cd_treinamento').val() == 0 || ($('#cd_objeto').val() != -1 && $('#cd_treinamento').val() == 0 ) ){
			return false;
		}
		var count = 0;
		var profissionais = "["; 
		$('#cd_profissional_2 option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
			count++;
		});
		profissionais += "]";
		if(count==0){
			alertMsg(L_VIEW_SCRIPT_SELECIONE_PROFISSIONAL_DESASSOCIAR);
			return false;
		}
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/treinamento-profissional/desassocia-treinamento-profissional",
			data	: "cd_treinamento="+$("#cd_treinamento").val()+"&profissionais="+profissionais,
			success	: function(retorno){
			    pesquisaTreinamentoProfissional();
			}
		});
	});
}); //END document.ready

function pesquisaTreinamentoProfissional()
{
	var cd_objeto		= $('#cd_objeto'	 ).val();
	var cd_treinamento	= $('#cd_treinamento').val()
	
    $.ajax({
		type     : 'POST',
		url      : systemName+"/treinamento-profissional/pesquisa-treinamento-profissional",
		data     : "cd_objeto="+cd_objeto+"&cd_treinamento="+cd_treinamento,
		dataType : 'json',
		success  : function(retorno){
            var profForaTreinamento = retorno[0];
			var profNoTreinamento   = retorno[1];
			$("#cd_profissional_1").html(profForaTreinamento);
			$("#cd_profissional_2").html(profNoTreinamento);
		}
	});
	montaGridTreinamentoProfissional();
}

function montaGridTreinamentoProfissional()
{
	var cd_objeto		= $('#cd_objeto'	 ).val();
	var cd_treinamento	= $('#cd_treinamento').val()
	
	$.ajax({
		type   : "POST",
		url	   : systemName+"/treinamento-profissional/grid-treinamento-profissional",
		data   : "cd_objeto="+cd_objeto+"&cd_treinamento="+cd_treinamento,
		success: function(retorno){
			$('#gridTreinamentoProfissional').html(retorno);
			$('#gridTreinamentoProfissional').show();
		}
	});
}

function salvarDataTreinamento(id_data)
{
	if( !validaDataTreinamento(id_data) ){return false; }

	var dt_treinamento = $('#'+id_data).val();
	
	//explode o id_data pois nele contem o cd_treinamento e o cd_profissional vindos da grid
	var arr = id_data.split('_');
	
	$.ajax({
		type   : "POST",
		url	   : systemName+"/treinamento-profissional/salvar-data-treinamento",
		data   : "cd_treinamento="+$.trim(arr[0])+
                 "&cd_profissional="+$.trim(arr[1])+
                 "&dt_treinamento="+dt_treinamento,
		success: function(retorno){
			alertMsg(retorno);
		}
	});
	montaGridTreinamentoProfissional();
}

function validaDataTreinamento(id)
{
	if($('#'+id).val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO, $('#'+id));
		return false;
	}	
	return true;
}

function limpaSeletores()
{
	$('#cd_profissional_1'			).empty();
	$('#cd_profissional_2'			).empty();
	$('#gridTreinamentoProfissional').hide();
}