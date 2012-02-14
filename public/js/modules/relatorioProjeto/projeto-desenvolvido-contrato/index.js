$(document).ready(function(){
    $('#btn_gerar').click( function(){
        if( !validaForm() ){ return false; }
        if( !validaImpacto() ){ return false; }
        gerarRelatorio( $('#formRelatorioProjetoDesenvolvidoContrato'), 'projeto-desenvolvido-contrato/projeto-desenvolvido-contrato');
        return true;
    });
});

function validaImpacto(){
	var retorno = true;
	if(!$('#rel_interno').attr('checked') &&
	   !$('#rel_externo').attr('checked') &&
	   !$('#rel_ambos').attr('checked')
	  ){
		showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_IMPACTO, $('#fieldset_impacto'));
		retorno = false;
	}
	return retorno;
}