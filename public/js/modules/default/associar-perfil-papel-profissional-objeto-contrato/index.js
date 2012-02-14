$(document).ready(function(){
	$('#container_associar_perfil_papel_profissional_objeto_contrato').show()
                                                                      .tabs()
                                                                      .triggerTab(1);
	$("#config_hidden_associar_perfil_papel_profissional_objeto_contrato").val('N');
});

function configAccordionAssociarPerfilPapelProfissionalObjetoContrato()
{
   if($('#config_hidden_associar_perfil_papel_profissional_objeto_contrato').val() == 'N'){

       if(($("#cd_area_atuacao_associar_perfil_profissional_objeto_contrato").val() != '0') && ($("#cd_objeto_associar_perfil_profissional_objeto_contrato").val() != '-1')){
            pesquisaPerfilProfissionalObjetoContrato();
        }
       if(($("#cd_area_atuacao_associar_papel_profissional_objeto_contrato").val() != '0') && ($("#cd_objeto_associar_papel_profissional_objeto_contrato").val() != '-1')){
            pesquisaPapelProfissionalObjetoContrato();
        }
        $("#config_hidden_associar_perfil_papel_profissional_objeto_contrato").val('S');
   }
}
