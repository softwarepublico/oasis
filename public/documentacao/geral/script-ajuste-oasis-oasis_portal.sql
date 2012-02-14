-- CRIAÇÃO DAS TABELAS NOVAS
CREATE TABLE oasis_portal.b_rotina
(
  cd_rotina numeric(8) NOT NULL,
  cd_area_atuacao_ti numeric(8) NOT NULL,
  tx_rotina character varying(200),
  tx_hora_inicio_rotina character varying(8),
  st_periodicidade_rotina character(1),
  ni_prazo_execucao_rotina numeric(8),
  id numeric(8),
  CONSTRAINT pk_oasis_160 PRIMARY KEY (cd_rotina),
  CONSTRAINT fk_oasis_208 FOREIGN KEY (cd_area_atuacao_ti)
      REFERENCES oasis_portal.b_area_atuacao_ti (cd_area_atuacao_ti) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);


CREATE TABLE oasis_portal.b_status_atendimento
(
  cd_status_atendimento numeric(8) NOT NULL,
  tx_status_atendimento character varying(100),
  tx_rgb_status_atendimento character varying(8),
  st_status_atendimento character varying(1),
  ni_percent_tempo_resposta_ini numeric(3),
  ni_percent_tempo_resposta_fim numeric(3),
  id numeric(8),
  CONSTRAINT pk_oasis_161 PRIMARY KEY (cd_status_atendimento)
);

