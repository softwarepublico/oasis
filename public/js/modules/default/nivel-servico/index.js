$(document).ready(function(){
		
	$("#bt_cancelar_nivel_servico").hide();

	// pega evento no onclick do botao
	$("#bt_salvar_nivel_servico").click(function(){
		salvaNivelServico();
	});

	$("#bt_cancelar_nivel_servico").click(function() {

		$('#nivel_servico :input').not('#cd_objeto_nivel_servico').val('');
		$("#bt_cancelar_nivel_servico").hide();
	});

	$("#cd_objeto_nivel_servico").change(function() {
		if ($("#cd_objeto").val() != "0") {
			montaGridNivelServico();
		} else {
			$("#gridNivelServico").html("");
		}
	});	
});

function montaGridNivelServico(){
    $.ajax({
		type	: "POST",
		url		: systemName+"/nivel-servico/grid-nivel-servico",
		data	: "cd_objeto="+$("#cd_objeto_nivel_servico").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridNivelServico").html(retorno);
		}
    });
}

function salvaNivelServico()
{
	if(!validaForm("#nivel_servico")){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/nivel-servico/salvar",
		data    : $('#nivel_servico :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaDadosNivelServico()');
			}
		}
	});
}

function limpaDadosNivelServico()
{
	$('#nivel_servico :input').not('#cd_objeto_nivel_servico').val('');

	$("#bt_cancelar_nivel_servico").hide();
	montaGridNivelServico();
/*
	$('#cd_nivel_servico'		 ).val("");
	$('#tx_nivel_servico'		 ).val("");
	//$('#st_nivel_servico'		 ).attr('checked', '');
	$('#ni_horas_prazo_execucao' ).val("");
	$('#bt_excluir_nivel_servico').hide();
	montaGridNivelServico();

*/
}

function recuperaNivelServico(cd_nivel_servico)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/nivel-servico/recupera-nivel-servico",
		data	: "cd_nivel_servico="+cd_nivel_servico,
		dataType:'json',
		success	: function(retorno){
			$('#cd_nivel_servico').val(retorno[0]['cd_nivel_servico']);
			$('#tx_nivel_servico').val(retorno[0]['tx_nivel_servico']);
			//$('#st_nivel_servico').attr(retorno[0]['st_nivel_servico']);
			$('#ni_horas_prazo_execucao'  ).val(retorno[0]['ni_horas_prazo_execucao']);
			$('#bt_excluir_nivel_servico' ).show();
			$("#bt_cancelar_nivel_servico").show();
		}
	});
}

function excluiNivelServico(cd_nivel_servico) {
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/nivel-servico/excluir",
			data	: "cd_nivel_servico="+cd_nivel_servico,
			success	: function(retorno){
				alertMsg(retorno,'',"montaGridNivelServico()");
			}
        });
	});
}