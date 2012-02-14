$(document).ready(function(){
	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#addModulo").click(function() {
		var modulo = "["; 
		$('#cd_modulo_combo option:selected').each(function() {
			modulo += (modulo == "[") ? $(this).val() : "," + $(this).val();
		});
		modulo += "]";
		$.ajax({
			type	: "POST",
			url		: systemName+"/modulo/associa-modulo-proposta",
			data	: "cd_projeto="+$("#cd_projeto").val()+"&modulo="+modulo+"&cd_proposta="+$("#cd_proposta").val(),
			success	: function(retorno){
				// apos atualizar as tabelas, atualiza os selects
				pesquisaModuloComboAjax();
			}
		});
	});
	
	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#removeModulo").click(function() {
		var modulo = "["; 
		$('#cd_modulo_combo2 option:selected').each(function() {
			modulo += (modulo == "[") ? $(this).val() : "," + $(this).val();
		});
		modulo += "]";
		
		$.ajax({
			type	: "POST",
			url		: systemName+"/modulo/desassocia-modulo-proposta",
			data	: "cd_projeto="+$("#cd_projeto").val()+"&modulo="+modulo+"&cd_proposta="+$("#cd_proposta").val(),
			success	: function(retorno){
			    pesquisaModuloComboAjax();
			}
		});
	});
	
	$("#adicionar").click(function() {
		if( !validaForm('#formModulos')){ return false; }
		salvarModulos();
		return true;
	});
	
	$("#alterar").click(function() {
		if( !validaForm('#formModulos')){ return false; }
		alterarModulos();
		return true;
	});
});

/**
 * Função para inicializar as informações do Accordion de Módulo
 */
function configModulo()
{
	$("#alterar").hide();
	pesquisaModuloProjetoAjax();
	pesquisaModuloComboAjax();
	$("#config_hidden_modulo").val("S");
}

/**
 * Salva os módulos do projeto
 */
function salvarModulos() 
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/modulo/salvar-modulo",
		data	: "tx_modulo="+$("#tx_modulo").val()+"&cd_projeto="+$("#cd_projeto").val(),
		success	: function(retorno){
			alertMsg(retorno);
			pesquisaModuloProjetoAjax();
			pesquisaModuloComboAjax();
		}
	});
}

/**
 * alterarModulos
 */
function alterarModulos()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/modulo/alterar-modulo",
		data	: "cd_modulo="+$("#cd_modulo").val()+
				  "&tx_modulo="+$("#tx_modulo").val()+
				  "&cd_projeto="+$("#cd_projeto").val(),
		success	: function(retorno){
			alertMsg(retorno);
			pesquisaModuloProjetoAjax();
			pesquisaModuloComboAjax();
			$("#alterar"  ).hide();
			$("#adicionar").show();
		}
	});
}

function verificaStatusAccordionModulos()
{
	if( $("#config_hidden_modulos").val() === "N" ){
		pesquisaModuloComboAjax();
	}
}

// Realiza a pesquisa de profissionais por projeto e atualiza os selects.
function pesquisaModuloProjetoAjax()
{	
	if ( $("#cd_projeto").val() != "0" ) {
		$.ajax({
			type	: "POST",
			url		: systemName+"/modulo/pesquisa-modulo-projeto",
			data	: "cd_projeto="+$("#cd_projeto").val(),
			success	: function(retorno){
				$("#grid").html(retorno);
			}
		});
	}
}

// Realiza a pesquisa de profissionais por projeto e atualiza os selects.
function pesquisaModuloComboAjax()
{		
	if ($("#cd_projeto").val() != "0") {
		$.ajax({
			type	: "POST",
			url		: systemName+"/modulo/pesquisar-modulo",
			data	: "cd_projeto="+$("#cd_projeto").val()
					 +"&cd_proposta="+$("#cd_proposta").val(),
			dataType: 'json',
			success	: function(retorno){
				mod1 = retorno[0];
				mod2 = retorno[1];
				$("#cd_modulo_combo").html(mod1);
				$("#cd_modulo_combo2").html(mod2);
				
				if( $("#config_hidden_modulos").val() === "N" ){
					$("#config_hidden_modulos").val("S");
				}
			}
		});
	}
}

function alterar(cd_modulo,tx_modulo)
{
	$("#cd_modulo").val(cd_modulo);
	$("#tx_modulo").val(tx_modulo);
	$("#adicionar").hide();
	$("#alterar"  ).show();
}

function excluirModulo(cd_modulo)
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
        $.ajax({
            type	: "POST",
            url		: systemName+"/modulo/excluir-modulo",
            data	: "cd_modulo=" + cd_modulo +
                      "&cd_projeto=" + $("#cd_projeto").val() +
                      "&cd_proposta=" + $("#cd_proposta").val(),
            success	: function(retorno){
                pesquisaModuloProjetoAjax();
                pesquisaModuloComboAjax();
                alertMsg(retorno);
            }
        });
    });
}