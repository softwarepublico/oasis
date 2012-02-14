
ALTER TABLE ONLY a_form_inventario
    ADD CONSTRAINT a_form_inventario_pk PRIMARY KEY (cd_inventario, cd_item_inventario, cd_item_inventariado, cd_subitem_inventario, cd_subitem_inv_descri);

ALTER TABLE ONLY a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_pk PRIMARY KEY (cd_item_inventariado, cd_inventario, cd_item_inventario);

ALTER TABLE ONLY b_item_inventario
    ADD CONSTRAINT b_item_inventario_pk PRIMARY KEY (cd_item_inventario);

ALTER TABLE ONLY b_subitem_inv_descri
    ADD CONSTRAINT b_subitem_inv_descri_pk PRIMARY KEY (cd_item_inventario, cd_subitem_inventario, cd_subitem_inv_descri);

ALTER TABLE ONLY b_subitem_inventario
    ADD CONSTRAINT b_subitem_inventario_pk PRIMARY KEY (cd_item_inventario, cd_subitem_inventario);

ALTER TABLE ONLY a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_pk PRIMARY KEY (dt_doc_projeto_continuo, cd_projeto_continuado, cd_objeto, cd_tipo_documentacao);

ALTER TABLE ONLY s_usuario_pedido
    ADD CONSTRAINT pk_K_SCHEMA_001 PRIMARY KEY (cd_usuario_pedido);

ALTER TABLE ONLY s_tabela
    ADD CONSTRAINT pk_K_SCHEMA_002 PRIMARY KEY (tx_tabela, cd_projeto);

ALTER TABLE ONLY s_solicitacao
    ADD CONSTRAINT pk_K_SCHEMA_003 PRIMARY KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto);

ALTER TABLE ONLY s_situacao_projeto
    ADD CONSTRAINT pk_K_SCHEMA_004 PRIMARY KEY (cd_projeto, ni_mes_situacao_projeto, ni_ano_situacao_projeto);

ALTER TABLE ONLY s_reuniao
    ADD CONSTRAINT pk_K_SCHEMA_005 PRIMARY KEY (cd_reuniao, cd_projeto);

