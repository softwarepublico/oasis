
/*
OBS: Antes de rodar o script substituir:
     K_SCHEMA pelo esquema de seu banco e 
     K_USER pelo usuário de seu banco. 
*/

/*
  Campos Incluídos nas Tabelas

	Tabela b_tipo_conhecimento 		-> campo	st_para_profissionais character(1)
	Tabela s_config_banco_de_dados 		-> campo 	tx_port character varying(100)
	Tabela b_item_inventario 		-> campo	cd_area_atuacao_ti numeric(8,0) NOT NULL
	Tabela s_demanda 			-> campo	cd_status_atendimento numeric(8,0)
	Tabela a_documentacao_projeto 		-> campo	st_doc_acompanhamento character(1)
	Tabela a_demanda_prof_nivel_servico 	-> campo 	tx_nova_obs_nivel_servico character varying(4000)
	Tabela a_demanda_prof_nivel_servico 	-> campo  	st_nova_obs_nivel_servico character(1)
	Tabela b_item_parecer_tecnico 		-> campo	tx_descricao character varying(4000)

*/

	ALTER TABLE K_SCHEMA.b_tipo_conhecimento     	  ADD COLUMN st_para_profissionais character(1);
	ALTER TABLE K_SCHEMA.s_config_banco_de_dados 	  ADD COLUMN tx_port character varying(100);
	ALTER TABLE K_SCHEMA.b_item_inventario       	  ADD COLUMN cd_area_atuacao_ti numeric(8,0) NOT NULL;
	ALTER TABLE K_SCHEMA.s_demanda		    	  ADD COLUMN cd_status_atendimento numeric(8,0);
	ALTER TABLE K_SCHEMA.a_documentacao_projeto  	  ADD COLUMN st_doc_acompanhamento character(1);
	ALTER TABLE K_SCHEMA.a_demanda_prof_nivel_servico ADD COLUMN tx_nova_obs_nivel_servico character varying(4000);
	ALTER TABLE K_SCHEMA.a_demanda_prof_nivel_servico ADD COLUMN st_nova_obs_nivel_servico character(1);
	ALTER TABLE K_SCHEMA.b_item_parecer_tecnico  	  ADD COLUMN tx_descricao character varying(4000);


/*
   Diferença de colunas
	
	"s_ocorrencia_administrativa";"dt_ocorrencia_administrativa";"timestamp without time zone";;;"N"
	"b_item_inventario";"tx_item_inventario";"character varying";100;;"Y"

*/









/*
  TABELAS INCLUÍDAS NA VERSÃO 2.10 DO OASIS 

	"b_status_atendimento"
	"a_rotina_profissional"
	"a_form_inventario"
	"a_item_inventariado"
	"s_execucao_rotina"
	"b_subitem_inventario"
	"b_rotina"
	"a_documentacao_contrato"
	"a_objeto_contrato_rotina"
	"s_historico_execucao_rotina"
	"a_doc_projeto_continuo"
	"s_inventario"

*/

