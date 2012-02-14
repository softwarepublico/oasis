--
-- Sql Server Create Table
--

CREATE TABLE [K_SCHEMA].[b_box_inicio]
(
	[cd_box_inicio] [numeric](8, 0) NOT NULL,
	[tx_box_inicio] [varchar](100) NOT NULL,
	[st_tipo_box_inicio] [char](1) NOT NULL,
	[tx_titulo_box_inicio] [varchar](100) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_098] PRIMARY KEY CLUSTERED
(
	[cd_box_inicio] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_conjunto_medida]
(
	[cd_conjunto_medida] [numeric](8, 0) NOT NULL,
	[tx_conjunto_medida] [varchar](500) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_095] PRIMARY KEY CLUSTERED
(
	[cd_conjunto_medida] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_evento]
(
	[cd_evento] [numeric](8, 0) NOT NULL,
	[tx_evento] [varchar](200) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_092] PRIMARY KEY CLUSTERED
(
	[cd_evento] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_funcionalidade]
(
	[cd_funcionalidade] [numeric](8, 0) NOT NULL,
	[tx_codigo_funcionalidade] [varchar](20) NULL,
	[tx_funcionalidade] [varchar](200) NULL,
	[st_funcionalidade] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_091] PRIMARY KEY CLUSTERED
(
	[cd_funcionalidade] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_definicao_metrica]
