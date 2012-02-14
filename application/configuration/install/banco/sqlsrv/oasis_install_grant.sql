--
-- Sql Server Grantt
--

ALTER TABLE [K_SCHEMA].[b_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_090] FOREIGN KEY([cd_menu_pai])
REFERENCES [K_SCHEMA].[b_menu] ([cd_menu])

ALTER TABLE [K_SCHEMA].[b_menu] CHECK CONSTRAINT [fk_oasis_090]

ALTER TABLE [K_SCHEMA].[s_analise_medicao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_076] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[s_analise_medicao] CHECK CONSTRAINT [fk_oasis_076]

ALTER TABLE [K_SCHEMA].[s_analise_medicao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_077] FOREIGN KEY([cd_medicao])
REFERENCES [K_SCHEMA].[s_medicao] ([cd_medicao])

ALTER TABLE [K_SCHEMA].[s_analise_medicao] CHECK CONSTRAINT [fk_oasis_077]

ALTER TABLE [K_SCHEMA].[s_analise_medicao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_078] FOREIGN KEY([cd_box_inicio])
REFERENCES [K_SCHEMA].[b_box_inicio] ([cd_box_inicio])

ALTER TABLE [K_SCHEMA].[s_analise_medicao] CHECK CONSTRAINT [fk_oasis_078]

ALTER TABLE [K_SCHEMA].[a_perfil_box_inicio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_143] FOREIGN KEY([cd_perfil])
REFERENCES [K_SCHEMA].[b_perfil] ([cd_perfil])

ALTER TABLE [K_SCHEMA].[a_perfil_box_inicio] CHECK CONSTRAINT [fk_oasis_143]

ALTER TABLE [K_SCHEMA].[a_perfil_box_inicio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_144] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[a_perfil_box_inicio] CHECK CONSTRAINT [fk_oasis_144]

ALTER TABLE [K_SCHEMA].[a_perfil_box_inicio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_145] FOREIGN KEY([cd_box_inicio])
REFERENCES [K_SCHEMA].[b_box_inicio] ([cd_box_inicio])

ALTER TABLE [K_SCHEMA].[a_perfil_box_inicio] CHECK CONSTRAINT [fk_oasis_145]

ALTER TABLE [K_SCHEMA].[a_conjunto_medida_medicao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_203] FOREIGN KEY([cd_conjunto_medida])
REFERENCES [K_SCHEMA].[b_conjunto_medida] ([cd_conjunto_medida])

ALTER TABLE [K_SCHEMA].[a_conjunto_medida_medicao] CHECK CONSTRAINT [fk_oasis_203]

ALTER TABLE [K_SCHEMA].[a_profissional_produto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_122] FOREIGN KEY([cd_produto_parcela], [cd_proposta], [cd_projeto], [cd_parcela])
REFERENCES [K_SCHEMA].[s_produto_parcela] ([cd_produto_parcela], [cd_proposta], [cd_projeto], [cd_parcela])

ALTER TABLE [K_SCHEMA].[a_profissional_produto] CHECK CONSTRAINT [fk_oasis_122]

ALTER TABLE [K_SCHEMA].[a_profissional_produto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_123] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_profissional_produto] CHECK CONSTRAINT [fk_oasis_123]

ALTER TABLE [K_SCHEMA].[s_gerencia_qualidade]  WITH CHECK ADD  CONSTRAINT [fk_oasis_054] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_gerencia_qualidade] CHECK CONSTRAINT [fk_oasis_054]

ALTER TABLE [K_SCHEMA].[s_gerencia_qualidade]  WITH CHECK ADD  CONSTRAINT [fk_oasis_055] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[s_gerencia_qualidade] CHECK CONSTRAINT [fk_oasis_055]

ALTER TABLE [K_SCHEMA].[s_analise_risco]  WITH CHECK ADD  CONSTRAINT [fk_oasis_073] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_analise_risco] CHECK CONSTRAINT [fk_oasis_073]

ALTER TABLE [K_SCHEMA].[s_analise_risco]  WITH CHECK ADD  CONSTRAINT [fk_oasis_074] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[s_analise_risco] CHECK CONSTRAINT [fk_oasis_074]

ALTER TABLE [K_SCHEMA].[s_analise_risco]  WITH CHECK ADD  CONSTRAINT [fk_oasis_075] FOREIGN KEY([cd_item_risco], [cd_etapa], [cd_atividade])
REFERENCES [K_SCHEMA].[b_item_risco] ([cd_item_risco], [cd_etapa], [cd_atividade])

ALTER TABLE [K_SCHEMA].[s_analise_risco] CHECK CONSTRAINT [fk_oasis_075]

ALTER TABLE [K_SCHEMA].[s_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_032] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_parcela] CHECK CONSTRAINT [fk_oasis_032]

ALTER TABLE [K_SCHEMA].[a_quest_avaliacao_qualidade]  WITH CHECK ADD  CONSTRAINT [fk_oasis_111] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_quest_avaliacao_qualidade] CHECK CONSTRAINT [fk_oasis_111]

ALTER TABLE [K_SCHEMA].[a_quest_avaliacao_qualidade]  WITH CHECK ADD  CONSTRAINT [fk_oasis_112] FOREIGN KEY([cd_item_grupo_fator], [cd_grupo_fator])
REFERENCES [K_SCHEMA].[b_item_grupo_fator] ([cd_item_grupo_fator], [cd_grupo_fator])

ALTER TABLE [K_SCHEMA].[a_quest_avaliacao_qualidade] CHECK CONSTRAINT [fk_oasis_112]

ALTER TABLE [K_SCHEMA].[a_proposta_sub_item_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_113] FOREIGN KEY([cd_sub_item_metrica], [cd_definicao_metrica], [cd_item_metrica])
REFERENCES [K_SCHEMA].[b_sub_item_metrica] ([cd_sub_item_metrica], [cd_definicao_metrica], [cd_item_metrica])

ALTER TABLE [K_SCHEMA].[a_proposta_sub_item_metrica] CHECK CONSTRAINT [fk_oasis_113]

ALTER TABLE [K_SCHEMA].[a_proposta_sub_item_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_114] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_proposta_sub_item_metrica] CHECK CONSTRAINT [fk_oasis_114]

ALTER TABLE [K_SCHEMA].[a_proposta_modulo]  WITH CHECK ADD  CONSTRAINT [fk_oasis_115] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_proposta_modulo] CHECK CONSTRAINT [fk_oasis_115]

ALTER TABLE [K_SCHEMA].[a_proposta_modulo]  WITH CHECK ADD  CONSTRAINT [fk_oasis_116] FOREIGN KEY([cd_modulo], [cd_projeto])
REFERENCES [K_SCHEMA].[s_modulo] ([cd_modulo], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_proposta_modulo] CHECK CONSTRAINT [fk_oasis_116]

ALTER TABLE [K_SCHEMA].[a_proposta_definicao_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_117] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_proposta_definicao_metrica] CHECK CONSTRAINT [fk_oasis_117]

ALTER TABLE [K_SCHEMA].[a_proposta_definicao_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_118] FOREIGN KEY([cd_definicao_metrica])
REFERENCES [K_SCHEMA].[b_definicao_metrica] ([cd_definicao_metrica])

ALTER TABLE [K_SCHEMA].[a_proposta_definicao_metrica] CHECK CONSTRAINT [fk_oasis_118]

ALTER TABLE [K_SCHEMA].[s_processamento_proposta]  WITH CHECK ADD  CONSTRAINT [fk_oasis_023] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_processamento_proposta] CHECK CONSTRAINT [fk_oasis_023]

ALTER TABLE [K_SCHEMA].[s_historico_proposta]  WITH CHECK ADD  CONSTRAINT [fk_oasis_047] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_historico_proposta] CHECK CONSTRAINT [fk_oasis_047]

ALTER TABLE [K_SCHEMA].[s_plano_implantacao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_030] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_plano_implantacao] CHECK CONSTRAINT [fk_oasis_030]

ALTER TABLE [K_SCHEMA].[a_controle]  WITH CHECK ADD  CONSTRAINT [fk_oasis_197] FOREIGN KEY([cd_proposta], [cd_projeto])
REFERENCES [K_SCHEMA].[s_proposta] ([cd_proposta], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_controle] CHECK CONSTRAINT [fk_oasis_197]

ALTER TABLE [K_SCHEMA].[a_controle]  WITH CHECK ADD  CONSTRAINT [fk_oasis_198] FOREIGN KEY([cd_projeto_previsto], [cd_contrato])
REFERENCES [K_SCHEMA].[s_projeto_previsto] ([cd_projeto_previsto], [cd_contrato])

ALTER TABLE [K_SCHEMA].[a_controle] CHECK CONSTRAINT [fk_oasis_198]

ALTER TABLE [K_SCHEMA].[b_tipo_produto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_082] FOREIGN KEY([cd_definicao_metrica])
REFERENCES [K_SCHEMA].[b_definicao_metrica] ([cd_definicao_metrica])

ALTER TABLE [K_SCHEMA].[b_tipo_produto] CHECK CONSTRAINT [fk_oasis_082]

ALTER TABLE [K_SCHEMA].[a_contrato_definicao_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_201] FOREIGN KEY([cd_definicao_metrica])
REFERENCES [K_SCHEMA].[b_definicao_metrica] ([cd_definicao_metrica])

ALTER TABLE [K_SCHEMA].[a_contrato_definicao_metrica] CHECK CONSTRAINT [fk_oasis_201]

