$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioExtratoGeralExecucaoContratoCusto') , 'extrato-geral-execucao-contrato-custo/extrato-geral-execucao-contrato-custo' );
        return true;
    });
});