$(document).ready(function(){
    $("#btnAlterarParcela").hide();
    //Captura o campo de proxima parcela.
    $('#proximaParcela').html($('#proxima_parcela_hidden').val());
    //captura o click do botão para cadastrar a parcela
    $("#btnAddParcela").click(function(){
        validaDadosParcela('S');
    });
    $("#btnAlterarParcela").click(function(){
        validaDadosParcela('A');
    });
});

/**
 * Configuração da inicialização das informações da Parcela
 */
function configCriacaoParcela()
{
    //Incluida function para que a grid possa apresentar sem adicionar proposta
    atualizaGridParcela();
    //Atualiza as horas disponivel do projeto
    atualizaHorasDisponivelAjax();
    $('#config_hidden_criacao_parcela').val('S');
}

function validaDadosParcela(decisao)
{
    if($("#mes").val() == '0'){
        showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_MES,$("#mes"));
        return false;
    }
    if($("#ano").val() == '0'){
        showToolTip(i18n.L_VIEW_SCRIPT_SELECIONE_ANO,$("#ano"));
        return false;
    }
    if($("#horas_parcela").val() == ''){
        showToolTip(i18n.L_VIEW_SCRIPT_INFORME_QTD_HORAS,$("#horas_parcela"));
        return false;
    }
    if(decisao == "S"){
        salvarParcelaValidacao();
    } else {
        alterarParcelaValidacao();
    }
}

// Realiza a pesquisa de profissionais por projeto e atualiza os selects.
function atualizaHorasDisponivelAjax()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/criar-parcela/horas-projeto-disponivel",
        data	: "cd_projeto="+$("#cd_projeto").val()+"&cd_proposta="+$("#cd_proposta").val(),
        dataType: 'json',
        success	: function(retorno){
            atualizaHorasTotal();
            $("#horasPropostaTotal").html(retorno.toString());
            $("#cd_horas_disponivel").val(retorno);
            $("#cd_horas_disponivel_antigo").val(retorno);
        }
    });
}

function atualizaHorasTotal()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/elaboracao-proposta/valor-hora-proposta",
        data	: "cd_projeto="+$("#cd_projeto").val()+"&cd_proposta="+$("#cd_proposta").val(),
        dataType: 'json',
        success	: function(retorno){
            $("#quantidadeHorasTotal").html(retorno[0]);
            $("#porcentagemHorasProposta").html(retorno[1]);
        }
    });
}

function atualizaGridParcela()
{
    $.ajax({
        type	: "POST",
        url		: systemName+"/criar-parcela/pesquisa-parcelas-proposta",
        data	: "cd_projeto="+$("#cd_projeto").val()+"&cd_proposta="+$("#cd_proposta").val(),
        success	: function(retorno){
            $("#parcelas").removeClass('hide');
            $("#parcelas").html(retorno);
        }
    });
}

function recuperaParcela(cd_parcela, qtd_horas, mes, ano, ni_parcela)
{
    $("#cd_parcela"			).val(cd_parcela);
    $("#mes"				).val(mes);
    $("#ano"				).val(ano);
    $("#horas_parcela"		).val(qtd_horas);
    $("#proximaParcela"		).html(ni_parcela);
    $("#ni_parcela_hidden"	).val(ni_parcela);
    $("#btnAddParcela"		).hide();
    $("#btnAlterarParcela"	).show();
    var horas_restantes = 0;
    //	horas_restantes = (parseInt($("#cd_horas_disponivel_antigo").val())+parseInt($("#horas_parcela").val()));
    horas_restantes = (parseFloat($("#cd_horas_disponivel_antigo").val())+parseFloat($("#horas_parcela").val()));
    $("#cd_horas_disponivel").val(''+horas_restantes);
    $("#horasPropostaTotal"	).html(''+horas_restantes);
} 

function salvarParcelaValidacao()
{
    //Variavel para contabilizar as horas disponiveis
    var horas_restantes = 0;
	
    if($("#cd_horas_disponivel").val() < 0 ){
        salvarParcela();
    } else {
        if($("#cd_horas_disponivel").val() > 0 ){
            //atualiza as horas disponiveis do projeto
            salvarParcela();
        } else {
            alertMsg(i18n.L_VIEW_SCRIPT_SEM_HORAS_DISPONIVEL);
            return false;
        }
    }
}

