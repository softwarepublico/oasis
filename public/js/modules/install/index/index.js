$(document).ready(function(){
	$('#barraMenu'    ).hide();
	$('#nomeUsuario'  ).hide();
	$('#menuContainer').hide();
	//Inicializa a tela para definição do idioma do sistema
	passoUmIdioma();
});

/**
 * Função que ira mostrar a tela de Languagem
 */
function passoUmIdioma()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-um-idioma",
		success: function(retorno){
			$('#language').html(retorno);
		}
	});
}

/**
 * Funcão que ira mostrar  a tela de Apresentação
 */
function passoDoisApresentacao()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-dois-apresentacao",
		data: $('#language :imput').serialize(),
		success: function(retorno){
			$('#apresentacao').html(retorno);
			$('#container-install').enableTab(2)
								   .triggerTab(2);
		}
	});
}

/**
 * Funcão que ira mostrar  a tela de Licença
 */
function passoTresLicenca()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-tres-licenca",
		success: function(retorno){
			$('#licenca').html(retorno);
			$('#container-install').enableTab(3)
								   .triggerTab(3);
		}
	});
}

/**
 * Funcão que ira identificar se as pastas e arquivos
 * necessários possuem permissão.
 */
function passoQuatroVerificarPermissao()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-quatro-verifica-permissao",
		success: function(retorno){
			$('#verifica_permissao').html(retorno);
			$('#container-install').enableTab(4)
								   .triggerTab(4);
		}
	});
}

/**
 * Função que das configurações geral do sistema
 */
function passoCincoConfiguracaoBancoDeDados()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-cinco-configuracao-banco-de-dados",
		data: {"sistemName":systemName},
		success: function(retorno){
			$('#configuracao_banco_de_dados').html(retorno);
			$('#container-install').enableTab(5)
								   .triggerTab(5);
		}
	});
}

/**
 * Função que das configurações geral do sistema
 */
function passoSeisConfiguracaoConstantes()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-seis-configuracao-constantes",
		success: function(retorno){
			$('#configuracao_constantes').html(retorno);
			$('#container-install').enableTab(6)
								   .triggerTab(6);
		}
	});
}

/**
* Função que atualiza o arquivo de constante do sistema
*/
function passoSeteCriaConstantes() {
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-sete-cria-constantes",
		success: function(retorno){
			$('#constantes_criadas').html(retorno);
			$('#container-install').enableTab(7)
								   .triggerTab(7);
		}
	});
}

/**
 * Função que cria as tabelas no banco de dados
 */
function passoOitoCriaTabelas() {
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-oito-cria-tabelas",
		success: function(retorno){
			$('#tabelas_criadas').html(retorno);
			$('#container-install').enableTab(8)
								   .triggerTab(8);
		}
	});
}

/**
 * Função que cria a senha do usuário Administrador
 */
function passoNoveDefinicaoDadosSistema() {
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-nove-definicao-dados-sistema",
		success: function(retorno){
			$('#defini_administrador').html(retorno);
			$('#container-install').enableTab(9)
								   .triggerTab(9);
		}
	});
}

/**
 * Função que cria a tela de finalização do sistema
 */
function passoDezFinalizaSistema()
{
	$.ajax({
		type: "POST",
		url: systemName+"/"+systemNameModule+"/index/passo-dez-finaliza-instalacao",
		success: function(retorno){
			$('#finaliza_instalacao').html(retorno);
			$('#container-install').tabs({
					disabled: [1,2,3,4,5,6,7,8,9]
			});
			$('#container-install').enableTab(10)
							       .triggerTab(10);
		}
	});
}
