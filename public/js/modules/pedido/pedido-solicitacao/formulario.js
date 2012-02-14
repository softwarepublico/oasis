
function submeteQuestionario()
{
    if(!validaFormularioQuestionarioPedido()){return false;}

    $("#form_preenchimento_questionario").submit();
}

function validaFormularioQuestionarioPedido()
{
    var retorno = true;
    $('#form_preenchimento_questionario div.pergunta-obrigatoria').each(function(){

        $('#'+this.getAttribute("id")+' .campo-obrigatorio').each(function(){

            if((this.getAttribute("type")=='radio')||(this.getAttribute("type")=='checkbox')){
                if($("input[name="+this.getAttribute("name")+"]:checked").val() == undefined){
                    alertMsg(i18n.L_VIEW_SCRIPT_PERGUNTA_OBRIGATORIA_SEM_RESPOSTA, 2);
                    retorno = false;
                }
            }
            if( $(this .textarea) && ($(this).val() == '') ){
                alertMsg(i18n.L_VIEW_SCRIPT_PERGUNTA_OBRIGATORIA_SEM_RESPOSTA, 2);
                retorno = false;
            }
        });
    });
    if(retorno){
        if($("#tx_observacao_pedido").val() == ''){
            showToolTip(i18n.L_VIEW_SCRIPT_CAMPO_OBRIGATORIO, $("#tx_observacao_pedido"));
            retorno = false;
        }
    }
    return retorno;
}