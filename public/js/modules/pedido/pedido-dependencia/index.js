$(document).ready(function(){
    montaComboPergunta();

    $('#cd_pergunta').change(function(){
        montaComboResposta();
        limpaFlipFlop();
    });

    $('#cd_resposta').change(function(){
        limpaFlipFlop();
        montaFlipFlop();
    });

	// ao clicar no botao >> os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#add_pergunta").click(function() {
        if(!validaForm('#div_dependencia_pergunta')){return false}

		var perguntas = "[";
		$('#cd_pergunta_1 option:selected').each(function() {
			perguntas += (perguntas == "[") ? $(this).val() : "," + $(this).val();
		});
		perguntas += "]";
		$.ajax({
			type    : "POST",
            url		: systemName+"/"+systemNameModule+"/pedido-dependencia/associa-pergunta",
			data    : {'cd_pergunta': $('#cd_pergunta').val(),
                       'cd_resposta': $('#cd_resposta').val(),
                       'perguntas'  : perguntas},
			success : function(){
                montaFlipFlop();
			}
		});
	});

	// ao clicar no botao << os dados sao submetidos ao server que atualiza as tabelas do banco de dados por intermedio do ajax
	$("#remove_pergunta").click(function() {
        if(!validaForm('#div_dependencia_pergunta')){return false}
            
		var perguntas = "[";
		$('#cd_pergunta_2 option:selected').each(function() {
			perguntas += (perguntas == "[") ? $(this).val() : "," + $(this).val();
		});
		perguntas += "]";
		$.ajax({
			type    : "POST",
			url		: systemName+"/"+systemNameModule+"/pedido-dependencia/desassocia-pergunta",
			data    : {'cd_pergunta': $('#cd_pergunta').val(),
                       'cd_resposta': $('#cd_resposta').val(),
                       'perguntas'  : perguntas},
			success : function(){
			    montaFlipFlop();
			}
		});
	});
});

function montaComboPergunta()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-dependencia/combo-pergunta",
		success	: function(retorno){
			$("#cd_pergunta").html(retorno);
		}
	});
}

function montaComboResposta()
{
    $.ajax({
		type	: "POST",
		url		: systemName+"/"+systemNameModule+"/pedido-dependencia/combo-resposta",
        data    : {'cd_pergunta':$('#cd_pergunta').val()},
		success	: function(retorno){
			$("#cd_resposta").html(retorno);
		}
	});
}

function montaFlipFlop()
{
    if($("#cd_resposta").val() != 0){
        $.ajax({
            type    : "POST",
            url		: systemName+"/"+systemNameModule+"/pedido-dependencia/pesquisa-pergunta",
            data    : {"cd_pergunta":$("#cd_pergunta").val(),"cd_resposta":$("#cd_resposta").val()},
            dataType: 'json',
            success : function(retorno){
                $("#cd_pergunta_1").html(retorno[0]);
                $("#cd_pergunta_2").html(retorno[1]);
            }
        });
    }
}

function limpaFlipFlop()
{
    $("#cd_pergunta_1").empty();
    $("#cd_pergunta_2").empty();
}