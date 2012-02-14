$(document).ready(function(){
	//esconde  o botão para alterar a parcela
	$("#btnAlterarParcela").hide();
	//captura o click do botão para alterar a parcela
	$("#btnAlterarParcela").click(function(){
		//Variavel para a quantidade de parcela
		var proxima_parcela = $("#proxima_parcela_hidden").val();
		//Variavel para contabilizar as horas disponiveis
		var horas_restantes = 0;
		//condição que verifica se o campo de horas da proposta esta preenchido
		if(($("#horas_parcela").val() != "") && ($("#mes").val() != 0) && ($("#ano").val() != 0)){
			//condição que verifica se o projeto possui horas
			if($("#cd_horas_disponivel").val() > 0){
				//atualiza as horas disponiveis do projeto
				horas_restantes = ($("#cd_horas_disponivel").val()-$("#horas_parcela").val());
				//Verifica se a contabilização ultrapassa as horas disponiveis
				if(horas_restantes >= 0){
					$.ajax({
						type: "POST",
						url	: systemName+"/criar-parcela/alterar-parcela",
						data: "cd_parcela="+$("#cd_parcela").val()+
							  "&cd_projeto="+$("#cd_projeto").val()+
							  "&cd_proposta="+$("#cd_proposta").val()+
							  "&mes="+$("#mes").val()+
							  "&ano="+$("#ano").val()+
							  "&horas_parcela="+$("#horas_parcela").val()+
							  "&proxima_parcela_hidden="+$("#ni_parcela_hidden").val(),
						success: function(retorno){
							atualizaHorasDisponivelAjax();
							$("#$horas_parcela").val("");
							$("#proximaParcela").html($("#proxima_parcela_hidden").val());
							atualizaGridParcela();
							$("#btnAlterarParcela").hide();
							$("#btnAddParcela").show();
							alertMsg(retorno);
						}
					});
					//Linha abaixo comentada para que a grid possa apresentar sem adicionar proposta
				} else {
					alertMsg(i18n.L_VIEW_SCRIPT_QTD_HORAS_SUPERIOR_QTD_DISPONIVEL);
					return false;
				}
			} else {
				alertMsg(i18n.L_VIEW_SCRIPT_PROPOSTA_NAO_POSSUI_HORAS);
				return false;
			}
		} else {
			alertMsg(i18n.L_VIEW_SCRIPT_PREENCHA_CAMPOS_OBRIGATORIOS);
			return false;
		}
	});
});