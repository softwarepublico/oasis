$(document).ready(function(){
	$('#btn_gerar').click(function(){
		abreRelatorio();
	});
})
function abreRelatorio()
{
	if(!validaForm()){ return false; }
	gerarRelatorio( $('#formRelatorioCustoDemandaServico'), 'custo-contrato-tipo-demanda/custo-contrato-tipo-demanda');
}