ALTER TABLE ONLY s_requisito
    ADD CONSTRAINT pk_K_SCHEMA_006 PRIMARY KEY (cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE ONLY s_regra_negocio
    ADD CONSTRAINT pk_K_SCHEMA_007 PRIMARY KEY (cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio);

ALTER TABLE ONLY s_proposta
    ADD CONSTRAINT pk_K_SCHEMA_008 PRIMARY KEY (cd_proposta, cd_projeto);

ALTER TABLE ONLY s_projeto_previsto
    ADD CONSTRAINT pk_K_SCHEMA_009 PRIMARY KEY (cd_projeto_previsto, cd_contrato);

ALTER TABLE ONLY s_projeto_continuado
    ADD CONSTRAINT pk_K_SCHEMA_010 PRIMARY KEY (cd_projeto_continuado, cd_objeto);

ALTER TABLE ONLY s_projeto
    ADD CONSTRAINT pk_K_SCHEMA_011 PRIMARY KEY (cd_projeto);

ALTER TABLE ONLY s_profissional
    ADD CONSTRAINT pk_K_SCHEMA_012 PRIMARY KEY (cd_profissional);

ALTER TABLE ONLY s_produto_parcela
    ADD CONSTRAINT pk_K_SCHEMA_013 PRIMARY KEY (cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE ONLY s_processamento_proposta
    ADD CONSTRAINT pk_K_SCHEMA_014 PRIMARY KEY (cd_processamento_proposta, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_processamento_parcela
    ADD CONSTRAINT pk_K_SCHEMA_015 PRIMARY KEY (cd_processamento_parcela, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE ONLY s_previsao_projeto_diario
    ADD CONSTRAINT pk_K_SCHEMA_016 PRIMARY KEY (cd_projeto, ni_mes, ni_dia);

ALTER TABLE ONLY s_pre_projeto_evolutivo
    ADD CONSTRAINT pk_K_SCHEMA_017 PRIMARY KEY (cd_pre_projeto_evolutivo, cd_projeto);

ALTER TABLE ONLY s_pre_projeto
    ADD CONSTRAINT pk_K_SCHEMA_018 PRIMARY KEY (cd_pre_projeto);

ALTER TABLE ONLY s_pre_demanda
    ADD CONSTRAINT pk_K_SCHEMA_019 PRIMARY KEY (cd_pre_demanda);

ALTER TABLE ONLY s_plano_implantacao
    ADD CONSTRAINT pk_K_SCHEMA_020 PRIMARY KEY (cd_projeto, cd_proposta);

ALTER TABLE ONLY s_penalizacao
    ADD CONSTRAINT pk_K_SCHEMA_021 PRIMARY KEY (dt_penalizacao, cd_contrato, cd_penalidade);

ALTER TABLE ONLY s_parcela
    ADD CONSTRAINT pk_K_SCHEMA_022 PRIMARY KEY (cd_parcela, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_ocorrencia_administrativa
    ADD CONSTRAINT pk_K_SCHEMA_023 PRIMARY KEY (dt_ocorrencia_administrativa, cd_evento, cd_contrato);

ALTER TABLE ONLY s_objeto_contrato
    ADD CONSTRAINT pk_K_SCHEMA_024 PRIMARY KEY (cd_objeto);

ALTER TABLE ONLY s_modulo_continuado
    ADD CONSTRAINT pk_K_SCHEMA_025 PRIMARY KEY (cd_modulo_continuado, cd_objeto, cd_projeto_continuado);

ALTER TABLE ONLY s_modulo
    ADD CONSTRAINT pk_K_SCHEMA_026 PRIMARY KEY (cd_modulo, cd_projeto);

ALTER TABLE ONLY s_mensageria
    ADD CONSTRAINT pk_K_SCHEMA_027 PRIMARY KEY (cd_mensageria);

ALTER TABLE ONLY s_medicao
    ADD CONSTRAINT pk_K_SCHEMA_028 PRIMARY KEY (cd_medicao);

ALTER TABLE ONLY s_log
    ADD CONSTRAINT pk_K_SCHEMA_029 PRIMARY KEY (dt_ocorrencia, cd_log);

ALTER TABLE ONLY s_interacao
    ADD CONSTRAINT pk_K_SCHEMA_030 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_interacao);

ALTER TABLE ONLY s_historico_proposta_produto
    ADD CONSTRAINT pk_K_SCHEMA_032 PRIMARY KEY (cd_historico_proposta_produto, dt_historico_proposta, cd_projeto, cd_proposta, cd_historico_proposta_parcela);

ALTER TABLE ONLY s_historico_proposta_parcela
    ADD CONSTRAINT pk_K_SCHEMA_033 PRIMARY KEY (cd_proposta, cd_projeto, dt_historico_proposta, cd_historico_proposta_parcela);

ALTER TABLE ONLY s_historico_proposta_metrica
    ADD CONSTRAINT pk_K_SCHEMA_034 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta, cd_definicao_metrica);

ALTER TABLE ONLY s_historico_proposta
    ADD CONSTRAINT pk_K_SCHEMA_035 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_historico_projeto_continuado
    ADD CONSTRAINT pk_K_SCHEMA_036 PRIMARY KEY (cd_historico_proj_continuado, cd_objeto, cd_projeto_continuado, cd_modulo_continuado, cd_etapa, cd_atividade);

ALTER TABLE ONLY s_historico_execucao_demanda
    ADD CONSTRAINT pk_K_SCHEMA_037 PRIMARY KEY (cd_historico_execucao_demanda, cd_profissional, cd_demanda);

ALTER TABLE ONLY s_historico
    ADD CONSTRAINT pk_K_SCHEMA_038 PRIMARY KEY (cd_historico, cd_projeto, cd_proposta, cd_modulo, cd_etapa, cd_atividade);

ALTER TABLE ONLY s_hist_prop_sub_item_metrica
    ADD CONSTRAINT pk_K_SCHEMA_039 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);

ALTER TABLE ONLY s_gerencia_qualidade
    ADD CONSTRAINT pk_K_SCHEMA_040 PRIMARY KEY (cd_gerencia_qualidade, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_fale_conosco
    ADD CONSTRAINT pk_K_SCHEMA_041 PRIMARY KEY (cd_fale_conosco);

ALTER TABLE ONLY s_extrato_mensal_parcela
    ADD CONSTRAINT pk_K_SCHEMA_042 PRIMARY KEY (cd_contrato, ni_ano_extrato, ni_mes_extrato, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE ONLY s_extrato_mensal
    ADD CONSTRAINT pk_K_SCHEMA_043 PRIMARY KEY (ni_mes_extrato, ni_ano_extrato, cd_contrato);

ALTER TABLE ONLY s_empresa
    ADD CONSTRAINT pk_K_SCHEMA_044 PRIMARY KEY (cd_empresa);

ALTER TABLE ONLY s_disponibilidade_servico
    ADD CONSTRAINT pk_K_SCHEMA_045 PRIMARY KEY (cd_disponibilidade_servico, cd_objeto);

ALTER TABLE ONLY s_demanda
    ADD CONSTRAINT pk_K_SCHEMA_046 PRIMARY KEY (cd_demanda);

ALTER TABLE ONLY s_custo_contrato_demanda
    ADD CONSTRAINT pk_K_SCHEMA_047 PRIMARY KEY (cd_contrato, ni_mes_custo_contrato_demanda, ni_ano_custo_contrato_demanda);

ALTER TABLE ONLY s_contrato
    ADD CONSTRAINT pk_K_SCHEMA_048 PRIMARY KEY (cd_contrato);

ALTER TABLE ONLY s_contato_empresa
    ADD CONSTRAINT pk_K_SCHEMA_049 PRIMARY KEY (cd_contato_empresa, cd_empresa);

ALTER TABLE ONLY s_config_banco_de_dados
    ADD CONSTRAINT pk_K_SCHEMA_050 PRIMARY KEY (cd_projeto);

ALTER TABLE ONLY s_condicao
    ADD CONSTRAINT pk_K_SCHEMA_051 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_condicao);

ALTER TABLE ONLY s_complemento
    ADD CONSTRAINT pk_K_SCHEMA_052 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_complemento);

ALTER TABLE ONLY s_coluna
    ADD CONSTRAINT pk_K_SCHEMA_053 PRIMARY KEY (tx_tabela, tx_coluna, cd_projeto);

ALTER TABLE ONLY s_caso_de_uso
    ADD CONSTRAINT pk_K_SCHEMA_054 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);

ALTER TABLE ONLY s_baseline
    ADD CONSTRAINT pk_K_SCHEMA_055 PRIMARY KEY (dt_baseline, cd_projeto);

ALTER TABLE ONLY s_base_conhecimento
    ADD CONSTRAINT pk_K_SCHEMA_056 PRIMARY KEY (cd_base_conhecimento, cd_area_conhecimento);

ALTER TABLE ONLY s_ator
    ADD CONSTRAINT pk_K_SCHEMA_057 PRIMARY KEY (cd_ator);

ALTER TABLE ONLY s_analise_risco
    ADD CONSTRAINT pk_K_SCHEMA_058 PRIMARY KEY (dt_analise_risco, cd_projeto, cd_proposta, cd_etapa, cd_atividade, cd_item_risco);

ALTER TABLE ONLY s_analise_medicao
    ADD CONSTRAINT pk_K_SCHEMA_059 PRIMARY KEY (dt_analise_medicao, cd_medicao, cd_box_inicio);

ALTER TABLE ONLY s_analise_matriz_rastreab
    ADD CONSTRAINT pk_K_SCHEMA_060 PRIMARY KEY (cd_analise_matriz_rastreab, cd_projeto, st_matriz_rastreabilidade);

ALTER TABLE ONLY s_analise_execucao_projeto
    ADD CONSTRAINT pk_K_SCHEMA_061 PRIMARY KEY (dt_analise_execucao_projeto, cd_projeto);

ALTER TABLE ONLY s_agenda_plano_implantacao
    ADD CONSTRAINT pk_K_SCHEMA_062 PRIMARY KEY (dt_agenda_plano_implantacao, cd_proposta, cd_projeto);

ALTER TABLE ONLY s_acompanhamento_proposta
    ADD CONSTRAINT pk_K_SCHEMA_063 PRIMARY KEY (cd_acompanhamento_proposta, cd_projeto, cd_proposta);

ALTER TABLE ONLY b_unidade
    ADD CONSTRAINT pk_K_SCHEMA_064 PRIMARY KEY (cd_unidade);

ALTER TABLE ONLY b_treinamento
    ADD CONSTRAINT pk_K_SCHEMA_065 PRIMARY KEY (cd_treinamento);

ALTER TABLE ONLY b_tipo_produto
    ADD CONSTRAINT pk_K_SCHEMA_066 PRIMARY KEY (cd_tipo_produto);

ALTER TABLE ONLY b_tipo_documentacao
    ADD CONSTRAINT pk_K_SCHEMA_068 PRIMARY KEY (cd_tipo_documentacao);

ALTER TABLE ONLY b_tipo_dado_tecnico
    ADD CONSTRAINT pk_K_SCHEMA_069 PRIMARY KEY (cd_tipo_dado_tecnico);

ALTER TABLE ONLY b_tipo_conhecimento
    ADD CONSTRAINT pk_K_SCHEMA_070 PRIMARY KEY (cd_tipo_conhecimento);

ALTER TABLE ONLY b_sub_item_metrica
    ADD CONSTRAINT pk_K_SCHEMA_071 PRIMARY KEY (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);

ALTER TABLE ONLY b_status
    ADD CONSTRAINT pk_K_SCHEMA_072 PRIMARY KEY (cd_status);

ALTER TABLE ONLY b_relacao_contratual
    ADD CONSTRAINT pk_K_SCHEMA_073 PRIMARY KEY (cd_relacao_contratual);

ALTER TABLE ONLY b_questao_analise_risco
    ADD CONSTRAINT pk_K_SCHEMA_074 PRIMARY KEY (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);

ALTER TABLE ONLY b_perfil_profissional
    ADD CONSTRAINT pk_K_SCHEMA_075 PRIMARY KEY (cd_perfil_profissional);

ALTER TABLE ONLY b_perfil
    ADD CONSTRAINT pk_K_SCHEMA_076 PRIMARY KEY (cd_perfil);

ALTER TABLE ONLY b_penalidade
    ADD CONSTRAINT pk_K_SCHEMA_077 PRIMARY KEY (cd_penalidade, cd_contrato);

ALTER TABLE ONLY b_papel_profissional
    ADD CONSTRAINT pk_K_SCHEMA_078 PRIMARY KEY (cd_papel_profissional);

ALTER TABLE ONLY b_nivel_servico
    ADD CONSTRAINT pk_K_SCHEMA_079 PRIMARY KEY (cd_nivel_servico);

ALTER TABLE ONLY b_msg_email
    ADD CONSTRAINT pk_K_SCHEMA_080 PRIMARY KEY (cd_msg_email, cd_menu);

ALTER TABLE ONLY b_menu
    ADD CONSTRAINT pk_K_SCHEMA_081 PRIMARY KEY (cd_menu);

ALTER TABLE ONLY b_medida
    ADD CONSTRAINT pk_K_SCHEMA_082 PRIMARY KEY (cd_medida);

ALTER TABLE ONLY b_item_teste
    ADD CONSTRAINT pk_K_SCHEMA_083 PRIMARY KEY (cd_item_teste);

ALTER TABLE ONLY b_item_risco
    ADD CONSTRAINT pk_K_SCHEMA_084 PRIMARY KEY (cd_item_risco, cd_etapa, cd_atividade);

ALTER TABLE ONLY b_item_parecer_tecnico
    ADD CONSTRAINT pk_K_SCHEMA_085 PRIMARY KEY (cd_item_parecer_tecnico);

ALTER TABLE ONLY b_item_metrica
    ADD CONSTRAINT pk_K_SCHEMA_086 PRIMARY KEY (cd_item_metrica, cd_definicao_metrica);

ALTER TABLE ONLY b_item_grupo_fator
    ADD CONSTRAINT pk_K_SCHEMA_088 PRIMARY KEY (cd_item_grupo_fator, cd_grupo_fator);

ALTER TABLE ONLY b_item_controle_baseline
    ADD CONSTRAINT pk_K_SCHEMA_089 PRIMARY KEY (cd_item_controle_baseline);

ALTER TABLE ONLY b_grupo_fator
    ADD CONSTRAINT pk_K_SCHEMA_090 PRIMARY KEY (cd_grupo_fator);

ALTER TABLE ONLY b_funcionalidade
    ADD CONSTRAINT pk_K_SCHEMA_091 PRIMARY KEY (cd_funcionalidade);

ALTER TABLE ONLY b_evento
    ADD CONSTRAINT pk_K_SCHEMA_092 PRIMARY KEY (cd_evento);

ALTER TABLE ONLY b_etapa
    ADD CONSTRAINT pk_K_SCHEMA_093 PRIMARY KEY (cd_etapa);

ALTER TABLE ONLY b_definicao_metrica
    ADD CONSTRAINT pk_K_SCHEMA_094 PRIMARY KEY (cd_definicao_metrica);

ALTER TABLE ONLY b_conjunto_medida
    ADD CONSTRAINT pk_K_SCHEMA_095 PRIMARY KEY (cd_conjunto_medida);

ALTER TABLE ONLY b_conhecimento
    ADD CONSTRAINT pk_K_SCHEMA_096 PRIMARY KEY (cd_conhecimento, cd_tipo_conhecimento);

ALTER TABLE ONLY b_condicao_sub_item_metrica
    ADD CONSTRAINT pk_K_SCHEMA_097 PRIMARY KEY (cd_condicao_sub_item_metrica, cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica);

ALTER TABLE ONLY b_box_inicio
    ADD CONSTRAINT pk_K_SCHEMA_098 PRIMARY KEY (cd_box_inicio);

ALTER TABLE ONLY b_atividade
    ADD CONSTRAINT pk_K_SCHEMA_099 PRIMARY KEY (cd_atividade, cd_etapa);

ALTER TABLE ONLY b_area_conhecimento
    ADD CONSTRAINT pk_K_SCHEMA_100 PRIMARY KEY (cd_area_conhecimento);

ALTER TABLE ONLY b_area_atuacao_ti
    ADD CONSTRAINT pk_K_SCHEMA_101 PRIMARY KEY (cd_area_atuacao_ti);

ALTER TABLE ONLY a_treinamento_profissional
    ADD CONSTRAINT pk_K_SCHEMA_102 PRIMARY KEY (cd_treinamento, cd_profissional);

ALTER TABLE ONLY a_reuniao_profissional
    ADD CONSTRAINT pk_K_SCHEMA_103 PRIMARY KEY (cd_projeto, cd_reuniao, cd_profissional);

ALTER TABLE ONLY a_requisito_dependente
    ADD CONSTRAINT pk_K_SCHEMA_104 PRIMARY KEY (cd_requisito_ascendente, dt_versao_requisito_ascendente, cd_projeto_ascendente, cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE ONLY a_requisito_caso_de_uso
    ADD CONSTRAINT pk_K_SCHEMA_105 PRIMARY KEY (cd_projeto, dt_versao_requisito, cd_requisito, dt_versao_caso_de_uso, cd_caso_de_uso, cd_modulo);

ALTER TABLE ONLY a_regra_negocio_requisito
    ADD CONSTRAINT pk_K_SCHEMA_106 PRIMARY KEY (cd_projeto_regra_negocio, dt_regra_negocio, cd_regra_negocio, dt_versao_requisito, cd_requisito, cd_projeto);

ALTER TABLE ONLY a_questionario_analise_risco
    ADD CONSTRAINT pk_K_SCHEMA_107 PRIMARY KEY (dt_analise_risco, cd_projeto, cd_proposta, cd_etapa, cd_atividade, cd_item_risco, cd_questao_analise_risco);

ALTER TABLE ONLY a_quest_avaliacao_qualidade
    ADD CONSTRAINT pk_K_SCHEMA_108 PRIMARY KEY (cd_projeto, cd_proposta, cd_grupo_fator, cd_item_grupo_fator);

ALTER TABLE ONLY a_proposta_sub_item_metrica
    ADD CONSTRAINT pk_K_SCHEMA_109 PRIMARY KEY (cd_projeto, cd_proposta, cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica);

ALTER TABLE ONLY a_proposta_modulo
    ADD CONSTRAINT pk_K_SCHEMA_110 PRIMARY KEY (cd_projeto, cd_modulo, cd_proposta);

ALTER TABLE ONLY a_proposta_definicao_metrica
    ADD CONSTRAINT pk_K_SCHEMA_111 PRIMARY KEY (cd_projeto, cd_proposta, cd_definicao_metrica);

ALTER TABLE ONLY a_profissional_projeto
    ADD CONSTRAINT pk_K_SCHEMA_112 PRIMARY KEY (cd_profissional, cd_projeto, cd_papel_profissional);

ALTER TABLE ONLY a_profissional_produto
    ADD CONSTRAINT pk_K_SCHEMA_113 PRIMARY KEY (cd_profissional, cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE ONLY a_profissional_objeto_contrato
    ADD CONSTRAINT pk_K_SCHEMA_114 PRIMARY KEY (cd_profissional, cd_objeto);

ALTER TABLE ONLY a_profissional_menu
    ADD CONSTRAINT pk_K_SCHEMA_115 PRIMARY KEY (cd_menu, cd_profissional, cd_objeto);

ALTER TABLE ONLY a_profissional_mensageria
    ADD CONSTRAINT pk_K_SCHEMA_116 PRIMARY KEY (cd_profissional, cd_mensageria);

ALTER TABLE ONLY a_profissional_conhecimento
    ADD CONSTRAINT pk_K_SCHEMA_117 PRIMARY KEY (cd_profissional, cd_tipo_conhecimento, cd_conhecimento);

ALTER TABLE ONLY a_planejamento
    ADD CONSTRAINT pk_K_SCHEMA_118 PRIMARY KEY (cd_etapa, cd_atividade, cd_projeto, cd_modulo);

ALTER TABLE ONLY a_perfil_prof_papel_prof
    ADD CONSTRAINT pk_K_SCHEMA_119 PRIMARY KEY (cd_perfil_profissional, cd_papel_profissional);

ALTER TABLE ONLY a_perfil_menu_sistema
    ADD CONSTRAINT pk_K_SCHEMA_120 PRIMARY KEY (cd_perfil, cd_menu, st_perfil_menu);

ALTER TABLE ONLY a_perfil_menu
    ADD CONSTRAINT pk_K_SCHEMA_121 PRIMARY KEY (cd_menu, cd_perfil, cd_objeto);

ALTER TABLE ONLY a_perfil_box_inicio
    ADD CONSTRAINT pk_K_SCHEMA_122 PRIMARY KEY (cd_perfil, cd_box_inicio, cd_objeto);

ALTER TABLE ONLY a_parecer_tecnico_proposta
    ADD CONSTRAINT pk_K_SCHEMA_123 PRIMARY KEY (cd_item_parecer_tecnico, cd_proposta, cd_projeto, cd_processamento_proposta);

ALTER TABLE ONLY a_parecer_tecnico_parcela
    ADD CONSTRAINT pk_K_SCHEMA_124 PRIMARY KEY (cd_projeto, cd_proposta, cd_parcela, cd_item_parecer_tecnico, cd_processamento_parcela);

ALTER TABLE ONLY a_objeto_contrato_perfil_prof
    ADD CONSTRAINT pk_K_SCHEMA_125 PRIMARY KEY (cd_objeto, cd_perfil_profissional);

ALTER TABLE ONLY a_objeto_contrato_papel_prof
    ADD CONSTRAINT pk_K_SCHEMA_126 PRIMARY KEY (cd_objeto, cd_papel_profissional);

ALTER TABLE ONLY a_objeto_contrato_atividade
    ADD CONSTRAINT pk_K_SCHEMA_127 PRIMARY KEY (cd_objeto, cd_etapa, cd_atividade);

ALTER TABLE ONLY a_medicao_medida
    ADD CONSTRAINT pk_K_SCHEMA_128 PRIMARY KEY (cd_medicao, cd_medida);

ALTER TABLE ONLY a_item_teste_requisito_doc
    ADD CONSTRAINT pk_K_SCHEMA_129 PRIMARY KEY (cd_arq_item_teste_requisito, cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste);

ALTER TABLE ONLY a_item_teste_requisito
    ADD CONSTRAINT pk_K_SCHEMA_130 PRIMARY KEY (cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste);

ALTER TABLE ONLY a_item_teste_regra_negocio_doc
    ADD CONSTRAINT pk_K_SCHEMA_131 PRIMARY KEY (cd_arq_item_teste_regra_neg, dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio);

ALTER TABLE ONLY a_item_teste_regra_negocio
    ADD CONSTRAINT pk_K_SCHEMA_132 PRIMARY KEY (dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio);

ALTER TABLE ONLY a_item_teste_caso_de_uso_doc
    ADD CONSTRAINT pk_K_SCHEMA_133 PRIMARY KEY (cd_arq_item_teste_caso_de_uso, cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso);

ALTER TABLE ONLY a_item_teste_caso_de_uso
    ADD CONSTRAINT pk_K_SCHEMA_134 PRIMARY KEY (cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso);

ALTER TABLE ONLY a_informacao_tecnica
    ADD CONSTRAINT pk_K_SCHEMA_135 PRIMARY KEY (cd_projeto, cd_tipo_dado_tecnico);

ALTER TABLE ONLY a_gerencia_mudanca
    ADD CONSTRAINT pk_K_SCHEMA_136 PRIMARY KEY (dt_gerencia_mudanca, cd_item_controle_baseline, cd_projeto, cd_item_controlado, dt_versao_item_controlado);

ALTER TABLE ONLY a_funcionalidade_menu
    ADD CONSTRAINT pk_K_SCHEMA_137 PRIMARY KEY (cd_funcionalidade, cd_menu);

ALTER TABLE ONLY a_documentacao_projeto
    ADD CONSTRAINT pk_K_SCHEMA_138 PRIMARY KEY (dt_documentacao_projeto, cd_projeto, cd_tipo_documentacao);

ALTER TABLE ONLY a_documentacao_profissional
    ADD CONSTRAINT pk_K_SCHEMA_139 PRIMARY KEY (dt_documentacao_profissional, cd_tipo_documentacao, cd_profissional);

ALTER TABLE ONLY a_disponibilidade_servico_doc
    ADD CONSTRAINT pk_K_SCHEMA_140 PRIMARY KEY (cd_disponibilidade_servico, cd_objeto, cd_tipo_documentacao, dt_doc_disponibilidade_servico);

ALTER TABLE ONLY a_demanda_profissional
    ADD CONSTRAINT pk_K_SCHEMA_141 PRIMARY KEY (cd_profissional, cd_demanda);

ALTER TABLE ONLY a_demanda_prof_nivel_servico
    ADD CONSTRAINT pk_K_SCHEMA_142 PRIMARY KEY (cd_demanda, cd_profissional, cd_nivel_servico);

ALTER TABLE ONLY a_definicao_processo
    ADD CONSTRAINT pk_K_SCHEMA_143 PRIMARY KEY (cd_perfil, cd_funcionalidade, st_definicao_processo);

ALTER TABLE ONLY a_controle
    ADD CONSTRAINT pk_K_SCHEMA_144 PRIMARY KEY (cd_controle, cd_projeto_previsto, cd_projeto, cd_proposta, cd_contrato);

ALTER TABLE ONLY a_contrato_projeto
    ADD CONSTRAINT pk_K_SCHEMA_145 PRIMARY KEY (cd_contrato, cd_projeto);

ALTER TABLE ONLY a_contrato_evento
    ADD CONSTRAINT pk_K_SCHEMA_146 PRIMARY KEY (cd_contrato, cd_evento);

ALTER TABLE ONLY a_contrato_definicao_metrica
    ADD CONSTRAINT pk_K_SCHEMA_147 PRIMARY KEY (cd_contrato, cd_definicao_metrica);

ALTER TABLE ONLY a_conjunto_medida_medicao
    ADD CONSTRAINT pk_K_SCHEMA_148 PRIMARY KEY (cd_conjunto_medida, cd_box_inicio, cd_medicao);

ALTER TABLE ONLY a_conhecimento_projeto
    ADD CONSTRAINT pk_K_SCHEMA_149 PRIMARY KEY (cd_tipo_conhecimento, cd_conhecimento, cd_projeto);

ALTER TABLE ONLY a_baseline_item_controle
    ADD CONSTRAINT pk_K_SCHEMA_150 PRIMARY KEY (cd_projeto, dt_baseline, cd_item_controle_baseline, cd_item_controlado, dt_versao_item_controlado);

ALTER TABLE ONLY b_pergunta_pedido
    ADD CONSTRAINT pk_K_SCHEMA_151 PRIMARY KEY (cd_pergunta_pedido);

ALTER TABLE ONLY b_resposta_pedido
    ADD CONSTRAINT pk_K_SCHEMA_152 PRIMARY KEY (cd_resposta_pedido);

ALTER TABLE ONLY s_solicitacao_pedido
    ADD CONSTRAINT pk_K_SCHEMA_153 PRIMARY KEY (cd_solicitacao_pedido);

ALTER TABLE ONLY s_historico_pedido
    ADD CONSTRAINT pk_K_SCHEMA_154 PRIMARY KEY (cd_historico_pedido);

ALTER TABLE ONLY a_solicitacao_resposta_pedido
    ADD CONSTRAINT pk_K_SCHEMA_155 PRIMARY KEY (cd_solicitacao_pedido, cd_pergunta_pedido, cd_resposta_pedido);

ALTER TABLE ONLY s_arquivo_pedido
    ADD CONSTRAINT pk_K_SCHEMA_156 PRIMARY KEY (cd_arquivo_pedido);

ALTER TABLE ONLY a_opcao_resp_pergunta_pedido
    ADD CONSTRAINT pk_K_SCHEMA_157 PRIMARY KEY (cd_pergunta_pedido, cd_resposta_pedido);

ALTER TABLE ONLY a_pergunta_depende_resp_pedido
    ADD CONSTRAINT pk_K_SCHEMA_158 PRIMARY KEY (cd_pergunta_depende, cd_pergunta_pedido, cd_resposta_pedido);

ALTER TABLE ONLY a_documentacao_contrato
    ADD CONSTRAINT pk_K_SCHEMA_159 PRIMARY KEY (dt_documentacao_contrato, cd_contrato, cd_tipo_documentacao);

ALTER TABLE ONLY b_rotina
    ADD CONSTRAINT pk_K_SCHEMA_160 PRIMARY KEY (cd_rotina);

ALTER TABLE ONLY b_status_atendimento
    ADD CONSTRAINT pk_K_SCHEMA_161 PRIMARY KEY (cd_status_atendimento);

ALTER TABLE ONLY a_objeto_contrato_rotina
    ADD CONSTRAINT pk_K_SCHEMA_162 PRIMARY KEY (cd_objeto, cd_rotina);

ALTER TABLE ONLY a_rotina_profissional
    ADD CONSTRAINT pk_K_SCHEMA_163 PRIMARY KEY (cd_objeto, cd_profissional, cd_rotina);

ALTER TABLE ONLY s_execucao_rotina
    ADD CONSTRAINT pk_K_SCHEMA_164 PRIMARY KEY (dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina);

ALTER TABLE ONLY s_historico_execucao_rotina
    ADD CONSTRAINT pk_K_SCHEMA_165 PRIMARY KEY (dt_historico_execucao_rotina, cd_rotina, cd_objeto, cd_profissional, dt_execucao_rotina);

ALTER TABLE ONLY s_reuniao_geral
    ADD CONSTRAINT pk_K_SCHEMA_2005 PRIMARY KEY (cd_reuniao_geral, cd_objeto);

ALTER TABLE ONLY a_reuniao_geral_profissional
    ADD CONSTRAINT pk_K_SCHEMA_2103 PRIMARY KEY (cd_objeto, cd_reuniao_geral, cd_profissional);

ALTER TABLE ONLY a_contrato_item_inventario
    ADD CONSTRAINT pk_K_SCHEMA_item_inventario_145 PRIMARY KEY (cd_contrato, cd_item_inventario);

ALTER TABLE ONLY s_inventario
    ADD CONSTRAINT s_inventario_pk PRIMARY KEY (cd_inventario);

CREATE INDEX a_analise_risco_fkindex2 ON s_analise_risco USING btree (cd_item_risco, cd_etapa, cd_atividade);

CREATE INDEX a_baseline_fkindex2 ON a_baseline_item_controle USING btree (dt_baseline, cd_projeto);

CREATE INDEX a_conjunto_medida_medicao_fkindex2 ON a_medicao_medida USING btree (cd_medida);

CREATE INDEX a_questionario_analise_risco_fkindex2 ON a_questionario_analise_risco USING btree (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);

CREATE INDEX b_condicao_sub_item_metrica_fkindex1 ON b_condicao_sub_item_metrica USING btree (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);

CREATE INDEX b_conjunto_medida_has_s_medicao_fkindex2 ON a_medicao_medida USING btree (cd_medicao);

CREATE INDEX b_perfil_profissional_has_b_papel_profissional_fkindex1 ON a_perfil_prof_papel_prof USING btree (cd_perfil_profissional);

CREATE INDEX b_perfil_profissional_has_b_papel_profissional_fkindex2 ON a_perfil_prof_papel_prof USING btree (cd_papel_profissional);

CREATE INDEX ifk_rel_05 ON b_item_metrica USING btree (cd_definicao_metrica);

CREATE INDEX ifk_rel_06 ON b_sub_item_metrica USING btree (cd_item_metrica, cd_definicao_metrica);

CREATE INDEX ifk_rel_08 ON b_condicao_sub_item_metrica USING btree (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);

CREATE INDEX ifk_rel_10 ON s_hist_prop_sub_item_metrica USING btree (cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);

CREATE INDEX ifk_rel_101 ON a_contrato_definicao_metrica USING btree (cd_contrato);

CREATE INDEX ifk_rel_11 ON a_medicao_medida USING btree (cd_medicao);

CREATE INDEX ifk_rel_112 ON a_contrato_definicao_metrica USING btree (cd_definicao_metrica);

CREATE INDEX ifk_rel_116 ON s_hist_prop_sub_item_metrica USING btree (dt_historico_proposta, cd_projeto, cd_proposta);

CREATE INDEX ifk_rel_12 ON a_proposta_definicao_metrica USING btree (cd_definicao_metrica);

CREATE INDEX ifk_rel_13 ON a_contrato_projeto USING btree (cd_contrato);

CREATE INDEX ifk_rel_14 ON a_contrato_projeto USING btree (cd_projeto);

CREATE INDEX ifk_rel_15 ON s_historico_proposta_metrica USING btree (dt_historico_proposta, cd_projeto, cd_proposta);

CREATE INDEX ifk_rel_16 ON s_historico_proposta_metrica USING btree (cd_projeto, cd_proposta, cd_definicao_metrica);

CREATE INDEX ifk_rel_52 ON a_baseline_item_controle USING btree (cd_item_controle_baseline);

CREATE INDEX ifk_rel_54 ON a_medicao_medida USING btree (cd_medida);

CREATE INDEX ifk_rel_56 ON s_analise_risco USING btree (cd_item_risco, cd_etapa, cd_atividade);

CREATE INDEX ifk_rel_58 ON a_questionario_analise_risco USING btree (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);

CREATE INDEX ifk_rel_61 ON a_baseline_item_controle USING btree (dt_baseline, cd_projeto);

CREATE INDEX ifk_rel_63 ON a_questionario_analise_risco USING btree (dt_analise_risco, cd_projeto, cd_proposta, cd_atividade, cd_etapa, cd_item_risco);

CREATE INDEX ifk_rel_64 ON a_perfil_prof_papel_prof USING btree (cd_papel_profissional);

CREATE INDEX ifk_rel_69 ON a_requisito_caso_de_uso USING btree (cd_requisito, dt_versao_requisito, cd_projeto);

CREATE INDEX ifk_rel_70 ON a_requisito_caso_de_uso USING btree (cd_projeto, cd_modulo, cd_caso_de_uso, dt_versao_caso_de_uso);

CREATE INDEX ifk_rel_91 ON a_proposta_definicao_metrica USING btree (cd_proposta, cd_projeto);

CREATE INDEX ifk_rel_93 ON a_perfil_prof_papel_prof USING btree (cd_perfil_profissional);

CREATE INDEX ix_ak_proposta_ni_solicitacao ON s_proposta USING btree (ni_solicitacao);

CREATE INDEX s_baseline_fkindex1 ON a_baseline_item_controle USING btree (cd_item_controle_baseline);

CREATE INDEX s_contrato_has_b_definicao_metrica_fkindex1 ON a_contrato_definicao_metrica USING btree (cd_contrato);

CREATE INDEX s_contrato_has_b_definicao_metrica_fkindex2 ON a_contrato_definicao_metrica USING btree (cd_definicao_metrica);

CREATE INDEX s_contrato_has_s_projeto_fkindex1 ON a_contrato_projeto USING btree (cd_contrato);

CREATE INDEX s_contrato_has_s_projeto_fkindex2 ON a_contrato_projeto USING btree (cd_projeto);

CREATE INDEX s_historico_metrica_fkindex1 ON s_hist_prop_sub_item_metrica USING btree (dt_historico_proposta, cd_projeto, cd_proposta);

CREATE INDEX s_historico_metrica_fkindex2 ON s_hist_prop_sub_item_metrica USING btree (cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);

CREATE INDEX s_historico_proposta_has_a_proposta_definicao_metrica_fkindex1 ON s_historico_proposta_metrica USING btree (dt_historico_proposta, cd_projeto, cd_proposta);

CREATE INDEX s_historico_proposta_has_a_proposta_definicao_metrica_fkindex2 ON s_historico_proposta_metrica USING btree (cd_projeto, cd_proposta, cd_definicao_metrica);

CREATE INDEX s_item_metrica_fkindex1 ON b_item_metrica USING btree (cd_definicao_metrica);

CREATE INDEX s_proposta_has_b_definicao_metrica_fkindex1 ON a_proposta_definicao_metrica USING btree (cd_proposta, cd_projeto);

CREATE INDEX s_proposta_has_b_definicao_metrica_fkindex2 ON a_proposta_definicao_metrica USING btree (cd_definicao_metrica);

CREATE INDEX s_proposta_has_b_item_risco_fkindex1 ON s_analise_risco USING btree (cd_proposta, cd_projeto);

CREATE INDEX s_requisito_has_s_caso_de_uso_fkindex1 ON a_requisito_caso_de_uso USING btree (cd_requisito, dt_versao_requisito, cd_projeto);

CREATE INDEX s_requisito_has_s_caso_de_uso_fkindex2 ON a_requisito_caso_de_uso USING btree (cd_projeto, cd_modulo, cd_caso_de_uso, dt_versao_caso_de_uso);

CREATE INDEX s_sub_item_metrica_fkindex1 ON b_sub_item_metrica USING btree (cd_item_metrica, cd_definicao_metrica);

ALTER TABLE ONLY a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_fk1 FOREIGN KEY (cd_item_inventario) REFERENCES b_item_inventario(cd_item_inventario);

ALTER TABLE ONLY a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_fk2 FOREIGN KEY (cd_inventario) REFERENCES s_inventario(cd_inventario);

ALTER TABLE ONLY b_item_inventario
    ADD CONSTRAINT b_item_inventario_fk1 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);

ALTER TABLE ONLY b_subitem_inv_descri
    ADD CONSTRAINT b_subitem_inv_descri_fk1 FOREIGN KEY (cd_item_inventario, cd_subitem_inventario) REFERENCES b_subitem_inventario(cd_item_inventario, cd_subitem_inventario);

ALTER TABLE ONLY b_subitem_inventario
    ADD CONSTRAINT b_subitem_inventario_fk1 FOREIGN KEY (cd_item_inventario) REFERENCES b_item_inventario(cd_item_inventario);

ALTER TABLE ONLY a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_fk1 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_fk2 FOREIGN KEY (cd_projeto_continuado, cd_objeto) REFERENCES s_projeto_continuado(cd_projeto_continuado, cd_objeto);

ALTER TABLE ONLY s_usuario_pedido
    ADD CONSTRAINT fk_K_SCHEMA_001 FOREIGN KEY (cd_unidade_usuario) REFERENCES b_unidade(cd_unidade);

ALTER TABLE ONLY s_tabela
    ADD CONSTRAINT fk_K_SCHEMA_002 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_solicitacao
    ADD CONSTRAINT fk_K_SCHEMA_003 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY s_solicitacao
    ADD CONSTRAINT fk_K_SCHEMA_004 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY s_solicitacao
    ADD CONSTRAINT fk_K_SCHEMA_005 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);

ALTER TABLE ONLY s_situacao_projeto
    ADD CONSTRAINT fk_K_SCHEMA_006 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_reuniao
    ADD CONSTRAINT fk_K_SCHEMA_007 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_requisito
    ADD CONSTRAINT fk_K_SCHEMA_008 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_regra_negocio
    ADD CONSTRAINT fk_K_SCHEMA_009 FOREIGN KEY (cd_projeto_regra_negocio) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_proposta
    ADD CONSTRAINT fk_K_SCHEMA_010 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto) REFERENCES s_solicitacao(ni_solicitacao, ni_ano_solicitacao, cd_objeto);

