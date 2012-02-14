$(document).ready(function(){
    $('#ocorrencia-administrativa').triggerTab(1);
	
	//tela contrato X evento
	$("#cd_contrato_evento").change(function(){
		if ($("#cd_contrato_evento").val() != "0") {	
			pesquisaEventosAjax();
		}
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add").click(function() {
	
		var eventos = "["; 
		$('#cd_evento option:selected').each(function() {
			eventos += (eventos == "[") ? $(this).val() : "," + $(this).val();
		});
		eventos += "]";
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/ocorrencia-administrativa/associa-evento-contrato",
			data	: "cd_contrato="+$("#cd_contrato_evento").val()+"&eventos="+eventos,
			success	: function(){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaEventosAjax();
			}
		});
	});
	
	$("#remove").click(function() {
	
		var eventos = "["; 
		$('#cd_evento2 option:selected').each(function() {
			eventos += (eventos == "[") ? $(this).val() : "," + $(this).val();
		});
		eventos += "]";
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/ocorrencia-administrativa/desassocia-evento-contrato",
			data	: "cd_contrato="+$("#cd_contrato_evento").val()+"&eventos="+eventos,
			success	: function(){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaEventosAjax();
			}
		});
	});
	
	//fim tela contrato X evento	
	
	//tela de registro de ocorrência administrativa
	
	$("#cd_contrato_objeto").change(function(){
		if ($("#cd_contrato_objeto").val() != "0") {	
			pesquisaEventosAssociadosAjax();
		}
	});	
	
	$('#alterarOcorrencia' ).hide();
	$('#cancelarOcorrencia').hide();
	
	$('#adicionarOcorrencia').click(function(){
		validaDadosOcorrencia("S");
	});	
	
	$('#alterarOcorrencia').click(function(){
		validaDadosOcorrencia("A");
	});	
	
	$("#cancelarOcorrencia").click(function(){
		limpaDadosOcorrencia();
	});	
	
	//fim tela de registro de ocorrência administrativa	
	
	// tela ocorrências administrativas
	$("#mesOcorrenciaAdministrativa").change(function(){
		if ($("#mesOcorrenciaAdministrativa").val() != "0") {
			gridOcorrenciaAdministrativaAjax();
            apresentaData($("#mesOcorrenciaAdministrativa").val(),$("#anoOcorrenciaAdministrativa").val(),"mesAnoDesc");
		}
	});

	$("#anoOcorrenciaAdministrativa").change(function(){
		if ($("#anoOcorrenciaAdministrativa").val() != "0") {
			gridOcorrenciaAdministrativaAjax();
            apresentaData($("#mesOcorrenciaAdministrativa").val(),$("#anoOcorrenciaAdministrativa").val(),"mesAnoDesc");
		}
	});
	$("#cd_contrato_ocorrencia_adm").change(function(){
		if ($("#cd_contrato_ocorrencia_adm").val() != "0") {	
			gridOcorrenciaAdministrativaAjax();
		}
	});	
	$("#nova_ocorrencia").click(function(){
		abreTabRegistroOcorrencia();
	});	
	// fim tela ocorrências administrativas
});

function ajaxComboContratoObjeto() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/contrato/pesquisa-contrato-ativo-objeto",
		success	: function(retorno){
			$("#cd_contrato_objeto"			).html(retorno);
			$("#cd_contrato_ocorrencia_adm"	).html(retorno);
			$("#cd_contrato_evento"			).html(retorno);
		}
	});
}

// Realiza a pesquisa de eventos por contrato e atualiza os selects.
function pesquisaEventosAjax()
{
	if ($("#cd_contrato_evento").val() != "0") {
		$.ajax({
			type	: "POST",
			url		: systemName+"/ocorrencia-administrativa/pesquisa-eventos",
			data	: "cd_contrato="+$("#cd_contrato_evento").val(),
			dataType: 'json',
			success	: function(retorno){
				event1 = retorno[0];
				event2 = retorno[1];
				$("#cd_evento").html(event1);
				$("#cd_evento2").html(event2);
			}
		});
	}
}

function pesquisaEventosAssociadosAjax()
{		
	$.ajax({
		type	: "POST",
		url		: systemName+"/ocorrencia-administrativa/pesquisa-eventos-associados",
		data	: "cd_contrato="+$("#cd_contrato_objeto").val(),
		dataType: 'json',
		success	: function(retorno){
			$("#cd_evento_ocorrencia").html(retorno[0]);
		}
	});
}

