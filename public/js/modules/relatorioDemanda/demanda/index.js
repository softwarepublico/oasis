$(document).ready(function(){
	$('#btn_gerar').click(function(){
		abreRelatorio();
	});
})
function abreRelatorio()
{
	if(!validaForm()){ return false; }
	gerarRelatorio( $('#formRelatorioDemanda'), 'demanda/demanda');
}