ALTER TABLE ONLY s_proposta
    ADD CONSTRAINT fk_K_SCHEMA_011 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_projeto_previsto
    ADD CONSTRAINT fk_K_SCHEMA_012 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);

ALTER TABLE ONLY s_projeto_previsto
    ADD CONSTRAINT fk_K_SCHEMA_013 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY s_projeto_continuado
    ADD CONSTRAINT fk_K_SCHEMA_014 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY s_projeto
    ADD CONSTRAINT fk_K_SCHEMA_015 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);

ALTER TABLE ONLY s_projeto
    ADD CONSTRAINT fk_K_SCHEMA_016 FOREIGN KEY (cd_status) REFERENCES b_status(cd_status);

ALTER TABLE ONLY s_projeto
    ADD CONSTRAINT fk_K_SCHEMA_017 FOREIGN KEY (cd_profissional_gerente) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY s_profissional
    ADD CONSTRAINT fk_K_SCHEMA_018 FOREIGN KEY (cd_relacao_contratual) REFERENCES b_relacao_contratual(cd_relacao_contratual);

ALTER TABLE ONLY s_profissional
    ADD CONSTRAINT fk_K_SCHEMA_019 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);

ALTER TABLE ONLY s_profissional
    ADD CONSTRAINT fk_K_SCHEMA_020 FOREIGN KEY (cd_empresa) REFERENCES s_empresa(cd_empresa);

