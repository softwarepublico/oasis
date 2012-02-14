
-- Foreign Key
ALTER TABLE a_baseline_item_controle
ADD CONSTRAINT fk_oasis2_206 FOREIGN KEY (dt_baseline, cd_projeto)
REFERENCES s_baseline (dt_baseline,cd_projeto)
/
ALTER TABLE a_baseline_item_controle
ADD CONSTRAINT fk_oasis2_207 FOREIGN KEY (cd_item_controle_baseline)
REFERENCES b_item_controle_baseline (cd_item_controle_baseline)
/
-- Foreign Key
ALTER TABLE a_conhecimento_projeto
ADD CONSTRAINT fk_oasis2_204 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
ALTER TABLE a_conhecimento_projeto
ADD CONSTRAINT fk_oasis2_205 FOREIGN KEY (cd_conhecimento, cd_tipo_conhecimento)
REFERENCES b_conhecimento (cd_conhecimento,cd_tipo_conhecimento)
/
-- Foreign Key
ALTER TABLE a_conjunto_medida_medicao
ADD CONSTRAINT fk_oasis2_203 FOREIGN KEY (cd_conjunto_medida)
REFERENCES b_conjunto_medida (cd_conjunto_medida)
/
-- Foreign Key
ALTER TABLE a_contrato_definicao_metrica
ADD CONSTRAINT fk_oasis2_201 FOREIGN KEY (cd_definicao_metrica)
REFERENCES b_definicao_metrica (cd_definicao_metrica)
/
ALTER TABLE a_contrato_definicao_metrica
ADD CONSTRAINT fk_oasis2_202 FOREIGN KEY (cd_contrato)
REFERENCES s_contrato (cd_contrato)
/
-- Foreign Key
ALTER TABLE a_contrato_projeto
ADD CONSTRAINT fk_oasis2_199 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
ALTER TABLE a_contrato_projeto
ADD CONSTRAINT fk_oasis2_200 FOREIGN KEY (cd_contrato)
REFERENCES s_contrato (cd_contrato)
/
-- Foreign Key
ALTER TABLE a_controle
ADD CONSTRAINT fk_oasis2_197 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
ALTER TABLE a_controle
ADD CONSTRAINT fk_oasis2_198 FOREIGN KEY (cd_projeto_previsto, cd_contrato)
REFERENCES s_projeto_previsto (cd_projeto_previsto,cd_contrato)
/
-- Foreign Key
ALTER TABLE a_definicao_processo
ADD CONSTRAINT fk_oasis2_195 FOREIGN KEY (cd_perfil)
REFERENCES b_perfil (cd_perfil)
/
ALTER TABLE a_definicao_processo
ADD CONSTRAINT fk_oasis2_196 FOREIGN KEY (cd_funcionalidade)
REFERENCES b_funcionalidade (cd_funcionalidade)
/
-- Foreign Key
ALTER TABLE a_demanda_prof_nivel_servico
ADD CONSTRAINT fk_oasis2_193 FOREIGN KEY (cd_nivel_servico)
REFERENCES b_nivel_servico (cd_nivel_servico)
/
ALTER TABLE a_demanda_prof_nivel_servico
ADD CONSTRAINT fk_oasis2_194 FOREIGN KEY (cd_profissional, cd_demanda)
REFERENCES a_demanda_profissional (cd_profissional,cd_demanda)
/
-- Foreign Key
ALTER TABLE a_demanda_profissional
ADD CONSTRAINT fk_oasis2_191 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_demanda_profissional
ADD CONSTRAINT fk_oasis2_192 FOREIGN KEY (cd_demanda)
REFERENCES s_demanda (cd_demanda)
/
-- Foreign Key
ALTER TABLE a_disponibilidade_servico_doc
ADD CONSTRAINT fk_oasis2_189 FOREIGN KEY (cd_disponibilidade_servico, cd_objeto)
REFERENCES s_disponibilidade_servico (cd_disponibilidade_servico,cd_objeto)
/
ALTER TABLE a_disponibilidade_servico_doc
ADD CONSTRAINT fk_oasis2_190 FOREIGN KEY (cd_tipo_documentacao)
REFERENCES b_tipo_documentacao (cd_tipo_documentacao)
/
-- Foreign Key
ALTER TABLE a_documentacao_profissional
ADD CONSTRAINT fk_oasis2_187 FOREIGN KEY (cd_tipo_documentacao)
REFERENCES b_tipo_documentacao (cd_tipo_documentacao)
/
ALTER TABLE a_documentacao_profissional
ADD CONSTRAINT fk_oasis2_188 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
-- Foreign Key
ALTER TABLE a_documentacao_projeto
ADD CONSTRAINT fk_oasis2_185 FOREIGN KEY (cd_tipo_documentacao)
REFERENCES b_tipo_documentacao (cd_tipo_documentacao)
/
ALTER TABLE a_documentacao_projeto
ADD CONSTRAINT fk_oasis2_186 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE a_funcionalidade_menu
ADD CONSTRAINT fk_oasis2_183 FOREIGN KEY (cd_menu)
REFERENCES b_menu (cd_menu)
/
ALTER TABLE a_funcionalidade_menu
ADD CONSTRAINT fk_oasis2_184 FOREIGN KEY (cd_funcionalidade)
REFERENCES b_funcionalidade (cd_funcionalidade)
/
-- Foreign Key
ALTER TABLE a_gerencia_mudanca
ADD CONSTRAINT fk_oasis2_180 FOREIGN KEY (cd_reuniao, cd_projeto_reuniao)
REFERENCES s_reuniao (cd_reuniao,cd_projeto)
/
ALTER TABLE a_gerencia_mudanca
ADD CONSTRAINT fk_oasis2_181 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
ALTER TABLE a_gerencia_mudanca
ADD CONSTRAINT fk_oasis2_182 FOREIGN KEY (cd_item_controle_baseline)
REFERENCES b_item_controle_baseline (cd_item_controle_baseline)
/
-- Foreign Key
ALTER TABLE a_informacao_tecnica
ADD CONSTRAINT fk_oasis2_178 FOREIGN KEY (cd_tipo_dado_tecnico)
REFERENCES b_tipo_dado_tecnico (cd_tipo_dado_tecnico)
/
ALTER TABLE a_informacao_tecnica
ADD CONSTRAINT fk_oasis2_179 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE a_item_teste_caso_de_uso
ADD CONSTRAINT fk_oasis2_173 FOREIGN KEY (cd_profissional_solucao)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_caso_de_uso
ADD CONSTRAINT fk_oasis2_174 FOREIGN KEY (cd_profissional_homologacao)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_caso_de_uso
ADD CONSTRAINT fk_oasis2_175 FOREIGN KEY (cd_profissional_analise)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_caso_de_uso
ADD CONSTRAINT fk_oasis2_176 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso)
REFERENCES s_caso_de_uso (cd_caso_de_uso,cd_projeto,cd_modulo,dt_versao_caso_de_uso)
/
ALTER TABLE a_item_teste_caso_de_uso
ADD CONSTRAINT fk_oasis2_177 FOREIGN KEY (cd_item_teste)
REFERENCES b_item_teste (cd_item_teste)
/
-- Foreign Key
ALTER TABLE a_item_teste_caso_de_uso_doc
ADD CONSTRAINT fk_oasis2_171 FOREIGN KEY (cd_tipo_documentacao)
REFERENCES b_tipo_documentacao (cd_tipo_documentacao)
/
ALTER TABLE a_item_teste_caso_de_uso_doc
ADD CONSTRAINT fk_oasis2_172 FOREIGN KEY (cd_item_teste, cd_modulo, cd_projeto, 
  cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso)
