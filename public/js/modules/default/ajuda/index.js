$(document).ready( function() {
    $("#treeview_ajuda").treeview({
        animated	: "fast",
        collapsed	: true,
        unique		: true,
        persist		: "cookie",
        toggle		: function() {
            window.console && console.log("%o was toggled", this);
        }
    });
});


function abreAjudaPagina( paginaPhtml ){

	$.ajax({
		type: "POST",
		url: systemName+"/ajuda/abre-pagina-ajuda",
		data: {'paginaPhtml':paginaPhtml},
		success: function(retorno){
			$("#img_da_ajuda").html(retorno);
		}
	});
}