$(document).ready( function() {
    $("img.openBox").click( function() {
        abreModalBoxManager();
    });
});

function marcaLista()
{
    var arrBox = retornaSerializeBoxList().split(',');
    $(".ck_box").attr('checked', false);
    for( i=0;i<arrBox.length;i++ ){
        $("#ck_" + arrBox[i]).attr('checked', 'checked');
    }
}

function marcaTodos( ck )
{
    if (ck) {
        $(".ck_box").attr('checked', 'checked');
    }else {
        $(".ck_box").attr('checked', false);
    }
}

function recuperaListaBoxes()
{
	var strPosicao = $(".ck_box").serialize();

        strPosicao = strPosicao.replace( /ck_/g , '' ); 
        strPosicao = strPosicao.replace( /\&/g , '' ); 
        strPosicao = strPosicao.replace( /\=/g , ',' );
        strPosicao = strPosicao.substr(0,strPosicao.length -1);

	gravaPosicaoBox( strPosicao, true );
}

function abreModalBoxManager()
{
    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_CANCELAR+'": '+function(){closeDialog('dialog_box_manager_dialog');}+', "'+i18n.L_VIEW_SCRIPT_BTN_ATUALIZAR+'": '+function(){recuperaListaBoxes();}+'};');
	loadDialog({
		   id       : 'dialog_box_manager_dialog',
		   title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_MANUTENCAO_BOX,
		   url		: systemName+"/inicio/box-manager",
		   height	: 'auto',
		   width	: 322,
		   buttons  : buttons
		});
	marcaLista();
}

function fechaBox( objQuery )
{
    var idBox = objQuery.attr('id');
    $("li#"+idBox).remove();
    gravaPosicaoBox();
}