REFERENCES a_item_teste_caso_de_uso (cd_item_teste,cd_modulo,cd_projeto,cd_caso_de_uso,dt_versao_caso_de_uso,cd_item_teste_caso_de_uso)
/
-- Foreign Key
ALTER TABLE a_item_teste_regra_negocio
ADD CONSTRAINT fk_oasis2_166 FOREIGN KEY (cd_profissional_solucao)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_regra_negocio
ADD CONSTRAINT fk_oasis2_167 FOREIGN KEY (cd_profissional_homologacao)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_regra_negocio
ADD CONSTRAINT fk_oasis2_168 FOREIGN KEY (cd_profissional_analise)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_regra_negocio
ADD CONSTRAINT fk_oasis2_169 FOREIGN KEY (cd_item_teste)
REFERENCES b_item_teste (cd_item_teste)
/
ALTER TABLE a_item_teste_regra_negocio
ADD CONSTRAINT fk_oasis2_170 FOREIGN KEY (cd_regra_negocio, dt_regra_negocio, 
  cd_projeto_regra_negocio)
REFERENCES s_regra_negocio (cd_regra_negocio,dt_regra_negocio,cd_projeto_regra_negocio)
/
-- Foreign Key
ALTER TABLE a_item_teste_regra_negocio_doc
ADD CONSTRAINT fk_oasis2_164 FOREIGN KEY (dt_regra_negocio, cd_regra_negocio, 
  cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio)
REFERENCES a_item_teste_regra_negocio (dt_regra_negocio,cd_regra_negocio,cd_item_teste,cd_projeto_regra_negocio,cd_item_teste_regra_negocio)
/
ALTER TABLE a_item_teste_regra_negocio_doc
ADD CONSTRAINT fk_oasis2_165 FOREIGN KEY (cd_tipo_documentacao)
REFERENCES b_tipo_documentacao (cd_tipo_documentacao)
/
-- Foreign Key
ALTER TABLE a_item_teste_requisito
ADD CONSTRAINT fk_oasis2_159 FOREIGN KEY (cd_requisito, dt_versao_requisito, 
  cd_projeto)
REFERENCES s_requisito (cd_requisito,dt_versao_requisito,cd_projeto)
/
ALTER TABLE a_item_teste_requisito
ADD CONSTRAINT fk_oasis2_160 FOREIGN KEY (cd_profissional_solucao)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_requisito
ADD CONSTRAINT fk_oasis2_161 FOREIGN KEY (cd_profissional_homologacao)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_requisito
ADD CONSTRAINT fk_oasis2_162 FOREIGN KEY (cd_profissional_analise)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_item_teste_requisito
ADD CONSTRAINT fk_oasis2_163 FOREIGN KEY (cd_item_teste)
REFERENCES b_item_teste (cd_item_teste)
/
-- Foreign Key
ALTER TABLE a_item_teste_requisito_doc
ADD CONSTRAINT fk_oasis2_157 FOREIGN KEY (cd_tipo_documentacao)
REFERENCES b_tipo_documentacao (cd_tipo_documentacao)
/
ALTER TABLE a_item_teste_requisito_doc
ADD CONSTRAINT fk_oasis2_158 FOREIGN KEY (cd_item_teste_requisito, cd_requisito, 
  dt_versao_requisito, cd_projeto, cd_item_teste)
