CREATE TABLE a_baseline_item_controle (
    cd_projeto decimal(8,0) NOT NULL,
    dt_baseline timestamp  NOT NULL,
    cd_item_controle_baseline decimal(8,0) NOT NULL,
    cd_item_controlado decimal(8,0) NOT NULL,
    dt_versao_item_controlado timestamp  NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_conhecimento_projeto (
    cd_tipo_conhecimento decimal NOT NULL,
    cd_conhecimento decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_conjunto_medida_medicao (
    cd_conjunto_medida decimal(8,0) NOT NULL,
    cd_box_inicio decimal(8,0) NOT NULL,
    cd_medicao decimal(8,0) NOT NULL,
    st_nivel_conjunto_medida character(1),
    id decimal(8,0)
);

CREATE TABLE a_contrato_definicao_metrica (
    cd_contrato decimal(8,0) NOT NULL,
    cd_definicao_metrica decimal(8,0) NOT NULL,
    id decimal(8,0),
    nf_fator_relacao_metrica_pad decimal(4,2),
    st_metrica_padrao character(1)
);

CREATE TABLE a_contrato_evento (
    cd_contrato decimal NOT NULL,
    cd_evento decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_contrato_item_inventario (
    cd_contrato decimal(8,0) NOT NULL,
    cd_item_inventario decimal(8,0) NOT NULL,
    id decimal(8,0),
    cd_inventario decimal(8,0)
);

CREATE TABLE a_contrato_projeto (
    cd_contrato decimal(8,0) NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_controle (
    cd_controle decimal NOT NULL,
    cd_projeto_previsto decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_contrato decimal NOT NULL,
    ni_horas decimal(8,1),
    st_controle character(1),
    st_modulo_proposta character(1),
    dt_lancamento timestamp ,
    cd_profissional decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE a_definicao_processo (
    cd_perfil decimal NOT NULL,
    cd_funcionalidade decimal(8,0) NOT NULL,
    st_definicao_processo character(1) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_demanda_prof_nivel_servico (
    cd_demanda decimal NOT NULL,
    cd_profissional decimal NOT NULL,
    cd_nivel_servico decimal NOT NULL,
    dt_fechamento_nivel_servico timestamp ,
    st_fechamento_nivel_servico character(1),
    st_fechamento_gerente character(1),
    dt_fechamento_gerente timestamp ,
    dt_leitura_nivel_servico timestamp ,
    dt_demanda_nivel_servico timestamp ,
    tx_motivo_fechamento varchar(4000),
    tx_obs_nivel_servico varchar(4000),
    dt_justificativa_nivel_servico timestamp ,
    tx_justificativa_nivel_servico varchar(4000),
    id decimal(8,0),
    st_nova_obs_nivel_servico character(1),
    tx_nova_obs_nivel_servico varchar(4000)
);

CREATE TABLE a_demanda_profissional (
    cd_profissional decimal NOT NULL,
    cd_demanda decimal NOT NULL,
    dt_demanda_profissional timestamp ,
    st_fechamento_demanda character(1),
    dt_fechamento_demanda timestamp ,
    id decimal(8,0)
);

CREATE TABLE a_disponibilidade_servico_doc (
    cd_disponibilidade_servico decimal(8,0) NOT NULL,
    cd_objeto decimal(8,0) NOT NULL,
    cd_tipo_documentacao decimal(8,0) NOT NULL,
    dt_doc_disponibilidade_servico timestamp  NOT NULL,
    tx_nome_arq_disp_servico varchar(4000),
    tx_arquivo_disp_servico varchar(4000),
    id decimal(8,0)
);

CREATE TABLE a_doc_projeto_continuo (
    dt_doc_projeto_continuo timestamp  NOT NULL,
    cd_objeto decimal NOT NULL,
    cd_projeto_continuado decimal NOT NULL,
    cd_tipo_documentacao decimal NOT NULL,
    tx_arq_doc_projeto_continuo varchar(1000),
    tx_nome_arquivo varchar(100),
    id decimal(8,0)
);

CREATE TABLE a_documentacao_contrato (
    dt_documentacao_contrato timestamp  NOT NULL,
    cd_contrato decimal NOT NULL,
    cd_tipo_documentacao decimal NOT NULL,
    tx_arq_documentacao_contrato varchar(1000),
    tx_nome_arquivo varchar(100),
    id decimal(8,0)
);

CREATE TABLE a_documentacao_profissional (
    dt_documentacao_profissional timestamp  NOT NULL,
    cd_tipo_documentacao decimal NOT NULL,
    cd_profissional decimal NOT NULL,
    tx_arq_documentacao_prof varchar (1000),
    tx_nome_arquivo varchar(100),
    id decimal(8,0)
);

CREATE TABLE a_documentacao_projeto (
    dt_documentacao_projeto timestamp  NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_tipo_documentacao decimal NOT NULL,
    tx_arq_documentacao_projeto varchar (1000),
    tx_nome_arquivo varchar(100),
    st_documentacao_controle character(1),
    id decimal(8,0),
    st_doc_acompanhamento character(1)
);

CREATE TABLE a_form_inventario (
    cd_inventario decimal(8,0) NOT NULL,
    cd_item_inventario decimal(8,0) NOT NULL,
    cd_item_inventariado decimal(8,0) NOT NULL,
    cd_subitem_inventario decimal(8,0) NOT NULL,
    cd_subitem_inv_descri decimal(8,0) NOT NULL,
    tx_valor_subitem_inventario varchar(1000),
    id decimal(8,0)
);

CREATE TABLE a_funcionalidade_menu (
    cd_funcionalidade decimal(8,0) NOT NULL,
    cd_menu decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_gerencia_mudanca (
    dt_gerencia_mudanca timestamp  NOT NULL,
    cd_item_controle_baseline decimal(8,0) NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    cd_item_controlado decimal(8,0) NOT NULL,
    dt_versao_item_controlado timestamp  NOT NULL,
    tx_motivo_mudanca varchar (1000),
    st_mudanca_metrica character(1),
    ni_custo_provavel_mudanca decimal(8,0),
    st_reuniao character(1),
    tx_decisao_mudanca varchar (1000),
    dt_decisao_mudanca date,
    cd_reuniao decimal(8,0),
    cd_projeto_reuniao decimal(8,0),
    st_decisao_mudanca character(1),
    st_execucao_mudanca character(1),
    id decimal(8,0)
);

CREATE TABLE a_informacao_tecnica (
    cd_projeto decimal NOT NULL,
    cd_tipo_dado_tecnico decimal NOT NULL,
    tx_conteudo_informacao_tecnica varchar (1000),
    id decimal(8,0)
);

CREATE TABLE a_item_inventariado (
    cd_item_inventariado decimal(8,0) NOT NULL,
    cd_inventario decimal(8,0) NOT NULL,
    cd_item_inventario decimal(8,0) NOT NULL,
    tx_item_inventariado varchar(100),
    id decimal(8,0)
);

CREATE TABLE a_item_teste_caso_de_uso (
    cd_item_teste_caso_de_uso decimal NOT NULL,
    cd_item_teste decimal(8,0) NOT NULL,
    cd_caso_de_uso decimal NOT NULL,
    dt_versao_caso_de_uso timestamp  NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    st_analise character(1),
    tx_analise varchar(4000),
    dt_analise date,
    cd_profissional_analise decimal,
    st_solucao character(1),
    tx_solucao varchar(4000),
    dt_solucao date,
    cd_profissional_solucao decimal,
    st_homologacao character(1),
    tx_homologacao varchar(4000),
    dt_homologacao date,
    cd_profissional_homologacao decimal,
    st_item_teste_caso_de_uso character(1),
    id decimal(8,0)
);

CREATE TABLE a_item_teste_caso_de_uso_doc (
    cd_arq_item_teste_caso_de_uso decimal(8,0) NOT NULL,
    cd_item_teste_caso_de_uso decimal NOT NULL,
    cd_item_teste decimal(8,0) NOT NULL,
    cd_caso_de_uso decimal NOT NULL,
    dt_versao_caso_de_uso timestamp  NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    cd_tipo_documentacao decimal NOT NULL,
    tx_nome_arq_teste_caso_de_uso varchar (1000),
    tx_arq_item_teste_caso_de_uso varchar (1000),
    id decimal(8,0)
);

CREATE TABLE a_item_teste_regra_negocio (
    cd_item_teste_regra_negocio decimal NOT NULL,
    cd_item_teste decimal NOT NULL,
    cd_regra_negocio decimal NOT NULL,
    dt_regra_negocio timestamp  NOT NULL,
    cd_projeto_regra_negocio decimal(8,0) NOT NULL,
    st_analise character(1),
    tx_analise varchar (1000),
    dt_analise date,
    cd_profissional_analise decimal,
    st_solucao character(1),
    tx_solucao varchar (1000),
    dt_solucao date,
    cd_profissional_solucao decimal,
    st_homologacao character(1),
    tx_homologacao varchar (1000),
    dt_homologacao date,
    cd_profissional_homologacao decimal,
    st_item_teste_regra_negocio character(1),
    id decimal(8,0)
);

CREATE TABLE a_item_teste_regra_negocio_doc (
    cd_arq_item_teste_regra_neg decimal(8,0) NOT NULL,
    cd_item_teste_regra_negocio decimal NOT NULL,
    cd_item_teste decimal NOT NULL,
    cd_regra_negocio decimal NOT NULL,
    dt_regra_negocio timestamp  NOT NULL,
    cd_projeto_regra_negocio decimal(8,0) NOT NULL,
    cd_tipo_documentacao decimal NOT NULL,
    tx_nome_arq_teste_regra_negoc varchar (1000),
    tx_arq_item_teste_regra_negoc varchar (1000),
    id decimal(8,0)
);

CREATE TABLE a_item_teste_requisito (
    cd_item_teste_requisito decimal NOT NULL,
    cd_item_teste decimal NOT NULL,
    cd_requisito decimal NOT NULL,
    dt_versao_requisito timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    st_analise character(1),
    tx_analise varchar (1000),
    dt_analise date,
    cd_profissional_analise decimal,
    st_solucao character(1),
    tx_solucao varchar (1000),
    dt_solucao date,
    cd_profissional_solucao decimal,
    st_homologacao character(1),
    tx_homologacao varchar (1000),
    dt_homologacao date,
    cd_profissional_homologacao decimal,
    st_item_teste_requisito character(1),
    id decimal(8,0)
);

CREATE TABLE a_item_teste_requisito_doc (
    cd_arq_item_teste_requisito decimal(8,0) NOT NULL,
    cd_item_teste_requisito decimal NOT NULL,
    cd_item_teste decimal NOT NULL,
    cd_requisito decimal NOT NULL,
    dt_versao_requisito timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    cd_tipo_documentacao decimal NOT NULL,
    tx_nome_arq_teste_requisito varchar (1000),
    tx_arq_item_teste_requisito varchar (1000),
    id decimal(8,0)
);

CREATE TABLE a_medicao_medida (
    cd_medicao decimal NOT NULL,
    cd_medida decimal NOT NULL,
    st_prioridade_medida character(1),
    id decimal(8,0)
);

CREATE TABLE a_objeto_contrato_atividade (
    cd_objeto decimal(8,0) NOT NULL,
    cd_etapa decimal(8,0) NOT NULL,
    cd_atividade decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_objeto_contrato_papel_prof (
    cd_objeto decimal(8,0) NOT NULL,
    cd_papel_profissional decimal(8,0) NOT NULL,
    tx_descricao_papel_prof varchar (1000),
    id decimal(8,0)
);

CREATE TABLE a_objeto_contrato_perfil_prof (
    cd_objeto decimal(8,0) NOT NULL,
    cd_perfil_profissional decimal(8,0) NOT NULL,
    tx_descricao_perfil_prof varchar (1000),
    id decimal(8,0)
);

CREATE TABLE a_objeto_contrato_rotina (
    cd_objeto decimal(8,0) NOT NULL,
    cd_rotina decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_opcao_resp_pergunta_pedido (
    cd_pergunta_pedido integer NOT NULL,
    cd_resposta_pedido integer NOT NULL,
    st_resposta_texto character(1) ,
    ni_ordem_apresenta integer DEFAULT 0 NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_parecer_tecnico_parcela (
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_parcela decimal NOT NULL,
    cd_item_parecer_tecnico decimal NOT NULL,
    cd_processamento_parcela decimal(8,0) NOT NULL,
    st_avaliacao character(2),
    id decimal(8,0)
);

CREATE TABLE a_parecer_tecnico_proposta (
    cd_item_parecer_tecnico decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_processamento_proposta decimal(8,0) NOT NULL,
    st_avaliacao character(2),
    id decimal(8,0)
);

CREATE TABLE a_perfil_box_inicio (
    cd_perfil decimal NOT NULL,
    cd_box_inicio decimal NOT NULL,
    cd_objeto decimal NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_perfil_menu (
    cd_perfil decimal NOT NULL,
    cd_menu decimal NOT NULL,
    cd_objeto decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_perfil_menu_sistema (
    cd_perfil decimal NOT NULL,
    cd_menu decimal NOT NULL,
    st_perfil_menu character(1) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_perfil_prof_papel_prof (
    cd_perfil_profissional decimal(8,0) NOT NULL,
    cd_papel_profissional decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_pergunta_depende_resp_pedido (
    cd_pergunta_depende integer NOT NULL,
    cd_pergunta_pedido integer NOT NULL,
    cd_resposta_pedido integer NOT NULL,
    st_tipo_dependencia character(1) ,
    id decimal(8,0)
);

CREATE TABLE a_planejamento (
    cd_etapa decimal NOT NULL,
    cd_atividade decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    dt_inicio_atividade date,
    dt_fim_atividade date,
    nf_porcentagem_execucao decimal,
    tx_obs_atividade varchar (1000),
    id decimal(8,0)
);

CREATE TABLE a_profissional_conhecimento (
    cd_profissional decimal NOT NULL,
    cd_tipo_conhecimento decimal NOT NULL,
    cd_conhecimento decimal NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_profissional_mensageria (
    cd_profissional decimal NOT NULL,
    cd_mensageria decimal(8,0) NOT NULL,
    dt_leitura_mensagem timestamp ,
    id decimal(8,0)
);

CREATE TABLE a_profissional_menu (
    cd_menu decimal NOT NULL,
    cd_profissional decimal NOT NULL,
    cd_objeto decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_profissional_objeto_contrato (
    cd_profissional decimal NOT NULL,
    cd_objeto decimal NOT NULL,
    st_recebe_email character(1),
    tx_posicao_box_inicio varchar(4000),
    st_objeto_padrao character(1),
    cd_perfil_profissional decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE a_profissional_produto (
    cd_profissional decimal(8,0) NOT NULL,
    cd_produto_parcela decimal(8,0) NOT NULL,
    cd_proposta decimal(8,0) NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    cd_parcela decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_profissional_projeto (
    cd_profissional decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_papel_profissional decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_proposta_definicao_metrica (
    cd_projeto decimal(8,0) NOT NULL,
    cd_proposta decimal(8,0) NOT NULL,
    cd_definicao_metrica decimal(8,0) NOT NULL,
    ni_horas_proposta_metrica decimal(8,1),
    tx_justificativa_metrica varchar(4000),
    id decimal(8,0)
);

CREATE TABLE a_proposta_modulo (
    cd_projeto decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    st_criacao_modulo character(1),
    id decimal(8,0)
);

CREATE TABLE a_proposta_sub_item_metrica (
    cd_projeto decimal(8,0) NOT NULL,
    cd_proposta decimal(8,0) NOT NULL,
    cd_item_metrica decimal(8,0) NOT NULL,
    cd_definicao_metrica decimal(8,0) NOT NULL,
    cd_sub_item_metrica decimal(8,0) NOT NULL,
    ni_valor_sub_item_metrica decimal(8,1),
    id decimal(8,0)
);

CREATE TABLE a_quest_avaliacao_qualidade (
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_grupo_fator decimal(8,0) NOT NULL,
    cd_item_grupo_fator decimal(8,0) NOT NULL,
    st_avaliacao_qualidade character(1),
    id decimal(8,0)
);

CREATE TABLE a_questionario_analise_risco (
    dt_analise_risco timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_etapa decimal(8,0) NOT NULL,
    cd_atividade decimal(8,0) NOT NULL,
    cd_item_risco decimal NOT NULL,
    cd_questao_analise_risco decimal NOT NULL,
    st_resposta_analise_risco character(3),
    cd_profissional decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE a_regra_negocio_requisito (
    cd_projeto_regra_negocio decimal(8,0) NOT NULL,
    dt_regra_negocio timestamp  NOT NULL,
    cd_regra_negocio decimal NOT NULL,
    dt_versao_requisito timestamp  NOT NULL,
    cd_requisito decimal NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    dt_inativacao_regra date,
    st_inativo character(1),
    id decimal(8,0)
);

CREATE TABLE a_requisito_caso_de_uso (
    cd_projeto decimal(8,0) NOT NULL,
    dt_versao_requisito timestamp  NOT NULL,
    cd_requisito decimal NOT NULL,
    dt_versao_caso_de_uso timestamp  NOT NULL,
    cd_caso_de_uso decimal NOT NULL,
    cd_modulo decimal(8,0) NOT NULL,
    dt_inativacao_caso_de_uso date,
    st_inativo character(1),
    id decimal(8,0)
);

CREATE TABLE a_requisito_dependente (
    cd_requisito_ascendente decimal NOT NULL,
    dt_versao_requisito_ascendente timestamp  NOT NULL,
    cd_projeto_ascendente decimal(8,0) NOT NULL,
    cd_requisito decimal NOT NULL,
    dt_versao_requisito timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    st_inativo character(1),
    dt_inativacao_requisito date,
    id decimal(8,0)
);

CREATE TABLE a_reuniao_geral_profissional (
    cd_objeto decimal NOT NULL,
    cd_reuniao_geral decimal NOT NULL,
    cd_profissional decimal NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_reuniao_profissional (
    cd_projeto decimal NOT NULL,
    cd_reuniao decimal NOT NULL,
    cd_profissional decimal NOT NULL,
    id decimal(8,0)
);

CREATE TABLE a_rotina_profissional (
    cd_objeto decimal(8,0) NOT NULL,
    cd_profissional decimal(8,0) NOT NULL,
    cd_rotina decimal(8,0) NOT NULL,
    st_inativa_rotina_profissional character(1),
    id decimal(8,0)
);

CREATE TABLE a_solicitacao_resposta_pedido (
    cd_solicitacao_pedido integer NOT NULL,
    cd_pergunta_pedido integer NOT NULL,
    cd_resposta_pedido integer NOT NULL,
    tx_descricao_resposta text,
    id decimal(8,0)
);

CREATE TABLE a_treinamento_profissional (
    cd_treinamento decimal(8,0) NOT NULL,
    cd_profissional decimal NOT NULL,
    dt_treinamento_profissional date,
    id decimal(8,0)
);

CREATE TABLE b_area_atuacao_ti (
    cd_area_atuacao_ti decimal(8,0) NOT NULL,
    tx_area_atuacao_ti varchar(200),
    id decimal(8,0)
);

CREATE TABLE b_area_conhecimento (
    cd_area_conhecimento decimal NOT NULL,
    tx_area_conhecimento varchar (1000),
    id decimal(8,0)
);

CREATE TABLE b_atividade (
    cd_atividade decimal NOT NULL,
    cd_etapa decimal NOT NULL,
    tx_atividade varchar (1000),
    ni_ordem_atividade decimal(4,0),
    tx_descricao_atividade varchar(4000),
    id decimal(8,0),
    st_atividade_inativa character(1)
);

CREATE TABLE b_box_inicio (
    cd_box_inicio decimal NOT NULL,
    tx_box_inicio varchar(100) NOT NULL,
    st_tipo_box_inicio character(1) NOT NULL,
    tx_titulo_box_inicio varchar(100) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE b_condicao_sub_item_metrica (
    cd_condicao_sub_item_metrica decimal(8,0) NOT NULL,
    cd_item_metrica decimal(8,0) NOT NULL,
    cd_definicao_metrica decimal(8,0) NOT NULL,
    cd_sub_item_metrica decimal(8,0) NOT NULL,
    tx_condicao_sub_item_metrica varchar(100),
    ni_valor_condicao_satisfeita decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE b_conhecimento (
    cd_conhecimento decimal NOT NULL,
    cd_tipo_conhecimento decimal NOT NULL,
    tx_conhecimento varchar (1000),
    st_padrao character(1),
    id decimal(8,0)
);

CREATE TABLE b_conjunto_medida (
    cd_conjunto_medida decimal(8,0) NOT NULL,
    tx_conjunto_medida varchar(500),
    id decimal(8,0)
);

CREATE TABLE b_definicao_metrica (
    cd_definicao_metrica decimal(8,0) NOT NULL,
    tx_nome_metrica varchar (1000),
    tx_sigla_metrica varchar (1000),
    tx_descricao_metrica varchar (1000),
    tx_formula_metrica varchar (1000),
    st_justificativa_metrica character(1),
    id decimal(8,0),
    tx_sigla_unidade_metrica varchar(10),
    tx_unidade_metrica varchar(100)
);

CREATE TABLE b_etapa (
    cd_etapa decimal NOT NULL,
    tx_etapa varchar (1000),
    ni_ordem_etapa decimal(4,0),
    tx_descricao_etapa varchar(4000),
    id decimal(8,0),
    cd_area_atuacao_ti decimal(8,0),
    st_etapa_inativa character(1)
);

CREATE TABLE b_evento (
    cd_evento decimal(8,0) NOT NULL,
    tx_evento varchar(200),
    id decimal(8,0)
);

CREATE TABLE b_funcionalidade (
    cd_funcionalidade decimal(8,0) NOT NULL,
    tx_codigo_funcionalidade varchar(20),
    tx_funcionalidade varchar(200),
    st_funcionalidade character(1),
    id decimal(8,0)
);

CREATE TABLE b_grupo_fator (
    cd_grupo_fator decimal(8,0) NOT NULL,
    tx_grupo_fator varchar(100),
    ni_peso_grupo_fator decimal(8,0) NOT NULL,
    ni_ordem_grupo_fator decimal NOT NULL,
    id decimal(8,0),
    ni_indice_grupo_fator decimal(8,0) NOT NULL
);

CREATE TABLE b_item_controle_baseline (
    cd_item_controle_baseline decimal(8,0) NOT NULL,
    tx_item_controle_baseline varchar(500),
    id decimal(8,0)
);

CREATE TABLE b_item_grupo_fator (
    cd_item_grupo_fator decimal(8,0) NOT NULL,
    cd_grupo_fator decimal(8,0) NOT NULL,
    tx_item_grupo_fator varchar(300),
    ni_ordem_item_grupo_fator decimal NOT NULL,
    id decimal(8,0)
);

CREATE TABLE b_item_inventario (
    cd_item_inventario decimal(8,0) NOT NULL,
    cd_area_atuacao_ti decimal(8,0) NOT NULL,
    tx_item_inventario varchar(100),
    id decimal(8,0)
);

CREATE TABLE b_item_metrica (
    cd_item_metrica decimal(8,0) NOT NULL,
    cd_definicao_metrica decimal(8,0) NOT NULL,
    tx_item_metrica varchar (1000),
    tx_variavel_item_metrica varchar (1000),
    ni_ordem_item_metrica integer,
    tx_formula_item_metrica varchar(500),
    st_interno_item_metrica character(1),
    id decimal(8,0)
);

CREATE TABLE b_item_parecer_tecnico (
    cd_item_parecer_tecnico decimal NOT NULL,
    tx_item_parecer_tecnico varchar (1000),
    st_proposta character(1),
    st_parcela character(1),
    st_viagem character(1),
    id decimal(8,0),
    tx_descricao varchar(4000)
);

CREATE TABLE b_item_risco (
    cd_item_risco decimal NOT NULL,
    cd_etapa decimal(8,0) NOT NULL,
    cd_atividade decimal(8,0) NOT NULL,
    tx_item_risco varchar (1000),
    tx_descricao_item_risco varchar (1000),
    id decimal(8,0)
);

CREATE TABLE b_item_teste (
    cd_item_teste decimal(8,0) NOT NULL,
    tx_item_teste varchar(1000),
    st_item_teste character(1),
    st_obrigatorio character(1),
    st_tipo_item_teste character(1),
    ni_ordem_item_teste decimal,
    id decimal(8,0)
);

CREATE TABLE b_medida (
    cd_medida decimal NOT NULL,
    tx_medida varchar (1000),
    tx_objetivo_medida varchar(4000),
    id decimal(8,0)
);

CREATE TABLE b_menu (
    cd_menu decimal NOT NULL,
    cd_menu_pai decimal,
    tx_menu varchar (1000),
    ni_nivel_menu decimal,
    tx_pagina varchar (1000),
    st_menu character(1),
    tx_modulo varchar(50),
    id decimal(8,0)
);

CREATE TABLE b_msg_email (
    cd_msg_email decimal(8,0) NOT NULL,
    cd_menu decimal(8,0) NOT NULL,
    tx_metodo_msg_email varchar(300),
    tx_msg_email varchar(1000),
    st_msg_email character(1),
    tx_assunto_msg_email varchar(200)
);

CREATE TABLE b_nivel_servico (
    cd_nivel_servico decimal(8,0) NOT NULL,
    cd_objeto decimal(8,0) NOT NULL,
    tx_nivel_servico varchar (1000),
    st_nivel_servico character(1),
    ni_horas_prazo_execucao decimal(8,1),
    id decimal(8,0)
);

CREATE TABLE b_papel_profissional (
    cd_papel_profissional decimal(8,0) NOT NULL,
    tx_papel_profissional varchar(200),
    id decimal(8,0),
    cd_area_atuacao_ti decimal(8,0)
);

CREATE TABLE b_penalidade (
    cd_penalidade decimal NOT NULL,
    cd_contrato decimal NOT NULL,
    tx_penalidade varchar (1000),
    tx_abreviacao_penalidade varchar (1000),
    ni_valor_penalidade decimal(8,2),
    ni_penalidade decimal,
    st_ocorrencia character(1),
    id decimal(8,0)
);

CREATE TABLE b_perfil (
    cd_perfil decimal NOT NULL,
    tx_perfil varchar (1000),
    id decimal(8,0),
    st_tipo_perfil character(1),
    st_tipo_atuacao character(1)
);

CREATE TABLE b_perfil_profissional (
    cd_perfil_profissional decimal(8,0) NOT NULL,
    tx_perfil_profissional varchar(200),
    id decimal(8,0),
    cd_area_atuacao_ti decimal(8,0)
);

CREATE TABLE b_pergunta_pedido (
    cd_pergunta_pedido integer NOT NULL,
    tx_titulo_pergunta varchar(200) NOT NULL,
    st_multipla_resposta character(1) ,
    st_obriga_resposta character(1) ,
    tx_ajuda_pergunta varchar (1000),
    id decimal(8,0)
);

CREATE TABLE b_questao_analise_risco (
    cd_questao_analise_risco decimal NOT NULL,
    cd_atividade decimal(8,0) NOT NULL,
    cd_etapa decimal(8,0) NOT NULL,
    cd_item_risco decimal NOT NULL,
    tx_questao_analise_risco varchar (1000),
    tx_obj_questao_analise_risco varchar (1000),
    ni_peso_questao_analise_risco decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE b_relacao_contratual (
    cd_relacao_contratual decimal NOT NULL,
    tx_relacao_contratual varchar (1000),
    id decimal(8,0)
);

CREATE TABLE b_resposta_pedido (
    cd_resposta_pedido integer NOT NULL,
    tx_titulo_resposta varchar(150) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE b_rotina (
    cd_rotina decimal(8,0) NOT NULL,
    cd_area_atuacao_ti decimal(8,0) NOT NULL,
    tx_rotina varchar(200),
    tx_hora_inicio_rotina varchar(8),
    st_periodicidade_rotina character(1),
    ni_prazo_execucao_rotina decimal(8,0),
    id decimal(8,0),
    ni_dia_semana_rotina decimal(1,0),
    ni_dia_mes_rotina decimal(2,0),
    st_rotina_inativa character(1)
);

CREATE TABLE b_status (
    cd_status decimal NOT NULL,
    tx_status varchar (1000),
    id decimal(8,0)
);

CREATE TABLE b_status_atendimento (
    cd_status_atendimento decimal(8,0) NOT NULL,
    tx_status_atendimento varchar(100),
    tx_rgb_status_atendimento varchar(8),
    st_status_atendimento varchar(1),
    ni_percent_tempo_resposta_ini decimal(3,0),
    ni_percent_tempo_resposta_fim decimal(3,0),
    id decimal(8,0)
);

CREATE TABLE b_sub_item_metrica (
    cd_sub_item_metrica decimal(8,0) NOT NULL,
    cd_definicao_metrica decimal(8,0) NOT NULL,
    cd_item_metrica decimal(8,0) NOT NULL,
    tx_sub_item_metrica varchar (1000),
    tx_variavel_sub_item_metrica varchar (1000),
    st_interno character(1),
    ni_ordem_sub_item_metrica decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE b_subitem_inv_descri (
    cd_item_inventario decimal(8,0) NOT NULL,
    cd_subitem_inventario decimal(8,0) NOT NULL,
    cd_subitem_inv_descri decimal(8,0) NOT NULL,
    tx_subitem_inv_descri varchar(100),
    id decimal(8,0),
    ni_ordem integer
);

CREATE TABLE b_subitem_inventario (
    cd_item_inventario decimal(8,0) NOT NULL,
    cd_subitem_inventario decimal(8,0) NOT NULL,
    tx_subitem_inventario varchar(100),
    id decimal(8,0),
    st_info_chamado_tecnico character(1)
);

CREATE TABLE b_tipo_conhecimento (
    cd_tipo_conhecimento decimal NOT NULL,
    tx_tipo_conhecimento varchar (1000),
    id decimal(8,0),
    st_para_profissionais character(1)
);

CREATE TABLE b_tipo_dado_tecnico (
    cd_tipo_dado_tecnico decimal NOT NULL,
    tx_tipo_dado_tecnico varchar (1000),
    id decimal(8,0)
);

CREATE TABLE b_tipo_documentacao (
    cd_tipo_documentacao decimal NOT NULL,
    tx_tipo_documentacao varchar (1000),
    tx_extensao_documentacao varchar (1000),
    st_classificacao character(1),
    id decimal(8,0)
);

CREATE TABLE b_tipo_produto (
    cd_tipo_produto decimal NOT NULL,
    tx_tipo_produto varchar (1000),
    ni_ordem_tipo_produto decimal(4,0),
    cd_definicao_metrica decimal(8,0),
    id decimal(8,0)
);



CREATE TABLE b_treinamento (
    cd_treinamento decimal(8,0) NOT NULL,
    tx_treinamento varchar(500),
    tx_obs_treinamento varchar(4000),
    id decimal(8,0)
);

CREATE TABLE b_unidade (
    cd_unidade decimal NOT NULL,
    tx_sigla_unidade varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_acompanhamento_proposta (
    cd_acompanhamento_proposta decimal(8,0) NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    tx_acompanhamento_proposta varchar(4000),
    st_restrito character(1),
    dt_acompanhamento_proposta timestamp ,
    id decimal(8,0)
);

CREATE TABLE s_agenda_plano_implantacao (
    dt_agenda_plano_implantacao timestamp  NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    tx_agenda_plano_implantacao varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_analise_execucao_projeto (
    dt_analise_execucao_projeto timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    tx_resultado_analise_execucao varchar (1000),
    tx_decisao_analise_execucao varchar (1000),
    dt_decisao_analise_execucao date,
    st_fecha_analise_execucao_proj character(1),
    id decimal(8,0)
);

CREATE TABLE s_analise_matriz_rastreab (
    cd_analise_matriz_rastreab decimal(8,0) NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    st_matriz_rastreabilidade character(2) NOT NULL,
    dt_analise_matriz_rastreab date NOT NULL,
    tx_analise_matriz_rastreab varchar (1000),
    st_fechamento character(1),
    id decimal(8,0)
);

CREATE TABLE s_analise_medicao (
    dt_analise_medicao timestamp  NOT NULL,
    cd_medicao decimal NOT NULL,
    cd_box_inicio decimal NOT NULL,
    cd_profissional decimal NOT NULL,
    tx_resultado_analise_medicao varchar (1000),
    tx_dados_medicao varchar (1000),
    tx_decisao varchar (1000),
    dt_decisao date,
    st_decisao_executada character(1),
    dt_decisao_executada date,
    tx_obs_decisao_executada varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_analise_risco (
    dt_analise_risco timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_etapa decimal(8,0) NOT NULL,
    cd_atividade decimal(8,0) NOT NULL,
    cd_item_risco decimal NOT NULL,
    st_fator_risco character(3),
    st_impacto_projeto_risco character(3),
    st_impacto_tecnico_risco character(3),
    st_impacto_custo_risco character(3),
    st_impacto_cronograma_risco character(3),
    tx_analise_risco varchar (1000),
    tx_acao_analise_risco varchar (1000),
    st_fechamento_risco character(1),
    cd_profissional decimal(8,0),
    cd_profissional_responsavel decimal(8,0),
    dt_limite_acao date,
    st_acao character(1),
    tx_observacao_acao varchar (1000),
    dt_fechamento_risco date,
    tx_cor_impacto_cronog_risco character(20),
    tx_cor_impacto_custo_risco character(20),
    tx_cor_impacto_projeto_risco character(20),
    tx_cor_impacto_tecnico_risco character(20),
    id decimal(8,0),
    st_nao_aplica_risco character(1),
    tx_mitigacao_risco varchar(4000)
);

CREATE TABLE s_arquivo_pedido (
    cd_arquivo_pedido integer NOT NULL,
    cd_pergunta_pedido integer NOT NULL,
    cd_resposta_pedido integer NOT NULL,
    cd_solicitacao_pedido integer NOT NULL,
    tx_titulo_arquivo varchar(100) NOT NULL,
    tx_nome_arquivo varchar(20) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE s_ator (
    cd_ator decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    tx_ator varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_base_conhecimento (
    cd_base_conhecimento decimal NOT NULL,
    cd_area_conhecimento decimal NOT NULL,
    tx_assunto varchar (1000),
    tx_problema varchar (1000),
    tx_solucao varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_baseline (
    dt_baseline timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    st_ativa character(1),
    id decimal(8,0)
);

CREATE TABLE s_caso_de_uso (
    cd_caso_de_uso decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    ni_ordem_caso_de_uso decimal,
    tx_caso_de_uso varchar (1000),
    tx_descricao_caso_de_uso varchar(4000),
    dt_fechamento_caso_de_uso timestamp ,
    dt_versao_caso_de_uso timestamp  NOT NULL,
    ni_versao_caso_de_uso decimal(8,0),
    st_fechamento_caso_de_uso character(1),
    id decimal(8,0)
);

CREATE TABLE s_coluna (
    tx_tabela varchar(100) NOT NULL,
    tx_coluna varchar(100) NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    tx_descricao varchar (1000),
    st_chave character(1),
    tx_tabela_referencia varchar (1000),
    cd_projeto_referencia decimal(8,0) NOT NULL,
    id decimal(8,0)
);

CREATE TABLE s_complemento (
    cd_complemento decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_caso_de_uso decimal NOT NULL,
    tx_complemento varchar (1000),
    st_complemento character(1),
    ni_ordem_complemento decimal,
    dt_versao_caso_de_uso timestamp  NOT NULL,
    id decimal(8,0)
);

CREATE TABLE s_condicao (
    cd_condicao decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_caso_de_uso decimal NOT NULL,
    tx_condicao varchar (1000),
    st_condicao character(1),
    dt_versao_caso_de_uso timestamp  NOT NULL,
    id decimal(8,0)
);

CREATE TABLE s_config_banco_de_dados (
    cd_projeto decimal NOT NULL,
    tx_adapter varchar(100),
    tx_host varchar(100),
    tx_dbname varchar(100),
    tx_username varchar(100),
    tx_password varchar(100),
    tx_schema varchar(100),
    id decimal(8,0),
    tx_port varchar(100)
);

CREATE TABLE s_contato_empresa (
    cd_contato_empresa decimal(8,0) NOT NULL,
    cd_empresa decimal NOT NULL,
    tx_contato_empresa varchar (1000),
    tx_telefone_contato varchar (1000),
    tx_email_contato varchar (1000),
    tx_celular_contato varchar (1000),
    st_gerente_conta character(1),
    tx_obs_contato varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_contrato (
    cd_contrato decimal NOT NULL,
    cd_empresa decimal NOT NULL,
    tx_numero_contrato varchar (1000),
    dt_inicio_contrato date,
    dt_fim_contrato date,
    st_aditivo character(1),
    tx_cpf_gestor varchar (1000),
    ni_horas_previstas decimal,
    tx_objeto varchar (1000),
    tx_gestor_contrato varchar (1000),
    tx_fone_gestor_contrato varchar (1000),
    tx_numero_processo varchar(20),
    tx_obs_contrato varchar (1000),
    tx_localizacao_arquivo varchar (1000),
    tx_co_gestor varchar (1000),
    tx_cpf_co_gestor varchar (1000),
    tx_fone_co_gestor_contrato varchar (1000),
    nf_valor_passagens_diarias decimal(15,2),
    nf_valor_unitario_diaria decimal(15,2),
    st_contrato character(1),
    ni_mes_inicial_contrato decimal(4,0),
    ni_ano_inicial_contrato decimal(4,0),
    ni_mes_final_contrato decimal(4,0),
    ni_ano_final_contrato decimal(4,0),
    ni_qtd_meses_contrato decimal(4,0),
    nf_valor_unitario_hora decimal(15,2),
    nf_valor_contrato decimal(15,2),
    cd_contato_empresa decimal(8,0),
    id decimal(8,0),
    cd_definicao_metrica decimal(8,0)
);

CREATE TABLE s_custo_contrato_demanda (
    cd_contrato decimal NOT NULL,
    ni_mes_custo_contrato_demanda integer NOT NULL,
    ni_ano_custo_contrato_demanda integer NOT NULL,
    nf_total_multa decimal(8,2),
    nf_total_glosa decimal(8,2),
    nf_total_pago decimal(8,2),
    id decimal(8,0)
);

CREATE TABLE s_demanda (
    cd_demanda decimal NOT NULL,
    cd_objeto decimal,
    ni_ano_solicitacao decimal,
    ni_solicitacao decimal,
    dt_demanda timestamp ,
    tx_demanda varchar (1000),
    st_conclusao_demanda character(1),
    dt_conclusao_demanda timestamp ,
    tx_solicitante_demanda varchar(200),
    cd_unidade decimal(8,0),
    st_fechamento_demanda character(1),
    dt_fechamento_demanda timestamp ,
    st_prioridade_demanda character(1),
    id decimal(8,0),
    cd_status_atendimento decimal(8,0)
);

CREATE TABLE s_disponibilidade_servico (
    cd_disponibilidade_servico decimal(8,0) NOT NULL,
    cd_objeto decimal(8,0) NOT NULL,
    dt_inicio_analise_disp_servico date,
    dt_fim_analise_disp_servico date,
    tx_analise_disp_servico varchar (1000),
    ni_indice_disp_servico decimal(8,2),
    tx_parecer_disp_servico varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_empresa (
    cd_empresa decimal NOT NULL,
    tx_empresa varchar (1000),
    tx_cnpj_empresa varchar (1000),
    tx_endereco_empresa varchar (1000),
    tx_telefone_empresa varchar(20),
    tx_email_empresa varchar(200),
    tx_fax_empresa varchar(30),
    tx_arquivo_logomarca varchar(100),
    id decimal(8,0)
);

CREATE TABLE s_execucao_rotina (
    dt_execucao_rotina date NOT NULL,
    cd_profissional decimal(8,0) NOT NULL,
    cd_objeto decimal(8,0) NOT NULL,
    cd_rotina decimal(8,0) NOT NULL,
    st_fechamento_execucao_rotina character(1),
    dt_just_execucao_rotina timestamp ,
    tx_just_execucao_rotina varchar(4000),
    st_historico character(1),
    id decimal(8,0),
    tx_hora_execucao_rotina varchar(8)
);

CREATE TABLE s_extrato_mensal (
    ni_mes_extrato decimal NOT NULL,
    ni_ano_extrato decimal NOT NULL,
    cd_contrato decimal NOT NULL,
    dt_fechamento_extrato date,
    ni_horas_extrato decimal,
    ni_qtd_parcela decimal,
    id decimal(8,0)
);

CREATE TABLE s_extrato_mensal_parcela (
    cd_contrato decimal NOT NULL,
    ni_ano_extrato decimal NOT NULL,
    ni_mes_extrato decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_parcela decimal NOT NULL,
    ni_hora_parcela_extrato decimal,
    id decimal(8,0)
);

CREATE TABLE s_fale_conosco (
    cd_fale_conosco decimal NOT NULL,
    tx_nome varchar (1000),
    tx_email varchar (1000),
    tx_assunto varchar (1000),
    tx_mensagem varchar (1000),
    tx_resposta varchar (1000),
    st_respondida character(1),
    dt_registro timestamp ,
    st_pendente character(1),
    id decimal(8,0)
);

CREATE TABLE s_gerencia_qualidade (
    cd_gerencia_qualidade decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_profissional decimal NOT NULL,
    dt_auditoria_qualidade date,
    tx_fase_projeto varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_hist_prop_sub_item_metrica (
    dt_historico_proposta timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    cd_proposta decimal(8,0) NOT NULL,
    cd_definicao_metrica decimal(8,0) NOT NULL,
    cd_item_metrica decimal(8,0) NOT NULL,
    cd_sub_item_metrica decimal(8,0) NOT NULL,
    ni_valor_sub_item_metrica decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE s_historico (
    cd_historico decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    cd_etapa decimal NOT NULL,
    cd_atividade decimal NOT NULL,
    dt_inicio_historico date,
    dt_fim_historico date,
    tx_historico varchar (1000),
    cd_profissional decimal NOT NULL,
    id decimal(8,0)
);

CREATE TABLE s_historico_execucao_demanda (
    cd_historico_execucao_demanda decimal NOT NULL,
    cd_profissional decimal NOT NULL,
    cd_demanda decimal NOT NULL,
    cd_nivel_servico decimal NOT NULL,
    dt_inicio timestamp ,
    dt_fim timestamp ,
    tx_historico varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_historico_execucao_rotina (
    dt_historico_execucao_rotina timestamp  NOT NULL,
    cd_rotina decimal(8,0) NOT NULL,
    cd_objeto decimal(8,0) NOT NULL,
    cd_profissional decimal(8,0) NOT NULL,
    dt_execucao_rotina date NOT NULL,
    tx_historico_execucao_rotina varchar(4000),
    dt_historico_rotina timestamp ,
    id decimal(8,0)
);

CREATE TABLE s_historico_pedido (
    cd_historico_pedido integer NOT NULL,
    cd_solicitacao_historico integer NOT NULL,
    dt_registro_historico timestamp  ,
    st_acao_historico character(1) ,
    tx_descricao_historico text,
    id decimal(8,0)
);

CREATE TABLE s_historico_projeto_continuado (
    cd_historico_proj_continuado decimal NOT NULL,
    cd_objeto decimal NOT NULL,
    cd_projeto_continuado decimal NOT NULL,
    cd_modulo_continuado decimal NOT NULL,
    cd_etapa decimal NOT NULL,
    cd_atividade decimal NOT NULL,
    dt_inicio_hist_proj_continuado date,
    dt_fim_hist_projeto_continuado date,
    tx_hist_projeto_continuado varchar (1000),
    cd_profissional decimal NOT NULL,
    id decimal(8,0)
);

CREATE TABLE s_historico_proposta (
    dt_historico_proposta timestamp  NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    tx_sigla_projeto varchar (1000),
    tx_projeto varchar (1000),
    tx_contexdo_geral varchar (1000),
    tx_escopo_projeto varchar (1000),
    tx_sigla_unidade varchar (1000),
    tx_gestor_projeto varchar (1000),
    tx_impacto_projeto varchar (1000),
    tx_gerente_projeto varchar (1000),
    st_metrica_historico character(3),
    tx_inicio_previsto varchar (1000),
    tx_termino_previsto varchar (1000),
    ni_horas_proposta decimal(8,1),
    id decimal(8,0)
);

CREATE TABLE s_historico_proposta_metrica (
    dt_historico_proposta timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    cd_proposta decimal(8,0) NOT NULL,
    cd_definicao_metrica decimal(8,0) NOT NULL,
    ni_um_prop_metrica_historico decimal(8,1),
    tx_just_metrica_historico varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_historico_proposta_parcela (
    cd_proposta decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    dt_historico_proposta timestamp  NOT NULL,
    cd_historico_proposta_parcela decimal NOT NULL,
    ni_parcela decimal NOT NULL,
    ni_mes_previsao_parcela decimal,
    ni_ano_previsao_parcela decimal,
    ni_horas_parcela decimal(8,1),
    id decimal(8,0)
);

CREATE TABLE s_historico_proposta_produto (
    cd_historico_proposta_produto decimal NOT NULL,
    dt_historico_proposta timestamp  NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_historico_proposta_parcela decimal NOT NULL,
    tx_produto varchar (1000),
    cd_tipo_produto decimal,
    id decimal(8,0)
);

CREATE TABLE s_interacao (
    cd_interacao decimal NOT NULL,
    cd_modulo decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_caso_de_uso decimal NOT NULL,
    cd_ator decimal NOT NULL,
    tx_interacao varchar (1000),
    ni_ordem_interacao decimal,
    st_interacao character(1),
    dt_versao_caso_de_uso timestamp  NOT NULL,
    id decimal(8,0)
);

CREATE TABLE s_inventario (
    cd_inventario decimal(8,0) NOT NULL,
    cd_area_atuacao_ti decimal(8,0) NOT NULL,
    tx_inventario varchar(100),
    tx_desc_inventario varchar(4000),
    tx_obs_inventario varchar(4000),
    dt_ult_atual_inventario date,
    id decimal(8,0)
);

CREATE TABLE s_log (
    dt_ocorrencia timestamp  NOT NULL,
    cd_log decimal(8,0) NOT NULL,
    cd_profissional decimal(8,0),
    tx_msg_log varchar(1000) NOT NULL,
    ni_prioridade decimal(8,0),
    tx_tabela varchar (1000),
    tx_controller varchar (1000),
    tx_ip varchar(15),
    tx_host varchar(1000)
);

CREATE TABLE s_medicao (
    cd_medicao decimal(8,2) NOT NULL,
    tx_medicao varchar(200),
    tx_objetivo_medicao varchar (1000),
    st_nivel_medicao character(1),
    tx_procedimento_coleta varchar (1000),
    tx_procedimento_analise varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_mensageria (
    cd_mensageria decimal(8,0) NOT NULL,
    cd_objeto decimal NOT NULL,
    cd_perfil decimal,
    tx_mensagem varchar(4000),
    dt_postagem timestamp ,
    dt_encerramento timestamp ,
    id decimal(8,0)
);

CREATE TABLE s_modulo (
    cd_modulo decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_status decimal,
    tx_modulo varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_modulo_continuado (
    cd_modulo_continuado decimal NOT NULL,
    cd_objeto decimal NOT NULL,
    cd_projeto_continuado decimal NOT NULL,
    tx_modulo_continuado varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_objeto_contrato (
    cd_objeto decimal NOT NULL,
    cd_contrato decimal NOT NULL,
    tx_objeto varchar (1000),
    ni_horas_objeto decimal,
    st_objeto_contrato character(1),
    st_viagem character(1),
    id decimal(8,0),
    st_parcela_orcamento character(1),
    ni_porcentagem_parc_orcamento decimal(3,2),
    st_necessita_justificativa character(1),
    ni_minutos_justificativa decimal(8,0),
    tx_hora_inicio_just_periodo_1 time ,
    tx_hora_fim_just_periodo_1 time ,
    tx_hora_inicio_just_periodo_2 time ,
    tx_hora_fim_just_periodo_2 time ,
    cd_area_atuacao_ti decimal(8,0)
);

CREATE TABLE s_ocorrencia_administrativa (
    dt_ocorrencia_administrativa timestamp  NOT NULL,
    cd_evento decimal(8,0) NOT NULL,
    cd_contrato decimal NOT NULL,
    tx_ocorrencia_administrativa varchar(4000),
    id decimal(8,0)
);

CREATE TABLE s_parcela (
    cd_parcela decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    ni_parcela decimal,
    ni_horas_parcela decimal(8,1),
    ni_mes_previsao_parcela decimal,
    ni_ano_previsao_parcela decimal,
    ni_mes_execucao_parcela decimal,
    ni_ano_execucao_parcela decimal,
    st_modulo_proposta character(1),
    id decimal(8,0)
);

CREATE TABLE s_penalizacao (
    dt_penalizacao date NOT NULL,
    cd_contrato decimal NOT NULL,
    cd_penalidade decimal NOT NULL,
    tx_obs_penalizacao varchar (1000),
    tx_justificativa_penalizacao varchar (1000),
    ni_qtd_ocorrencia decimal,
    st_aceite_justificativa character(1),
    id decimal(8,0),
    dt_justificativa date,
    tx_obs_justificativa varchar(4000)
);

CREATE TABLE s_plano_implantacao (
    cd_projeto decimal(8,0) NOT NULL,
    cd_proposta decimal NOT NULL,
    tx_descricao_plano_implantacao varchar(4000) NOT NULL,
    cd_prof_plano_implantacao decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE s_pre_demanda (
    cd_pre_demanda decimal NOT NULL,
    cd_objeto_emissor decimal,
    cd_objeto_receptor decimal,
    ni_ano_solicitacao decimal,
    ni_solicitacao decimal,
    tx_pre_demanda varchar (1000),
    st_aceite_pre_demanda character(1),
    dt_pre_demanda timestamp ,
    cd_profissional_solicitante decimal(8,0),
    dt_fim_pre_demanda timestamp ,
    st_fim_pre_demanda character(1),
    dt_aceite_pre_demanda timestamp ,
    tx_obs_aceite_pre_demanda varchar(4000),
    tx_obs_reabertura_pre_demanda varchar(4000),
    st_reabertura_pre_demanda character(1),
    cd_unidade decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE s_pre_projeto (
    cd_pre_projeto decimal(8,0) NOT NULL,
    cd_unidade decimal,
    cd_gerente_pre_projeto decimal,
    tx_pre_projeto varchar(200),
    tx_sigla_pre_projeto varchar(100),
    tx_contexto_geral_pre_projeto varchar(4000),
    tx_escopo_pre_projeto varchar(4000),
    tx_gestor_pre_projeto varchar(200),
    tx_obs_pre_projeto varchar(4000),
    st_impacto_pre_projeto character(1),
    st_prioridade_pre_projeto character(1),
    tx_horas_estimadas varchar(10),
    tx_pub_alcancado_pre_proj varchar(200),
    cd_contrato decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE s_pre_projeto_evolutivo (
    cd_pre_projeto_evolutivo decimal NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    tx_pre_projeto_evolutivo varchar(200),
    tx_objetivo_pre_proj_evol varchar (1000),
    st_gerencia_mudanca character(1),
    dt_gerencia_mudanca date,
    cd_contrato decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE s_previsao_projeto_diario (
    cd_projeto decimal NOT NULL,
    ni_mes decimal NOT NULL,
    ni_dia decimal NOT NULL,
    ni_horas decimal,
    id decimal(8,0)
);

CREATE TABLE s_processamento_parcela (
    cd_processamento_parcela decimal(8,0) NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_parcela decimal NOT NULL,
    cd_objeto_execucao decimal,
    ni_ano_solicitacao_execucao decimal,
    ni_solicitacao_execucao decimal,
    st_autorizacao_parcela character(1),
    dt_autorizacao_parcela timestamp ,
    cd_prof_autorizacao_parcela decimal(8,0),
    st_fechamento_parcela character(1),
    dt_fechamento_parcela timestamp ,
    cd_prof_fechamento_parcela decimal,
    st_parecer_tecnico_parcela character(1),
    dt_parecer_tecnico_parcela timestamp ,
    tx_obs_parecer_tecnico_parcela varchar (1000),
    cd_prof_parecer_tecnico_parc decimal,
    st_aceite_parcela character(1),
    dt_aceite_parcela timestamp ,
    tx_obs_aceite_parcela varchar (1000),
    cd_profissional_aceite_parcela decimal,
    st_homologacao_parcela character(1),
    dt_homologacao_parcela timestamp ,
    tx_obs_homologacao_parcela varchar (1000),
    cd_prof_homologacao_parcela decimal,
    st_ativo character(1),
    st_pendente character(1),
    dt_inicio_pendencia timestamp ,
    dt_fim_pendencia timestamp ,
    id decimal(8,0)
);

CREATE TABLE s_processamento_proposta (
    cd_processamento_proposta decimal(8,0) NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    st_fechamento_proposta character(1),
    dt_fechamento_proposta timestamp ,
    cd_prof_fechamento_proposta decimal,
    st_parecer_tecnico_proposta character(1),
    dt_parecer_tecnico_proposta timestamp ,
    tx_obs_parecer_tecnico_prop varchar (1000),
    cd_prof_parecer_tecnico_propos decimal,
    st_aceite_proposta character(1),
    dt_aceite_proposta timestamp ,
    tx_obs_aceite_proposta varchar (1000),
    cd_prof_aceite_proposta decimal,
    st_homologacao_proposta character(1),
    dt_homologacao_proposta timestamp ,
    tx_obs_homologacao_proposta varchar (1000),
    cd_prof_homologacao_proposta decimal,
    st_alocacao_proposta character(1),
    dt_alocacao_proposta timestamp ,
    cd_prof_alocacao_proposta decimal,
    st_ativo character(1),
    tx_motivo_alteracao_proposta varchar(4000),
    id decimal(8,0)
);

CREATE TABLE s_produto_parcela (
    cd_produto_parcela decimal NOT NULL,
    cd_proposta decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_parcela decimal NOT NULL,
    tx_produto_parcela varchar (1000),
    cd_tipo_produto decimal,
    id decimal(8,0)
);

CREATE TABLE s_profissional (
    cd_profissional decimal NOT NULL,
    tx_profissional varchar (1000),
    cd_relacao_contratual decimal,
    cd_empresa decimal(8,0),
    tx_nome_conhecido varchar(100),
    tx_telefone_residencial varchar(20),
    tx_celular_profissional varchar(20),
    tx_ramal_profissional varchar(10),
    tx_endereco_profissional varchar(200),
    dt_nascimento_profissional date,
    dt_inicio_trabalho date,
    dt_saida_profissional date,
    tx_email_institucional varchar(200),
    tx_email_pessoal varchar(200),
    st_nova_senha character(1),
    st_inativo character(1),
    tx_senha varchar(50),
    tx_data_ultimo_acesso varchar (1000),
    tx_hora_ultimo_acesso varchar (1000),
    cd_perfil decimal(8,0),
    st_dados_todos_contratos character(1),
    id decimal(8,0)
);

CREATE TABLE s_projeto (
    cd_projeto decimal NOT NULL,
    cd_profissional_gerente decimal,
    cd_unidade decimal,
    cd_status decimal,
    tx_projeto varchar (1000),
    tx_obs_projeto varchar (1000),
    tx_sigla_projeto varchar (1000),
    tx_gestor_projeto varchar (1000),
    st_impacto_projeto character(1),
    st_prioridade_projeto character(1),
    tx_escopo_projeto varchar (1000),
    tx_contexto_geral_projeto varchar (1000),
    tx_publico_alcancado varchar(200),
    ni_mes_inicio_previsto decimal(4,0),
    ni_ano_inicio_previsto decimal(4,0),
    ni_mes_termino_previsto decimal(4,0),
    ni_ano_termino_previsto decimal(4,0),
    tx_co_gestor_projeto varchar(200),
    st_dicionario_dados character(1) DEFAULT 0,
    st_informacoes_tecnicas character(1) DEFAULT 0,
    id decimal(8,0)
);

CREATE TABLE s_projeto_continuado (
    cd_projeto_continuado decimal NOT NULL,
    cd_objeto decimal NOT NULL,
    tx_projeto_continuado varchar (1000),
    tx_objetivo_projeto_continuado varchar (1000),
    tx_obs_projeto_continuado varchar (1000),
    st_prioridade_proj_continuado character(1),
    id decimal(8,0)
);

CREATE TABLE s_projeto_previsto (
    cd_projeto_previsto decimal NOT NULL,
    cd_contrato decimal NOT NULL,
    cd_unidade decimal,
    tx_projeto_previsto varchar (1000),
    ni_horas_projeto_previsto decimal,
    st_projeto_previsto character(1),
    tx_descricao_projeto_previsto varchar (1000),
    id decimal(8,0),
    cd_definicao_metrica decimal(8,0)
);

CREATE TABLE s_proposta (
    cd_proposta decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    cd_objeto decimal NOT NULL,
    ni_ano_solicitacao decimal NOT NULL,
    ni_solicitacao decimal NOT NULL,
    st_encerramento_proposta character(1),
    dt_encerramento_proposta timestamp ,
    cd_prof_encerramento_proposta decimal,
    ni_horas_proposta decimal(8,1),
    st_alteracao_proposta character(1),
    st_contrato_anterior character(1),
    tx_motivo_insatisfacao varchar(4000),
    tx_gestao_qualidade varchar(4000),
    st_descricao character(1) DEFAULT 0,
    st_profissional character(1) DEFAULT 0,
    st_metrica character(1) DEFAULT 0,
    st_documentacao character(1) DEFAULT 0,
    st_modulo character(1) DEFAULT 0,
    st_parcela character(1) DEFAULT 0,
    st_produto character(1) DEFAULT 0,
    st_caso_de_uso character(1) DEFAULT 0,
    ni_mes_proposta decimal(4,0),
    ni_ano_proposta decimal(4,0),
    tx_objetivo_proposta varchar(4000),
    id decimal(8,0),
    st_requisito character(1) DEFAULT 0,
    nf_indice_avaliacao_proposta decimal(8,0),
    st_objetivo_proposta character(1) DEFAULT 0,
    st_suspensao_proposta character(1)
);

CREATE TABLE s_regra_negocio (
    cd_regra_negocio decimal NOT NULL,
    dt_regra_negocio timestamp  NOT NULL,
    cd_projeto_regra_negocio decimal(8,0) NOT NULL,
    tx_regra_negocio varchar (1000),
    tx_descricao_regra_negocio varchar (1000),
    st_regra_negocio character(1),
    ni_versao_regra_negocio decimal,
    dt_fechamento_regra_negocio date,
    ni_ordem_regra_negocio decimal(8,0),
    st_fechamento_regra_negocio character(1),
    id decimal(8,0)
);

CREATE TABLE s_requisito (
    cd_requisito decimal NOT NULL,
    dt_versao_requisito timestamp  NOT NULL,
    cd_projeto decimal(8,0) NOT NULL,
    st_tipo_requisito character(1),
    tx_requisito varchar (1000),
    tx_descricao_requisito varchar (1000),
    ni_versao_requisito decimal(8,0),
    st_prioridade_requisito character(1),
    st_requisito character(1),
    tx_usuario_solicitante varchar (1000),
    tx_nivel_solicitante varchar (1000),
    st_fechamento_requisito character(1),
    dt_fechamento_requisito date,
    ni_ordem decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE s_reuniao (
    cd_reuniao decimal NOT NULL,
    cd_projeto decimal NOT NULL,
    dt_reuniao date,
    tx_pauta varchar (1000),
    tx_participantes varchar (1000),
    tx_ata varchar (1000),
    tx_local_reuniao varchar (1000),
    cd_profissional decimal,
    id decimal(8,0)
);

CREATE TABLE s_reuniao_geral (
    cd_objeto decimal NOT NULL,
    cd_reuniao_geral decimal NOT NULL,
    dt_reuniao date,
    tx_pauta varchar (1000),
    tx_participantes varchar (1000),
    tx_ata varchar (1000),
    tx_local_reuniao varchar (1000),
    cd_profissional decimal,
    id decimal(8,0)
);

CREATE TABLE s_situacao_projeto (
    cd_projeto decimal NOT NULL,
    ni_mes_situacao_projeto decimal NOT NULL,
    ni_ano_situacao_projeto decimal NOT NULL,
    tx_situacao_projeto varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_solicitacao (
    ni_solicitacao decimal NOT NULL,
    ni_ano_solicitacao decimal NOT NULL,
    cd_objeto decimal NOT NULL,
    cd_profissional decimal,
    cd_unidade decimal,
    tx_solicitacao varchar (1000),
    st_solicitacao character(1),
    tx_justificativa_solicitacao varchar (1000),
    dt_justificativa timestamp ,
    st_aceite character(1),
    dt_aceite timestamp ,
    tx_obs_aceite varchar (1000),
    st_fechamento character(1),
    dt_fechamento timestamp ,
    st_homologacao character(1),
    dt_homologacao timestamp ,
    tx_obs_homologacao varchar (1000),
    ni_dias_execucao decimal,
    tx_problema_encontrado varchar (1000),
    tx_solucao_solicitacao varchar (1000),
    st_grau_satisfacao character(1),
    tx_obs_grau_satisfacao varchar (1000),
    dt_grau_satisfacao timestamp ,
    dt_leitura_solicitacao timestamp ,
    dt_solicitacao timestamp ,
    tx_solicitante varchar (1000),
    tx_sala_solicitante varchar (1000),
    tx_telefone_solicitante varchar (1000),
    tx_obs_solicitacao varchar (1000),
    tx_execucao_solicitacao varchar(4000),
    ni_prazo_atendimento decimal(8,0),
    id decimal(8,0),
    st_aceite_just_solicitacao character(1),
    tx_obs_aceite_just_solicitacao varchar(1000),
    cd_item_inventariado decimal(8,0),
    cd_item_inventario decimal(8,0),
    cd_inventario decimal(8,0)
);

CREATE TABLE s_solicitacao_pedido (
    cd_solicitacao_pedido integer NOT NULL,
    cd_usuario_pedido integer NOT NULL,
    cd_unidade_pedido decimal NOT NULL,
    dt_solicitacao_pedido timestamp  NOT NULL,
    st_situacao_pedido character(1) NOT NULL,
    tx_observacao_pedido text,
    dt_encaminhamento_pedido timestamp ,
    dt_autorizacao_competente timestamp ,
    tx_analise_aut_competente varchar (1000),
    dt_analise_area_ti_solicitacao timestamp ,
    tx_analise_area_ti_solicitacao varchar (1000),
    dt_analise_area_ti_chefia_sol timestamp ,
    tx_analise_area_ti_chefia_sol varchar (1000),
    dt_analise_comite timestamp ,
    tx_analise_comite varchar (1000),
    dt_analise_area_ti_chefia_exec timestamp ,
    tx_analise_area_ti_chefia_exec varchar (1000),
    cd_usuario_aut_competente decimal(8,0),
    id decimal(8,0)
);

CREATE TABLE s_tabela (
    tx_tabela varchar(100) NOT NULL,
    cd_projeto decimal NOT NULL,
    tx_descricao varchar (1000),
    id decimal(8,0)
);

CREATE TABLE s_usuario_pedido (
    cd_usuario_pedido integer NOT NULL,
    cd_unidade_usuario decimal NOT NULL,
    st_autoridade character(1) ,
    st_inativo character(1) ,
    tx_nome_usuario varchar(100) NOT NULL,
    tx_email_usuario varchar(100) NOT NULL,
    tx_senha_acesso varchar(40) NOT NULL,
    tx_sala_usuario varchar(50),
    tx_telefone_usuario varchar(50),
    id decimal(8,0)
);

