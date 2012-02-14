$(document).ready(function(){
	$('#alterarInteracao').hide();
	$('#cancelarInteracao').hide();
		
	$("#cd_modulo_interacao").change(function(){
		comboCasoDeUsoInteracao();
	});
	$("#cd_caso_de_uso_combo").change(function(){
		if($("#cd_caso_de_uso_combo").val() == "0"){
			$('#gridInteracao').hide('slow');
		} else {
			ajaxGridInteracao();
		}
	});
	$('#adicionarInteracao').click(function(){
		if( !validaForm('#formInteracao') ){ return false; }
		salvarInteracao();
	});
	$('#alterarInteracao').click(function(){
		if( !validaForm('#formInteracao') ){ return false; }
		alteraInteracao()
	});
	$('#cancelarInteracao').click(function(){
		limpaValueInteracao();
	});
});

function salvarInteracao()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-interacao/salvar-interacao",
		data: $('#formInteracao :input').serialize()+"&cd_projeto="+$('#cd_projeto').val(),
		success: function(retorno){
			alertMsg(retorno);
			ajaxGridInteracao();
			limpaValueInteracao();
		}
	});
}

function alteraInteracao()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-interacao/alterar-interacao",
		data: $('#formInteracao :input').serialize()+"&cd_projeto="+$('#cd_projeto').val(),
		success: function(retorno){
			alertMsg(retorno);
			ajaxGridInteracao();
			limpaValueInteracao();
		}
	});
}

function excluirInteracao(cd_interacao, dt_versao_caso_de_uso)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type: "POST",
            url: systemName+"/caso-de-uso-interacao/excluir-interacao-projeto",
            data: "cd_interacao="+cd_interacao
                  +"&dt_versao_caso_de_uso="+dt_versao_caso_de_uso,
            success: function(retorno){
                alertMsg(retorno);
                ajaxGridInteracao();
                limpaValueInteracao();
            }
        });
    });
}

function ajaxGridInteracao()
{
	if($("#cd_modulo_interacao").val() != "0"){
		if($("#cd_caso_de_uso_combo").val() != "0"){
			$.ajax({
				type: "POST",
				url: systemName+"/caso-de-uso-interacao/pesquisa-interacao-projeto",
				data: "cd_projeto="+$("#cd_projeto").val()
				      +"&cd_modulo="+$("#cd_modulo_interacao").val()
				      +"&cd_caso_de_uso="+$("#cd_caso_de_uso_combo").val(),
				success: function(retorno){
					$('#gridInteracao').html(retorno);
					limpaValueInteracao();
					$('#gridInteracao').show('slow');
				}
			});
		} 
	}
}

function ajaxRecuperaInteracao(cd_interacao, dt_versao_caso_de_uso,st_fechamento_caso_de_uso)
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-interacao/recupera-interacao-projeto",
		data: "cd_interacao="+cd_interacao
		      +"&dt_versao_caso_de_uso="+dt_versao_caso_de_uso,
		dataType:'json',
		success: function(retorno){
			$('#cd_modulo_interacao').val(retorno[0]['cd_modulo']);
			$('#cd_interacao').val(retorno[0]['cd_interacao']);
			comboCasoDeUso();
			$('#cd_ator_combo').val(retorno[0]['cd_ator']);
			$('#ni_ordem_interacao').val(retorno[0]['ni_ordem_interacao']);
			
			$('#dt_versao_caso_de_uso_interacao').val(retorno[0]['dt_versao_caso_de_uso']);
			
			if(retorno[0]['st_interacao'] == 'S'){
				$('#st_interacao_s').attr('checked','checked');
			} else {
				$('#st_interacao_a').attr('checked','checked');
			}
			$('#tx_interacao').val(retorno[0]['tx_interacao']);
			var t=setTimeout("$('#cd_caso_de_uso_combo').val("+retorno[0]['cd_caso_de_uso']+")",1000);
				
			if(st_fechamento_caso_de_uso == 'S'){
				$('#adicionarInteracao').hide();
				$('#alterarInteracao').hide();
				$('#cancelarInteracao').addClass('clear-l').show();
			} else {
				$('#adicionarInteracao').hide();
				$('#alterarInteracao').show();
				$('#cancelarInteracao').removeClass('clear-l').show();
			}
		}
	});
}

function montaComboInteracaoAtor()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso-ator/combo-ator",
		data: "cd_projeto="+$('#cd_projeto').val(),
		success: function(retorno){
			$('#cd_ator_combo').html(retorno);
		}
	});
}

function comboCasoDeUsoInteracao()
{
	$.ajax({
		type: "POST",
		url: systemName+"/caso-de-uso/combo-group-caso-de-uso",
		data: "cd_projeto="+$('#cd_projeto').val()
			  +"&cd_modulo="+$('#cd_modulo_interacao').val(),
		success: function(retorno){
			$('#cd_caso_de_uso_combo').html(retorno);
		}
	});
}

function limpaValueInteracao()
{
	$('#ni_ordem_interacao' ).val("");
	$('#st_interacao_s'     ).attr('checked','checked');
	$('#st_interacao_a'     ).attr('checked','');
	$('#tx_interacao'       ).val("");
	$('#cd_interacao'       ).val("");
	$('#dt_versao_caso_de_uso_interacao').val("");
	
	if($('#st_fechamento_caso_de_uso_hidden').val() == "S"){
		$('#adicionarInteracao').hide();
		$('#alterarInteracao'  ).hide();
		$('#cancelarInteracao' ).hide();
	} else {
		$('#adicionarInteracao').show();
		$('#alterarInteracao'  ).hide();
		$('#cancelarInteracao' ).hide();
	}
}