ALTER TABLE ONLY s_produto_parcela
    ADD CONSTRAINT fk_K_SCHEMA_021 FOREIGN KEY (cd_tipo_produto) REFERENCES b_tipo_produto(cd_tipo_produto);

ALTER TABLE ONLY s_produto_parcela
    ADD CONSTRAINT fk_K_SCHEMA_022 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta) REFERENCES s_parcela(cd_parcela, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_processamento_proposta
    ADD CONSTRAINT fk_K_SCHEMA_023 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY s_processamento_parcela
    ADD CONSTRAINT fk_K_SCHEMA_024 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta) REFERENCES s_parcela(cd_parcela, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_previsao_projeto_diario
    ADD CONSTRAINT fk_K_SCHEMA_025 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_pre_projeto_evolutivo
    ADD CONSTRAINT fk_K_SCHEMA_026 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_pre_demanda
    ADD CONSTRAINT fk_K_SCHEMA_027 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto_receptor) REFERENCES s_solicitacao(ni_solicitacao, ni_ano_solicitacao, cd_objeto);

ALTER TABLE ONLY s_pre_demanda
    ADD CONSTRAINT fk_K_SCHEMA_028 FOREIGN KEY (cd_objeto_emissor) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY s_pre_demanda
    ADD CONSTRAINT fk_K_SCHEMA_029 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);

