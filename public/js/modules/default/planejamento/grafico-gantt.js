$('#formPlanejamentoGrafico').ready(function(){
    $('#cd_projeto_planejamento_grafico').change(function(){
        comboModuloGrafico();
    });
	
    $('#btn_gerar_grafico_gantt').click( function(){
        if( !validaForm('#formPlanejamentoGrafico') ){ return false; }
		var urlGrafico = 'planejamento/pop-up-grafico-gantt/cd_projeto/'+$("#cd_projeto_planejamento_grafico").val()+'/cd_modulo/'+$('#cd_modulo_planejamento_grafico').val();
        gerarRelatorio( $('#formPlanejamentoGrafico'), urlGrafico,800,600,'yes');
        return true;
    });

	$('#gantt_tipo_rel-E').attr('checked', 'checked');
});

function comboModuloGrafico(){
    if ($("#cd_projeto_planejamento_grafico").val() != 0){
        $.ajax({
            type	: "POST",
            url		: systemName + "/modulo/monta-combo-modulo",
            data	: "cd_projeto=" + $("#cd_projeto_planejamento_grafico").val()
					 +"&comTodos=true",
            success: function(retorno){
                $('#cd_modulo_planejamento_grafico').html(retorno);
            }
        });
    }
}

function semRegistro()
{
	var strHtml = "<h4 style=\"color: red;\">"+i18n.L_VIEW_SCRIPT_SEM_REGISTRO_EXIBICAO+"</h4>";
	setTimeout(function(){$("#sem_registro").html(strHtml);}, 100);
}

function verificaBrowser()
{
//    var instrucaoGeral  = i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_IMG_PLANO_FUNDO_BROWSER;
//	var instrucao 		= instrucaoGeral;
//
//	if($.browser.mozilla){
//		instrucao = instrucaoGeral + i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_MOZILLA;
//	}else if($.browser.msie){
//		instrucao = instrucaoGeral + i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_MSIE;
//	}else if($.browser.opera){
//		instrucao  = i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_OPERA;
//	}else{
//        instrucao = i18n.L_VIEW_SCRIPT_INFO_IMPRESSAO_CORES_DEFAULT;
//    }
//
//	var strHtml = "<span style=\"color: red;\"><small>"+instrucao+"</small></span>";
//
//	 setTimeout(function(){$("#intrucao").html(strHtml);}, 100);
}

function imprimirRelatorio()
{
	$("#print_instrucao").hide();
	window.print();
	setTimeout(function(){$("#print_instrucao").show();}, 500);

}