$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioCatalogoProjeto') , 'catalogo-projeto/catalogo-projeto' );
        return true;
    });
});