function validaDadosOcorrencia(cond)
{
    if(!validaForm("#formOcorrenciaAdministrativa")){return false}
    if(cond == "S"){
        salvarOcorrencia();
    } else {
        alterarOcorrencia();
    }
}

function salvarOcorrencia()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/ocorrencia-administrativa/salvar-ocorrencia",
		data	: $('#formOcorrenciaAdministrativa :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosOcorrencia();
            gridOcorrenciaAdministrativaAjax();
		}
	});
}

function alterarOcorrencia()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/ocorrencia-administrativa/alterar-ocorrencia",
		data	: $('#formOcorrenciaAdministrativa :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosOcorrencia();
            gridOcorrenciaAdministrativaAjax();
		}
	});
}

function excluirOcorrencia(dt_ocorrencia_administrativa, cd_evento, cd_contrato)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/ocorrencia-administrativa/excluir-ocorrencia",
			data	: "dt_ocorrencia_administrativa="+dt_ocorrencia_administrativa+
                      "&cd_evento="+cd_evento+
                      "&cd_contrato="+cd_contrato,
			success	: function(retorno){
				alertMsg(retorno);
				gridOcorrenciaAdministrativaAjax();
			}
		});
    });
}

function recuperaOcorrencia(dt_ocorrencia_administrativa, cd_evento, cd_contrato)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/ocorrencia-administrativa/recupera-ocorrencia",
		data	: "dt_ocorrencia_administrativa="+dt_ocorrencia_administrativa+
				  "&cd_evento="+cd_evento+
				  "&cd_contrato="+cd_contrato,
		dataType: 'json',
		success	: function(retorno){
			$('#dt_ocorrencia_administrativa').val(retorno['dt_ocorrencia_administrativa']);
			$('#dt_ocorrencia_administrativa').attr("readonly",true);
			$('#cd_contrato_objeto'          ).val(retorno['cd_contrato']);
			$('#cd_contrato_objeto'          ).attr("readonly",true);
			pesquisaEventosAssociadosAjax();
			$('#tx_ocorrencia_administrativa').val(retorno['tx_ocorrencia_administrativa']);
			$('#adicionarOcorrencia'         ).hide();
			$('#alterarOcorrencia'           ).show();
			$('#cancelarOcorrencia'          ).show();
			var t = setTimeout("$('#cd_evento_ocorrencia').val(" + retorno['cd_evento'] + ");", 1000);
			$('#cd_evento_ocorrencia'        ).attr("readonly",true);
			abreTabRegistroOcorrencia();
		}
	});
}

function limpaDadosOcorrencia()
{
	$('#adicionarOcorrencia').show();
	$('#alterarOcorrencia').hide();
	$('#cancelarOcorrencia').hide();
	$('#cd_contrato_objeto').val("");
	$('#cd_evento_ocorrencia').val("");
	$('#dt_ocorrencia_administrativa').val("");
	$('#tx_ocorrencia_administrativa').val("");
	$('#dt_ocorrencia_administrativa').removeAttr("readonly");
	$('#cd_contrato_objeto').removeAttr("readonly");
	$('#cd_evento_ocorrencia').removeAttr("readonly");
	ajaxComboContratoObjeto();
	pesquisaEventosAssociadosAjax();
	fechaTabRegistroOcorrencia();
}

function gridOcorrenciaAdministrativaAjax()
{		
	$.ajax({
		type	: "POST",
		url		: systemName+"/ocorrencia-administrativa/grid-ocorrencia-administrativa",
		data	: "mes="+$("#mesOcorrenciaAdministrativa").val()+
				  "&ano="+$("#anoOcorrenciaAdministrativa").val()+
				  "&cd_contrato="+$("#cd_contrato_ocorrencia_adm").val(),
		success: function(retorno){
			$("#gridOcorrenciaAdministrativa").html(retorno);
			$('#mesAnoDesc'					 ).html($('#mesAnoOcorrenciaAdministrativa').val());
		}
	});
}

function abreTabRegistroOcorrencia()
{
	$("#liRegistroOcorrencia"	  ).show();
	$("#cancelarOcorrencia"		  ).show();
	$('#ocorrencia-administrativa').triggerTab(2);
	
	$('#dt_ocorrencia_administrativa').val(getDateTime());
}

function fechaTabRegistroOcorrencia()
{
	$('#ocorrencia-administrativa').triggerTab(1);
	$("#liRegistroOcorrencia"	  ).css('display','none');
}