(
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[tx_nome_metrica] [varchar](1000) NULL,
	[tx_sigla_metrica] [varchar](1000) NULL,
	[tx_descricao_metrica] [varchar](1000) NULL,
	[tx_formula_metrica] [varchar](1000) NULL,
	[st_justificativa_metrica] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
	[tx_sigla_unidade_metrica] [varchar](10) NULL,
	[tx_unidade_metrica] [varchar](100) NULL,
 CONSTRAINT [pk_oasis_094] PRIMARY KEY CLUSTERED
(
	[cd_definicao_metrica] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_grupo_fator]
(
	[cd_grupo_fator] [numeric](8, 0) NOT NULL,
	[tx_grupo_fator] [varchar](100) NULL,
	[ni_peso_grupo_fator] [numeric](8, 0) NOT NULL,
	[ni_ordem_grupo_fator] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
	[ni_indice_grupo_fator] [numeric](8, 0) NOT NULL,
 CONSTRAINT [pk_oasis_090] PRIMARY KEY CLUSTERED
(
	[cd_grupo_fator] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_item_controle_baseline]
(
	[cd_item_controle_baseline] [numeric](8, 0) NOT NULL,
	[tx_item_controle_baseline] [varchar](500) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_089] PRIMARY KEY CLUSTERED
(
	[cd_item_controle_baseline] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_item_grupo_fator]
(
	[cd_item_grupo_fator] [numeric](8, 0) NOT NULL,
	[cd_grupo_fator] [numeric](8, 0) NOT NULL,
	[tx_item_grupo_fator] [varchar](300) NULL,
	[ni_ordem_item_grupo_fator] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_088] PRIMARY KEY CLUSTERED
(
	[cd_item_grupo_fator] ASC,
	[cd_grupo_fator] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_item_parecer_tecnico]
(
	[cd_item_parecer_tecnico] [numeric](8, 0) NOT NULL,
	[tx_item_parecer_tecnico] [varchar](1000) NULL,
	[st_proposta] [char](1) NULL,
	[st_parcela] [char](1) NULL,
	[st_viagem] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_085] PRIMARY KEY CLUSTERED
(
	[cd_item_parecer_tecnico] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[s_log]
(
	[dt_ocorrencia] [datetime] NOT NULL,
	[cd_log] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NULL,
	[tx_msg_log] [varchar](1000) NOT NULL,
	[ni_prioridade] [numeric](8, 0) NULL,
	[tx_tabela] [varchar](500) NULL,
	[tx_controller] [varchar](500) NULL,
	[tx_ip] [varchar](15) NULL,
	[tx_host] [varchar](max) NULL
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_item_teste]
(
	[cd_item_teste] [numeric](8, 0) NOT NULL,
	[tx_item_teste] [varchar](1000) NULL,
	[st_item_teste] [char](1) NULL,
	[st_obrigatorio] [char](1) NULL,
	[st_tipo_item_teste] [char](1) NULL,
	[ni_ordem_item_teste] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_083] PRIMARY KEY CLUSTERED
(
	[cd_item_teste] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_medida]
(
	[cd_medida] [numeric](8, 0) NOT NULL,
	[tx_medida] [varchar](500) NULL,
	[tx_objetivo_medida] [varchar](4000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_082] PRIMARY KEY CLUSTERED
(
	[cd_medida] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_menu]
(
	[cd_menu] [numeric](8, 0) NOT NULL,
	[cd_menu_pai] [numeric](8, 0) NULL,
	[tx_menu] [varchar](1000) NULL,
	[ni_nivel_menu] [numeric](8, 0) NULL,
	[tx_pagina] [varchar](1000) NULL,
	[st_menu] [char](1) NULL,
	[tx_modulo] [varchar](50) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_081] PRIMARY KEY CLUSTERED
(
	[cd_menu] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_perfil]
(
	[cd_perfil] [numeric](8, 0) NOT NULL,
	[tx_perfil] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_076] PRIMARY KEY CLUSTERED
(
	[cd_perfil] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_tipo_conhecimento]
(
	[cd_tipo_conhecimento] [numeric](8, 0) NOT NULL,
	[tx_tipo_conhecimento] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_070] PRIMARY KEY CLUSTERED
(
	[cd_tipo_conhecimento] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_tipo_dado_tecnico](
	[cd_tipo_dado_tecnico] [numeric](8, 0) NOT NULL,
	[tx_tipo_dado_tecnico] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_069] PRIMARY KEY CLUSTERED
(
	[cd_tipo_dado_tecnico] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]

CREATE TABLE [K_SCHEMA].[b_tipo_documentacao](
	[cd_tipo_documentacao] [numeric](8, 0) NOT NULL,
	[tx_tipo_documentacao] [varchar](1000) NULL,
	[tx_extensao_documentacao] [varchar](1000) NULL,
	[st_classificacao] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_068] PRIMARY KEY CLUSTERED
(
	[cd_tipo_documentacao] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_tipo_inventario](
	[cd_tipo_inventario] [numeric](8, 0) NOT NULL,
	[tx_tipo_inventario] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_067] PRIMARY KEY CLUSTERED
(
	[cd_tipo_inventario] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_treinamento](
	[cd_treinamento] [numeric](8, 0) NOT NULL,
	[tx_treinamento] [varchar](500) NULL,
	[tx_obs_treinamento] [varchar](4000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_065] PRIMARY KEY CLUSTERED
(
	[cd_treinamento] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_unidade](
	[cd_unidade] [numeric](8, 0) NOT NULL,
	[tx_sigla_unidade] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_064] PRIMARY KEY CLUSTERED
(
	[cd_unidade] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_acompanhamento_proposta](
	[cd_acompanhamento_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[tx_acompanhamento_proposta] [varchar](8000) NULL,
	[st_restrito] [char](1) NULL,
	[dt_acompanhamento_proposta] [datetime] NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_063] PRIMARY KEY CLUSTERED
(
	[cd_acompanhamento_proposta] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_relacao_contratual](
	[cd_relacao_contratual] [numeric](8, 0) NOT NULL,
	[tx_relacao_contratual] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_073] PRIMARY KEY CLUSTERED
(
	[cd_relacao_contratual] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_status](
	[cd_status] [numeric](8, 0) NOT NULL,
	[tx_status] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_072] PRIMARY KEY CLUSTERED
(
	[cd_status] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_config_banco_de_dados](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[tx_adapter] [varchar](100) NULL,
	[tx_host] [varchar](100) NULL,
	[tx_dbname] [varchar](100) NULL,
	[tx_username] [varchar](100) NULL,
	[tx_password] [varchar](100) NULL,
	[tx_schema] [varchar](100) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_050] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_custo_contrato_demanda](
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[ni_mes_custo_contrato_demanda] [int] NOT NULL,
	[ni_ano_custo_contrato_demanda] [int] NOT NULL,
	[nf_total_multa] [numeric](8, 2) NULL,
	[nf_total_glosa] [numeric](8, 2) NULL,
	[nf_total_pago] [numeric](8, 2) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_047] PRIMARY KEY CLUSTERED
(
	[cd_contrato] ASC,
	[ni_mes_custo_contrato_demanda] ASC,
	[ni_ano_custo_contrato_demanda] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_empresa](
	[cd_empresa] [numeric](8, 0) NOT NULL,
	[tx_empresa] [varchar](1000) NULL,
	[tx_cnpj_empresa] [varchar](1000) NULL,
	[tx_endereco_empresa] [varchar](1000) NULL,
	[tx_telefone_empresa] [varchar](20) NULL,
	[tx_email_empresa] [varchar](200) NULL,
	[tx_fax_empresa] [varchar](30) NULL,
	[tx_arquivo_logomarca] [varchar](100) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_044] PRIMARY KEY CLUSTERED
(
	[cd_empresa] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_fale_conosco](
	[cd_fale_conosco] [numeric](8, 0) NOT NULL,
	[tx_nome] [varchar](1000) NULL,
	[tx_email] [varchar](1000) NULL,
	[tx_assunto] [varchar](1000) NULL,
	[tx_mensagem] [varchar](1000) NULL,
	[tx_resposta] [varchar](1000) NULL,
	[st_respondida] [char](1) NULL,
	[dt_registro] [datetime] NULL,
	[st_pendente] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_041] PRIMARY KEY CLUSTERED
(
	[cd_fale_conosco] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_medicao](
	[cd_medicao] [numeric](8, 0) NOT NULL,
	[tx_medicao] [varchar](200) NULL,
	[tx_objetivo_medicao] [varchar](1000) NULL,
	[st_nivel_medicao] [char](1) NULL,
	[tx_procedimento_coleta] [varchar](1000) NULL,
	[tx_procedimento_analise] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_028] PRIMARY KEY CLUSTERED
(
	[cd_medicao] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_ocorrencia_administrativa](
	[dt_ocorrencia_administrativa] [datetime] NOT NULL,
	[cd_evento] [numeric](8, 0) NOT NULL,
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[tx_ocorrencia_administrativa] [varchar](8000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_023] PRIMARY KEY CLUSTERED
(
	[dt_ocorrencia_administrativa] ASC,
	[cd_evento] ASC,
	[cd_contrato] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_contrato_evento](
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[cd_evento] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_146] PRIMARY KEY CLUSTERED
(
	[cd_contrato] ASC,
	[cd_evento] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_pergunta_pedido](
	[cd_pergunta_pedido] [numeric](8, 0) NOT NULL,
	[tx_titulo_pergunta] [varchar](200) NOT NULL,
	[st_multipla_resposta] [char](1) NOT NULL DEFAULT ('N'),
	[st_obriga_resposta] [char](1) NOT NULL DEFAULT ('N'),
	[tx_ajuda_pergunta] [varchar](max) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_151] PRIMARY KEY CLUSTERED
(
	[cd_pergunta_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_resposta_pedido](
	[cd_resposta_pedido] [numeric](8, 0) NOT NULL,
	[tx_titulo_resposta] [varchar](150) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_152] PRIMARY KEY CLUSTERED
(
	[cd_resposta_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_pre_projeto](
	[cd_pre_projeto] [numeric](8, 0) NOT NULL,
	[cd_unidade] [numeric](8, 0) NULL,
	[cd_gerente_pre_projeto] [numeric](8, 0) NULL,
	[tx_pre_projeto] [varchar](200) NULL,
	[tx_sigla_pre_projeto] [varchar](100) NULL,
	[tx_contexto_geral_pre_projeto] [varchar](2000) NULL,
	[tx_escopo_pre_projeto] [varchar](2000) NULL,
	[tx_gestor_pre_projeto] [varchar](200) NULL,
	[tx_obs_pre_projeto] [varchar](2000) NULL,
	[st_impacto_pre_projeto] [char](1) NULL,
	[st_prioridade_pre_projeto] [char](1) NULL,
	[tx_horas_estimadas] [varchar](10) NULL,
	[tx_pub_alcancado_pre_proj] [varchar](200) NULL,
	[cd_contrato] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_018] PRIMARY KEY CLUSTERED
(
	[cd_pre_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_area_atuacao_ti](
	[cd_area_atuacao_ti] [numeric](8, 0) NOT NULL,
	[tx_area_atuacao_ti] [varchar](200) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_101] PRIMARY KEY CLUSTERED
(
	[cd_area_atuacao_ti] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_area_conhecimento](
	[cd_area_conhecimento] [numeric](8, 0) NOT NULL,
	[tx_area_conhecimento] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_100] PRIMARY KEY CLUSTERED
(
	[cd_area_conhecimento] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_analise_medicao](
	[dt_analise_medicao] [datetime] NOT NULL,
	[cd_medicao] [numeric](8, 0) NOT NULL,
	[cd_box_inicio] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[tx_resultado_analise_medicao] [varchar](1000) NULL,
	[tx_dados_medicao] [varchar](1000) NULL,
	[tx_decisao] [varchar](1000) NULL,
	[dt_decisao] [datetime] NULL,
	[st_decisao_executada] [char](1) NULL,
	[dt_decisao_executada] [datetime] NULL,
	[tx_obs_decisao_executada] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_059] PRIMARY KEY CLUSTERED
(
	[dt_analise_medicao] ASC,
	[cd_medicao] ASC,
	[cd_box_inicio] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_perfil_box_inicio](
	[cd_perfil] [numeric](8, 0) NOT NULL,
	[cd_box_inicio] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_122] PRIMARY KEY CLUSTERED
(
	[cd_perfil] ASC,
	[cd_box_inicio] ASC,
	[cd_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_conjunto_medida_medicao](
	[cd_conjunto_medida] [numeric](8, 0) NOT NULL,
	[cd_box_inicio] [numeric](8, 0) NOT NULL,
	[cd_medicao] [numeric](8, 0) NOT NULL,
	[st_nivel_conjunto_medida] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_148] PRIMARY KEY CLUSTERED
(
	[cd_conjunto_medida] ASC,
	[cd_box_inicio] ASC,
	[cd_medicao] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_profissional_produto](
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_produto_parcela] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_parcela] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_113] PRIMARY KEY CLUSTERED
(
	[cd_profissional] ASC,
	[cd_produto_parcela] ASC,
	[cd_proposta] ASC,
	[cd_projeto] ASC,
	[cd_parcela] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_gerencia_qualidade](
	[cd_gerencia_qualidade] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[dt_auditoria_qualidade] [datetime] NULL,
	[tx_fase_projeto] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_040] PRIMARY KEY CLUSTERED
(
	[cd_gerencia_qualidade] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_analise_risco](
	[dt_analise_risco] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[cd_item_risco] [numeric](8, 0) NOT NULL,
	[st_fator_risco] [char](3) NULL,
	[st_impacto_projeto_risco] [char](3) NULL,
	[st_impacto_tecnico_risco] [char](3) NULL,
	[st_impacto_custo_risco] [char](3) NULL,
	[st_impacto_cronograma_risco] [char](3) NULL,
	[tx_analise_risco] [varchar](1000) NULL,
	[tx_acao_analise_risco] [varchar](1000) NULL,
	[st_fechamento_risco] [char](1) NULL,
	[cd_profissional] [numeric](8, 0) NULL,
	[cd_profissional_responsavel] [numeric](8, 0) NULL,
	[dt_limite_acao] [datetime] NULL,
	[st_acao] [char](1) NULL,
	[tx_observacao_acao] [varchar](1000) NULL,
	[dt_fechamento_risco] [datetime] NULL,
	[tx_cor_impacto_cronog_risco] [char](20) NULL,
	[tx_cor_impacto_custo_risco] [char](20) NULL,
	[tx_cor_impacto_projeto_risco] [char](20) NULL,
	[tx_cor_impacto_tecnico_risco] [char](20) NULL,
	[id] [numeric](8, 0) NULL,
	[st_nao_aplica_risco] [char](1) NULL,
	[tx_mitigacao_risco] [varchar](1) NULL,
 CONSTRAINT [pk_oasis_058] PRIMARY KEY CLUSTERED
(
	[dt_analise_risco] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC,
	[cd_etapa] ASC,
	[cd_atividade] ASC,
	[cd_item_risco] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_parcela](
	[cd_parcela] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[ni_parcela] [numeric](8, 0) NULL,
	[ni_horas_parcela] [numeric](8, 1) NULL,
	[ni_mes_previsao_parcela] [numeric](8, 0) NULL,
	[ni_ano_previsao_parcela] [numeric](8, 0) NULL,
	[ni_mes_execucao_parcela] [numeric](8, 0) NULL,
	[ni_ano_execucao_parcela] [numeric](8, 0) NULL,
	[st_modulo_proposta] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_022] PRIMARY KEY CLUSTERED
(
	[cd_parcela] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_quest_avaliacao_qualidade](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_grupo_fator] [numeric](8, 0) NOT NULL,
	[cd_item_grupo_fator] [numeric](8, 0) NOT NULL,
	[st_avaliacao_qualidade] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_108] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[cd_proposta] ASC,
	[cd_grupo_fator] ASC,
	[cd_item_grupo_fator] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_proposta_sub_item_metrica](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_item_metrica] [numeric](8, 0) NOT NULL,
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[cd_sub_item_metrica] [numeric](8, 0) NOT NULL,
	[ni_valor_sub_item_metrica] [numeric](8, 1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_109] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[cd_proposta] ASC,
	[cd_item_metrica] ASC,
	[cd_definicao_metrica] ASC,
	[cd_sub_item_metrica] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_proposta_modulo](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[st_criacao_modulo] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_110] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[cd_modulo] ASC,
	[cd_proposta] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_proposta_definicao_metrica](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[ni_horas_proposta_metrica] [numeric](8, 1) NULL,
	[tx_justificativa_metrica] [varchar](8000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_111] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[cd_proposta] ASC,
	[cd_definicao_metrica] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_processamento_proposta](
	[cd_processamento_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[st_fechamento_proposta] [char](1) NULL,
	[dt_fechamento_proposta] [datetime] NULL,
	[cd_prof_fechamento_proposta] [numeric](8, 0) NULL,
	[st_parecer_tecnico_proposta] [char](1) NULL,
	[dt_parecer_tecnico_proposta] [datetime] NULL,
	[tx_obs_parecer_tecnico_prop] [varchar](1000) NULL,
	[cd_prof_parecer_tecnico_propos] [numeric](8, 0) NULL,
	[st_aceite_proposta] [char](1) NULL,
	[dt_aceite_proposta] [datetime] NULL,
	[tx_obs_aceite_proposta] [varchar](1000) NULL,
	[cd_prof_aceite_proposta] [numeric](8, 0) NULL,
	[st_homologacao_proposta] [char](1) NULL,
	[dt_homologacao_proposta] [datetime] NULL,
	[tx_obs_homologacao_proposta] [varchar](1000) NULL,
	[cd_prof_homologacao_proposta] [numeric](8, 0) NULL,
	[st_alocacao_proposta] [char](1) NULL,
	[dt_alocacao_proposta] [datetime] NULL,
	[cd_prof_alocacao_proposta] [numeric](8, 0) NULL,
	[st_ativo] [char](1) NULL,
	[tx_motivo_alteracao_proposta] [varchar](4000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_014] PRIMARY KEY CLUSTERED
(
	[cd_processamento_proposta] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_historico_proposta](
	[dt_historico_proposta] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[tx_sigla_projeto] [varchar](100) NULL,
	[tx_projeto] [varchar](1000) NULL,
	[tx_contexdo_geral] [varchar](max) NULL,
	[tx_escopo_projeto] [varchar](max) NULL,
	[tx_sigla_unidade] [varchar](100) NULL,
	[tx_gestor_projeto] [varchar](100) NULL,
	[tx_impacto_projeto] [varchar](100) NULL,
	[tx_gerente_projeto] [varchar](200) NULL,
	[st_metrica_historico] [char](3) NULL,
	[tx_inicio_previsto] [varchar](100) NULL,
	[tx_termino_previsto] [varchar](100) NULL,
	[ni_horas_proposta] [numeric](8, 1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_035] PRIMARY KEY CLUSTERED
(
	[dt_historico_proposta] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_plano_implantacao](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[tx_descricao_plano_implantacao] [varchar](1000) NOT NULL,
	[cd_prof_plano_implantacao] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_020] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[cd_proposta] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_controle](
	[cd_controle] [numeric](8, 0) NOT NULL,
	[cd_projeto_previsto] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[ni_horas] [numeric](8, 1) NULL,
	[st_controle] [char](1) NULL,
	[st_modulo_proposta] [char](1) NULL,
	[dt_lancamento] [datetime] NULL,
	[cd_profissional] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_144] PRIMARY KEY CLUSTERED
(
	[cd_controle] ASC,
	[cd_projeto_previsto] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC,
	[cd_contrato] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_tipo_produto](
	[cd_tipo_produto] [numeric](8, 0) NOT NULL,
	[tx_tipo_produto] [varchar](1000) NULL,
	[ni_ordem_tipo_produto] [numeric](4, 0) NULL,
	[cd_definicao_metrica] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_066] PRIMARY KEY CLUSTERED
(
	[cd_tipo_produto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_contrato_definicao_metrica](
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
	[nf_fator_relacao_metrica_pad] [numeric](4, 0) NULL,
	[st_metrica_padrao] [char](1) NULL,
 CONSTRAINT [pk_oasis_147] PRIMARY KEY CLUSTERED
(
	[cd_contrato] ASC,
	[cd_definicao_metrica] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_item_metrica](
	[cd_item_metrica] [numeric](8, 0) NOT NULL,
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[tx_item_metrica] [varchar](1000) NULL,
	[tx_variavel_item_metrica] [varchar](1000) NULL,
	[ni_ordem_item_metrica] [int] NULL,
	[tx_formula_item_metrica] [varchar](500) NULL,
	[st_interno_item_metrica] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_086] PRIMARY KEY CLUSTERED
(
	[cd_item_metrica] ASC,
	[cd_definicao_metrica] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_solicitacao_resposta_pedido](
	[cd_solicitacao_pedido] [numeric](8, 0) NOT NULL,
	[cd_pergunta_pedido] [numeric](8, 0) NOT NULL,
	[cd_resposta_pedido] [numeric](8, 0) NOT NULL,
	[tx_descricao_resposta] [varchar](max) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_155] PRIMARY KEY CLUSTERED
(
	[cd_solicitacao_pedido] ASC,
	[cd_pergunta_pedido] ASC,
	[cd_resposta_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_pergunta_depende_resp_pedido](
	[cd_pergunta_depende] [numeric](8, 0) NOT NULL,
	[cd_pergunta_pedido] [numeric](8, 0) NOT NULL,
	[cd_resposta_pedido] [numeric](8, 0) NOT NULL,
	[st_tipo_dependencia] [char](1) NOT NULL DEFAULT ('S'),
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_158] PRIMARY KEY CLUSTERED
(
	[cd_pergunta_depende] ASC,
	[cd_pergunta_pedido] ASC,
	[cd_resposta_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_funcionalidade_menu](
	[cd_funcionalidade] [numeric](8, 0) NOT NULL,
	[cd_menu] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_137] PRIMARY KEY CLUSTERED
(
	[cd_funcionalidade] ASC,
	[cd_menu] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_definicao_processo](
	[cd_perfil] [numeric](8, 0) NOT NULL,
	[cd_funcionalidade] [numeric](8, 0) NOT NULL,
	[st_definicao_processo] [char](1) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_143] PRIMARY KEY CLUSTERED
(
	[cd_perfil] ASC,
	[cd_funcionalidade] ASC,
	[st_definicao_processo] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_baseline_item_controle](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[dt_baseline] [datetime] NOT NULL,
	[cd_item_controle_baseline] [numeric](8, 0) NOT NULL,
	[cd_item_controlado] [numeric](8, 0) NOT NULL,
	[dt_versao_item_controlado] [datetime] NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_150] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[dt_baseline] ASC,
	[cd_item_controle_baseline] ASC,
	[cd_item_controlado] ASC,
	[dt_versao_item_controlado] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_gerencia_mudanca](
	[dt_gerencia_mudanca] [datetime] NOT NULL,
	[cd_item_controle_baseline] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_item_controlado] [numeric](8, 0) NOT NULL,
	[dt_versao_item_controlado] [datetime] NOT NULL,
	[tx_motivo_mudanca] [varchar](1000) NULL,
	[st_mudanca_metrica] [char](1) NULL,
	[ni_custo_provavel_mudanca] [numeric](8, 0) NULL,
	[st_reuniao] [char](1) NULL,
	[tx_decisao_mudanca] [varchar](1000) NULL,
	[dt_decisao_mudanca] [datetime] NULL,
	[cd_reuniao] [numeric](8, 0) NULL,
	[cd_projeto_reuniao] [numeric](8, 0) NULL,
	[st_decisao_mudanca] [char](1) NULL,
	[st_execucao_mudanca] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_136] PRIMARY KEY CLUSTERED
(
	[dt_gerencia_mudanca] ASC,
	[cd_item_controle_baseline] ASC,
	[cd_projeto] ASC,
	[cd_item_controlado] ASC,
	[dt_versao_item_controlado] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_penalidade](
	[cd_penalidade] [numeric](8, 0) NOT NULL,
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[tx_penalidade] [varchar](1000) NULL,
	[tx_abreviacao_penalidade] [varchar](1000) NULL,
	[ni_valor_penalidade] [numeric](8, 2) NULL,
	[ni_penalidade] [numeric](8, 0) NULL,
	[st_ocorrencia] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_077] PRIMARY KEY CLUSTERED
(
	[cd_penalidade] ASC,
	[cd_contrato] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_extrato_mensal](
	[ni_mes_extrato] [numeric](8, 0) NOT NULL,
	[ni_ano_extrato] [numeric](8, 0) NOT NULL,
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[dt_fechamento_extrato] [datetime] NULL,
	[ni_horas_extrato] [numeric](8, 0) NULL,
	[ni_qtd_parcela] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_043] PRIMARY KEY CLUSTERED
(
	[ni_mes_extrato] ASC,
	[ni_ano_extrato] ASC,
	[cd_contrato] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_projeto_previsto](
	[cd_projeto_previsto] [numeric](8, 0) NOT NULL,
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[cd_unidade] [numeric](8, 0) NULL,
	[tx_projeto_previsto] [varchar](1000) NULL,
	[ni_horas_projeto_previsto] [numeric](8, 0) NULL,
	[st_projeto_previsto] [char](1) NULL,
	[tx_descricao_projeto_previsto] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
	[cd_definicao_metrica] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_009] PRIMARY KEY CLUSTERED
(
	[cd_projeto_previsto] ASC,
	[cd_contrato] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_objeto_contrato](
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[tx_objeto] [varchar](1000) NULL,
	[ni_horas_objeto] [numeric](8, 0) NULL,
	[st_objeto_contrato] [char](1) NULL,
	[st_viagem] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
	[st_parcela_orcamento] [char](1) NULL,
	[ni_porcentagem_parc_orcamento] [numeric](3, 0) NULL,
	[st_necessita_justificativa] [char](1) NULL,
	[ni_minutos_justificativa] [numeric](8, 0) NULL,
	[tx_hora_inicio_just_periodo_1] [varchar](8) NULL,
	[tx_hora_fim_just_periodo_1] [varchar](8) NULL,
	[tx_hora_inicio_just_periodo_2] [varchar](8) NULL,
	[tx_hora_fim_just_periodo_2] [varchar](8) NULL,
 CONSTRAINT [pk_oasis_024] PRIMARY KEY CLUSTERED
(
	[cd_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_contrato_projeto](
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_145] PRIMARY KEY CLUSTERED
(
	[cd_contrato] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_arquivo_pedido](
	[cd_arquivo_pedido] [numeric](8, 0) NOT NULL,
	[cd_pergunta_pedido] [numeric](8, 0) NOT NULL,
	[cd_resposta_pedido] [numeric](8, 0) NOT NULL,
	[cd_solicitacao_pedido] [numeric](8, 0) NOT NULL,
	[tx_titulo_arquivo] [varchar](100) NOT NULL,
	[tx_nome_arquivo] [varchar](20) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_156] PRIMARY KEY CLUSTERED
(
	[cd_arquivo_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_contrato](
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[cd_empresa] [numeric](8, 0) NOT NULL,
	[tx_numero_contrato] [varchar](50) NULL,
	[dt_inicio_contrato] [smalldatetime] NULL,
	[dt_fim_contrato] [smalldatetime] NULL,
	[st_aditivo] [char](1) NULL,
	[tx_cpf_gestor] [varchar](20) NULL,
	[ni_horas_previstas] [numeric](8, 0) NULL,
	[tx_objeto] [varchar](1000) NULL,
	[tx_gestor_contrato] [varchar](200) NULL,
	[tx_fone_gestor_contrato] [varchar](20) NULL,
	[tx_numero_processo] [varchar](20) NULL,
	[tx_obs_contrato] [varchar](1000) NULL,
	[tx_localizacao_arquivo] [varchar](200) NULL,
	[tx_co_gestor] [varchar](200) NULL,
	[tx_cpf_co_gestor] [varchar](20) NULL,
	[tx_fone_co_gestor_contrato] [varchar](20) NULL,
	[nf_valor_passagens_diarias] [numeric](15, 2) NULL,
	[nf_valor_unitario_diaria] [numeric](15, 2) NULL,
	[st_contrato] [char](1) NULL,
	[ni_mes_inicial_contrato] [numeric](4, 0) NULL,
	[ni_ano_inicial_contrato] [numeric](4, 0) NULL,
	[ni_mes_final_contrato] [numeric](4, 0) NULL,
	[ni_ano_final_contrato] [numeric](4, 0) NULL,
	[ni_qtd_meses_contrato] [numeric](4, 0) NULL,
	[nf_valor_unitario_hora] [numeric](15, 2) NULL,
	[nf_valor_contrato] [numeric](15, 2) NULL,
	[cd_contato_empresa] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
	[cd_definicao_metrica] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_048] PRIMARY KEY CLUSTERED
(
	[cd_contrato] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_coluna](
	[tx_tabela] [varchar](200) NOT NULL,
	[tx_coluna] [varchar](200) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[tx_descricao] [varchar](1000) NULL,
	[st_chave] [char](1) NULL,
	[tx_tabela_referencia] [varchar](200) NULL,
	[cd_projeto_referencia] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_053] PRIMARY KEY CLUSTERED
(
	[tx_tabela] ASC,
	[tx_coluna] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_parecer_tecnico_parcela](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_parcela] [numeric](8, 0) NOT NULL,
	[cd_item_parecer_tecnico] [numeric](8, 0) NOT NULL,
	[cd_processamento_parcela] [numeric](8, 0) NOT NULL,
	[st_avaliacao] [char](2) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_124] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[cd_proposta] ASC,
	[cd_parcela] ASC,
	[cd_item_parecer_tecnico] ASC,
	[cd_processamento_parcela] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_parecer_tecnico_proposta](
	[cd_item_parecer_tecnico] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_processamento_proposta] [numeric](8, 0) NOT NULL,
	[st_avaliacao] [char](2) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_123] PRIMARY KEY CLUSTERED
(
	[cd_item_parecer_tecnico] ASC,
	[cd_proposta] ASC,
	[cd_projeto] ASC,
	[cd_processamento_proposta] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_item_teste_caso_de_uso](
	[cd_item_teste_caso_de_uso] [numeric](8, 0) NOT NULL,
	[cd_item_teste] [numeric](8, 0) NOT NULL,
	[cd_caso_de_uso] [numeric](8, 0) NOT NULL,
	[dt_versao_caso_de_uso] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[st_analise] [char](1) NULL,
	[tx_analise] [varchar](2000) NULL,
	[dt_analise] [datetime] NULL,
	[cd_profissional_analise] [numeric](8, 0) NULL,
	[st_solucao] [char](1) NULL,
	[tx_solucao] [varchar](2000) NULL,
	[dt_solucao] [datetime] NULL,
	[cd_profissional_solucao] [numeric](8, 0) NULL,
	[st_homologacao] [char](1) NULL,
	[tx_homologacao] [varchar](2000) NULL,
	[dt_homologacao] [datetime] NULL,
	[cd_profissional_homologacao] [numeric](8, 0) NULL,
	[st_item_teste_caso_de_uso] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_134] PRIMARY KEY CLUSTERED
(
	[cd_item_teste] ASC,
	[cd_modulo] ASC,
	[cd_projeto] ASC,
	[cd_caso_de_uso] ASC,
	[dt_versao_caso_de_uso] ASC,
	[cd_item_teste_caso_de_uso] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_item_teste_regra_negocio](
	[cd_item_teste_regra_negocio] [numeric](8, 0) NOT NULL,
	[cd_item_teste] [numeric](8, 0) NOT NULL,
	[cd_regra_negocio] [numeric](8, 0) NOT NULL,
	[dt_regra_negocio] [datetime] NOT NULL,
	[cd_projeto_regra_negocio] [numeric](8, 0) NOT NULL,
	[st_analise] [char](1) NULL,
	[tx_analise] [varchar](1000) NULL,
	[dt_analise] [datetime] NULL,
	[cd_profissional_analise] [numeric](8, 0) NULL,
	[st_solucao] [char](1) NULL,
	[tx_solucao] [varchar](1000) NULL,
	[dt_solucao] [datetime] NULL,
	[cd_profissional_solucao] [numeric](8, 0) NULL,
	[st_homologacao] [char](1) NULL,
	[tx_homologacao] [varchar](1000) NULL,
	[dt_homologacao] [datetime] NULL,
	[cd_profissional_homologacao] [numeric](8, 0) NULL,
	[st_item_teste_regra_negocio] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_132] PRIMARY KEY CLUSTERED
(
	[dt_regra_negocio] ASC,
	[cd_regra_negocio] ASC,
	[cd_item_teste] ASC,
	[cd_projeto_regra_negocio] ASC,
	[cd_item_teste_regra_negocio] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_item_teste_requisito](
	[cd_item_teste_requisito] [numeric](8, 0) NOT NULL,
	[cd_item_teste] [numeric](8, 0) NOT NULL,
	[cd_requisito] [numeric](8, 0) NOT NULL,
	[dt_versao_requisito] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[st_analise] [char](1) NULL,
	[tx_analise] [varchar](1000) NULL,
	[dt_analise] [datetime] NULL,
	[cd_profissional_analise] [numeric](8, 0) NULL,
	[st_solucao] [char](1) NULL,
	[tx_solucao] [varchar](1000) NULL,
	[dt_solucao] [datetime] NULL,
	[cd_profissional_solucao] [numeric](8, 0) NULL,
	[st_homologacao] [char](1) NULL,
	[tx_homologacao] [varchar](1000) NULL,
	[dt_homologacao] [datetime] NULL,
	[cd_profissional_homologacao] [numeric](8, 0) NULL,
	[st_item_teste_requisito] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_130] PRIMARY KEY CLUSTERED
(
	[cd_item_teste_requisito] ASC,
	[cd_requisito] ASC,
	[dt_versao_requisito] ASC,
	[cd_projeto] ASC,
	[cd_item_teste] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_requisito_caso_de_uso](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[dt_versao_requisito] [datetime] NOT NULL,
	[cd_requisito] [numeric](8, 0) NOT NULL,
	[dt_versao_caso_de_uso] [datetime] NOT NULL,
	[cd_caso_de_uso] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[dt_inativacao_caso_de_uso] [datetime] NULL,
	[st_inativo] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_105] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[dt_versao_requisito] ASC,
	[cd_requisito] ASC,
	[dt_versao_caso_de_uso] ASC,
	[cd_caso_de_uso] ASC,
	[cd_modulo] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_complemento](
	[cd_complemento] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_caso_de_uso] [numeric](8, 0) NOT NULL,
	[tx_complemento] [varchar](max) NULL,
	[st_complemento] [char](1) NULL,
	[ni_ordem_complemento] [numeric](8, 0) NULL,
	[dt_versao_caso_de_uso] [datetime] NOT NULL,
	[id] [numeric](8, 0) NULL
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_condicao](
	[cd_condicao] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_caso_de_uso] [numeric](8, 0) NOT NULL,
	[tx_condicao] [varchar](1000) NULL,
	[st_condicao] [char](1) NULL,
	[dt_versao_caso_de_uso] [datetime] NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_051] PRIMARY KEY CLUSTERED
(
	[cd_caso_de_uso] ASC,
	[cd_projeto] ASC,
	[cd_modulo] ASC,
	[dt_versao_caso_de_uso] ASC,
	[cd_condicao] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_interacao](
	[cd_interacao] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_caso_de_uso] [numeric](8, 0) NOT NULL,
	[cd_ator] [numeric](8, 0) NOT NULL,
	[tx_interacao] [varchar](4000) NULL,
	[ni_ordem_interacao] [numeric](8, 0) NULL,
	[st_interacao] [char](1) NULL,
	[dt_versao_caso_de_uso] [datetime] NOT NULL,
	[id] [numeric](8, 0) NULL
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_hw_inventario](
	[cd_projeto_continuado] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_modulo_continuado] [numeric](8, 0) NOT NULL,
	[tx_servidor] [varchar](1000) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_031] PRIMARY KEY CLUSTERED
(
	[cd_projeto_continuado] ASC,
	[cd_objeto] ASC,
	[cd_modulo_continuado] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_historico_projeto_continuado](
	[cd_historico_proj_continuado] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_projeto_continuado] [numeric](8, 0) NOT NULL,
	[cd_modulo_continuado] [numeric](8, 0) NOT NULL,
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[dt_inicio_hist_proj_continuado] [datetime] NULL,
	[dt_fim_hist_projeto_continuado] [datetime] NULL,
	[tx_hist_projeto_continuado] [varchar](1000) NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_036] PRIMARY KEY CLUSTERED
(
	[cd_historico_proj_continuado] ASC,
	[cd_objeto] ASC,
	[cd_projeto_continuado] ASC,
	[cd_modulo_continuado] ASC,
	[cd_etapa] ASC,
	[cd_atividade] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_medicao_medida](
	[cd_medicao] [numeric](8, 0) NOT NULL,
	[cd_medida] [numeric](8, 0) NOT NULL,
	[st_prioridade_medida] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_128] PRIMARY KEY CLUSTERED
(
	[cd_medicao] ASC,
	[cd_medida] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_profissional_menu](
	[cd_menu] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_115] PRIMARY KEY CLUSTERED
(
	[cd_menu] ASC,
	[cd_profissional] ASC,
	[cd_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_perfil_menu](
	[cd_perfil] [numeric](8, 0) NOT NULL,
	[cd_menu] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_121] PRIMARY KEY CLUSTERED
(
	[cd_menu] ASC,
	[cd_perfil] ASC,
	[cd_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_perfil_menu_sistema](
	[cd_perfil] [numeric](8, 0) NOT NULL,
	[cd_menu] [numeric](8, 0) NOT NULL,
	[st_perfil_menu] [char](1) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_120] PRIMARY KEY CLUSTERED
(
	[cd_perfil] ASC,
	[cd_menu] ASC,
	[st_perfil_menu] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_msg_email](
	[cd_msg_email] [numeric](8, 0) NOT NULL,
	[cd_menu] [numeric](8, 0) NOT NULL,
	[tx_metodo_msg_email] [varchar](300) NULL,
	[tx_msg_email] [varchar](1000) NULL,
	[st_msg_email] [char](1) NULL,
	[tx_assunto_msg_email] [varchar](200) NULL,
	[tx_metodo_msg_email_bkp] [varchar](200) NULL,
 CONSTRAINT [pk_oasis_080] PRIMARY KEY CLUSTERED
(
	[cd_msg_email] ASC,
	[cd_menu] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_disponibilidade_servico_doc](
	[cd_disponibilidade_servico] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_tipo_documentacao] [numeric](8, 0) NOT NULL,
	[dt_doc_disponibilidade_servico] [datetime] NOT NULL,
	[tx_nome_arq_disp_servico] [varchar](1000) NULL,
	[tx_arquivo_disp_servico] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_140] PRIMARY KEY CLUSTERED
(
	[cd_disponibilidade_servico] ASC,
	[cd_objeto] ASC,
	[cd_tipo_documentacao] ASC,
	[dt_doc_disponibilidade_servico] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_agenda_plano_implantacao](
	[dt_agenda_plano_implantacao] [datetime] NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[tx_agenda_plano_implantacao] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_062] PRIMARY KEY CLUSTERED
(
	[dt_agenda_plano_implantacao] ASC,
	[cd_proposta] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_profissional](
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[tx_profissional] [varchar](1000) NULL,
	[cd_relacao_contratual] [numeric](8, 0) NULL,
	[cd_empresa] [numeric](8, 0) NULL,
	[tx_nome_conhecido] [varchar](100) NULL,
	[tx_telefone_residencial] [varchar](20) NULL,
	[tx_celular_profissional] [varchar](20) NULL,
	[tx_ramal_profissional] [varchar](10) NULL,
	[tx_endereco_profissional] [varchar](200) NULL,
	[dt_nascimento_profissional] [datetime] NULL,
	[dt_inicio_trabalho] [datetime] NULL,
	[dt_saida_profissional] [datetime] NULL,
	[tx_email_institucional] [varchar](200) NULL,
	[tx_email_pessoal] [varchar](200) NULL,
	[st_nova_senha] [char](1) NULL,
	[st_inativo] [char](1) NULL,
	[tx_senha] [varchar](50) NULL,
	[tx_data_ultimo_acesso] [varchar](1000) NULL,
	[tx_hora_ultimo_acesso] [varchar](1000) NULL,
	[cd_perfil] [numeric](8, 0) NULL,
	[st_dados_todos_contratos] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_012] PRIMARY KEY CLUSTERED
(
	[cd_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_mensageria](
	[cd_mensageria] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_perfil] [numeric](8, 0) NULL,
	[tx_mensagem] [varchar](8000) NULL,
	[dt_postagem] [datetime] NULL,
	[dt_encerramento] [datetime] NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_027] PRIMARY KEY CLUSTERED
(
	[cd_mensageria] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_demanda_prof_nivel_servico](
	[cd_demanda] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_nivel_servico] [numeric](8, 0) NOT NULL,
	[dt_fechamento_nivel_servico] [datetime] NULL,
	[st_fechamento_nivel_servico] [char](1) NULL,
	[st_fechamento_gerente] [char](1) NULL,
	[dt_fechamento_gerente] [datetime] NULL,
	[dt_leitura_nivel_servico] [datetime] NULL,
	[dt_demanda_nivel_servico] [datetime] NULL,
	[tx_motivo_fechamento] [varchar](2000) NULL,
	[tx_obs_nivel_servico] [varchar](2000) NULL,
	[dt_justificativa_nivel_servico] [datetime] NULL,
	[tx_justificativa_nivel_servico] [varchar](2000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_142] PRIMARY KEY CLUSTERED
(
	[cd_demanda] ASC,
	[cd_profissional] ASC,
	[cd_nivel_servico] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_conhecimento](
	[cd_conhecimento] [numeric](8, 0) NOT NULL,
	[cd_tipo_conhecimento] [numeric](8, 0) NOT NULL,
	[tx_conhecimento] [varchar](1000) NULL,
	[st_padrao] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_096] PRIMARY KEY CLUSTERED
(
	[cd_conhecimento] ASC,
	[cd_tipo_conhecimento] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_modulo_continuado](
	[cd_modulo_continuado] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_projeto_continuado] [numeric](8, 0) NOT NULL,
	[tx_modulo_continuado] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_025] PRIMARY KEY CLUSTERED
(
	[cd_modulo_continuado] ASC,
	[cd_objeto] ASC,
	[cd_projeto_continuado] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_historico_proposta_metrica](
	[dt_historico_proposta] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[ni_um_prop_metrica_historico] [numeric](8, 1) NULL,
	[tx_just_metrica_historico] [varchar](max) NULL,
	[id] [numeric](8, 0) NULL
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_hist_prop_sub_item_metrica](
	[dt_historico_proposta] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[cd_item_metrica] [numeric](8, 0) NOT NULL,
	[cd_sub_item_metrica] [numeric](8, 0) NOT NULL,
	[ni_valor_sub_item_metrica] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_039] PRIMARY KEY CLUSTERED
(
	[dt_historico_proposta] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC,
	[cd_definicao_metrica] ASC,
	[cd_item_metrica] ASC,
	[cd_sub_item_metrica] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_historico_proposta_parcela](
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[dt_historico_proposta] [datetime] NOT NULL,
	[cd_historico_proposta_parcela] [numeric](8, 0) NOT NULL,
	[ni_parcela] [numeric](8, 0) NOT NULL,
	[ni_mes_previsao_parcela] [numeric](8, 0) NULL,
	[ni_ano_previsao_parcela] [numeric](8, 0) NULL,
	[ni_horas_parcela] [numeric](8, 1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_033] PRIMARY KEY CLUSTERED
(
	[cd_proposta] ASC,
	[cd_projeto] ASC,
	[dt_historico_proposta] ASC,
	[cd_historico_proposta_parcela] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_informacao_tecnica](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_tipo_dado_tecnico] [numeric](8, 0) NOT NULL,
	[tx_conteudo_informacao_tecnica] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_135] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[cd_tipo_dado_tecnico] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_item_teste_requisito_doc](
	[cd_arq_item_teste_requisito] [numeric](8, 0) NOT NULL,
	[cd_item_teste_requisito] [numeric](8, 0) NOT NULL,
	[cd_item_teste] [numeric](8, 0) NOT NULL,
	[cd_requisito] [numeric](8, 0) NOT NULL,
	[dt_versao_requisito] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_tipo_documentacao] [numeric](8, 0) NOT NULL,
	[tx_nome_arq_teste_requisito] [varchar](1000) NULL,
	[tx_arq_item_teste_requisito] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_129] PRIMARY KEY CLUSTERED
(
	[cd_arq_item_teste_requisito] ASC,
	[cd_item_teste_requisito] ASC,
	[cd_requisito] ASC,
	[dt_versao_requisito] ASC,
	[cd_projeto] ASC,
	[cd_item_teste] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_item_teste_regra_negocio_doc](
	[cd_arq_item_teste_regra_neg] [numeric](8, 0) NOT NULL,
	[cd_item_teste_regra_negocio] [numeric](8, 0) NOT NULL,
	[cd_item_teste] [numeric](8, 0) NOT NULL,
	[cd_regra_negocio] [numeric](8, 0) NOT NULL,
	[dt_regra_negocio] [datetime] NOT NULL,
	[cd_projeto_regra_negocio] [numeric](8, 0) NOT NULL,
	[cd_tipo_documentacao] [numeric](8, 0) NOT NULL,
	[tx_nome_arq_teste_regra_negoc] [varchar](1000) NULL,
	[tx_arq_item_teste_regra_negoc] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_131] PRIMARY KEY CLUSTERED
(
	[cd_arq_item_teste_regra_neg] ASC,
	[dt_regra_negocio] ASC,
	[cd_regra_negocio] ASC,
	[cd_item_teste] ASC,
	[cd_projeto_regra_negocio] ASC,
	[cd_item_teste_regra_negocio] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_documentacao_profissional](
	[dt_documentacao_profissional] [datetime] NOT NULL,
	[cd_tipo_documentacao] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[tx_arq_documentacao_prof] [varchar](1000) NULL,
	[tx_nome_arquivo] [varchar](100) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_139] PRIMARY KEY CLUSTERED
(
	[dt_documentacao_profissional] ASC,
	[cd_tipo_documentacao] ASC,
	[cd_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_item_teste_caso_de_uso_doc](
	[cd_arq_item_teste_caso_de_uso] [numeric](8, 0) NOT NULL,
	[cd_item_teste_caso_de_uso] [numeric](8, 0) NOT NULL,
	[cd_item_teste] [numeric](8, 0) NOT NULL,
	[cd_caso_de_uso] [numeric](8, 0) NOT NULL,
	[dt_versao_caso_de_uso] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[cd_tipo_documentacao] [numeric](8, 0) NOT NULL,
	[tx_nome_arq_teste_caso_de_uso] [varchar](1000) NULL,
	[tx_arq_item_teste_caso_de_uso] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_133] PRIMARY KEY CLUSTERED
(
	[cd_arq_item_teste_caso_de_uso] ASC,
	[cd_item_teste] ASC,
	[cd_modulo] ASC,
	[cd_projeto] ASC,
	[cd_caso_de_uso] ASC,
	[dt_versao_caso_de_uso] ASC,
	[cd_item_teste_caso_de_uso] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_documentacao_projeto](
	[dt_documentacao_projeto] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_tipo_documentacao] [numeric](8, 0) NOT NULL,
	[tx_arq_documentacao_projeto] [varchar](1000) NULL,
	[tx_nome_arquivo] [varchar](100) NULL,
	[st_documentacao_controle] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_138] PRIMARY KEY CLUSTERED
(
	[dt_documentacao_projeto] ASC,
	[cd_projeto] ASC,
	[cd_tipo_documentacao] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_item_inventario](
	[cd_item_inventario] [numeric](8, 0) NOT NULL,
	[cd_tipo_inventario] [numeric](8, 0) NOT NULL,
	[tx_item_inventario] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_087] PRIMARY KEY CLUSTERED
(
	[cd_item_inventario] ASC,
	[cd_tipo_inventario] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_treinamento_profissional](
	[cd_treinamento] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[dt_treinamento_profissional] [datetime] NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_102] PRIMARY KEY CLUSTERED
(
	[cd_treinamento] ASC,
	[cd_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_solicitacao](
	[ni_solicitacao] [numeric](8, 0) NOT NULL,
	[ni_ano_solicitacao] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NULL,
	[cd_unidade] [numeric](8, 0) NULL,
	[tx_solicitacao] [varchar](max) NULL,
	[st_solicitacao] [char](1) NULL,
	[tx_justificativa_solicitacao] [varchar](500) NULL,
	[dt_justificativa] [datetime] NULL,
	[st_aceite] [char](1) NULL,
	[dt_aceite] [datetime] NULL,
	[tx_obs_aceite] [varchar](500) NULL,
	[st_fechamento] [char](1) NULL,
	[dt_fechamento] [datetime] NULL,
	[st_homologacao] [char](1) NULL,
	[dt_homologacao] [datetime] NULL,
	[tx_obs_homologacao] [varchar](500) NULL,
	[ni_dias_execucao] [numeric](8, 0) NULL,
	[tx_problema_encontrado] [varchar](max) NULL,
	[tx_solucao_solicitacao] [varchar](max) NULL,
	[st_grau_satisfacao] [char](1) NULL,
	[tx_obs_grau_satisfacao] [varchar](500) NULL,
	[dt_grau_satisfacao] [datetime] NULL,
	[dt_leitura_solicitacao] [datetime] NULL,
	[dt_solicitacao] [datetime] NULL,
	[tx_solicitante] [varchar](200) NULL,
	[tx_sala_solicitante] [varchar](20) NULL,
	[tx_telefone_solicitante] [varchar](20) NULL,
	[tx_obs_solicitacao] [varchar](max) NULL,
	[tx_execucao_solicitacao] [varchar](max) NULL,
	[ni_prazo_atendimento] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
	[st_aceite_just_solicitacao] [char](1) NULL,
	[tx_obs_aceite_just_solicitacao] [varchar](1000) NULL,
 CONSTRAINT [pk_oasis_003] PRIMARY KEY CLUSTERED
(
	[ni_solicitacao] ASC,
	[ni_ano_solicitacao] ASC,
	[cd_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_pre_demanda](
	[cd_pre_demanda] [numeric](8, 0) NOT NULL,
	[cd_objeto_emissor] [numeric](8, 0) NULL,
	[cd_objeto_receptor] [numeric](8, 0) NULL,
	[ni_ano_solicitacao] [numeric](8, 0) NULL,
	[ni_solicitacao] [numeric](8, 0) NULL,
	[tx_pre_demanda] [varchar](max) NULL,
	[st_aceite_pre_demanda] [char](1) NULL,
	[dt_pre_demanda] [datetime] NULL,
	[cd_profissional_solicitante] [numeric](8, 0) NULL,
	[dt_fim_pre_demanda] [datetime] NULL,
	[st_fim_pre_demanda] [char](1) NULL,
	[dt_aceite_pre_demanda] [datetime] NULL,
	[tx_obs_aceite_pre_demanda] [varchar](2000) NULL,
	[tx_obs_reabertura_pre_demanda] [varchar](2000) NULL,
	[st_reabertura_pre_demanda] [char](1) NULL,
	[cd_unidade] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_projeto](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_profissional_gerente] [numeric](8, 0) NULL,
	[cd_unidade] [numeric](8, 0) NULL,
	[cd_status] [numeric](8, 0) NULL,
	[tx_projeto] [varchar](200) NULL,
	[tx_obs_projeto] [varchar](1000) NULL,
	[tx_sigla_projeto] [varchar](200) NULL,
	[tx_gestor_projeto] [varchar](200) NULL,
	[st_impacto_projeto] [char](1) NULL,
	[st_prioridade_projeto] [char](1) NULL,
	[tx_escopo_projeto] [varchar](8000) NULL,
	[tx_contexto_geral_projeto] [varchar](8000) NULL,
	[tx_publico_alcancado] [varchar](200) NULL,
	[ni_mes_inicio_previsto] [numeric](4, 0) NULL,
	[ni_ano_inicio_previsto] [numeric](4, 0) NULL,
	[ni_mes_termino_previsto] [numeric](4, 0) NULL,
	[ni_ano_termino_previsto] [numeric](4, 0) NULL,
	[tx_co_gestor_projeto] [varchar](200) NULL,
	[st_dicionario_dados] [char](1) NULL DEFAULT ((0)),
	[st_informacoes_tecnicas] [char](1) NULL DEFAULT ((0)),
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_011] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_demanda](
	[cd_demanda] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NULL,
	[ni_ano_solicitacao] [numeric](8, 0) NULL,
	[ni_solicitacao] [numeric](8, 0) NULL,
	[dt_demanda] [datetime] NULL,
	[tx_demanda] [varchar](max) NULL,
	[st_conclusao_demanda] [char](1) NULL,
	[dt_conclusao_demanda] [datetime] NULL,
	[tx_solicitante_demanda] [varchar](200) NULL,
	[cd_unidade] [numeric](8, 0) NULL,
	[st_fechamento_demanda] [char](1) NULL,
	[dt_fechamento_demanda] [datetime] NULL,
	[st_prioridade_demanda] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_046] PRIMARY KEY CLUSTERED
(
	[cd_demanda] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_solicitacao_pedido](
	[cd_solicitacao_pedido] [numeric](8, 0) NOT NULL,
	[cd_usuario_pedido] [numeric](8, 0) NOT NULL,
	[cd_unidade_pedido] [numeric](8, 0) NOT NULL,
	[dt_solicitacao_pedido] [datetime] NOT NULL,
	[st_situacao_pedido] [char](1) NOT NULL DEFAULT ('P'),
	[tx_observacao_pedido] [varchar](max) NULL,
	[dt_encaminhamento_pedido] [datetime] NULL,
	[dt_autorizacao_competente] [datetime] NULL,
	[tx_analise_aut_competente] [varchar](max) NULL,
	[dt_analise_area_ti_solicitacao] [datetime] NULL,
	[tx_analise_area_ti_solicitacao] [varchar](max) NULL,
	[dt_analise_area_ti_chefia_sol] [datetime] NULL,
	[tx_analise_area_ti_chefia_sol] [varchar](max) NULL,
	[dt_analise_comite] [datetime] NULL,
	[tx_analise_comite] [varchar](max) NULL,
	[dt_analise_area_ti_chefia_exec] [datetime] NULL,
	[tx_analise_area_ti_chefia_exec] [varchar](max) NULL,
	[cd_usuario_aut_competente] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_153] PRIMARY KEY CLUSTERED
(
	[cd_solicitacao_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_usuario_pedido](
	[cd_usuario_pedido] [numeric](8, 0) NOT NULL,
	[cd_unidade_usuario] [numeric](8, 0) NOT NULL,
	[st_autoridade] [char](1) NOT NULL CONSTRAINT [DF__s_usuario__st_au__0880433F]  DEFAULT ('N'),
	[st_inativo] [char](1) NOT NULL CONSTRAINT [DF__s_usuario__st_in__09746778]  DEFAULT ('N'),
	[tx_nome_usuario] [varchar](100) NOT NULL,
	[tx_email_institucional] [varchar](100) NOT NULL,
	[tx_senha_acesso] [varchar](40),
	[tx_sala_usuario] [varchar](50) NULL,
	[tx_telefone_usuario] [varchar](50) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_001] PRIMARY KEY CLUSTERED
(
	[cd_usuario_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_extrato_mensal_parcela](
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[ni_ano_extrato] [numeric](8, 0) NOT NULL,
	[ni_mes_extrato] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_parcela] [numeric](8, 0) NOT NULL,
	[ni_hora_parcela_extrato] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_042] PRIMARY KEY CLUSTERED
(
	[cd_contrato] ASC,
	[ni_ano_extrato] ASC,
	[ni_mes_extrato] ASC,
	[cd_proposta] ASC,
	[cd_projeto] ASC,
	[cd_parcela] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_produto_parcela](
	[cd_produto_parcela] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_parcela] [numeric](8, 0) NOT NULL,
	[tx_produto_parcela] [varchar](8000) NULL,
	[cd_tipo_produto] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_013] PRIMARY KEY CLUSTERED
(
	[cd_produto_parcela] ASC,
	[cd_proposta] ASC,
	[cd_projeto] ASC,
	[cd_parcela] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_processamento_parcela](
	[cd_processamento_parcela] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_parcela] [numeric](8, 0) NOT NULL,
	[cd_objeto_execucao] [numeric](8, 0) NULL,
	[ni_ano_solicitacao_execucao] [numeric](8, 0) NULL,
	[ni_solicitacao_execucao] [numeric](8, 0) NULL,
	[st_autorizacao_parcela] [char](1) NULL,
	[dt_autorizacao_parcela] [datetime] NULL,
	[cd_prof_autorizacao_parcela] [numeric](8, 0) NULL,
	[st_fechamento_parcela] [char](1) NULL,
	[dt_fechamento_parcela] [datetime] NULL,
	[cd_prof_fechamento_parcela] [numeric](8, 0) NULL,
	[st_parecer_tecnico_parcela] [char](1) NULL,
	[dt_parecer_tecnico_parcela] [datetime] NULL,
	[tx_obs_parecer_tecnico_parcela] [varchar](1000) NULL,
	[cd_prof_parecer_tecnico_parc] [numeric](8, 0) NULL,
	[st_aceite_parcela] [char](1) NULL,
	[dt_aceite_parcela] [datetime] NULL,
	[tx_obs_aceite_parcela] [varchar](1000) NULL,
	[cd_profissional_aceite_parcela] [numeric](8, 0) NULL,
	[st_homologacao_parcela] [char](1) NULL,
	[dt_homologacao_parcela] [datetime] NULL,
	[tx_obs_homologacao_parcela] [varchar](1000) NULL,
	[cd_prof_homologacao_parcela] [numeric](8, 0) NULL,
	[st_ativo] [char](1) NULL,
	[st_pendente] [char](1) NULL,
	[dt_inicio_pendencia] [datetime] NULL,
	[dt_fim_pendencia] [datetime] NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_015] PRIMARY KEY CLUSTERED
(
	[cd_processamento_parcela] ASC,
	[cd_proposta] ASC,
	[cd_projeto] ASC,
	[cd_parcela] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_modulo](
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_status] [numeric](8, 0) NULL,
	[tx_modulo] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_026] PRIMARY KEY CLUSTERED
(
	[cd_modulo] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_contato_empresa](
	[cd_contato_empresa] [numeric](8, 0) NOT NULL,
	[cd_empresa] [numeric](8, 0) NOT NULL,
	[tx_contato_empresa] [varchar](1000) NULL,
	[tx_telefone_contato] [varchar](1000) NULL,
	[tx_email_contato] [varchar](1000) NULL,
	[tx_celular_contato] [varchar](1000) NULL,
	[st_gerente_conta] [char](1) NULL,
	[tx_obs_contato] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_049] PRIMARY KEY CLUSTERED
(
	[cd_contato_empresa] ASC,
	[cd_empresa] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_objeto_contrato_perfil_prof](
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_perfil_profissional] [numeric](8, 0) NOT NULL,
	[tx_descricao_perfil_prof] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_125] PRIMARY KEY CLUSTERED
