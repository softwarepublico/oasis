$(document).ready(function(){
	
	$('#novo_profissional').click(function(){

		limpaFormNovoProfissional();

		$('#li-profissional').show();
		$('#aba_profissional').show();
		$('#container_painel_profissional').triggerTab(2);
		$('#st_nova_senha').attr('checked','checked');
		$('#bt_excluir_profissional' ).hide();
	});
	
	$('#st_inativo_lista').change(function(){
		montaGridProfissional();
	});
	
	$('#cd_empresa_lista').change(function(){
		montaGridProfissional();
	});
});

function montaGridProfissional()
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/painel-profissional/grid-painel-profissional",
		data	: "cd_empresa="+$("#cd_empresa_lista").val()
				 +"&st_inativo="+$("#st_inativo_lista").val(),
		success	: function(retorno){
			$("#gridProfissional").html(retorno);
		}
	});
}