ALTER TABLE [K_SCHEMA].[a_contrato_definicao_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_202] FOREIGN KEY([cd_contrato])
REFERENCES [K_SCHEMA].[s_contrato] ([cd_contrato])

ALTER TABLE [K_SCHEMA].[a_contrato_definicao_metrica] CHECK CONSTRAINT [fk_oasis_202]

ALTER TABLE [K_SCHEMA].[b_item_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_092] FOREIGN KEY([cd_definicao_metrica])
REFERENCES [K_SCHEMA].[b_definicao_metrica] ([cd_definicao_metrica])

ALTER TABLE [K_SCHEMA].[b_item_metrica] CHECK CONSTRAINT [fk_oasis_092]

ALTER TABLE [K_SCHEMA].[a_solicitacao_resposta_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_211] FOREIGN KEY([cd_pergunta_pedido], [cd_resposta_pedido])
REFERENCES [K_SCHEMA].[a_opcao_resp_pergunta_pedido] ([cd_pergunta_pedido], [cd_resposta_pedido])

ALTER TABLE [K_SCHEMA].[a_solicitacao_resposta_pedido] CHECK CONSTRAINT [fk_oasis_211]

ALTER TABLE [K_SCHEMA].[a_solicitacao_resposta_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_212] FOREIGN KEY([cd_solicitacao_pedido])
REFERENCES [K_SCHEMA].[s_solicitacao_pedido] ([cd_solicitacao_pedido])

ALTER TABLE [K_SCHEMA].[a_solicitacao_resposta_pedido] CHECK CONSTRAINT [fk_oasis_212]

ALTER TABLE [K_SCHEMA].[a_pergunta_depende_resp_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_215] FOREIGN KEY([cd_pergunta_depende])
REFERENCES [K_SCHEMA].[b_pergunta_pedido] ([cd_pergunta_pedido])

ALTER TABLE [K_SCHEMA].[a_pergunta_depende_resp_pedido] CHECK CONSTRAINT [fk_oasis_215]

ALTER TABLE [K_SCHEMA].[a_pergunta_depende_resp_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_216] FOREIGN KEY([cd_pergunta_pedido], [cd_resposta_pedido])
REFERENCES [K_SCHEMA].[a_opcao_resp_pergunta_pedido] ([cd_pergunta_pedido], [cd_resposta_pedido])

ALTER TABLE [K_SCHEMA].[a_pergunta_depende_resp_pedido] CHECK CONSTRAINT [fk_oasis_216]

ALTER TABLE [K_SCHEMA].[a_funcionalidade_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_183] FOREIGN KEY([cd_menu])
REFERENCES [K_SCHEMA].[b_menu] ([cd_menu])

ALTER TABLE [K_SCHEMA].[a_funcionalidade_menu] CHECK CONSTRAINT [fk_oasis_183]

ALTER TABLE [K_SCHEMA].[a_funcionalidade_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_184] FOREIGN KEY([cd_funcionalidade])
REFERENCES [K_SCHEMA].[b_funcionalidade] ([cd_funcionalidade])

ALTER TABLE [K_SCHEMA].[a_funcionalidade_menu] CHECK CONSTRAINT [fk_oasis_184]

ALTER TABLE [K_SCHEMA].[a_definicao_processo]  WITH CHECK ADD  CONSTRAINT [fk_oasis_195] FOREIGN KEY([cd_perfil])
REFERENCES [K_SCHEMA].[b_perfil] ([cd_perfil])

ALTER TABLE [K_SCHEMA].[a_definicao_processo] CHECK CONSTRAINT [fk_oasis_195]

ALTER TABLE [K_SCHEMA].[a_definicao_processo]  WITH CHECK ADD  CONSTRAINT [fk_oasis_196] FOREIGN KEY([cd_funcionalidade])
REFERENCES [K_SCHEMA].[b_funcionalidade] ([cd_funcionalidade])

ALTER TABLE [K_SCHEMA].[a_definicao_processo] CHECK CONSTRAINT [fk_oasis_196]

ALTER TABLE [K_SCHEMA].[a_baseline_item_controle]  WITH CHECK ADD  CONSTRAINT [fk_oasis_206] FOREIGN KEY([dt_baseline], [cd_projeto])
REFERENCES [K_SCHEMA].[s_baseline] ([dt_baseline], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_baseline_item_controle] CHECK CONSTRAINT [fk_oasis_206]

ALTER TABLE [K_SCHEMA].[a_baseline_item_controle]  WITH CHECK ADD  CONSTRAINT [fk_oasis_207] FOREIGN KEY([cd_item_controle_baseline])
REFERENCES [K_SCHEMA].[b_item_controle_baseline] ([cd_item_controle_baseline])

ALTER TABLE [K_SCHEMA].[a_baseline_item_controle] CHECK CONSTRAINT [fk_oasis_207]

ALTER TABLE [K_SCHEMA].[a_gerencia_mudanca]  WITH CHECK ADD  CONSTRAINT [fk_oasis_180] FOREIGN KEY([cd_reuniao], [cd_projeto_reuniao])
REFERENCES [K_SCHEMA].[s_reuniao] ([cd_reuniao], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_gerencia_mudanca] CHECK CONSTRAINT [fk_oasis_180]

ALTER TABLE [K_SCHEMA].[a_gerencia_mudanca]  WITH CHECK ADD  CONSTRAINT [fk_oasis_181] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[a_gerencia_mudanca] CHECK CONSTRAINT [fk_oasis_181]

ALTER TABLE [K_SCHEMA].[a_gerencia_mudanca]  WITH CHECK ADD  CONSTRAINT [fk_oasis_182] FOREIGN KEY([cd_item_controle_baseline])
REFERENCES [K_SCHEMA].[b_item_controle_baseline] ([cd_item_controle_baseline])

ALTER TABLE [K_SCHEMA].[a_gerencia_mudanca] CHECK CONSTRAINT [fk_oasis_182]

ALTER TABLE [K_SCHEMA].[b_penalidade]  WITH CHECK ADD  CONSTRAINT [fk_oasis_086] FOREIGN KEY([cd_contrato])
REFERENCES [K_SCHEMA].[s_contrato] ([cd_contrato])

ALTER TABLE [K_SCHEMA].[b_penalidade] CHECK CONSTRAINT [fk_oasis_086]

ALTER TABLE [K_SCHEMA].[s_extrato_mensal]  WITH CHECK ADD  CONSTRAINT [fk_oasis_058] FOREIGN KEY([cd_contrato])
REFERENCES [K_SCHEMA].[s_contrato] ([cd_contrato])

ALTER TABLE [K_SCHEMA].[s_extrato_mensal] CHECK CONSTRAINT [fk_oasis_058]

ALTER TABLE [K_SCHEMA].[s_projeto_previsto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_012] FOREIGN KEY([cd_unidade])
REFERENCES [K_SCHEMA].[b_unidade] ([cd_unidade])

ALTER TABLE [K_SCHEMA].[s_projeto_previsto] CHECK CONSTRAINT [fk_oasis_012]

ALTER TABLE [K_SCHEMA].[s_projeto_previsto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_013] FOREIGN KEY([cd_contrato])
REFERENCES [K_SCHEMA].[s_contrato] ([cd_contrato])

ALTER TABLE [K_SCHEMA].[s_projeto_previsto] CHECK CONSTRAINT [fk_oasis_013]

ALTER TABLE [K_SCHEMA].[s_objeto_contrato]  WITH CHECK ADD  CONSTRAINT [fk_oasis_033] FOREIGN KEY([cd_contrato])
REFERENCES [K_SCHEMA].[s_contrato] ([cd_contrato])

ALTER TABLE [K_SCHEMA].[s_objeto_contrato] CHECK CONSTRAINT [fk_oasis_033]

ALTER TABLE [K_SCHEMA].[a_contrato_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_199] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[a_contrato_projeto] CHECK CONSTRAINT [fk_oasis_199]

ALTER TABLE [K_SCHEMA].[a_contrato_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_200] FOREIGN KEY([cd_contrato])
REFERENCES [K_SCHEMA].[s_contrato] ([cd_contrato])

ALTER TABLE [K_SCHEMA].[a_contrato_projeto] CHECK CONSTRAINT [fk_oasis_200]

ALTER TABLE [K_SCHEMA].[s_arquivo_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_217] FOREIGN KEY([cd_solicitacao_pedido], [cd_resposta_pedido], [cd_pergunta_pedido])
REFERENCES [K_SCHEMA].[a_solicitacao_resposta_pedido] ([cd_solicitacao_pedido], [cd_pergunta_pedido], [cd_resposta_pedido])

ALTER TABLE [K_SCHEMA].[s_arquivo_pedido] CHECK CONSTRAINT [fk_oasis_217]

ALTER TABLE [K_SCHEMA].[s_contrato]  WITH CHECK ADD  CONSTRAINT [fk_oasis_062] FOREIGN KEY([cd_empresa])
REFERENCES [K_SCHEMA].[s_empresa] ([cd_empresa])

ALTER TABLE [K_SCHEMA].[s_contrato] CHECK CONSTRAINT [fk_oasis_062]

ALTER TABLE [K_SCHEMA].[s_contrato]  WITH CHECK ADD  CONSTRAINT [fk_oasis_063] FOREIGN KEY([cd_contato_empresa], [cd_empresa])
REFERENCES [K_SCHEMA].[s_contato_empresa] ([cd_contato_empresa], [cd_empresa])

ALTER TABLE [K_SCHEMA].[s_contrato] CHECK CONSTRAINT [fk_oasis_063]