function salvarParcela(horas_restantes){
    var horas_restantes = ($("#cd_horas_disponivel").val()-$("#horas_parcela").val());
    //Variavel para a quantidade de parcela
    var proxima_parcela = $("#proxima_parcela_hidden").val();
    $.ajax({
        type	: "POST",
        url		: systemName+"/criar-parcela/criar-parcela",
        data	: "cd_projeto="+$("#cd_projeto").val()+
        "&cd_proposta="+$("#cd_proposta").val()+
        "&mes="+$("#mes").val()+
        "&ano="+$("#ano").val()+
        "&horas_parcela="+$("#horas_parcela").val()+
        "&proxima_parcela_hidden="+$("#proxima_parcela_hidden").val(),
        success	: function(retorno){
            proxima_parcela++;
            atualizaHorasDisponivelAjax();
            $("#horas_parcela").val("");
            $("#proximaParcela").html(proxima_parcela);
            $("#proxima_parcela_hidden").val(proxima_parcela);
            atualizaGridParcela();
            $("#mes").add("#ano").val("0");
            $("#horas_parcela").val("");
            $("#cd_horas_disponivel").val(horas_restantes);
            $("#cd_horas_disponivel_antigo").val(horas_restantes);
            $("#horasPropostaTotal").val(horas_restantes);
            limparParcela();
            alertMsg(retorno);
            atualizaParcela();
            ajaxParcelaSemProduto();
        }
    });
}

function alterarParcelaValidacao()
{
    //Variavel para contabilizar as horas disponiveis
    var horas_restantes = 0;
    //atualiza as horas disponiveis do projeto
    if($("#cd_horas_disponivel").val() < 0){
        alterarParcela();
    } else {
        if($("#cd_horas_disponivel").val() > 0){
            horas_restantes = ($("#cd_horas_disponivel").val()-$("#horas_parcela").val());
            alterarParcela();
        } else {
            alertMsg(i18n.L_VIEW_SCRIPT_SEM_HORAS_DISPONIVEL);
            return false;
        }
    }
}

function alterarParcela()
{
    var horas_restantes = ($("#cd_horas_disponivel").val()-$("#horas_parcela").val());
    $.ajax({
        type	: "POST",
        url		: systemName+"/criar-parcela/alterar-parcela",
        data	: "cd_projeto="+$("#cd_projeto").val()+
        "&cd_proposta="+$("#cd_proposta").val()+
        "&mes="+$("#mes").val()+
        "&ano="+$("#ano").val()+
        "&horas_parcela="+$("#horas_parcela").val()+
        "&cd_parcela="+$("#cd_parcela").val(),
        success: function(retorno){
            atualizaHorasDisponivelAjax();
            $("#horas_parcela").val("");
            atualizaGridParcela();
            $("#mes").add("#ano").val("0");
            $("#horas_parcela").val("");
            $("#cd_horas_disponivel").val(horas_restantes);
            $("#cd_horas_disponivel_antigo").val(horas_restantes);
            $("#horasPropostaTotal").val(horas_restantes);
            limparParcela();
            alertMsg(retorno);
            atualizaParcela();
        }
    });
}

function excluirParcela(cd_parcela)
{
    confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR,function(){
        $.ajax({
            type: "POST",
            url: systemName+"/criar-parcela/excluir-parcela",
            data: "cd_parcela=" + cd_parcela + "&cd_projeto=" + $("#cd_projeto").val() + "&cd_proposta=" + $("#cd_proposta").val(),
            success: function(retorno){
                atualizaGridParcela();
                atualizaHorasDisponivelAjax();
                alertMsg(retorno);
                ajaxParcelaComProduto();
                ajaxParcelaSemProduto();
                atualizaParcela();
            }
        });
    });
}

function limparParcela()
{
//    $('#mes'			).val("0");
//    $('#ano'			).val("0");
    $('#horas_parcela'		).val("");
    $("#btnAlterarParcela"	).hide();
    $("#btnAddParcela"		).show();
}

function atualizaParcela()
{
    $.ajax({
        type   : "POST",
        url    : systemName+"/criar-parcela/pesquisa-ultima-Parcela",
        data   : "cd_projeto=" + $("#cd_projeto").val(),
        success: function(retorno){
            $('#proximaParcela').html(retorno);
            $('#proxima_parcela_hidden').val(retorno);
        }
    });
}