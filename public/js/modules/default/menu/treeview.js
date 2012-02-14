function verificaItens(o, id) {

	var strId	 = "#"+id;
	var idParent = $(strId).attr('id_pai');

	arr.push(id);
	arr.push(idParent);

	if (id != idParent) {
		verificaItens(o, idParent);
	}
}

function isArray(o){
	return(typeof(o.length)=="undefined")? true : false;
}

$(document).ready(function(){

	var dados = '<?php echo Zend_Json_Encoder::encode($menu);?>';
	$("#red").treeview({
		animated	: "fast",
		collapsed	: true,
		unique		: true,
		persist		: "cookie",
		toggle		: function() {
			window.console && console.log("%o was toggled", this);
		}
	});

	$('input:checkbox').click( function() {
		id 		 = $(this).attr('id');
    	arr = new Array();

    	strIdClickedCheck = "#"+id;

    	if ($(strIdClickedCheck).attr('checked')) {
			verificaItens(dados, id);
	
			for (var i=0; i<arr.length; i++) {
				str = "#" + arr[i];
				$(str).attr('checked', 'checked');
			}    		
    	} else {
    		$(strIdClickedCheck).removeAttr('checked');
    	}
	});
});