
ALTER TABLE  a_form_inventario ADD CONSTRAINT a_form_inventario_pk PRIMARY KEY (cd_inventario, cd_item_inventario, cd_item_inventariado, cd_subitem_inventario, cd_subitem_inv_descri);

ALTER TABLE  a_item_inventariado ADD CONSTRAINT a_item_inventariado_pk PRIMARY KEY (cd_item_inventariado, cd_inventario, cd_item_inventario);

ALTER TABLE  b_item_inventario ADD CONSTRAINT b_item_inventario_pk PRIMARY KEY (cd_item_inventario);

ALTER TABLE  b_subitem_inv_descri ADD CONSTRAINT b_subitem_inv_descri_pk PRIMARY KEY (cd_item_inventario, cd_subitem_inventario, cd_subitem_inv_descri);

ALTER TABLE  b_subitem_inventario ADD CONSTRAINT b_subitem_inventario_pk PRIMARY KEY (cd_item_inventario, cd_subitem_inventario);

ALTER TABLE  a_doc_projeto_continuo ADD CONSTRAINT doc_projeto_continuo_pk PRIMARY KEY (dt_doc_projeto_continuo, cd_projeto_continuado, cd_objeto, cd_tipo_documentacao);

ALTER TABLE  s_usuario_pedido ADD CONSTRAINT pk_001 PRIMARY KEY (cd_usuario_pedido);

ALTER TABLE  s_tabela ADD CONSTRAINT pk_002 PRIMARY KEY (tx_tabela, cd_projeto);

ALTER TABLE  s_solicitacao ADD CONSTRAINT pk_003 PRIMARY KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto);

ALTER TABLE  s_situacao_projeto ADD CONSTRAINT pk_004 PRIMARY KEY (cd_projeto, ni_mes_situacao_projeto, ni_ano_situacao_projeto);

ALTER TABLE  s_reuniao ADD CONSTRAINT pk_005 PRIMARY KEY (cd_reuniao, cd_projeto);

