$(document).ready(  function() {
     $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioDiversoItemParecer') , 'item-parecer/item-parecer' );
        return true;
    });
});