ALTER TABLE [K_SCHEMA].[s_coluna]  WITH CHECK ADD  CONSTRAINT [fk_oasis_067] FOREIGN KEY([tx_tabela_referencia], [cd_projeto_referencia])
REFERENCES [K_SCHEMA].[s_tabela] ([tx_tabela], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_coluna] CHECK CONSTRAINT [fk_oasis_067]

ALTER TABLE [K_SCHEMA].[s_coluna]  WITH CHECK ADD  CONSTRAINT [fk_oasis_068] FOREIGN KEY([tx_tabela], [cd_projeto])
REFERENCES [K_SCHEMA].[s_tabela] ([tx_tabela], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_coluna] CHECK CONSTRAINT [fk_oasis_068]

ALTER TABLE [K_SCHEMA].[a_parecer_tecnico_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_147] FOREIGN KEY([cd_processamento_parcela], [cd_proposta], [cd_projeto], [cd_parcela])
REFERENCES [K_SCHEMA].[s_processamento_parcela] ([cd_processamento_parcela], [cd_proposta], [cd_projeto], [cd_parcela])

ALTER TABLE [K_SCHEMA].[a_parecer_tecnico_parcela] CHECK CONSTRAINT [fk_oasis_147]

ALTER TABLE [K_SCHEMA].[a_parecer_tecnico_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_148] FOREIGN KEY([cd_item_parecer_tecnico])
REFERENCES [K_SCHEMA].[b_item_parecer_tecnico] ([cd_item_parecer_tecnico])

ALTER TABLE [K_SCHEMA].[a_parecer_tecnico_parcela] CHECK CONSTRAINT [fk_oasis_148]

ALTER TABLE [K_SCHEMA].[a_parecer_tecnico_proposta]  WITH CHECK ADD  CONSTRAINT [fk_oasis_146] FOREIGN KEY([cd_item_parecer_tecnico])
REFERENCES [K_SCHEMA].[b_item_parecer_tecnico] ([cd_item_parecer_tecnico])

ALTER TABLE [K_SCHEMA].[a_parecer_tecnico_proposta] CHECK CONSTRAINT [fk_oasis_146]

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso]  WITH CHECK ADD  CONSTRAINT [fk_oasis_173] FOREIGN KEY([cd_profissional_solucao])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso] CHECK CONSTRAINT [fk_oasis_173]

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso]  WITH CHECK ADD  CONSTRAINT [fk_oasis_174] FOREIGN KEY([cd_profissional_homologacao])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso] CHECK CONSTRAINT [fk_oasis_174]

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso]  WITH CHECK ADD  CONSTRAINT [fk_oasis_175] FOREIGN KEY([cd_profissional_analise])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso] CHECK CONSTRAINT [fk_oasis_175]

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso]  WITH CHECK ADD  CONSTRAINT [fk_oasis_176] FOREIGN KEY([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])
REFERENCES [K_SCHEMA].[s_caso_de_uso] ([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso] CHECK CONSTRAINT [fk_oasis_176]

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso]  WITH CHECK ADD  CONSTRAINT [fk_oasis_177] FOREIGN KEY([cd_item_teste])
REFERENCES [K_SCHEMA].[b_item_teste] ([cd_item_teste])

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso] CHECK CONSTRAINT [fk_oasis_177]

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_166] FOREIGN KEY([cd_profissional_solucao])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio] CHECK CONSTRAINT [fk_oasis_166]

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_167] FOREIGN KEY([cd_profissional_homologacao])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio] CHECK CONSTRAINT [fk_oasis_167]

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_168] FOREIGN KEY([cd_profissional_analise])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio] CHECK CONSTRAINT [fk_oasis_168]

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_169] FOREIGN KEY([cd_item_teste])
REFERENCES [K_SCHEMA].[b_item_teste] ([cd_item_teste])

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio] CHECK CONSTRAINT [fk_oasis_169]

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_170] FOREIGN KEY([cd_regra_negocio], [dt_regra_negocio], [cd_projeto_regra_negocio])
REFERENCES [K_SCHEMA].[s_regra_negocio] ([cd_regra_negocio], [dt_regra_negocio], [cd_projeto_regra_negocio])

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio] CHECK CONSTRAINT [fk_oasis_170]

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito]  WITH CHECK ADD  CONSTRAINT [fk_oasis_159] FOREIGN KEY([cd_requisito], [dt_versao_requisito], [cd_projeto])
REFERENCES [K_SCHEMA].[s_requisito] ([cd_requisito], [dt_versao_requisito], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito] CHECK CONSTRAINT [fk_oasis_159]

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito]  WITH CHECK ADD  CONSTRAINT [fk_oasis_160] FOREIGN KEY([cd_profissional_solucao])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito] CHECK CONSTRAINT [fk_oasis_160]

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito]  WITH CHECK ADD  CONSTRAINT [fk_oasis_161] FOREIGN KEY([cd_profissional_homologacao])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito] CHECK CONSTRAINT [fk_oasis_161]

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito]  WITH CHECK ADD  CONSTRAINT [fk_oasis_162] FOREIGN KEY([cd_profissional_analise])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito] CHECK CONSTRAINT [fk_oasis_162]

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito]  WITH CHECK ADD  CONSTRAINT [fk_oasis_163] FOREIGN KEY([cd_item_teste])
REFERENCES [K_SCHEMA].[b_item_teste] ([cd_item_teste])

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito] CHECK CONSTRAINT [fk_oasis_163]

ALTER TABLE [K_SCHEMA].[a_requisito_caso_de_uso]  WITH CHECK ADD  CONSTRAINT [fk_oasis_104] FOREIGN KEY([cd_requisito], [dt_versao_requisito], [cd_projeto])
REFERENCES [K_SCHEMA].[s_requisito] ([cd_requisito], [dt_versao_requisito], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_requisito_caso_de_uso] CHECK CONSTRAINT [fk_oasis_104]

ALTER TABLE [K_SCHEMA].[a_requisito_caso_de_uso]  WITH CHECK ADD  CONSTRAINT [fk_oasis_105] FOREIGN KEY([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])
REFERENCES [K_SCHEMA].[s_caso_de_uso] ([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])

ALTER TABLE [K_SCHEMA].[a_requisito_caso_de_uso] CHECK CONSTRAINT [fk_oasis_105]

ALTER TABLE [K_SCHEMA].[s_complemento]  WITH CHECK ADD  CONSTRAINT [fk_oasis_066] FOREIGN KEY([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])
REFERENCES [K_SCHEMA].[s_caso_de_uso] ([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])

ALTER TABLE [K_SCHEMA].[s_complemento] CHECK CONSTRAINT [fk_oasis_066]

ALTER TABLE [K_SCHEMA].[s_condicao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_065] FOREIGN KEY([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])
REFERENCES [K_SCHEMA].[s_caso_de_uso] ([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])

ALTER TABLE [K_SCHEMA].[s_condicao] CHECK CONSTRAINT [fk_oasis_065]

ALTER TABLE [K_SCHEMA].[s_interacao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_039] FOREIGN KEY([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])
REFERENCES [K_SCHEMA].[s_caso_de_uso] ([cd_caso_de_uso], [cd_projeto], [cd_modulo], [dt_versao_caso_de_uso])

ALTER TABLE [K_SCHEMA].[s_interacao] CHECK CONSTRAINT [fk_oasis_039]

ALTER TABLE [K_SCHEMA].[s_interacao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_040] FOREIGN KEY([cd_ator])
REFERENCES [K_SCHEMA].[s_ator] ([cd_ator])

ALTER TABLE [K_SCHEMA].[s_interacao] CHECK CONSTRAINT [fk_oasis_040]

ALTER TABLE [K_SCHEMA].[s_hw_inventario]  WITH CHECK ADD  CONSTRAINT [fk_oasis_041] FOREIGN KEY([cd_modulo_continuado], [cd_objeto], [cd_projeto_continuado])
REFERENCES [K_SCHEMA].[s_modulo_continuado] ([cd_modulo_continuado], [cd_objeto], [cd_projeto_continuado])

ALTER TABLE [K_SCHEMA].[s_hw_inventario] CHECK CONSTRAINT [fk_oasis_041]

ALTER TABLE [K_SCHEMA].[s_historico_projeto_continuado]  WITH CHECK ADD  CONSTRAINT [fk_oasis_048] FOREIGN KEY([cd_modulo_continuado], [cd_objeto], [cd_projeto_continuado])
REFERENCES [K_SCHEMA].[s_modulo_continuado] ([cd_modulo_continuado], [cd_objeto], [cd_projeto_continuado])

ALTER TABLE [K_SCHEMA].[s_historico_projeto_continuado] CHECK CONSTRAINT [fk_oasis_048]

ALTER TABLE [K_SCHEMA].[s_historico_projeto_continuado]  WITH CHECK ADD  CONSTRAINT [fk_oasis_049] FOREIGN KEY([cd_atividade], [cd_etapa])
REFERENCES [K_SCHEMA].[b_atividade] ([cd_atividade], [cd_etapa])

ALTER TABLE [K_SCHEMA].[s_historico_projeto_continuado] CHECK CONSTRAINT [fk_oasis_049]

ALTER TABLE [K_SCHEMA].[a_medicao_medida]  WITH CHECK ADD  CONSTRAINT [fk_oasis_155] FOREIGN KEY([cd_medida])
REFERENCES [K_SCHEMA].[b_medida] ([cd_medida])

ALTER TABLE [K_SCHEMA].[a_medicao_medida] CHECK CONSTRAINT [fk_oasis_155]