ALTER TABLE ONLY s_plano_implantacao
    ADD CONSTRAINT fk_K_SCHEMA_030 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY s_penalizacao
    ADD CONSTRAINT fk_K_SCHEMA_031 FOREIGN KEY (cd_penalidade, cd_contrato) REFERENCES b_penalidade(cd_penalidade, cd_contrato);

ALTER TABLE ONLY s_parcela
    ADD CONSTRAINT fk_K_SCHEMA_032 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY s_objeto_contrato
    ADD CONSTRAINT fk_K_SCHEMA_033 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY s_modulo_continuado
    ADD CONSTRAINT fk_K_SCHEMA_034 FOREIGN KEY (cd_projeto_continuado, cd_objeto) REFERENCES s_projeto_continuado(cd_projeto_continuado, cd_objeto);

ALTER TABLE ONLY s_modulo
    ADD CONSTRAINT fk_K_SCHEMA_035 FOREIGN KEY (cd_status) REFERENCES b_status(cd_status);

ALTER TABLE ONLY s_modulo
    ADD CONSTRAINT fk_K_SCHEMA_036 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_mensageria
    ADD CONSTRAINT fk_K_SCHEMA_037 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);

ALTER TABLE ONLY s_mensageria
    ADD CONSTRAINT fk_K_SCHEMA_038 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY s_interacao
    ADD CONSTRAINT fk_K_SCHEMA_039 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);

ALTER TABLE ONLY s_interacao
    ADD CONSTRAINT fk_K_SCHEMA_040 FOREIGN KEY (cd_ator) REFERENCES s_ator(cd_ator);

ALTER TABLE ONLY s_historico_proposta_produto
    ADD CONSTRAINT fk_K_SCHEMA_042 FOREIGN KEY (cd_tipo_produto) REFERENCES b_tipo_produto(cd_tipo_produto);

ALTER TABLE ONLY s_historico_proposta_produto
    ADD CONSTRAINT fk_K_SCHEMA_043 FOREIGN KEY (cd_proposta, cd_projeto, dt_historico_proposta, cd_historico_proposta_parcela) REFERENCES s_historico_proposta_parcela(cd_proposta, cd_projeto, dt_historico_proposta, cd_historico_proposta_parcela);

ALTER TABLE ONLY s_historico_proposta_parcela
    ADD CONSTRAINT fk_K_SCHEMA_044 FOREIGN KEY (dt_historico_proposta, cd_projeto, cd_proposta) REFERENCES s_historico_proposta(dt_historico_proposta, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_historico_proposta_metrica
    ADD CONSTRAINT fk_K_SCHEMA_045 FOREIGN KEY (dt_historico_proposta, cd_projeto, cd_proposta) REFERENCES s_historico_proposta(dt_historico_proposta, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_historico_proposta
    ADD CONSTRAINT fk_K_SCHEMA_047 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY s_historico_projeto_continuado
    ADD CONSTRAINT fk_K_SCHEMA_048 FOREIGN KEY (cd_modulo_continuado, cd_objeto, cd_projeto_continuado) REFERENCES s_modulo_continuado(cd_modulo_continuado, cd_objeto, cd_projeto_continuado);

ALTER TABLE ONLY s_historico_projeto_continuado
    ADD CONSTRAINT fk_K_SCHEMA_049 FOREIGN KEY (cd_atividade, cd_etapa) REFERENCES b_atividade(cd_atividade, cd_etapa);

ALTER TABLE ONLY s_historico_execucao_demanda
    ADD CONSTRAINT fk_K_SCHEMA_050 FOREIGN KEY (cd_demanda, cd_profissional, cd_nivel_servico) REFERENCES a_demanda_prof_nivel_servico(cd_demanda, cd_profissional, cd_nivel_servico);

ALTER TABLE ONLY s_historico
    ADD CONSTRAINT fk_K_SCHEMA_051 FOREIGN KEY (cd_atividade, cd_etapa) REFERENCES b_atividade(cd_atividade, cd_etapa);

ALTER TABLE ONLY s_hist_prop_sub_item_metrica
    ADD CONSTRAINT fk_K_SCHEMA_052 FOREIGN KEY (cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica) REFERENCES a_proposta_sub_item_metrica(cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);

ALTER TABLE ONLY s_hist_prop_sub_item_metrica
    ADD CONSTRAINT fk_K_SCHEMA_053 FOREIGN KEY (dt_historico_proposta, cd_projeto, cd_proposta) REFERENCES s_historico_proposta(dt_historico_proposta, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_gerencia_qualidade
    ADD CONSTRAINT fk_K_SCHEMA_054 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY s_gerencia_qualidade
    ADD CONSTRAINT fk_K_SCHEMA_055 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY s_extrato_mensal_parcela
    ADD CONSTRAINT fk_K_SCHEMA_056 FOREIGN KEY (ni_mes_extrato, ni_ano_extrato, cd_contrato) REFERENCES s_extrato_mensal(ni_mes_extrato, ni_ano_extrato, cd_contrato);

ALTER TABLE ONLY s_extrato_mensal_parcela
    ADD CONSTRAINT fk_K_SCHEMA_057 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta) REFERENCES s_parcela(cd_parcela, cd_projeto, cd_proposta);

ALTER TABLE ONLY s_extrato_mensal
    ADD CONSTRAINT fk_K_SCHEMA_058 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY s_disponibilidade_servico
    ADD CONSTRAINT fk_K_SCHEMA_059 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY s_demanda
    ADD CONSTRAINT fk_K_SCHEMA_060 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto) REFERENCES s_solicitacao(ni_solicitacao, ni_ano_solicitacao, cd_objeto);

ALTER TABLE ONLY s_demanda
    ADD CONSTRAINT fk_K_SCHEMA_061 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);

ALTER TABLE ONLY s_contrato
    ADD CONSTRAINT fk_K_SCHEMA_062 FOREIGN KEY (cd_empresa) REFERENCES s_empresa(cd_empresa);

ALTER TABLE ONLY s_contrato
    ADD CONSTRAINT fk_K_SCHEMA_063 FOREIGN KEY (cd_contato_empresa, cd_empresa) REFERENCES s_contato_empresa(cd_contato_empresa, cd_empresa);

ALTER TABLE ONLY s_contato_empresa
    ADD CONSTRAINT fk_K_SCHEMA_064 FOREIGN KEY (cd_empresa) REFERENCES s_empresa(cd_empresa);

ALTER TABLE ONLY s_condicao
    ADD CONSTRAINT fk_K_SCHEMA_065 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);

ALTER TABLE ONLY s_complemento
    ADD CONSTRAINT fk_K_SCHEMA_066 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);

ALTER TABLE ONLY s_coluna
    ADD CONSTRAINT fk_K_SCHEMA_067 FOREIGN KEY (tx_tabela_referencia, cd_projeto_referencia) REFERENCES s_tabela(tx_tabela, cd_projeto);

ALTER TABLE ONLY s_coluna
    ADD CONSTRAINT fk_K_SCHEMA_068 FOREIGN KEY (tx_tabela, cd_projeto) REFERENCES s_tabela(tx_tabela, cd_projeto);

ALTER TABLE ONLY s_caso_de_uso
    ADD CONSTRAINT fk_K_SCHEMA_069 FOREIGN KEY (cd_modulo, cd_projeto) REFERENCES s_modulo(cd_modulo, cd_projeto);

ALTER TABLE ONLY s_baseline
    ADD CONSTRAINT fk_K_SCHEMA_070 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_base_conhecimento
    ADD CONSTRAINT fk_K_SCHEMA_071 FOREIGN KEY (cd_area_conhecimento) REFERENCES b_area_conhecimento(cd_area_conhecimento);

