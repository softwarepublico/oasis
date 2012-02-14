$(document).ready(function(){
	$("#cmb_projeto_baseline").val(0);
    
	
	$("#btn_gerar_baseline").click(function(){
        if ($("#cmb_projeto_baseline").val() == 0 ) {
            alertMsg(i18n.L_VIEW_SCRIPT_BASELINE_NECESSITA_PROJETO);
        }else{
            confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_CRIAR_BASELINE, "gerarNovaBaseline()");
        }
	});
	
	$("#cmb_projeto_baseline").change(function(){
		if($(this).val() == 0){
			$("#gridBaseline").hide();
		}else{
			montaGridBaseline();
		}
	});
});

function montaGridBaseline()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/baseline/grid-baseline",
		data	: "cd_projeto="+$("#cmb_projeto_baseline").val(),
		success : function(retorno){
			// atualiza a grid
			$("#gridBaseline").html(retorno);
			$("#gridBaseline").show();
		}
	});
}

function gerarNovaBaseline()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/baseline/gerar-nova-baseline",
		data	: "cd_projeto="+$("#cmb_projeto_baseline").val(),
		dataType: "json",
		success : function(retorno){
			if(retorno['error'] == true){
	            alertMsg(retorno['errorText'],retorno['errorType']);
	        }else{
	            alertMsg(i18n.L_VIEW_SCRIPT_BASELINE_GERADA_SUCESSO);
				montaGridBaseline();
	        }
		}
	});
}