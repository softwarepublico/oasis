$(document).ready(function() {

	$("#config_hidden_mensageria").val('N');

	$('#container-mensageria').show().tabs();
	$('#container-mensageria').triggerTab(1);

	$("#mesMensagens").change(function() {
		if ($("#mesMensagens").val() != "0") {
			apresentaData($("#mesMensagens").val(),$("#anoMensagens").val(),"mesAnoMensageriaMensagens");
			montaGridMensagens();
		} else {
			apresentaData($("#mesMensagens").val(),$("#anoMensagens").val(),"mesAnoMensageriaMensagens");
		}
	});

	$("#anoMensagens").change(function() {
		if ($("#anoMensagens").val() != "0") {
			apresentaData($("#mesMensagens").val(),$("#anoMensagens").val(),"mesAnoMensageriaMensagens");
			montaGridMensagens();
		} else {
			apresentaData($("#mesMensagens").val(),$("#anoMensagens").val(),"mesAnoMensageriaMensagens");
		}
	});
	
	//pega o click do botão nova mensagem
	$("#btn_nova_mensagem").click(function() {
		habilitaAbaMensagem();
	});
	
	//pega o click do botão salvar mensagem
	$("#btn_salvar_mensagem").click(function() {
		salvarMensagemMensageria();
	});
	
	//pega o click do botão alterar mensagem
	$("#btn_alterar_mensagem").click(function() {
		alterarMensagemMensageria();
	});
	
	$("#btn_cancelar_mensagem").click(function() {
		desabilitaAbaMensagem();
		limparInputsMensagem();
	});
});// EMD document.ready

function verificaConfigAccordionMensageria()
{
	if( $("#config_hidden_mensageria").val() === 'N' ){
		apresentaData($('#mesMensagens').val(),$('#anoMensagens').val(),'mesAnoMensageriaMensagens');
		montaGridMensagens();
	}
}

function montaGridMensagens()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/mensageria/grid-mensageria-mensagens",
		data	: "mes="+$("#mesMensagens").val()+"&ano="+$("#anoMensagens").val(),
		success	: function(retorno){
			// atualiza a grid
			$("#gridMensageriaMensagens").html(retorno);

			if( $("#config_hidden_mensageria").val() === 'N' ){
				$("#config_hidden_mensageria").val('S');
			}
		}
	});
}

function recuperaDadosDaMensagem( cd_mensageria )
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/mensageria/recupera-dados-mensagem",
		data	: "cd_mensageria="+cd_mensageria,
		dataType: 'json',
		success	: function(retorno){
			
			var cd_perfil = (retorno['cd_perfil'] == null ) ? -1 : retorno['cd_perfil']; 

			$('#cd_mensageria'		 ).val(retorno['cd_mensageria']);
			$('#cd_objeto_mensageria').val(retorno['cd_objeto']);
			$('#cd_perfil_mensageria').val(cd_perfil);
			$('#dt_postagem'		 ).val(retorno['dt_postagem']);
			$('#dt_encerramento'	 ).val(retorno['dt_encerramento']);
			$('#tx_mensagem'		 ).wysiwyg('value',retorno['tx_mensagem']);
			
			$('#btn_alterar_mensagem').show();
			$('#btn_salvar_mensagem' ).hide();
			habilitaAbaMensagem();
		}
	});
}

function salvarMensagemMensageria()
{
	if( !validaForm("#div_nova_mensagem") ){return false;}
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/mensageria/salvar-mensagem",
		data	: $("#div_nova_mensagem :input").serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['errorType']);
			}else{
				alertMsg(retorno['msg'],1);
				montaGridMensagens();
				limparInputsMensagem();
			}
		}
	});
}

function alterarMensagemMensageria()
{
	if( !validaForm("#div_nova_mensagem") ){return false; }
	
	$.ajax({
		type	: "POST",
		url		: systemName+"/mensageria/alterar-mensagem",
		data	: $("#div_nova_mensagem :input").serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] == true){
				alertMsg(retorno['msg'],retorno['errorType']);
			}else{
				alertMsg(retorno['msg'],1);
				montaGridMensagens();
				limparInputsMensagem();
			}
		}
	});
}

function excluirMensagem(cd_mensageria)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
		$.ajax({
			type	: "POST",
			url		: systemName + "/mensageria/excluir-mensagem",
			data	: "cd_mensageria=" + cd_mensageria,
			dataType: 'json',
			success : function(retorno){

				if(retorno['error'] == true){
					alertMsg(retorno['msg'],retorno['typeMsg']);
				}else{
					alertMsg(retorno['msg'],retorno['typeMsg']);
					montaGridMensagens();
				}
			}
		});
	});
}

function limparInputsMensagem()
{
	$("#div_nova_mensagem :input").val('');
	$('#tx_mensagem'			 ).wysiwyg('value','');
	$('#btn_alterar_mensagem'	 ).hide();
	$('#btn_salvar_mensagem'	 ).show();
}

function habilitaAbaMensagem()
{
	$('#li_aba_mensagem'	 ).show();
	$('#container-mensageria').triggerTab(2);
}

function desabilitaAbaMensagem()
{
	$('#li_aba_mensagem'	 ).hide();
	$('#container-mensageria').triggerTab(1);
}