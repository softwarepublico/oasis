$(document).ready(  function() {
   $('#btn_gerar').click( function(){
       if( !validaForm() ){ return false; }
       $('#tx_objeto').val($('#cd_objeto :selected').text());
       gerarRelatorio( $('#formRelatorioProjetoProfissional') , 'profissional/generate' );
       return true;
   });
});