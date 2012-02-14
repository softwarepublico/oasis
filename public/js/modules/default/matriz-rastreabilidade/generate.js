function semRegistro()
{
	var strHtml = "<h4 style=\"color: red;\">"+i18n.L_VIEW_SCRIPT_SEM_REGISTRO_EXIBICAO+"</h4>";
	setTimeout(function(){$("#sem_registro").html(strHtml);}, 100);
}

function verificaBrowser()
{
	var instrucaoGeral  = i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_IMG_PLANO_FUNDO_BROWSER;
	var instrucao 		= instrucaoGeral;
	
	if($.browser.mozilla){
		instrucao = instrucaoGeral + i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_MOZILLA;
	}else if($.browser.msie){
		instrucao = instrucaoGeral + i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_MSIE;
	}else if($.browser.opera){
		instrucao  = i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_OPERA;
	}else{
        instrucao = i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_DEFAULT;
    }
	
	var strHtml = "<span style=\"color: red;\"><small>"+instrucao+"</small></span>";

	 setTimeout(function(){$("#intrucao").html(strHtml);}, 100);
}

function imprimirRelatorio()
{
	$("#print_instrucao").hide();
	window.print();
	setTimeout(function(){$("#print_instrucao").show();}, 500);
	
}