$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoDistribuicaoRecurso') , 'distribuicao-recurso/distribuicao-recurso' );
        return true;
    });
});