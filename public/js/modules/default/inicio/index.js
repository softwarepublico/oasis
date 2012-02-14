$(document).ready( function() {
    $("img.openBoxMensagensAntigas").click( function() {
        abreDialogMensagemAntiga();
    });
});

function montaBox( strBox ){

	$.ajax({
		type	: 'POST',
		url		: systemName+'/inicio/'+strBox,
//        async   : false,
		success	: function(retorno){
			$('#boxList').append(retorno);
			configuraLayoutBox();
		}
	});
}

function configuraLayoutBox(){

	$("#boxList").sortable({
					stop: function(){
						gravaPosicaoBox();
					}
	});

	$(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
				 .find(".portlet-header")
				 .addClass("ui-widget-header ui-corner-all")
				 .end()
				 .find(".portlet-content");
	$("#boxList").disableSelection();

	$(".imgCloseBox").click( function() {
		fechaBox( $(this).parent().parent().parent().parent() );
	});
}

function recuperaListaBox( param, label )
{
	arrLabel = label.split('-');

	arrParams		= param.split('_');
	var cd_contrato = arrParams[0];
	var cd_objeto	= arrParams[1];

	//mostra a label para qual contrato está sendo exibido os boxes
    
    $("#span_boxes_do_contrato").html(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_BOX_NR_CONTRATO, new Array(arrLabel[0],arrLabel[2]))).show();
	//limpa a ul para receber os novos boxes
	$('#boxList').html('');

	for(var i=0; i < qtdBox; i++ ){
		$.ajax({
			type	: 'POST',
			url		: systemName+'/inicio/'+arrBox[i],
			data	: 'cd_contrato='+cd_contrato+'&cd_objeto='+cd_objeto,
			success	: function(retorno){
				$('#boxList').append(retorno);
				configuraLayoutBox();
			}
		});
	}
	setTimeout(function(){closeDialog('dialog_box_troca_contrato')}, 1000);

}

function retornaSerializeBoxList(){

	var strPosicao = '';
	$("#boxList .boxList").each(function(){
		strPosicao += $(this).attr('id')+",";
	});
	
	return strPosicao.substr(0,strPosicao.length -1);
}

function gravaPosicaoBox( str, peloDialog )
{
    var recarregaTela = false;
    var url = 'inicio/grava-posisao';

    if ( (!str) && (peloDialog !== true) ) {
        var strPosicao = retornaSerializeBoxList();
    } else {
        var strPosicao = str;
       recarregaTela = true
    }

    $.post( url,{'tx_posicao_box_inicio': strPosicao},function(ret){ 

		if( recarregaTela ){
            window.location.href="";
        }

		if(strPosicao.length == 0){
			$("#span_boxes_do_contrato").remove();
		}

        return true;
    });
}

function atualizaConteudo(minutos)
{
	var tempo = (minutos * 60 *1000);
	setTimeout( function(){
		atualizaConteudo(tempo);
	}, tempo );
	
	$.ajax({
		type	: 'POST',
		url		: systemName+'/gerenciar-projetos-solicitacao/index/pagina/inicio',
		success	: function(retorno){
			// atualiza a grid
			$('#inicio_solicitacao').html('');
			$('#inicio_solicitacao').html(retorno);
		}
	});
	
	$.ajax({
		type	: 'POST',
		url		: systemName+'/gerenciar-projetos-elaboracao/index/pagina/inicio',
		success	: function(retorno){
			// atualiza a grid
			$('#inicio_elaboracao').html('');
			$('#inicio_elaboracao').html(retorno);
		}
	});
	
	
	/* descomentar quando a view e a controller gerenciarProjetosExecucao forem criadas
	   a view não poderá renderizar o layout
	
	$.ajax({
		type: 'POST',
		url: '/oasis/gerenciar-projetos-execucao/index/pagina/inicio',
		success: function(retorno){
			// atualiza a grid
			$('#inicio_execucao').html('');
			$('#inicio_execucao').html(retorno);
		}
	});
	*/
}


//function abreDialogMensagens(strHtml)
function abreDialogMensagens()
{
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CONFIRMAR_LEITURA+'": '+function(){confirmaLeituraMensagem();}+'};');

	loadDialog({
        id       : 'dialog_mensagem_nao_lida',		//id do pop-up
        title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_MENSAGEM_NAO_LIDA,	// titulo do pop-up
        url      : systemName + '/inicio/dialog-mensagens-nao-lidas',	// url onde encontra-se o phtml
        height   : 300,									// altura do pop-up
        buttons  : buttons
    });
}

function confirmaLeituraMensagem( cd_mensagem )
{
	var dataPost = '';
	var fechaPopUp = false;
	var desabilitaBotao = false;
	if( cd_mensagem == undefined ){
		dataPost = {'cd_mensagens_nao_lidas':$('#cd_mensagens_nao_lidas').val()};
		fechaPopUp = true;
	}else{
		dataPost = {'cd_mensagens_nao_lidas': cd_mensagem};
		desabilitaBotao = true;
	}

	$.ajax({
		type	: 'POST',
		url		: systemName+'/inicio/confirma-leitura-mensagem',
		data	: dataPost,
		dataType: 'json',
		success	: function(retorno){
			if(retorno['error'] === true){
				alertMsg(retorno['msg'],retorno['typeMsg']);
			}else{
				alertMsg(retorno['msg'],retorno['typeMsg']);
				if( fechaPopUp ){
					closeDialog( 'dialog_mensagem_nao_lida' );
				}
				if( desabilitaBotao ){
					$("#btn_conf_leitura_msg_"+cd_mensagem).hide();
					$("#span_conf_leitura_msg_"+cd_mensagem).html(getTranslaterJsComVariaveis(i18n.L_VIEW_SCRIPT_DATA_LEITURA, new Array(retorno['dtLeitura']))).show();
				}
			}
		}
	});
	
}

function abreDialogMensagemAntiga()
{
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_FECHAR+'": '+function(){closeDialog( 'dialog_mensagem_antiga' );}+'};');

	loadDialog({
        id       : 'dialog_mensagem_antiga',	//id do pop-up
        title    : 'Mensagens Antigas',			// titulo do pop-up
        url      : systemName + '/inicio/dialog-mensagens-antigas',	// url onde encontra-se o phtml
        height   : 450,									// altura do pop-up
        buttons  : {"Fechar"   : function(){closeDialog( 'dialog_mensagem_antiga' );}
		}
    });
}