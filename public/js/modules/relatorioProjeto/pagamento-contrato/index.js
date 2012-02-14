$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoPagamentoContrato') , 'pagamento-contrato/pagamento-contrato' );
        return true;
    });
});