ALTER TABLE ONLY s_ator
    ADD CONSTRAINT fk_K_SCHEMA_072 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_analise_risco
    ADD CONSTRAINT fk_K_SCHEMA_073 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY s_analise_risco
    ADD CONSTRAINT fk_K_SCHEMA_074 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY s_analise_risco
    ADD CONSTRAINT fk_K_SCHEMA_075 FOREIGN KEY (cd_item_risco, cd_etapa, cd_atividade) REFERENCES b_item_risco(cd_item_risco, cd_etapa, cd_atividade);

ALTER TABLE ONLY s_analise_medicao
    ADD CONSTRAINT fk_K_SCHEMA_076 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY s_analise_medicao
    ADD CONSTRAINT fk_K_SCHEMA_077 FOREIGN KEY (cd_medicao) REFERENCES s_medicao(cd_medicao);

ALTER TABLE ONLY s_analise_medicao
    ADD CONSTRAINT fk_K_SCHEMA_078 FOREIGN KEY (cd_box_inicio) REFERENCES b_box_inicio(cd_box_inicio);

ALTER TABLE ONLY s_analise_matriz_rastreab
    ADD CONSTRAINT fk_K_SCHEMA_079 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_analise_execucao_projeto
    ADD CONSTRAINT fk_K_SCHEMA_080 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_agenda_plano_implantacao
    ADD CONSTRAINT fk_K_SCHEMA_081 FOREIGN KEY (cd_projeto, cd_proposta) REFERENCES s_plano_implantacao(cd_projeto, cd_proposta);

ALTER TABLE ONLY b_tipo_produto
    ADD CONSTRAINT fk_K_SCHEMA_082 FOREIGN KEY (cd_definicao_metrica) REFERENCES b_definicao_metrica(cd_definicao_metrica);

ALTER TABLE ONLY b_sub_item_metrica
    ADD CONSTRAINT fk_K_SCHEMA_083 FOREIGN KEY (cd_item_metrica, cd_definicao_metrica) REFERENCES b_item_metrica(cd_item_metrica, cd_definicao_metrica);

ALTER TABLE ONLY b_questao_analise_risco
    ADD CONSTRAINT fk_K_SCHEMA_084 FOREIGN KEY (cd_item_risco, cd_etapa, cd_atividade) REFERENCES b_item_risco(cd_item_risco, cd_etapa, cd_atividade);

ALTER TABLE ONLY b_perfil_profissional
    ADD CONSTRAINT fk_K_SCHEMA_085 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);

ALTER TABLE ONLY b_penalidade
    ADD CONSTRAINT fk_K_SCHEMA_086 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY b_papel_profissional
    ADD CONSTRAINT fk_K_SCHEMA_087 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);

ALTER TABLE ONLY b_nivel_servico
    ADD CONSTRAINT fk_K_SCHEMA_088 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY b_menu
    ADD CONSTRAINT fk_K_SCHEMA_089 FOREIGN KEY (cd_menu_pai) REFERENCES b_menu(cd_menu);

ALTER TABLE ONLY b_item_risco
    ADD CONSTRAINT fk_K_SCHEMA_090 FOREIGN KEY (cd_atividade, cd_etapa) REFERENCES b_atividade(cd_atividade, cd_etapa);

ALTER TABLE ONLY b_item_metrica
    ADD CONSTRAINT fk_K_SCHEMA_091 FOREIGN KEY (cd_definicao_metrica) REFERENCES b_definicao_metrica(cd_definicao_metrica);

ALTER TABLE ONLY b_etapa
    ADD CONSTRAINT fk_K_SCHEMA_093 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);

ALTER TABLE ONLY b_conhecimento
    ADD CONSTRAINT fk_K_SCHEMA_094 FOREIGN KEY (cd_tipo_conhecimento) REFERENCES b_tipo_conhecimento(cd_tipo_conhecimento);

ALTER TABLE ONLY b_condicao_sub_item_metrica
    ADD CONSTRAINT fk_K_SCHEMA_095 FOREIGN KEY (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica) REFERENCES b_sub_item_metrica(cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);

ALTER TABLE ONLY b_atividade
    ADD CONSTRAINT fk_K_SCHEMA_096 FOREIGN KEY (cd_etapa) REFERENCES b_etapa(cd_etapa);

ALTER TABLE ONLY a_treinamento_profissional
    ADD CONSTRAINT fk_K_SCHEMA_097 FOREIGN KEY (cd_treinamento) REFERENCES b_treinamento(cd_treinamento);

ALTER TABLE ONLY a_treinamento_profissional
    ADD CONSTRAINT fk_K_SCHEMA_098 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_reuniao_profissional
    ADD CONSTRAINT fk_K_SCHEMA_099 FOREIGN KEY (cd_reuniao, cd_projeto) REFERENCES s_reuniao(cd_reuniao, cd_projeto);

ALTER TABLE ONLY a_reuniao_profissional
    ADD CONSTRAINT fk_K_SCHEMA_100 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_requisito_dependente
    ADD CONSTRAINT fk_K_SCHEMA_101 FOREIGN KEY (cd_requisito, dt_versao_requisito, cd_projeto) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE ONLY a_requisito_dependente
    ADD CONSTRAINT fk_K_SCHEMA_102 FOREIGN KEY (cd_requisito_ascendente, dt_versao_requisito_ascendente, cd_projeto_ascendente) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE ONLY a_requisito_caso_de_uso
    ADD CONSTRAINT fk_K_SCHEMA_103 FOREIGN KEY (cd_requisito, dt_versao_requisito, cd_projeto) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE ONLY a_requisito_caso_de_uso
    ADD CONSTRAINT fk_K_SCHEMA_104 FOREIGN KEY (cd_projeto, cd_modulo, cd_caso_de_uso, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_projeto, cd_modulo, cd_caso_de_uso, dt_versao_caso_de_uso);

ALTER TABLE ONLY a_regra_negocio_requisito
    ADD CONSTRAINT fk_K_SCHEMA_105 FOREIGN KEY (cd_requisito, dt_versao_requisito, cd_projeto) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE ONLY a_regra_negocio_requisito
    ADD CONSTRAINT fk_K_SCHEMA_106 FOREIGN KEY (cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio) REFERENCES s_regra_negocio(cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio);

ALTER TABLE ONLY a_questionario_analise_risco
    ADD CONSTRAINT fk_K_SCHEMA_107 FOREIGN KEY (dt_analise_risco, cd_projeto, cd_proposta, cd_atividade, cd_etapa, cd_item_risco) REFERENCES s_analise_risco(dt_analise_risco, cd_projeto, cd_proposta, cd_atividade, cd_etapa, cd_item_risco);

ALTER TABLE ONLY a_questionario_analise_risco
    ADD CONSTRAINT fk_K_SCHEMA_108 FOREIGN KEY (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco) REFERENCES b_questao_analise_risco(cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);

ALTER TABLE ONLY a_questionario_analise_risco
    ADD CONSTRAINT fk_K_SCHEMA_109 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_quest_avaliacao_qualidade
    ADD CONSTRAINT fk_K_SCHEMA_110 FOREIGN KEY (cd_projeto, cd_proposta) REFERENCES s_proposta(cd_projeto, cd_proposta);

ALTER TABLE ONLY a_quest_avaliacao_qualidade
    ADD CONSTRAINT fk_K_SCHEMA_111 FOREIGN KEY (cd_item_grupo_fator, cd_grupo_fator) REFERENCES b_item_grupo_fator(cd_item_grupo_fator, cd_grupo_fator);

ALTER TABLE ONLY a_proposta_sub_item_metrica
    ADD CONSTRAINT fk_K_SCHEMA_113 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY a_proposta_modulo
    ADD CONSTRAINT fk_K_SCHEMA_114 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY a_proposta_modulo
    ADD CONSTRAINT fk_K_SCHEMA_115 FOREIGN KEY (cd_modulo, cd_projeto) REFERENCES s_modulo(cd_modulo, cd_projeto);

ALTER TABLE ONLY a_proposta_definicao_metrica
    ADD CONSTRAINT fk_K_SCHEMA_116 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY a_proposta_definicao_metrica
    ADD CONSTRAINT fk_K_SCHEMA_117 FOREIGN KEY (cd_definicao_metrica) REFERENCES b_definicao_metrica(cd_definicao_metrica);

ALTER TABLE ONLY a_profissional_projeto
    ADD CONSTRAINT fk_K_SCHEMA_118 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY a_profissional_projeto
    ADD CONSTRAINT fk_K_SCHEMA_119 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_profissional_projeto
    ADD CONSTRAINT fk_K_SCHEMA_120 FOREIGN KEY (cd_papel_profissional) REFERENCES b_papel_profissional(cd_papel_profissional);

ALTER TABLE ONLY a_profissional_produto
    ADD CONSTRAINT fk_K_SCHEMA_121 FOREIGN KEY (cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela) REFERENCES s_produto_parcela(cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela);

ALTER TABLE ONLY a_profissional_produto
    ADD CONSTRAINT fk_K_SCHEMA_122 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_profissional_objeto_contrato
    ADD CONSTRAINT fk_K_SCHEMA_123 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_profissional_objeto_contrato
    ADD CONSTRAINT fk_K_SCHEMA_124 FOREIGN KEY (cd_perfil_profissional) REFERENCES b_perfil_profissional(cd_perfil_profissional);

ALTER TABLE ONLY a_profissional_objeto_contrato
    ADD CONSTRAINT fk_K_SCHEMA_125 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY a_profissional_menu
    ADD CONSTRAINT fk_K_SCHEMA_126 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY a_profissional_menu
    ADD CONSTRAINT fk_K_SCHEMA_127 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_profissional_menu
    ADD CONSTRAINT fk_K_SCHEMA_128 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);

ALTER TABLE ONLY a_profissional_mensageria
    ADD CONSTRAINT fk_K_SCHEMA_129 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_profissional_mensageria
    ADD CONSTRAINT fk_K_SCHEMA_130 FOREIGN KEY (cd_mensageria) REFERENCES s_mensageria(cd_mensageria);

ALTER TABLE ONLY a_profissional_conhecimento
    ADD CONSTRAINT fk_K_SCHEMA_131 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_profissional_conhecimento
    ADD CONSTRAINT fk_K_SCHEMA_132 FOREIGN KEY (cd_conhecimento, cd_tipo_conhecimento) REFERENCES b_conhecimento(cd_conhecimento, cd_tipo_conhecimento);