(
	[cd_objeto] ASC,
	[cd_perfil_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_perfil_prof_papel_prof](
	[cd_perfil_profissional] [numeric](8, 0) NOT NULL,
	[cd_papel_profissional] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_119] PRIMARY KEY CLUSTERED
(
	[cd_perfil_profissional] ASC,
	[cd_papel_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_profissional_objeto_contrato](
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[st_recebe_email] [char](1) NULL,
	[tx_posicao_box_inicio] [varchar](8000) NULL,
	[st_objeto_padrao] [char](1) NULL,
	[cd_perfil_profissional] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_114] PRIMARY KEY CLUSTERED
(
	[cd_profissional] ASC,
	[cd_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_profissional_projeto](
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_papel_profissional] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_112] PRIMARY KEY CLUSTERED
(
	[cd_profissional] ASC,
	[cd_projeto] ASC,
	[cd_papel_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_objeto_contrato_papel_prof](
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_papel_profissional] [numeric](8, 0) NOT NULL,
	[tx_descricao_papel_prof] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_126] PRIMARY KEY CLUSTERED
(
	[cd_objeto] ASC,
	[cd_papel_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_atividade](
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[tx_atividade] [varchar](200) NULL,
	[ni_ordem_atividade] [numeric](4, 0) NULL,
	[tx_descricao_atividade] [varchar](4000) NULL,
	[id] [numeric](8, 0) NULL,
	[st_atividade_inativa] [char](1) NULL,
 CONSTRAINT [pk_oasis_099] PRIMARY KEY CLUSTERED
(
	[cd_atividade] ASC,
	[cd_etapa] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_questao_analise_risco](
	[cd_questao_analise_risco] [numeric](8, 0) NOT NULL,
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[cd_item_risco] [numeric](8, 0) NOT NULL,
	[tx_questao_analise_risco] [varchar](1000) NULL,
	[tx_obj_questao_analise_risco] [varchar](1000) NULL,
	[ni_peso_questao_analise_risco] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_074] PRIMARY KEY CLUSTERED
(
	[cd_questao_analise_risco] ASC,
	[cd_atividade] ASC,
	[cd_etapa] ASC,
	[cd_item_risco] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_historico_execucao_demanda](
	[cd_historico_execucao_demanda] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_demanda] [numeric](8, 0) NOT NULL,
	[cd_nivel_servico] [numeric](8, 0) NOT NULL,
	[dt_inicio] [datetime] NULL,
	[dt_fim] [datetime] NULL,
	[tx_historico] [varchar](max) NULL,
	[id] [numeric](8, 0) NULL
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_demanda_profissional](
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_demanda] [numeric](8, 0) NOT NULL,
	[dt_demanda_profissional] [datetime] NULL,
	[st_fechamento_demanda] [char](1) NULL,
	[dt_fechamento_demanda] [datetime] NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_141] PRIMARY KEY CLUSTERED
(
	[cd_profissional] ASC,
	[cd_demanda] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_tabela](
	[tx_tabela] [varchar](200) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[tx_descricao] [varchar](max) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_002] PRIMARY KEY CLUSTERED
(
	[tx_tabela] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_proposta](
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[ni_ano_solicitacao] [numeric](8, 0) NOT NULL,
	[ni_solicitacao] [numeric](8, 0) NOT NULL,
	[st_encerramento_proposta] [char](1) NULL,
	[dt_encerramento_proposta] [datetime] NULL,
	[cd_prof_encerramento_proposta] [numeric](8, 0) NULL,
	[ni_horas_proposta] [numeric](8, 1) NULL,
	[st_alteracao_proposta] [char](1) NULL,
	[st_contrato_anterior] [char](1) NULL,
	[tx_motivo_insatisfacao] [varchar](2000) NULL,
	[tx_gestao_qualidade] [varchar](2000) NULL,
	[st_descricao] [char](1) NULL DEFAULT ((0)),
	[st_profissional] [char](1) NULL DEFAULT ((0)),
	[st_metrica] [char](1) NULL DEFAULT ((0)),
	[st_documentacao] [char](1) NULL DEFAULT ((0)),
	[st_modulo] [char](1) NULL DEFAULT ((0)),
	[st_parcela] [char](1) NULL DEFAULT ((0)),
	[st_produto] [char](1) NULL DEFAULT ((0)),
	[st_caso_de_uso] [char](1) NULL DEFAULT ((0)),
	[ni_mes_proposta] [numeric](4, 0) NULL,
	[ni_ano_proposta] [numeric](4, 0) NULL,
	[tx_objetivo_proposta] [varchar](2000) NULL,
	[id] [numeric](8, 0) NULL,
	[st_requisito] [char](1) NULL DEFAULT ((0)),
	[nf_indice_avaliacao_proposta] [numeric](8, 0) NULL,
	[st_objetivo_proposta] [char](1) NULL DEFAULT ((0)),
	[st_suspensao_proposta] [char](1) NULL,
 CONSTRAINT [pk_oasis_008] PRIMARY KEY CLUSTERED
(
	[cd_proposta] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_ator](
	[cd_ator] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[tx_ator] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_057] PRIMARY KEY CLUSTERED
(
	[cd_ator] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_analise_execucao_projeto](
	[dt_analise_execucao_projeto] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[tx_resultado_analise_execucao] [varchar](1000) NULL,
	[tx_decisao_analise_execucao] [varchar](1000) NULL,
	[dt_decisao_analise_execucao] [datetime] NULL,
	[st_fecha_analise_execucao_proj] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_061] PRIMARY KEY CLUSTERED
(
	[dt_analise_execucao_projeto] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_conhecimento_projeto](
	[cd_tipo_conhecimento] [numeric](8, 0) NOT NULL,
	[cd_conhecimento] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_149] PRIMARY KEY CLUSTERED
(
	[cd_tipo_conhecimento] ASC,
	[cd_conhecimento] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_situacao_projeto](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[ni_mes_situacao_projeto] [numeric](8, 0) NOT NULL,
	[ni_ano_situacao_projeto] [numeric](8, 0) NOT NULL,
	[tx_situacao_projeto] [varchar](4000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_004] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[ni_mes_situacao_projeto] ASC,
	[ni_ano_situacao_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_analise_matriz_rastreab](
	[cd_analise_matriz_rastreab] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[st_matriz_rastreabilidade] [char](2) NOT NULL,
	[dt_analise_matriz_rastreab] [datetime] NOT NULL,
	[tx_analise_matriz_rastreab] [varchar](1000) NULL,
	[st_fechamento] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_060] PRIMARY KEY CLUSTERED
(
	[cd_analise_matriz_rastreab] ASC,
	[cd_projeto] ASC,
	[st_matriz_rastreabilidade] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_baseline](
	[dt_baseline] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[st_ativa] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_055] PRIMARY KEY CLUSTERED
(
	[dt_baseline] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_regra_negocio](
	[cd_regra_negocio] [numeric](8, 0) NOT NULL,
	[dt_regra_negocio] [datetime] NOT NULL,
	[cd_projeto_regra_negocio] [numeric](8, 0) NOT NULL,
	[tx_regra_negocio] [varchar](1000) NULL,
	[tx_descricao_regra_negocio] [varchar](1000) NULL,
	[st_regra_negocio] [char](1) NULL,
	[ni_versao_regra_negocio] [numeric](8, 0) NULL,
	[dt_fechamento_regra_negocio] [datetime] NULL,
	[ni_ordem_regra_negocio] [numeric](8, 0) NULL,
	[st_fechamento_regra_negocio] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_007] PRIMARY KEY CLUSTERED
(
	[cd_regra_negocio] ASC,
	[dt_regra_negocio] ASC,
	[cd_projeto_regra_negocio] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_reuniao](
	[cd_reuniao] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[dt_reuniao] [datetime] NULL,
	[tx_pauta] [varchar](8000) NULL,
	[tx_participantes] [varchar](500) NULL,
	[tx_ata] [varchar](max) NULL,
	[tx_local_reuniao] [varchar](200) NULL,
	[cd_profissional] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_005] PRIMARY KEY CLUSTERED
(
	[cd_reuniao] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]


CREATE TABLE [K_SCHEMA].[s_reuniao_geral]
(
  [cd_reuniao_geral] [numeric](10,0) NOT NULL,
  [cd_objeto] [numeric](8, 0) NOT NULL,
  [dt_reuniao] [datetime] NULL,
  [tx_pauta] [varchar](8000) NULL,
  [tx_participantes] [varchar](500) NULL,
  [tx_ata] [varchar](max) NULL,
  [tx_local_reuniao] [varchar](200) NULL,
  [cd_profissional] [numeric](8, 0) NULL,
  [id] [numeric](8,0) NULL,

  CONSTRAINT [pk_oasis_2005] PRIMARY KEY CLUSTERED(
     [cd_reuniao_geral] ASC,
     [cd_objeto] ASC
  )WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]



CREATE TABLE [K_SCHEMA].[s_requisito](
	[cd_requisito] [numeric](8, 0) NOT NULL,
	[dt_versao_requisito] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[st_tipo_requisito] [char](1) NULL,
	[tx_requisito] [varchar](1000) NULL,
	[tx_descricao_requisito] [varchar](max) NULL,
	[ni_versao_requisito] [numeric](8, 0) NULL,
	[st_prioridade_requisito] [char](1) NULL,
	[st_requisito] [char](1) NULL,
	[tx_usuario_solicitante] [varchar](1000) NULL,
	[tx_nivel_solicitante] [varchar](1000) NULL,
	[st_fechamento_requisito] [char](1) NULL,
	[dt_fechamento_requisito] [datetime] NULL,
	[ni_ordem] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_006] PRIMARY KEY CLUSTERED
(
	[cd_requisito] ASC,
	[dt_versao_requisito] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_pre_projeto_evolutivo](
	[cd_pre_projeto_evolutivo] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[tx_pre_projeto_evolutivo] [varchar](200) NULL,
	[tx_objetivo_pre_proj_evol] [varchar](1000) NULL,
	[st_gerencia_mudanca] [char](1) NULL,
	[dt_gerencia_mudanca] [datetime] NULL,
	[cd_contrato] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_017] PRIMARY KEY CLUSTERED
(
	[cd_pre_projeto_evolutivo] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_previsao_projeto_diario](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[ni_mes] [numeric](8, 0) NOT NULL,
	[ni_dia] [numeric](8, 0) NOT NULL,
	[ni_horas] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_016] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[ni_mes] ASC,
	[ni_dia] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_historico_proposta_produto](
	[cd_historico_proposta_produto] [numeric](8, 0) NOT NULL,
	[dt_historico_proposta] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_historico_proposta_parcela] [numeric](8, 0) NOT NULL,
	[tx_produto] [varchar](max) NULL,
	[cd_tipo_produto] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_sub_item_metrica](
	[cd_sub_item_metrica] [numeric](8, 0) NOT NULL,
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[cd_item_metrica] [numeric](8, 0) NOT NULL,
	[tx_sub_item_metrica] [varchar](1000) NULL,
	[tx_variavel_sub_item_metrica] [varchar](1000) NULL,
	[st_interno] [char](1) NULL,
	[ni_ordem_sub_item_metrica] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_071] PRIMARY KEY CLUSTERED
(
	[cd_sub_item_metrica] ASC,
	[cd_definicao_metrica] ASC,
	[cd_item_metrica] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_historico](
	[cd_historico] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[dt_inicio_historico] [datetime] NULL,
	[dt_fim_historico] [datetime] NULL,
	[tx_historico] [varchar](max) NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_planejamento](
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[dt_inicio_atividade] [datetime] NULL,
	[dt_fim_atividade] [datetime] NULL,
	[nf_porcentagem_execucao] [numeric](8, 0) NULL,
	[tx_obs_atividade] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_118] PRIMARY KEY CLUSTERED
(
	[cd_etapa] ASC,
	[cd_atividade] ASC,
	[cd_projeto] ASC,
	[cd_modulo] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_objeto_contrato_atividade](
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_127] PRIMARY KEY CLUSTERED
(
	[cd_objeto] ASC,
	[cd_etapa] ASC,
	[cd_atividade] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_item_risco](
	[cd_item_risco] [numeric](8, 0) NOT NULL,
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[tx_item_risco] [varchar](1000) NULL,
	[tx_descricao_item_risco] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_084] PRIMARY KEY CLUSTERED
(
	[cd_item_risco] ASC,
	[cd_etapa] ASC,
	[cd_atividade] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_condicao_sub_item_metrica](
	[cd_condicao_sub_item_metrica] [numeric](8, 0) NOT NULL,
	[cd_item_metrica] [numeric](8, 0) NOT NULL,
	[cd_definicao_metrica] [numeric](8, 0) NOT NULL,
	[cd_sub_item_metrica] [numeric](8, 0) NOT NULL,
	[tx_condicao_sub_item_metrica] [varchar](100) NULL,
	[ni_valor_condicao_satisfeita] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_097] PRIMARY KEY CLUSTERED
(
	[cd_condicao_sub_item_metrica] ASC,
	[cd_item_metrica] ASC,
	[cd_definicao_metrica] ASC,
	[cd_sub_item_metrica] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_questionario_analise_risco](
	[dt_analise_risco] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_proposta] [numeric](8, 0) NOT NULL,
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[cd_atividade] [numeric](8, 0) NOT NULL,
	[cd_item_risco] [numeric](8, 0) NOT NULL,
	[cd_questao_analise_risco] [numeric](8, 0) NOT NULL,
	[st_resposta_analise_risco] [char](3) NULL,
	[cd_profissional] [numeric](8, 0) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_107] PRIMARY KEY CLUSTERED
(
	[dt_analise_risco] ASC,
	[cd_projeto] ASC,
	[cd_proposta] ASC,
	[cd_etapa] ASC,
	[cd_atividade] ASC,
	[cd_item_risco] ASC,
	[cd_questao_analise_risco] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_penalizacao](
	[dt_penalizacao] [datetime] NOT NULL,
	[cd_contrato] [numeric](8, 0) NOT NULL,
	[cd_penalidade] [numeric](8, 0) NOT NULL,
	[tx_obs_penalizacao] [varchar](1000) NULL,
	[tx_justificativa_penalizacao] [varchar](1000) NULL,
	[ni_qtd_ocorrencia] [numeric](8, 0) NULL,
	[st_aceite_justificativa] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
	[dt_justificativa] [datetime] NULL,
	[tx_obs_justificativa] [varchar](max) NULL,
 CONSTRAINT [pk_oasis_021] PRIMARY KEY CLUSTERED
(
	[dt_penalizacao] ASC,
	[cd_contrato] ASC,
	[cd_penalidade] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_projeto_continuado](
	[cd_projeto_continuado] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[tx_projeto_continuado] [varchar](1000) NULL,
	[tx_objetivo_projeto_continuado] [varchar](1000) NULL,
	[tx_obs_projeto_continuado] [varchar](1000) NULL,
	[st_prioridade_proj_continuado] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_010] PRIMARY KEY CLUSTERED
(
	[cd_projeto_continuado] ASC,
	[cd_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_nivel_servico](
	[cd_nivel_servico] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[tx_nivel_servico] [varchar](1000) NULL,
	[st_nivel_servico] [char](1) NULL,
	[ni_horas_prazo_execucao] [numeric](8, 1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_079] PRIMARY KEY CLUSTERED
(
	[cd_nivel_servico] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_disponibilidade_servico](
	[cd_disponibilidade_servico] [numeric](8, 0) NOT NULL,
	[cd_objeto] [numeric](8, 0) NOT NULL,
	[dt_inicio_analise_disp_servico] [datetime] NULL,
	[dt_fim_analise_disp_servico] [datetime] NULL,
	[tx_analise_disp_servico] [varchar](1000) NULL,
	[ni_indice_disp_servico] [numeric](8, 2) NULL,
	[tx_parecer_disp_servico] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_045] PRIMARY KEY CLUSTERED
(
	[cd_disponibilidade_servico] ASC,
	[cd_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_opcao_resp_pergunta_pedido](
	[cd_pergunta_pedido] [numeric](8, 0) NOT NULL,
	[cd_resposta_pedido] [numeric](8, 0) NOT NULL,
	[st_resposta_texto] [char](1) NOT NULL DEFAULT ('N'),
	[ni_ordem_apresenta] [numeric](8, 0) NOT NULL DEFAULT ((0)),
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_157] PRIMARY KEY CLUSTERED
(
	[cd_pergunta_pedido] ASC,
	[cd_resposta_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_caso_de_uso](
	[cd_caso_de_uso] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_modulo] [numeric](8, 0) NOT NULL,
	[ni_ordem_caso_de_uso] [numeric](8, 0) NULL,
	[tx_caso_de_uso] [varchar](200) NULL,
	[tx_descricao_caso_de_uso] [varchar](4000) NULL,
	[dt_fechamento_caso_de_uso] [datetime] NULL,
	[dt_versao_caso_de_uso] [datetime] NOT NULL,
	[ni_versao_caso_de_uso] [numeric](8, 0) NULL,
	[st_fechamento_caso_de_uso] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_054] PRIMARY KEY CLUSTERED
(
	[cd_caso_de_uso] ASC,
	[cd_projeto] ASC,
	[cd_modulo] ASC,
	[dt_versao_caso_de_uso] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_regra_negocio_requisito](
	[cd_projeto_regra_negocio] [numeric](8, 0) NOT NULL,
	[dt_regra_negocio] [datetime] NOT NULL,
	[cd_regra_negocio] [numeric](8, 0) NOT NULL,
	[dt_versao_requisito] [datetime] NOT NULL,
	[cd_requisito] [numeric](8, 0) NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[dt_inativacao_regra] [datetime] NULL,
	[st_inativo] [char](1) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_106] PRIMARY KEY CLUSTERED
(
	[cd_projeto_regra_negocio] ASC,
	[dt_regra_negocio] ASC,
	[cd_regra_negocio] ASC,
	[dt_versao_requisito] ASC,
	[cd_requisito] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_reuniao_profissional](
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[cd_reuniao] [numeric](8, 0) NOT NULL,
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_103] PRIMARY KEY CLUSTERED
(
	[cd_projeto] ASC,
	[cd_reuniao] ASC,
	[cd_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]


CREATE TABLE [K_SCHEMA].[a_reuniao_geral_profissional]
(
  [cd_reuniao_geral] [numeric](10,0) NOT NULL,
  [cd_objeto] [numeric](8, 0) NOT NULL,
  [cd_profissional] [numeric](8, 0) NULL,  
  [id] [numeric](8, 0) NULL,
  CONSTRAINT [pk_oasis_2103] PRIMARY KEY CLUSTERED(
	[cd_reuniao_geral] ASC,
	[cd_objeto] ASC,
	[cd_profissional] ASC
  )WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]


CREATE TABLE [K_SCHEMA].[a_profissional_mensageria](
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_mensageria] [numeric](8, 0) NOT NULL,
	[dt_leitura_mensagem] [datetime] NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_116] PRIMARY KEY CLUSTERED
(
	[cd_profissional] ASC,
	[cd_mensageria] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_requisito_dependente](
	[cd_requisito_ascendente] [numeric](8, 0) NOT NULL,
	[dt_versao_requisito_ascendente] [datetime] NOT NULL,
	[cd_projeto_ascendente] [numeric](8, 0) NOT NULL,
	[cd_requisito] [numeric](8, 0) NOT NULL,
	[dt_versao_requisito] [datetime] NOT NULL,
	[cd_projeto] [numeric](8, 0) NOT NULL,
	[st_inativo] [char](1) NULL,
	[dt_inativacao_requisito] [datetime] NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_104] PRIMARY KEY CLUSTERED
(
	[cd_requisito_ascendente] ASC,
	[dt_versao_requisito_ascendente] ASC,
	[cd_projeto_ascendente] ASC,
	[cd_requisito] ASC,
	[dt_versao_requisito] ASC,
	[cd_projeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[a_profissional_conhecimento](
	[cd_profissional] [numeric](8, 0) NOT NULL,
	[cd_tipo_conhecimento] [numeric](8, 0) NOT NULL,
	[cd_conhecimento] [numeric](8, 0) NOT NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_117] PRIMARY KEY CLUSTERED
(
	[cd_profissional] ASC,
	[cd_tipo_conhecimento] ASC,
	[cd_conhecimento] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_historico_pedido](
	[cd_historico_pedido] [numeric](8, 0) NOT NULL,
	[cd_solicitacao_historico] [numeric](8, 0) NOT NULL,
	[dt_registro_historico] [datetime] NOT NULL,
	[st_acao_historico] [char](1) NOT NULL DEFAULT ('P'),
	[tx_descricao_historico] [varchar](max) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_154] PRIMARY KEY CLUSTERED
(
	[cd_historico_pedido] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_papel_profissional](
	[cd_papel_profissional] [numeric](8, 0) NOT NULL,
	[tx_papel_profissional] [varchar](200) NULL,
	[id] [numeric](8, 0) NULL,
	[cd_area_atuacao_ti] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_078] PRIMARY KEY CLUSTERED
(
	[cd_papel_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_perfil_profissional](
	[cd_perfil_profissional] [numeric](8, 0) NOT NULL,
	[tx_perfil_profissional] [varchar](200) NULL,
	[id] [numeric](8, 0) NULL,
	[cd_area_atuacao_ti] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_075] PRIMARY KEY CLUSTERED
(
	[cd_perfil_profissional] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[b_etapa](
	[cd_etapa] [numeric](8, 0) NOT NULL,
	[tx_etapa] [varchar](200) NULL,
	[ni_ordem_etapa] [numeric](4, 0) NULL,
	[tx_descricao_etapa] [varchar](4000) NULL,
	[id] [numeric](8, 0) NULL,
	[cd_area_atuacao_ti] [numeric](8, 0) NULL,
	[st_etapa_inativa] [char](1) NULL,
 CONSTRAINT [pk_oasis_093] PRIMARY KEY CLUSTERED
(
	[cd_etapa] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]






CREATE TABLE [K_SCHEMA].[s_base_conhecimento](
	[cd_base_conhecimento] [numeric](8, 0) NOT NULL,
	[cd_area_conhecimento] [numeric](8, 0) NOT NULL,
	[tx_assunto] [varchar](1000) NULL,
	[tx_problema] [varchar](1000) NULL,
	[tx_solucao] [varchar](1000) NULL,
	[id] [numeric](8, 0) NULL,
 CONSTRAINT [pk_oasis_056] PRIMARY KEY CLUSTERED
(
	[cd_base_conhecimento] ASC,
	[cd_area_conhecimento] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