CREATE TABLE b_status_atendimento (
    cd_status_atendimento numeric(8,0) NOT NULL,
    tx_status_atendimento character varying(100),
    tx_rgb_status_atendimento character varying(8),
    st_status_atendimento character varying(1),
    ni_percent_tempo_resposta_ini numeric(3,0),
    ni_percent_tempo_resposta_fim numeric(3,0),
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.b_status_atendimento OWNER TO K_USER;

CREATE TABLE a_rotina_profissional (
    cd_objeto numeric(8,0) NOT NULL,
    cd_profissional numeric(8,0) NOT NULL,
    cd_rotina numeric(8,0) NOT NULL,
    st_inativa_rotina_profissional character(1),
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.a_rotina_profissional OWNER TO K_USER;

CREATE TABLE a_form_inventario (
    cd_inventario numeric(8,0) NOT NULL,
    cd_item_inventario numeric(8,0) NOT NULL,
    cd_item_inventariado numeric(8,0) NOT NULL,
    cd_subitem_inventario numeric(8,0) NOT NULL,
    tx_valor_subitem_inventario character varying(4000),
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.a_form_inventario OWNER TO K_USER;

CREATE TABLE a_item_inventariado (
    cd_item_inventariado numeric(8,0) NOT NULL,
    cd_inventario numeric(8,0) NOT NULL,
    cd_item_inventario numeric(8,0) NOT NULL,
    tx_item_inventariado character varying(100),
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.a_item_inventariado OWNER TO K_USER;

CREATE TABLE s_execucao_rotina (
    dt_execucao_rotina date NOT NULL,
    cd_profissional numeric(8,0) NOT NULL,
    cd_objeto numeric(8,0) NOT NULL,
    cd_rotina numeric(8,0) NOT NULL,
    st_fechamento_execucao_rotina character(1),
    dt_just_execucao_rotina timestamp without time zone,
    tx_just_execucao_rotina character varying(4000),
    st_historico character(1),
    id numeric(8,0),
    tx_hora_execucao_rotina character varying(8)
);

ALTER TABLE K_SCHEMA.s_execucao_rotina OWNER TO K_USER;

CREATE TABLE b_subitem_inventario (
    cd_item_inventario numeric(8,0) NOT NULL,
    cd_subitem_inventario numeric(8,0) NOT NULL,
    tx_subitem_inventario character varying(100),
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.b_subitem_inventario OWNER TO K_USER;

CREATE TABLE b_rotina (
    cd_rotina numeric(8,0) NOT NULL,
    cd_area_atuacao_ti numeric(8,0) NOT NULL,
    tx_rotina character varying(200),
    tx_hora_inicio_rotina character varying(8),
    st_periodicidade_rotina character(1),
    ni_prazo_execucao_rotina numeric(8,0),
    id numeric(8,0),
    ni_dia_semana_rotina numeric(1,0),
    ni_dia_mes_rotina numeric(2,0),
    st_rotina_inativa character(1)
);

ALTER TABLE K_SCHEMA.b_rotina OWNER TO K_USER;

CREATE TABLE a_documentacao_contrato (
    dt_documentacao_contrato timestamp without time zone NOT NULL,
    cd_contrato numeric NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    tx_arq_documentacao_contrato character varying,
    tx_nome_arquivo character varying(100),
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.a_documentacao_contrato OWNER TO K_USER;

CREATE TABLE a_objeto_contrato_rotina (
    cd_objeto numeric(8,0) NOT NULL,
    cd_rotina numeric(8,0) NOT NULL,
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.a_objeto_contrato_rotina OWNER TO K_USER;

CREATE TABLE s_historico_execucao_rotina (
    dt_historico_execucao_rotina timestamp without time zone NOT NULL,
    cd_rotina numeric(8,0) NOT NULL,
    cd_objeto numeric(8,0) NOT NULL,
    cd_profissional numeric(8,0) NOT NULL,
    dt_execucao_rotina date NOT NULL,
    tx_historico_execucao_rotina character varying(4000),
    dt_historico_rotina timestamp without time zone,
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.s_historico_execucao_rotina OWNER TO K_USER;

CREATE TABLE a_doc_projeto_continuo (
    dt_doc_projeto_continuo timestamp without time zone NOT NULL,
    cd_objeto numeric NOT NULL,
    cd_projeto_continuado numeric NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    tx_arq_doc_projeto_continuo character varying,
    tx_nome_arquivo character varying(100),
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.a_doc_projeto_continuo OWNER TO K_USER;

CREATE TABLE s_inventario (
    cd_inventario numeric(8,0) NOT NULL,
    cd_area_atuacao_ti numeric(8,0) NOT NULL,
    tx_inventario character varying(100),
    tx_desc_inventario character varying(4000),
    tx_obs_inventario character varying(4000),
    dt_ult_atual_inventario date,
    id numeric(8,0)
);

ALTER TABLE K_SCHEMA.s_inventario OWNER TO K_USER;




/* Primary Key */

ALTER TABLE ONLY K_SCHEMA.a_form_inventario
    ADD CONSTRAINT pk_K_SCHEMA_a_form_inventario PRIMARY KEY (cd_inventario, cd_item_inventario, cd_item_inventariado, cd_subitem_inventario);

ALTER TABLE ONLY K_SCHEMA.a_form_inventario
    ADD CONSTRAINT pk_K_SCHEMA_a_form_inventario PRIMARY KEY (cd_inventario, cd_item_inventario, cd_item_inventariado, cd_subitem_inventario);

ALTER TABLE ONLY K_SCHEMA.a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_pk PRIMARY KEY (cd_item_inventariado, cd_inventario, cd_item_inventario);

ALTER TABLE ONLY K_SCHEMA.a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_pk PRIMARY KEY (cd_item_inventariado, cd_inventario, cd_item_inventario);

ALTER TABLE ONLY K_SCHEMA.a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_pk PRIMARY KEY (cd_item_inventariado, cd_inventario, cd_item_inventario);

ALTER TABLE ONLY K_SCHEMA.a_documentacao_contrato
    ADD CONSTRAINT pk_K_SCHEMA_159 PRIMARY KEY (dt_documentacao_contrato, cd_contrato, cd_tipo_documentacao);

ALTER TABLE ONLY K_SCHEMA.b_rotina
    ADD CONSTRAINT pk_K_SCHEMA_160 PRIMARY KEY (cd_rotina);

ALTER TABLE ONLY K_SCHEMA.b_status_atendimento
    ADD CONSTRAINT pk_K_SCHEMA_161 PRIMARY KEY (cd_status_atendimento);

ALTER TABLE ONLY K_SCHEMA.a_objeto_contrato_rotina
    ADD CONSTRAINT pk_K_SCHEMA_162 PRIMARY KEY (cd_objeto, cd_rotina);

ALTER TABLE ONLY K_SCHEMA.a_rotina_profissional
    ADD CONSTRAINT pk_K_SCHEMA_163 PRIMARY KEY (cd_objeto, cd_profissional, cd_rotina);

ALTER TABLE ONLY K_SCHEMA.s_execucao_rotina
    ADD CONSTRAINT pk_K_SCHEMA_164 PRIMARY KEY (dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina);

ALTER TABLE ONLY K_SCHEMA.s_historico_execucao_rotina
    ADD CONSTRAINT pk_K_SCHEMA_165 PRIMARY KEY (dt_historico_execucao_rotina, cd_rotina, cd_objeto, cd_profissional, dt_execucao_rotina);

ALTER TABLE ONLY K_SCHEMA.b_subitem_inventario
    ADD CONSTRAINT b_subitem_inventario_pk PRIMARY KEY (cd_item_inventario, cd_subitem_inventario);

ALTER TABLE ONLY K_SCHEMA.s_inventario
    ADD CONSTRAINT s_inventario_pk PRIMARY KEY (cd_inventario);

ALTER TABLE ONLY K_SCHEMA.a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_pk PRIMARY KEY (dt_doc_projeto_continuo, cd_projeto_continuado, cd_objeto, cd_tipo_documentacao);


/* Foreign Key */

ALTER TABLE ONLY K_SCHEMA.a_rotina_profissional
    ADD CONSTRAINT fk_K_SCHEMA_210 FOREIGN KEY (cd_profissional, cd_objeto) REFERENCES K_SCHEMA.a_profissional_objeto_contrato(cd_profissional, cd_objeto);

ALTER TABLE ONLY K_SCHEMA.a_rotina_profissional
    ADD CONSTRAINT fk_K_SCHEMA_211 FOREIGN KEY (cd_rotina, cd_objeto) REFERENCES K_SCHEMA.a_objeto_contrato_rotina(cd_rotina, cd_objeto);

ALTER TABLE ONLY K_SCHEMA.s_execucao_rotina
    ADD CONSTRAINT fk_K_SCHEMA_212 FOREIGN KEY (cd_rotina, cd_objeto, cd_profissional) REFERENCES K_SCHEMA.a_rotina_profissional(cd_rotina, cd_objeto, cd_profissional);
ALTER TABLE ONLY K_SCHEMA.a_form_inventario
    ADD CONSTRAINT a_form_inventario_fk1 FOREIGN KEY (cd_subitem_inventario, cd_item_inventario) REFERENCES K_SCHEMA.b_subitem_inventario(cd_subitem_inventario, cd_item_inventario);

ALTER TABLE ONLY K_SCHEMA.a_form_inventario
    ADD CONSTRAINT a_form_inventario_fk2 FOREIGN KEY (cd_item_inventariado, cd_inventario, cd_item_inventario) REFERENCES K_SCHEMA.a_item_inventariado(cd_item_inventariado, cd_inventario, cd_item_inventario);

ALTER TABLE ONLY K_SCHEMA.a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_fk1 FOREIGN KEY (cd_item_inventario) REFERENCES K_SCHEMA.b_item_inventario(cd_item_inventario);

ALTER TABLE ONLY K_SCHEMA.a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_fk2 FOREIGN KEY (cd_inventario) REFERENCES K_SCHEMA.s_inventario(cd_inventario);


ALTER TABLE ONLY K_SCHEMA.b_subitem_inventario
    ADD CONSTRAINT b_subitem_inventario_fk1 FOREIGN KEY (cd_item_inventario) REFERENCES K_SCHEMA.b_item_inventario(cd_item_inventario);

ALTER TABLE ONLY K_SCHEMA.b_rotina
    ADD CONSTRAINT fk_K_SCHEMA_208 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES K_SCHEMA.b_area_atuacao_ti(cd_area_atuacao_ti);

ALTER TABLE ONLY K_SCHEMA.a_documentacao_contrato
    ADD CONSTRAINT fk_K_SCHEMA_209 FOREIGN KEY (cd_tipo_documentacao) REFERENCES K_SCHEMA.b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY K_SCHEMA.a_documentacao_contrato
    ADD CONSTRAINT fk_K_SCHEMA_210 FOREIGN KEY (cd_contrato) REFERENCES K_SCHEMA.s_contrato(cd_contrato);

ALTER TABLE ONLY K_SCHEMA.a_objeto_contrato_rotina
    ADD CONSTRAINT fk_K_SCHEMA_208 FOREIGN KEY (cd_objeto) REFERENCES K_SCHEMA.s_objeto_contrato(cd_objeto);

ALTER TABLE ONLY K_SCHEMA.a_objeto_contrato_rotina
    ADD CONSTRAINT fk_K_SCHEMA_209 FOREIGN KEY (cd_rotina) REFERENCES K_SCHEMA.b_rotina(cd_rotina);

ALTER TABLE ONLY K_SCHEMA.s_historico_execucao_rotina
    ADD CONSTRAINT fk_K_SCHEMA_213 FOREIGN KEY (dt_execucao_rotina, cd_rotina, cd_objeto, cd_profissional) REFERENCES K_SCHEMA.s_execucao_rotina(dt_execucao_rotina, cd_rotina, cd_objeto, cd_profissional);

ALTER TABLE ONLY K_SCHEMA.a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_fk1 FOREIGN KEY (cd_tipo_documentacao) REFERENCES K_SCHEMA.b_tipo_documentacao(cd_tipo_documentacao);

ALTER TABLE ONLY K_SCHEMA.a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_fk2 FOREIGN KEY (cd_projeto_continuado, cd_objeto) REFERENCES K_SCHEMA.s_projeto_continuado(cd_projeto_continuado, cd_objeto);

ALTER TABLE ONLY K_SCHEMA.s_inventario
    ADD CONSTRAINT s_inventario_fk1 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES K_SCHEMA.b_area_atuacao_ti(cd_area_atuacao_ti);



/*
   Inclusão no Menu

*/

INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (297,119,'L_MENU_ASSOCIAR_ROTINA_AO_OBJETO_DO_CONTRATO',null,'associar-rotina-objeto-contrato','','',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (300,null,'L_MENU_EXECUTAR_ROTINA',null,'execucao-rotina','','',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (293,14,'L_MENU_STATUS_DE_ATENDIMENTO',null,'status-atendimento','','',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (294,255,'L_MENU_DEMANDAS_EM_ABERTO',null,'demanda-em-aberto','','relatorioDemanda',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (302,252,'L_MENU_ALOCACAO_DE_RECURSOS_DE_CONTRATO',null,'alocacao-recurso-contrato','','relatorioProjeto',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (305,304,'L_MENU_DOCUMENTACAO_PROJETO',null,'acompanhamento-fiscalizacao-documentacao','','',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (307,249,'L_MENU_HW_INVENTARIO',null,'inventario','','relatorioDiverso',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (304,null,'L_MENU_ACOMPANHAMENTO_INTERNO',null,'acompanhamento-fiscalizacao','','',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (303,251,'L_MENU_CHECKLIST_DE_PROJETO',null,'checklist-projeto','','relatorioProjeto',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (295,255,'L_MENU_DEMANDA_DETALHE',null,'demanda-detalhe','','relatorioDemanda',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (306,121,'L_MENU_DOCUMENTACAO_PROJETO_CONTINUO',null,'documentacao-projeto-continuo','','',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (291,119,'L_MENU_DOCUMENTACAO_DE_CONTRATO',null,'documentacao-contrato','','',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (301,255,'L_MENU_RELATORIO_ROTINA',null,'rotina','','relatorioDemanda',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (298,250,'L_MENU_HISTORICO_DE_PROPOSTA',null,'historico-proposta','','relatorioProjeto',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (296,119,'L_MENU_ROTINA',null,'rotina','','',0);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES (299,null,'L_MENU_GERENCIAMENTO_ROTINA',null,'gerenciamento-rotina','','',0);
