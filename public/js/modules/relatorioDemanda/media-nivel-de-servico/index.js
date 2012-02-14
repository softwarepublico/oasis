$(document).ready(function(){
	$('#btn_gerar').click(function(){
		abreRelatorio();
	});
})
function abreRelatorio()
{
	if(!validaForm()){ return false; }
	    gerarRelatorio( $('#formRelatorioMediaNivelDeServico'), 'media-nivel-de-servico/media-nivel-de-servico');
    return true;
}


