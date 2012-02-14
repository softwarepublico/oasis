$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoChecklistParcela') , 'checklist-parcela/generate' );
        return true;
    });
});
