$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioExtratoGeralExecucaoContrato') , 'extrato-geral-execucao-contrato/extrato-geral-execucao-contrato' );
        return true;
    });
});