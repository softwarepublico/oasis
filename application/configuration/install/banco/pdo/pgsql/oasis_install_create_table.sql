SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

CREATE SCHEMA K_SCHEMA;

ALTER SCHEMA K_SCHEMA OWNER TO K_USER;

SET search_path = K_SCHEMA, pg_catalog;

-- CREATE LANGUAGE plpgsql;

CREATE FUNCTION insere_rotina() RETURNS integer
    LANGUAGE plpgsql
    AS $$
    declare registro record;
begin
    for registro in
        select * from 
		(select
		    rp.*,
		    rd.tx_hora_inicio_rotina
		 from
		    K_SCHEMA.a_rotina_profissional as rp
		 join
		    K_SCHEMA.b_rotina as rd 
		 on
		    rp.cd_rotina = rd.cd_rotina
		    and rd.st_periodicidade_rotina = 'D'
		    and st_inativa_rotina_profissional is null
		)as rd1
	union 
		(select
		    rp.*,
		    rs.tx_hora_inicio_rotina
		 from
		    K_SCHEMA.a_rotina_profissional as rp
		 join
		    K_SCHEMA.b_rotina as rs 
		 on
		    rp.cd_rotina = rs.cd_rotina
		    and (rs.st_periodicidade_rotina = 'S' and cast(to_char(now(),'D') as int) = cast(rs.ni_dia_semana_rotina as int))
		    and st_rotina_inativa is null
		 where
		    rp.st_inativa_rotina_profissional is null    
	   
		) 
	union 
		(select
		    rp.*,
		    rm.tx_hora_inicio_rotina
		from
		    K_SCHEMA.a_rotina_profissional as rp
		join
		    K_SCHEMA.b_rotina as rm 
		on
		    rp.cd_rotina = rm.cd_rotina
		    and (rm.st_periodicidade_rotina = 'M' and cast(to_char(now(),'DD') as int) = rm.ni_dia_mes_rotina) 
		    and st_rotina_inativa is null
		where
		    rp.st_inativa_rotina_profissional is null    
	       )
    loop
        INSERT INTO
            K_SCHEMA.s_execucao_rotina
            (
                dt_execucao_rotina,
                cd_profissional,
                cd_objeto,
                cd_rotina,
                tx_hora_execucao_rotina,
                st_fechamento_execucao_rotina,
                dt_just_execucao_rotina,
                tx_just_execucao_rotina,
                st_historico,
                id
            )
        VALUES (
                current_date,
                registro.cd_profissional,
                registro.cd_objeto,
                registro.cd_rotina,
                registro.tx_hora_inicio_rotina,
                    null,
                    null,
                    null,
                    null,
                    registro.id
            );
    end loop;
    return 0;
end;
$$;


ALTER FUNCTION K_SCHEMA.insere_rotina() OWNER TO K_USER;



SET default_tablespace = '';

SET default_with_oids = false;


