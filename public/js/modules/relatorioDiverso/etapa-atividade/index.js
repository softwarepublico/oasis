$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioEtapaAtividade') , 'etapa-atividade/etapa-atividade' );
        return true;
    });
});