CREATE TABLE oasis_portal.a_objeto_contrato_rotina
(
  cd_objeto numeric(8) NOT NULL, -- Código sequencial identificador do objeto do contrato
  cd_rotina numeric(8) NOT NULL, -- Código sequencial identificador da rotina
  id numeric(8), -- Este campo armazena o código do...
  CONSTRAINT pk_oasis_162 PRIMARY KEY (cd_objeto, cd_rotina),
  CONSTRAINT fk_oasis_208 FOREIGN KEY (cd_objeto)
      REFERENCES oasis_portal.s_objeto_contrato (cd_objeto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_oasis_209 FOREIGN KEY (cd_rotina)
      REFERENCES oasis_portal.b_rotina (cd_rotina) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

COMMENT ON TABLE oasis_portal.a_objeto_contrato_rotina IS 'Essa tabela armazena os dados de associação das
tabelas  s_objeto_contrato e b_rotina.';
COMMENT ON COLUMN oasis_portal.a_objeto_contrato_rotina.cd_objeto IS 'Código sequencial identificador do objeto do contrato';
COMMENT ON COLUMN oasis_portal.a_objeto_contrato_rotina.cd_rotina IS 'Código sequencial identificador da rotina';
COMMENT ON COLUMN oasis_portal.a_objeto_contrato_rotina.id IS 'Este campo armazena o código do
profissional que realizou a última
gravação ou atualização.';

CREATE TABLE oasis_portal.a_rotina_profissional
(
  cd_objeto numeric(8) NOT NULL, -- Código sequencial identificador do objeto do contrato
  cd_profissional numeric(8) NOT NULL, -- Código sequencial identificador do profissional
  cd_rotina numeric(8) NOT NULL, -- Código sequencial identificador da...
  st_inativa_rotina_profissional character(1), -- Este campo armazena a indicação de...
  id numeric(8), -- Este campo armazena o código do...
  CONSTRAINT pk_oasis_163 PRIMARY KEY (cd_objeto, cd_profissional, cd_rotina),
  CONSTRAINT fk_oasis_210 FOREIGN KEY (cd_profissional, cd_objeto)
      REFERENCES oasis_portal.a_profissional_objeto_contrato (cd_profissional, cd_objeto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_oasis_211 FOREIGN KEY (cd_rotina, cd_objeto)
      REFERENCES oasis_portal.a_objeto_contrato_rotina (cd_rotina, cd_objeto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

COMMENT ON TABLE oasis_portal.a_rotina_profissional IS 'Essa tabela armazena os dados de associação das
tabelas s_profissional e b_rotina.';
COMMENT ON COLUMN oasis_portal.a_rotina_profissional.cd_objeto IS 'Código sequencial identificador do objeto do contrato';
COMMENT ON COLUMN oasis_portal.a_rotina_profissional.cd_profissional IS 'Código sequencial identificador do profissional';
COMMENT ON COLUMN oasis_portal.a_rotina_profissional.cd_rotina IS 'Código sequencial identificador da
rotina';
COMMENT ON COLUMN oasis_portal.a_rotina_profissional.st_inativa_rotina_profissional IS 'Este campo armazena a indicação de
inativação da rotina para o profissional';
COMMENT ON COLUMN oasis_portal.a_rotina_profissional.id IS 'Este campo armazena o código do
profissional que realizou a última
gravação ou atualização.';

CREATE TABLE oasis_portal.s_execucao_rotina
(
  dt_execucao_rotina date NOT NULL,
  cd_profissional numeric(8) NOT NULL,
  cd_objeto numeric(8) NOT NULL,
  cd_rotina numeric(8) NOT NULL,
  tx_hora_execucao_rotina time without time zone,
  st_fechamento_execucao_rotina character(1),
  dt_just_execucao_rotina timestamp without time zone,
  tx_just_execucao_rotina character varying(4000),
  st_historico character(1),
  id numeric(8),
  CONSTRAINT pk_oasis_164 PRIMARY KEY (dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina),
  CONSTRAINT fk_oasis_212 FOREIGN KEY (cd_rotina, cd_objeto, cd_profissional)
      REFERENCES oasis_portal.a_rotina_profissional (cd_rotina, cd_objeto, cd_profissional) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

CREATE TABLE oasis_portal.s_historico_execucao_rotina
(
  dt_historico_execucao_rotina timestamp without time zone NOT NULL,
  cd_rotina numeric(8) NOT NULL,
  cd_objeto numeric(8) NOT NULL,
  cd_profissional numeric(8) NOT NULL,
  dt_execucao_rotina date NOT NULL,
  tx_historico_execucao_rotina character varying(4000),
  dt_historico_rotina timestamp without time zone,
  id numeric(8),
  CONSTRAINT pk_oasis_165 PRIMARY KEY (dt_historico_execucao_rotina, cd_rotina, cd_objeto, cd_profissional, dt_execucao_rotina),
  CONSTRAINT fk_oasis_213 FOREIGN KEY (dt_execucao_rotina, cd_rotina, cd_objeto, cd_profissional)
      REFERENCES oasis_portal.s_execucao_rotina (dt_execucao_rotina, cd_rotina, cd_objeto, cd_profissional) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

CREATE TABLE oasis_portal.a_documentacao_contrato
(
  dt_documentacao_contrato timestamp without time zone NOT NULL, -- Esse campo armazena a data do upload da documentação do contrato
  cd_contrato numeric NOT NULL, -- Código sequencial identificador do contrato
  cd_tipo_documentacao numeric NOT NULL, -- Código sequencial identificador do tipo de documento
  tx_arq_documentacao_contrato character varying, -- Este campo armazena o nome do arquivo que está gravado no diretório do OASIS que foi realizado o upload.
  tx_nome_arquivo character varying(100), -- Este campo armazena o nome do arquivo que foi indicado para ser feito o upload
  id numeric(8), -- Este campo armazena o código do profissional que realizou a última gravação ou atualização
  CONSTRAINT pk_oasis_159 PRIMARY KEY (dt_documentacao_contrato, cd_contrato, cd_tipo_documentacao),
  CONSTRAINT fk_oasis_209 FOREIGN KEY (cd_tipo_documentacao)
      REFERENCES oasis_portal.b_tipo_documentacao (cd_tipo_documentacao) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_oasis_210 FOREIGN KEY (cd_contrato)
      REFERENCES oasis_portal.s_contrato (cd_contrato) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

COMMENT ON TABLE oasis_portal.a_documentacao_contrato IS 'Essa tabela armazena os dados de documentação de contrato. Permite estabelecer o tipo de documentação e os tipo s de arquivos permitidos para a tipo de documentação.';
COMMENT ON COLUMN oasis_portal.a_documentacao_contrato.dt_documentacao_contrato IS 'Esse campo armazena a data do upload da documentação do contrato';
COMMENT ON COLUMN oasis_portal.a_documentacao_contrato.cd_contrato IS 'Código sequencial identificador do contrato';
COMMENT ON COLUMN oasis_portal.a_documentacao_contrato.cd_tipo_documentacao IS 'Código sequencial identificador do tipo de documento';
COMMENT ON COLUMN oasis_portal.a_documentacao_contrato.tx_arq_documentacao_contrato IS 'Este campo armazena o nome do arquivo que está gravado no diretório do OASIS que foi realizado o upload.';
COMMENT ON COLUMN oasis_portal.a_documentacao_contrato.tx_nome_arquivo IS 'Este campo armazena o nome do arquivo que foi indicado para ser feito o upload';
COMMENT ON COLUMN oasis_portal.a_documentacao_contrato.id IS 'Este campo armazena o código do profissional que realizou a última gravação ou atualização';

-- INSERTS PARA A TABELA B_STATUS_ATENDIMENTO
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (1, 'Altíssima', '030303', 'P', NULL, NULL, 0);
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (2, 'Alta', 'f70000', 'P', NULL, NULL, 0);
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (3, 'Média', 'f5f500', 'P', NULL, NULL, 0);
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (5, 'Baixa', '00f731', 'P', NULL, NULL, 0);
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (6, 'Baixa', '00f541', 'T', 0, 25, 0);
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (7, 'Média', 'edf508', 'T', 26, 50, 0);
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (8, 'Alta', 'f00000', 'T', 51, 75, 0);
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (4, 'Altíssima', '521252', 'T', 76, 100, 0);
INSERT INTO oasis_portal.b_status_atendimento (cd_status_atendimento, tx_status_atendimento, tx_rgb_status_atendimento, st_status_atendimento, ni_percent_tempo_resposta_ini, ni_percent_tempo_resposta_fim, id) VALUES (9, 'Esgotado', '000000', 'T', 101, 999, 0);


-- CRIAÇÃO DAS COLUNAS NOVAS
alter table oasis_portal.s_demanda                    add column cd_status_atendimento     numeric(8);
alter table oasis_portal.a_demanda_prof_nivel_servico add column tx_nova_obs_nivel_servico character varying(4000);
alter table oasis_portal.a_demanda_prof_nivel_servico add column st_nova_obs_nivel_servico character(1);
alter table oasis_portal.s_config_banco_de_dados      add column tx_port character varying(100); -- CONFERIR!!!!

COMMENT ON COLUMN oasis_portal.a_demanda_prof_nivel_servico.st_nova_obs_nivel_servico IS 'Este campo armazena a indicação de nova observação de nível de serviço';
COMMENT ON COLUMN oasis_portal.a_demanda_prof_nivel_servico.tx_nova_obs_nivel_servico IS 'Este campo armazena a nova observação de nível de serviço';


-- ALTERAÇÃO NO TIPO DE DADOS DE COLUNA EXISTENTE
ALTER TABLE oasis_portal.s_ocorrencia_administrativa ALTER COLUMN dt_ocorrencia_administrativa TYPE timestamp without time zone;


-- CRIAÇÃO DAS CHAVES ESTRANGEIRAS QUE NÃO FORAM NO SCRIPT ORIGINAL DA VERSÃO DO PORTAL
alter table oasis_portal.a_contrato_evento add constraint fk_oasis_217 foreign key (cd_contrato) references oasis_portal.s_contrato (cd_contrato);
alter table oasis_portal.a_contrato_evento add constraint fk_oasis_218 foreign key (cd_evento) references oasis_portal.b_evento (cd_evento);
alter table oasis_portal.b_item_grupo_fator add constraint fk_oasis_219 foreign key (cd_grupo_fator) references oasis_portal.b_grupo_fator (cd_grupo_fator);
alter table oasis_portal.b_msg_email add constraint fk_oasis_220 foreign key (cd_menu) references oasis_portal.b_menu (cd_menu);
alter table oasis_portal.s_acompanhamento_proposta add constraint fk_oasis_221 foreign key (cd_projeto, cd_proposta) references oasis_portal.s_proposta (cd_projeto, cd_proposta);
alter table oasis_portal.s_config_banco_de_dados add constraint fk_oasis_222 foreign key (cd_projeto) references oasis_portal.s_projeto (cd_projeto);
alter table oasis_portal.s_log add constraint fk_oasis_223 foreign key (cd_profissional) references oasis_portal.s_profissional (cd_profissional);
alter table oasis_portal.s_ocorrencia_administrativa add constraint fk_oasis_224 foreign key (cd_contrato, cd_evento) references oasis_portal.a_contrato_evento (cd_contrato, cd_evento);
alter table oasis_portal.s_pre_projeto add constraint fk_oasis_225 foreign key (cd_unidade) references oasis_portal.b_unidade (cd_unidade);
alter table oasis_portal.s_pre_projeto add constraint fk_oasis_226 foreign key (cd_gerente_pre_projeto) references oasis_portal.s_profissional (cd_profissional);
alter table oasis_portal.s_demanda         add constraint fk_oasis_227 foreign key (cd_status_atendimento) references oasis_portal.b_status_atendimento (cd_status_atendimento);


-- UPDATE NA TABELA S_DEMANDA DEVIDO À CRIAÇÃO DE NOVA FUNCIONALIDADE E COLUNA NA TABELA
update
oasis_portal.s_demanda
set
cd_status_atendimento = case st_prioridade_demanda
when 'A' then 2
when 'B' then 5
when 'L' then 1
when 'M' then 3
end
where st_prioridade_demanda is not null

-- EXCLUSÃO DE CAMPO SUBSTIUÍDO NA TABELA S_DEMANDA
alter table oasis_portal.s_demanda      drop column st_prioridade_demanda;

-- INSERT DOS MENUS NOVOS REFERENTES ÀS NOVAS FUNCIONALIDADES CRIADAS
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (297, 119, 'L_MENU_ASSOCIAR_ROTINA_AO_OBJETO_DO_CONTRATO', 0, 'associar-rotina-objeto-contrato', NULL, NULL, 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (293, 14, 'L_MENU_STATUS_DE_ATENDIMENTO', 0, 'status-atendimento', NULL, NULL, 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (300, NULL, 'L_MENU_EXECUTAR_ROTINA', 0, 'execucao-rotina', NULL, NULL, 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (296, 119, 'L_MENU_ROTINA', 0, 'rotina', NULL, NULL, 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (302, 252, 'L_MENU_ALOCACAO_DE_RECURSOS_DE_CONTRATO', 0, 'alocacao-recurso-contrato', NULL, 'relatorioProjeto', 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (301, 255, 'L_MENU_RELATORIO_ROTINA', 0, 'rotina', NULL, 'relatorioDemanda', 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (303, 251, 'L_MENU_CHECKLIST_DE_PROJETO', 0, 'checklist-projeto', NULL, 'relatorioProjeto', 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (299, NULL, 'L_MENU_GERENCIAMENTO_ROTINA', 0, 'gerenciamento-rotina', NULL, NULL, 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (291, 119, 'L_MENU_DOCUMENTACAO_DE_CONTRATO', 0, 'documentacao-contrato', NULL, NULL, 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (294, 255, 'L_MENU_DEMANDAS_EM_ABERTO', 0, 'demanda-em-aberto', NULL, 'relatorioDemanda', 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (295, 255, 'L_MENU_DEMANDA_DETALHE', 0, 'demanda-detalhe', NULL, 'relatorioDemanda', 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (304, NULL, 'L_MENU_ACOMPANHAMENTO_INTERNO', 0, 'acompanhamento-fiscalizacao', NULL, NULL, 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (298, 250, 'L_MENU_HISTORICO_DE_PROPOSTA', 0, 'historico-proposta', NULL, 'relatorioProjeto', 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (292, 14, 'L_MENU_PRIORIDADE', 0, 'prioridade', NULL, NULL, 0);
INSERT INTO oasis_portal.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (305, 304, 'L_MENU_DOCUMENTACAO_PROJETO', 0, 'acompanhamento-fiscalizacao-documentacao', NULL, NULL, 0);

-- INSERT DOS MENUS NOVOS E ALGUNS OUTROS PARA O USUÁRIO ADMINISTRADOR
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (274, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (271, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (293, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (266, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (303, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (276, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (299, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (292, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (304, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (264, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (302, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (290, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (294, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (272, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (275, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (265, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (305, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (267, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (270, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (268, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (269, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (301, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (300, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (298, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (296, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (291, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (297, 0, 0, 0);
INSERT INTO oasis_portal.a_profissional_menu (cd_menu, cd_profissional, cd_objeto, id) VALUES (295, 0, 0, 0);

-- INCLUSÃO DE NOVO PERFIL
INSERT INTO oasis_portal.b_perfil (cd_perfil, tx_perfil, id) VALUES (5, 'Líder de Projeto', 0);

-- MENUS NOVOS PARA OS PERFIS CADASTRADOS
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 150, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 240, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 210, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 22, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 262, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 74, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 161, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 180, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 179, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 217, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 226, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 227, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 260, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 50, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 204, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 49, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 159, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 252, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 219, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 20, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 89, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 249, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 154, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 254, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 205, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 223, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 157, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 85, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 235, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 21, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 168, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 86, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 155, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 149, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 216, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 91, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 215, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 203, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 224, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 261, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 170, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 225, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 19, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 277, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 133, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 139, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 237, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 146, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 214, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 169, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 221, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 10, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 238, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 166, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 52, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 115, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 218, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 220, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 34, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 138, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 206, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 207, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 162, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 73, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 158, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 142, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 171, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 263, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 175, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 45, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 151, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 258, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 153, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 239, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 273, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 152, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 222, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 163, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 202, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 143, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 156, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (1, 157, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 291, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 218, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 238, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 166, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 214, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 169, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 221, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 250, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 237, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 146, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 170, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 225, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 15, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 65, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 17, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 35, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 277, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 203, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 224, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 261, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 2, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 149, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 216, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 70, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 215, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 163, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 202, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 143, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 156, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 152, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 63, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 222, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 123, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 93, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 153, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 239, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 273, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 291, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 105, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 151, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 258, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 263, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 175, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 158, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 142, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 171, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 34, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 206, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 207, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 162, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 220, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 204, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 159, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 252, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 219, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 99, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 226, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 107, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 227, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 260, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 179, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 108, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 217, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 180, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 262, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 161, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 6, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 210, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 150, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 240, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 16, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 155, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 168, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 251, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 235, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 157, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 254, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 106, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 205, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 223, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 89, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 249, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 122, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 154, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (2, 253, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (3, 260, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (3, 255, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (3, 145, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (3, 144, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (3, 154, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (3, 251, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (3, 3, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (3, 11, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (4, 33, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (4, 255, 'D', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (4, 145, 'D', 0);

-- MENUS PARA O NOVO PERFIL CRIADO
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 157, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 16, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 29, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 90, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 92, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 68, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 242, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 200, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 100, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 48, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 42, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 35, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 116, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 140, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 37, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 57, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 208, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 54, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 252, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 245, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 152, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 111, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 199, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 32, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 27, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 30, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 218, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 215, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 170, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 146, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 120, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 241, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 38, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 253, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 9, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 96, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 254, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 31, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 106, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 81, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 260, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 227, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 107, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 71, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 161, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 78, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 105, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 55, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 93, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 153, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 195, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 41, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 209, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 53, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 175, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 43, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 250, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 244, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 261, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 25, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 173, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 197, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 249, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 154, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 205, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 223, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 8, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 36, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 127, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 165, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 61, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 94, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 172, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 24, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 26, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 169, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 214, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 166, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 198, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 243, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 149, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 15, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 17, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 131, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 101, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 251, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 168, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 230, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 47, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 240, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 246, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 213, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 33, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 62, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 56, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 23, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 4, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 7, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 216, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 91, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 196, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 229, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 39, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 217, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 174, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 3, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 44, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 210, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 1, 'P', 0);
INSERT INTO oasis_portal.a_perfil_menu_sistema (cd_perfil, cd_menu, st_perfil_menu, id) VALUES (5, 239, 'P', 0);


-- CONFIGURAÇÃO DE MENUS NOVOS PARA O ADMINISTRADOR e OBJETO ADMINISTRADOR
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 71, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 103, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 80, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 3, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 44, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 6, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 33, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 74, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 8, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 22, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 97, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 62, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 36, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 1, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 78, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 84, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 81, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 92, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 66, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 108, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 107, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 76, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 68, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 99, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 60, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 58, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 49, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 159, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 50, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 79, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 29, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 38, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 9, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 11, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 96, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 88, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 122, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 47, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 39, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 20, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 89, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 14, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 106, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 31, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 54, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 90, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 125, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 131, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 82, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 25, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 85, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 129, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 21, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 136, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 57, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 134, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 37, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 94, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 101, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 135, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 119, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 120, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 86, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 16, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 18, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 130, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 70, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 42, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 91, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 2, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 19, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 17, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 35, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 65, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 15, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 59, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 133, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 139, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 28, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 116, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 27, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 72, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 10, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 30, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 7, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 118, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 64, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 52, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 115, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 126, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 98, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 100, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 113, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 138, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 34, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 24, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 111, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 41, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 26, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 117, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 23, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 73, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 48, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 43, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 32, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 5, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 4, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 53, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 83, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 87, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 56, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 45, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 127, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 105, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 95, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 55, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 93, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 61, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 121, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 109, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 123, 0, 0);
INSERT INTO oasis_portal.a_perfil_menu (cd_perfil, cd_menu, cd_objeto, id) VALUES (0, 63, 0, 0);