REFERENCES a_item_teste_requisito (cd_item_teste_requisito,cd_requisito,dt_versao_requisito,cd_projeto,cd_item_teste)
/
-- Foreign Key
ALTER TABLE a_medicao_medida
ADD CONSTRAINT fk_oasis2_155 FOREIGN KEY (cd_medida)
REFERENCES b_medida (cd_medida)
/
ALTER TABLE a_medicao_medida
ADD CONSTRAINT fk_oasis2_156 FOREIGN KEY (cd_medicao)
REFERENCES s_medicao (cd_medicao)
/
-- Foreign Key
ALTER TABLE a_objeto_contrato_atividade
ADD CONSTRAINT fk_oasis2_153 FOREIGN KEY (cd_atividade, cd_etapa)
REFERENCES b_atividade (cd_atividade,cd_etapa)
/
ALTER TABLE a_objeto_contrato_atividade
ADD CONSTRAINT fk_oasis2_154 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
-- Foreign Key
ALTER TABLE a_objeto_contrato_papel_prof
ADD CONSTRAINT fk_oasis2_151 FOREIGN KEY (cd_papel_profissional)
REFERENCES b_papel_profissional (cd_papel_profissional)
/
ALTER TABLE a_objeto_contrato_papel_prof
ADD CONSTRAINT fk_oasis2_152 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
-- Foreign Key
ALTER TABLE a_objeto_contrato_perfil_prof
ADD CONSTRAINT fk_oasis2_149 FOREIGN KEY (cd_perfil_profissional)
REFERENCES b_perfil_profissional (cd_perfil_profissional)
/
ALTER TABLE a_objeto_contrato_perfil_prof
ADD CONSTRAINT fk_oasis2_150 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
-- Foreign Key
ALTER TABLE a_opcao_resp_pergunta_pedido
ADD CONSTRAINT fk_oasis_214 FOREIGN KEY (cd_resposta_pedido)
REFERENCES b_resposta_pedido (cd_resposta_pedido)
/
ALTER TABLE a_opcao_resp_pergunta_pedido
ADD CONSTRAINT fk_oasis_213 FOREIGN KEY (cd_pergunta_pedido)
REFERENCES b_pergunta_pedido (cd_pergunta_pedido)
/
-- Foreign Key
ALTER TABLE a_parecer_tecnico_parcela
ADD CONSTRAINT fk_oasis2_147 FOREIGN KEY (cd_processamento_parcela, cd_proposta, 
  cd_projeto, cd_parcela)
REFERENCES s_processamento_parcela (cd_processamento_parcela,cd_proposta,cd_projeto,cd_parcela)
/
ALTER TABLE a_parecer_tecnico_parcela
ADD CONSTRAINT fk_oasis2_148 FOREIGN KEY (cd_item_parecer_tecnico)
REFERENCES b_item_parecer_tecnico (cd_item_parecer_tecnico)
/
-- Foreign Key
ALTER TABLE a_parecer_tecnico_proposta
ADD CONSTRAINT fk_oasis2_146 FOREIGN KEY (cd_item_parecer_tecnico)
REFERENCES b_item_parecer_tecnico (cd_item_parecer_tecnico)
/
-- Foreign Key
ALTER TABLE a_perfil_box_inicio
ADD CONSTRAINT fk_oasis2_143 FOREIGN KEY (cd_perfil)
REFERENCES b_perfil (cd_perfil)
/
ALTER TABLE a_perfil_box_inicio
ADD CONSTRAINT fk_oasis2_144 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
ALTER TABLE a_perfil_box_inicio
ADD CONSTRAINT fk_oasis2_145 FOREIGN KEY (cd_box_inicio)
REFERENCES b_box_inicio (cd_box_inicio)
/
-- Foreign Key
ALTER TABLE a_perfil_menu
ADD CONSTRAINT fk_oasis2_140 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
ALTER TABLE a_perfil_menu
ADD CONSTRAINT fk_oasis2_141 FOREIGN KEY (cd_perfil)
REFERENCES b_perfil (cd_perfil)
/
ALTER TABLE a_perfil_menu
ADD CONSTRAINT fk_oasis2_142 FOREIGN KEY (cd_menu)
REFERENCES b_menu (cd_menu)
/
-- Foreign Key
ALTER TABLE a_perfil_menu_sistema
ADD CONSTRAINT fk_oasis2_138 FOREIGN KEY (cd_perfil)
REFERENCES b_perfil (cd_perfil)
/
ALTER TABLE a_perfil_menu_sistema
ADD CONSTRAINT fk_oasis2_139 FOREIGN KEY (cd_menu)
REFERENCES b_menu (cd_menu)
/
-- Foreign Key
ALTER TABLE a_perfil_prof_papel_prof
ADD CONSTRAINT fk_oasis2_136 FOREIGN KEY (cd_perfil_profissional)
REFERENCES b_perfil_profissional (cd_perfil_profissional)
/
ALTER TABLE a_perfil_prof_papel_prof
ADD CONSTRAINT fk_oasis2_137 FOREIGN KEY (cd_papel_profissional)
REFERENCES b_papel_profissional (cd_papel_profissional)
/
-- Foreign Key
ALTER TABLE a_pergunta_depende_resp_pedido
ADD CONSTRAINT fk_oasis_216 FOREIGN KEY (cd_pergunta_pedido, cd_resposta_pedido)
REFERENCES a_opcao_resp_pergunta_pedido (cd_pergunta_pedido,cd_resposta_pedido)
/
ALTER TABLE a_pergunta_depende_resp_pedido
ADD CONSTRAINT fk_oasis_215 FOREIGN KEY (cd_pergunta_depende)
REFERENCES b_pergunta_pedido (cd_pergunta_pedido)
/
-- Foreign Key
ALTER TABLE a_planejamento
ADD CONSTRAINT fk_oasis2_134 FOREIGN KEY (cd_modulo, cd_projeto)
REFERENCES s_modulo (cd_modulo,cd_projeto)
/
ALTER TABLE a_planejamento
ADD CONSTRAINT fk_oasis2_135 FOREIGN KEY (cd_atividade, cd_etapa)
REFERENCES b_atividade (cd_atividade,cd_etapa)
/
-- Foreign Key
ALTER TABLE a_profissional_conhecimento
ADD CONSTRAINT fk_oasis2_132 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_profissional_conhecimento
ADD CONSTRAINT fk_oasis2_133 FOREIGN KEY (cd_conhecimento, cd_tipo_conhecimento)
REFERENCES b_conhecimento (cd_conhecimento,cd_tipo_conhecimento)
/
-- Foreign Key
ALTER TABLE a_profissional_mensageria
ADD CONSTRAINT fk_oasis2_130 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_profissional_mensageria
ADD CONSTRAINT fk_oasis2_131 FOREIGN KEY (cd_mensageria)
REFERENCES s_mensageria (cd_mensageria)
/
-- Foreign Key
ALTER TABLE a_profissional_menu
ADD CONSTRAINT fk_oasis2_127 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
ALTER TABLE a_profissional_menu
ADD CONSTRAINT fk_oasis2_128 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_profissional_menu
ADD CONSTRAINT fk_oasis2_129 FOREIGN KEY (cd_menu)
REFERENCES b_menu (cd_menu)
/
-- Foreign Key
ALTER TABLE a_profissional_objeto_contrato
ADD CONSTRAINT fk_oasis2_124 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_profissional_objeto_contrato
ADD CONSTRAINT fk_oasis2_125 FOREIGN KEY (cd_perfil_profissional)
REFERENCES b_perfil_profissional (cd_perfil_profissional)
/
ALTER TABLE a_profissional_objeto_contrato
ADD CONSTRAINT fk_oasis2_126 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
-- Foreign Key
ALTER TABLE a_profissional_produto
ADD CONSTRAINT fk_oasis2_122 FOREIGN KEY (cd_produto_parcela, cd_proposta, 
  cd_projeto, cd_parcela)
