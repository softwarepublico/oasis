$(document).ready(  function() {

    $('#cd_objeto').change(function(){
        comboPerfil();
    });

	$('#btn_gerar').click( function(){
		if( !validaForm("#formRelatorioPefilProfissional") ){ return false; }
		gerarRelatorio( $('#formRelatorioPefilProfissional') , 'perfil-profissional/relatorio-perfil-profissional' );
		return true;
	});
});

function comboPerfil()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/perfil-profissional/combo-perfil",
		data: "cd_objeto="+$("#cd_objeto").val(),
		success: function(retorno){
			$("#cd_perfil").html(retorno);
		}
	});
}