CREATE TABLE K_SCHEMA.a_baseline_item_controle (
    cd_projeto numeric(8,0) NOT NULL,
    dt_baseline timestamp without time zone NOT NULL,
    cd_item_controle_baseline numeric(8,0) NOT NULL,
    cd_item_controlado numeric(8,0) NOT NULL,
    dt_versao_item_controlado timestamp without time zone NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_conhecimento_projeto (
    cd_tipo_conhecimento numeric NOT NULL,
    cd_conhecimento numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_conjunto_medida_medicao (
    cd_conjunto_medida numeric(8,0) NOT NULL,
    cd_box_inicio numeric(8,0) NOT NULL,
    cd_medicao numeric(8,0) NOT NULL,
    st_nivel_conjunto_medida character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_contrato_definicao_metrica (
    cd_contrato numeric(8,0) NOT NULL,
    cd_definicao_metrica numeric(8,0) NOT NULL,
    id numeric(8,0),
    nf_fator_relacao_metrica_pad numeric(4,2),
    st_metrica_padrao character(1)
);

CREATE TABLE K_SCHEMA.a_contrato_evento (
    cd_contrato numeric NOT NULL,
    cd_evento numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_contrato_item_inventario (
    cd_contrato numeric(8,0) NOT NULL,
    cd_item_inventario numeric(8,0) NOT NULL,
    id numeric(8,0),
    cd_inventario numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_contrato_projeto (
    cd_contrato numeric(8,0) NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_controle (
    cd_controle numeric NOT NULL,
    cd_projeto_previsto numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_contrato numeric NOT NULL,
    ni_horas numeric(8,1),
    st_controle character(1),
    st_modulo_proposta character(1),
    dt_lancamento timestamp without time zone,
    cd_profissional numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_definicao_processo (
    cd_perfil numeric NOT NULL,
    cd_funcionalidade numeric(8,0) NOT NULL,
    st_definicao_processo character(1) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_demanda_prof_nivel_servico (
    cd_demanda numeric NOT NULL,
    cd_profissional numeric NOT NULL,
    cd_nivel_servico numeric NOT NULL,
    dt_fechamento_nivel_servico timestamp without time zone,
    st_fechamento_nivel_servico character(1),
    st_fechamento_gerente character(1),
    dt_fechamento_gerente timestamp without time zone,
    dt_leitura_nivel_servico timestamp without time zone,
    dt_demanda_nivel_servico timestamp without time zone,
    tx_motivo_fechamento character varying(4000),
    tx_obs_nivel_servico character varying(4000),
    dt_justificativa_nivel_servico timestamp without time zone,
    tx_justificativa_nivel_servico character varying(4000),
    id numeric(8,0),
    st_nova_obs_nivel_servico character(1),
    tx_nova_obs_nivel_servico character varying(4000)
);

CREATE TABLE K_SCHEMA.a_demanda_profissional (
    cd_profissional numeric NOT NULL,
    cd_demanda numeric NOT NULL,
    dt_demanda_profissional timestamp without time zone,
    st_fechamento_demanda character(1),
    dt_fechamento_demanda timestamp without time zone,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_disponibilidade_servico_doc (
    cd_disponibilidade_servico numeric(8,0) NOT NULL,
    cd_objeto numeric(8,0) NOT NULL,
    cd_tipo_documentacao numeric(8,0) NOT NULL,
    dt_doc_disponibilidade_servico timestamp without time zone NOT NULL,
    tx_nome_arq_disp_servico character varying,
    tx_arquivo_disp_servico character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_doc_projeto_continuo (
    dt_doc_projeto_continuo timestamp without time zone NOT NULL,
    cd_objeto numeric NOT NULL,
    cd_projeto_continuado numeric NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    tx_arq_doc_projeto_continuo character varying,
    tx_nome_arquivo character varying(100),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_documentacao_contrato (
    dt_documentacao_contrato timestamp without time zone NOT NULL,
    cd_contrato numeric NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    tx_arq_documentacao_contrato character varying,
    tx_nome_arquivo character varying(100),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_documentacao_profissional (
    dt_documentacao_profissional timestamp without time zone NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    cd_profissional numeric NOT NULL,
    tx_arq_documentacao_prof character varying,
    tx_nome_arquivo character varying(100),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_documentacao_projeto (
    dt_documentacao_projeto timestamp without time zone NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    tx_arq_documentacao_projeto character varying,
    tx_nome_arquivo character varying(100),
    st_documentacao_controle character(1),
    id numeric(8,0),
    st_doc_acompanhamento character(1)
);

CREATE TABLE K_SCHEMA.a_form_inventario (
    cd_inventario numeric(8,0) NOT NULL,
    cd_item_inventario numeric(8,0) NOT NULL,
    cd_item_inventariado numeric(8,0) NOT NULL,
    cd_subitem_inventario numeric(8,0) NOT NULL,
    cd_subitem_inv_descri numeric(8,0) NOT NULL,
    tx_valor_subitem_inventario character varying(1000),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_funcionalidade_menu (
    cd_funcionalidade numeric(8,0) NOT NULL,
    cd_menu numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_gerencia_mudanca (
    dt_gerencia_mudanca timestamp without time zone NOT NULL,
    cd_item_controle_baseline numeric(8,0) NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    cd_item_controlado numeric(8,0) NOT NULL,
    dt_versao_item_controlado timestamp without time zone NOT NULL,
    tx_motivo_mudanca character varying,
    st_mudanca_metrica character(1),
    ni_custo_provavel_mudanca numeric(8,0),
    st_reuniao character(1),
    tx_decisao_mudanca character varying,
    dt_decisao_mudanca date,
    cd_reuniao numeric(8,0),
    cd_projeto_reuniao numeric(8,0),
    st_decisao_mudanca character(1),
    st_execucao_mudanca character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_informacao_tecnica (
    cd_projeto numeric NOT NULL,
    cd_tipo_dado_tecnico numeric NOT NULL,
    tx_conteudo_informacao_tecnica character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_item_inventariado (
    cd_item_inventariado numeric(8,0) NOT NULL,
    cd_inventario numeric(8,0) NOT NULL,
    cd_item_inventario numeric(8,0) NOT NULL,
    tx_item_inventariado character varying(100),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_item_teste_caso_de_uso (
    cd_item_teste_caso_de_uso numeric NOT NULL,
    cd_item_teste numeric(8,0) NOT NULL,
    cd_caso_de_uso numeric NOT NULL,
    dt_versao_caso_de_uso timestamp without time zone NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    st_analise character(1),
    tx_analise character varying(4000),
    dt_analise date,
    cd_profissional_analise numeric,
    st_solucao character(1),
    tx_solucao character varying(4000),
    dt_solucao date,
    cd_profissional_solucao numeric,
    st_homologacao character(1),
    tx_homologacao character varying(4000),
    dt_homologacao date,
    cd_profissional_homologacao numeric,
    st_item_teste_caso_de_uso character(1),
    id numeric(8,0),
    CONSTRAINT ck_K_SCHEMA_009 CHECK (((st_item_teste_caso_de_uso IS NULL) OR (st_item_teste_caso_de_uso = ANY (ARRAY['H'::bpchar, 'F'::bpchar]))))
);

CREATE TABLE K_SCHEMA.a_item_teste_caso_de_uso_doc (
    cd_arq_item_teste_caso_de_uso numeric(8,0) NOT NULL,
    cd_item_teste_caso_de_uso numeric NOT NULL,
    cd_item_teste numeric(8,0) NOT NULL,
    cd_caso_de_uso numeric NOT NULL,
    dt_versao_caso_de_uso timestamp without time zone NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    tx_nome_arq_teste_caso_de_uso character varying,
    tx_arq_item_teste_caso_de_uso character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_item_teste_regra_negocio (
    cd_item_teste_regra_negocio numeric NOT NULL,
    cd_item_teste numeric NOT NULL,
    cd_regra_negocio numeric NOT NULL,
    dt_regra_negocio timestamp without time zone NOT NULL,
    cd_projeto_regra_negocio numeric(8,0) NOT NULL,
    st_analise character(1),
    tx_analise character varying,
    dt_analise date,
    cd_profissional_analise numeric,
    st_solucao character(1),
    tx_solucao character varying,
    dt_solucao date,
    cd_profissional_solucao numeric,
    st_homologacao character(1),
    tx_homologacao character varying,
    dt_homologacao date,
    cd_profissional_homologacao numeric,
    st_item_teste_regra_negocio character(1),
    id numeric(8,0),
    CONSTRAINT ck_K_SCHEMA_008 CHECK (((st_item_teste_regra_negocio IS NULL) OR (st_item_teste_regra_negocio = ANY (ARRAY['H'::bpchar, 'F'::bpchar]))))
);

CREATE TABLE K_SCHEMA.a_item_teste_regra_negocio_doc (
    cd_arq_item_teste_regra_neg numeric(8,0) NOT NULL,
    cd_item_teste_regra_negocio numeric NOT NULL,
    cd_item_teste numeric NOT NULL,
    cd_regra_negocio numeric NOT NULL,
    dt_regra_negocio timestamp without time zone NOT NULL,
    cd_projeto_regra_negocio numeric(8,0) NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    tx_nome_arq_teste_regra_negoc character varying,
    tx_arq_item_teste_regra_negoc character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_item_teste_requisito (
    cd_item_teste_requisito numeric NOT NULL,
    cd_item_teste numeric NOT NULL,
    cd_requisito numeric NOT NULL,
    dt_versao_requisito timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    st_analise character(1),
    tx_analise character varying,
    dt_analise date,
    cd_profissional_analise numeric,
    st_solucao character(1),
    tx_solucao character varying,
    dt_solucao date,
    cd_profissional_solucao numeric,
    st_homologacao character(1),
    tx_homologacao character varying,
    dt_homologacao date,
    cd_profissional_homologacao numeric,
    st_item_teste_requisito character(1),
    id numeric(8,0),
    CONSTRAINT ck_K_SCHEMA_007 CHECK (((st_item_teste_requisito IS NULL) OR (st_item_teste_requisito = ANY (ARRAY['H'::bpchar, 'F'::bpchar]))))
);

CREATE TABLE K_SCHEMA.a_item_teste_requisito_doc (
    cd_arq_item_teste_requisito numeric(8,0) NOT NULL,
    cd_item_teste_requisito numeric NOT NULL,
    cd_item_teste numeric NOT NULL,
    cd_requisito numeric NOT NULL,
    dt_versao_requisito timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    cd_tipo_documentacao numeric NOT NULL,
    tx_nome_arq_teste_requisito character varying,
    tx_arq_item_teste_requisito character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_medicao_medida (
    cd_medicao numeric NOT NULL,
    cd_medida numeric NOT NULL,
    st_prioridade_medida character(1),
    id numeric(8,0),
    CONSTRAINT ck_K_SCHEMA_006 CHECK ((st_prioridade_medida = ANY (ARRAY['A'::bpchar, 'L'::bpchar, 'M'::bpchar, 'B'::bpchar])))
);

CREATE TABLE K_SCHEMA.a_objeto_contrato_atividade (
    cd_objeto numeric(8,0) NOT NULL,
    cd_etapa numeric(8,0) NOT NULL,
    cd_atividade numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_objeto_contrato_papel_prof (
    cd_objeto numeric(8,0) NOT NULL,
    cd_papel_profissional numeric(8,0) NOT NULL,
    tx_descricao_papel_prof character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_objeto_contrato_perfil_prof (
    cd_objeto numeric(8,0) NOT NULL,
    cd_perfil_profissional numeric(8,0) NOT NULL,
    tx_descricao_perfil_prof character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_objeto_contrato_rotina (
    cd_objeto numeric(8,0) NOT NULL,
    cd_rotina numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_opcao_resp_pergunta_pedido (
    cd_pergunta_pedido integer NOT NULL,
    cd_resposta_pedido integer NOT NULL,
    st_resposta_texto character(1) DEFAULT 'N'::bpchar NOT NULL,
    ni_ordem_apresenta integer DEFAULT 0 NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_parecer_tecnico_parcela (
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_parcela numeric NOT NULL,
    cd_item_parecer_tecnico numeric NOT NULL,
    cd_processamento_parcela numeric(8,0) NOT NULL,
    st_avaliacao character(2),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_parecer_tecnico_proposta (
    cd_item_parecer_tecnico numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_processamento_proposta numeric(8,0) NOT NULL,
    st_avaliacao character(2),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_perfil_box_inicio (
    cd_perfil numeric NOT NULL,
    cd_box_inicio numeric NOT NULL,
    cd_objeto numeric NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_perfil_menu (
    cd_perfil numeric NOT NULL,
    cd_menu numeric NOT NULL,
    cd_objeto numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_perfil_menu_sistema (
    cd_perfil numeric NOT NULL,
    cd_menu numeric NOT NULL,
    st_perfil_menu character(1) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_perfil_prof_papel_prof (
    cd_perfil_profissional numeric(8,0) NOT NULL,
    cd_papel_profissional numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_pergunta_depende_resp_pedido (
    cd_pergunta_depende integer NOT NULL,
    cd_pergunta_pedido integer NOT NULL,
    cd_resposta_pedido integer NOT NULL,
    st_tipo_dependencia character(1) DEFAULT 'S'::bpchar NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_planejamento (
    cd_etapa numeric NOT NULL,
    cd_atividade numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    dt_inicio_atividade date,
    dt_fim_atividade date,
    nf_porcentagem_execucao numeric,
    tx_obs_atividade character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_profissional_conhecimento (
    cd_profissional numeric NOT NULL,
    cd_tipo_conhecimento numeric NOT NULL,
    cd_conhecimento numeric NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_profissional_mensageria (
    cd_profissional numeric NOT NULL,
    cd_mensageria numeric(8,0) NOT NULL,
    dt_leitura_mensagem timestamp without time zone,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_profissional_menu (
    cd_menu numeric NOT NULL,
    cd_profissional numeric NOT NULL,
    cd_objeto numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_profissional_objeto_contrato (
    cd_profissional numeric NOT NULL,
    cd_objeto numeric NOT NULL,
    st_recebe_email character(1),
    tx_posicao_box_inicio character varying(4000),
    st_objeto_padrao character(1),
    cd_perfil_profissional numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_profissional_produto (
    cd_profissional numeric(8,0) NOT NULL,
    cd_produto_parcela numeric(8,0) NOT NULL,
    cd_proposta numeric(8,0) NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    cd_parcela numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_profissional_projeto (
    cd_profissional numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_papel_profissional numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_proposta_definicao_metrica (
    cd_projeto numeric(8,0) NOT NULL,
    cd_proposta numeric(8,0) NOT NULL,
    cd_definicao_metrica numeric(8,0) NOT NULL,
    ni_horas_proposta_metrica numeric(8,1),
    tx_justificativa_metrica character varying(4000),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_proposta_modulo (
    cd_projeto numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    st_criacao_modulo character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_proposta_sub_item_metrica (
    cd_projeto numeric(8,0) NOT NULL,
    cd_proposta numeric(8,0) NOT NULL,
    cd_item_metrica numeric(8,0) NOT NULL,
    cd_definicao_metrica numeric(8,0) NOT NULL,
    cd_sub_item_metrica numeric(8,0) NOT NULL,
    ni_valor_sub_item_metrica numeric(8,1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_quest_avaliacao_qualidade (
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_grupo_fator numeric(8,0) NOT NULL,
    cd_item_grupo_fator numeric(8,0) NOT NULL,
    st_avaliacao_qualidade character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_questionario_analise_risco (
    dt_analise_risco timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_etapa numeric(8,0) NOT NULL,
    cd_atividade numeric(8,0) NOT NULL,
    cd_item_risco numeric NOT NULL,
    cd_questao_analise_risco numeric NOT NULL,
    st_resposta_analise_risco character(3),
    cd_profissional numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_regra_negocio_requisito (
    cd_projeto_regra_negocio numeric(8,0) NOT NULL,
    dt_regra_negocio timestamp without time zone NOT NULL,
    cd_regra_negocio numeric NOT NULL,
    dt_versao_requisito timestamp without time zone NOT NULL,
    cd_requisito numeric NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    dt_inativacao_regra date,
    st_inativo character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_requisito_caso_de_uso (
    cd_projeto numeric(8,0) NOT NULL,
    dt_versao_requisito timestamp without time zone NOT NULL,
    cd_requisito numeric NOT NULL,
    dt_versao_caso_de_uso timestamp without time zone NOT NULL,
    cd_caso_de_uso numeric NOT NULL,
    cd_modulo numeric(8,0) NOT NULL,
    dt_inativacao_caso_de_uso date,
    st_inativo character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_requisito_dependente (
    cd_requisito_ascendente numeric NOT NULL,
    dt_versao_requisito_ascendente timestamp without time zone NOT NULL,
    cd_projeto_ascendente numeric(8,0) NOT NULL,
    cd_requisito numeric NOT NULL,
    dt_versao_requisito timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    st_inativo character(1),
    dt_inativacao_requisito date,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_reuniao_geral_profissional (
    cd_objeto numeric NOT NULL,
    cd_reuniao_geral numeric NOT NULL,
    cd_profissional numeric NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_reuniao_profissional (
    cd_projeto numeric NOT NULL,
    cd_reuniao numeric NOT NULL,
    cd_profissional numeric NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_rotina_profissional (
    cd_objeto numeric(8,0) NOT NULL,
    cd_profissional numeric(8,0) NOT NULL,
    cd_rotina numeric(8,0) NOT NULL,
    st_inativa_rotina_profissional character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_solicitacao_resposta_pedido (
    cd_solicitacao_pedido integer NOT NULL,
    cd_pergunta_pedido integer NOT NULL,
    cd_resposta_pedido integer NOT NULL,
    tx_descricao_resposta text,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.a_treinamento_profissional (
    cd_treinamento numeric(8,0) NOT NULL,
    cd_profissional numeric NOT NULL,
    dt_treinamento_profissional date,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_area_atuacao_ti (
    cd_area_atuacao_ti numeric(8,0) NOT NULL,
    tx_area_atuacao_ti character varying(200),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_area_conhecimento (
    cd_area_conhecimento numeric NOT NULL,
    tx_area_conhecimento character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_atividade (
    cd_atividade numeric NOT NULL,
    cd_etapa numeric NOT NULL,
    tx_atividade character varying,
    ni_ordem_atividade numeric(4,0),
    tx_descricao_atividade character varying(4000),
    id numeric(8,0),
    st_atividade_inativa character(1)
);

CREATE TABLE K_SCHEMA.b_box_inicio (
    cd_box_inicio numeric NOT NULL,
    tx_box_inicio character varying(100) NOT NULL,
    st_tipo_box_inicio character(1) NOT NULL,
    tx_titulo_box_inicio character varying(100) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_condicao_sub_item_metrica (
    cd_condicao_sub_item_metrica numeric(8,0) NOT NULL,
    cd_item_metrica numeric(8,0) NOT NULL,
    cd_definicao_metrica numeric(8,0) NOT NULL,
    cd_sub_item_metrica numeric(8,0) NOT NULL,
    tx_condicao_sub_item_metrica character varying(100),
    ni_valor_condicao_satisfeita numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_conhecimento (
    cd_conhecimento numeric NOT NULL,
    cd_tipo_conhecimento numeric NOT NULL,
    tx_conhecimento character varying,
    st_padrao character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_conjunto_medida (
    cd_conjunto_medida numeric(8,0) NOT NULL,
    tx_conjunto_medida character varying(500),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_definicao_metrica (
    cd_definicao_metrica numeric(8,0) NOT NULL,
    tx_nome_metrica character varying,
    tx_sigla_metrica character varying,
    tx_descricao_metrica character varying,
    tx_formula_metrica character varying,
    st_justificativa_metrica character(1),
    id numeric(8,0),
    tx_sigla_unidade_metrica character varying(10),
    tx_unidade_metrica character varying(100)
);

CREATE TABLE K_SCHEMA.b_etapa (
    cd_etapa numeric NOT NULL,
    tx_etapa character varying,
    ni_ordem_etapa numeric(4,0),
    tx_descricao_etapa character varying(4000),
    id numeric(8,0),
    cd_area_atuacao_ti numeric(8,0),
    st_etapa_inativa character(1)
);

CREATE TABLE K_SCHEMA.b_evento (
    cd_evento numeric(8,0) NOT NULL,
    tx_evento character varying(200),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_funcionalidade (
    cd_funcionalidade numeric(8,0) NOT NULL,
    tx_codigo_funcionalidade character varying(20),
    tx_funcionalidade character varying(200),
    st_funcionalidade character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_grupo_fator (
    cd_grupo_fator numeric(8,0) NOT NULL,
    tx_grupo_fator character varying(100),
    ni_peso_grupo_fator numeric(8,0) NOT NULL,
    ni_ordem_grupo_fator numeric NOT NULL,
    id numeric(8,0),
    ni_indice_grupo_fator numeric(8,0) NOT NULL
);

CREATE TABLE K_SCHEMA.b_item_controle_baseline (
    cd_item_controle_baseline numeric(8,0) NOT NULL,
    tx_item_controle_baseline character varying(500),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_item_grupo_fator (
    cd_item_grupo_fator numeric(8,0) NOT NULL,
    cd_grupo_fator numeric(8,0) NOT NULL,
    tx_item_grupo_fator character varying(300),
    ni_ordem_item_grupo_fator numeric NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_item_inventario (
    cd_item_inventario numeric(8,0) NOT NULL,
    cd_area_atuacao_ti numeric(8,0) NOT NULL,
    tx_item_inventario character varying(100),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_item_metrica (
    cd_item_metrica numeric(8,0) NOT NULL,
    cd_definicao_metrica numeric(8,0) NOT NULL,
    tx_item_metrica character varying,
    tx_variavel_item_metrica character varying,
    ni_ordem_item_metrica integer,
    tx_formula_item_metrica character varying(500),
    st_interno_item_metrica character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_item_parecer_tecnico (
    cd_item_parecer_tecnico numeric NOT NULL,
    tx_item_parecer_tecnico character varying,
    st_proposta character(1),
    st_parcela character(1),
    st_viagem character(1),
    id numeric(8,0),
    tx_descricao character varying(4000)
);

CREATE TABLE K_SCHEMA.b_item_risco (
    cd_item_risco numeric NOT NULL,
    cd_etapa numeric(8,0) NOT NULL,
    cd_atividade numeric(8,0) NOT NULL,
    tx_item_risco character varying,
    tx_descricao_item_risco character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_item_teste (
    cd_item_teste numeric(8,0) NOT NULL,
    tx_item_teste character varying(1000),
    st_item_teste character(1),
    st_obrigatorio character(1),
    st_tipo_item_teste character(1),
    ni_ordem_item_teste numeric,
    id numeric(8,0),
    CONSTRAINT ck_K_SCHEMA_004 CHECK ((st_tipo_item_teste = ANY (ARRAY['C'::bpchar, 'R'::bpchar, 'N'::bpchar, 'I'::bpchar]))),
    CONSTRAINT ck_K_SCHEMA_005 CHECK ((st_item_teste = ANY (ARRAY['I'::bpchar, 'A'::bpchar])))
);

CREATE TABLE K_SCHEMA.b_medida (
    cd_medida numeric NOT NULL,
    tx_medida character varying,
    tx_objetivo_medida character varying(4000),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_menu (
    cd_menu numeric NOT NULL,
    cd_menu_pai numeric,
    tx_menu character varying,
    ni_nivel_menu numeric,
    tx_pagina character varying,
    st_menu character(1),
    tx_modulo character varying(50),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_msg_email (
    cd_msg_email numeric(8,0) NOT NULL,
    cd_menu numeric(8,0) NOT NULL,
    tx_metodo_msg_email character varying(300),
    tx_msg_email character varying(1000),
    st_msg_email character(1),
    tx_assunto_msg_email character varying(200)
);

CREATE TABLE K_SCHEMA.b_nivel_servico (
    cd_nivel_servico numeric(8,0) NOT NULL,
    cd_objeto numeric(8,0) NOT NULL,
    tx_nivel_servico character varying,
    st_nivel_servico character(1),
    ni_horas_prazo_execucao numeric(8,1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_papel_profissional (
    cd_papel_profissional numeric(8,0) NOT NULL,
    tx_papel_profissional character varying(200),
    id numeric(8,0),
    cd_area_atuacao_ti numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_penalidade (
    cd_penalidade numeric NOT NULL,
    cd_contrato numeric NOT NULL,
    tx_penalidade character varying,
    tx_abreviacao_penalidade character varying,
    ni_valor_penalidade numeric(8,2),
    ni_penalidade numeric,
    st_ocorrencia character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_perfil (
    cd_perfil numeric NOT NULL,
    tx_perfil character varying,
    id numeric(8,0),
    st_tipo_perfil character(1),
    st_tipo_atuacao character(1)
);

CREATE TABLE K_SCHEMA.b_perfil_profissional (
    cd_perfil_profissional numeric(8,0) NOT NULL,
    tx_perfil_profissional character varying(200),
    id numeric(8,0),
    cd_area_atuacao_ti numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_pergunta_pedido (
    cd_pergunta_pedido integer NOT NULL,
    tx_titulo_pergunta character varying(200) NOT NULL,
    st_multipla_resposta character(1) DEFAULT 'N'::bpchar NOT NULL,
    st_obriga_resposta character(1) DEFAULT 'N'::bpchar NOT NULL,
    tx_ajuda_pergunta character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_questao_analise_risco (
    cd_questao_analise_risco numeric NOT NULL,
    cd_atividade numeric(8,0) NOT NULL,
    cd_etapa numeric(8,0) NOT NULL,
    cd_item_risco numeric NOT NULL,
    tx_questao_analise_risco character varying,
    tx_obj_questao_analise_risco character varying,
    ni_peso_questao_analise_risco numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_relacao_contratual (
    cd_relacao_contratual numeric NOT NULL,
    tx_relacao_contratual character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_resposta_pedido (
    cd_resposta_pedido integer NOT NULL,
    tx_titulo_resposta character varying(150) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_rotina (
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

CREATE TABLE K_SCHEMA.b_status (
    cd_status numeric NOT NULL,
    tx_status character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_status_atendimento (
    cd_status_atendimento numeric(8,0) NOT NULL,
    tx_status_atendimento character varying(100),
    tx_rgb_status_atendimento character varying(8),
    st_status_atendimento character varying(1),
    ni_percent_tempo_resposta_ini numeric(3,0),
    ni_percent_tempo_resposta_fim numeric(3,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_sub_item_metrica (
    cd_sub_item_metrica numeric(8,0) NOT NULL,
    cd_definicao_metrica numeric(8,0) NOT NULL,
    cd_item_metrica numeric(8,0) NOT NULL,
    tx_sub_item_metrica character varying,
    tx_variavel_sub_item_metrica character varying,
    st_interno character(1),
    ni_ordem_sub_item_metrica numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_subitem_inv_descri (
    cd_item_inventario numeric(8,0) NOT NULL,
    cd_subitem_inventario numeric(8,0) NOT NULL,
    cd_subitem_inv_descri numeric(8,0) NOT NULL,
    tx_subitem_inv_descri character varying(100),
    id numeric(8,0),
    ni_ordem integer
);

CREATE TABLE K_SCHEMA.b_subitem_inventario (
    cd_item_inventario numeric(8,0) NOT NULL,
    cd_subitem_inventario numeric(8,0) NOT NULL,
    tx_subitem_inventario character varying(100),
    id numeric(8,0),
    st_info_chamado_tecnico character(1)
);

CREATE TABLE K_SCHEMA.b_tipo_conhecimento (
    cd_tipo_conhecimento numeric NOT NULL,
    tx_tipo_conhecimento character varying,
    id numeric(8,0),
    st_para_profissionais character(1)
);

CREATE TABLE K_SCHEMA.b_tipo_dado_tecnico (
    cd_tipo_dado_tecnico numeric NOT NULL,
    tx_tipo_dado_tecnico character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_tipo_documentacao (
    cd_tipo_documentacao numeric NOT NULL,
    tx_tipo_documentacao character varying,
    tx_extensao_documentacao character varying,
    st_classificacao character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_tipo_produto (
    cd_tipo_produto numeric NOT NULL,
    tx_tipo_produto character varying,
    ni_ordem_tipo_produto numeric(4,0),
    cd_definicao_metrica numeric(8,0),
    id numeric(8,0)
);



CREATE TABLE K_SCHEMA.b_treinamento (
    cd_treinamento numeric(8,0) NOT NULL,
    tx_treinamento character varying(500),
    tx_obs_treinamento character varying(4000),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.b_unidade (
    cd_unidade numeric NOT NULL,
    tx_sigla_unidade character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_acompanhamento_proposta (
    cd_acompanhamento_proposta numeric(8,0) NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    tx_acompanhamento_proposta character varying(4000),
    st_restrito character(1),
    dt_acompanhamento_proposta timestamp without time zone,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_agenda_plano_implantacao (
    dt_agenda_plano_implantacao timestamp without time zone NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    tx_agenda_plano_implantacao character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_analise_execucao_projeto (
    dt_analise_execucao_projeto timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    tx_resultado_analise_execucao character varying,
    tx_decisao_analise_execucao character varying,
    dt_decisao_analise_execucao date,
    st_fecha_analise_execucao_proj character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_analise_matriz_rastreab (
    cd_analise_matriz_rastreab numeric(8,0) NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    st_matriz_rastreabilidade character(2) NOT NULL,
    dt_analise_matriz_rastreab date NOT NULL,
    tx_analise_matriz_rastreab character varying,
    st_fechamento character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_analise_medicao (
    dt_analise_medicao timestamp without time zone NOT NULL,
    cd_medicao numeric NOT NULL,
    cd_box_inicio numeric NOT NULL,
    cd_profissional numeric NOT NULL,
    tx_resultado_analise_medicao character varying,
    tx_dados_medicao character varying,
    tx_decisao character varying,
    dt_decisao date,
    st_decisao_executada character(1),
    dt_decisao_executada date,
    tx_obs_decisao_executada character varying,
    id numeric(8,0),
    CONSTRAINT ck_K_SCHEMA_002 CHECK ((st_decisao_executada = ANY (ARRAY['E'::bpchar, 'N'::bpchar])))
);

CREATE TABLE K_SCHEMA.s_analise_risco (
    dt_analise_risco timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_etapa numeric(8,0) NOT NULL,
    cd_atividade numeric(8,0) NOT NULL,
    cd_item_risco numeric NOT NULL,
    st_fator_risco character(3),
    st_impacto_projeto_risco character(3),
    st_impacto_tecnico_risco character(3),
    st_impacto_custo_risco character(3),
    st_impacto_cronograma_risco character(3),
    tx_analise_risco character varying,
    tx_acao_analise_risco character varying,
    st_fechamento_risco character(1),
    cd_profissional numeric(8,0),
    cd_profissional_responsavel numeric(8,0),
    dt_limite_acao date,
    st_acao character(1),
    tx_observacao_acao character varying,
    dt_fechamento_risco date,
    tx_cor_impacto_cronog_risco character(20),
    tx_cor_impacto_custo_risco character(20),
    tx_cor_impacto_projeto_risco character(20),
    tx_cor_impacto_tecnico_risco character(20),
    id numeric(8,0),
    st_nao_aplica_risco character(1),
    tx_mitigacao_risco character varying
);

CREATE TABLE K_SCHEMA.s_arquivo_pedido (
    cd_arquivo_pedido integer NOT NULL,
    cd_pergunta_pedido integer NOT NULL,
    cd_resposta_pedido integer NOT NULL,
    cd_solicitacao_pedido integer NOT NULL,
    tx_titulo_arquivo character varying(100) NOT NULL,
    tx_nome_arquivo character varying(20) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_ator (
    cd_ator numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    tx_ator character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_base_conhecimento (
    cd_base_conhecimento numeric NOT NULL,
    cd_area_conhecimento numeric NOT NULL,
    tx_assunto character varying,
    tx_problema character varying,
    tx_solucao character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_baseline (
    dt_baseline timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    st_ativa character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_caso_de_uso (
    cd_caso_de_uso numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    ni_ordem_caso_de_uso numeric,
    tx_caso_de_uso character varying,
    tx_descricao_caso_de_uso character varying(4000),
    dt_fechamento_caso_de_uso timestamp without time zone,
    dt_versao_caso_de_uso timestamp without time zone NOT NULL,
    ni_versao_caso_de_uso numeric(8,0),
    st_fechamento_caso_de_uso character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_coluna (
    tx_tabela character varying NOT NULL,
    tx_coluna character varying NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    tx_descricao character varying,
    st_chave character(1),
    tx_tabela_referencia character varying,
    cd_projeto_referencia numeric(8,0) NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_complemento (
    cd_complemento numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_caso_de_uso numeric NOT NULL,
    tx_complemento character varying,
    st_complemento character(1),
    ni_ordem_complemento numeric,
    dt_versao_caso_de_uso timestamp without time zone NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_condicao (
    cd_condicao numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_caso_de_uso numeric NOT NULL,
    tx_condicao character varying,
    st_condicao character(1),
    dt_versao_caso_de_uso timestamp without time zone NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_config_banco_de_dados (
    cd_projeto numeric NOT NULL,
    tx_adapter character varying(100),
    tx_host character varying(100),
    tx_dbname character varying(100),
    tx_username character varying(100),
    tx_password character varying(100),
    tx_schema character varying(100),
    id numeric(8,0),
    tx_port character varying(100)
);

CREATE TABLE K_SCHEMA.s_contato_empresa (
    cd_contato_empresa numeric(8,0) NOT NULL,
    cd_empresa numeric NOT NULL,
    tx_contato_empresa character varying,
    tx_telefone_contato character varying,
    tx_email_contato character varying,
    tx_celular_contato character varying,
    st_gerente_conta character(1),
    tx_obs_contato character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_contrato (
    cd_contrato numeric NOT NULL,
    cd_empresa numeric NOT NULL,
    tx_numero_contrato character varying,
    dt_inicio_contrato date,
    dt_fim_contrato date,
    st_aditivo character(1),
    tx_cpf_gestor character varying,
    ni_horas_previstas numeric,
    tx_objeto character varying,
    tx_gestor_contrato character varying,
    tx_fone_gestor_contrato character varying,
    tx_numero_processo character varying(20),
    tx_obs_contrato character varying,
    tx_localizacao_arquivo character varying,
    tx_co_gestor character varying,
    tx_cpf_co_gestor character varying,
    tx_fone_co_gestor_contrato character varying,
    nf_valor_passagens_diarias numeric(15,2),
    nf_valor_unitario_diaria numeric(15,2),
    st_contrato character(1),
    ni_mes_inicial_contrato numeric(4,0),
    ni_ano_inicial_contrato numeric(4,0),
    ni_mes_final_contrato numeric(4,0),
    ni_ano_final_contrato numeric(4,0),
    ni_qtd_meses_contrato numeric(4,0),
    nf_valor_unitario_hora numeric(15,2),
    nf_valor_contrato numeric(15,2),
    cd_contato_empresa numeric(8,0),
    id numeric(8,0),
    cd_definicao_metrica numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_custo_contrato_demanda (
    cd_contrato numeric NOT NULL,
    ni_mes_custo_contrato_demanda integer NOT NULL,
    ni_ano_custo_contrato_demanda integer NOT NULL,
    nf_total_multa numeric(8,2),
    nf_total_glosa numeric(8,2),
    nf_total_pago numeric(8,2),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_demanda (
    cd_demanda numeric NOT NULL,
    cd_objeto numeric,
    ni_ano_solicitacao numeric,
    ni_solicitacao numeric,
    dt_demanda timestamp without time zone,
    tx_demanda character varying,
    st_conclusao_demanda character(1),
    dt_conclusao_demanda timestamp without time zone,
    tx_solicitante_demanda character varying(200),
    cd_unidade numeric(8,0),
    st_fechamento_demanda character(1),
    dt_fechamento_demanda timestamp without time zone,
    st_prioridade_demanda character(1),
    id numeric(8,0),
    cd_status_atendimento numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_disponibilidade_servico (
    cd_disponibilidade_servico numeric(8,0) NOT NULL,
    cd_objeto numeric(8,0) NOT NULL,
    dt_inicio_analise_disp_servico date,
    dt_fim_analise_disp_servico date,
    tx_analise_disp_servico character varying,
    ni_indice_disp_servico numeric(8,2),
    tx_parecer_disp_servico character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_empresa (
    cd_empresa numeric NOT NULL,
    tx_empresa character varying,
    tx_cnpj_empresa character varying,
    tx_endereco_empresa character varying,
    tx_telefone_empresa character varying(20),
    tx_email_empresa character varying(200),
    tx_fax_empresa character varying(30),
    tx_arquivo_logomarca character varying(100),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_execucao_rotina (
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

CREATE TABLE K_SCHEMA.s_extrato_mensal (
    ni_mes_extrato numeric NOT NULL,
    ni_ano_extrato numeric NOT NULL,
    cd_contrato numeric NOT NULL,
    dt_fechamento_extrato date,
    ni_horas_extrato numeric,
    ni_qtd_parcela numeric,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_extrato_mensal_parcela (
    cd_contrato numeric NOT NULL,
    ni_ano_extrato numeric NOT NULL,
    ni_mes_extrato numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_parcela numeric NOT NULL,
    ni_hora_parcela_extrato numeric,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_fale_conosco (
    cd_fale_conosco numeric NOT NULL,
    tx_nome character varying,
    tx_email character varying,
    tx_assunto character varying,
    tx_mensagem character varying,
    tx_resposta character varying,
    st_respondida character(1),
    dt_registro timestamp without time zone,
    st_pendente character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_gerencia_qualidade (
    cd_gerencia_qualidade numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_profissional numeric NOT NULL,
    dt_auditoria_qualidade date,
    tx_fase_projeto character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_hist_prop_sub_item_metrica (
    dt_historico_proposta timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    cd_proposta numeric(8,0) NOT NULL,
    cd_definicao_metrica numeric(8,0) NOT NULL,
    cd_item_metrica numeric(8,0) NOT NULL,
    cd_sub_item_metrica numeric(8,0) NOT NULL,
    ni_valor_sub_item_metrica numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico (
    cd_historico numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    cd_etapa numeric NOT NULL,
    cd_atividade numeric NOT NULL,
    dt_inicio_historico date,
    dt_fim_historico date,
    tx_historico character varying,
    cd_profissional numeric NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico_execucao_demanda (
    cd_historico_execucao_demanda numeric NOT NULL,
    cd_profissional numeric NOT NULL,
    cd_demanda numeric NOT NULL,
    cd_nivel_servico numeric NOT NULL,
    dt_inicio timestamp without time zone,
    dt_fim timestamp without time zone,
    tx_historico character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico_execucao_rotina (
    dt_historico_execucao_rotina timestamp without time zone NOT NULL,
    cd_rotina numeric(8,0) NOT NULL,
    cd_objeto numeric(8,0) NOT NULL,
    cd_profissional numeric(8,0) NOT NULL,
    dt_execucao_rotina date NOT NULL,
    tx_historico_execucao_rotina character varying(4000),
    dt_historico_rotina timestamp without time zone,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico_pedido (
    cd_historico_pedido integer NOT NULL,
    cd_solicitacao_historico integer NOT NULL,
    dt_registro_historico timestamp without time zone DEFAULT now() NOT NULL,
    st_acao_historico character(1) DEFAULT 'P'::bpchar NOT NULL,
    tx_descricao_historico text,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico_projeto_continuado (
    cd_historico_proj_continuado numeric NOT NULL,
    cd_objeto numeric NOT NULL,
    cd_projeto_continuado numeric NOT NULL,
    cd_modulo_continuado numeric NOT NULL,
    cd_etapa numeric NOT NULL,
    cd_atividade numeric NOT NULL,
    dt_inicio_hist_proj_continuado date,
    dt_fim_hist_projeto_continuado date,
    tx_hist_projeto_continuado character varying,
    cd_profissional numeric NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico_proposta (
    dt_historico_proposta timestamp without time zone NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    tx_sigla_projeto character varying,
    tx_projeto character varying,
    tx_contexdo_geral character varying,
    tx_escopo_projeto character varying,
    tx_sigla_unidade character varying,
    tx_gestor_projeto character varying,
    tx_impacto_projeto character varying,
    tx_gerente_projeto character varying,
    st_metrica_historico character(3),
    tx_inicio_previsto character varying,
    tx_termino_previsto character varying,
    ni_horas_proposta numeric(8,1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico_proposta_metrica (
    dt_historico_proposta timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    cd_proposta numeric(8,0) NOT NULL,
    cd_definicao_metrica numeric(8,0) NOT NULL,
    ni_um_prop_metrica_historico numeric(8,1),
    tx_just_metrica_historico character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico_proposta_parcela (
    cd_proposta numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    dt_historico_proposta timestamp without time zone NOT NULL,
    cd_historico_proposta_parcela numeric NOT NULL,
    ni_parcela numeric NOT NULL,
    ni_mes_previsao_parcela numeric,
    ni_ano_previsao_parcela numeric,
    ni_horas_parcela numeric(8,1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_historico_proposta_produto (
    cd_historico_proposta_produto numeric NOT NULL,
    dt_historico_proposta timestamp without time zone NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_historico_proposta_parcela numeric NOT NULL,
    tx_produto character varying,
    cd_tipo_produto numeric,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_interacao (
    cd_interacao numeric NOT NULL,
    cd_modulo numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_caso_de_uso numeric NOT NULL,
    cd_ator numeric NOT NULL,
    tx_interacao character varying,
    ni_ordem_interacao numeric,
    st_interacao character(1),
    dt_versao_caso_de_uso timestamp without time zone NOT NULL,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_inventario (
    cd_inventario numeric(8,0) NOT NULL,
    cd_area_atuacao_ti numeric(8,0) NOT NULL,
    tx_inventario character varying(100),
    tx_desc_inventario character varying(4000),
    tx_obs_inventario character varying(4000),
    dt_ult_atual_inventario date,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_log (
    dt_ocorrencia timestamp without time zone NOT NULL,
    cd_log numeric(8,0) NOT NULL,
    cd_profissional numeric(8,0),
    tx_msg_log character varying NOT NULL,
    ni_prioridade numeric(8,0),
    tx_tabela character varying,
    tx_controller character varying,
    tx_ip character varying(15),
    tx_host character varying
);

CREATE TABLE K_SCHEMA.s_medicao (
    cd_medicao numeric NOT NULL,
    tx_medicao character varying(200),
    tx_objetivo_medicao character varying,
    st_nivel_medicao character(1),
    tx_procedimento_coleta character varying,
    tx_procedimento_analise character varying,
    id numeric(8,0),
    CONSTRAINT ck_K_SCHEMA_001 CHECK ((st_nivel_medicao = ANY (ARRAY['E'::bpchar, 'T'::bpchar])))
);

CREATE TABLE K_SCHEMA.s_mensageria (
    cd_mensageria numeric(8,0) NOT NULL,
    cd_objeto numeric NOT NULL,
    cd_perfil numeric,
    tx_mensagem character varying(4000),
    dt_postagem timestamp without time zone,
    dt_encerramento timestamp without time zone,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_modulo (
    cd_modulo numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_status numeric,
    tx_modulo character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_modulo_continuado (
    cd_modulo_continuado numeric NOT NULL,
    cd_objeto numeric NOT NULL,
    cd_projeto_continuado numeric NOT NULL,
    tx_modulo_continuado character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_objeto_contrato (
    cd_objeto numeric NOT NULL,
    cd_contrato numeric NOT NULL,
    tx_objeto character varying,
    ni_horas_objeto numeric,
    st_objeto_contrato character(1),
    st_viagem character(1),
    id numeric(8,0),
    st_parcela_orcamento character(1),
    ni_porcentagem_parc_orcamento numeric(3,2),
    st_necessita_justificativa character(1),
    ni_minutos_justificativa numeric(8,0),
    tx_hora_inicio_just_periodo_1 time without time zone,
    tx_hora_fim_just_periodo_1 time without time zone,
    tx_hora_inicio_just_periodo_2 time without time zone,
    tx_hora_fim_just_periodo_2 time without time zone,
    cd_area_atuacao_ti numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_ocorrencia_administrativa (
    dt_ocorrencia_administrativa timestamp without time zone NOT NULL,
    cd_evento numeric(8,0) NOT NULL,
    cd_contrato numeric NOT NULL,
    tx_ocorrencia_administrativa character varying(4000),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_parcela (
    cd_parcela numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    ni_parcela numeric,
    ni_horas_parcela numeric(8,1),
    ni_mes_previsao_parcela numeric,
    ni_ano_previsao_parcela numeric,
    ni_mes_execucao_parcela numeric,
    ni_ano_execucao_parcela numeric,
    st_modulo_proposta character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_penalizacao (
    dt_penalizacao date NOT NULL,
    cd_contrato numeric NOT NULL,
    cd_penalidade numeric NOT NULL,
    tx_obs_penalizacao character varying,
    tx_justificativa_penalizacao character varying,
    ni_qtd_ocorrencia numeric,
    st_aceite_justificativa character(1),
    id numeric(8,0),
    dt_justificativa date,
    tx_obs_justificativa character varying
);

CREATE TABLE K_SCHEMA.s_plano_implantacao (
    cd_projeto numeric(8,0) NOT NULL,
    cd_proposta numeric NOT NULL,
    tx_descricao_plano_implantacao character varying NOT NULL,
    cd_prof_plano_implantacao numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_pre_demanda (
    cd_pre_demanda numeric NOT NULL,
    cd_objeto_emissor numeric,
    cd_objeto_receptor numeric,
    ni_ano_solicitacao numeric,
    ni_solicitacao numeric,
    tx_pre_demanda character varying,
    st_aceite_pre_demanda character(1),
    dt_pre_demanda timestamp without time zone,
    cd_profissional_solicitante numeric(8,0),
    dt_fim_pre_demanda timestamp without time zone,
    st_fim_pre_demanda character(1),
    dt_aceite_pre_demanda timestamp without time zone,
    tx_obs_aceite_pre_demanda character varying(4000),
    tx_obs_reabertura_pre_demanda character varying(4000),
    st_reabertura_pre_demanda character(1),
    cd_unidade numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_pre_projeto (
    cd_pre_projeto numeric(8,0) NOT NULL,
    cd_unidade numeric,
    cd_gerente_pre_projeto numeric,
    tx_pre_projeto character varying(200),
    tx_sigla_pre_projeto character varying(100),
    tx_contexto_geral_pre_projeto character varying(4000),
    tx_escopo_pre_projeto character varying(4000),
    tx_gestor_pre_projeto character varying(200),
    tx_obs_pre_projeto character varying(4000),
    st_impacto_pre_projeto character(1),
    st_prioridade_pre_projeto character(1),
    tx_horas_estimadas character varying(10),
    tx_pub_alcancado_pre_proj character varying(200),
    cd_contrato numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_pre_projeto_evolutivo (
    cd_pre_projeto_evolutivo numeric NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    tx_pre_projeto_evolutivo character varying(200),
    tx_objetivo_pre_proj_evol character varying,
    st_gerencia_mudanca character(1),
    dt_gerencia_mudanca date,
    cd_contrato numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_previsao_projeto_diario (
    cd_projeto numeric NOT NULL,
    ni_mes numeric NOT NULL,
    ni_dia numeric NOT NULL,
    ni_horas numeric,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_processamento_parcela (
    cd_processamento_parcela numeric(8,0) NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_parcela numeric NOT NULL,
    cd_objeto_execucao numeric,
    ni_ano_solicitacao_execucao numeric,
    ni_solicitacao_execucao numeric,
    st_autorizacao_parcela character(1),
    dt_autorizacao_parcela timestamp without time zone,
    cd_prof_autorizacao_parcela numeric(8,0),
    st_fechamento_parcela character(1),
    dt_fechamento_parcela timestamp without time zone,
    cd_prof_fechamento_parcela numeric,
    st_parecer_tecnico_parcela character(1),
    dt_parecer_tecnico_parcela timestamp without time zone,
    tx_obs_parecer_tecnico_parcela character varying,
    cd_prof_parecer_tecnico_parc numeric,
    st_aceite_parcela character(1),
    dt_aceite_parcela timestamp without time zone,
    tx_obs_aceite_parcela character varying,
    cd_profissional_aceite_parcela numeric,
    st_homologacao_parcela character(1),
    dt_homologacao_parcela timestamp without time zone,
    tx_obs_homologacao_parcela character varying,
    cd_prof_homologacao_parcela numeric,
    st_ativo character(1),
    st_pendente character(1),
    dt_inicio_pendencia timestamp without time zone,
    dt_fim_pendencia timestamp without time zone,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_processamento_proposta (
    cd_processamento_proposta numeric(8,0) NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    st_fechamento_proposta character(1),
    dt_fechamento_proposta timestamp without time zone,
    cd_prof_fechamento_proposta numeric,
    st_parecer_tecnico_proposta character(1),
    dt_parecer_tecnico_proposta timestamp without time zone,
    tx_obs_parecer_tecnico_prop character varying,
    cd_prof_parecer_tecnico_propos numeric,
    st_aceite_proposta character(1),
    dt_aceite_proposta timestamp without time zone,
    tx_obs_aceite_proposta character varying,
    cd_prof_aceite_proposta numeric,
    st_homologacao_proposta character(1),
    dt_homologacao_proposta timestamp without time zone,
    tx_obs_homologacao_proposta character varying,
    cd_prof_homologacao_proposta numeric,
    st_alocacao_proposta character(1),
    dt_alocacao_proposta timestamp without time zone,
    cd_prof_alocacao_proposta numeric,
    st_ativo character(1),
    tx_motivo_alteracao_proposta character varying(4000),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_produto_parcela (
    cd_produto_parcela numeric NOT NULL,
    cd_proposta numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_parcela numeric NOT NULL,
    tx_produto_parcela character varying,
    cd_tipo_produto numeric,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_profissional (
    cd_profissional numeric NOT NULL,
    tx_profissional character varying,
    cd_relacao_contratual numeric,
    cd_empresa numeric(8,0),
    tx_nome_conhecido character varying(100),
    tx_telefone_residencial character varying(20),
    tx_celular_profissional character varying(20),
    tx_ramal_profissional character varying(10),
    tx_endereco_profissional character varying(200),
    dt_nascimento_profissional date,
    dt_inicio_trabalho date,
    dt_saida_profissional date,
    tx_email_institucional character varying(200),
    tx_email_pessoal character varying(200),
    st_nova_senha character(1),
    st_inativo character(1),
    tx_senha character varying(50),
    tx_data_ultimo_acesso character varying,
    tx_hora_ultimo_acesso character varying,
    cd_perfil numeric(8,0),
    st_dados_todos_contratos character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_projeto (
    cd_projeto numeric NOT NULL,
    cd_profissional_gerente numeric,
    cd_unidade numeric,
    cd_status numeric,
    tx_projeto character varying,
    tx_obs_projeto character varying,
    tx_sigla_projeto character varying,
    tx_gestor_projeto character varying,
    st_impacto_projeto character(1),
    st_prioridade_projeto character(1),
    tx_escopo_projeto character varying,
    tx_contexto_geral_projeto character varying,
    tx_publico_alcancado character varying(200),
    ni_mes_inicio_previsto numeric(4,0),
    ni_ano_inicio_previsto numeric(4,0),
    ni_mes_termino_previsto numeric(4,0),
    ni_ano_termino_previsto numeric(4,0),
    tx_co_gestor_projeto character varying(200),
    st_dicionario_dados character(1) DEFAULT 0,
    st_informacoes_tecnicas character(1) DEFAULT 0,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_projeto_continuado (
    cd_projeto_continuado numeric NOT NULL,
    cd_objeto numeric NOT NULL,
    tx_projeto_continuado character varying,
    tx_objetivo_projeto_continuado character varying,
    tx_obs_projeto_continuado character varying,
    st_prioridade_proj_continuado character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_projeto_previsto (
    cd_projeto_previsto numeric NOT NULL,
    cd_contrato numeric NOT NULL,
    cd_unidade numeric,
    tx_projeto_previsto character varying,
    ni_horas_projeto_previsto numeric,
    st_projeto_previsto character(1),
    tx_descricao_projeto_previsto character varying,
    id numeric(8,0),
    cd_definicao_metrica numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_proposta (
    cd_proposta numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    cd_objeto numeric NOT NULL,
    ni_ano_solicitacao numeric NOT NULL,
    ni_solicitacao numeric NOT NULL,
    st_encerramento_proposta character(1),
    dt_encerramento_proposta timestamp without time zone,
    cd_prof_encerramento_proposta numeric,
    ni_horas_proposta numeric(8,1),
    st_alteracao_proposta character(1),
    st_contrato_anterior character(1),
    tx_motivo_insatisfacao character varying(4000),
    tx_gestao_qualidade character varying(4000),
    st_descricao character(1) DEFAULT 0,
    st_profissional character(1) DEFAULT 0,
    st_metrica character(1) DEFAULT 0,
    st_documentacao character(1) DEFAULT 0,
    st_modulo character(1) DEFAULT 0,
    st_parcela character(1) DEFAULT 0,
    st_produto character(1) DEFAULT 0,
    st_caso_de_uso character(1) DEFAULT 0,
    ni_mes_proposta numeric(4,0),
    ni_ano_proposta numeric(4,0),
    tx_objetivo_proposta character varying(4000),
    id numeric(8,0),
    st_requisito character(1) DEFAULT 0,
    nf_indice_avaliacao_proposta numeric(8,0),
    st_objetivo_proposta character(1) DEFAULT 0,
    st_suspensao_proposta character(1)
);

CREATE TABLE K_SCHEMA.s_regra_negocio (
    cd_regra_negocio numeric NOT NULL,
    dt_regra_negocio timestamp without time zone NOT NULL,
    cd_projeto_regra_negocio numeric(8,0) NOT NULL,
    tx_regra_negocio character varying,
    tx_descricao_regra_negocio character varying,
    st_regra_negocio character(1),
    ni_versao_regra_negocio numeric,
    dt_fechamento_regra_negocio date,
    ni_ordem_regra_negocio numeric(8,0),
    st_fechamento_regra_negocio character(1),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_requisito (
    cd_requisito numeric NOT NULL,
    dt_versao_requisito timestamp without time zone NOT NULL,
    cd_projeto numeric(8,0) NOT NULL,
    st_tipo_requisito character(1),
    tx_requisito character varying,
    tx_descricao_requisito character varying,
    ni_versao_requisito numeric(8,0),
    st_prioridade_requisito character(1),
    st_requisito character(1),
    tx_usuario_solicitante character varying,
    tx_nivel_solicitante character varying,
    st_fechamento_requisito character(1),
    dt_fechamento_requisito date,
    ni_ordem numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_reuniao (
    cd_reuniao numeric NOT NULL,
    cd_projeto numeric NOT NULL,
    dt_reuniao date,
    tx_pauta character varying,
    tx_participantes character varying,
    tx_ata character varying,
    tx_local_reuniao character varying,
    cd_profissional numeric,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_reuniao_geral (
    cd_objeto numeric NOT NULL,
    cd_reuniao_geral numeric NOT NULL,
    dt_reuniao date,
    tx_pauta character varying,
    tx_participantes character varying,
    tx_ata character varying,
    tx_local_reuniao character varying,
    cd_profissional numeric,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_situacao_projeto (
    cd_projeto numeric NOT NULL,
    ni_mes_situacao_projeto numeric NOT NULL,
    ni_ano_situacao_projeto numeric NOT NULL,
    tx_situacao_projeto character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_solicitacao (
    ni_solicitacao numeric NOT NULL,
    ni_ano_solicitacao numeric NOT NULL,
    cd_objeto numeric NOT NULL,
    cd_profissional numeric,
    cd_unidade numeric,
    tx_solicitacao character varying,
    st_solicitacao character(1),
    tx_justificativa_solicitacao character varying,
    dt_justificativa timestamp without time zone,
    st_aceite character(1),
    dt_aceite timestamp without time zone,
    tx_obs_aceite character varying,
    st_fechamento character(1),
    dt_fechamento timestamp without time zone,
    st_homologacao character(1),
    dt_homologacao timestamp without time zone,
    tx_obs_homologacao character varying,
    ni_dias_execucao numeric,
    tx_problema_encontrado character varying,
    tx_solucao_solicitacao character varying,
    st_grau_satisfacao character(1),
    tx_obs_grau_satisfacao character varying,
    dt_grau_satisfacao timestamp without time zone,
    dt_leitura_solicitacao timestamp without time zone,
    dt_solicitacao timestamp without time zone,
    tx_solicitante character varying,
    tx_sala_solicitante character varying,
    tx_telefone_solicitante character varying,
    tx_obs_solicitacao character varying,
    tx_execucao_solicitacao character varying(4000),
    ni_prazo_atendimento numeric(8,0),
    id numeric(8,0),
    st_aceite_just_solicitacao character(1),
    tx_obs_aceite_just_solicitacao character varying(1000),
    cd_item_inventariado numeric(8,0),
    cd_item_inventario numeric(8,0),
    cd_inventario numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_solicitacao_pedido (
    cd_solicitacao_pedido integer NOT NULL,
    cd_usuario_pedido integer NOT NULL,
    cd_unidade_pedido numeric NOT NULL,
    dt_solicitacao_pedido timestamp without time zone DEFAULT now() NOT NULL,
    st_situacao_pedido character(1) DEFAULT 'P'::bpchar NOT NULL,
    tx_observacao_pedido text,
    dt_encaminhamento_pedido timestamp without time zone,
    dt_autorizacao_competente timestamp without time zone,
    tx_analise_aut_competente character varying,
    dt_analise_area_ti_solicitacao timestamp without time zone,
    tx_analise_area_ti_solicitacao character varying,
    dt_analise_area_ti_chefia_sol timestamp without time zone,
    tx_analise_area_ti_chefia_sol character varying,
    dt_analise_comite timestamp without time zone,
    tx_analise_comite character varying,
    dt_analise_area_ti_chefia_exec timestamp without time zone,
    tx_analise_area_ti_chefia_exec character varying,
    cd_usuario_aut_competente numeric(8,0),
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_tabela (
    tx_tabela character varying NOT NULL,
    cd_projeto numeric NOT NULL,
    tx_descricao character varying,
    id numeric(8,0)
);

CREATE TABLE K_SCHEMA.s_usuario_pedido (
    cd_usuario_pedido integer NOT NULL,
    cd_unidade_usuario numeric NOT NULL,
    st_autoridade character(1) DEFAULT 'N'::bpchar NOT NULL,
    st_inativo character(1) DEFAULT 'N'::bpchar NOT NULL,
    tx_nome_usuario character varying(100) NOT NULL,
    tx_email_usuario character varying(100) NOT NULL,
    tx_senha_acesso character varying(40) NOT NULL,
    tx_sala_usuario character varying(50),
    tx_telefone_usuario character varying(50),
    id numeric(8,0)
);

