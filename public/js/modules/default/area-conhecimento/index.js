$(document).ready(function(){
	$('#btn_alterar_conhecimento').hide();
	$('#btn_cancelar_conhecimento').hide();
	limpaCampos();
	gridAreaConhecimento();	

	$('#btn_salvar_conhecimento').click(function(){
		salvarAreaConhecimento();
	});
	$('#btn_alterar_conhecimento').click(function(){
		alterarAreaConhecimento();
	});
	$('#btn_cancelar_conhecimento').click(function(){
		limpaCampos();
	});
});

function salvarAreaConhecimento()
{
	if(!validaForm()){return false}
	$.ajax({
		type: "POST",
		url: systemName+"/area-conhecimento/salvar-area-conhecimento",
		data: $('#formAreaConhecimento :input').serialize(),
		success: function(retorno){
			limpaCampos();
			gridAreaConhecimento();
			alertMsg(retorno);
		}
	});
}

function alterarAreaConhecimento()
{
	if(!validaForm()){return false}
	$.ajax({
		type: "POST",
		url: systemName+"/area-conhecimento/alterar-area-conhecimento",
		data: $('#formAreaConhecimento :input').serialize(),
		success: function(retorno){
			limpaCampos();
			gridAreaConhecimento();
			alertMsg(retorno);
		}
	});
}


function excluirAreaConhecimento(cd_area_conhecimento)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
        $.ajax({
            type: "POST",
            url: systemName+"/area-conhecimento/excluir-area-conhecimento",
            data: "cd_area_conhecimento="+cd_area_conhecimento,
            success: function(retorno){
                gridAreaConhecimento();
                alertMsg(retorno);
            }
        });
    });
}

function gridAreaConhecimento()
{
	$.ajax({
		type: "POST",
		url: systemName+"/area-conhecimento/grid-area-conhecimento",
		success: function(retorno){
			$('#gridAreaConhecimento').html(retorno);
		}
	});
}

function limpaCampos()
{
	$('#cd_area_conhecimento').val("");
	$('#tx_area_conhecimento').val("");
	$('#btn_salvar_conhecimento').show();
	$('#btn_alterar_conhecimento').hide();
	$('#btn_cancelar_conhecimento').hide();
}

function recuperaAreaConhecimento(cd_area_conhecimento)
{
	$.ajax({
		type: "POST",
		url: systemName+"/area-conhecimento/recupera-area-conhecimento",
		data: "cd_area_conhecimento="+cd_area_conhecimento,
		dataType: "json",
		success: function(retorno){
			$('#btn_salvar_conhecimento').hide();
			$('#btn_alterar_conhecimento').show();
			$('#btn_cancelar_conhecimento').show();
			$('#cd_area_conhecimento').val(retorno[0]['cd_area_conhecimento']); 
			$('#tx_area_conhecimento').val(retorno[0]['tx_area_conhecimento']);
		}
	});	
}