$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoSituacao') , 'situacao-projeto/situacao-projeto' );
        return true;
    });
});