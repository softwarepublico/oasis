$(document).ready(function(){
	if($('#cd_definicao_metrica_tipo_produto').val() != 0){
		gridTipoProduto();
	}
	limpaDadosTipoProduto();

	$('#btn_salvar_tipo_produto').click(function(){
		salvarTipoProduto();
	});
	
	$('#btn_alterar_tipo_produto').click(function(){
		alterarTipoProduto();
	});
	
	$('#btn_cancelar_tipo_produto').click(function(){
		limpaDadosTipoProduto();
	});
	
	$('#cd_definicao_metrica_tipo_produto').change(function(){
		if($(this).val() != 0){
			gridTipoProduto();
		} else {
			$('#gridTipoProduto').hide();
		}
		limpaDadosTipoProduto();			
	});
});

function salvarTipoProduto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-produto/salvar-tipo-produto",
		data	: $('#formTipoProduto :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosTipoProduto();
			gridTipoProduto();
		}
	});
}

function alterarTipoProduto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-produto/alterar-tipo-produto",
		data	: $('#formTipoProduto :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno);
			limpaDadosTipoProduto();
			gridTipoProduto();
		}
	});	
}

function gridTipoProduto()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-produto/grid-tipo-produto",
		data	: "cd_definicao_metrica="+$('#cd_definicao_metrica_tipo_produto').val(),
		success	: function(retorno){
			$('#gridTipoProduto').html(retorno);
			$('#gridTipoProduto').show('slow');
		}
	});	
}

function limpaDadosTipoProduto()
{	
	$('#tx_tipo_produto'			).val("");
	$('#ni_ordem_tipo_produto'		).val("");
	$('#btn_alterar_tipo_produto'	).hide();
	$('#btn_cancelar_tipo_produto'	).hide();
	$('#btn_salvar_tipo_produto'	).show();
}

function recuperaTipoProduto(cd_tipo_produto)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/tipo-produto/recupera-tipo-produto",
		data	: "cd_tipo_produto="+cd_tipo_produto,
		dataType: 'json',
		success	: function(retorno){
			$('#cd_tipo_produto'			).val(retorno[0]['cd_tipo_produto']);
			$('#cd_definicao_metrica_tipo_produto').val(retorno[0]['cd_definicao_metrica']);
			$('#ni_ordem_tipo_produto'		).val(retorno[0]['ni_ordem_tipo_produto']);
			$('#tx_tipo_produto'			).val(retorno[0]['tx_tipo_produto']);
			$('#btn_salvar_tipo_produto'	).hide();
			$('#btn_alterar_tipo_produto'	).show();
			$('#btn_cancelar_tipo_produto'	).show();
		}
	});	
}

function excluirTipoProduto(cd_tipo_produto)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/tipo-produto/excluir-tipo-produto",
            data	: "cd_tipo_produto="+cd_tipo_produto,
            success	: function(retorno){
                alertMsg(retorno);
                gridTipoProduto();
            }
        });
    });
}