REFERENCES s_produto_parcela (cd_produto_parcela,cd_proposta,cd_projeto,cd_parcela)
/
ALTER TABLE a_profissional_produto
ADD CONSTRAINT fk_oasis2_123 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
-- Foreign Key
ALTER TABLE a_profissional_projeto
ADD CONSTRAINT fk_oasis2_119 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
ALTER TABLE a_profissional_projeto
ADD CONSTRAINT fk_oasis2_120 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE a_profissional_projeto
ADD CONSTRAINT fk_oasis2_121 FOREIGN KEY (cd_papel_profissional)
REFERENCES b_papel_profissional (cd_papel_profissional)
/
-- Foreign Key
ALTER TABLE a_proposta_definicao_metrica
ADD CONSTRAINT fk_oasis2_117 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
ALTER TABLE a_proposta_definicao_metrica
ADD CONSTRAINT fk_oasis2_118 FOREIGN KEY (cd_definicao_metrica)
REFERENCES b_definicao_metrica (cd_definicao_metrica)
/
-- Foreign Key
ALTER TABLE a_proposta_modulo
ADD CONSTRAINT fk_oasis2_115 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
ALTER TABLE a_proposta_modulo
ADD CONSTRAINT fk_oasis2_116 FOREIGN KEY (cd_modulo, cd_projeto)
REFERENCES s_modulo (cd_modulo,cd_projeto)
/
-- Foreign Key
ALTER TABLE a_proposta_sub_item_metrica
ADD CONSTRAINT fk_oasis2_113 FOREIGN KEY (cd_sub_item_metrica, 
  cd_definicao_metrica, cd_item_metrica)
REFERENCES b_sub_item_metrica (cd_sub_item_metrica,cd_definicao_metrica,cd_item_metrica)
/
ALTER TABLE a_proposta_sub_item_metrica
ADD CONSTRAINT fk_oasis2_114 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
-- Foreign Key
ALTER TABLE a_quest_avaliacao_qualidade
ADD CONSTRAINT fk_oasis2_111 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
ALTER TABLE a_quest_avaliacao_qualidade
ADD CONSTRAINT fk_oasis2_112 FOREIGN KEY (cd_item_grupo_fator, cd_grupo_fator)
REFERENCES b_item_grupo_fator (cd_item_grupo_fator,cd_grupo_fator)
/
-- Foreign Key
ALTER TABLE a_questionario_analise_risco
ADD CONSTRAINT fk_oasis2_108 FOREIGN KEY (dt_analise_risco, cd_projeto, 
  cd_proposta, cd_etapa, cd_atividade, cd_item_risco)
REFERENCES s_analise_risco (dt_analise_risco,cd_projeto,cd_proposta,cd_etapa,cd_atividade,cd_item_risco)
/
ALTER TABLE a_questionario_analise_risco
ADD CONSTRAINT fk_oasis2_109 FOREIGN KEY (cd_questao_analise_risco, cd_atividade, 
  cd_etapa, cd_item_risco)
REFERENCES b_questao_analise_risco (cd_questao_analise_risco,cd_atividade,cd_etapa,cd_item_risco)
/
ALTER TABLE a_questionario_analise_risco
ADD CONSTRAINT fk_oasis2_110 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
-- Foreign Key
ALTER TABLE a_regra_negocio_requisito
ADD CONSTRAINT fk_oasis2_106 FOREIGN KEY (cd_requisito, dt_versao_requisito, 
  cd_projeto)
REFERENCES s_requisito (cd_requisito,dt_versao_requisito,cd_projeto)
/
ALTER TABLE a_regra_negocio_requisito
ADD CONSTRAINT fk_oasis2_107 FOREIGN KEY (cd_regra_negocio, dt_regra_negocio, 
  cd_projeto_regra_negocio)
REFERENCES s_regra_negocio (cd_regra_negocio,dt_regra_negocio,cd_projeto_regra_negocio)
/
-- Foreign Key
ALTER TABLE a_requisito_caso_de_uso
ADD CONSTRAINT fk_oasis2_104 FOREIGN KEY (cd_requisito, dt_versao_requisito, 
  cd_projeto)
REFERENCES s_requisito (cd_requisito,dt_versao_requisito,cd_projeto)
/
ALTER TABLE a_requisito_caso_de_uso
ADD CONSTRAINT fk_oasis2_105 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso)
REFERENCES s_caso_de_uso (cd_caso_de_uso,cd_projeto,cd_modulo,dt_versao_caso_de_uso)
/
-- Foreign Key
ALTER TABLE a_requisito_dependente
ADD CONSTRAINT fk_oasis2_102 FOREIGN KEY (cd_requisito, dt_versao_requisito, 
  cd_projeto)
