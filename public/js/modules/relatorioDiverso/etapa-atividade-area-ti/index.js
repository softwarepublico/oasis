$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioEtapaAtividadeAreaTi') , 'etapa-atividade-area-ti/etapa-atividade-area-ti' );
        return true;
    });
});