ALTER TABLE  s_requisito ADD CONSTRAINT pk_006 PRIMARY KEY (cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE  s_regra_negocio ADD CONSTRAINT pk_007 PRIMARY KEY (cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio);

ALTER TABLE  s_proposta ADD CONSTRAINT pk_008 PRIMARY KEY (cd_proposta, cd_projeto);

ALTER TABLE  s_projeto_previsto ADD CONSTRAINT pk_009 PRIMARY KEY (cd_projeto_previsto, cd_contrato);

ALTER TABLE  s_projeto_continuado ADD CONSTRAINT pk_010 PRIMARY KEY (cd_projeto_continuado, cd_objeto);

ALTER TABLE  s_projeto ADD CONSTRAINT pk_011 PRIMARY KEY (cd_projeto);

ALTER TABLE  s_profissional ADD CONSTRAINT pk_012 PRIMARY KEY (cd_profissional);

ALTER TABLE  s_produto_parcela ADD CONSTRAINT pk_013 PRIMARY KEY (cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE  s_processamento_proposta ADD CONSTRAINT pk_014 PRIMARY KEY (cd_processamento_proposta, cd_projeto, cd_proposta);

ALTER TABLE  s_processamento_parcela ADD CONSTRAINT pk_015 PRIMARY KEY (cd_processamento_parcela, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE  s_previsao_projeto_diario ADD CONSTRAINT pk_016 PRIMARY KEY (cd_projeto, ni_mes, ni_dia);

ALTER TABLE  s_pre_projeto_evolutivo ADD CONSTRAINT pk_017 PRIMARY KEY (cd_pre_projeto_evolutivo, cd_projeto);

ALTER TABLE  s_pre_projeto ADD CONSTRAINT pk_018 PRIMARY KEY (cd_pre_projeto);

ALTER TABLE  s_pre_demanda ADD CONSTRAINT pk_019 PRIMARY KEY (cd_pre_demanda);

ALTER TABLE  s_plano_implantacao ADD CONSTRAINT pk_020 PRIMARY KEY (cd_projeto, cd_proposta);

ALTER TABLE  s_penalizacao ADD CONSTRAINT pk_021 PRIMARY KEY (dt_penalizacao, cd_contrato, cd_penalidade);

ALTER TABLE  s_parcela ADD CONSTRAINT pk_022 PRIMARY KEY (cd_parcela, cd_projeto, cd_proposta);

ALTER TABLE  s_ocorrencia_administrativa ADD CONSTRAINT pk_023 PRIMARY KEY (dt_ocorrencia_administrativa, cd_evento, cd_contrato);

ALTER TABLE  s_objeto_contrato ADD CONSTRAINT pk_024 PRIMARY KEY (cd_objeto);

ALTER TABLE  s_modulo_continuado ADD CONSTRAINT pk_025 PRIMARY KEY (cd_modulo_continuado, cd_objeto, cd_projeto_continuado);

ALTER TABLE  s_modulo ADD CONSTRAINT pk_026 PRIMARY KEY (cd_modulo, cd_projeto);

ALTER TABLE  s_mensageria ADD CONSTRAINT pk_027 PRIMARY KEY (cd_mensageria);

ALTER TABLE  s_medicao ADD CONSTRAINT pk_028 PRIMARY KEY (cd_medicao);

ALTER TABLE  s_log ADD CONSTRAINT pk_029 PRIMARY KEY (dt_ocorrencia, cd_log);

ALTER TABLE  s_interacao ADD CONSTRAINT pk_030 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_interacao);

ALTER TABLE  s_historico_proposta_produto ADD CONSTRAINT pk_032 PRIMARY KEY (cd_historico_proposta_produto, dt_historico_proposta, cd_projeto, cd_proposta, cd_historico_proposta_parcela);

ALTER TABLE  s_historico_proposta_parcela ADD CONSTRAINT pk_033 PRIMARY KEY (cd_proposta, cd_projeto, dt_historico_proposta, cd_historico_proposta_parcela);

ALTER TABLE  s_historico_proposta_metrica ADD CONSTRAINT pk_034 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta, cd_definicao_metrica);

ALTER TABLE  s_historico_proposta ADD CONSTRAINT pk_035 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta);

ALTER TABLE  s_historico_projeto_continuado ADD CONSTRAINT pk_036 PRIMARY KEY (cd_historico_proj_continuado, cd_objeto, cd_projeto_continuado, cd_modulo_continuado, cd_etapa, cd_atividade);

ALTER TABLE  s_historico_execucao_demanda ADD CONSTRAINT pk_037 PRIMARY KEY (cd_historico_execucao_demanda, cd_profissional, cd_demanda);

ALTER TABLE  s_historico ADD CONSTRAINT pk_038 PRIMARY KEY (cd_historico, cd_projeto, cd_proposta, cd_modulo, cd_etapa, cd_atividade);

ALTER TABLE  s_hist_prop_sub_item_metrica ADD CONSTRAINT pk_039 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);

ALTER TABLE  s_gerencia_qualidade ADD CONSTRAINT pk_040 PRIMARY KEY (cd_gerencia_qualidade, cd_projeto, cd_proposta);

ALTER TABLE  s_fale_conosco ADD CONSTRAINT pk_041 PRIMARY KEY (cd_fale_conosco);

ALTER TABLE  s_extrato_mensal_parcela ADD CONSTRAINT pk_042 PRIMARY KEY (cd_contrato, ni_ano_extrato, ni_mes_extrato, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE  s_extrato_mensal ADD CONSTRAINT pk_043 PRIMARY KEY (ni_mes_extrato, ni_ano_extrato, cd_contrato);

ALTER TABLE  s_empresa ADD CONSTRAINT pk_044 PRIMARY KEY (cd_empresa);

ALTER TABLE  s_disponibilidade_servico ADD CONSTRAINT pk_045 PRIMARY KEY (cd_disponibilidade_servico, cd_objeto);

ALTER TABLE  s_demanda ADD CONSTRAINT pk_046 PRIMARY KEY (cd_demanda);

ALTER TABLE  s_custo_contrato_demanda ADD CONSTRAINT pk_047 PRIMARY KEY (cd_contrato, ni_mes_custo_contrato_demanda, ni_ano_custo_contrato_demanda);

ALTER TABLE  s_contrato ADD CONSTRAINT pk_048 PRIMARY KEY (cd_contrato);

ALTER TABLE  s_contato_empresa ADD CONSTRAINT pk_049 PRIMARY KEY (cd_contato_empresa, cd_empresa);

ALTER TABLE  s_config_banco_de_dados ADD CONSTRAINT pk_050 PRIMARY KEY (cd_projeto);

ALTER TABLE  s_condicao ADD CONSTRAINT pk_051 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_condicao);