REFERENCES s_requisito (cd_requisito,dt_versao_requisito,cd_projeto)
/
ALTER TABLE a_requisito_dependente
ADD CONSTRAINT fk_oasis2_103 FOREIGN KEY (cd_requisito_ascendente, 
  dt_versao_requisito_ascendente, cd_projeto_ascendente)
REFERENCES s_requisito (cd_requisito,dt_versao_requisito,cd_projeto)
/
-- Foreign Key
ALTER TABLE a_reuniao_profissional
ADD CONSTRAINT fk_oasis2_100 FOREIGN KEY (cd_reuniao, cd_projeto)
REFERENCES s_reuniao (cd_reuniao,cd_projeto)
/
ALTER TABLE a_reuniao_profissional
ADD CONSTRAINT fk_oasis2_101 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
-- Foreign Key
ALTER TABLE a_solicitacao_resposta_pedido
ADD CONSTRAINT fk_oasis_211 FOREIGN KEY (cd_solicitacao_pedido)
REFERENCES s_solicitacao_pedido (cd_solicitacao_pedido)
/
ALTER TABLE a_solicitacao_resposta_pedido
ADD CONSTRAINT fk_oasis_210 FOREIGN KEY (cd_pergunta_pedido, cd_resposta_pedido)
REFERENCES a_opcao_resp_pergunta_pedido (cd_pergunta_pedido,cd_resposta_pedido)
/
-- Foreign Key
ALTER TABLE a_treinamento_profissional
ADD CONSTRAINT fk_oasis2_098 FOREIGN KEY (cd_treinamento)
REFERENCES b_treinamento (cd_treinamento)
/
ALTER TABLE a_treinamento_profissional
ADD CONSTRAINT fk_oasis2_099 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
-- Foreign Key
ALTER TABLE b_atividade
ADD CONSTRAINT fk_oasis2_097 FOREIGN KEY (cd_etapa)
REFERENCES b_etapa (cd_etapa)
/
-- Foreign Key
ALTER TABLE b_condicao_sub_item_metrica
ADD CONSTRAINT fk_oasis2_096 FOREIGN KEY (cd_sub_item_metrica, 
  cd_definicao_metrica, cd_item_metrica)
REFERENCES b_sub_item_metrica (cd_sub_item_metrica,cd_definicao_metrica,cd_item_metrica)
/
-- Foreign Key
ALTER TABLE b_conhecimento
ADD CONSTRAINT fk_oasis2_095 FOREIGN KEY (cd_tipo_conhecimento)
REFERENCES b_tipo_conhecimento (cd_tipo_conhecimento)
/
-- Foreign Key
ALTER TABLE b_etapa
ADD CONSTRAINT fk_oasis2_094 FOREIGN KEY (cd_area_atuacao_ti)
REFERENCES b_area_atuacao_ti (cd_area_atuacao_ti)
/
-- Foreign Key
ALTER TABLE b_item_inventario
ADD CONSTRAINT fk_oasis2_093 FOREIGN KEY (cd_tipo_inventario)
REFERENCES b_tipo_inventario (cd_tipo_inventario)
/
-- Foreign Key
ALTER TABLE b_item_metrica
ADD CONSTRAINT fk_oasis2_092 FOREIGN KEY (cd_definicao_metrica)
REFERENCES b_definicao_metrica (cd_definicao_metrica)
/
-- Foreign Key
ALTER TABLE b_item_risco
ADD CONSTRAINT fk_oasis2_091 FOREIGN KEY (cd_atividade, cd_etapa)
REFERENCES b_atividade (cd_atividade,cd_etapa)
/
-- Foreign Key
ALTER TABLE b_menu
ADD CONSTRAINT fk_oasis2_090 FOREIGN KEY (cd_menu_pai)
REFERENCES b_menu (cd_menu)
/
-- Foreign Key
ALTER TABLE b_msg_email
ADD CONSTRAINT fk_oasis2_089 FOREIGN KEY (cd_menu)
REFERENCES b_menu (cd_menu)
/
-- Foreign Key
ALTER TABLE b_nivel_servico
ADD CONSTRAINT fk_oasis2_088 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
-- Foreign Key
ALTER TABLE b_papel_profissional
ADD CONSTRAINT fk_oasis2_087 FOREIGN KEY (cd_area_atuacao_ti)
REFERENCES b_area_atuacao_ti (cd_area_atuacao_ti)
/
-- Foreign Key
ALTER TABLE b_penalidade
ADD CONSTRAINT fk_oasis2_086 FOREIGN KEY (cd_contrato)
REFERENCES s_contrato (cd_contrato)
/
-- Foreign Key
ALTER TABLE b_perfil_profissional
ADD CONSTRAINT fk_oasis2_085 FOREIGN KEY (cd_area_atuacao_ti)
REFERENCES b_area_atuacao_ti (cd_area_atuacao_ti)
/
-- Foreign Key
ALTER TABLE b_questao_analise_risco
ADD CONSTRAINT fk_oasis2_084 FOREIGN KEY (cd_item_risco, cd_etapa, cd_atividade)
REFERENCES b_item_risco (cd_item_risco,cd_etapa,cd_atividade)
/
-- Foreign Key
ALTER TABLE b_sub_item_metrica
ADD CONSTRAINT fk_oasis2_083 FOREIGN KEY (cd_item_metrica, cd_definicao_metrica)
REFERENCES b_item_metrica (cd_item_metrica,cd_definicao_metrica)
/
-- Foreign Key
ALTER TABLE b_tipo_produto
ADD CONSTRAINT fk_oasis2_082 FOREIGN KEY (cd_definicao_metrica)
REFERENCES b_definicao_metrica (cd_definicao_metrica)
/
-- Foreign Key
ALTER TABLE s_agenda_plano_implantacao
ADD CONSTRAINT fk_oasis2_081 FOREIGN KEY (cd_projeto, cd_proposta)
REFERENCES s_plano_implantacao (cd_projeto,cd_proposta)
/
-- Foreign Key
ALTER TABLE s_analise_execucao_projeto
ADD CONSTRAINT fk_oasis2_080 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_analise_matriz_rastreab
ADD CONSTRAINT fk_oasis2_079 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_analise_medicao
ADD CONSTRAINT fk_oasis2_076 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE s_analise_medicao
ADD CONSTRAINT fk_oasis2_077 FOREIGN KEY (cd_medicao)
REFERENCES s_medicao (cd_medicao)
/
ALTER TABLE s_analise_medicao
ADD CONSTRAINT fk_oasis2_078 FOREIGN KEY (cd_box_inicio)
REFERENCES b_box_inicio (cd_box_inicio)
/
-- Foreign Key
ALTER TABLE s_analise_risco
ADD CONSTRAINT fk_oasis2_073 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
ALTER TABLE s_analise_risco
ADD CONSTRAINT fk_oasis2_074 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE s_analise_risco
ADD CONSTRAINT fk_oasis2_075 FOREIGN KEY (cd_item_risco, cd_etapa, cd_atividade)
REFERENCES b_item_risco (cd_item_risco,cd_etapa,cd_atividade)
/
-- Foreign Key
ALTER TABLE s_arquivo_pedido
ADD CONSTRAINT fk_oasis_212 FOREIGN KEY (cd_solicitacao_pedido, 
  cd_pergunta_pedido, cd_resposta_pedido)
