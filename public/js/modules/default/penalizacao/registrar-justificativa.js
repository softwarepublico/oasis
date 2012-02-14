var _btnSalvarJustificativa,_btnCancelarJustificativa;

$(document).ready(function(){

    _btnSalvarJustificativa   = $('#btn_salvar_justificativa');
    _btnCancelarJustificativa = $('#btn_cancelar_justificativa');


    _btnCancelarJustificativa.click(function(){
        $("#liRegistrarJustificativa").hide();
        $("#container-penalizacao"   ).triggerTab(2);
    });

    _btnSalvarJustificativa.click(_fnSaveJustificativa);

});

function _fnSaveJustificativa(){
    if(validaForm('#registroJustificativa')){
        $.ajax({
            type	: "POST",
            url		: systemName+"/penalizacao/salvar-justificativa",
            data	: $('#registroJustificativa :input').serialize(),
            success	: function(retorno){
                alertMsg(retorno,null,function(){
                    _btnCancelarJustificativa.trigger('click');
                    gridJustificativaPenalizacao();
                });
            }
        });
    }else{
        return false;
    }
}