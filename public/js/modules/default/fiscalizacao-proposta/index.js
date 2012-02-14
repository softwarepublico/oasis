$(document).ready(function() {

	if( errorDonwload == '1' ){
		alertMsg(i18n.L_VIEW_SCRIPT_ERRO_ARQUIVO_NAO_ENCONTRATO, 3, openGridFiscalizacao());
	}else{
		openGridFiscalizacao();
	}

	$('#ni_mes_execucao_parcela').change(function(){
		openGridFiscalizacao();
	});

	$('#ni_ano_execucao_parcela').change(function(){
		openGridFiscalizacao();
	});

	$('#cd_contrato').change(function(){
		openGridFiscalizacao();
	});
});

function openGridFiscalizacao()
{
	if($('#cd_contrato').val() != 0){
		$.ajax({
			type	: "POST",
			url		: systemName+"/fiscalizacao-proposta/grid-fiscalizacao",
			data	: {"ni_mes_execucao_parcela" : $('#ni_mes_execucao_parcela').val(),
				       "cd_contrato"             : $('#cd_contrato').val(),
				       "ni_ano_execucao_parcela" : $('#ni_ano_execucao_parcela').val()},
			success	: function(retorno){
				$("#gridFiscalizacao").html(retorno).show();
			}
		});
	} else {
		$('#gridFiscalizacao').html('').hide();
	}
}

function abreModalFiscalizacao(cd_projeto, cd_proposta, heightModal) {

	var jsonData = {'cd_projeto' : cd_projeto,
					'cd_proposta': cd_proposta
				   };
	eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog( 'dialog_acompanhamento_proposta' );}+', "'+i18n.L_VIEW_SCRIPT_BTN_SALVAR+'": '+function(){salvarAcompanhamentoProposta();}+'};');
    loadDialog({
        id       : 'dialog_acompanhamento_proposta',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_REGISTRA_ACOMPACHAMENTO_PROPOSTA,	// titulo do pop-up
        url      : systemName + '/fiscalizacao-proposta/modal-acompanhamento-proposta',	// url onde encontra-se o phtml
        data     : jsonData,							    // parametros para serem transferidos para o pop-up
        height   : heightModal,								// altura do pop-up
        buttons  : buttons
    });
}

function salvarAcompanhamentoProposta()
{
	if( $('#tx_acompanhamento_proposta').val() == "" ){
		alertMsg(i18n.L_VIEW_SCRIPT_ACOMPANHAMENTO_PROPOSTA_OBRIGATORIO);
		return false;
	}

	$.ajax({
		type	: "POST",
		url		: systemName+"/fiscalizacao-proposta/salvar-acompanhamento-proposta",
		data	: $('#formModalAcompanhamentoProposta :form').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			closeDialog('dialog_acompanhamento_proposta');
		}
	});
}

function abreRelatorioFiscalizacao(cd_projeto,cd_proposta,cd_perfil)
{
	gerarRelatorio( "", 'fiscalizacao-proposta/relatorio-fiscalizacao-produto/cd_projeto/'+cd_projeto+'/cd_proposta/'+cd_proposta+'/cd_perfil/'+cd_perfil);
}