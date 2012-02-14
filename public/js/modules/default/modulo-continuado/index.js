$(document).ready(function(){
   
	if ($("#cd_objeto_modulo_continuado").val() != -1) {
		montaComboProjetoContinuadoModulo();
	}

	$("#cd_objeto_modulo_continuado").change(function() {
		if($(this).val() != -1 ){
			// Pesquisa projeto continuado (combo)
			montaComboProjetoContinuado1Modulo();
		}else{
			$("#cd_projeto_continuado_modulo_continuado").empty();
			$('#gridModuloContinuo').hide();		
		}
	});
	
	$("#cd_projeto_continuado_modulo_continuado").change(function() {
		if ($(this).val() != "0") {
			montaGridModuloContinuado();
		} else {
			$('#gridModuloContinuo').hide();		
		}
	});
	
	// pega evento no onclick do botao
	$("#submitbuttonModuloContinuado").click(function(){
		$("form#modulo_continuado").submit();
	});
});

function montaComboProjetoContinuado1Modulo()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/projeto-continuado/pesquisa-projeto-continuado",
		data	: "cd_objeto="+$("#cd_objeto_modulo_continuado").val(),
		success	: function(retorno){
			$("#cd_projeto_continuado_modulo_continuado").html(retorno);
		}
	});
}

function validaModuloContinuo()
{
	if($('#cd_objeto_modulo_continuado').val() == "-1"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_objeto_modulo_continuado'));
		$('#cd_objeto_modulo_continuado').focus();
		return false;
	}
	if($('#cd_projeto_continuado_modulo_continuado').val() == "0"){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#cd_projeto_continuado_modulo_continuado'));
		$('#cd_projeto_continuado_modulo_continuado').focus();
		return false;
	}
	if($('#tx_modulo_continuado').val() == ""){
		showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO,$('#tx_modulo_continuado'));
		$('#tx_modulo_continuado').focus();
		return false;
	}
	return true;
}

function salvarModuloContinuo()
{
	if(!validaModuloContinuo()){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/modulo-continuado/salvar-modulo-continuado",
		data	: $('#modulo_continuado :input').serialize(),
		success	: function(retorno){
			cancelarModuloContinuo();
			montaGridModuloContinuado();
			alertMsg(retorno);	
		}
	});
}

function alterarModuloContinuo()
{
	if(!validaModuloContinuo()){return false;}
	$.ajax({
		type	: "POST",
		url		: systemName+"/modulo-continuado/alterar-modulo-continuado",
		data	: $('#modulo_continuado :input').serialize(),
		success	: function(retorno){
			cancelarModuloContinuo();
			montaGridModuloContinuado();
			alertMsg(retorno);	
		}
	});
}

function excluirModuloContinuo(cd_modulo_continuado,cd_projeto_continuado)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/modulo-continuado/excluir",
			data	: "cd_modulo_continuado="+cd_modulo_continuado
					 +"&cd_projeto_continuado="+cd_projeto_continuado,
			success	: function(retorno){
				montaGridModuloContinuado();
				alertMsg(retorno);	
			}
		});
	});
}

function cancelarModuloContinuo()
{
    $(".toolTip"				).remove();
	$('#adicionarModuloContinuo').show();
 	$('#alterarModuloContinuo'	).hide();
 	$('#cancelarModuloContinuo'	).hide();
	$('#tx_modulo_continuado'	).val("");
}

function recuperaModuloContinuo(cd_modulo_continuado)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/modulo-continuado/recuperar-modulo-continuado",
		data	: "cd_modulo_continuado="+cd_modulo_continuado,
		dataType: 'json',
		success	: function(retorno){
			$('#adicionarModuloContinuo'	).hide();
		 	$('#alterarModuloContinuo'		).show();
		 	$('#cancelarModuloContinuo'		).show();
			$('#cd_objeto_modulo_continuado').val(retorno[0]['cd_objeto']);
			montaComboProjetoContinuadoModulo();			
		 	var t=setTimeout("$('#cd_projeto_continuado_modulo_continuado').val("+retorno[0]['cd_projeto_continuado']+")",1000);
			$('#tx_modulo_continuado'		).val(retorno[0]['tx_modulo_continuado']);
			$('#cd_modulo_continuado'		).val(retorno[0]['cd_modulo_continuado']);
		}
	});
}

function montaGridModuloContinuado()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/modulo-continuado/grid-modulo-continuado",
		data	: "cd_projeto_continuado="+$("#cd_projeto_continuado_modulo_continuado").val()
				 +"&cd_objeto="+$("#cd_objeto_modulo_continuado").val(),
		success	: function(retorno){
			$('#gridModuloContinuo').html(retorno);
			$('#gridModuloContinuo').show();			
		}
	});
}