function config_form_dados_tecnicos()
{
	if( $("#config_hidden_dados_tecnicos_aba_info_tec").val() === "N" ){
		ajaxGridInformacoesTecnicas();
	}
	if( $("#config_hidden_dados_tecnicos_aba_conhecimento").val() === "N" ){
		pesquisaTipoConhecimentoAjax();
	}
}