$(document).ready(function(){

	$('#bt_excluir_profissional' ).hide();
	$('#container_painel_profissional').triggerTab(1);

	$("#bt_excluir_profissional").click(excluirProfissional);
	// pega evento no onclick do botao
	$("#submitbuttonProfissional").click(salvarProfissional);
	
	$('#bt_cancelar_profissional').click(function(){
		$('#li-profissional').hide();
		$('#container_painel_profissional').triggerTab(1);
		limpaFormNovoProfissional();
	});
});

function salvarProfissional()
{
	if(!validaForm("#form_profissional")){return false;}
	$.ajax({
		type    : "POST",
		url     : systemName+"/profissional/salvar",
		data    : $('#form_profissional :input').serialize(),
		dataType: 'json',
		success	: function(retorno){
			if(retorno['erro'] == true){
				alertMsg(retorno['msg'],retorno['type']);
			} else {
				alertMsg(retorno['msg'],retorno['type'],'limpaFormProfissional()');
			}
		}
	});
}

function limpaFormProfissional()
{
	$('#form_profissional :input'		)
		.not('#st_inativo_lista'		)
		.not('#cd_empresa_lista'		)
		.not('#novo_profissional'		)
		.not('#bt_excluir_profissional'	)
		.not('#submitbuttonProfissional')
		.not('#bt_cancelar_profissional')
		.not('#st_inativo')
		.not('#st_dados_todos_contratos')
		.not('#st_nova_senha')
		.val('');

	$('#st_inativo'              ).removeAttr('checked');
	$('#st_dados_todos_contratos').removeAttr('checked');
	$('#st_nova_senha'           ).removeAttr('checked');

	montaGridProfissional();
	$('#submitbuttonProfissional'		).show();
	$('#bt_excluir_profissional'		).hide();
    $('#li-profissional'				).hide();
	$('#container_painel_profissional'	).triggerTab(1);
}

function recuperaProfissional(cd_profissional)
{
	$.ajax({
		type	: "POST",
		url		: systemName+"/profissional/recupera-profissional",
		data	: "cd_profissional="+cd_profissional,
		dataType:'json',
		success	: function(retorno){
			$('#cd_profissional'			).val(retorno[0]['cd_profissional'			 ]);
			$('#cd_empresa'					).val(retorno[0]['cd_empresa'				 ]);
			$('#cd_relacao_contratual'		).val(retorno[0]['cd_relacao_contratual'	 ]);
			$('#tx_profissional'			).val(retorno[0]['tx_profissional'			 ]);
			$('#tx_nome_conhecido'			).val(retorno[0]['tx_nome_conhecido'		 ]);
			$('#dt_nascimento_profissional'	).val(retorno[0]['dt_nascimento_profissional']);
			$('#tx_email_institucional'		).val(retorno[0]['tx_email_institucional'	 ]);
			$('#tx_email_pessoal'			).val(retorno[0]['tx_email_pessoal'			 ]);
			$('#tx_telefone_residencial'	).val(retorno[0]['tx_telefone_residencial'	 ]);
			$('#tx_celular_profissional'	).val(retorno[0]['tx_celular_profissional'	 ]);
			$('#tx_ramal_profissional'		).val(retorno[0]['tx_ramal_profissional'	 ]);
			$('#cd_perfil'					).val(retorno[0]['cd_perfil'				 ]);
			$('#tx_endereco_profissional'	).val(retorno[0]['tx_endereco_profissional'	 ]);
			$('#dt_inicio_trabalho'			).val(retorno[0]['dt_inicio_trabalho'		 ]);
			$('#dt_saida_profissional'		).val(retorno[0]['dt_saida_profissional'	 ]);
			
			if(retorno[0]['st_inativo'] == "S"){
				$('#st_inativo').attr('checked','checked');
			}else{
				$('#st_inativo').removeAttr('checked');
			}
			if(retorno[0]['st_dados_todos_contratos'] == "S"){
				$('#st_dados_todos_contratos').attr('checked','checked');
			}else{
				$('#st_dados_todos_contratos').removeAttr('checked');
			}
			if(retorno[0]['st_nova_senha'] == "S"){
				$('#st_nova_senha').attr('checked','checked');
			}else{
				$('#st_nova_senha').removeAttr('checked');
			}

			//utilizados se estiver na tab
			$('#submitbuttonMenu'		 ).show();
			$('#bt_excluir_profissional' ).show();
			$('#bt_cancelar_profissional').show();

			$('#li-profissional').show();
			$('#aba_profissional').show();
			$('#container_painel_profissional').triggerTab(2);
		}
	});
}

function excluirProfissional()
{
	confirmMsg(i18n.L_VIEW_SCRIPT_DESEJA_EXCLUIR, function(){
		$.ajax({
			type	: "POST",
			url		: systemName+"/profissional/excluir",
			data	: "cd_profissional="+$("#cd_profissional").val(),
			success	: function(retorno){
				alertMsg(retorno,'',"limpaFormProfissional()");
			}
		});
	});
}

function limpaFormNovoProfissional()
{
	$('#form_profissional :input').not(':checkbox').val('');
	$('#form_profissional :checkbox').removeAttr('checked');
}