REFERENCES a_solicitacao_resposta_pedido (cd_solicitacao_pedido,cd_pergunta_pedido,cd_resposta_pedido)
/
-- Foreign Key
ALTER TABLE s_ator
ADD CONSTRAINT fk_oasis2_072 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_base_conhecimento
ADD CONSTRAINT fk_oasis2_071 FOREIGN KEY (cd_area_conhecimento)
REFERENCES b_area_conhecimento (cd_area_conhecimento)
/
-- Foreign Key
ALTER TABLE s_baseline
ADD CONSTRAINT fk_oasis2_070 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_caso_de_uso
ADD CONSTRAINT fk_oasis2_069 FOREIGN KEY (cd_modulo, cd_projeto)
REFERENCES s_modulo (cd_modulo,cd_projeto)
/
-- Foreign Key
ALTER TABLE s_coluna
ADD CONSTRAINT fk_oasis2_067 FOREIGN KEY (tx_tabela_referencia, 
  cd_projeto_referencia)
REFERENCES s_tabela (tx_tabela,cd_projeto)
/
ALTER TABLE s_coluna
ADD CONSTRAINT fk_oasis2_068 FOREIGN KEY (tx_tabela, cd_projeto)
REFERENCES s_tabela (tx_tabela,cd_projeto)
/
-- Foreign Key
ALTER TABLE s_complemento
ADD CONSTRAINT fk_oasis2_066 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso)
REFERENCES s_caso_de_uso (cd_caso_de_uso,cd_projeto,cd_modulo,dt_versao_caso_de_uso)
/
-- Foreign Key
ALTER TABLE s_condicao
ADD CONSTRAINT fk_oasis2_065 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso)
REFERENCES s_caso_de_uso (cd_caso_de_uso,cd_projeto,cd_modulo,dt_versao_caso_de_uso)
/
-- Foreign Key
ALTER TABLE s_contato_empresa
ADD CONSTRAINT fk_oasis2_064 FOREIGN KEY (cd_empresa)
REFERENCES s_empresa (cd_empresa)
/
-- Foreign Key
ALTER TABLE s_contrato
ADD CONSTRAINT fk_oasis2_062 FOREIGN KEY (cd_empresa)
REFERENCES s_empresa (cd_empresa)
/
ALTER TABLE s_contrato
ADD CONSTRAINT fk_oasis2_063 FOREIGN KEY (cd_contato_empresa, cd_empresa)
REFERENCES s_contato_empresa (cd_contato_empresa,cd_empresa)
/
-- Foreign Key
ALTER TABLE s_demanda
ADD CONSTRAINT fk_oasis2_060 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, 
  cd_objeto)
REFERENCES s_solicitacao (ni_solicitacao,ni_ano_solicitacao,cd_objeto)
/
ALTER TABLE s_demanda
ADD CONSTRAINT fk_oasis2_061 FOREIGN KEY (cd_unidade)
REFERENCES b_unidade (cd_unidade)
/
-- Foreign Key
ALTER TABLE s_disponibilidade_servico
ADD CONSTRAINT fk_oasis2_059 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
-- Foreign Key
ALTER TABLE s_extrato_mensal
ADD CONSTRAINT fk_oasis2_058 FOREIGN KEY (cd_contrato)
REFERENCES s_contrato (cd_contrato)
/
-- Foreign Key
ALTER TABLE s_extrato_mensal_parcela
ADD CONSTRAINT fk_oasis2_056 FOREIGN KEY (ni_mes_extrato, ni_ano_extrato, 
  cd_contrato)
REFERENCES s_extrato_mensal (ni_mes_extrato,ni_ano_extrato,cd_contrato)
/
ALTER TABLE s_extrato_mensal_parcela
ADD CONSTRAINT fk_oasis2_057 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta)
REFERENCES s_parcela (cd_parcela,cd_projeto,cd_proposta)
/
-- Foreign Key
ALTER TABLE s_gerencia_qualidade
ADD CONSTRAINT fk_oasis2_054 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
ALTER TABLE s_gerencia_qualidade
ADD CONSTRAINT fk_oasis2_055 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
-- Foreign Key
ALTER TABLE s_hist_prop_sub_item_metrica
ADD CONSTRAINT fk_oasis2_052 FOREIGN KEY (cd_projeto, cd_proposta, 
  cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica)
