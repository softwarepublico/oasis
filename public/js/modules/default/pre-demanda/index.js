$(document).ready(function() {
	
	$('#bt_cancelar_pre_demanda').click(function () {
		redirecionaPreDemanda();
	});
	
	if ($("#cd_pre_demanda").val() == "") {
		$("#bt_excluir_pre_demanda").hide();
	}else{
		$("#bt_excluir_pre_demanda").show();
	}

	if ($("#ni_solicitacao").val() != "") {
		$("#bt_excluir_pre_demanda").hide();
		$("#bt_salvar_pre_demanda").hide();
	}
	
	$('#bt_salvar_pre_demanda').click(function () {

        var msg = i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO;

		if ($('#cd_unidade').val() == '0'){
			showToolTip(msg,$('#cd_unidade'));
			$('#cd_unidade').focus();
			return false;
		}

		if ($('#cd_objeto_receptor').val() == '-1'){
			showToolTip(msg,$('#cd_objeto_receptor'));
			$('#cd_objeto_receptor').focus();
			return false;
		}

		if($('#tx_pre_demanda').wysiwyg('getContent').val() == ""){
			showToolTip(msg,$('#tx_pre_demanda_editor'));
			var t = setTimeout('removeTollTip()',5000);
			return false;
		}

		var postData = $('#formPreDemanda :input').serialize();
		$.post(systemName+'/pre-demanda/salvar',
		   postData,
		   function(response) {
			   alertMsg(response,'',"redirecionaPreDemanda()");
	    });
	});
	
	$("#bt_excluir_pre_demanda").click(function() {
		confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/pre-demanda/excluir",
				data	: "cd_pre_demanda="+$("#cd_pre_demanda").val(),
				success	: function(retorno){
					if( retorno == 'Erro ao excluir' ){
                        alertMsg(retorno,3,"redirecionaPreDemanda()");
                    } else {
                        alertMsg(retorno,1,"redirecionaPreDemanda()");
                    }
				}
			});
		});
	});
});

function redirecionaPreDemanda()
{
    window.location.href = systemName+"/pre-demanda-painel";
}