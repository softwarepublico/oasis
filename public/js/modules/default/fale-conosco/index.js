$(document).ready(function(){
	$('#tx_resposta').hide();	
	$('#label_tx_resposta').hide();	
	
	$('#btn_enviar_msg').click(function(){
		salvarMensagem();
	});

	$("#mesFaleConoscoMensagemAberta").change(function(){
		gridFaleConoscoMensagemAberta();
	});

	$("#anoFaleConoscoMensagemAberta").change(function(){
		gridFaleConoscoMensagemAberta();
	});

	$("#mesFaleConoscoMensagemPendente").change(function(){
		gridFaleConoscoMensagemPendente();
	});

	$("#anoFaleConoscoMensagemPendente").change(function(){
		gridFaleConoscoMensagemPendente();
	});

	$("#mesFaleConoscoMensagemRespondida").change(function(){
		gridFaleConoscoMensagemRespondida();
	});

	$("#anoFaleConoscoMensagemRespondida").change(function(){
		gridFaleConoscoMensagemRespondida();
	});
});

function salvarMensagem()
{
	if(!validaForm()){return false}
	$.ajax({
		type	: "POST",
		url		: systemName+"/fale-conosco/salvar-fale-conosco",
		data	: $('#formFaleConosco :input').serialize(),
		dataType: 'json',
		success : function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['msg'],retorno['errorType']);
	        }else{
				alertMsg(retorno['msg'],null, function(){
					
					if(retorno['comUrl']== true){
						window.location.href = systemName+retorno['url'];
					}else{
						window.location.href = systemName;
					}
				});
	        }
		}
	});
}

function gridFaleConoscoMensagemAberta()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/fale-conosco/grid-fale-conosco-mensagem-aberta",
		data	: "mes="+$("#mesFaleConoscoMensagemAberta").val()
				 +"&ano="+$("#anoFaleConoscoMensagemAberta").val(),
		success: function(retorno){
			$("#gridFaleConoscoMensagemAberta").html(retorno);
			$('#mesAno').html($('#mesAnoFaleConoscoMensagemAberta').val());
		}
	});
}

function gridFaleConoscoMensagemPendente()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/fale-conosco/grid-fale-conosco-mensagem-pendente",
		data	: "mes="+$("#mesFaleConoscoMensagemPendente").val()
				 +"&ano="+$("#anoFaleConoscoMensagemPendente").val(),
		success: function(retorno){
			$("#gridFaleConoscoMensagemPendente").html(retorno);
			$('#mesAno').html($('#mesAnoFaleConoscoMensagemPendente').val());
		}
	});
}

function gridFaleConoscoMensagemRespondida()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/fale-conosco/grid-fale-conosco-mensagem-respondida",
		data	: "mes="+$("#mesFaleConoscoMensagemRespondida").val()
				 +"&ano="+$("#anoFaleConoscoMensagemRespondida").val(),
		success	: function(retorno){
			$("#gridFaleConoscoMensagemRespondida").html(retorno);
			$('#mesAno').html($('#mesAnoFaleConoscoMensagemRespondida').val());
		}
	});
}

function respondeMensagem(cd_fale_conosco, tab_origem)
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/fale-conosco/tab-resposta",
		data	: "cd_fale_conosco="+cd_fale_conosco+"&tab_origem="+tab_origem,
		success	: function(retorno){
				$('#respostaFaleConosco'	  ).html(retorno);
				$('#li-resposta-fale-conosco' ).show();
				$('#aba_resposta_fale_conosco').show();
				$('#container_fale_conosco'	  ).triggerTab(4);
		}
    });
}