ALTER TABLE ONLY a_planejamento
    ADD CONSTRAINT fk_K_SCHEMA_133 FOREIGN KEY (cd_modulo, cd_projeto) REFERENCES s_modulo(cd_modulo, cd_projeto);

ALTER TABLE ONLY a_planejamento
    ADD CONSTRAINT fk_K_SCHEMA_134 FOREIGN KEY (cd_atividade, cd_etapa) REFERENCES b_atividade(cd_atividade, cd_etapa);

ALTER TABLE ONLY a_perfil_prof_papel_prof
    ADD CONSTRAINT fk_K_SCHEMA_135 FOREIGN KEY (cd_perfil_profissional) REFERENCES b_perfil_profissional(cd_perfil_profissional);

ALTER TABLE ONLY a_perfil_prof_papel_prof
    ADD CONSTRAINT fk_K_SCHEMA_136 FOREIGN KEY (cd_papel_profissional) REFERENCES b_papel_profissional(cd_papel_profissional);

ALTER TABLE ONLY a_perfil_menu_sistema
    ADD CONSTRAINT fk_K_SCHEMA_137 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);

ALTER TABLE ONLY a_perfil_menu_sistema
    ADD CONSTRAINT fk_K_SCHEMA_138 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);

ALTER TABLE ONLY a_perfil_menu
    ADD CONSTRAINT fk_K_SCHEMA_139 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY a_perfil_menu
    ADD CONSTRAINT fk_K_SCHEMA_140 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);

ALTER TABLE ONLY a_perfil_menu
    ADD CONSTRAINT fk_K_SCHEMA_141 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);

ALTER TABLE ONLY a_perfil_box_inicio
    ADD CONSTRAINT fk_K_SCHEMA_142 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);

ALTER TABLE ONLY a_perfil_box_inicio
    ADD CONSTRAINT fk_K_SCHEMA_143 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY a_perfil_box_inicio
    ADD CONSTRAINT fk_K_SCHEMA_144 FOREIGN KEY (cd_box_inicio) REFERENCES b_box_inicio(cd_box_inicio);

ALTER TABLE ONLY a_parecer_tecnico_proposta
    ADD CONSTRAINT fk_K_SCHEMA_145 FOREIGN KEY (cd_item_parecer_tecnico) REFERENCES b_item_parecer_tecnico(cd_item_parecer_tecnico);

ALTER TABLE ONLY a_parecer_tecnico_parcela
    ADD CONSTRAINT fk_K_SCHEMA_146 FOREIGN KEY (cd_proposta, cd_projeto, cd_parcela, cd_processamento_parcela) REFERENCES s_processamento_parcela(cd_proposta, cd_projeto, cd_parcela, cd_processamento_parcela);

ALTER TABLE ONLY a_parecer_tecnico_parcela
    ADD CONSTRAINT fk_K_SCHEMA_147 FOREIGN KEY (cd_item_parecer_tecnico) REFERENCES b_item_parecer_tecnico(cd_item_parecer_tecnico);

ALTER TABLE ONLY a_objeto_contrato_perfil_prof
    ADD CONSTRAINT fk_K_SCHEMA_148 FOREIGN KEY (cd_perfil_profissional) REFERENCES b_perfil_profissional(cd_perfil_profissional);

ALTER TABLE ONLY a_objeto_contrato_perfil_prof
    ADD CONSTRAINT fk_K_SCHEMA_149 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY a_objeto_contrato_papel_prof
    ADD CONSTRAINT fk_K_SCHEMA_150 FOREIGN KEY (cd_papel_profissional) REFERENCES b_papel_profissional(cd_papel_profissional);

ALTER TABLE ONLY a_objeto_contrato_papel_prof
    ADD CONSTRAINT fk_K_SCHEMA_151 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY a_objeto_contrato_atividade
    ADD CONSTRAINT fk_K_SCHEMA_152 FOREIGN KEY (cd_etapa, cd_atividade) REFERENCES b_atividade(cd_etapa, cd_atividade);

ALTER TABLE ONLY a_objeto_contrato_atividade
    ADD CONSTRAINT fk_K_SCHEMA_153 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY a_medicao_medida
    ADD CONSTRAINT fk_K_SCHEMA_154 FOREIGN KEY (cd_medida) REFERENCES b_medida(cd_medida);

ALTER TABLE ONLY a_medicao_medida
    ADD CONSTRAINT fk_K_SCHEMA_155 FOREIGN KEY (cd_medicao) REFERENCES s_medicao(cd_medicao);

ALTER TABLE ONLY a_item_teste_requisito_doc
    ADD CONSTRAINT fk_K_SCHEMA_156 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY a_item_teste_requisito_doc
    ADD CONSTRAINT fk_K_SCHEMA_157 FOREIGN KEY (cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste) REFERENCES a_item_teste_requisito(cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste);

ALTER TABLE ONLY a_item_teste_requisito
    ADD CONSTRAINT fk_K_SCHEMA_158 FOREIGN KEY (cd_requisito, dt_versao_requisito, cd_projeto) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);

ALTER TABLE ONLY a_item_teste_requisito
    ADD CONSTRAINT fk_K_SCHEMA_159 FOREIGN KEY (cd_profissional_solucao) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_requisito
    ADD CONSTRAINT fk_K_SCHEMA_160 FOREIGN KEY (cd_profissional_homologacao) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_requisito
    ADD CONSTRAINT fk_K_SCHEMA_161 FOREIGN KEY (cd_profissional_analise) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_requisito
    ADD CONSTRAINT fk_K_SCHEMA_162 FOREIGN KEY (cd_item_teste) REFERENCES b_item_teste(cd_item_teste);

ALTER TABLE ONLY a_item_teste_regra_negocio_doc
    ADD CONSTRAINT fk_K_SCHEMA_163 FOREIGN KEY (dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio) REFERENCES a_item_teste_regra_negocio(dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio);

ALTER TABLE ONLY a_item_teste_regra_negocio_doc
    ADD CONSTRAINT fk_K_SCHEMA_164 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY a_item_teste_regra_negocio
    ADD CONSTRAINT fk_K_SCHEMA_165 FOREIGN KEY (cd_profissional_solucao) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_regra_negocio
    ADD CONSTRAINT fk_K_SCHEMA_166 FOREIGN KEY (cd_profissional_homologacao) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_regra_negocio
    ADD CONSTRAINT fk_K_SCHEMA_167 FOREIGN KEY (cd_profissional_analise) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_regra_negocio
    ADD CONSTRAINT fk_K_SCHEMA_168 FOREIGN KEY (cd_item_teste) REFERENCES b_item_teste(cd_item_teste);

ALTER TABLE ONLY a_item_teste_regra_negocio
    ADD CONSTRAINT fk_K_SCHEMA_169 FOREIGN KEY (cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio) REFERENCES s_regra_negocio(cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio);

ALTER TABLE ONLY a_item_teste_caso_de_uso_doc
    ADD CONSTRAINT fk_K_SCHEMA_170 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY a_item_teste_caso_de_uso_doc
    ADD CONSTRAINT fk_K_SCHEMA_171 FOREIGN KEY (cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso) REFERENCES a_item_teste_caso_de_uso(cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso);

ALTER TABLE ONLY a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_K_SCHEMA_172 FOREIGN KEY (cd_profissional_solucao) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_K_SCHEMA_173 FOREIGN KEY (cd_profissional_homologacao) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_K_SCHEMA_174 FOREIGN KEY (cd_profissional_analise) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_K_SCHEMA_175 FOREIGN KEY (cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso);

ALTER TABLE ONLY a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_K_SCHEMA_176 FOREIGN KEY (cd_item_teste) REFERENCES b_item_teste(cd_item_teste);

ALTER TABLE ONLY a_informacao_tecnica
    ADD CONSTRAINT fk_K_SCHEMA_177 FOREIGN KEY (cd_tipo_dado_tecnico) REFERENCES b_tipo_dado_tecnico(cd_tipo_dado_tecnico);

ALTER TABLE ONLY a_informacao_tecnica
    ADD CONSTRAINT fk_K_SCHEMA_178 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY a_gerencia_mudanca
    ADD CONSTRAINT fk_K_SCHEMA_179 FOREIGN KEY (cd_reuniao, cd_projeto_reuniao) REFERENCES s_reuniao(cd_reuniao, cd_projeto);

ALTER TABLE ONLY a_gerencia_mudanca
    ADD CONSTRAINT fk_K_SCHEMA_180 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY a_gerencia_mudanca
    ADD CONSTRAINT fk_K_SCHEMA_181 FOREIGN KEY (cd_item_controle_baseline) REFERENCES b_item_controle_baseline(cd_item_controle_baseline);

ALTER TABLE ONLY a_funcionalidade_menu
    ADD CONSTRAINT fk_K_SCHEMA_182 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);

ALTER TABLE ONLY a_funcionalidade_menu
    ADD CONSTRAINT fk_K_SCHEMA_183 FOREIGN KEY (cd_funcionalidade) REFERENCES b_funcionalidade(cd_funcionalidade);

ALTER TABLE ONLY a_documentacao_projeto
    ADD CONSTRAINT fk_K_SCHEMA_184 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY a_documentacao_projeto
    ADD CONSTRAINT fk_K_SCHEMA_185 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY a_documentacao_profissional
    ADD CONSTRAINT fk_K_SCHEMA_186 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY a_documentacao_profissional
    ADD CONSTRAINT fk_K_SCHEMA_187 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_disponibilidade_servico_doc
    ADD CONSTRAINT fk_K_SCHEMA_188 FOREIGN KEY (cd_disponibilidade_servico, cd_objeto) REFERENCES s_disponibilidade_servico(cd_disponibilidade_servico, cd_objeto);

ALTER TABLE ONLY a_disponibilidade_servico_doc
    ADD CONSTRAINT fk_K_SCHEMA_189 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY a_demanda_profissional
    ADD CONSTRAINT fk_K_SCHEMA_190 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_demanda_profissional
    ADD CONSTRAINT fk_K_SCHEMA_191 FOREIGN KEY (cd_demanda) REFERENCES s_demanda(cd_demanda);

ALTER TABLE ONLY a_demanda_prof_nivel_servico
    ADD CONSTRAINT fk_K_SCHEMA_192 FOREIGN KEY (cd_nivel_servico) REFERENCES b_nivel_servico(cd_nivel_servico);

