$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioDadosContrato') , 'dados-contrato/dados-contrato' );
        return true;
    });
});