ALTER TABLE [K_SCHEMA].[a_medicao_medida]  WITH CHECK ADD  CONSTRAINT [fk_oasis_156] FOREIGN KEY([cd_medicao])
REFERENCES [K_SCHEMA].[s_medicao] ([cd_medicao])

ALTER TABLE [K_SCHEMA].[a_medicao_medida] CHECK CONSTRAINT [fk_oasis_156]

ALTER TABLE [K_SCHEMA].[a_profissional_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_127] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[a_profissional_menu] CHECK CONSTRAINT [fk_oasis_127]

ALTER TABLE [K_SCHEMA].[a_profissional_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_128] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_profissional_menu] CHECK CONSTRAINT [fk_oasis_128]

ALTER TABLE [K_SCHEMA].[a_profissional_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_129] FOREIGN KEY([cd_menu])
REFERENCES [K_SCHEMA].[b_menu] ([cd_menu])

ALTER TABLE [K_SCHEMA].[a_profissional_menu] CHECK CONSTRAINT [fk_oasis_129]

ALTER TABLE [K_SCHEMA].[a_perfil_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_140] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[a_perfil_menu] CHECK CONSTRAINT [fk_oasis_140]

ALTER TABLE [K_SCHEMA].[a_perfil_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_141] FOREIGN KEY([cd_perfil])
REFERENCES [K_SCHEMA].[b_perfil] ([cd_perfil])

ALTER TABLE [K_SCHEMA].[a_perfil_menu] CHECK CONSTRAINT [fk_oasis_141]

ALTER TABLE [K_SCHEMA].[a_perfil_menu]  WITH CHECK ADD  CONSTRAINT [fk_oasis_142] FOREIGN KEY([cd_menu])
REFERENCES [K_SCHEMA].[b_menu] ([cd_menu])

ALTER TABLE [K_SCHEMA].[a_perfil_menu] CHECK CONSTRAINT [fk_oasis_142]

ALTER TABLE [K_SCHEMA].[a_perfil_menu_sistema]  WITH CHECK ADD  CONSTRAINT [fk_oasis_138] FOREIGN KEY([cd_perfil])
REFERENCES [K_SCHEMA].[b_perfil] ([cd_perfil])

ALTER TABLE [K_SCHEMA].[a_perfil_menu_sistema] CHECK CONSTRAINT [fk_oasis_138]

ALTER TABLE [K_SCHEMA].[a_perfil_menu_sistema]  WITH CHECK ADD  CONSTRAINT [fk_oasis_139] FOREIGN KEY([cd_menu])
REFERENCES [K_SCHEMA].[b_menu] ([cd_menu])

ALTER TABLE [K_SCHEMA].[a_perfil_menu_sistema] CHECK CONSTRAINT [fk_oasis_139]

ALTER TABLE [K_SCHEMA].[b_msg_email]  WITH CHECK ADD  CONSTRAINT [fk_oasis_089] FOREIGN KEY([cd_menu])
REFERENCES [K_SCHEMA].[b_menu] ([cd_menu])

ALTER TABLE [K_SCHEMA].[b_msg_email] CHECK CONSTRAINT [fk_oasis_089]

ALTER TABLE [K_SCHEMA].[a_disponibilidade_servico_doc]  WITH CHECK ADD  CONSTRAINT [fk_oasis_189] FOREIGN KEY([cd_disponibilidade_servico], [cd_objeto])
REFERENCES [K_SCHEMA].[s_disponibilidade_servico] ([cd_disponibilidade_servico], [cd_objeto])

ALTER TABLE [K_SCHEMA].[a_disponibilidade_servico_doc] CHECK CONSTRAINT [fk_oasis_189]

ALTER TABLE [K_SCHEMA].[a_disponibilidade_servico_doc]  WITH CHECK ADD  CONSTRAINT [fk_oasis_190] FOREIGN KEY([cd_tipo_documentacao])
REFERENCES [K_SCHEMA].[b_tipo_documentacao] ([cd_tipo_documentacao])

ALTER TABLE [K_SCHEMA].[a_disponibilidade_servico_doc] CHECK CONSTRAINT [fk_oasis_190]

ALTER TABLE [K_SCHEMA].[s_agenda_plano_implantacao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_081] FOREIGN KEY([cd_projeto], [cd_proposta])
REFERENCES [K_SCHEMA].[s_plano_implantacao] ([cd_projeto], [cd_proposta])

ALTER TABLE [K_SCHEMA].[s_agenda_plano_implantacao] CHECK CONSTRAINT [fk_oasis_081]

ALTER TABLE [K_SCHEMA].[s_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_018] FOREIGN KEY([cd_relacao_contratual])
REFERENCES [K_SCHEMA].[b_relacao_contratual] ([cd_relacao_contratual])

ALTER TABLE [K_SCHEMA].[s_profissional] CHECK CONSTRAINT [fk_oasis_018]

ALTER TABLE [K_SCHEMA].[s_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_019] FOREIGN KEY([cd_perfil])
REFERENCES [K_SCHEMA].[b_perfil] ([cd_perfil])

ALTER TABLE [K_SCHEMA].[s_profissional] CHECK CONSTRAINT [fk_oasis_019]

ALTER TABLE [K_SCHEMA].[s_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_020] FOREIGN KEY([cd_empresa])
REFERENCES [K_SCHEMA].[s_empresa] ([cd_empresa])

ALTER TABLE [K_SCHEMA].[s_profissional] CHECK CONSTRAINT [fk_oasis_020]

ALTER TABLE [K_SCHEMA].[s_mensageria]  WITH CHECK ADD  CONSTRAINT [fk_oasis_037] FOREIGN KEY([cd_perfil])
REFERENCES [K_SCHEMA].[b_perfil] ([cd_perfil])

ALTER TABLE [K_SCHEMA].[s_mensageria] CHECK CONSTRAINT [fk_oasis_037]

ALTER TABLE [K_SCHEMA].[s_mensageria]  WITH CHECK ADD  CONSTRAINT [fk_oasis_038] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[s_mensageria] CHECK CONSTRAINT [fk_oasis_038]

ALTER TABLE [K_SCHEMA].[a_demanda_prof_nivel_servico]  WITH CHECK ADD  CONSTRAINT [fk_oasis_193] FOREIGN KEY([cd_nivel_servico])
REFERENCES [K_SCHEMA].[b_nivel_servico] ([cd_nivel_servico])

ALTER TABLE [K_SCHEMA].[a_demanda_prof_nivel_servico] CHECK CONSTRAINT [fk_oasis_193]

ALTER TABLE [K_SCHEMA].[a_demanda_prof_nivel_servico]  WITH CHECK ADD  CONSTRAINT [fk_oasis_194] FOREIGN KEY([cd_profissional], [cd_demanda])
REFERENCES [K_SCHEMA].[a_demanda_profissional] ([cd_profissional], [cd_demanda])

ALTER TABLE [K_SCHEMA].[a_demanda_prof_nivel_servico] CHECK CONSTRAINT [fk_oasis_194]

ALTER TABLE [K_SCHEMA].[b_conhecimento]  WITH CHECK ADD  CONSTRAINT [fk_oasis_095] FOREIGN KEY([cd_tipo_conhecimento])
REFERENCES [K_SCHEMA].[b_tipo_conhecimento] ([cd_tipo_conhecimento])

ALTER TABLE [K_SCHEMA].[b_conhecimento] CHECK CONSTRAINT [fk_oasis_095]

ALTER TABLE [K_SCHEMA].[s_modulo_continuado]  WITH CHECK ADD  CONSTRAINT [fk_oasis_034] FOREIGN KEY([cd_projeto_continuado], [cd_objeto])
REFERENCES [K_SCHEMA].[s_projeto_continuado] ([cd_projeto_continuado], [cd_objeto])

ALTER TABLE [K_SCHEMA].[s_modulo_continuado] CHECK CONSTRAINT [fk_oasis_034]

ALTER TABLE [K_SCHEMA].[s_historico_proposta_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_045] FOREIGN KEY([dt_historico_proposta], [cd_projeto], [cd_proposta])
REFERENCES [K_SCHEMA].[s_historico_proposta] ([dt_historico_proposta], [cd_projeto], [cd_proposta])

ALTER TABLE [K_SCHEMA].[s_historico_proposta_metrica] CHECK CONSTRAINT [fk_oasis_045]

ALTER TABLE [K_SCHEMA].[s_historico_proposta_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_046] FOREIGN KEY([cd_projeto], [cd_proposta], [cd_definicao_metrica])
REFERENCES [K_SCHEMA].[a_proposta_definicao_metrica] ([cd_projeto], [cd_proposta], [cd_definicao_metrica])

ALTER TABLE [K_SCHEMA].[s_historico_proposta_metrica] CHECK CONSTRAINT [fk_oasis_046]

ALTER TABLE [K_SCHEMA].[s_hist_prop_sub_item_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_052] FOREIGN KEY([cd_projeto], [cd_proposta], [cd_item_metrica], [cd_definicao_metrica], [cd_sub_item_metrica])
REFERENCES [K_SCHEMA].[a_proposta_sub_item_metrica] ([cd_projeto], [cd_proposta], [cd_item_metrica], [cd_definicao_metrica], [cd_sub_item_metrica])

ALTER TABLE [K_SCHEMA].[s_hist_prop_sub_item_metrica] CHECK CONSTRAINT [fk_oasis_052]

