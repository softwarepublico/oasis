$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoRegistroHistorico') , 'registro-historico/generate' );
        return true;
    });
});