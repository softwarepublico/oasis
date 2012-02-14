// Todas as funcionalidades utilizam AJAX e sao implementadas com o Jquery
$(document).ready(function(){

	// Ao selecionar uma opcao no combobox, dispara este evento que preenche os selects.
	$("#cd_objeto_profissional_objeto_contrato").change(function() {
		pesquisaPorObjetoAjax();
	});
	
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_profissional_objeto_contrato").click(function() {
		var profissionais = "["; 
		$('#cd_profissional_objeto_contrato_1 option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
		});
		profissionais += "]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/profissional-objeto-contrato/associa-profissional-objeto-contrato",
			data	: "cd_objeto="+$("#cd_objeto_profissional_objeto_contrato").val()+
					  "&profissionais="+profissionais,
			success: function(retorno){
				//altera o valor do hidden de configuração do accordion de perfil profissional
				$("#config_hidden_objeto_perfil_profissional").val('N');
				
				// apos atualizar as tabelas, atualiza os selects
				pesquisaPorObjetoAjax();
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_profissional_objeto_contrato").click(function() {
		var profissionais = "["; 
		$('#cd_profissional_objeto_contrato_2 option:selected').each(function() {
			profissionais += (profissionais == "[") ? $(this).val() : "," + $(this).val();
		});
		profissionais += "]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/profissional-objeto-contrato/desassocia-profissional-objeto-contrato",
			data	: "cd_objeto="+$("#cd_objeto_profissional_objeto_contrato").val()+
					  "&profissionais="+profissionais,
			success	: function(retorno){
				//altera o valor do hidden de configuração do accordion de perfil profissional
				$("#config_hidden_objeto_perfil_profissional").val('N');
			    pesquisaPorObjetoAjax();
			}
		});
	});
});

// Realiza a pesquisa de profissionais por projeto e atualiza os selects.
function pesquisaPorObjetoAjax()
{	
	if ($("#cd_objeto_profissional_objeto_contrato").val() != -1) {
		$.ajax({
			type	: "POST",
			url		: systemName+"/profissional-objeto-contrato/pesquisa-profissional",
			data	: "cd_objeto="+$("#cd_objeto_profissional_objeto_contrato").val(),
			dataType: 'json',
			success	: function(retorno){
				prof1 = retorno[0];
				prof2 = retorno[1];
				$("#cd_profissional_objeto_contrato_1").html(prof1);
				$("#cd_profissional_objeto_contrato_2").html(prof2);
				gridProfissionalAssociadoAjax();
			}
		});
	} else {
		$("#cd_profissional_objeto_contrato_1").empty();
		$("#cd_profissional_objeto_contrato_2").empty();
		$("#gridProfissionalAssociado").hide().html('');
	}
}

function gridProfissionalAssociadoAjax()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/profissional-objeto-contrato/grid-profissional-associado",
		data	: "cd_objeto="+$("#cd_objeto_profissional_objeto_contrato").val(),
		success	: function(retorno){
			$("#gridProfissionalAssociado").html(retorno);
			$("#gridProfissionalAssociado").show();
		}
	});
}

function gravaRecebeEmail(cd_profissional, valor)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/profissional-objeto-contrato/grava-recebe-email",
		data	: "cd_profissional="+cd_profissional+
                  "&st_recebe_email="+valor+
                  "&cd_objeto="+$("#cd_objeto_profissional_objeto_contrato").val()
	});
}

function gravaObjetoPadrao(cd_profissional, valor, id)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/profissional-objeto-contrato/grava-objeto-padrao",
		data	: "cd_profissional="+cd_profissional+
                  "&st_objeto_padrao="+valor+
                  "&cd_objeto="+$("#cd_objeto_profissional_objeto_contrato").val(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['comMsg'] == true){
				alertMsg(retorno['msg'],retorno['type']);
				$("#"+id).removeAttr('checked');
			}
		}
	});
}

function gravaPerfilProfissional(cd_objeto, cd_profissional, cd_perfil_profissional)
{
	if( cd_perfil_profissional == -1 ){
		confirmMsg(i18n.L_VIEW_SCRIPT_APAGAR_PERFIL_PROF_ASSOCIADO_PROF_CONTINUAR, function(){
			$.ajax({
				type	: "POST",
				url		: systemName+"/profissional-objeto-contrato/grava-perfil-profissional",
				data	: "cd_objeto="+cd_objeto+
						  "&cd_profissional="+cd_profissional+
						  "&cd_perfil_profissional="+cd_perfil_profissional,
				dataType: 'json',
				success	: function(retorno){
				if(retorno['sucesso'] == true){
					$("#cd_perfil_profissional_"+cd_profissional).val('');
				}
			}
			});
			
		}, function(){
			$("#"+cd_profissional).val($("#cd_perfil_profissional_"+cd_profissional).val());
		},i18n.L_VIEW_SCRIPT_AVISO);

	}else{
		$.ajax({
			type	: "POST",
			url		: systemName+"/profissional-objeto-contrato/grava-perfil-profissional",
			data	: "cd_objeto="+cd_objeto+
					  "&cd_profissional="+cd_profissional+
					  "&cd_perfil_profissional="+cd_perfil_profissional,
			dataType: 'json',
			success	: function(retorno){
				if(retorno['sucesso'] == true){
					$("#cd_perfil_profissional_"+cd_profissional).val(cd_perfil_profissional);
				}
			}
		});
	}
}