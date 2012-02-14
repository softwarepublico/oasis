$(document).ready(function(){
	$('#btn_gerar').click(function(){
		abreRelatorio();
	});
})
function abreRelatorio()
{
	if(!validaForm()){ return false; }
	gerarRelatorio( $('#formRelatorioDemandaDetalhe'), 'demanda-detalhe/demanda-detalhe');
}