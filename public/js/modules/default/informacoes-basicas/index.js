$(document).ready( function() {
    $("#btnIr").click( function() {
        var link = $(".optLink:checked").val();
        if( link != undefined ){
            window.location.href = link;
            return true;
        } else {
            alertMsg(i18n.L_VIEW_SCRIPT_ESCOLHA_OPCAO, 3);
            return false;
        }
    });
});