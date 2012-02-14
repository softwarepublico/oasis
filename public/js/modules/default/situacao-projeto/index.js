function carregaSituacaoMesAnterior(mes, ano, cd_projeto)
{
	$.ajax({
		type	: "POST",
		url		: systemName+'/situacao-projeto/ultima-situacao-projeto',
		data	: "cd_projeto="+cd_projeto
				 +"&mes_ano_selecionado="+mes+'_'+ano,
		success	: function(retUltimaSituacao){
			$("#box_posicionamento").html(retUltimaSituacao)
		}
	});
}

function gravaPosicionamentoSituacao()
{
	$.ajax({
		type	: "POST",
		url		: systemName+'/situacao-projeto/salvar-posicionamento-projeto',
		data	: $('#form_posicionamento :input').serialize(),
		success	: function(response){
			alertMsg(response);
			// Chama a funca que se encontra no index.js da tela principal de elaboracao de proposta
			carregaBoxSituacaoProjeto();
			closeDialog('dialog_posicionamento_projeto');
		}
	});
}