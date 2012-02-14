$(document).ready(function() {

	//pega o click do botão salvar plano de implantacao
	$("#btn_salvar_plano_implantacao").click(function() {
		salvarPlanoImplantacao();
	});
	
	//pega o click do botão salvar agenda
	$("#btn_salvar_data_agenda").click(function() {
		salvarAgendaImplantacao();
	});
	
	//pega o click do botão aleterar agenda
	$("#btn_alterar_data_agenda").click(function() {
		alterarAgendaImplantacao();
	});
	
	//pega o click do botão cancelar agenda
	$("#btn_cancelar_data_agenda").click(function() {
		limpaInputsAgendaImplantacao();
		habilitaDataAgenda( true );
		
	});
});// EMD document.ready

function verificaStatusAccordionPlanoImplantacao()
{
	if( $("#config_hidden_plano_implantacao").val() === "N" ){
		montaGridAgendaImplantacao();
	}
}

function montaGridAgendaImplantacao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/plano-implantacao/grid-agenda-implantacao",
		data	: "cd_projeto="+$("#cd_projeto").val()+"&cd_proposta="+$("#cd_proposta").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridAgendaImplantacao").html(retorno);
			
			if( $("#config_hidden_plano_implantacao").val() === "N" ){
				$("#config_hidden_plano_implantacao").val("S");
			}
		}
	});
}

function salvarPlanoImplantacao()
{
	if( !validaForm('#div_descricao_plano') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/plano-implantacao/salvar-plano-implantacao",
		data	: $("#div_descricao_plano :input").serialize()+
				  "&cd_projeto="+$('#cd_projeto').val() +
				  "&cd_proposta="+ $('#cd_proposta').val(),
		dataType: 'json',
		success	: function(retorno){
			alertMsg(retorno['msg']);
			if(retorno['acao'] != null){
				$('#acao').val('update');
			}	
		}
	});
}

function salvarAgendaImplantacao()
{
	if( !validaForm('#div_agenda_implantacao') ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/plano-implantacao/salvar-agenda-plano-implantacao",
		data	: $("#div_agenda_implantacao :input").serialize()+
				  "&cd_projeto="+$('#cd_projeto').val() +
				  "&cd_proposta="+ $('#cd_proposta').val(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaInputsAgendaImplantacao();
			montaGridAgendaImplantacao();
		}
	});
}

function recuperaDadosAgendaImplantacao(dt_agenda)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/plano-implantacao/recupera-dados-agenda-implantacao",
		data	: "dt_agenda="+dt_agenda+
				  "&cd_projeto="+$('#cd_projeto').val() +
				  "&cd_proposta="+ $('#cd_proposta').val(),
		dataType: 'json',
		success	: function(retorno){
			habilitaDataAgenda(false);
			
			$("#hidden_dt_agenda_plano_implantacao" ).val(retorno['dt_agenda_plano_implantacao']);
			$("#dt_agenda_plano_implantacao"		).val(retorno['dt_agenda_masked']);
			$("#tx_agenda_plano_implantacao"		).wysiwyg('value',retorno['tx_agenda_plano_implantacao']);
			
			$("#btn_salvar_data_agenda" ).hide();
			$("#btn_alterar_data_agenda").show();
			$("#btn_cancelar_data_agenda").show();
		}
	});
}

function alterarAgendaImplantacao()
{
	if (!validaForm('#div_agenda_implantacao')) {return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName + "/plano-implantacao/alterar-agenda-plano-implantacao",
		data	: $("#div_agenda_implantacao :input").serialize() +
				  "&cd_projeto=" + $('#cd_projeto').val() +
				  "&cd_proposta=" + $('#cd_proposta').val(),
		success	: function(retorno){
			alertMsg(retorno);
			habilitaDataAgenda(true);
			limpaInputsAgendaImplantacao();
			montaGridAgendaImplantacao();
		}
	});
}

function excluirAgendaImplantacao(dt_agenda)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName + "/plano-implantacao/excluir-agenda-plano-implantacao",
			data	: "dt_agenda=" + dt_agenda +
					  "&cd_projeto=" + $('#cd_projeto').val() +
					  "&cd_proposta=" + $('#cd_proposta').val(),
			success	: function(retorno){
				alertMsg(retorno);
				montaGridAgendaImplantacao();
			}
		});
	});
}

function limpaInputsAgendaImplantacao()
{
	$("#div_agenda_implantacao :input"	).val("");
	$("#tx_agenda_plano_implantacao"	).wysiwyg('value','');
	$("#btn_salvar_data_agenda"			).show();
	$("#btn_alterar_data_agenda"		).hide();
	$("#btn_cancelar_data_agenda"		).hide();
}

function habilitaDataAgenda( boolean )
{
	(!boolean) ? $("#dt_agenda_plano_implantacao").attr('disabled','disabled') : $("#dt_agenda_plano_implantacao").removeAttr('disabled'); 
	$("#dt_agenda_plano_implantacao_img").toggleClass('calendario');

}