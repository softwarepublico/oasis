$(document).ready( function() {
    $("img.openTrocaContrato").click( function() {
        abreModalBoxTrocaContrato();
    });
});

function abreModalBoxTrocaContrato(){

    eval('var buttons = {"'+i18n.L_VIEW_SCRIPT_BTN_FECHAR+'": '+function(){closeDialog( 'dialog_box_troca_contrato' );}+'};');
	loadDialog({
		   id       : 'dialog_box_troca_contrato',
		   title    : i18n.L_VIEW_SCRIPT_TITLE_DIALOG_TROCA_CONTRATO,
		   url		: systemName+"/inicio/box-troca-contrato",
		   height	: 400,
		   width	: 'auto',
		   buttons  : buttons
		});

}