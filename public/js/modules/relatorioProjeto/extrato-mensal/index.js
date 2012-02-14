$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorio') , 'extrato-mensal/generate' );
        return true;
    });
 });
