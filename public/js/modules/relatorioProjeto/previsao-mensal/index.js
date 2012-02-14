$(document).ready(  function() {
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        if( !validaAno() ){ return false; }
        if( !validaQuantidadeMese() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoPrevisaoMensal') , 'previsao-mensal/generate' );
        return true;
    });
    
    $('#ano_final').change(function(){
    	validaAno();
    });
    $('#ano_inicial').change(function(){
    	validaAno();
    });
});
function validaAno()
{
	removeTollTip();
	if($('#ano_final').val() != 0){
	  	if($('#ano_final').val() < $('#ano_inicial').val() ){
	  		showToolTip(i18n.L_VIEW_SCRIPT_DT_ANO_FINAL_MAIOR_ANO_INICIAL, $('#ano_final'));
	  		return false;
  		}
  	}
	return true;
}

function validaQuantidadeMese()
{
	var qtdAno = 0;
	if($('#ano_final').val() != 0){
		qtdAno = $('#ano_final').val() - $('#ano_inicial').val();
		if(qtdAno > 1){
			alertMsg(getTranslaterJsComVariaveis(i18.L_VIEW_SCRIPT_INTERVALO_ANO_MAIOR_QUE, new Array(1)),2);
			return false;
		}
	}
	if(qtdAno != 0){
		if($('#mes_final').val() != 0){
			var mes = (13 - $('#mes_inicial').val()) + parseInt($('#mes_final').val());
			if(mes >= 13 ){
				alertMsg(getTranslaterJsComVariaveis(i18.L_VIEW_SCRIPT_QTD_MESES_SUPERIO_A, new Array(12)),2);
				return false;
			}
		}
	}
	return true;
}