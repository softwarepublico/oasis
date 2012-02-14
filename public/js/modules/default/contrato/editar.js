$(document).ready(function(){
	$("#cd_empresa").change(function() {
		window.location.href = systemName+"/contrato/index/cd_empresa/" + $("#cd_empresa").val();	
	});
	
	// pega evento no onclick do botao
	$("#submitbutton").click(function(){
		$("form#contrato").submit();
	});
});