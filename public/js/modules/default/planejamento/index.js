$('#formPlanejamento').ready(function(){
	$('#cd_projeto_planejamento').change(function(){
		comboModulo();
	});

	$('#cd_etapa_planejamento').change(function(){
		gridPlanejamento();
	});

    $('#config_hidden_planejamento').val('N');
});

function configAccordionPlanejamento()
{
    if($('#config_hidden_planejamento').val() === 'N'){
        comboEtapa();
        pesquisaProjetoPlanejamentoProdutoProfissional();
    }

    $('#config_hidden_planejamento').val('S');
}

function salvaPlanejamento()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/planejamento/salva-dados-planejamento",
		data	: $('#formPlanejamento :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
		}
	});
}

function gridPlanejamento()
{
	if($('#cd_modulo_planejamento').val() == 0){
		showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_MODULO,$('#cd_modulo_planejamento'));
		return false;
	}
	if($('#cd_etapa_planejamento').val() == 0){
		showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_ETAPA,$('#cd_etapa_planejamento'));
		return false;
	}
	$.ajax({
		type	: "POST",
		url		: systemName+"/planejamento/grid-planejamento",
		data	: "cd_projeto="+$("#cd_projeto_planejamento").val()
				 +"&cd_modulo="+$("#cd_modulo_planejamento").val()
				 +"&cd_etapa="+$("#cd_etapa_planejamento").val(),
		success: function(retorno){
			$('#gridPlanejamento').html(retorno);
			$('#gridPlanejamento').show();		
		}
	});
	return true;
}

function comboModulo(){
	if ($("#cd_projeto_planejamento").val() != 0){
		$.ajax({
			type	: "POST",
			url		: systemName + "/modulo/monta-combo-modulo",
			data	: "cd_projeto=" + $("#cd_projeto_planejamento").val(),
			success	: function(retorno){
				$('#cd_modulo_planejamento').html(retorno);
			}
		});
	}
}

function comboEtapa()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/etapa/pesquisa-etapa",
		success	: function(retorno){
			$('#cd_etapa_planejamento').html(retorno);
		}
	});
}