ALTER TABLE  s_complemento ADD CONSTRAINT pk_052 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_complemento);

ALTER TABLE  s_coluna ADD CONSTRAINT pk_053 PRIMARY KEY (tx_tabela, tx_coluna, cd_projeto);

ALTER TABLE  s_caso_de_uso ADD CONSTRAINT pk_054 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);

ALTER TABLE  s_baseline ADD CONSTRAINT pk_055 PRIMARY KEY (dt_baseline, cd_projeto);

ALTER TABLE  s_base_conhecimento ADD CONSTRAINT pk_056 PRIMARY KEY (cd_base_conhecimento, cd_area_conhecimento);

ALTER TABLE  s_ator ADD CONSTRAINT pk_057 PRIMARY KEY (cd_ator);

ALTER TABLE  s_analise_risco ADD CONSTRAINT pk_058 PRIMARY KEY (dt_analise_risco, cd_projeto, cd_proposta, cd_etapa, cd_atividade, cd_item_risco);

ALTER TABLE  s_analise_medicao ADD CONSTRAINT pk_059 PRIMARY KEY (dt_analise_medicao, cd_medicao, cd_box_inicio);

ALTER TABLE  s_analise_matriz_rastreab ADD CONSTRAINT pk_060 PRIMARY KEY (cd_analise_matriz_rastreab, cd_projeto, st_matriz_rastreabilidade);

ALTER TABLE  s_analise_execucao_projeto ADD CONSTRAINT pk_061 PRIMARY KEY (dt_analise_execucao_projeto, cd_projeto);

ALTER TABLE  s_agenda_plano_implantacao ADD CONSTRAINT pk_062 PRIMARY KEY (dt_agenda_plano_implantacao, cd_proposta, cd_projeto);

ALTER TABLE  s_acompanhamento_proposta ADD CONSTRAINT pk_063 PRIMARY KEY (cd_acompanhamento_proposta, cd_projeto, cd_proposta);
 
ALTER TABLE  b_unidade ADD CONSTRAINT pk_064 PRIMARY KEY (cd_unidade);

ALTER TABLE  b_treinamento ADD CONSTRAINT pk_065 PRIMARY KEY (cd_treinamento);

ALTER TABLE  b_tipo_produto ADD CONSTRAINT pk_066 PRIMARY KEY (cd_tipo_produto);

ALTER TABLE  b_tipo_documentacao ADD CONSTRAINT pk_068 PRIMARY KEY (cd_tipo_documentacao);

ALTER TABLE  b_tipo_dado_tecnico ADD CONSTRAINT pk_069 PRIMARY KEY (cd_tipo_dado_tecnico);

ALTER TABLE  b_tipo_conhecimento ADD CONSTRAINT pk_070 PRIMARY KEY (cd_tipo_conhecimento);

ALTER TABLE  b_sub_item_metrica ADD CONSTRAINT pk_071 PRIMARY KEY (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);

ALTER TABLE  b_status ADD CONSTRAINT pk_072 PRIMARY KEY (cd_status);

ALTER TABLE  b_relacao_contratual ADD CONSTRAINT pk_073 PRIMARY KEY (cd_relacao_contratual);

ALTER TABLE  b_questao_analise_risco ADD CONSTRAINT pk_074 PRIMARY KEY (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);

