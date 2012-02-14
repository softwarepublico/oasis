$(document).ready(function() {
	
	//carrega os dados associativos caso ocorrer um reload e o combo estiver selecionado
	if($('#cmb_medicao_medida').val() != 0 && $('#cmb_medicao_medida').val() != null){
		pesquisaMedicaoMedida();
	}
	
	$('#cmb_medicao_medida').change(function(){
		if($(this).val() != 0){
			pesquisaMedicaoMedida();
		}else{
			limpaSeletores();
		}
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add").click(function() {
		
		//verifica se foi selecionado alguma opção no combo 
		if( $('#cmb_medicao_medida').val() == 0 ){
			return false;
		}
		
		var count = 0;
		var medidas = "["; 
		$('#medida_1 option:selected').each(function() {
			medidas += (medidas == "[") ? $(this).val() : "," + $(this).val();
			count++;
		});
		medidas += "]";

		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_MEDIDA_ASSOCIAR_MEDICAO)
			return false;
		}
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/medicao/associa-medicao-medida",
			data	: "cd_medicao="+$("#cmb_medicao_medida").val()+"&medidas="+medidas,
			success	: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaMedicaoMedida();
			}
		});
	});

	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove").click(function() {
		//verifica se foi selecionado alguma opção nos combos 
		if( $('#cmb_medicao_medida').val() == 0 ){
			return false;
		}
		
		var count = 0;
		var medidas = "["; 
		$('#medida_2 option:selected').each(function() {
			medidas += (medidas == "[") ? $(this).val() : "," + $(this).val();
			count++;
		});
		medidas += "]";
		if(count==0){
			alertMsg(i18n.L_VIEW_SCRIPT_SELECIONE_MEDIDA_DESASSOCIAR_MEDICAO)
			return false;
		}
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/medicao/desassocia-medicao-medida",
			data	: "cd_medicao="+$("#cmb_medicao_medida").val()+"&medidas="+medidas,
			success	: function(retorno){
			    pesquisaMedicaoMedida();
			}
		});
	});
	limpaSeletores();
});


//esta função é carregada inicialmente no index.js
//e cada vez que é adicionanda uma nova medição
function carregaComboMedicaoMedida()
{
	$.ajax({
		type     : 'POST',
		url      : systemName+"/medicao/carrega-combo-medicao-medida",
		dataType : 'json',
		success  : function(retorno){
			$("#cmb_medicao_medida").html(retorno);
		}
	});
}

function pesquisaMedicaoMedida()
{
	var cd_medicao = $('#cmb_medicao_medida').val();
	
	$.ajax({
		type     : 'POST',
		url      : systemName+"/medicao/pesquisa-medicao-medida",
		data     : "cd_medicao="+cd_medicao,
		dataType : 'json',
		success  : function(retorno){
			
            var medida			= retorno[0];
			var medidaAssociada = retorno[1];
			
			$("#medida_1").html(medida);
			$("#medida_2").html(medidaAssociada);
		}
	});
	montaGridMedicaoMedida();
}

function montaGridMedicaoMedida()
{
	var cd_medicao = $('#cmb_medicao_medida').val()
	
	$.ajax({
		type   : "POST",
		url	   : systemName+"/medicao/grid-medicao-medida",
		data   : "cd_medicao="+cd_medicao,
		success: function(retorno){
			$('#gridMedicaoMedida').html(retorno);
			$('#gridMedicaoMedida').show();
		}
	});	
}

function salvaPrioridadeMedida(value)
{
	if(value == '0'){return false;}
	
	$.ajax({
		type   : "POST",
		url	   : systemName+"/medicao/salvar-prioridade-medicao-medida",
		data   : "dados_prioridade="+value+"&cd_medicao="+$('#cmb_medicao_medida').val(),
		success: function(retorno){
			alertMsg(retorno);
		}
	});	
}

function limpaSeletores()
{
	$('#medida_1').empty();
	$('#medida_2').empty();
	$('#gridMedicaoMedida').hide();
}