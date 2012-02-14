$(document).ready(function(){

	$("#op_sistemas").click(function() {
		if (this.checked) {
			$("#sistemas").show();
		} else {
			$("#sistemas").hide();
		}
	});
	
	$("#op_sitios").click(function() {
		if (this.checked) {
			$("#sitio").show();
		} else {
			$("#sitio").hide();
		}
	});
	
	$("#op_metrica").click(function() {
		if (this.checked) {
			$("#nao_aplica").show();
		} else {
			$("#nao_aplica").hide();
		}
	});
	
	/*
	* INICIO DO JS DE CALCULO DA METRICA PARA SISTEMAS
	*/
	var simples = 0;
	var total   = 0;
	var rel_simples = 0;
	var tabelas_basicas = 0;
	var tabelas_associativas = 0;
	
	$("#calcular").click(function() {
		simples     		   = calculoSistemaRelatorio($("#nu_simples").val(), $("#nu_media").val(), $("#nu_complexa").val());
		rel_simples 		   = calculoSistemaRelatorio($("#rel_simples").val(), $("#rel_media").val(), $("#rel_complexa").val());
		tabelas_basicas        = calculoBasicaAssociativa($("#tabelas_basicas").val()); 
		tabelas_associativas   = calculoBasicaAssociativa($("#tabelas_associativas").val());
		total = (simples + rel_simples + tabelas_basicas + tabelas_associativas) * 20;
		$("#h_simples").val(simples);
		$("#h_rel_simples").val(rel_simples);
		$("#h_tabelas_basicas").val(tabelas_basicas);
		$("#h_tabelas_associativas").val(tabelas_associativas);
		$("#horas").val(total);
	});
	
	// ajax para salvar estes dados

	urlProjeto = systemName+'/projeto/editar'
	
	$("#salvar_sistema").click(function() {
		
		
		var dadosSistema  = 'simples='+simples;
		var dadosSistema  = '&total_sistema='+total;
		dadosSistema += '&rel_simples='+rel_simples;
		dadosSistema += '&cd_projeto='+$("#cd_projeto").val();
		dadosSistema += '&cd_proposta='+$("#cd_proposta").val();
		dadosSistema += '&horas='+total;
		dadosSistema += '&nu_simples='+$("#nu_simples").val();
		dadosSistema += '&nu_media='+$("#nu_media").val();
		dadosSistema += '&nu_complexa='+$("#nu_complexa").val();
		dadosSistema += '&rel_simples='+$("#rel_simples").val();
		dadosSistema += '&rel_media='+$("#rel_media").val();
		dadosSistema += '&rel_complexa='+$("#rel_complexa").val();
		dadosSistema += '&tabelas_basicas='+$("#tabelas_basicas").val();
		dadosSistema += '&tabelas_associativas='+$("#tabelas_associativas").val();
		
		// Submete os dados via ajax
		$.ajax({
			type	: "POST",
			url		: systemName+"/metrica/sistema",
			data	: dadosSistema,
			success	: function(retorno){
				//$("#teste").html(retorno);return false;
				$("#valor_total_horas").text(retorno+" horas");
				$('#horasTotais').effect("highlight", {}, 1000); 
				atualizaHorasDisponivelAjax();
			}
		});
	});
	
	/*
	* FIM DO JS DE CALCULO DA METRICA PARA SISTEMAS
	*/
	
	
	/*
	* INICIO DO JS DE CALCULO DA METRICA PARA SITIOS
	*/
	$("#calcular_sitio").click(function() {
		PMD         		   = calculoSitio($("#simples_sitio").val(), $("#media_sitio").val(), $("#complexa_sitio").val(), $("#eventos_visuais_simples").val(), $("#eventos_visuais_media").val(), $("#eventos_visuais_complexa").val());
		total_sitios 		   = (PMD) * 20;
		$("#horas_sitio").val(total_sitios);
	});
	
	$("#salvar_sitio").click(function() {
		
		var dadosSitio  = 'horas_sitio='+$("#horas_sitio").val();
		dadosSitio += '&cd_projeto='+$("#cd_projeto").val();
		dadosSitio += '&cd_proposta='+$("#cd_proposta").val();
		dadosSitio += '&nu_simples_sitio='+$("#simples_sitio").val();
		dadosSitio += '&nu_media_sitio='+$("#media_sitio").val();
		dadosSitio += '&nu_complexa_sitio='+$("#complexa_sitio").val();
		dadosSitio += '&eventos_visuais_simples='+$("#eventos_visuais_simples").val();
		dadosSitio += '&eventos_visuais_media='+$("#eventos_visuais_media").val();
		dadosSitio += '&eventos_visuais_complexa='+$("#eventos_visuais_complexa").val();

		// Submete os dados via ajax
		$.ajax({
			type	: "POST",
			url		: systemName+"/metrica/sitio",
			data	: dadosSitio,
			success	: function(retorno){
				//alertMsg(retorno);
				$("#valor_total_horas").text(retorno+" horas");
				$('#horasTotais').effect("highlight", {}, 1000);
				atualizaHorasDisponivelAjax();
			}
		});
	});
	
	/*
	* FIM DO JS DE CALCULO DA METRICA PARA SITIOS
	*/
	
	/*
	* INICIO DO JS PARA SEM METRICA
	*/
	$("#salvar_sem_metrica").click(function() {
		var horas_sem_metrica = $("#horas_sem_metrica").val();
		var justificativa     = $("#sem_metrica_justificativa").val();
		var dadosSemMetrica   = 'horas_sem_metrica='+horas_sem_metrica;
		dadosSemMetrica += '&sem_metrica_justificativa='+justificativa;
		dadosSemMetrica += '&cd_projeto='+$("#cd_projeto").val();
		dadosSemMetrica += '&cd_proposta='+$("#cd_proposta").val();

		// Submete os dados via ajax
		$.ajax({
			type	: "POST",
			url		: systemName+"/metrica/sem-metrica",
			data	: dadosSemMetrica,
			success	: function(retorno){
				$("#valor_total_horas").text(retorno+" horas");
				$('#horasTotais').effect("highlight", {}, 1000);
				atualizaHorasDisponivelAjax();
			}
		});
	});
});