ALTER TABLE [K_SCHEMA].[s_hist_prop_sub_item_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_053] FOREIGN KEY([dt_historico_proposta], [cd_projeto], [cd_proposta])
REFERENCES [K_SCHEMA].[s_historico_proposta] ([dt_historico_proposta], [cd_projeto], [cd_proposta])

ALTER TABLE [K_SCHEMA].[s_hist_prop_sub_item_metrica] CHECK CONSTRAINT [fk_oasis_053]

ALTER TABLE [K_SCHEMA].[s_historico_proposta_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_044] FOREIGN KEY([dt_historico_proposta], [cd_projeto], [cd_proposta])
REFERENCES [K_SCHEMA].[s_historico_proposta] ([dt_historico_proposta], [cd_projeto], [cd_proposta])

ALTER TABLE [K_SCHEMA].[s_historico_proposta_parcela] CHECK CONSTRAINT [fk_oasis_044]

ALTER TABLE [K_SCHEMA].[a_informacao_tecnica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_178] FOREIGN KEY([cd_tipo_dado_tecnico])
REFERENCES [K_SCHEMA].[b_tipo_dado_tecnico] ([cd_tipo_dado_tecnico])

ALTER TABLE [K_SCHEMA].[a_informacao_tecnica] CHECK CONSTRAINT [fk_oasis_178]

ALTER TABLE [K_SCHEMA].[a_informacao_tecnica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_179] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[a_informacao_tecnica] CHECK CONSTRAINT [fk_oasis_179]

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito_doc]  WITH CHECK ADD  CONSTRAINT [fk_oasis_157] FOREIGN KEY([cd_tipo_documentacao])
REFERENCES [K_SCHEMA].[b_tipo_documentacao] ([cd_tipo_documentacao])

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito_doc] CHECK CONSTRAINT [fk_oasis_157]

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito_doc]  WITH CHECK ADD  CONSTRAINT [fk_oasis_158] FOREIGN KEY([cd_item_teste_requisito], [cd_requisito], [dt_versao_requisito], [cd_projeto], [cd_item_teste])
REFERENCES [K_SCHEMA].[a_item_teste_requisito] ([cd_item_teste_requisito], [cd_requisito], [dt_versao_requisito], [cd_projeto], [cd_item_teste])

ALTER TABLE [K_SCHEMA].[a_item_teste_requisito_doc] CHECK CONSTRAINT [fk_oasis_158]

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio_doc]  WITH CHECK ADD  CONSTRAINT [fk_oasis_164] FOREIGN KEY([dt_regra_negocio], [cd_regra_negocio], [cd_item_teste], [cd_projeto_regra_negocio], [cd_item_teste_regra_negocio])
REFERENCES [K_SCHEMA].[a_item_teste_regra_negocio] ([dt_regra_negocio], [cd_regra_negocio], [cd_item_teste], [cd_projeto_regra_negocio], [cd_item_teste_regra_negocio])

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio_doc] CHECK CONSTRAINT [fk_oasis_164]

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio_doc]  WITH CHECK ADD  CONSTRAINT [fk_oasis_165] FOREIGN KEY([cd_tipo_documentacao])
REFERENCES [K_SCHEMA].[b_tipo_documentacao] ([cd_tipo_documentacao])

ALTER TABLE [K_SCHEMA].[a_item_teste_regra_negocio_doc] CHECK CONSTRAINT [fk_oasis_165]

ALTER TABLE [K_SCHEMA].[a_documentacao_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_187] FOREIGN KEY([cd_tipo_documentacao])
REFERENCES [K_SCHEMA].[b_tipo_documentacao] ([cd_tipo_documentacao])

ALTER TABLE [K_SCHEMA].[a_documentacao_profissional] CHECK CONSTRAINT [fk_oasis_187]

ALTER TABLE [K_SCHEMA].[a_documentacao_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_188] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_documentacao_profissional] CHECK CONSTRAINT [fk_oasis_188]

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso_doc]  WITH CHECK ADD  CONSTRAINT [fk_oasis_171] FOREIGN KEY([cd_tipo_documentacao])
REFERENCES [K_SCHEMA].[b_tipo_documentacao] ([cd_tipo_documentacao])

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso_doc] CHECK CONSTRAINT [fk_oasis_171]

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso_doc]  WITH CHECK ADD  CONSTRAINT [fk_oasis_172] FOREIGN KEY([cd_item_teste], [cd_modulo], [cd_projeto], [cd_caso_de_uso], [dt_versao_caso_de_uso], [cd_item_teste_caso_de_uso])
REFERENCES [K_SCHEMA].[a_item_teste_caso_de_uso] ([cd_item_teste], [cd_modulo], [cd_projeto], [cd_caso_de_uso], [dt_versao_caso_de_uso], [cd_item_teste_caso_de_uso])

ALTER TABLE [K_SCHEMA].[a_item_teste_caso_de_uso_doc] CHECK CONSTRAINT [fk_oasis_172]

ALTER TABLE [K_SCHEMA].[a_documentacao_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_185] FOREIGN KEY([cd_tipo_documentacao])
REFERENCES [K_SCHEMA].[b_tipo_documentacao] ([cd_tipo_documentacao])

ALTER TABLE [K_SCHEMA].[a_documentacao_projeto] CHECK CONSTRAINT [fk_oasis_185]

ALTER TABLE [K_SCHEMA].[a_documentacao_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_186] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[a_documentacao_projeto] CHECK CONSTRAINT [fk_oasis_186]

ALTER TABLE [K_SCHEMA].[b_item_inventario]  WITH CHECK ADD  CONSTRAINT [fk_oasis_093] FOREIGN KEY([cd_tipo_inventario])
REFERENCES [K_SCHEMA].[b_tipo_inventario] ([cd_tipo_inventario])

ALTER TABLE [K_SCHEMA].[b_item_inventario] CHECK CONSTRAINT [fk_oasis_093]

ALTER TABLE [K_SCHEMA].[a_treinamento_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_098] FOREIGN KEY([cd_treinamento])
REFERENCES [K_SCHEMA].[b_treinamento] ([cd_treinamento])

ALTER TABLE [K_SCHEMA].[a_treinamento_profissional] CHECK CONSTRAINT [fk_oasis_098]

ALTER TABLE [K_SCHEMA].[a_treinamento_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_099] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_treinamento_profissional] CHECK CONSTRAINT [fk_oasis_099]

ALTER TABLE [K_SCHEMA].[s_solicitacao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_003] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[s_solicitacao] CHECK CONSTRAINT [fk_oasis_003]

ALTER TABLE [K_SCHEMA].[s_solicitacao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_004] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[s_solicitacao] CHECK CONSTRAINT [fk_oasis_004]

ALTER TABLE [K_SCHEMA].[s_solicitacao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_005] FOREIGN KEY([cd_unidade])
REFERENCES [K_SCHEMA].[b_unidade] ([cd_unidade])

ALTER TABLE [K_SCHEMA].[s_solicitacao] CHECK CONSTRAINT [fk_oasis_005]

ALTER TABLE [K_SCHEMA].[s_pre_demanda]  WITH CHECK ADD  CONSTRAINT [fk_oasis_027] FOREIGN KEY([ni_solicitacao], [ni_ano_solicitacao], [cd_objeto_receptor])
REFERENCES [K_SCHEMA].[s_solicitacao] ([ni_solicitacao], [ni_ano_solicitacao], [cd_objeto])

ALTER TABLE [K_SCHEMA].[s_pre_demanda] CHECK CONSTRAINT [fk_oasis_027]

ALTER TABLE [K_SCHEMA].[s_pre_demanda]  WITH CHECK ADD  CONSTRAINT [fk_oasis_028] FOREIGN KEY([cd_objeto_emissor])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[s_pre_demanda] CHECK CONSTRAINT [fk_oasis_028]

ALTER TABLE [K_SCHEMA].[s_pre_demanda]  WITH CHECK ADD  CONSTRAINT [fk_oasis_029] FOREIGN KEY([cd_unidade])
REFERENCES [K_SCHEMA].[b_unidade] ([cd_unidade])

ALTER TABLE [K_SCHEMA].[s_pre_demanda] CHECK CONSTRAINT [fk_oasis_029]

ALTER TABLE [K_SCHEMA].[s_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_015] FOREIGN KEY([cd_unidade])
REFERENCES [K_SCHEMA].[b_unidade] ([cd_unidade])

ALTER TABLE [K_SCHEMA].[s_projeto] CHECK CONSTRAINT [fk_oasis_015]

ALTER TABLE [K_SCHEMA].[s_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_016] FOREIGN KEY([cd_status])
REFERENCES [K_SCHEMA].[b_status] ([cd_status])

ALTER TABLE [K_SCHEMA].[s_projeto] CHECK CONSTRAINT [fk_oasis_016]

ALTER TABLE [K_SCHEMA].[s_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_017] FOREIGN KEY([cd_profissional_gerente])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[s_projeto] CHECK CONSTRAINT [fk_oasis_017]

ALTER TABLE [K_SCHEMA].[s_demanda]  WITH CHECK ADD  CONSTRAINT [fk_oasis_060] FOREIGN KEY([ni_solicitacao], [ni_ano_solicitacao], [cd_objeto])
REFERENCES [K_SCHEMA].[s_solicitacao] ([ni_solicitacao], [ni_ano_solicitacao], [cd_objeto])

ALTER TABLE [K_SCHEMA].[s_demanda] CHECK CONSTRAINT [fk_oasis_060]

ALTER TABLE [K_SCHEMA].[s_demanda]  WITH CHECK ADD  CONSTRAINT [fk_oasis_061] FOREIGN KEY([cd_unidade])
REFERENCES [K_SCHEMA].[b_unidade] ([cd_unidade])

