$(document).ready(function(){
	$('#adicionarBoxInicio').click(function(){
		if( !validaForm('#formBoxInicio')){ return false; }
		salvarBoxInicio();
	});

	$('#alterarBoxInicio').click(function(){
		if( !validaForm('#formBoxInicio')){ return false; }
		salvarBoxInicio();
	});
	
	$('#cancelarBoxInicio').click(function(){
		$('#formBoxInicio :input').val('');
		$('#adicionarBoxInicio'	 ).show();
		$('#alterarBoxInicio'	 ).hide();
		$('#cancelarBoxInicio'	 ).hide();
	});
});

function salvarBoxInicio()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/box-inicio/salvar-box-inicio",
		data	: $('#formBoxInicio :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno,'',"montaGridBoxInicio()");
            
		}
	});
}

function redirecionaBoxInicio(cd_box_inicio)
{
	$("#cd_box_inicio").val(cd_box_inicio);
	$.ajax({
		type	: "POST",
		url		: systemName+"/box-inicio/redireciona-box-inicio",
		data	: "cd_box_inicio="+cd_box_inicio,
		dataType: 'json',
		success	: function(ret){
            
			$('#adicionarBoxInicio'	 ).hide();
			$('#alterarBoxInicio'	 ).show();
			$('#cancelarBoxInicio'	 ).show();
            
			$("#tx_box_inicio"		 ).val(ret[0].tx_box_inicio);
			$("#tx_titulo_box_inicio").val(ret[0].tx_titulo_box_inicio);
			$("#st_tipo_box_inicio"	 ).val(ret[0].st_tipo_box_inicio.split(''));
		}
	});
}

function montaGridBoxInicio()
{
    $("#formBoxInicio :input").val('');
    $('#adicionarBoxInicio'	 ).show();
    $('#alterarBoxInicio'	 ).hide();
	$('#cancelarBoxInicio'	 ).hide();
	$.ajax({
		type	: "POST",
		url		: systemName+"/box-inicio/grid-box-inicio",
		success	: function(retorno){
			$("#gridBoxInicio").html(retorno);
		}
	});
}

function excluirBoxInicio(cd_box_inicio)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/box-inicio/excluir-box-inicio",
            data	: "cd_box_inicio="+cd_box_inicio,
            success	: function(retorno){
                alertMsg(retorno,'',"montaGridBoxInicio()");
            }
        });
    });
}