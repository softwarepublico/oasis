$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioJustificativaSolicitacao') , 'justificativa-solicitacao/justificativa-solicitacao' );
        return true;
    });
});