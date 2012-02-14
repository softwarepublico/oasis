$(document).ready(function(){
    //verifica se o usuário ira sair da pagina mesmo
//    window.onbeforeunload=goodbye;

/**
tela de Reunião
*/
	$('#submitReuniaoGeral'      ).hide();
	$('#adicionarReuniaoGeral'	 ).show();
	$('#alterarReuniaoGeral'	 ).hide();
	$('#cancelarReuniaoGeral'	 ).hide();
	
	$('#adicionarReuniaoGeral').click(function(){
		//função esta no java script da reunião reuniao/index.js
		salvarDadosReuniaoGeral();
	});
	$('#alterarReuniaoGeral').click(function(){
		//função esta no java script da reunião reuniao/index.js
		alterarReuniaoGeral()
	});
	$('#cancelarReuniaoGeral').click(function(){
		//função esta no java script da reunião reuniao/index.js
		cancelarReuniaoGeral();
	});
});



