$(document).ready(  function() {
    
    
    $('#ni_ano_solicitacao').change(setupAutocomplete);
    $('#cd_objeto'         ).change(setupAutocomplete);
    
    $('#ni_ano_solicitacao').trigger('change');
    
    $('#btn_gerar').click( function(){
        if( !validaForm() ){return false;}
        gerarRelatorio( $('#formRelatorioDemandaChamadoTecnico') , 'chamado-tecnico/generate' );
        return true;
    });
});

function setupAutocomplete(){
    $.ajax({
		type    : "POST",
		url     : systemName+"/"+systemNameModule+"/chamado-tecnico/get-solicitacao-autocomplete",
        async   :false,
		data    : {"cd_objeto"         :$("#cd_objeto").val(),
                   "ni_ano_solicitacao":$('#ni_ano_solicitacao').val()},
        dataType: 'json',
		success: function(retorno){
            $("#ni_solicitacao").val('');
			$("#ni_solicitacao").unautocomplete()
                                .autocomplete(retorno,{
                                    minChars: 0,
                                    autoFill: true,
                                    mustMatch: true,
                                    matchContains: false,
                                    scrollHeight: 220,
                                    formatItem: function(row, i, max) {
                                        return row.ni_solicitacao+'/'+row.ni_ano_solicitacao;
                                    },
                                    formatMatch: function(row, i, max) {
                                        return row.ni_solicitacao+'/'+row.ni_ano_solicitacao;
                                    },
                                    formatResult: function(row) {
                                        return row.ni_solicitacao;
                                    }
                                });
		}
	});
}