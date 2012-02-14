$(document).ready(function(){
	$('#cd_tipo_conhecimento, #cd_profissional_conhecimento').change(function(){
        if($('#cd_profissional_conhecimento').val() != 0 ){
            if($('#cd_tipo_conhecimento').val() != 0){
                pesquisaConhecimentoAjax(true);
            } else {
                pesquisaConhecimentoAjax(false);
    		}
        } else {
            $('#cd_conhecimento1, #cd_conhecimento2').empty();
            $('#cd_tipo_conhecimento').val('0');
        }
	});

	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#addTipoConhecimento").click(function() {
        acaoConhecimentoProfissional( 'associa' );
	});

	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#removeTipoConhecimento").click(function() {
        acaoConhecimentoProfissional( 'desassocia' );
	});
    
    $('#cd_tipo_conhecimento, #cd_profissional_conhecimento').val('0').trigger('change');
});

function pesquisaConhecimentoAjax( especifico ) {
    if($('#cd_profissional_conhecimento').val() != 0 ){
    	var postUrl  = systemName+"/conhecimento-profissional/pesquisa-conhecimento";
        var postData = {
            'cd_profissional' : $('#cd_profissional_conhecimento').val()
        }; 
        var postDataEspecifico = {
            'cd_profissional'      : $('#cd_profissional_conhecimento').val(),
            'cd_tipo_conhecimento' : $('#cd_tipo_conhecimento').val()
        }
        $.ajax({
    		type     : 'POST',
    		url      : postUrl,
    		data     : ( !especifico )? postData : postDataEspecifico,
    		success  : function(retorno){
                eval('var ret = '+retorno);
                var conh1 = ret[0];
    			var conh2 = ret[1];
    			$("#cd_conhecimento1").html(conh1);
    			$("#cd_conhecimento2").html(conh2);
    		}
    	});
    }
}

function acaoConhecimentoProfissional( associa_desassocia ){
    if( associa_desassocia == 'associa' ){
        var selecao = $('#cd_conhecimento1 option:selected'); 
    } else if( associa_desassocia == 'desassocia' ){
        var selecao = $('#cd_conhecimento2 option:selected');
    }
    if( selecao.val() == undefined ){return false;}
    var conhecimento = "[";
    selecao.each(function() {
        conhecimento += (conhecimento == "[") ? $(this).val() : "," + $(this).val();
    });
        conhecimento += "]";
    if( associa_desassocia == 'associa' ){
        var postUrl  = systemName+"/conhecimento-profissional/associa-conhecimento-profissional";
    } else if( associa_desassocia == 'desassocia' ){
        var postUrl  = systemName+"/conhecimento-profissional/desassocia-conhecimento-profissional";
    }
    var postData = {
        'cd_profissional'      : $('#cd_profissional_conhecimento').val(),
        'cd_tipo_conhecimento' : $('#cd_tipo_conhecimento').val(),
        'conhecimento'         : conhecimento
    };
    $.ajax({
        type    : "POST",
        url     : postUrl,
        data    : postData,
        success : function(retorno){
            if($('#cd_tipo_conhecimento').val() != 0){
                pesquisaConhecimentoAjax(true);
            } else {
                pesquisaConhecimentoAjax(false);
            }
        }
    });
}