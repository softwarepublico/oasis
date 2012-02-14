$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioInventario') , 'inventario/inventario' );
        return true;
    });

    $('#cd_area_atuacao_ti').change(function(){
		montaComboNomeInventario();
	});
});

function montaComboNomeInventario()
{
	$.ajax({
		type: "POST",
		url: systemName+"/inventario/monta-combo-nome-inventario",
		data: {"cd_area_atuacao_ti" : $("#cd_area_atuacao_ti").val()},
		success: function(retorno){
			$('#cd_inventario').html(retorno);
		}
	});
}