REFERENCES a_proposta_sub_item_metrica (cd_projeto,cd_proposta,cd_item_metrica,cd_definicao_metrica,cd_sub_item_metrica)
/
ALTER TABLE s_hist_prop_sub_item_metrica
ADD CONSTRAINT fk_oasis2_053 FOREIGN KEY (dt_historico_proposta, cd_projeto, 
  cd_proposta)
REFERENCES s_historico_proposta (dt_historico_proposta,cd_projeto,cd_proposta)
/
-- Foreign Key
ALTER TABLE s_historico
ADD CONSTRAINT fk_oasis2_051 FOREIGN KEY (cd_atividade, cd_etapa)
REFERENCES b_atividade (cd_atividade,cd_etapa)
/
-- Foreign Key
ALTER TABLE s_historico_execucao_demanda
ADD CONSTRAINT fk_oasis2_050 FOREIGN KEY (cd_demanda, cd_profissional, 
  cd_nivel_servico)
REFERENCES a_demanda_prof_nivel_servico (cd_demanda,cd_profissional,cd_nivel_servico)
/
-- Foreign Key
ALTER TABLE s_historico_pedido
ADD CONSTRAINT fk_oasis_209 FOREIGN KEY (cd_solicitacao_historico)
REFERENCES s_solicitacao_pedido (cd_solicitacao_pedido)
/
-- Foreign Key
ALTER TABLE s_historico_projeto_continuado
ADD CONSTRAINT fk_oasis2_048 FOREIGN KEY (cd_modulo_continuado, cd_objeto, 
  cd_projeto_continuado)
REFERENCES s_modulo_continuado (cd_modulo_continuado,cd_objeto,cd_projeto_continuado)
/
ALTER TABLE s_historico_projeto_continuado
ADD CONSTRAINT fk_oasis2_049 FOREIGN KEY (cd_atividade, cd_etapa)
REFERENCES b_atividade (cd_atividade,cd_etapa)
/
-- Foreign Key
ALTER TABLE s_historico_proposta
ADD CONSTRAINT fk_oasis2_047 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
-- Foreign Key
ALTER TABLE s_historico_proposta_metrica
ADD CONSTRAINT fk_oasis2_045 FOREIGN KEY (dt_historico_proposta, cd_projeto, 
  cd_proposta)
REFERENCES s_historico_proposta (dt_historico_proposta,cd_projeto,cd_proposta)
/
ALTER TABLE s_historico_proposta_metrica
ADD CONSTRAINT fk_oasis2_046 FOREIGN KEY (cd_projeto, cd_proposta, 
  cd_definicao_metrica)
REFERENCES a_proposta_definicao_metrica (cd_projeto,cd_proposta,cd_definicao_metrica)
/
-- Foreign Key
ALTER TABLE s_historico_proposta_parcela
ADD CONSTRAINT fk_oasis2_044 FOREIGN KEY (dt_historico_proposta, cd_projeto, 
  cd_proposta)
REFERENCES s_historico_proposta (dt_historico_proposta,cd_projeto,cd_proposta)
/
-- Foreign Key
ALTER TABLE s_historico_proposta_produto
ADD CONSTRAINT fk_oasis2_043 FOREIGN KEY (cd_proposta, cd_projeto, 
  dt_historico_proposta, cd_historico_proposta_parcela)
REFERENCES s_historico_proposta_parcela (cd_proposta,cd_projeto,dt_historico_proposta,cd_historico_proposta_parcela)
/
ALTER TABLE s_historico_proposta_produto
ADD CONSTRAINT fk_oasis2_042 FOREIGN KEY (cd_tipo_produto)
REFERENCES b_tipo_produto (cd_tipo_produto)
/
-- Foreign Key
ALTER TABLE s_hw_inventario
ADD CONSTRAINT fk_oasis2_041 FOREIGN KEY (cd_modulo_continuado, cd_objeto, 
  cd_projeto_continuado)
REFERENCES s_modulo_continuado (cd_modulo_continuado,cd_objeto,cd_projeto_continuado)
/
-- Foreign Key
ALTER TABLE s_interacao
ADD CONSTRAINT fk_oasis2_039 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso)
REFERENCES s_caso_de_uso (cd_caso_de_uso,cd_projeto,cd_modulo,dt_versao_caso_de_uso)
/
ALTER TABLE s_interacao
ADD CONSTRAINT fk_oasis2_040 FOREIGN KEY (cd_ator)
REFERENCES s_ator (cd_ator)
/
-- Foreign Key
ALTER TABLE s_mensageria
ADD CONSTRAINT fk_oasis2_037 FOREIGN KEY (cd_perfil)
REFERENCES b_perfil (cd_perfil)
/
ALTER TABLE s_mensageria
ADD CONSTRAINT fk_oasis2_038 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
-- Foreign Key
ALTER TABLE s_modulo
ADD CONSTRAINT fk_oasis2_035 FOREIGN KEY (cd_status)
REFERENCES b_status (cd_status)
/
ALTER TABLE s_modulo
ADD CONSTRAINT fk_oasis2_036 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_modulo_continuado
ADD CONSTRAINT fk_oasis2_034 FOREIGN KEY (cd_projeto_continuado, cd_objeto)
REFERENCES s_projeto_continuado (cd_projeto_continuado,cd_objeto)
/
-- Foreign Key
ALTER TABLE s_objeto_contrato
ADD CONSTRAINT fk_oasis2_033 FOREIGN KEY (cd_contrato)
REFERENCES s_contrato (cd_contrato)
/
-- Foreign Key
ALTER TABLE s_parcela
ADD CONSTRAINT fk_oasis2_032 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
-- Foreign Key
ALTER TABLE s_penalizacao
ADD CONSTRAINT fk_oasis2_031 FOREIGN KEY (cd_penalidade, cd_contrato)
REFERENCES b_penalidade (cd_penalidade,cd_contrato)
/
-- Foreign Key
ALTER TABLE s_plano_implantacao
ADD CONSTRAINT fk_oasis2_030 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
-- Foreign Key
ALTER TABLE s_pre_demanda
ADD CONSTRAINT fk_oasis2_027 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, 
  cd_objeto_receptor)