ALTER TABLE  b_perfil_profissional ADD CONSTRAINT pk_075 PRIMARY KEY (cd_perfil_profissional);

ALTER TABLE  b_perfil ADD CONSTRAINT pk_076 PRIMARY KEY (cd_perfil);

ALTER TABLE  b_penalidade ADD CONSTRAINT pk_077 PRIMARY KEY (cd_penalidade, cd_contrato);

ALTER TABLE  b_papel_profissional ADD CONSTRAINT pk_078 PRIMARY KEY (cd_papel_profissional);

ALTER TABLE  b_nivel_servico ADD CONSTRAINT pk_079 PRIMARY KEY (cd_nivel_servico);

ALTER TABLE  b_msg_email ADD CONSTRAINT pk_080 PRIMARY KEY (cd_msg_email, cd_menu);

ALTER TABLE  b_menu ADD CONSTRAINT pk_081 PRIMARY KEY (cd_menu);

ALTER TABLE  b_medida ADD CONSTRAINT pk_082 PRIMARY KEY (cd_medida);

ALTER TABLE  b_item_teste ADD CONSTRAINT pk_083 PRIMARY KEY (cd_item_teste);

ALTER TABLE  b_item_risco ADD CONSTRAINT pk_084 PRIMARY KEY (cd_item_risco, cd_etapa, cd_atividade);

ALTER TABLE  b_item_parecer_tecnico ADD CONSTRAINT pk_085 PRIMARY KEY (cd_item_parecer_tecnico);

ALTER TABLE  b_item_metrica ADD CONSTRAINT pk_086 PRIMARY KEY (cd_item_metrica, cd_definicao_metrica);

ALTER TABLE  b_item_grupo_fator ADD CONSTRAINT pk_088 PRIMARY KEY (cd_item_grupo_fator, cd_grupo_fator);

ALTER TABLE  b_item_controle_baseline ADD CONSTRAINT pk_089 PRIMARY KEY (cd_item_controle_baseline);

ALTER TABLE  b_grupo_fator ADD CONSTRAINT pk_090 PRIMARY KEY (cd_grupo_fator);

ALTER TABLE  b_funcionalidade ADD CONSTRAINT pk_091 PRIMARY KEY (cd_funcionalidade);

ALTER TABLE  b_evento ADD CONSTRAINT pk_092 PRIMARY KEY (cd_evento);

ALTER TABLE  b_etapa ADD CONSTRAINT pk_093 PRIMARY KEY (cd_etapa);

ALTER TABLE  b_definicao_metrica ADD CONSTRAINT pk_094 PRIMARY KEY (cd_definicao_metrica);

ALTER TABLE  b_conjunto_medida ADD CONSTRAINT pk_095 PRIMARY KEY (cd_conjunto_medida);

ALTER TABLE  b_conhecimento ADD CONSTRAINT pk_096 PRIMARY KEY (cd_conhecimento, cd_tipo_conhecimento);

ALTER TABLE  b_condicao_sub_item_metrica ADD CONSTRAINT pk_097 PRIMARY KEY (cd_condicao_sub_item_metrica, cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica);

ALTER TABLE  b_box_inicio ADD CONSTRAINT pk_098 PRIMARY KEY (cd_box_inicio);

ALTER TABLE  b_atividade ADD CONSTRAINT pk_099 PRIMARY KEY (cd_atividade, cd_etapa);

ALTER TABLE  b_area_conhecimento ADD CONSTRAINT pk_100 PRIMARY KEY (cd_area_conhecimento);

ALTER TABLE  b_area_atuacao_ti
    ADD CONSTRAINT pk_101 PRIMARY KEY (cd_area_atuacao_ti);

ALTER TABLE  a_treinamento_profissional
    ADD CONSTRAINT pk_102 PRIMARY KEY (cd_treinamento, cd_profissional);

ALTER TABLE  a_reuniao_profissional
    ADD CONSTRAINT pk_103 PRIMARY KEY (cd_projeto, cd_reuniao, cd_profissional);