ALTER TABLE ONLY a_demanda_prof_nivel_servico
    ADD CONSTRAINT fk_K_SCHEMA_193 FOREIGN KEY (cd_demanda, cd_profissional) REFERENCES a_demanda_profissional(cd_demanda, cd_profissional);

ALTER TABLE ONLY a_definicao_processo
    ADD CONSTRAINT fk_K_SCHEMA_194 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);

ALTER TABLE ONLY a_definicao_processo
    ADD CONSTRAINT fk_K_SCHEMA_195 FOREIGN KEY (cd_funcionalidade) REFERENCES b_funcionalidade(cd_funcionalidade);

ALTER TABLE ONLY a_controle
    ADD CONSTRAINT fk_K_SCHEMA_196 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);

ALTER TABLE ONLY a_controle
    ADD CONSTRAINT fk_K_SCHEMA_197 FOREIGN KEY (cd_projeto_previsto, cd_contrato) REFERENCES s_projeto_previsto(cd_projeto_previsto, cd_contrato);

ALTER TABLE ONLY a_contrato_projeto
    ADD CONSTRAINT fk_K_SCHEMA_198 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY a_contrato_projeto
    ADD CONSTRAINT fk_K_SCHEMA_199 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY a_contrato_definicao_metrica
    ADD CONSTRAINT fk_K_SCHEMA_200 FOREIGN KEY (cd_definicao_metrica) REFERENCES b_definicao_metrica(cd_definicao_metrica);

ALTER TABLE ONLY s_reuniao_geral
    ADD CONSTRAINT fk_K_SCHEMA_2007 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY a_contrato_definicao_metrica
    ADD CONSTRAINT fk_K_SCHEMA_201 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY a_conjunto_medida_medicao
    ADD CONSTRAINT fk_K_SCHEMA_202 FOREIGN KEY (cd_conjunto_medida) REFERENCES b_conjunto_medida(cd_conjunto_medida);

ALTER TABLE ONLY a_conhecimento_projeto
    ADD CONSTRAINT fk_K_SCHEMA_203 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY a_conhecimento_projeto
    ADD CONSTRAINT fk_K_SCHEMA_204 FOREIGN KEY (cd_conhecimento, cd_tipo_conhecimento) REFERENCES b_conhecimento(cd_conhecimento, cd_tipo_conhecimento);

ALTER TABLE ONLY a_baseline_item_controle
    ADD CONSTRAINT fk_K_SCHEMA_205 FOREIGN KEY (dt_baseline, cd_projeto) REFERENCES s_baseline(dt_baseline, cd_projeto);

ALTER TABLE ONLY a_baseline_item_controle
    ADD CONSTRAINT fk_K_SCHEMA_206 FOREIGN KEY (cd_item_controle_baseline) REFERENCES b_item_controle_baseline(cd_item_controle_baseline);

ALTER TABLE ONLY s_solicitacao_pedido
    ADD CONSTRAINT fk_K_SCHEMA_207 FOREIGN KEY (cd_unidade_pedido) REFERENCES b_unidade(cd_unidade);

ALTER TABLE ONLY s_demanda
    ADD CONSTRAINT fk_K_SCHEMA_207 FOREIGN KEY (cd_status_atendimento) REFERENCES b_status_atendimento(cd_status_atendimento);

ALTER TABLE ONLY s_solicitacao_pedido
    ADD CONSTRAINT fk_K_SCHEMA_208 FOREIGN KEY (cd_usuario_pedido) REFERENCES s_usuario_pedido(cd_usuario_pedido);

ALTER TABLE ONLY b_rotina
    ADD CONSTRAINT fk_K_SCHEMA_208 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);

ALTER TABLE ONLY a_objeto_contrato_rotina
    ADD CONSTRAINT fk_K_SCHEMA_208 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY s_historico_pedido
    ADD CONSTRAINT fk_K_SCHEMA_209 FOREIGN KEY (cd_solicitacao_historico) REFERENCES s_solicitacao_pedido(cd_solicitacao_pedido);

ALTER TABLE ONLY a_documentacao_contrato
    ADD CONSTRAINT fk_K_SCHEMA_209 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY a_objeto_contrato_rotina
    ADD CONSTRAINT fk_K_SCHEMA_209 FOREIGN KEY (cd_rotina) REFERENCES b_rotina(cd_rotina);

ALTER TABLE ONLY a_reuniao_geral_profissional
    ADD CONSTRAINT fk_K_SCHEMA_2099 FOREIGN KEY (cd_reuniao_geral, cd_objeto) REFERENCES s_reuniao_geral(cd_reuniao_geral, cd_objeto);

ALTER TABLE ONLY a_solicitacao_resposta_pedido
    ADD CONSTRAINT fk_K_SCHEMA_210 FOREIGN KEY (cd_pergunta_pedido, cd_resposta_pedido) REFERENCES a_opcao_resp_pergunta_pedido(cd_pergunta_pedido, cd_resposta_pedido);

ALTER TABLE ONLY a_documentacao_contrato
    ADD CONSTRAINT fk_K_SCHEMA_210 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY a_rotina_profissional
    ADD CONSTRAINT fk_K_SCHEMA_210 FOREIGN KEY (cd_profissional, cd_objeto) REFERENCES a_profissional_objeto_contrato(cd_profissional, cd_objeto);

ALTER TABLE ONLY a_reuniao_geral_profissional
    ADD CONSTRAINT fk_K_SCHEMA_2100 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY a_solicitacao_resposta_pedido
    ADD CONSTRAINT fk_K_SCHEMA_211 FOREIGN KEY (cd_solicitacao_pedido) REFERENCES s_solicitacao_pedido(cd_solicitacao_pedido);

ALTER TABLE ONLY a_rotina_profissional
    ADD CONSTRAINT fk_K_SCHEMA_211 FOREIGN KEY (cd_rotina, cd_objeto) REFERENCES a_objeto_contrato_rotina(cd_rotina, cd_objeto);

ALTER TABLE ONLY s_arquivo_pedido
    ADD CONSTRAINT fk_K_SCHEMA_212 FOREIGN KEY (cd_solicitacao_pedido, cd_resposta_pedido, cd_pergunta_pedido) REFERENCES a_solicitacao_resposta_pedido(cd_solicitacao_pedido, cd_resposta_pedido, cd_pergunta_pedido);

ALTER TABLE ONLY s_execucao_rotina
    ADD CONSTRAINT fk_K_SCHEMA_212 FOREIGN KEY (cd_rotina, cd_objeto, cd_profissional) REFERENCES a_rotina_profissional(cd_rotina, cd_objeto, cd_profissional);

ALTER TABLE ONLY a_opcao_resp_pergunta_pedido
    ADD CONSTRAINT fk_K_SCHEMA_213 FOREIGN KEY (cd_pergunta_pedido) REFERENCES b_pergunta_pedido(cd_pergunta_pedido);

ALTER TABLE ONLY s_historico_execucao_rotina
    ADD CONSTRAINT fk_K_SCHEMA_213 FOREIGN KEY (dt_execucao_rotina, cd_rotina, cd_objeto, cd_profissional) REFERENCES s_execucao_rotina(dt_execucao_rotina, cd_rotina, cd_objeto, cd_profissional);

ALTER TABLE ONLY a_opcao_resp_pergunta_pedido
    ADD CONSTRAINT fk_K_SCHEMA_214 FOREIGN KEY (cd_resposta_pedido) REFERENCES b_resposta_pedido(cd_resposta_pedido);

ALTER TABLE ONLY a_pergunta_depende_resp_pedido
    ADD CONSTRAINT fk_K_SCHEMA_215 FOREIGN KEY (cd_pergunta_depende) REFERENCES b_pergunta_pedido(cd_pergunta_pedido);

ALTER TABLE ONLY a_pergunta_depende_resp_pedido
    ADD CONSTRAINT fk_K_SCHEMA_216 FOREIGN KEY (cd_pergunta_pedido, cd_resposta_pedido) REFERENCES a_opcao_resp_pergunta_pedido(cd_pergunta_pedido, cd_resposta_pedido);

ALTER TABLE ONLY a_contrato_evento
    ADD CONSTRAINT fk_K_SCHEMA_217 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY a_contrato_evento
    ADD CONSTRAINT fk_K_SCHEMA_218 FOREIGN KEY (cd_evento) REFERENCES b_evento(cd_evento);

ALTER TABLE ONLY b_item_grupo_fator
    ADD CONSTRAINT fk_K_SCHEMA_219 FOREIGN KEY (cd_grupo_fator) REFERENCES b_grupo_fator(cd_grupo_fator);

ALTER TABLE ONLY b_msg_email
    ADD CONSTRAINT fk_K_SCHEMA_220 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);

ALTER TABLE ONLY s_acompanhamento_proposta
    ADD CONSTRAINT fk_K_SCHEMA_221 FOREIGN KEY (cd_projeto, cd_proposta) REFERENCES s_proposta(cd_projeto, cd_proposta);

ALTER TABLE ONLY s_config_banco_de_dados
    ADD CONSTRAINT fk_K_SCHEMA_222 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);

ALTER TABLE ONLY s_log
    ADD CONSTRAINT fk_K_SCHEMA_223 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY s_ocorrencia_administrativa
    ADD CONSTRAINT fk_K_SCHEMA_224 FOREIGN KEY (cd_contrato, cd_evento) REFERENCES a_contrato_evento(cd_contrato, cd_evento);

ALTER TABLE ONLY s_pre_projeto
    ADD CONSTRAINT fk_K_SCHEMA_225 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);

ALTER TABLE ONLY s_pre_projeto
    ADD CONSTRAINT fk_K_SCHEMA_226 FOREIGN KEY (cd_gerente_pre_projeto) REFERENCES s_profissional(cd_profissional);

ALTER TABLE ONLY s_demanda
    ADD CONSTRAINT fk_K_SCHEMA_227 FOREIGN KEY (cd_status_atendimento) REFERENCES b_status_atendimento(cd_status_atendimento);

ALTER TABLE ONLY a_contrato_item_inventario
    ADD CONSTRAINT fk_K_SCHEMA_contrato_199 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);

ALTER TABLE ONLY a_contrato_item_inventario
    ADD CONSTRAINT fk_K_SCHEMA_item_inventario_198 FOREIGN KEY (cd_item_inventario) REFERENCES b_item_inventario(cd_item_inventario);

ALTER TABLE ONLY s_inventario
    ADD CONSTRAINT s_inventario_fk1 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);

