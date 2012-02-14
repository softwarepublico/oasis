$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoAlocacaoRecursoContrato') , 'alocacao-recurso-contrato/alocacao-recurso-contrato' );
        return true;
    });
});