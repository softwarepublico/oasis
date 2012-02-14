var strOption = "<option value=\"0\">"+i18n.L_VIEW_SCRIPT_SELECIONE+"</option>";

$(document).ready(function(){
	
	if($("#cd_contrato").val() != 0){
		getProjeto();
	}

	$("#cd_contrato").change(function() {
		if($(this).val() != 0){
			getProjeto();		
		}else{
			$("#cd_projeto").html(strOption);
			$("#dt_baseline").html(strOption);
		}
	});
	
	
	$("#cd_projeto").change(function(){
		if($(this).val() > 0 ){
			montaComboBaseline();
		}else{
			$("#dt_baseline").html(strOption);
		}
	});
	
	
	$('#btn_gerar').click( function(){
		if( !validaConteudo() ){return false;}
		gerarRelatorio( $('#formRelatorioProjetoConteudoBaseline') , 'conteudo-baseline/conteudo-baseline' );
		return true;
	});
});

function getProjeto()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/conteudo-baseline/pesquisa-projeto",
		data: "cd_contrato="+$("#cd_contrato").val(),
		success: function(retorno){
			$("#cd_projeto").html(retorno);
		}
	});
}

function montaComboBaseline()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/conteudo-baseline/get-combo-group-baseline",
		data	: "cd_projeto="+$("#cd_projeto").val(),
		success : function(retorno){
			$("#dt_baseline").html(retorno);
		}
	});
}

function validaConteudo()
{
	if( $("#cd_contrato").val() == 0){
		showToolTip('Campo Obrigatório',$("#cd_contrato"));
		return false;
	}
	if( $("#cd_projeto").val() == 0){
		showToolTip('Campo Obrigatório',$("#cd_projeto"));
		return false;
	}
	if( $("#dt_baseline").val() == 0){
		showToolTip('Campo Obrigatório',$("#dt_baseline"));
		return false;
	}

	if( $("#chk_requisito").attr('checked') != true && $("#chk_proposta").attr('checked') != true && $("#chk_regra_negocio").attr('checked') != true && $("#chk_caso_uso").attr('checked') != true){
		alertMsg("Escolha uma opção de conteúdo para o relatório.");
		return false;
	}
	
	return true;
}
