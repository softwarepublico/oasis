$(document).ready(function(){
	$("#btn_gerar").click(function(){
		if(!validaForm("#form_relatorio_penalizacao")){return false};
		gerarRelatorio( $('#form_relatorio_penalizacao') , 'penalizacao/penalizacao' );
	});
});
