$(document).ready(function(){
	/*$('#btn_fechar_demanda_profissional').click(function(){
		$.ajax({
			type: "POST",
			url: systemName+"/solicitacao-tipo-demanda/fecha-demanda-gerente",
			data: $('#tabSolicitacaoTipoDemandaExecultor :input').serialize(),
			success: function(retorno){
				alertMsg(retorno);
				$('#containerSolicitacaoTipoDemanda').triggerTab(1);
				$("#li-profissional-designado").css("display", "none");
				ajaxGridSolicitacaoTipoDemanda();
				ajaxGridSolicitacaoTipoDemandaExecutada();
			}
		});
	});*/
	$('#btn_cancelar_demanda_profissional').click(function(){
		var tab_origem = parseInt($('#tab_origem').val());
		$('#container-solicitacao-servico').triggerTab(tab_origem);
		$("#li-profissional-designado").css("display", "none");
	});
});
/*
function motivoFechamento(num)
{
	if($('#nivelServico_'+num).attr('checked') == true){
		$('#divMotivo_'+num).css('display','');
	} else {
		$('#divMotivo_'+num).css('display','none');
		$('#tx_motivo_fechamento_'+num).val("");
	}
}
*/