ALTER TABLE [K_SCHEMA].[s_demanda] CHECK CONSTRAINT [fk_oasis_061]

ALTER TABLE [K_SCHEMA].[s_solicitacao_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_208] FOREIGN KEY([cd_unidade_pedido])
REFERENCES [K_SCHEMA].[b_unidade] ([cd_unidade])

ALTER TABLE [K_SCHEMA].[s_solicitacao_pedido] CHECK CONSTRAINT [fk_oasis_208]

ALTER TABLE [K_SCHEMA].[s_solicitacao_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_209] FOREIGN KEY([cd_usuario_pedido])
REFERENCES [K_SCHEMA].[s_usuario_pedido] ([cd_usuario_pedido])

ALTER TABLE [K_SCHEMA].[s_solicitacao_pedido] CHECK CONSTRAINT [fk_oasis_209]

ALTER TABLE [K_SCHEMA].[s_usuario_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_001] FOREIGN KEY([cd_unidade_usuario])
REFERENCES [K_SCHEMA].[b_unidade] ([cd_unidade])

ALTER TABLE [K_SCHEMA].[s_usuario_pedido] CHECK CONSTRAINT [fk_oasis_001]

ALTER TABLE [K_SCHEMA].[s_extrato_mensal_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_056] FOREIGN KEY([ni_mes_extrato], [ni_ano_extrato], [cd_contrato])
REFERENCES [K_SCHEMA].[s_extrato_mensal] ([ni_mes_extrato], [ni_ano_extrato], [cd_contrato])

ALTER TABLE [K_SCHEMA].[s_extrato_mensal_parcela] CHECK CONSTRAINT [fk_oasis_056]

ALTER TABLE [K_SCHEMA].[s_extrato_mensal_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_057] FOREIGN KEY([cd_parcela], [cd_projeto], [cd_proposta])
REFERENCES [K_SCHEMA].[s_parcela] ([cd_parcela], [cd_projeto], [cd_proposta])

ALTER TABLE [K_SCHEMA].[s_extrato_mensal_parcela] CHECK CONSTRAINT [fk_oasis_057]

ALTER TABLE [K_SCHEMA].[s_produto_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_021] FOREIGN KEY([cd_tipo_produto])
REFERENCES [K_SCHEMA].[b_tipo_produto] ([cd_tipo_produto])

ALTER TABLE [K_SCHEMA].[s_produto_parcela] CHECK CONSTRAINT [fk_oasis_021]

ALTER TABLE [K_SCHEMA].[s_produto_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_022] FOREIGN KEY([cd_parcela], [cd_projeto], [cd_proposta])
REFERENCES [K_SCHEMA].[s_parcela] ([cd_parcela], [cd_projeto], [cd_proposta])

ALTER TABLE [K_SCHEMA].[s_produto_parcela] CHECK CONSTRAINT [fk_oasis_022]

ALTER TABLE [K_SCHEMA].[s_processamento_parcela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_024] FOREIGN KEY([cd_parcela], [cd_projeto], [cd_proposta])
REFERENCES [K_SCHEMA].[s_parcela] ([cd_parcela], [cd_projeto], [cd_proposta])

ALTER TABLE [K_SCHEMA].[s_processamento_parcela] CHECK CONSTRAINT [fk_oasis_024]

ALTER TABLE [K_SCHEMA].[s_modulo]  WITH CHECK ADD  CONSTRAINT [fk_oasis_035] FOREIGN KEY([cd_status])
REFERENCES [K_SCHEMA].[b_status] ([cd_status])

ALTER TABLE [K_SCHEMA].[s_modulo] CHECK CONSTRAINT [fk_oasis_035]

ALTER TABLE [K_SCHEMA].[s_modulo]  WITH CHECK ADD  CONSTRAINT [fk_oasis_036] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_modulo] CHECK CONSTRAINT [fk_oasis_036]

ALTER TABLE [K_SCHEMA].[s_contato_empresa]  WITH CHECK ADD  CONSTRAINT [fk_oasis_064] FOREIGN KEY([cd_empresa])
REFERENCES [K_SCHEMA].[s_empresa] ([cd_empresa])

ALTER TABLE [K_SCHEMA].[s_contato_empresa] CHECK CONSTRAINT [fk_oasis_064]

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_perfil_prof]  WITH CHECK ADD  CONSTRAINT [fk_oasis_149] FOREIGN KEY([cd_perfil_profissional])
REFERENCES [K_SCHEMA].[b_perfil_profissional] ([cd_perfil_profissional])

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_perfil_prof] CHECK CONSTRAINT [fk_oasis_149]

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_perfil_prof]  WITH CHECK ADD  CONSTRAINT [fk_oasis_150] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_perfil_prof] CHECK CONSTRAINT [fk_oasis_150]

ALTER TABLE [K_SCHEMA].[a_perfil_prof_papel_prof]  WITH CHECK ADD  CONSTRAINT [fk_oasis_136] FOREIGN KEY([cd_perfil_profissional])
REFERENCES [K_SCHEMA].[b_perfil_profissional] ([cd_perfil_profissional])

ALTER TABLE [K_SCHEMA].[a_perfil_prof_papel_prof] CHECK CONSTRAINT [fk_oasis_136]

ALTER TABLE [K_SCHEMA].[a_perfil_prof_papel_prof]  WITH CHECK ADD  CONSTRAINT [fk_oasis_137] FOREIGN KEY([cd_papel_profissional])
REFERENCES [K_SCHEMA].[b_papel_profissional] ([cd_papel_profissional])

ALTER TABLE [K_SCHEMA].[a_perfil_prof_papel_prof] CHECK CONSTRAINT [fk_oasis_137]

ALTER TABLE [K_SCHEMA].[a_profissional_objeto_contrato]  WITH CHECK ADD  CONSTRAINT [fk_oasis_124] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_profissional_objeto_contrato] CHECK CONSTRAINT [fk_oasis_124]

ALTER TABLE [K_SCHEMA].[a_profissional_objeto_contrato]  WITH CHECK ADD  CONSTRAINT [fk_oasis_125] FOREIGN KEY([cd_perfil_profissional])
REFERENCES [K_SCHEMA].[b_perfil_profissional] ([cd_perfil_profissional])

ALTER TABLE [K_SCHEMA].[a_profissional_objeto_contrato] CHECK CONSTRAINT [fk_oasis_125]

ALTER TABLE [K_SCHEMA].[a_profissional_objeto_contrato]  WITH CHECK ADD  CONSTRAINT [fk_oasis_126] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[a_profissional_objeto_contrato] CHECK CONSTRAINT [fk_oasis_126]

ALTER TABLE [K_SCHEMA].[a_profissional_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_119] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[a_profissional_projeto] CHECK CONSTRAINT [fk_oasis_119]

ALTER TABLE [K_SCHEMA].[a_profissional_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_120] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_profissional_projeto] CHECK CONSTRAINT [fk_oasis_120]

ALTER TABLE [K_SCHEMA].[a_profissional_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_121] FOREIGN KEY([cd_papel_profissional])
REFERENCES [K_SCHEMA].[b_papel_profissional] ([cd_papel_profissional])

ALTER TABLE [K_SCHEMA].[a_profissional_projeto] CHECK CONSTRAINT [fk_oasis_121]

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_papel_prof]  WITH CHECK ADD  CONSTRAINT [fk_oasis_151] FOREIGN KEY([cd_papel_profissional])
REFERENCES [K_SCHEMA].[b_papel_profissional] ([cd_papel_profissional])

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_papel_prof] CHECK CONSTRAINT [fk_oasis_151]

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_papel_prof]  WITH CHECK ADD  CONSTRAINT [fk_oasis_152] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_papel_prof] CHECK CONSTRAINT [fk_oasis_152]

ALTER TABLE [K_SCHEMA].[b_atividade]  WITH CHECK ADD  CONSTRAINT [fk_oasis_097] FOREIGN KEY([cd_etapa])
REFERENCES [K_SCHEMA].[b_etapa] ([cd_etapa])

ALTER TABLE [K_SCHEMA].[b_atividade] CHECK CONSTRAINT [fk_oasis_097]

ALTER TABLE [K_SCHEMA].[b_questao_analise_risco]  WITH CHECK ADD  CONSTRAINT [fk_oasis_084] FOREIGN KEY([cd_item_risco], [cd_etapa], [cd_atividade])
REFERENCES [K_SCHEMA].[b_item_risco] ([cd_item_risco], [cd_etapa], [cd_atividade])

ALTER TABLE [K_SCHEMA].[b_questao_analise_risco] CHECK CONSTRAINT [fk_oasis_084]

ALTER TABLE [K_SCHEMA].[s_historico_execucao_demanda]  WITH CHECK ADD  CONSTRAINT [fk_oasis_050] FOREIGN KEY([cd_demanda], [cd_profissional], [cd_nivel_servico])
REFERENCES [K_SCHEMA].[a_demanda_prof_nivel_servico] ([cd_demanda], [cd_profissional], [cd_nivel_servico])

ALTER TABLE [K_SCHEMA].[s_historico_execucao_demanda] CHECK CONSTRAINT [fk_oasis_050]

ALTER TABLE [K_SCHEMA].[a_demanda_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_191] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_demanda_profissional] CHECK CONSTRAINT [fk_oasis_191]

ALTER TABLE [K_SCHEMA].[a_demanda_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_192] FOREIGN KEY([cd_demanda])
REFERENCES [K_SCHEMA].[s_demanda] ([cd_demanda])

