$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoChecklistProposta') , 'checklist-proposta/generate' );
        return true;
    });
});