ALTER TABLE  a_requisito_dependente
    ADD CONSTRAINT pk_104 PRIMARY KEY (cd_requisito_ascendente, dt_versao_requisito_ascendente, cd_projeto_ascendente, cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE  a_requisito_caso_de_uso
    ADD CONSTRAINT pk_105 PRIMARY KEY (cd_projeto, dt_versao_requisito, cd_requisito, dt_versao_caso_de_uso, cd_caso_de_uso, cd_modulo);

ALTER TABLE  a_regra_negocio_requisito
    ADD CONSTRAINT pk_106 PRIMARY KEY (cd_projeto_regra_negocio, dt_regra_negocio, cd_regra_negocio, dt_versao_requisito, cd_requisito, cd_projeto);

ALTER TABLE  a_questionario_analise_risco
    ADD CONSTRAINT pk_107 PRIMARY KEY (dt_analise_risco, cd_projeto, cd_proposta, cd_etapa, cd_atividade, cd_item_risco, cd_questao_analise_risco);

ALTER TABLE  a_quest_avaliacao_qualidade
    ADD CONSTRAINT pk_108 PRIMARY KEY (cd_projeto, cd_proposta, cd_grupo_fator, cd_item_grupo_fator);

ALTER TABLE  a_proposta_sub_item_metrica
    ADD CONSTRAINT pk_109 PRIMARY KEY (cd_projeto, cd_proposta, cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica);

ALTER TABLE  a_proposta_modulo
    ADD CONSTRAINT pk_110 PRIMARY KEY (cd_projeto, cd_modulo, cd_proposta);

ALTER TABLE  a_proposta_definicao_metrica
    ADD CONSTRAINT pk_111 PRIMARY KEY (cd_projeto, cd_proposta, cd_definicao_metrica);

ALTER TABLE  a_profissional_projeto
    ADD CONSTRAINT pk_112 PRIMARY KEY (cd_profissional, cd_projeto, cd_papel_profissional);

ALTER TABLE  a_profissional_produto
    ADD CONSTRAINT pk_113 PRIMARY KEY (cd_profissional, cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE  a_profissional_objeto_contrato
    ADD CONSTRAINT pk_114 PRIMARY KEY (cd_profissional, cd_objeto);

ALTER TABLE  a_profissional_menu
    ADD CONSTRAINT pk_115 PRIMARY KEY (cd_menu, cd_profissional, cd_objeto);

ALTER TABLE  a_profissional_mensageria
    ADD CONSTRAINT pk_116 PRIMARY KEY (cd_profissional, cd_mensageria);

ALTER TABLE  a_profissional_conhecimento
    ADD CONSTRAINT pk_117 PRIMARY KEY (cd_profissional, cd_tipo_conhecimento, cd_conhecimento);

ALTER TABLE  a_planejamento
    ADD CONSTRAINT pk_118 PRIMARY KEY (cd_etapa, cd_atividade, cd_projeto, cd_modulo);

ALTER TABLE  a_perfil_prof_papel_prof
    ADD CONSTRAINT pk_119 PRIMARY KEY (cd_perfil_profissional, cd_papel_profissional);

ALTER TABLE  a_perfil_menu_sistema
    ADD CONSTRAINT pk_120 PRIMARY KEY (cd_perfil, cd_menu, st_perfil_menu);

ALTER TABLE  a_perfil_menu
    ADD CONSTRAINT pk_121 PRIMARY KEY (cd_menu, cd_perfil, cd_objeto);

ALTER TABLE  a_perfil_box_inicio
    ADD CONSTRAINT pk_122 PRIMARY KEY (cd_perfil, cd_box_inicio, cd_objeto);

ALTER TABLE  a_parecer_tecnico_proposta
    ADD CONSTRAINT pk_123 PRIMARY KEY (cd_item_parecer_tecnico, cd_proposta, cd_projeto, cd_processamento_proposta);

ALTER TABLE  a_parecer_tecnico_parcela
    ADD CONSTRAINT pk_124 PRIMARY KEY (cd_projeto, cd_proposta, cd_parcela, cd_item_parecer_tecnico, cd_processamento_parcela);

ALTER TABLE  a_objeto_contrato_perfil_prof
    ADD CONSTRAINT pk_125 PRIMARY KEY (cd_objeto, cd_perfil_profissional);

ALTER TABLE  a_objeto_contrato_papel_prof
    ADD CONSTRAINT pk_126 PRIMARY KEY (cd_objeto, cd_papel_profissional);

ALTER TABLE  a_objeto_contrato_atividade
    ADD CONSTRAINT pk_127 PRIMARY KEY (cd_objeto, cd_etapa, cd_atividade);

ALTER TABLE  a_medicao_medida
    ADD CONSTRAINT pk_128 PRIMARY KEY (cd_medicao, cd_medida);

ALTER TABLE  a_item_teste_requisito_doc
    ADD CONSTRAINT pk_129 PRIMARY KEY (cd_arq_item_teste_requisito, cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste);

ALTER TABLE  a_item_teste_requisito
    ADD CONSTRAINT pk_130 PRIMARY KEY (cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste);

ALTER TABLE  a_item_teste_regra_negocio_doc
    ADD CONSTRAINT pk_131 PRIMARY KEY (cd_arq_item_teste_regra_neg, dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio);

ALTER TABLE  a_item_teste_regra_negocio
    ADD CONSTRAINT pk_132 PRIMARY KEY (dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio);

ALTER TABLE  a_item_teste_caso_de_uso_doc
    ADD CONSTRAINT pk_133 PRIMARY KEY (cd_arq_item_teste_caso_de_uso, cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso);

ALTER TABLE  a_item_teste_caso_de_uso
    ADD CONSTRAINT pk_134 PRIMARY KEY (cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso);

ALTER TABLE  a_informacao_tecnica
    ADD CONSTRAINT pk_135 PRIMARY KEY (cd_projeto, cd_tipo_dado_tecnico);

ALTER TABLE  a_gerencia_mudanca
    ADD CONSTRAINT pk_136 PRIMARY KEY (dt_gerencia_mudanca, cd_item_controle_baseline, cd_projeto, cd_item_controlado, dt_versao_item_controlado);

ALTER TABLE  a_funcionalidade_menu
    ADD CONSTRAINT pk_137 PRIMARY KEY (cd_funcionalidade, cd_menu);

ALTER TABLE  a_documentacao_projeto
    ADD CONSTRAINT pk_138 PRIMARY KEY (dt_documentacao_projeto, cd_projeto, cd_tipo_documentacao);

ALTER TABLE  a_documentacao_profissional
    ADD CONSTRAINT pk_139 PRIMARY KEY (dt_documentacao_profissional, cd_tipo_documentacao, cd_profissional);

ALTER TABLE  a_disponibilidade_servico_doc
    ADD CONSTRAINT pk_140 PRIMARY KEY (cd_disponibilidade_servico, cd_objeto, cd_tipo_documentacao, dt_doc_disponibilidade_servico);

ALTER TABLE  a_demanda_profissional
    ADD CONSTRAINT pk_141 PRIMARY KEY (cd_profissional, cd_demanda);

ALTER TABLE  a_demanda_prof_nivel_servico
    ADD CONSTRAINT pk_142 PRIMARY KEY (cd_demanda, cd_profissional, cd_nivel_servico);

ALTER TABLE  a_definicao_processo
    ADD CONSTRAINT pk_143 PRIMARY KEY (cd_perfil, cd_funcionalidade, st_definicao_processo);

ALTER TABLE  a_controle
    ADD CONSTRAINT pk_144 PRIMARY KEY (cd_controle, cd_projeto_previsto, cd_projeto, cd_proposta, cd_contrato);

ALTER TABLE  a_contrato_projeto
    ADD CONSTRAINT pk_145 PRIMARY KEY (cd_contrato, cd_projeto);

ALTER TABLE  a_contrato_evento
    ADD CONSTRAINT pk_146 PRIMARY KEY (cd_contrato, cd_evento);

ALTER TABLE  a_contrato_definicao_metrica
    ADD CONSTRAINT pk_147 PRIMARY KEY (cd_contrato, cd_definicao_metrica);

ALTER TABLE  a_conjunto_medida_medicao
    ADD CONSTRAINT pk_148 PRIMARY KEY (cd_conjunto_medida, cd_box_inicio, cd_medicao);

ALTER TABLE  a_conhecimento_projeto
    ADD CONSTRAINT pk_149 PRIMARY KEY (cd_tipo_conhecimento, cd_conhecimento, cd_projeto);

ALTER TABLE  a_baseline_item_controle
    ADD CONSTRAINT pk_150 PRIMARY KEY (cd_projeto, dt_baseline, cd_item_controle_baseline, cd_item_controlado, dt_versao_item_controlado);

ALTER TABLE  b_pergunta_pedido
    ADD CONSTRAINT pk_151 PRIMARY KEY (cd_pergunta_pedido);

ALTER TABLE  b_resposta_pedido
    ADD CONSTRAINT pk_152 PRIMARY KEY (cd_resposta_pedido);

ALTER TABLE  s_solicitacao_pedido
    ADD CONSTRAINT pk_153 PRIMARY KEY (cd_solicitacao_pedido);

ALTER TABLE  s_historico_pedido
    ADD CONSTRAINT pk_154 PRIMARY KEY (cd_historico_pedido);

ALTER TABLE  a_solicitacao_resposta_pedido
    ADD CONSTRAINT pk_155 PRIMARY KEY (cd_solicitacao_pedido, cd_pergunta_pedido, cd_resposta_pedido);

ALTER TABLE  s_arquivo_pedido
    ADD CONSTRAINT pk_156 PRIMARY KEY (cd_arquivo_pedido);

ALTER TABLE  a_opcao_resp_pergunta_pedido
    ADD CONSTRAINT pk_157 PRIMARY KEY (cd_pergunta_pedido, cd_resposta_pedido);

ALTER TABLE  a_pergunta_depende_resp_pedido
    ADD CONSTRAINT pk_158 PRIMARY KEY (cd_pergunta_depende, cd_pergunta_pedido, cd_resposta_pedido);

ALTER TABLE  a_documentacao_contrato
    ADD CONSTRAINT pk_159 PRIMARY KEY (dt_documentacao_contrato, cd_contrato, cd_tipo_documentacao);

ALTER TABLE  b_rotina
    ADD CONSTRAINT pk_160 PRIMARY KEY (cd_rotina);

ALTER TABLE  b_status_atendimento
    ADD CONSTRAINT pk_161 PRIMARY KEY (cd_status_atendimento);

ALTER TABLE  a_objeto_contrato_rotina
    ADD CONSTRAINT pk_162 PRIMARY KEY (cd_objeto, cd_rotina);

ALTER TABLE  a_rotina_profissional
    ADD CONSTRAINT pk_163 PRIMARY KEY (cd_objeto, cd_profissional, cd_rotina);

ALTER TABLE  s_execucao_rotina
    ADD CONSTRAINT pk_164 PRIMARY KEY (dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina);

ALTER TABLE  s_historico_execucao_rotina
    ADD CONSTRAINT pk_165 PRIMARY KEY (dt_historico_execucao_rotina, cd_rotina, cd_objeto, cd_profissional, dt_execucao_rotina);

ALTER TABLE  s_reuniao_geral
    ADD CONSTRAINT pk_2005 PRIMARY KEY (cd_reuniao_geral, cd_objeto);

ALTER TABLE  a_reuniao_geral_profissional
    ADD CONSTRAINT pk_2103 PRIMARY KEY (cd_objeto, cd_reuniao_geral, cd_profissional);

ALTER TABLE  a_contrato_item_inventario
    ADD CONSTRAINT pk_item_inventario_145 PRIMARY KEY (cd_contrato, cd_item_inventario);

ALTER TABLE  s_inventario
    ADD CONSTRAINT s_inventario_pk PRIMARY KEY (cd_inventario);


