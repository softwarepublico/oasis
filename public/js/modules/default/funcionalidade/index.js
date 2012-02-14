$(document).ready(function(){

	$('#alterarFuncionalidade' ).hide();
	$('#cancelarFuncionalidade').hide();

	$('#adicionarFuncionalidade').click(function(){
		//if( !validaForm('#formFuncionalidade')){ return false; }
		salvarFuncionalidade();
	});

	$('#alterarFuncionalidade').click(function(){
		//if( !validaForm('#formFuncionalidade')){ return false; }
		salvarFuncionalidade();
	});
	
	$('#cancelarFuncionalidade').click(function(){
		$('#formFuncionalidade :input').val('');
        $('#tx_descricao').wysiwyg('clear');
		$('#adicionarFuncionalidade'  ).show();
		$('#alterarFuncionalidade'	  ).hide();
		$('#cancelarFuncionalidade'	  ).hide();
	});

	$("#cd_funcionalidade_menu").change(function(){
		if($(this).val() != 0 ){
			pesquisaFuncionalidadeMenuAjax();
		}else{
			$("#cd_menu_funcionalidade" ).empty();
			$("#cd_menu_funcionalidade2").empty();
		}
	});

	$("#addFuncionalidadeMenu").click(function() {
		var menus = "[";
		$('#cd_menu_funcionalidade option:selected').each(function() {
			menus += (menus == "[") ? $(this).val() : "," + $(this).val();
		});
		menus += "]";
		$.ajax({
			type: "POST",
			url: systemName+"/funcionalidade/associa-menu-funcionalidade",
			data: "cd_funcionalidade="+$("#cd_funcionalidade_menu").val()+
			"&menus="+menus,
			success: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaFuncionalidadeMenuAjax();
			}
		});
	});

	$("#removeFuncionalidadeMenu").click(function() {
		var menus = "[";
		$('#cd_menu_funcionalidade2 option:selected').each(function() {
			menus += (menus == "[") ? $(this).val() : "," + $(this).val();
		});
		menus += "]";
		$.ajax({
			type: "POST",
			url: systemName+"/funcionalidade/desassocia-menu-funcionalidade",
			data: "cd_funcionalidade="+$("#cd_funcionalidade_menu").val()+
			"&menus="+menus,
			success: function(retorno){
			    pesquisaFuncionalidadeMenuAjax();
			}
		});
	});
});

function salvarFuncionalidade()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/funcionalidade/salvar-funcionalidade",
		data	: $('#formFuncionalidade :input').serialize(),
		success	: function(retorno){
			alertMsg(retorno,'', function(){
                montaComboFuncionalidade(); 
                montaGridFuncionalidade();
		        $('#tx_descricao').wysiwyg('value', '');
            });
		}
	});
}

function montaComboFuncionalidade()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/funcionalidade/combo-funcionalidade",
		success	: function(retorno){
			$("#cd_funcionalidade_menu").html(retorno);
		}
	});
}

function redirecionaFuncionalidade(cd_funcionalidade)
{
	$("#cd_funcionalidade").val(cd_funcionalidade);
	$.ajax({
		type	: "POST",
		url		: systemName+"/funcionalidade/recupera-funcionalidade",
		data	: "cd_funcionalidade="+cd_funcionalidade,
		dataType: 'json',
		success	: function(ret){
			$('#adicionarFuncionalidade' ).hide();
			$('#alterarFuncionalidade'	 ).show();
			$('#cancelarFuncionalidade'	 ).show();
            
			$("#tx_codigo_funcionalidade").val(ret[0].tx_codigo_funcionalidade);
			$("#tx_funcionalidade"       ).val(ret[0].tx_funcionalidade);
//			$("#tx_descricao"            ).val(ret[0].tx_descricao);
			$('#tx_descricao').wysiwyg('value', ret[0].tx_descricao);
			var st_funcionalidade        = (ret[0].st_funcionalidade == null) ? '0' : ret[0].st_funcionalidade;
			$("#st_funcionalidade"	     ).val(st_funcionalidade);
		}
	});
}

function montaGridFuncionalidade()
{
    $("#formFuncionalidade :input").val('');
    $('#adicionarFuncionalidade' ).show();
    $('#alterarFuncionalidade'	 ).hide();
	$('#cancelarFuncionalidade'	 ).hide();
	$.ajax({
		type	: "POST",
		url		: systemName+"/funcionalidade/grid-funcionalidade",
		success	: function(retorno){
			$("#gridFuncionalidade").html(retorno);
		}
	});
}

function excluirFuncionalidade(cd_funcionalidade)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/funcionalidade/excluir-funcionalidade",
		data	: "cd_funcionalidade="+cd_funcionalidade,
		success	: function(retorno){
			alertMsg(retorno,'',function(){montaComboFuncionalidade(); montaGridFuncionalidade()});
		}
	});
}

function pesquisaFuncionalidadeMenuAjax()
{
	$.ajax({
		type: "POST",
		url: systemName+"/funcionalidade/pesquisa-menu",
		data: "cd_funcionalidade="+$("#cd_funcionalidade_menu").val(),
		dataType: 'json',
		success: function(retorno){
			ret1 = retorno[0];
			ret2 = retorno[1];
			$("#cd_menu_funcionalidade" ).html(ret1);
			$("#cd_menu_funcionalidade2").html(ret2);
		}
	});
}