var _btnSalvarAceiteJustificativa,_btnCancelarAceiteJustificativa;

$(document).ready(function(){

    _btnSalvarAceiteJustificativa   = $('#btn_salvar_aceite_justificativa');
    _btnCancelarAceiteJustificativa = $('#btn_cancelar_aceite_justificativa');

    _btnCancelarAceiteJustificativa.click(function(){
        $("#liAnaliseJustificativa").hide();
        $("#container-penalizacao" ).triggerTab(2);
    });
    _btnSalvarAceiteJustificativa.click(_fnSaveJustificativa);
});

function _fnSaveJustificativa(){
    if(validaForm('#aceiteAnaliseJustificativa')){
        $.ajax({
            type	: "POST",
            url		: systemName+"/penalizacao/salvar-aceite-justificativa",
            data	: $('#aceiteAnaliseJustificativa :input').serialize(),
            success	: function(retorno){
                alertMsg(retorno,null,function(){
                    _btnCancelarAceiteJustificativa.trigger('click');
                    gridJustificativaPenalizacao();
                });
            }
        });
    }else{
        return false;
    }
}