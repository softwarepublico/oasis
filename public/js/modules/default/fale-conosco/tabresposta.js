$(document).ready(function(){

	$('#btn_salvar_resposta_fale_conosco').click(function(){
		salvarResposta();
	});

	$('#btn_cancelar_resposta_fale_conosco').click(function(){
		fechaTabResposta();
	});

	if ($('#tab_origem').val() == 3) {
		$('#btn_salvar_resposta_fale_conosco').hide();
	}
});

function salvarResposta()
{
    alert("aqui");
    //if(!validaForm()){return false}
    $.ajax({
		type	: "POST",
		url		: systemName+"/fale-conosco/salvar-resposta",
		data	: $('#formRespostaFaleConosco :input').serialize(),
		//dataType: 'json',
		success : function(retorno){
			alertMsg(retorno,null, 'fechaTabResposta()');
			gridFaleConoscoMensagemAberta();
			gridFaleConoscoMensagemPendente();
			gridFaleConoscoMensagemRespondida();
		}
    });
}

function fechaTabResposta() {
    $('#li-resposta-fale-conosco').hide();
    var tab_origem = parseInt($('#tab_origem').val());
    $('#container_fale_conosco').triggerTab(tab_origem);
}