REFERENCES s_solicitacao (ni_solicitacao,ni_ano_solicitacao,cd_objeto)
/
ALTER TABLE s_pre_demanda
ADD CONSTRAINT fk_oasis2_028 FOREIGN KEY (cd_objeto_emissor)
REFERENCES s_objeto_contrato (cd_objeto)
/
ALTER TABLE s_pre_demanda
ADD CONSTRAINT fk_oasis2_029 FOREIGN KEY (cd_unidade)
REFERENCES b_unidade (cd_unidade)
/
-- Foreign Key
ALTER TABLE s_pre_projeto_evolutivo
ADD CONSTRAINT fk_oasis2_026 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_previsao_projeto_diario
ADD CONSTRAINT fk_oasis2_025 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_processamento_parcela
ADD CONSTRAINT fk_oasis2_024 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta)
REFERENCES s_parcela (cd_parcela,cd_projeto,cd_proposta)
/
-- Foreign Key
ALTER TABLE s_processamento_proposta
ADD CONSTRAINT fk_oasis2_023 FOREIGN KEY (cd_proposta, cd_projeto)
REFERENCES s_proposta (cd_proposta,cd_projeto)
/
-- Foreign Key
ALTER TABLE s_produto_parcela
ADD CONSTRAINT fk_oasis2_021 FOREIGN KEY (cd_tipo_produto)
REFERENCES b_tipo_produto (cd_tipo_produto)
/
ALTER TABLE s_produto_parcela
ADD CONSTRAINT fk_oasis2_022 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta)
REFERENCES s_parcela (cd_parcela,cd_projeto,cd_proposta)
/
-- Foreign Key
ALTER TABLE s_profissional
ADD CONSTRAINT fk_oasis2_018 FOREIGN KEY (cd_relacao_contratual)
REFERENCES b_relacao_contratual (cd_relacao_contratual)
/
ALTER TABLE s_profissional
ADD CONSTRAINT fk_oasis2_019 FOREIGN KEY (cd_perfil)
REFERENCES b_perfil (cd_perfil)
/
ALTER TABLE s_profissional
ADD CONSTRAINT fk_oasis2_020 FOREIGN KEY (cd_empresa)
REFERENCES s_empresa (cd_empresa)
/
-- Foreign Key
ALTER TABLE s_projeto
ADD CONSTRAINT fk_oasis2_015 FOREIGN KEY (cd_unidade)
REFERENCES b_unidade (cd_unidade)
/
ALTER TABLE s_projeto
ADD CONSTRAINT fk_oasis2_016 FOREIGN KEY (cd_status)
REFERENCES b_status (cd_status)
/
ALTER TABLE s_projeto
ADD CONSTRAINT fk_oasis2_017 FOREIGN KEY (cd_profissional_gerente)
REFERENCES s_profissional (cd_profissional)
/
-- Foreign Key
ALTER TABLE s_projeto_continuado
ADD CONSTRAINT fk_oasis2_014 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
-- Foreign Key
ALTER TABLE s_projeto_previsto
ADD CONSTRAINT fk_oasis2_012 FOREIGN KEY (cd_unidade)
REFERENCES b_unidade (cd_unidade)
/
ALTER TABLE s_projeto_previsto
ADD CONSTRAINT fk_oasis2_013 FOREIGN KEY (cd_contrato)
REFERENCES s_contrato (cd_contrato)
/
-- Foreign Key
ALTER TABLE s_proposta
ADD CONSTRAINT fk_oasis2_010 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, 
  cd_objeto)
REFERENCES s_solicitacao (ni_solicitacao,ni_ano_solicitacao,cd_objeto)
/
ALTER TABLE s_proposta
ADD CONSTRAINT fk_oasis2_011 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_regra_negocio
ADD CONSTRAINT fk_oasis2_009 FOREIGN KEY (cd_projeto_regra_negocio)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_requisito
ADD CONSTRAINT fk_oasis2_008 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_reuniao
ADD CONSTRAINT fk_oasis2_007 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_situacao_projeto
ADD CONSTRAINT fk_oasis2_006 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_solicitacao
ADD CONSTRAINT fk_oasis2_003 FOREIGN KEY (cd_profissional)
REFERENCES s_profissional (cd_profissional)
/
ALTER TABLE s_solicitacao
ADD CONSTRAINT fk_oasis2_004 FOREIGN KEY (cd_objeto)
REFERENCES s_objeto_contrato (cd_objeto)
/
ALTER TABLE s_solicitacao
ADD CONSTRAINT fk_oasis2_005 FOREIGN KEY (cd_unidade)
REFERENCES b_unidade (cd_unidade)
/
-- Foreign Key
ALTER TABLE s_solicitacao_pedido
ADD CONSTRAINT fk_oasis_208 FOREIGN KEY (cd_usuario_pedido)
REFERENCES s_usuario_pedido (cd_usuario_pedido)
/
ALTER TABLE s_solicitacao_pedido
ADD CONSTRAINT fk_oasis_207 FOREIGN KEY (cd_unidade_pedido)
REFERENCES b_unidade (cd_unidade)
/
-- Foreign Key
ALTER TABLE s_tabela
ADD CONSTRAINT fk_oasis2_002 FOREIGN KEY (cd_projeto)
REFERENCES s_projeto (cd_projeto)
/
-- Foreign Key
ALTER TABLE s_usuario_pedido
ADD CONSTRAINT fk_oasis2_001 FOREIGN KEY (cd_unidade_usuario)
REFERENCES b_unidade (cd_unidade)
/
-- End of DDL script for Foreign Key(s)
