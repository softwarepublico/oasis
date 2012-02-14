$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoChecklistProjeto') , 'checklist-projeto/generate' );
        return true;
    });
});