ALTER TABLE [K_SCHEMA].[a_demanda_profissional] CHECK CONSTRAINT [fk_oasis_192]

ALTER TABLE [K_SCHEMA].[s_tabela]  WITH CHECK ADD  CONSTRAINT [fk_oasis_002] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_tabela] CHECK CONSTRAINT [fk_oasis_002]

ALTER TABLE [K_SCHEMA].[s_proposta]  WITH CHECK ADD  CONSTRAINT [fk_oasis_010] FOREIGN KEY([ni_solicitacao], [ni_ano_solicitacao], [cd_objeto])
REFERENCES [K_SCHEMA].[s_solicitacao] ([ni_solicitacao], [ni_ano_solicitacao], [cd_objeto])

ALTER TABLE [K_SCHEMA].[s_proposta] CHECK CONSTRAINT [fk_oasis_010]

ALTER TABLE [K_SCHEMA].[s_proposta]  WITH CHECK ADD  CONSTRAINT [fk_oasis_011] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_proposta] CHECK CONSTRAINT [fk_oasis_011]

ALTER TABLE [K_SCHEMA].[s_ator]  WITH CHECK ADD  CONSTRAINT [fk_oasis_072] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_ator] CHECK CONSTRAINT [fk_oasis_072]

ALTER TABLE [K_SCHEMA].[s_analise_execucao_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_080] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_analise_execucao_projeto] CHECK CONSTRAINT [fk_oasis_080]

ALTER TABLE [K_SCHEMA].[a_conhecimento_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_204] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[a_conhecimento_projeto] CHECK CONSTRAINT [fk_oasis_204]

ALTER TABLE [K_SCHEMA].[a_conhecimento_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_205] FOREIGN KEY([cd_conhecimento], [cd_tipo_conhecimento])
REFERENCES [K_SCHEMA].[b_conhecimento] ([cd_conhecimento], [cd_tipo_conhecimento])

ALTER TABLE [K_SCHEMA].[a_conhecimento_projeto] CHECK CONSTRAINT [fk_oasis_205]

ALTER TABLE [K_SCHEMA].[s_situacao_projeto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_006] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_situacao_projeto] CHECK CONSTRAINT [fk_oasis_006]

ALTER TABLE [K_SCHEMA].[s_analise_matriz_rastreab]  WITH CHECK ADD  CONSTRAINT [fk_oasis_079] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_analise_matriz_rastreab] CHECK CONSTRAINT [fk_oasis_079]

ALTER TABLE [K_SCHEMA].[s_baseline]  WITH CHECK ADD  CONSTRAINT [fk_oasis_070] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_baseline] CHECK CONSTRAINT [fk_oasis_070]

ALTER TABLE [K_SCHEMA].[s_regra_negocio]  WITH CHECK ADD  CONSTRAINT [fk_oasis_009] FOREIGN KEY([cd_projeto_regra_negocio])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_regra_negocio] CHECK CONSTRAINT [fk_oasis_009]

ALTER TABLE [K_SCHEMA].[s_reuniao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_007] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_reuniao] CHECK CONSTRAINT [fk_oasis_007]

ALTER TABLE [K_SCHEMA].[s_reuniao_geral]  WITH CHECK ADD  CONSTRAINT [fk_oasis_2007] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[s_reuniao_geral] CHECK CONSTRAINT [fk_oasis_2007]

ALTER TABLE [K_SCHEMA].[s_requisito]  WITH CHECK ADD  CONSTRAINT [fk_oasis_008] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_requisito] CHECK CONSTRAINT [fk_oasis_008]

ALTER TABLE [K_SCHEMA].[s_pre_projeto_evolutivo]  WITH CHECK ADD  CONSTRAINT [fk_oasis_026] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_pre_projeto_evolutivo] CHECK CONSTRAINT [fk_oasis_026]

ALTER TABLE [K_SCHEMA].[s_previsao_projeto_diario]  WITH CHECK ADD  CONSTRAINT [fk_oasis_025] FOREIGN KEY([cd_projeto])
REFERENCES [K_SCHEMA].[s_projeto] ([cd_projeto])

ALTER TABLE [K_SCHEMA].[s_previsao_projeto_diario] CHECK CONSTRAINT [fk_oasis_025]

ALTER TABLE [K_SCHEMA].[s_historico_proposta_produto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_042] FOREIGN KEY([cd_tipo_produto])
REFERENCES [K_SCHEMA].[b_tipo_produto] ([cd_tipo_produto])

ALTER TABLE [K_SCHEMA].[s_historico_proposta_produto] CHECK CONSTRAINT [fk_oasis_042]

ALTER TABLE [K_SCHEMA].[s_historico_proposta_produto]  WITH CHECK ADD  CONSTRAINT [fk_oasis_043] FOREIGN KEY([cd_proposta], [cd_projeto], [dt_historico_proposta], [cd_historico_proposta_parcela])
REFERENCES [K_SCHEMA].[s_historico_proposta_parcela] ([cd_proposta], [cd_projeto], [dt_historico_proposta], [cd_historico_proposta_parcela])

ALTER TABLE [K_SCHEMA].[s_historico_proposta_produto] CHECK CONSTRAINT [fk_oasis_043]

ALTER TABLE [K_SCHEMA].[b_sub_item_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_083] FOREIGN KEY([cd_item_metrica], [cd_definicao_metrica])
REFERENCES [K_SCHEMA].[b_item_metrica] ([cd_item_metrica], [cd_definicao_metrica])

ALTER TABLE [K_SCHEMA].[b_sub_item_metrica] CHECK CONSTRAINT [fk_oasis_083]

ALTER TABLE [K_SCHEMA].[s_historico]  WITH CHECK ADD  CONSTRAINT [fk_oasis_051] FOREIGN KEY([cd_atividade], [cd_etapa])
REFERENCES [K_SCHEMA].[b_atividade] ([cd_atividade], [cd_etapa])

ALTER TABLE [K_SCHEMA].[s_historico] CHECK CONSTRAINT [fk_oasis_051]

ALTER TABLE [K_SCHEMA].[a_planejamento]  WITH CHECK ADD  CONSTRAINT [fk_oasis_134] FOREIGN KEY([cd_modulo], [cd_projeto])
REFERENCES [K_SCHEMA].[s_modulo] ([cd_modulo], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_planejamento] CHECK CONSTRAINT [fk_oasis_134]

ALTER TABLE [K_SCHEMA].[a_planejamento]  WITH CHECK ADD  CONSTRAINT [fk_oasis_135] FOREIGN KEY([cd_atividade], [cd_etapa])
REFERENCES [K_SCHEMA].[b_atividade] ([cd_atividade], [cd_etapa])

ALTER TABLE [K_SCHEMA].[a_planejamento] CHECK CONSTRAINT [fk_oasis_135]

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_atividade]  WITH CHECK ADD  CONSTRAINT [fk_oasis_153] FOREIGN KEY([cd_atividade], [cd_etapa])
REFERENCES [K_SCHEMA].[b_atividade] ([cd_atividade], [cd_etapa])

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_atividade] CHECK CONSTRAINT [fk_oasis_153]

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_atividade]  WITH CHECK ADD  CONSTRAINT [fk_oasis_154] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[a_objeto_contrato_atividade] CHECK CONSTRAINT [fk_oasis_154]

ALTER TABLE [K_SCHEMA].[b_item_risco]  WITH CHECK ADD  CONSTRAINT [fk_oasis_091] FOREIGN KEY([cd_atividade], [cd_etapa])
REFERENCES [K_SCHEMA].[b_atividade] ([cd_atividade], [cd_etapa])

ALTER TABLE [K_SCHEMA].[b_item_risco] CHECK CONSTRAINT [fk_oasis_091]

ALTER TABLE [K_SCHEMA].[b_condicao_sub_item_metrica]  WITH CHECK ADD  CONSTRAINT [fk_oasis_096] FOREIGN KEY([cd_sub_item_metrica], [cd_definicao_metrica], [cd_item_metrica])
REFERENCES [K_SCHEMA].[b_sub_item_metrica] ([cd_sub_item_metrica], [cd_definicao_metrica], [cd_item_metrica])

ALTER TABLE [K_SCHEMA].[b_condicao_sub_item_metrica] CHECK CONSTRAINT [fk_oasis_096]

ALTER TABLE [K_SCHEMA].[a_questionario_analise_risco]  WITH CHECK ADD  CONSTRAINT [fk_oasis_108] FOREIGN KEY([dt_analise_risco], [cd_projeto], [cd_proposta], [cd_etapa], [cd_atividade], [cd_item_risco])
REFERENCES [K_SCHEMA].[s_analise_risco] ([dt_analise_risco], [cd_projeto], [cd_proposta], [cd_etapa], [cd_atividade], [cd_item_risco])

ALTER TABLE [K_SCHEMA].[a_questionario_analise_risco] CHECK CONSTRAINT [fk_oasis_108]

ALTER TABLE [K_SCHEMA].[a_questionario_analise_risco]  WITH CHECK ADD  CONSTRAINT [fk_oasis_109] FOREIGN KEY([cd_questao_analise_risco], [cd_atividade], [cd_etapa], [cd_item_risco])
REFERENCES [K_SCHEMA].[b_questao_analise_risco] ([cd_questao_analise_risco], [cd_atividade], [cd_etapa], [cd_item_risco])

ALTER TABLE [K_SCHEMA].[a_questionario_analise_risco] CHECK CONSTRAINT [fk_oasis_109]

