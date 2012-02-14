$(document).ready( function() {
    $("#mapa_site").treeview({
        animated	: "fast",
        collapsed	: true,
        unique		: true,
        persist		: "cookie",
        toggle		: function() {
            window.console && console.log("%o was toggled", this);
        }
    });
});
        
    