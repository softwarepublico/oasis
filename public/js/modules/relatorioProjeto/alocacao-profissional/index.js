$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        gerarRelatorio( $('#formRelatorioProjetoPorProfissional') , 'alocacao-profissional/generate' );
        return true;
    });
});