ALTER TABLE [K_SCHEMA].[a_questionario_analise_risco]  WITH CHECK ADD  CONSTRAINT [fk_oasis_110] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_questionario_analise_risco] CHECK CONSTRAINT [fk_oasis_110]

ALTER TABLE [K_SCHEMA].[s_penalizacao]  WITH CHECK ADD  CONSTRAINT [fk_oasis_031] FOREIGN KEY([cd_penalidade], [cd_contrato])
REFERENCES [K_SCHEMA].[b_penalidade] ([cd_penalidade], [cd_contrato])

ALTER TABLE [K_SCHEMA].[s_penalizacao] CHECK CONSTRAINT [fk_oasis_031]

ALTER TABLE [K_SCHEMA].[s_projeto_continuado]  WITH CHECK ADD  CONSTRAINT [fk_oasis_014] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[s_projeto_continuado] CHECK CONSTRAINT [fk_oasis_014]

ALTER TABLE [K_SCHEMA].[b_nivel_servico]  WITH CHECK ADD  CONSTRAINT [fk_oasis_088] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[b_nivel_servico] CHECK CONSTRAINT [fk_oasis_088]

ALTER TABLE [K_SCHEMA].[s_disponibilidade_servico]  WITH CHECK ADD  CONSTRAINT [fk_oasis_059] FOREIGN KEY([cd_objeto])
REFERENCES [K_SCHEMA].[s_objeto_contrato] ([cd_objeto])

ALTER TABLE [K_SCHEMA].[s_disponibilidade_servico] CHECK CONSTRAINT [fk_oasis_059]

ALTER TABLE [K_SCHEMA].[a_opcao_resp_pergunta_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_213] FOREIGN KEY([cd_pergunta_pedido])
REFERENCES [K_SCHEMA].[b_pergunta_pedido] ([cd_pergunta_pedido])

ALTER TABLE [K_SCHEMA].[a_opcao_resp_pergunta_pedido] CHECK CONSTRAINT [fk_oasis_213]

ALTER TABLE [K_SCHEMA].[a_opcao_resp_pergunta_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_214] FOREIGN KEY([cd_resposta_pedido])
REFERENCES [K_SCHEMA].[b_resposta_pedido] ([cd_resposta_pedido])

ALTER TABLE [K_SCHEMA].[a_opcao_resp_pergunta_pedido] CHECK CONSTRAINT [fk_oasis_214]

ALTER TABLE [K_SCHEMA].[s_caso_de_uso]  WITH CHECK ADD  CONSTRAINT [fk_oasis_069] FOREIGN KEY([cd_modulo], [cd_projeto])
REFERENCES [K_SCHEMA].[s_modulo] ([cd_modulo], [cd_projeto])

ALTER TABLE [K_SCHEMA].[s_caso_de_uso] CHECK CONSTRAINT [fk_oasis_069]

ALTER TABLE [K_SCHEMA].[a_regra_negocio_requisito]  WITH CHECK ADD  CONSTRAINT [fk_oasis_106] FOREIGN KEY([cd_requisito], [dt_versao_requisito], [cd_projeto])
REFERENCES [K_SCHEMA].[s_requisito] ([cd_requisito], [dt_versao_requisito], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_regra_negocio_requisito] CHECK CONSTRAINT [fk_oasis_106]

ALTER TABLE [K_SCHEMA].[a_regra_negocio_requisito]  WITH CHECK ADD  CONSTRAINT [fk_oasis_107] FOREIGN KEY([cd_regra_negocio], [dt_regra_negocio], [cd_projeto_regra_negocio])
REFERENCES [K_SCHEMA].[s_regra_negocio] ([cd_regra_negocio], [dt_regra_negocio], [cd_projeto_regra_negocio])

ALTER TABLE [K_SCHEMA].[a_regra_negocio_requisito] CHECK CONSTRAINT [fk_oasis_107]

ALTER TABLE [K_SCHEMA].[a_reuniao_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_100] FOREIGN KEY([cd_reuniao], [cd_projeto])
REFERENCES [K_SCHEMA].[s_reuniao] ([cd_reuniao], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_reuniao_profissional] CHECK CONSTRAINT [fk_oasis_100]

ALTER TABLE [K_SCHEMA].[a_reuniao_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_101] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_reuniao_profissional] CHECK CONSTRAINT [fk_oasis_101]

ALTER TABLE [K_SCHEMA].[a_reuniao_geral_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_2099] FOREIGN KEY([cd_reuniao_geral], [cd_objeto])
REFERENCES [K_SCHEMA].[s_reuniao_geral] ([cd_reuniao_geral], [cd_objeto])

ALTER TABLE [K_SCHEMA].[a_reuniao_geral_profissional] CHECK CONSTRAINT [fk_oasis_2099]

ALTER TABLE [K_SCHEMA].[a_reuniao_geral_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_2100] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_reuniao_geral_profissional] CHECK CONSTRAINT [fk_oasis_2100]

ALTER TABLE [K_SCHEMA].[a_profissional_mensageria]  WITH CHECK ADD  CONSTRAINT [fk_oasis_130] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_profissional_mensageria] CHECK CONSTRAINT [fk_oasis_130]

ALTER TABLE [K_SCHEMA].[a_profissional_mensageria]  WITH CHECK ADD  CONSTRAINT [fk_oasis_131] FOREIGN KEY([cd_mensageria])
REFERENCES [K_SCHEMA].[s_mensageria] ([cd_mensageria])

ALTER TABLE [K_SCHEMA].[a_profissional_mensageria] CHECK CONSTRAINT [fk_oasis_131]

ALTER TABLE [K_SCHEMA].[a_requisito_dependente]  WITH CHECK ADD  CONSTRAINT [fk_oasis_102] FOREIGN KEY([cd_requisito], [dt_versao_requisito], [cd_projeto])
REFERENCES [K_SCHEMA].[s_requisito] ([cd_requisito], [dt_versao_requisito], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_requisito_dependente] CHECK CONSTRAINT [fk_oasis_102]

ALTER TABLE [K_SCHEMA].[a_requisito_dependente]  WITH CHECK ADD  CONSTRAINT [fk_oasis_103] FOREIGN KEY([cd_requisito_ascendente], [dt_versao_requisito_ascendente], [cd_projeto_ascendente])
REFERENCES [K_SCHEMA].[s_requisito] ([cd_requisito], [dt_versao_requisito], [cd_projeto])

ALTER TABLE [K_SCHEMA].[a_requisito_dependente] CHECK CONSTRAINT [fk_oasis_103]

ALTER TABLE [K_SCHEMA].[a_profissional_conhecimento]  WITH CHECK ADD  CONSTRAINT [fk_oasis_132] FOREIGN KEY([cd_profissional])
REFERENCES [K_SCHEMA].[s_profissional] ([cd_profissional])

ALTER TABLE [K_SCHEMA].[a_profissional_conhecimento] CHECK CONSTRAINT [fk_oasis_132]

ALTER TABLE [K_SCHEMA].[a_profissional_conhecimento]  WITH CHECK ADD  CONSTRAINT [fk_oasis_133] FOREIGN KEY([cd_conhecimento], [cd_tipo_conhecimento])
REFERENCES [K_SCHEMA].[b_conhecimento] ([cd_conhecimento], [cd_tipo_conhecimento])

ALTER TABLE [K_SCHEMA].[a_profissional_conhecimento] CHECK CONSTRAINT [fk_oasis_133]

ALTER TABLE [K_SCHEMA].[s_historico_pedido]  WITH CHECK ADD  CONSTRAINT [fk_oasis_210] FOREIGN KEY([cd_solicitacao_historico])
REFERENCES [K_SCHEMA].[s_solicitacao_pedido] ([cd_solicitacao_pedido])

ALTER TABLE [K_SCHEMA].[s_historico_pedido] CHECK CONSTRAINT [fk_oasis_210]

ALTER TABLE [K_SCHEMA].[b_papel_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_087] FOREIGN KEY([cd_area_atuacao_ti])
REFERENCES [K_SCHEMA].[b_area_atuacao_ti] ([cd_area_atuacao_ti])

ALTER TABLE [K_SCHEMA].[b_papel_profissional] CHECK CONSTRAINT [fk_oasis_087]

ALTER TABLE [K_SCHEMA].[b_perfil_profissional]  WITH CHECK ADD  CONSTRAINT [fk_oasis_085] FOREIGN KEY([cd_area_atuacao_ti])
REFERENCES [K_SCHEMA].[b_area_atuacao_ti] ([cd_area_atuacao_ti])

ALTER TABLE [K_SCHEMA].[b_perfil_profissional] CHECK CONSTRAINT [fk_oasis_085]

ALTER TABLE [K_SCHEMA].[b_etapa]  WITH CHECK ADD  CONSTRAINT [fk_oasis_094] FOREIGN KEY([cd_area_atuacao_ti])
REFERENCES [K_SCHEMA].[b_area_atuacao_ti] ([cd_area_atuacao_ti])

ALTER TABLE [K_SCHEMA].[b_etapa] CHECK CONSTRAINT [fk_oasis_094]

ALTER TABLE [K_SCHEMA].[s_base_conhecimento]  WITH CHECK ADD  CONSTRAINT [fk_oasis_071] FOREIGN KEY([cd_area_conhecimento])
REFERENCES [K_SCHEMA].[b_area_conhecimento] ([cd_area_conhecimento])

ALTER TABLE [K_SCHEMA].[s_base_conhecimento] CHECK CONSTRAINT [fk_oasis_071]