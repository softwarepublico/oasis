

CREATE SCHEMA oasis;


ALTER SCHEMA oasis OWNER TO oasis;


CREATE TABLE a_baseline_item_controle (
    cd_projeto number(8,0) NOT NULL,
    dt_baseline timestamp NOT NULL,
    cd_item_controle_baseline number(8,0) NOT NULL,
    cd_item_controlado number(8,0) NOT NULL,
    dt_versao_item_controlado timestamp NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_baseline_item_controle OWNER TO oasis;

--
-- TOC entry 5101 (class 0 OID 0)
-- Dependencies: 145
-- Name: TABLE a_baseline_item_controle; Type: COMMENT; Schema: oasis; Owner: postgres
--




--
-- TOC entry 5102 (class 0 OID 0)
-- Dependencies: 145
-- Name: COLUMN a_baseline_item_controle.cd_projeto; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_baseline_item_controle.cd_projeto IS 'Código da tabela de s_projeto';


--
-- TOC entry 146 (class 1259 OID 16389)
-- Dependencies: 11
-- Name: a_conhecimento_projeto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_conhecimento_projeto (
    cd_tipo_conhecimento number NOT NULL,
    cd_conhecimento number NOT NULL,
    cd_projeto number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_conhecimento_projeto OWNER TO oasis;

--
-- TOC entry 5103 (class 0 OID 0)
-- Dependencies: 146
-- Name: COLUMN a_conhecimento_projeto.cd_projeto; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_conhecimento_projeto.cd_projeto IS 'fk da tabela projeto';


--
-- TOC entry 147 (class 1259 OID 16395)
-- Dependencies: 11
-- Name: a_conjunto_medida_medicao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_conjunto_medida_medicao (
    cd_conjunto_medida number(8,0) NOT NULL,
    cd_box_inicio number(8,0) NOT NULL,
    cd_medicao number(8,0) NOT NULL,
    st_nivel_conjunto_medida char(1),
    id number(8,0)
);


ALTER TABLE oasis.a_conjunto_medida_medicao OWNER TO oasis;

--
-- TOC entry 148 (class 1259 OID 16398)
-- Dependencies: 11
-- Name: a_contrato_definicao_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_contrato_definicao_metrica (
    cd_contrato number(8,0) NOT NULL,
    cd_definicao_metrica number(8,0) NOT NULL,
    id number(8,0),
    nf_fator_relacao_metrica_pad number(4,2),
    st_metrica_padrao char(1)
);


ALTER TABLE oasis.a_contrato_definicao_metrica OWNER TO oasis;

--
-- TOC entry 149 (class 1259 OID 16401)
-- Dependencies: 11
-- Name: a_contrato_evento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_contrato_evento (
    cd_contrato number NOT NULL,
    cd_evento number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_contrato_evento OWNER TO oasis;

--
-- TOC entry 150 (class 1259 OID 16407)
-- Dependencies: 11
-- Name: a_contrato_item_inventario; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_contrato_item_inventario (
    cd_contrato number(8,0) NOT NULL,
    cd_item_inventario number(8,0) NOT NULL,
    id number(8,0),
    cd_inventario number(8,0)
);


ALTER TABLE oasis.a_contrato_item_inventario OWNER TO oasis;

--
-- TOC entry 151 (class 1259 OID 16410)
-- Dependencies: 11
-- Name: a_contrato_projeto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_contrato_projeto (
    cd_contrato number(8,0) NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_contrato_projeto OWNER TO oasis;

--
-- TOC entry 152 (class 1259 OID 16413)
-- Dependencies: 11
-- Name: a_controle; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_controle (
    cd_controle number NOT NULL,
    cd_projeto_previsto number NOT NULL,
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    cd_contrato number NOT NULL,
    ni_horas number(8,1),
    st_controle char(1),
    st_modulo_proposta char(1),
    dt_lancamento timestamp,
    cd_profissional number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.a_controle OWNER TO oasis;

--
-- TOC entry 153 (class 1259 OID 16419)
-- Dependencies: 11
-- Name: a_definicao_processo; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_definicao_processo (
    cd_perfil number NOT NULL,
    cd_funcionalidade number(8,0) NOT NULL,
    st_definicao_processo char(1) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_definicao_processo OWNER TO oasis;

--
-- TOC entry 154 (class 1259 OID 16425)
-- Dependencies: 11
-- Name: a_demanda_prof_nivel_servico; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_demanda_prof_nivel_servico (
    cd_demanda number NOT NULL,
    cd_profissional number NOT NULL,
    cd_nivel_servico number NOT NULL,
    dt_fechamento_nivel_servico timestamp,
    st_fechamento_nivel_servico char(1),
    st_fechamento_gerente char(1),
    dt_fechamento_gerente timestamp,
    dt_leitura_nivel_servico timestamp,
    dt_demanda_nivel_servico timestamp,
    tx_motivo_fechamento varchar2(4000),
    tx_obs_nivel_servico varchar2(4000),
    dt_justificativa_nivel_servico timestamp,
    tx_justificativa_nivel_servico varchar2(4000),
    id number(8,0),
    st_nova_obs_nivel_servico char(1),
    tx_nova_obs_nivel_servico varchar2(4000)
);


ALTER TABLE oasis.a_demanda_prof_nivel_servico OWNER TO oasis;

--
-- TOC entry 5107 (class 0 OID 0)
-- Dependencies: 154
-- Name: COLUMN a_demanda_prof_nivel_servico.st_nova_obs_nivel_servico; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_demanda_prof_nivel_servico.st_nova_obs_nivel_servico IS 'Este campo armazena a indicação de
nova observação de nível de serviço';


--
-- TOC entry 5108 (class 0 OID 0)
-- Dependencies: 154
-- Name: COLUMN a_demanda_prof_nivel_servico.tx_nova_obs_nivel_servico; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_demanda_prof_nivel_servico.tx_nova_obs_nivel_servico IS 'Este campo armazena a nova observação de nível de serviço';


--
-- TOC entry 155 (class 1259 OID 16431)
-- Dependencies: 11
-- Name: a_demanda_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_demanda_profissional (
    cd_profissional number NOT NULL,
    cd_demanda number NOT NULL,
    dt_demanda_profissional timestamp,
    st_fechamento_demanda char(1),
    dt_fechamento_demanda timestamp,
    id number(8,0)
);


ALTER TABLE oasis.a_demanda_profissional OWNER TO oasis;

--
-- TOC entry 156 (class 1259 OID 16437)
-- Dependencies: 11
-- Name: a_disponibilidade_servico_doc; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_disponibilidade_servico_doc (
    cd_disponibilidade_servico number(8,0) NOT NULL,
    cd_objeto number(8,0) NOT NULL,
    cd_tipo_documentacao number(8,0) NOT NULL,
    dt_doc_disponibilidade_servico timestamp NOT NULL,
    tx_nome_arq_disp_servico varchar2,
    tx_arquivo_disp_servico varchar2,
    id number(8,0)
);


ALTER TABLE oasis.a_disponibilidade_servico_doc OWNER TO oasis;

--
-- TOC entry 157 (class 1259 OID 16443)
-- Dependencies: 11
-- Name: a_doc_projeto_continuo; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_doc_projeto_continuo (
    dt_doc_projeto_continuo timestamp NOT NULL,
    cd_objeto number NOT NULL,
    cd_projeto_continuado number NOT NULL,
    cd_tipo_documentacao number NOT NULL,
    tx_arq_doc_projeto_continuo varchar2,
    tx_nome_arquivo varchar2(100),
    id number(8,0)
);


ALTER TABLE oasis.a_doc_projeto_continuo OWNER TO oasis;

--
-- TOC entry 158 (class 1259 OID 16449)
-- Dependencies: 11
-- Name: a_documentacao_contrato; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_documentacao_contrato (
    dt_documentacao_contrato timestamp NOT NULL,
    cd_contrato number NOT NULL,
    cd_tipo_documentacao number NOT NULL,
    tx_arq_documentacao_contrato varchar2,
    tx_nome_arquivo varchar2(100),
    id number(8,0)
);


ALTER TABLE oasis.a_documentacao_contrato OWNER TO oasis;

--
-- TOC entry 5111 (class 0 OID 0)
-- Dependencies: 158
-- Name: TABLE a_documentacao_contrato; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON TABLE a_documentacao_contrato IS 'Essa tabela armazena os dados de documentação de contrato. Permite estabelecer o tipo de documentação e os tipo s de arquivos permitidos para a tipo de documentação.';


--
-- TOC entry 5112 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN a_documentacao_contrato.dt_documentacao_contrato; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_documentacao_contrato.dt_documentacao_contrato IS 'Esse campo armazena a data do upload da documentação do contrato';


--
-- TOC entry 5113 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN a_documentacao_contrato.cd_contrato; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_documentacao_contrato.cd_contrato IS 'Código sequencial identificador do contrato';


--
-- TOC entry 5114 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN a_documentacao_contrato.cd_tipo_documentacao; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_documentacao_contrato.cd_tipo_documentacao IS 'Código sequencial identificador do tipo de documento';


--
-- TOC entry 5115 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN a_documentacao_contrato.tx_arq_documentacao_contrato; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_documentacao_contrato.tx_arq_documentacao_contrato IS 'Este campo armazena o nome do arquivo que está gravado no diretório do OASIS que foi realizado o upload.';


--
-- TOC entry 5116 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN a_documentacao_contrato.tx_nome_arquivo; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_documentacao_contrato.tx_nome_arquivo IS 'Este campo armazena o nome do arquivo que foi indicado para ser feito o upload';


--
-- TOC entry 5117 (class 0 OID 0)
-- Dependencies: 158
-- Name: COLUMN a_documentacao_contrato.id; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_documentacao_contrato.id IS 'Este campo armazena o código do profissional que realizou a última gravação ou atualização';


--
-- TOC entry 159 (class 1259 OID 16455)
-- Dependencies: 11
-- Name: a_documentacao_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_documentacao_profissional (
    dt_documentacao_profissional timestamp NOT NULL,
    cd_tipo_documentacao number NOT NULL,
    cd_profissional number NOT NULL,
    tx_arq_documentacao_prof varchar2,
    tx_nome_arquivo varchar2(100),
    id number(8,0)
);


ALTER TABLE oasis.a_documentacao_profissional OWNER TO oasis;

--
-- TOC entry 160 (class 1259 OID 16461)
-- Dependencies: 11
-- Name: a_documentacao_projeto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_documentacao_projeto (
    dt_documentacao_projeto timestamp NOT NULL,
    cd_projeto number NOT NULL,
    cd_tipo_documentacao number NOT NULL,
    tx_arq_documentacao_projeto varchar2,
    tx_nome_arquivo varchar2(100),
    st_documentacao_controle char(1),
    id number(8,0),
    st_doc_acompanhamento char(1)
);


ALTER TABLE oasis.a_documentacao_projeto OWNER TO oasis;

--
-- TOC entry 161 (class 1259 OID 16467)
-- Dependencies: 11
-- Name: a_form_inventario; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_form_inventario (
    cd_inventario number(8,0) NOT NULL,
    cd_item_inventario number(8,0) NOT NULL,
    cd_item_inventariado number(8,0) NOT NULL,
    cd_subitem_inventario number(8,0) NOT NULL,
    cd_subitem_inv_descri number(8,0) NOT NULL,
    tx_valor_subitem_inventario varchar2(1000),
    id number(8,0)
);


ALTER TABLE oasis.a_form_inventario OWNER TO oasis;

--
-- TOC entry 162 (class 1259 OID 16473)
-- Dependencies: 11
-- Name: a_funcionalidade_menu; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_funcionalidade_menu (
    cd_funcionalidade number(8,0) NOT NULL,
    cd_menu number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_funcionalidade_menu OWNER TO oasis;

--
-- TOC entry 163 (class 1259 OID 16476)
-- Dependencies: 11
-- Name: a_gerencia_mudanca; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_gerencia_mudanca (
    dt_gerencia_mudanca timestamp NOT NULL,
    cd_item_controle_baseline number(8,0) NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    cd_item_controlado number(8,0) NOT NULL,
    dt_versao_item_controlado timestamp NOT NULL,
    tx_motivo_mudanca varchar2,
    st_mudanca_metrica char(1),
    ni_custo_provavel_mudanca number(8,0),
    st_reuniao char(1),
    tx_decisao_mudanca varchar2,
    dt_decisao_mudanca date,
    cd_reuniao number(8,0),
    cd_projeto_reuniao number(8,0),
    st_decisao_mudanca char(1),
    st_execucao_mudanca char(1),
    id number(8,0)
);


ALTER TABLE oasis.a_gerencia_mudanca OWNER TO oasis;

--
-- TOC entry 164 (class 1259 OID 16482)
-- Dependencies: 11
-- Name: a_informacao_tecnica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_informacao_tecnica (
    cd_projeto number NOT NULL,
    cd_tipo_dado_tecnico number NOT NULL,
    tx_conteudo_informacao_tecnica varchar2,
    id number(8,0)
);


ALTER TABLE oasis.a_informacao_tecnica OWNER TO oasis;

--
-- TOC entry 165 (class 1259 OID 16488)
-- Dependencies: 11
-- Name: a_item_inventariado; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_item_inventariado (
    cd_item_inventariado number(8,0) NOT NULL,
    cd_inventario number(8,0) NOT NULL,
    cd_item_inventario number(8,0) NOT NULL,
    tx_item_inventariado varchar2(100),
    id number(8,0)
);


ALTER TABLE oasis.a_item_inventariado OWNER TO oasis;

--
-- TOC entry 166 (class 1259 OID 16491)
-- Dependencies: 4427 11
-- Name: a_item_teste_caso_de_uso; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_item_teste_caso_de_uso (
    cd_item_teste_caso_de_uso number NOT NULL,
    cd_item_teste number(8,0) NOT NULL,
    cd_caso_de_uso number NOT NULL,
    dt_versao_caso_de_uso timestamp NOT NULL,
    cd_projeto number NOT NULL,
    cd_modulo number NOT NULL,
    st_analise char(1),
    tx_analise varchar2(4000),
    dt_analise date,
    cd_profissional_analise number,
    st_solucao char(1),
    tx_solucao varchar2(4000),
    dt_solucao date,
    cd_profissional_solucao number,
    st_homologacao char(1),
    tx_homologacao varchar2(4000),
    dt_homologacao date,
    cd_profissional_homologacao number,
    st_item_teste_caso_de_uso char(1),
    id number(8,0),
    CONSTRAINT ck_oasis_009 CHECK (((st_item_teste_caso_de_uso IS NULL) OR (st_item_teste_caso_de_uso IN ('H', 'F')))))
);


ALTER TABLE oasis.a_item_teste_caso_de_uso OWNER TO oasis;

--
-- TOC entry 5121 (class 0 OID 0)
-- Dependencies: 166
-- Name: COLUMN a_item_teste_caso_de_uso.st_item_teste_caso_de_uso; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_item_teste_caso_de_uso.st_item_teste_caso_de_uso IS 'null : em aberto
''H''  : para homologar
''F''  : homologado';


--
-- TOC entry 167 (class 1259 OID 16498)
-- Dependencies: 11
-- Name: a_item_teste_caso_de_uso_doc; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_item_teste_caso_de_uso_doc (
    cd_arq_item_teste_caso_de_uso number(8,0) NOT NULL,
    cd_item_teste_caso_de_uso number NOT NULL,
    cd_item_teste number(8,0) NOT NULL,
    cd_caso_de_uso number NOT NULL,
    dt_versao_caso_de_uso timestamp NOT NULL,
    cd_projeto number NOT NULL,
    cd_modulo number NOT NULL,
    cd_tipo_documentacao number NOT NULL,
    tx_nome_arq_teste_caso_de_uso varchar2,
    tx_arq_item_teste_caso_de_uso varchar2,
    id number(8,0)
);


ALTER TABLE oasis.a_item_teste_caso_de_uso_doc OWNER TO oasis;

--
-- TOC entry 168 (class 1259 OID 16504)
-- Dependencies: 4428 11
-- Name: a_item_teste_regra_negocio; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_item_teste_regra_negocio (
    cd_item_teste_regra_negocio number NOT NULL,
    cd_item_teste number NOT NULL,
    cd_regra_negocio number NOT NULL,
    dt_regra_negocio timestamp NOT NULL,
    cd_projeto_regra_negocio number(8,0) NOT NULL,
    st_analise char(1),
    tx_analise varchar2,
    dt_analise date,
    cd_profissional_analise number,
    st_solucao char(1),
    tx_solucao varchar2,
    dt_solucao date,
    cd_profissional_solucao number,
    st_homologacao char(1),
    tx_homologacao varchar2,
    dt_homologacao date,
    cd_profissional_homologacao number,
    st_item_teste_regra_negocio char(1),
    id number(8,0),
    CONSTRAINT ck_oasis_008 CHECK (((st_item_teste_regra_negocio IS NULL) OR (st_item_teste_regra_negocio IN ('H', 'F'))))
);


ALTER TABLE oasis.a_item_teste_regra_negocio OWNER TO oasis;

--
-- TOC entry 5122 (class 0 OID 0)
-- Dependencies: 168
-- Name: COLUMN a_item_teste_regra_negocio.st_item_teste_regra_negocio; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_item_teste_regra_negocio.st_item_teste_regra_negocio IS 'null : em aberto
''H''  : para homologar
''F''  : homologado';


--
-- TOC entry 169 (class 1259 OID 16511)
-- Dependencies: 11
-- Name: a_item_teste_regra_negocio_doc; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_item_teste_regra_negocio_doc (
    cd_arq_item_teste_regra_neg number(8,0) NOT NULL,
    cd_item_teste_regra_negocio number NOT NULL,
    cd_item_teste number NOT NULL,
    cd_regra_negocio number NOT NULL,
    dt_regra_negocio timestamp NOT NULL,
    cd_projeto_regra_negocio number(8,0) NOT NULL,
    cd_tipo_documentacao number NOT NULL,
    tx_nome_arq_teste_regra_negoc varchar2,
    tx_arq_item_teste_regra_negoc varchar2,
    id number(8,0)
);


ALTER TABLE oasis.a_item_teste_regra_negocio_doc OWNER TO oasis;

--
-- TOC entry 170 (class 1259 OID 16517)
-- Dependencies: 4429 11
-- Name: a_item_teste_requisito; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_item_teste_requisito (
    cd_item_teste_requisito number NOT NULL,
    cd_item_teste number NOT NULL,
    cd_requisito number NOT NULL,
    dt_versao_requisito timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    st_analise char(1),
    tx_analise varchar2,
    dt_analise date,
    cd_profissional_analise number,
    st_solucao char(1),
    tx_solucao varchar2,
    dt_solucao date,
    cd_profissional_solucao number,
    st_homologacao char(1),
    tx_homologacao varchar2,
    dt_homologacao date,
    cd_profissional_homologacao number,
    st_item_teste_requisito char(1),
    id number(8,0),
    CONSTRAINT ck_oasis_007 CHECK (((st_item_teste_requisito IS NULL) OR (st_item_teste_requisito IN ('H', 'F'))))
);


ALTER TABLE oasis.a_item_teste_requisito OWNER TO oasis;

--
-- TOC entry 5123 (class 0 OID 0)
-- Dependencies: 170
-- Name: COLUMN a_item_teste_requisito.st_item_teste_requisito; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_item_teste_requisito.st_item_teste_requisito IS 'null : em aberto
''H''  : para homologar
''F''  : homologado';


--
-- TOC entry 171 (class 1259 OID 16524)
-- Dependencies: 11
-- Name: a_item_teste_requisito_doc; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_item_teste_requisito_doc (
    cd_arq_item_teste_requisito number(8,0) NOT NULL,
    cd_item_teste_requisito number NOT NULL,
    cd_item_teste number NOT NULL,
    cd_requisito number NOT NULL,
    dt_versao_requisito timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    cd_tipo_documentacao number NOT NULL,
    tx_nome_arq_teste_requisito varchar2,
    tx_arq_item_teste_requisito varchar2,
    id number(8,0)
);


ALTER TABLE oasis.a_item_teste_requisito_doc OWNER TO oasis;

--
-- TOC entry 172 (class 1259 OID 16530)
-- Dependencies: 4430 11
-- Name: a_medicao_medida; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_medicao_medida (
    cd_medicao number NOT NULL,
    cd_medida number NOT NULL,
    st_prioridade_medida char(1),
    id number(8,0),
    CONSTRAINT ck_oasis_006 CHECK ((st_prioridade_medida IN ('A', 'L', 'M', 'B')))
);


ALTER TABLE oasis.a_medicao_medida OWNER TO oasis;

--
-- TOC entry 5124 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN a_medicao_medida.st_prioridade_medida; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_medicao_medida.st_prioridade_medida IS 'valores possíveis:
A=Altíssima;
L=Alta;
M=Média; e
B=Baixa.';


--
-- TOC entry 173 (class 1259 OID 16537)
-- Dependencies: 11
-- Name: a_objeto_contrato_atividade; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_objeto_contrato_atividade (
    cd_objeto number(8,0) NOT NULL,
    cd_etapa number(8,0) NOT NULL,
    cd_atividade number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_objeto_contrato_atividade OWNER TO oasis;

--
-- TOC entry 174 (class 1259 OID 16540)
-- Dependencies: 11
-- Name: a_objeto_contrato_papel_prof; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_objeto_contrato_papel_prof (
    cd_objeto number(8,0) NOT NULL,
    cd_papel_profissional number(8,0) NOT NULL,
    tx_descricao_papel_prof varchar2,
    id number(8,0)
);


ALTER TABLE oasis.a_objeto_contrato_papel_prof OWNER TO oasis;

--
-- TOC entry 175 (class 1259 OID 16546)
-- Dependencies: 11
-- Name: a_objeto_contrato_perfil_prof; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_objeto_contrato_perfil_prof (
    cd_objeto number(8,0) NOT NULL,
    cd_perfil_profissional number(8,0) NOT NULL,
    tx_descricao_perfil_prof varchar2,
    id number(8,0)
);


ALTER TABLE oasis.a_objeto_contrato_perfil_prof OWNER TO oasis;

--
-- TOC entry 176 (class 1259 OID 16552)
-- Dependencies: 11
-- Name: a_objeto_contrato_rotina; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_objeto_contrato_rotina (
    cd_objeto number(8,0) NOT NULL,
    cd_rotina number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_objeto_contrato_rotina OWNER TO oasis;

--
-- TOC entry 5125 (class 0 OID 0)
-- Dependencies: 176
-- Name: TABLE a_objeto_contrato_rotina; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON TABLE a_objeto_contrato_rotina IS 'Essa tabela armazena os dados de associação das
tabelas  s_objeto_contrato e b_rotina.';


--
-- TOC entry 5126 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN a_objeto_contrato_rotina.cd_objeto; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_objeto_contrato_rotina.cd_objeto IS 'Código sequencial identificador do objeto do contrato';


--
-- TOC entry 5127 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN a_objeto_contrato_rotina.cd_rotina; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_objeto_contrato_rotina.cd_rotina IS 'Código sequencial identificador da rotina';


--
-- TOC entry 5128 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN a_objeto_contrato_rotina.id; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_objeto_contrato_rotina.id IS 'Este campo armazena o código do
profissional que realizou a última
gravação ou atualização.';


--
-- TOC entry 177 (class 1259 OID 16555)
-- Dependencies: 4431 4432 11
-- Name: a_opcao_resp_pergunta_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_opcao_resp_pergunta_pedido (
    cd_pergunta_pedido number(8,0) NOT NULL,
    cd_resposta_pedido number(8,0) NOT NULL,
    st_resposta_texto char(1) DEFAULT 'N' NOT NULL,
    ni_ordem_apresenta number(8,0) DEFAULT 0 NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_opcao_resp_pergunta_pedido OWNER TO oasis;

--
-- TOC entry 5129 (class 0 OID 0)
-- Dependencies: 177
-- Name: TABLE a_opcao_resp_pergunta_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON TABLE a_opcao_resp_pergunta_pedido IS 'Essa tabela armazena os dados de associação das
tabelas b_pergunta_pedido e b_resposta_pedido.';


--
-- TOC entry 5130 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN a_opcao_resp_pergunta_pedido.cd_pergunta_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_opcao_resp_pergunta_pedido.cd_pergunta_pedido IS 'Código sequencial identificador da pergunta do pedido';


--
-- TOC entry 5131 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN a_opcao_resp_pergunta_pedido.cd_resposta_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_opcao_resp_pergunta_pedido.cd_resposta_pedido IS 'Código sequencial identificador da resposta do pedido';


--
-- TOC entry 5132 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN a_opcao_resp_pergunta_pedido.st_resposta_texto; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_opcao_resp_pergunta_pedido.st_resposta_texto IS 'N => Opção
S => Frase
T => Texto
U => Arquivo';


--
-- TOC entry 5133 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN a_opcao_resp_pergunta_pedido.ni_ordem_apresenta; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_opcao_resp_pergunta_pedido.ni_ordem_apresenta IS 'Este campo armazena a ordem de apresentação das perguntas do pedido';


--
-- TOC entry 5134 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN a_opcao_resp_pergunta_pedido.id; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_opcao_resp_pergunta_pedido.id IS 'Este campo armazena o código do
profissional que realizou a última
gravação ou atualização.';


--
-- TOC entry 178 (class 1259 OID 16560)
-- Dependencies: 11
-- Name: a_parecer_tecnico_parcela; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_parecer_tecnico_parcela (
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    cd_parcela number NOT NULL,
    cd_item_parecer_tecnico number NOT NULL,
    cd_processamento_parcela number(8,0) NOT NULL,
    st_avaliacao char(2),
    id number(8,0)
);


ALTER TABLE oasis.a_parecer_tecnico_parcela OWNER TO oasis;

--
-- TOC entry 179 (class 1259 OID 16566)
-- Dependencies: 11
-- Name: a_parecer_tecnico_proposta; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_parecer_tecnico_proposta (
    cd_item_parecer_tecnico number NOT NULL,
    cd_proposta number NOT NULL,
    cd_projeto number NOT NULL,
    cd_processamento_proposta number(8,0) NOT NULL,
    st_avaliacao char(2),
    id number(8,0)
);


ALTER TABLE oasis.a_parecer_tecnico_proposta OWNER TO oasis;

--
-- TOC entry 180 (class 1259 OID 16572)
-- Dependencies: 11
-- Name: a_perfil_box_inicio; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_perfil_box_inicio (
    cd_perfil number NOT NULL,
    cd_box_inicio number NOT NULL,
    cd_objeto number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_perfil_box_inicio OWNER TO oasis;

--
-- TOC entry 181 (class 1259 OID 16578)
-- Dependencies: 11
-- Name: a_perfil_menu; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_perfil_menu (
    cd_perfil number NOT NULL,
    cd_menu number NOT NULL,
    cd_objeto number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_perfil_menu OWNER TO oasis;

--
-- TOC entry 182 (class 1259 OID 16584)
-- Dependencies: 11
-- Name: a_perfil_menu_sistema; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_perfil_menu_sistema (
    cd_perfil number NOT NULL,
    cd_menu number NOT NULL,
    st_perfil_menu char(1) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_perfil_menu_sistema OWNER TO oasis;

--
-- TOC entry 5138 (class 0 OID 0)
-- Dependencies: 182
-- Name: COLUMN a_perfil_menu_sistema.st_perfil_menu; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_perfil_menu_sistema.st_perfil_menu IS 'P - Projeto
D - Demanda';


--
-- TOC entry 183 (class 1259 OID 16590)
-- Dependencies: 11
-- Name: a_perfil_prof_papel_prof; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_perfil_prof_papel_prof (
    cd_perfil_profissional number(8,0) NOT NULL,
    cd_papel_profissional number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_perfil_prof_papel_prof OWNER TO oasis;

--
-- TOC entry 184 (class 1259 OID 16593)
-- Dependencies: 4433 11
-- Name: a_pergunta_depende_resp_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_pergunta_depende_resp_pedido (
    cd_pergunta_depende number(8,0) NOT NULL,
    cd_pergunta_pedido number(8,0) NOT NULL,
    cd_resposta_pedido number(8,0) NOT NULL,
    st_tipo_dependencia char(1) DEFAULT 'S' NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_pergunta_depende_resp_pedido OWNER TO oasis;

--
-- TOC entry 5139 (class 0 OID 0)
-- Dependencies: 184
-- Name: TABLE a_pergunta_depende_resp_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON TABLE a_pergunta_depende_resp_pedido IS 'Essa tabela armazena os dados de associação das
tabelas b_pergunta_pedido e b_resposta_pedido com inclusão de pergunta da b_pedido_pergunta que depende.';


--
-- TOC entry 5140 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN a_pergunta_depende_resp_pedido.cd_pergunta_depende; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_pergunta_depende_resp_pedido.cd_pergunta_depende IS 'Código sequencial identificador da perginta de pedido que é dependente';


--
-- TOC entry 5141 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN a_pergunta_depende_resp_pedido.cd_pergunta_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_pergunta_depende_resp_pedido.cd_pergunta_pedido IS 'Código sequencial identificador da pergunta do pedido ';


--
-- TOC entry 5142 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN a_pergunta_depende_resp_pedido.cd_resposta_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_pergunta_depende_resp_pedido.cd_resposta_pedido IS 'Código sequencial identificador da resposta do pedido ';


--
-- TOC entry 5143 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN a_pergunta_depende_resp_pedido.st_tipo_dependencia; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_pergunta_depende_resp_pedido.st_tipo_dependencia IS 'Este campo armazena a indicação do tipo de dependencia';


--
-- TOC entry 5144 (class 0 OID 0)
-- Dependencies: 184
-- Name: COLUMN a_pergunta_depende_resp_pedido.id; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_pergunta_depende_resp_pedido.id IS 'Este campo armazena o código do
profissional que realizou a última
gravação ou atualização.';


--
-- TOC entry 185 (class 1259 OID 16597)
-- Dependencies: 11
-- Name: a_planejamento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_planejamento (
    cd_etapa number NOT NULL,
    cd_atividade number NOT NULL,
    cd_projeto number NOT NULL,
    cd_modulo number NOT NULL,
    dt_inicio_atividade date,
    dt_fim_atividade date,
    nf_porcentagem_execucao number,
    tx_obs_atividade varchar2,
    id number(8,0)
);


ALTER TABLE oasis.a_planejamento OWNER TO oasis;

--
-- TOC entry 186 (class 1259 OID 16603)
-- Dependencies: 11
-- Name: a_profissional_conhecimento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_profissional_conhecimento (
    cd_profissional number NOT NULL,
    cd_tipo_conhecimento number NOT NULL,
    cd_conhecimento number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_profissional_conhecimento OWNER TO oasis;

--
-- TOC entry 187 (class 1259 OID 16609)
-- Dependencies: 11
-- Name: a_profissional_mensageria; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_profissional_mensageria (
    cd_profissional number NOT NULL,
    cd_mensageria number(8,0) NOT NULL,
    dt_leitura_mensagem timestamp,
    id number(8,0)
);


ALTER TABLE oasis.a_profissional_mensageria OWNER TO oasis;

--
-- TOC entry 188 (class 1259 OID 16615)
-- Dependencies: 11
-- Name: a_profissional_menu; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_profissional_menu (
    cd_menu number NOT NULL,
    cd_profissional number NOT NULL,
    cd_objeto number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_profissional_menu OWNER TO oasis;

--
-- TOC entry 189 (class 1259 OID 16621)
-- Dependencies: 11
-- Name: a_profissional_objeto_contrato; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_profissional_objeto_contrato (
    cd_profissional number NOT NULL,
    cd_objeto number NOT NULL,
    st_recebe_email char(1),
    tx_posicao_box_inicio varchar2(4000),
    st_objeto_padrao char(1),
    cd_perfil_profissional number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.a_profissional_objeto_contrato OWNER TO oasis;

--
-- TOC entry 190 (class 1259 OID 16627)
-- Dependencies: 11
-- Name: a_profissional_produto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_profissional_produto (
    cd_profissional number(8,0) NOT NULL,
    cd_produto_parcela number(8,0) NOT NULL,
    cd_proposta number(8,0) NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    cd_parcela number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_profissional_produto OWNER TO oasis;

--
-- TOC entry 191 (class 1259 OID 16630)
-- Dependencies: 11
-- Name: a_profissional_projeto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_profissional_projeto (
    cd_profissional number NOT NULL,
    cd_projeto number NOT NULL,
    cd_papel_profissional number(8,0) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_profissional_projeto OWNER TO oasis;

--
-- TOC entry 192 (class 1259 OID 16636)
-- Dependencies: 11
-- Name: a_proposta_definicao_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_proposta_definicao_metrica (
    cd_projeto number(8,0) NOT NULL,
    cd_proposta number(8,0) NOT NULL,
    cd_definicao_metrica number(8,0) NOT NULL,
    ni_horas_proposta_metrica number(8,1),
    tx_justificativa_metrica varchar2(4000),
    id number(8,0)
);


ALTER TABLE oasis.a_proposta_definicao_metrica OWNER TO oasis;

--
-- TOC entry 193 (class 1259 OID 16642)
-- Dependencies: 11
-- Name: a_proposta_modulo; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_proposta_modulo (
    cd_projeto number NOT NULL,
    cd_modulo number NOT NULL,
    cd_proposta number NOT NULL,
    st_criacao_modulo char(1),
    id number(8,0)
);


ALTER TABLE oasis.a_proposta_modulo OWNER TO oasis;

--
-- TOC entry 194 (class 1259 OID 16648)
-- Dependencies: 11
-- Name: a_proposta_sub_item_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_proposta_sub_item_metrica (
    cd_projeto number(8,0) NOT NULL,
    cd_proposta number(8,0) NOT NULL,
    cd_item_metrica number(8,0) NOT NULL,
    cd_definicao_metrica number(8,0) NOT NULL,
    cd_sub_item_metrica number(8,0) NOT NULL,
    ni_valor_sub_item_metrica number(8,1),
    id number(8,0)
);


ALTER TABLE oasis.a_proposta_sub_item_metrica OWNER TO oasis;

--
-- TOC entry 195 (class 1259 OID 16651)
-- Dependencies: 11
-- Name: a_quest_avaliacao_qualidade; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_quest_avaliacao_qualidade (
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    cd_grupo_fator number(8,0) NOT NULL,
    cd_item_grupo_fator number(8,0) NOT NULL,
    st_avaliacao_qualidade char(1),
    id number(8,0)
);


ALTER TABLE oasis.a_quest_avaliacao_qualidade OWNER TO oasis;

--
-- TOC entry 196 (class 1259 OID 16657)
-- Dependencies: 11
-- Name: a_questionario_analise_risco; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_questionario_analise_risco (
    dt_analise_risco timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    cd_proposta number NOT NULL,
    cd_etapa number(8,0) NOT NULL,
    cd_atividade number(8,0) NOT NULL,
    cd_item_risco number NOT NULL,
    cd_questao_analise_risco number NOT NULL,
    st_resposta_analise_risco char(3),
    cd_profissional number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.a_questionario_analise_risco OWNER TO oasis;

--
-- TOC entry 197 (class 1259 OID 16663)
-- Dependencies: 11
-- Name: a_regra_negocio_requisito; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_regra_negocio_requisito (
    cd_projeto_regra_negocio number(8,0) NOT NULL,
    dt_regra_negocio timestamp NOT NULL,
    cd_regra_negocio number NOT NULL,
    dt_versao_requisito timestamp NOT NULL,
    cd_requisito number NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    dt_inativacao_regra date,
    st_inativo char(1),
    id number(8,0)
);


ALTER TABLE oasis.a_regra_negocio_requisito OWNER TO oasis;

--
-- TOC entry 198 (class 1259 OID 16669)
-- Dependencies: 11
-- Name: a_requisito_caso_de_uso; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_requisito_caso_de_uso (
    cd_projeto number(8,0) NOT NULL,
    dt_versao_requisito timestamp NOT NULL,
    cd_requisito number NOT NULL,
    dt_versao_caso_de_uso timestamp NOT NULL,
    cd_caso_de_uso number NOT NULL,
    cd_modulo number(8,0) NOT NULL,
    dt_inativacao_caso_de_uso date,
    st_inativo char(1),
    id number(8,0)
);


ALTER TABLE oasis.a_requisito_caso_de_uso OWNER TO oasis;

--
-- TOC entry 199 (class 1259 OID 16675)
-- Dependencies: 11
-- Name: a_requisito_dependente; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_requisito_dependente (
    cd_requisito_ascendente number NOT NULL,
    dt_versao_requisito_ascendente timestamp NOT NULL,
    cd_projeto_ascendente number(8,0) NOT NULL,
    cd_requisito number NOT NULL,
    dt_versao_requisito timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    st_inativo char(1),
    dt_inativacao_requisito date,
    id number(8,0)
);


ALTER TABLE oasis.a_requisito_dependente OWNER TO oasis;

--
-- TOC entry 200 (class 1259 OID 16681)
-- Dependencies: 11
-- Name: a_reuniao_geral_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_reuniao_geral_profissional (
    cd_objeto number NOT NULL,
    cd_reuniao_geral number NOT NULL,
    cd_profissional number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_reuniao_geral_profissional OWNER TO oasis;

--
-- TOC entry 201 (class 1259 OID 16687)
-- Dependencies: 11
-- Name: a_reuniao_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_reuniao_profissional (
    cd_projeto number NOT NULL,
    cd_reuniao number NOT NULL,
    cd_profissional number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.a_reuniao_profissional OWNER TO oasis;

--
-- TOC entry 202 (class 1259 OID 16693)
-- Dependencies: 11
-- Name: a_rotina_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_rotina_profissional (
    cd_objeto number(8,0) NOT NULL,
    cd_profissional number(8,0) NOT NULL,
    cd_rotina number(8,0) NOT NULL,
    st_inativa_rotina_profissional char(1),
    id number(8,0)
);


ALTER TABLE oasis.a_rotina_profissional OWNER TO oasis;

--
-- TOC entry 5154 (class 0 OID 0)
-- Dependencies: 202
-- Name: TABLE a_rotina_profissional; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON TABLE a_rotina_profissional IS 'Essa tabela armazena os dados de associação das
tabelas s_profissional e b_rotina.';


--
-- TOC entry 5155 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN a_rotina_profissional.cd_objeto; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_rotina_profissional.cd_objeto IS 'Código sequencial identificador do objeto do contrato';


--
-- TOC entry 5156 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN a_rotina_profissional.cd_profissional; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_rotina_profissional.cd_profissional IS 'Código sequencial identificador do profissional';


--
-- TOC entry 5157 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN a_rotina_profissional.cd_rotina; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_rotina_profissional.cd_rotina IS 'Código sequencial identificador da
rotina';


--
-- TOC entry 5158 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN a_rotina_profissional.st_inativa_rotina_profissional; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_rotina_profissional.st_inativa_rotina_profissional IS 'Este campo armazena a indicação de
inativação da rotina para o profissional';


--
-- TOC entry 5159 (class 0 OID 0)
-- Dependencies: 202
-- Name: COLUMN a_rotina_profissional.id; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_rotina_profissional.id IS 'Este campo armazena o código do
profissional que realizou a última
gravação ou atualização.';


--
-- TOC entry 203 (class 1259 OID 16696)
-- Dependencies: 11
-- Name: a_solicitacao_resposta_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_solicitacao_resposta_pedido (
    cd_solicitacao_pedido number(8,0) NOT NULL,
    cd_pergunta_pedido number(8,0) NOT NULL,
    cd_resposta_pedido number(8,0) NOT NULL,
    tx_descricao_resposta text,
    id number(8,0)
);


ALTER TABLE oasis.a_solicitacao_resposta_pedido OWNER TO oasis;

--
-- TOC entry 5160 (class 0 OID 0)
-- Dependencies: 203
-- Name: TABLE a_solicitacao_resposta_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON TABLE a_solicitacao_resposta_pedido IS 'Essa tabela armazena os dados de associação das
tabelas b_pergunta_pedido, b_resposta_pedido e s_solicitacao_pedido';


--
-- TOC entry 5161 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN a_solicitacao_resposta_pedido.cd_solicitacao_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_solicitacao_resposta_pedido.cd_solicitacao_pedido IS 'Código sequencial identificador da
solicitacao de pedido';


--
-- TOC entry 5162 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN a_solicitacao_resposta_pedido.cd_pergunta_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_solicitacao_resposta_pedido.cd_pergunta_pedido IS 'Código sequencial identificador da
pergunta do pedido';


--
-- TOC entry 5163 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN a_solicitacao_resposta_pedido.cd_resposta_pedido; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_solicitacao_resposta_pedido.cd_resposta_pedido IS 'Código sequencial identificador da
resposta do pedido';


--
-- TOC entry 5164 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN a_solicitacao_resposta_pedido.tx_descricao_resposta; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_solicitacao_resposta_pedido.tx_descricao_resposta IS 'Este campo armazena o texto de descrição da resposta.';


--
-- TOC entry 5165 (class 0 OID 0)
-- Dependencies: 203
-- Name: COLUMN a_solicitacao_resposta_pedido.id; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN a_solicitacao_resposta_pedido.id IS 'Este campo armazena o código do
profissional que realizou a última
gravação ou atualização.';


--
-- TOC entry 204 (class 1259 OID 16702)
-- Dependencies: 11
-- Name: a_treinamento_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE a_treinamento_profissional (
    cd_treinamento number(8,0) NOT NULL,
    cd_profissional number NOT NULL,
    dt_treinamento_profissional date,
    id number(8,0)
);


ALTER TABLE oasis.a_treinamento_profissional OWNER TO oasis;

--
-- TOC entry 205 (class 1259 OID 16708)
-- Dependencies: 11
-- Name: b_area_atuacao_ti; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_area_atuacao_ti (
    cd_area_atuacao_ti number(8,0) NOT NULL,
    tx_area_atuacao_ti varchar2(200),
    id number(8,0)
);


ALTER TABLE oasis.b_area_atuacao_ti OWNER TO oasis;

--
-- TOC entry 5166 (class 0 OID 0)
-- Dependencies: 205
-- Name: COLUMN b_area_atuacao_ti.tx_area_atuacao_ti; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_area_atuacao_ti.tx_area_atuacao_ti IS 'Este campo armazena a descrição da área de atuação de TI';


--
-- TOC entry 206 (class 1259 OID 16711)
-- Dependencies: 11
-- Name: b_area_conhecimento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_area_conhecimento (
    cd_area_conhecimento number NOT NULL,
    tx_area_conhecimento varchar2,
    id number(8,0)
);


ALTER TABLE oasis.b_area_conhecimento OWNER TO oasis;

--
-- TOC entry 5167 (class 0 OID 0)
-- Dependencies: 206
-- Name: COLUMN b_area_conhecimento.tx_area_conhecimento; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_area_conhecimento.tx_area_conhecimento IS 'Este campo armazena a descrição da área de conhecimento';


--
-- TOC entry 207 (class 1259 OID 16717)
-- Dependencies: 11
-- Name: b_atividade; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_atividade (
    cd_atividade number NOT NULL,
    cd_etapa number NOT NULL,
    tx_atividade varchar2,
    ni_ordem_atividade number(4,0),
    tx_descricao_atividade varchar2(4000),
    id number(8,0),
    st_atividade_inativa char(1)
);


ALTER TABLE oasis.b_atividade OWNER TO oasis;

--
-- TOC entry 5169 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN b_atividade.tx_atividade; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_atividade.tx_atividade IS 'Este campo armazena a descrição da atividade';


--
-- TOC entry 208 (class 1259 OID 16723)
-- Dependencies: 11
-- Name: b_box_inicio; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_box_inicio (
    cd_box_inicio number NOT NULL,
    tx_box_inicio varchar2(100) NOT NULL,
    st_tipo_box_inicio char(1) NOT NULL,
    tx_titulo_box_inicio varchar2(100) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.b_box_inicio OWNER TO oasis;

--
-- TOC entry 5171 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN b_box_inicio.tx_box_inicio; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_box_inicio.tx_box_inicio IS 'Este campo armazena a descrição da box de início';


--
-- TOC entry 5172 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN b_box_inicio.st_tipo_box_inicio; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_box_inicio.st_tipo_box_inicio IS 'P = Projeto, D = Demanda, A = Ambos';


--
-- TOC entry 5173 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN b_box_inicio.tx_titulo_box_inicio; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_box_inicio.tx_titulo_box_inicio IS 'Este campo armazena o titulo da box de início';


--
-- TOC entry 5174 (class 0 OID 0)
-- Dependencies: 208
-- Name: COLUMN b_box_inicio.id; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_box_inicio.id IS 'Este campo armazena o código do
profissional que realizou a última
gravação ou atualização.';


--
-- TOC entry 209 (class 1259 OID 16729)
-- Dependencies: 11
-- Name: b_condicao_sub_item_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_condicao_sub_item_metrica (
    cd_condicao_sub_item_metrica number(8,0) NOT NULL,
    cd_item_metrica number(8,0) NOT NULL,
    cd_definicao_metrica number(8,0) NOT NULL,
    cd_sub_item_metrica number(8,0) NOT NULL,
    tx_condicao_sub_item_metrica varchar2(100),
    ni_valor_condicao_satisfeita number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.b_condicao_sub_item_metrica OWNER TO oasis;

--
-- TOC entry 210 (class 1259 OID 16732)
-- Dependencies: 11
-- Name: b_conhecimento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_conhecimento (
    cd_conhecimento number NOT NULL,
    cd_tipo_conhecimento number NOT NULL,
    tx_conhecimento varchar2,
    st_padrao char(1),
    id number(8,0)
);


ALTER TABLE oasis.b_conhecimento OWNER TO oasis;

--
-- TOC entry 211 (class 1259 OID 16738)
-- Dependencies: 11
-- Name: b_conjunto_medida; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_conjunto_medida (
    cd_conjunto_medida number(8,0) NOT NULL,
    tx_conjunto_medida varchar2(500),
    id number(8,0)
);


ALTER TABLE oasis.b_conjunto_medida OWNER TO oasis;

--
-- TOC entry 212 (class 1259 OID 16744)
-- Dependencies: 11
-- Name: b_definicao_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_definicao_metrica (
    cd_definicao_metrica number(8,0) NOT NULL,
    tx_nome_metrica varchar2,
    tx_sigla_metrica varchar2,
    tx_descricao_metrica varchar2,
    tx_formula_metrica varchar2,
    st_justificativa_metrica char(1),
    id number(8,0),
    tx_sigla_unidade_metrica varchar2(10),
    tx_unidade_metrica varchar2(100)
);


ALTER TABLE oasis.b_definicao_metrica OWNER TO oasis;

--
-- TOC entry 213 (class 1259 OID 16750)
-- Dependencies: 11
-- Name: b_etapa; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_etapa (
    cd_etapa number NOT NULL,
    tx_etapa varchar2,
    ni_ordem_etapa number(4,0),
    tx_descricao_etapa varchar2(4000),
    id number(8,0),
    cd_area_atuacao_ti number(8,0),
    st_etapa_inativa char(1)
);


ALTER TABLE oasis.b_etapa OWNER TO oasis;

--
-- TOC entry 214 (class 1259 OID 16756)
-- Dependencies: 11
-- Name: b_evento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_evento (
    cd_evento number(8,0) NOT NULL,
    tx_evento varchar2(200),
    id number(8,0)
);


ALTER TABLE oasis.b_evento OWNER TO oasis;

--
-- TOC entry 215 (class 1259 OID 16759)
-- Dependencies: 11
-- Name: b_funcionalidade; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_funcionalidade (
    cd_funcionalidade number(8,0) NOT NULL,
    tx_codigo_funcionalidade varchar2(20),
    tx_funcionalidade varchar2(200),
    st_funcionalidade char(1),
    id number(8,0)
);


ALTER TABLE oasis.b_funcionalidade OWNER TO oasis;

--
-- TOC entry 216 (class 1259 OID 16762)
-- Dependencies: 11
-- Name: b_grupo_fator; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_grupo_fator (
    cd_grupo_fator number(8,0) NOT NULL,
    tx_grupo_fator varchar2(100),
    ni_peso_grupo_fator number(8,0) NOT NULL,
    ni_ordem_grupo_fator number NOT NULL,
    id number(8,0),
    ni_indice_grupo_fator number(8,0) NOT NULL
);


ALTER TABLE oasis.b_grupo_fator OWNER TO oasis;

--
-- TOC entry 217 (class 1259 OID 16768)
-- Dependencies: 11
-- Name: b_item_controle_baseline; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_item_controle_baseline (
    cd_item_controle_baseline number(8,0) NOT NULL,
    tx_item_controle_baseline varchar2(500),
    id number(8,0)
);


ALTER TABLE oasis.b_item_controle_baseline OWNER TO oasis;

--
-- TOC entry 218 (class 1259 OID 16774)
-- Dependencies: 11
-- Name: b_item_grupo_fator; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_item_grupo_fator (
    cd_item_grupo_fator number(8,0) NOT NULL,
    cd_grupo_fator number(8,0) NOT NULL,
    tx_item_grupo_fator varchar2(300),
    ni_ordem_item_grupo_fator number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.b_item_grupo_fator OWNER TO oasis;

--
-- TOC entry 219 (class 1259 OID 16780)
-- Dependencies: 11
-- Name: b_item_inventario; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_item_inventario (
    cd_item_inventario number(8,0) NOT NULL,
    cd_area_atuacao_ti number(8,0) NOT NULL,
    tx_item_inventario varchar2(100),
    id number(8,0)
);


ALTER TABLE oasis.b_item_inventario OWNER TO oasis;

--
-- TOC entry 220 (class 1259 OID 16783)
-- Dependencies: 11
-- Name: b_item_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_item_metrica (
    cd_item_metrica number(8,0) NOT NULL,
    cd_definicao_metrica number(8,0) NOT NULL,
    tx_item_metrica varchar2,
    tx_variavel_item_metrica varchar2,
    ni_ordem_item_metrica number(8,0),
    tx_formula_item_metrica varchar2(500),
    st_interno_item_metrica char(1),
    id number(8,0)
);


ALTER TABLE oasis.b_item_metrica OWNER TO oasis;

--
-- TOC entry 221 (class 1259 OID 16789)
-- Dependencies: 11
-- Name: b_item_parecer_tecnico; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_item_parecer_tecnico (
    cd_item_parecer_tecnico number NOT NULL,
    tx_item_parecer_tecnico varchar2,
    st_proposta char(1),
    st_parcela char(1),
    st_viagem char(1),
    id number(8,0),
    tx_descricao varchar2(4000)
);


ALTER TABLE oasis.b_item_parecer_tecnico OWNER TO oasis;

--
-- TOC entry 222 (class 1259 OID 16795)
-- Dependencies: 11
-- Name: b_item_risco; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_item_risco (
    cd_item_risco number NOT NULL,
    cd_etapa number(8,0) NOT NULL,
    cd_atividade number(8,0) NOT NULL,
    tx_item_risco varchar2,
    tx_descricao_item_risco varchar2,
    id number(8,0)
);


ALTER TABLE oasis.b_item_risco OWNER TO oasis;

--
-- TOC entry 223 (class 1259 OID 16801)
-- Dependencies: 4434 4435 11
-- Name: b_item_teste; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_item_teste (
    cd_item_teste number(8,0) NOT NULL,
    tx_item_teste varchar2(1000),
    st_item_teste char(1),
    st_obrigatorio char(1),
    st_tipo_item_teste char(1),
    ni_ordem_item_teste number,
    id number(8,0),
    CONSTRAINT ck_oasis_004 CHECK ((st_tipo_item_teste IN ('C', 'R', 'N', 'I'))),
    CONSTRAINT ck_oasis_005 CHECK ((st_item_teste IN ('I', 'A')))
);


ALTER TABLE oasis.b_item_teste OWNER TO oasis;

--
-- TOC entry 224 (class 1259 OID 16809)
-- Dependencies: 11
-- Name: b_medida; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_medida (
    cd_medida number NOT NULL,
    tx_medida varchar2,
    tx_objetivo_medida varchar2(4000),
    id number(8,0)
);


ALTER TABLE oasis.b_medida OWNER TO oasis;

--
-- TOC entry 225 (class 1259 OID 16815)
-- Dependencies: 11
-- Name: b_menu; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_menu (
    cd_menu number NOT NULL,
    cd_menu_pai number,
    tx_menu varchar2,
    ni_nivel_menu number,
    tx_pagina varchar2,
    st_menu char(1),
    tx_modulo varchar2(50),
    id number(8,0)
);


ALTER TABLE oasis.b_menu OWNER TO oasis;

--
-- TOC entry 226 (class 1259 OID 16821)
-- Dependencies: 11
-- Name: b_msg_email; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_msg_email (
    cd_msg_email number(8,0) NOT NULL,
    cd_menu number(8,0) NOT NULL,
    tx_metodo_msg_email varchar2(300),
    tx_msg_email varchar2(1000),
    st_msg_email char(1),
    tx_assunto_msg_email varchar2(200)
);


ALTER TABLE oasis.b_msg_email OWNER TO oasis;

--
-- TOC entry 227 (class 1259 OID 16827)
-- Dependencies: 11
-- Name: b_nivel_servico; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_nivel_servico (
    cd_nivel_servico number(8,0) NOT NULL,
    cd_objeto number(8,0) NOT NULL,
    tx_nivel_servico varchar2,
    st_nivel_servico char(1),
    ni_horas_prazo_execucao number(8,1),
    id number(8,0)
);


ALTER TABLE oasis.b_nivel_servico OWNER TO oasis;

--
-- TOC entry 228 (class 1259 OID 16833)
-- Dependencies: 11
-- Name: b_papel_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_papel_profissional (
    cd_papel_profissional number(8,0) NOT NULL,
    tx_papel_profissional varchar2(200),
    id number(8,0),
    cd_area_atuacao_ti number(8,0)
);


ALTER TABLE oasis.b_papel_profissional OWNER TO oasis;

--
-- TOC entry 229 (class 1259 OID 16836)
-- Dependencies: 11
-- Name: b_penalidade; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_penalidade (
    cd_penalidade number NOT NULL,
    cd_contrato number NOT NULL,
    tx_penalidade varchar2,
    tx_abreviacao_penalidade varchar2,
    ni_valor_penalidade number(8,2),
    ni_penalidade number,
    st_ocorrencia char(1),
    id number(8,0)
);


ALTER TABLE oasis.b_penalidade OWNER TO oasis;

--
-- TOC entry 230 (class 1259 OID 16842)
-- Dependencies: 11
-- Name: b_perfil; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_perfil (
    cd_perfil number NOT NULL,
    tx_perfil varchar2,
    id number(8,0),
    st_tipo_perfil char(1),
    st_tipo_atuacao char(1)
);


ALTER TABLE oasis.b_perfil OWNER TO oasis;

--
-- TOC entry 5185 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN b_perfil.st_tipo_perfil; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_perfil.st_tipo_perfil IS 'Se o perfil é do (O)rgão ou (E)mpresa';


--
-- TOC entry 5186 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN b_perfil.st_tipo_atuacao; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_perfil.st_tipo_atuacao IS 'Se a atuação é - (C)oordenação, (F)iscal TI, (P)reposto ou Gerente, (A)nalista e (T)écnico';


--
-- TOC entry 231 (class 1259 OID 16848)
-- Dependencies: 11
-- Name: b_perfil_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_perfil_profissional (
    cd_perfil_profissional number(8,0) NOT NULL,
    tx_perfil_profissional varchar2(200),
    id number(8,0),
    cd_area_atuacao_ti number(8,0)
);


ALTER TABLE oasis.b_perfil_profissional OWNER TO oasis;

--
-- TOC entry 232 (class 1259 OID 16851)
-- Dependencies: 4436 4437 11
-- Name: b_pergunta_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_pergunta_pedido (
    cd_pergunta_pedido number(8,0) NOT NULL,
    tx_titulo_pergunta varchar2(200) NOT NULL,
    st_multipla_resposta char(1) DEFAULT 'N' NOT NULL,
    st_obriga_resposta char(1) DEFAULT 'N' NOT NULL,
    tx_ajuda_pergunta varchar2,
    id number(8,0)
);


ALTER TABLE oasis.b_pergunta_pedido OWNER TO oasis;

--
-- TOC entry 233 (class 1259 OID 16859)
-- Dependencies: 11
-- Name: b_questao_analise_risco; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_questao_analise_risco (
    cd_questao_analise_risco number NOT NULL,
    cd_atividade number(8,0) NOT NULL,
    cd_etapa number(8,0) NOT NULL,
    cd_item_risco number NOT NULL,
    tx_questao_analise_risco varchar2,
    tx_obj_questao_analise_risco varchar2,
    ni_peso_questao_analise_risco number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.b_questao_analise_risco OWNER TO oasis;

--
-- TOC entry 234 (class 1259 OID 16865)
-- Dependencies: 11
-- Name: b_relacao_contratual; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_relacao_contratual (
    cd_relacao_contratual number NOT NULL,
    tx_relacao_contratual varchar2,
    id number(8,0)
);


ALTER TABLE oasis.b_relacao_contratual OWNER TO oasis;

--
-- TOC entry 235 (class 1259 OID 16871)
-- Dependencies: 11
-- Name: b_resposta_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_resposta_pedido (
    cd_resposta_pedido number(8,0) NOT NULL,
    tx_titulo_resposta varchar2(150) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.b_resposta_pedido OWNER TO oasis;

--
-- TOC entry 236 (class 1259 OID 16874)
-- Dependencies: 11
-- Name: b_rotina; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_rotina (
    cd_rotina number(8,0) NOT NULL,
    cd_area_atuacao_ti number(8,0) NOT NULL,
    tx_rotina varchar2(200),
    tx_hora_inicio_rotina varchar2(8),
    st_periodicidade_rotina char(1),
    ni_prazo_execucao_rotina number(8,0),
    id number(8,0),
    ni_dia_semana_rotina number(1,0),
    ni_dia_mes_rotina number(2,0),
    st_rotina_inativa char(1)
);


ALTER TABLE oasis.b_rotina OWNER TO oasis;

--
-- TOC entry 237 (class 1259 OID 16877)
-- Dependencies: 11
-- Name: b_status; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_status (
    cd_status number NOT NULL,
    tx_status varchar2,
    id number(8,0)
);


ALTER TABLE oasis.b_status OWNER TO oasis;

--
-- TOC entry 238 (class 1259 OID 16883)
-- Dependencies: 11
-- Name: b_status_atendimento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_status_atendimento (
    cd_status_atendimento number(8,0) NOT NULL,
    tx_status_atendimento varchar2(100),
    tx_rgb_status_atendimento varchar2(8),
    st_status_atendimento varchar2(1),
    ni_percent_tempo_resposta_ini number(3,0),
    ni_percent_tempo_resposta_fim number(3,0),
    id number(8,0)
);


ALTER TABLE oasis.b_status_atendimento OWNER TO oasis;

--
-- TOC entry 239 (class 1259 OID 16886)
-- Dependencies: 11
-- Name: b_sub_item_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_sub_item_metrica (
    cd_sub_item_metrica number(8,0) NOT NULL,
    cd_definicao_metrica number(8,0) NOT NULL,
    cd_item_metrica number(8,0) NOT NULL,
    tx_sub_item_metrica varchar2,
    tx_variavel_sub_item_metrica varchar2,
    st_interno char(1),
    ni_ordem_sub_item_metrica number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.b_sub_item_metrica OWNER TO oasis;

--
-- TOC entry 240 (class 1259 OID 16892)
-- Dependencies: 11
-- Name: b_subitem_inv_descri; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_subitem_inv_descri (
    cd_item_inventario number(8,0) NOT NULL,
    cd_subitem_inventario number(8,0) NOT NULL,
    cd_subitem_inv_descri number(8,0) NOT NULL,
    tx_subitem_inv_descri varchar2(100),
    id number(8,0),
    ni_ordem pls_integer
);


ALTER TABLE oasis.b_subitem_inv_descri OWNER TO oasis;

--
-- TOC entry 241 (class 1259 OID 16895)
-- Dependencies: 11
-- Name: b_subitem_inventario; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_subitem_inventario (
    cd_item_inventario number(8,0) NOT NULL,
    cd_subitem_inventario number(8,0) NOT NULL,
    tx_subitem_inventario varchar2(100),
    id number(8,0),
    st_info_chamado_tecnico char(1)
);


ALTER TABLE oasis.b_subitem_inventario OWNER TO oasis;

--
-- TOC entry 242 (class 1259 OID 16898)
-- Dependencies: 11
-- Name: b_tipo_conhecimento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_tipo_conhecimento (
    cd_tipo_conhecimento number NOT NULL,
    tx_tipo_conhecimento varchar2,
    id number(8,0),
    st_para_profissionais char(1)
);


ALTER TABLE oasis.b_tipo_conhecimento OWNER TO oasis;

--
-- TOC entry 243 (class 1259 OID 16904)
-- Dependencies: 11
-- Name: b_tipo_dado_tecnico; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_tipo_dado_tecnico (
    cd_tipo_dado_tecnico number NOT NULL,
    tx_tipo_dado_tecnico varchar2,
    id number(8,0)
);


ALTER TABLE oasis.b_tipo_dado_tecnico OWNER TO oasis;

--
-- TOC entry 244 (class 1259 OID 16910)
-- Dependencies: 11
-- Name: b_tipo_documentacao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_tipo_documentacao (
    cd_tipo_documentacao number NOT NULL,
    tx_tipo_documentacao varchar2,
    tx_extensao_documentacao varchar2,
    st_classificacao char(1),
    id number(8,0)
);


ALTER TABLE oasis.b_tipo_documentacao OWNER TO oasis;

--
-- TOC entry 5192 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN b_tipo_documentacao.st_classificacao; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN b_tipo_documentacao.st_classificacao IS 'P = Projeto
R = Profissional
T = Itens de Teste
C = Controle';


--
-- TOC entry 245 (class 1259 OID 16916)
-- Dependencies: 11
-- Name: b_tipo_produto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_tipo_produto (
    cd_tipo_produto number NOT NULL,
    tx_tipo_produto varchar2,
    ni_ordem_tipo_produto number(4,0),
    cd_definicao_metrica number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.b_tipo_produto OWNER TO oasis;

--
-- TOC entry 246 (class 1259 OID 16922)
-- Dependencies: 11
-- Name: b_treinamento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_treinamento (
    cd_treinamento number(8,0) NOT NULL,
    tx_treinamento varchar2(500),
    tx_obs_treinamento varchar2(4000),
    id number(8,0)
);


ALTER TABLE oasis.b_treinamento OWNER TO oasis;

--
-- TOC entry 247 (class 1259 OID 16928)
-- Dependencies: 11
-- Name: b_unidade; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE b_unidade (
    cd_unidade number NOT NULL,
    tx_sigla_unidade varchar2,
    id number(8,0)
);


ALTER TABLE oasis.b_unidade OWNER TO oasis;

--
-- TOC entry 248 (class 1259 OID 16934)
-- Dependencies: 11
-- Name: s_acompanhamento_proposta; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_acompanhamento_proposta (
    cd_acompanhamento_proposta number(8,0) NOT NULL,
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    tx_acompanhamento_proposta varchar2(4000),
    st_restrito char(1),
    dt_acompanhamento_proposta timestamp,
    id number(8,0)
);


ALTER TABLE oasis.s_acompanhamento_proposta OWNER TO oasis;

--
-- TOC entry 249 (class 1259 OID 16940)
-- Dependencies: 11
-- Name: s_agenda_plano_implantacao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_agenda_plano_implantacao (
    dt_agenda_plano_implantacao timestamp NOT NULL,
    cd_proposta number NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    tx_agenda_plano_implantacao varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_agenda_plano_implantacao OWNER TO oasis;

--
-- TOC entry 250 (class 1259 OID 16946)
-- Dependencies: 11
-- Name: s_analise_execucao_projeto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_analise_execucao_projeto (
    dt_analise_execucao_projeto timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    tx_resultado_analise_execucao varchar2,
    tx_decisao_analise_execucao varchar2,
    dt_decisao_analise_execucao date,
    st_fecha_analise_execucao_proj char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_analise_execucao_projeto OWNER TO oasis;

--
-- TOC entry 251 (class 1259 OID 16952)
-- Dependencies: 11
-- Name: s_analise_matriz_rastreab; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_analise_matriz_rastreab (
    cd_analise_matriz_rastreab number(8,0) NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    st_matriz_rastreabilidade char(2) NOT NULL,
    dt_analise_matriz_rastreab date NOT NULL,
    tx_analise_matriz_rastreab varchar2,
    st_fechamento char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_analise_matriz_rastreab OWNER TO oasis;

--
-- TOC entry 5197 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN s_analise_matriz_rastreab.st_matriz_rastreabilidade; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN s_analise_matriz_rastreab.st_matriz_rastreabilidade IS 'RR-Requisito X Requisito;RN-Requisito X Regra de Negócio;RC-Requisito X Caso de Uso';


--
-- TOC entry 252 (class 1259 OID 16958)
-- Dependencies: 4438 11
-- Name: s_analise_medicao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_analise_medicao (
    dt_analise_medicao timestamp NOT NULL,
    cd_medicao number NOT NULL,
    cd_box_inicio number NOT NULL,
    cd_profissional number NOT NULL,
    tx_resultado_analise_medicao varchar2,
    tx_dados_medicao varchar2,
    tx_decisao varchar2,
    dt_decisao date,
    st_decisao_executada char(1),
    dt_decisao_executada date,
    tx_obs_decisao_executada varchar2,
    id number(8,0),
    CONSTRAINT ck_oasis_002 CHECK ((st_decisao_executada IN ('E', 'N')))
);


ALTER TABLE oasis.s_analise_medicao OWNER TO oasis;

--
-- TOC entry 5198 (class 0 OID 0)
-- Dependencies: 252
-- Name: COLUMN s_analise_medicao.st_decisao_executada; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN s_analise_medicao.st_decisao_executada IS 'valores possíveis:
E=Executada;
N=Não Executada;';


--
-- TOC entry 253 (class 1259 OID 16965)
-- Dependencies: 11
-- Name: s_analise_risco; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_analise_risco (
    dt_analise_risco timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    cd_proposta number NOT NULL,
    cd_etapa number(8,0) NOT NULL,
    cd_atividade number(8,0) NOT NULL,
    cd_item_risco number NOT NULL,
    st_fator_risco char(3),
    st_impacto_projeto_risco char(3),
    st_impacto_tecnico_risco char(3),
    st_impacto_custo_risco char(3),
    st_impacto_cronograma_risco char(3),
    tx_analise_risco varchar2,
    tx_acao_analise_risco varchar2,
    st_fechamento_risco char(1),
    cd_profissional number(8,0),
    cd_profissional_responsavel number(8,0),
    dt_limite_acao date,
    st_acao char(1),
    tx_observacao_acao varchar2,
    dt_fechamento_risco date,
    tx_cor_impacto_cronog_risco char(20),
    tx_cor_impacto_custo_risco char(20),
    tx_cor_impacto_projeto_risco char(20),
    tx_cor_impacto_tecnico_risco char(20),
    id number(8,0),
    st_nao_aplica_risco char(1),
    tx_mitigacao_risco varchar2
);


ALTER TABLE oasis.s_analise_risco OWNER TO oasis;

--
-- TOC entry 254 (class 1259 OID 16971)
-- Dependencies: 11
-- Name: s_arquivo_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_arquivo_pedido (
    cd_arquivo_pedido number(8,0) NOT NULL,
    cd_pergunta_pedido number(8,0) NOT NULL,
    cd_resposta_pedido number(8,0) NOT NULL,
    cd_solicitacao_pedido number(8,0) NOT NULL,
    tx_titulo_arquivo varchar2(100) NOT NULL,
    tx_nome_arquivo varchar2(20) NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.s_arquivo_pedido OWNER TO oasis;

--
-- TOC entry 255 (class 1259 OID 16974)
-- Dependencies: 11
-- Name: s_ator; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_ator (
    cd_ator number NOT NULL,
    cd_projeto number NOT NULL,
    tx_ator varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_ator OWNER TO oasis;

--
-- TOC entry 256 (class 1259 OID 16980)
-- Dependencies: 11
-- Name: s_base_conhecimento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_base_conhecimento (
    cd_base_conhecimento number NOT NULL,
    cd_area_conhecimento number NOT NULL,
    tx_assunto varchar2,
    tx_problema varchar2,
    tx_solucao varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_base_conhecimento OWNER TO oasis;

--
-- TOC entry 257 (class 1259 OID 16986)
-- Dependencies: 11
-- Name: s_baseline; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_baseline (
    dt_baseline timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    st_ativa char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_baseline OWNER TO oasis;

--
-- TOC entry 258 (class 1259 OID 16989)
-- Dependencies: 11
-- Name: s_caso_de_uso; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_caso_de_uso (
    cd_caso_de_uso number NOT NULL,
    cd_projeto number NOT NULL,
    cd_modulo number NOT NULL,
    ni_ordem_caso_de_uso number,
    tx_caso_de_uso varchar2,
    tx_descricao_caso_de_uso varchar2(4000),
    dt_fechamento_caso_de_uso timestamp,
    dt_versao_caso_de_uso timestamp NOT NULL,
    ni_versao_caso_de_uso number(8,0),
    st_fechamento_caso_de_uso char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_caso_de_uso OWNER TO oasis;

--
-- TOC entry 259 (class 1259 OID 16995)
-- Dependencies: 11
-- Name: s_coluna; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_coluna (
    tx_tabela varchar2 NOT NULL,
    tx_coluna varchar2 NOT NULL,
    cd_projeto number NOT NULL,
    tx_descricao varchar2,
    st_chave char(1),
    tx_tabela_referencia varchar2,
    cd_projeto_referencia number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.s_coluna OWNER TO oasis;

--
-- TOC entry 260 (class 1259 OID 17001)
-- Dependencies: 11
-- Name: s_complemento; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_complemento (
    cd_complemento number NOT NULL,
    cd_modulo number NOT NULL,
    cd_projeto number NOT NULL,
    cd_caso_de_uso number NOT NULL,
    tx_complemento varchar2,
    st_complemento char(1),
    ni_ordem_complemento number,
    dt_versao_caso_de_uso timestamp NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.s_complemento OWNER TO oasis;

--
-- TOC entry 261 (class 1259 OID 17007)
-- Dependencies: 11
-- Name: s_condicao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_condicao (
    cd_condicao number NOT NULL,
    cd_modulo number NOT NULL,
    cd_projeto number NOT NULL,
    cd_caso_de_uso number NOT NULL,
    tx_condicao varchar2,
    st_condicao char(1),
    dt_versao_caso_de_uso timestamp NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.s_condicao OWNER TO oasis;

--
-- TOC entry 262 (class 1259 OID 17013)
-- Dependencies: 11
-- Name: s_config_banco_de_dados; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_config_banco_de_dados (
    cd_projeto number NOT NULL,
    tx_adapter varchar2(100),
    tx_host varchar2(100),
    tx_dbname varchar2(100),
    tx_username varchar2(100),
    tx_password varchar2(100),
    tx_schema varchar2(100),
    id number(8,0),
    tx_port varchar2(100)
);


ALTER TABLE oasis.s_config_banco_de_dados OWNER TO oasis;

--
-- TOC entry 263 (class 1259 OID 17019)
-- Dependencies: 11
-- Name: s_contato_empresa; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_contato_empresa (
    cd_contato_empresa number(8,0) NOT NULL,
    cd_empresa number NOT NULL,
    tx_contato_empresa varchar2,
    tx_telefone_contato varchar2,
    tx_email_contato varchar2,
    tx_celular_contato varchar2,
    st_gerente_conta char(1),
    tx_obs_contato varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_contato_empresa OWNER TO oasis;

--
-- TOC entry 264 (class 1259 OID 17025)
-- Dependencies: 11
-- Name: s_contrato; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_contrato (
    cd_contrato number NOT NULL,
    cd_empresa number NOT NULL,
    tx_numero_contrato varchar2,
    dt_inicio_contrato date,
    dt_fim_contrato date,
    st_aditivo char(1),
    tx_cpf_gestor varchar2,
    ni_horas_previstas number,
    tx_objeto varchar2,
    tx_gestor_contrato varchar2,
    tx_fone_gestor_contrato varchar2,
    tx_numero_processo varchar2(20),
    tx_obs_contrato varchar2,
    tx_localizacao_arquivo varchar2,
    tx_co_gestor varchar2,
    tx_cpf_co_gestor varchar2,
    tx_fone_co_gestor_contrato varchar2,
    nf_valor_passagens_diarias number(15,2),
    nf_valor_unitario_diaria number(15,2),
    st_contrato char(1),
    ni_mes_inicial_contrato number(4,0),
    ni_ano_inicial_contrato number(4,0),
    ni_mes_final_contrato number(4,0),
    ni_ano_final_contrato number(4,0),
    ni_qtd_meses_contrato number(4,0),
    nf_valor_unitario_hora number(15,2),
    nf_valor_contrato number(15,2),
    cd_contato_empresa number(8,0),
    id number(8,0),
    cd_definicao_metrica number(8,0)
);


ALTER TABLE oasis.s_contrato OWNER TO oasis;

--
-- TOC entry 265 (class 1259 OID 17031)
-- Dependencies: 11
-- Name: s_custo_contrato_demanda; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_custo_contrato_demanda (
    cd_contrato number NOT NULL,
    ni_mes_custo_contrato_demanda number(8,0) NOT NULL,
    ni_ano_custo_contrato_demanda number(8,0) NOT NULL,
    nf_total_multa number(8,2),
    nf_total_glosa number(8,2),
    nf_total_pago number(8,2),
    id number(8,0)
);


ALTER TABLE oasis.s_custo_contrato_demanda OWNER TO oasis;

--
-- TOC entry 266 (class 1259 OID 17037)
-- Dependencies: 11
-- Name: s_demanda; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_demanda (
    cd_demanda number NOT NULL,
    cd_objeto number,
    ni_ano_solicitacao number,
    ni_solicitacao number,
    dt_demanda timestamp,
    tx_demanda varchar2,
    st_conclusao_demanda char(1),
    dt_conclusao_demanda timestamp,
    tx_solicitante_demanda varchar2(200),
    cd_unidade number(8,0),
    st_fechamento_demanda char(1),
    dt_fechamento_demanda timestamp,
    st_prioridade_demanda char(1),
    id number(8,0),
    cd_status_atendimento number(8,0)
);


ALTER TABLE oasis.s_demanda OWNER TO oasis;

--
-- TOC entry 267 (class 1259 OID 17043)
-- Dependencies: 11
-- Name: s_disponibilidade_servico; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_disponibilidade_servico (
    cd_disponibilidade_servico number(8,0) NOT NULL,
    cd_objeto number(8,0) NOT NULL,
    dt_inicio_analise_disp_servico date,
    dt_fim_analise_disp_servico date,
    tx_analise_disp_servico varchar2,
    ni_indice_disp_servico number(8,2),
    tx_parecer_disp_servico varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_disponibilidade_servico OWNER TO oasis;

--
-- TOC entry 268 (class 1259 OID 17049)
-- Dependencies: 11
-- Name: s_empresa; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_empresa (
    cd_empresa number NOT NULL,
    tx_empresa varchar2,
    tx_cnpj_empresa varchar2,
    tx_endereco_empresa varchar2,
    tx_telefone_empresa varchar2(20),
    tx_email_empresa varchar2(200),
    tx_fax_empresa varchar2(30),
    tx_arquivo_logomarca varchar2(100),
    id number(8,0)
);


ALTER TABLE oasis.s_empresa OWNER TO oasis;

--
-- TOC entry 269 (class 1259 OID 17055)
-- Dependencies: 11
-- Name: s_execucao_rotina; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_execucao_rotina (
    dt_execucao_rotina date NOT NULL,
    cd_profissional number(8,0) NOT NULL,
    cd_objeto number(8,0) NOT NULL,
    cd_rotina number(8,0) NOT NULL,
    st_fechamento_execucao_rotina char(1),
    dt_just_execucao_rotina timestamp,
    tx_just_execucao_rotina varchar2(4000),
    st_historico char(1),
    id number(8,0),
    tx_hora_execucao_rotina varchar2(8)
);


ALTER TABLE oasis.s_execucao_rotina OWNER TO oasis;

--
-- TOC entry 270 (class 1259 OID 17061)
-- Dependencies: 11
-- Name: s_extrato_mensal; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_extrato_mensal (
    ni_mes_extrato number NOT NULL,
    ni_ano_extrato number NOT NULL,
    cd_contrato number NOT NULL,
    dt_fechamento_extrato date,
    ni_horas_extrato number,
    ni_qtd_parcela number,
    id number(8,0)
);


ALTER TABLE oasis.s_extrato_mensal OWNER TO oasis;

--
-- TOC entry 271 (class 1259 OID 17067)
-- Dependencies: 11
-- Name: s_extrato_mensal_parcela; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_extrato_mensal_parcela (
    cd_contrato number NOT NULL,
    ni_ano_extrato number NOT NULL,
    ni_mes_extrato number NOT NULL,
    cd_proposta number NOT NULL,
    cd_projeto number NOT NULL,
    cd_parcela number NOT NULL,
    ni_hora_parcela_extrato number,
    id number(8,0)
);


ALTER TABLE oasis.s_extrato_mensal_parcela OWNER TO oasis;

--
-- TOC entry 272 (class 1259 OID 17073)
-- Dependencies: 11
-- Name: s_fale_conosco; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_fale_conosco (
    cd_fale_conosco number NOT NULL,
    tx_nome varchar2,
    tx_email varchar2,
    tx_assunto varchar2,
    tx_mensagem varchar2,
    tx_resposta varchar2,
    st_respondida char(1),
    dt_registro timestamp,
    st_pendente char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_fale_conosco OWNER TO oasis;

--
-- TOC entry 273 (class 1259 OID 17079)
-- Dependencies: 11
-- Name: s_gerencia_qualidade; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_gerencia_qualidade (
    cd_gerencia_qualidade number NOT NULL,
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    cd_profissional number NOT NULL,
    dt_auditoria_qualidade date,
    tx_fase_projeto varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_gerencia_qualidade OWNER TO oasis;

--
-- TOC entry 274 (class 1259 OID 17085)
-- Dependencies: 11
-- Name: s_hist_prop_sub_item_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_hist_prop_sub_item_metrica (
    dt_historico_proposta timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    cd_proposta number(8,0) NOT NULL,
    cd_definicao_metrica number(8,0) NOT NULL,
    cd_item_metrica number(8,0) NOT NULL,
    cd_sub_item_metrica number(8,0) NOT NULL,
    ni_valor_sub_item_metrica number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.s_hist_prop_sub_item_metrica OWNER TO oasis;

--
-- TOC entry 275 (class 1259 OID 17088)
-- Dependencies: 11
-- Name: s_historico; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico (
    cd_historico number NOT NULL,
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    cd_modulo number NOT NULL,
    cd_etapa number NOT NULL,
    cd_atividade number NOT NULL,
    dt_inicio_historico date,
    dt_fim_historico date,
    tx_historico varchar2,
    cd_profissional number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.s_historico OWNER TO oasis;

--
-- TOC entry 276 (class 1259 OID 17094)
-- Dependencies: 11
-- Name: s_historico_execucao_demanda; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico_execucao_demanda (
    cd_historico_execucao_demanda number NOT NULL,
    cd_profissional number NOT NULL,
    cd_demanda number NOT NULL,
    cd_nivel_servico number NOT NULL,
    dt_inicio timestamp,
    dt_fim timestamp,
    tx_historico varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_historico_execucao_demanda OWNER TO oasis;

--
-- TOC entry 277 (class 1259 OID 17100)
-- Dependencies: 11
-- Name: s_historico_execucao_rotina; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico_execucao_rotina (
    dt_historico_execucao_rotina timestamp NOT NULL,
    cd_rotina number(8,0) NOT NULL,
    cd_objeto number(8,0) NOT NULL,
    cd_profissional number(8,0) NOT NULL,
    dt_execucao_rotina date NOT NULL,
    tx_historico_execucao_rotina varchar2(4000),
    dt_historico_rotina timestamp,
    id number(8,0)
);


ALTER TABLE oasis.s_historico_execucao_rotina OWNER TO oasis;

--
-- TOC entry 278 (class 1259 OID 17106)
-- Dependencies: 4439 4440 11
-- Name: s_historico_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico_pedido (
    cd_historico_pedido number(8,0) NOT NULL,
    cd_solicitacao_historico number(8,0) NOT NULL,
    dt_registro_historico timestamp DEFAULT now() NOT NULL,
    st_acao_historico char(1) DEFAULT 'P' NOT NULL,
    tx_descricao_historico text,
    id number(8,0)
);


ALTER TABLE oasis.s_historico_pedido OWNER TO oasis;

--
-- TOC entry 5215 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN s_historico_pedido.st_acao_historico; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN s_historico_pedido.st_acao_historico IS 'D => Analisada pelo Comitê (Tela que o Comite analisa)
A => Autorizado
C => Completar
E => Encaminhado (tela de preenchimento do pedido)
I = > Criada Solicitação de Serviço
M => Modificar
P => Preenchido (tela de preenchimento do pedido)
R => Recusado
S => Solicitação de Proposta  (tela decisoes encaminhadas pelo comite)
T => Comite
V => Validado
X => Arquivado (tela decisoes encaminhadas pelo comite)';


--
-- TOC entry 279 (class 1259 OID 17114)
-- Dependencies: 11
-- Name: s_historico_projeto_continuado; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico_projeto_continuado (
    cd_historico_proj_continuado number NOT NULL,
    cd_objeto number NOT NULL,
    cd_projeto_continuado number NOT NULL,
    cd_modulo_continuado number NOT NULL,
    cd_etapa number NOT NULL,
    cd_atividade number NOT NULL,
    dt_inicio_hist_proj_continuado date,
    dt_fim_hist_projeto_continuado date,
    tx_hist_projeto_continuado varchar2,
    cd_profissional number NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.s_historico_projeto_continuado OWNER TO oasis;

--
-- TOC entry 280 (class 1259 OID 17120)
-- Dependencies: 11
-- Name: s_historico_proposta; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico_proposta (
    dt_historico_proposta timestamp NOT NULL,
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    tx_sigla_projeto varchar2,
    tx_projeto varchar2,
    tx_contexdo_geral varchar2,
    tx_escopo_projeto varchar2,
    tx_sigla_unidade varchar2,
    tx_gestor_projeto varchar2,
    tx_impacto_projeto varchar2,
    tx_gerente_projeto varchar2,
    st_metrica_historico char(3),
    tx_inicio_previsto varchar2,
    tx_termino_previsto varchar2,
    ni_horas_proposta number(8,1),
    id number(8,0)
);


ALTER TABLE oasis.s_historico_proposta OWNER TO oasis;

--
-- TOC entry 281 (class 1259 OID 17126)
-- Dependencies: 11
-- Name: s_historico_proposta_metrica; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico_proposta_metrica (
    dt_historico_proposta timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    cd_proposta number(8,0) NOT NULL,
    cd_definicao_metrica number(8,0) NOT NULL,
    ni_um_prop_metrica_historico number(8,1),
    tx_just_metrica_historico varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_historico_proposta_metrica OWNER TO oasis;

--
-- TOC entry 282 (class 1259 OID 17132)
-- Dependencies: 11
-- Name: s_historico_proposta_parcela; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico_proposta_parcela (
    cd_proposta number NOT NULL,
    cd_projeto number NOT NULL,
    dt_historico_proposta timestamp NOT NULL,
    cd_historico_proposta_parcela number NOT NULL,
    ni_parcela number NOT NULL,
    ni_mes_previsao_parcela number,
    ni_ano_previsao_parcela number,
    ni_horas_parcela number(8,1),
    id number(8,0)
);


ALTER TABLE oasis.s_historico_proposta_parcela OWNER TO oasis;

--
-- TOC entry 283 (class 1259 OID 17138)
-- Dependencies: 11
-- Name: s_historico_proposta_produto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_historico_proposta_produto (
    cd_historico_proposta_produto number NOT NULL,
    dt_historico_proposta timestamp NOT NULL,
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    cd_historico_proposta_parcela number NOT NULL,
    tx_produto varchar2,
    cd_tipo_produto number,
    id number(8,0)
);


ALTER TABLE oasis.s_historico_proposta_produto OWNER TO oasis;

--
-- TOC entry 284 (class 1259 OID 17144)
-- Dependencies: 11
-- Name: s_interacao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_interacao (
    cd_interacao number NOT NULL,
    cd_modulo number NOT NULL,
    cd_projeto number NOT NULL,
    cd_caso_de_uso number NOT NULL,
    cd_ator number NOT NULL,
    tx_interacao varchar2,
    ni_ordem_interacao number,
    st_interacao char(1),
    dt_versao_caso_de_uso timestamp NOT NULL,
    id number(8,0)
);


ALTER TABLE oasis.s_interacao OWNER TO oasis;

--
-- TOC entry 285 (class 1259 OID 17150)
-- Dependencies: 11
-- Name: s_inventario; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_inventario (
    cd_inventario number(8,0) NOT NULL,
    cd_area_atuacao_ti number(8,0) NOT NULL,
    tx_inventario varchar2(100),
    tx_desc_inventario varchar2(4000),
    tx_obs_inventario varchar2(4000),
    dt_ult_atual_inventario date,
    id number(8,0)
);


ALTER TABLE oasis.s_inventario OWNER TO oasis;

--
-- TOC entry 286 (class 1259 OID 17156)
-- Dependencies: 11
-- Name: s_log; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_log (
    dt_ocorrencia timestamp NOT NULL,
    cd_log number(8,0) NOT NULL,
    cd_profissional number(8,0),
    tx_msg_log varchar2 NOT NULL,
    ni_prioridade number(8,0),
    tx_tabela varchar2,
    tx_controller varchar2,
    tx_ip varchar2(15),
    tx_host varchar2
);


ALTER TABLE oasis.s_log OWNER TO oasis;

--
-- TOC entry 287 (class 1259 OID 17162)
-- Dependencies: 4441 11
-- Name: s_medicao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_medicao (
    cd_medicao number NOT NULL,
    tx_medicao varchar2(200),
    tx_objetivo_medicao varchar2,
    st_nivel_medicao char(1),
    tx_procedimento_coleta varchar2,
    tx_procedimento_analise varchar2,
    id number(8,0),
    CONSTRAINT ck_oasis_001 CHECK ((st_nivel_medicao IN ('E', 'T')))
);


ALTER TABLE oasis.s_medicao OWNER TO oasis;

--
-- TOC entry 5221 (class 0 OID 0)
-- Dependencies: 287
-- Name: COLUMN s_medicao.st_nivel_medicao; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN s_medicao.st_nivel_medicao IS '''Valores possíveis:
E=Estratégico; e
T=Técnico''';


--
-- TOC entry 288 (class 1259 OID 17169)
-- Dependencies: 11
-- Name: s_mensageria; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_mensageria (
    cd_mensageria number(8,0) NOT NULL,
    cd_objeto number NOT NULL,
    cd_perfil number,
    tx_mensagem varchar2(4000),
    dt_postagem timestamp,
    dt_encerramento timestamp,
    id number(8,0)
);


ALTER TABLE oasis.s_mensageria OWNER TO oasis;

--
-- TOC entry 289 (class 1259 OID 17175)
-- Dependencies: 11
-- Name: s_modulo; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_modulo (
    cd_modulo number NOT NULL,
    cd_projeto number NOT NULL,
    cd_status number,
    tx_modulo varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_modulo OWNER TO oasis;

--
-- TOC entry 290 (class 1259 OID 17181)
-- Dependencies: 11
-- Name: s_modulo_continuado; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_modulo_continuado (
    cd_modulo_continuado number NOT NULL,
    cd_objeto number NOT NULL,
    cd_projeto_continuado number NOT NULL,
    tx_modulo_continuado varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_modulo_continuado OWNER TO oasis;

--
-- TOC entry 291 (class 1259 OID 17187)
-- Dependencies: 11
-- Name: s_objeto_contrato; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_objeto_contrato (
    cd_objeto number NOT NULL,
    cd_contrato number NOT NULL,
    tx_objeto varchar2,
    ni_horas_objeto number,
    st_objeto_contrato char(1),
    st_viagem char(1),
    id number(8,0),
    st_parcela_orcamento char(1),
    ni_porcentagem_parc_orcamento number(3,2),
    st_necessita_justificativa char(1),
    ni_minutos_justificativa number(8,0),
    tx_hora_inicio_just_periodo_1 time without time zone,
    tx_hora_fim_just_periodo_1 time without time zone,
    tx_hora_inicio_just_periodo_2 time without time zone,
    tx_hora_fim_just_periodo_2 time without time zone,
    cd_area_atuacao_ti number(8,0)
);


ALTER TABLE oasis.s_objeto_contrato OWNER TO oasis;

--
-- TOC entry 292 (class 1259 OID 17193)
-- Dependencies: 11
-- Name: s_ocorrencia_administrativa; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_ocorrencia_administrativa (
    dt_ocorrencia_administrativa timestamp NOT NULL,
    cd_evento number(8,0) NOT NULL,
    cd_contrato number NOT NULL,
    tx_ocorrencia_administrativa varchar2(4000),
    id number(8,0)
);


ALTER TABLE oasis.s_ocorrencia_administrativa OWNER TO oasis;

--
-- TOC entry 293 (class 1259 OID 17199)
-- Dependencies: 11
-- Name: s_parcela; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_parcela (
    cd_parcela number NOT NULL,
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    ni_parcela number,
    ni_horas_parcela number(8,1),
    ni_mes_previsao_parcela number,
    ni_ano_previsao_parcela number,
    ni_mes_execucao_parcela number,
    ni_ano_execucao_parcela number,
    st_modulo_proposta char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_parcela OWNER TO oasis;

--
-- TOC entry 294 (class 1259 OID 17205)
-- Dependencies: 11
-- Name: s_penalizacao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_penalizacao (
    dt_penalizacao date NOT NULL,
    cd_contrato number NOT NULL,
    cd_penalidade number NOT NULL,
    tx_obs_penalizacao varchar2,
    tx_justificativa_penalizacao varchar2,
    ni_qtd_ocorrencia number,
    st_aceite_justificativa char(1),
    id number(8,0),
    dt_justificativa date,
    tx_obs_justificativa varchar2
);


ALTER TABLE oasis.s_penalizacao OWNER TO oasis;

--
-- TOC entry 295 (class 1259 OID 17211)
-- Dependencies: 11
-- Name: s_plano_implantacao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_plano_implantacao (
    cd_projeto number(8,0) NOT NULL,
    cd_proposta number NOT NULL,
    tx_descricao_plano_implantacao varchar2 NOT NULL,
    cd_prof_plano_implantacao number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.s_plano_implantacao OWNER TO oasis;

--
-- TOC entry 296 (class 1259 OID 17217)
-- Dependencies: 11
-- Name: s_pre_demanda; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_pre_demanda (
    cd_pre_demanda number NOT NULL,
    cd_objeto_emissor number,
    cd_objeto_receptor number,
    ni_ano_solicitacao number,
    ni_solicitacao number,
    tx_pre_demanda varchar2,
    st_aceite_pre_demanda char(1),
    dt_pre_demanda timestamp,
    cd_profissional_solicitante number(8,0),
    dt_fim_pre_demanda timestamp,
    st_fim_pre_demanda char(1),
    dt_aceite_pre_demanda timestamp,
    tx_obs_aceite_pre_demanda varchar2(4000),
    tx_obs_reabertura_pre_demanda varchar2(4000),
    st_reabertura_pre_demanda char(1),
    cd_unidade number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.s_pre_demanda OWNER TO oasis;

--
-- TOC entry 297 (class 1259 OID 17223)
-- Dependencies: 11
-- Name: s_pre_projeto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_pre_projeto (
    cd_pre_projeto number(8,0) NOT NULL,
    cd_unidade number,
    cd_gerente_pre_projeto number,
    tx_pre_projeto varchar2(200),
    tx_sigla_pre_projeto varchar2(100),
    tx_contexto_geral_pre_projeto varchar2(4000),
    tx_escopo_pre_projeto varchar2(4000),
    tx_gestor_pre_projeto varchar2(200),
    tx_obs_pre_projeto varchar2(4000),
    st_impacto_pre_projeto char(1),
    st_prioridade_pre_projeto char(1),
    tx_horas_estimadas varchar2(10),
    tx_pub_alcancado_pre_proj varchar2(200),
    cd_contrato number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.s_pre_projeto OWNER TO oasis;

--
-- TOC entry 298 (class 1259 OID 17229)
-- Dependencies: 11
-- Name: s_pre_projeto_evolutivo; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_pre_projeto_evolutivo (
    cd_pre_projeto_evolutivo number NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    tx_pre_projeto_evolutivo varchar2(200),
    tx_objetivo_pre_proj_evol varchar2,
    st_gerencia_mudanca char(1),
    dt_gerencia_mudanca date,
    cd_contrato number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.s_pre_projeto_evolutivo OWNER TO oasis;

--
-- TOC entry 299 (class 1259 OID 17235)
-- Dependencies: 11
-- Name: s_previsao_projeto_diario; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_previsao_projeto_diario (
    cd_projeto number NOT NULL,
    ni_mes number NOT NULL,
    ni_dia number NOT NULL,
    ni_horas number,
    id number(8,0)
);


ALTER TABLE oasis.s_previsao_projeto_diario OWNER TO oasis;

--
-- TOC entry 300 (class 1259 OID 17241)
-- Dependencies: 11
-- Name: s_processamento_parcela; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_processamento_parcela (
    cd_processamento_parcela number(8,0) NOT NULL,
    cd_proposta number NOT NULL,
    cd_projeto number NOT NULL,
    cd_parcela number NOT NULL,
    cd_objeto_execucao number,
    ni_ano_solicitacao_execucao number,
    ni_solicitacao_execucao number,
    st_autorizacao_parcela char(1),
    dt_autorizacao_parcela timestamp,
    cd_prof_autorizacao_parcela number(8,0),
    st_fechamento_parcela char(1),
    dt_fechamento_parcela timestamp,
    cd_prof_fechamento_parcela number,
    st_parecer_tecnico_parcela char(1),
    dt_parecer_tecnico_parcela timestamp,
    tx_obs_parecer_tecnico_parcela varchar2,
    cd_prof_parecer_tecnico_parc number,
    st_aceite_parcela char(1),
    dt_aceite_parcela timestamp,
    tx_obs_aceite_parcela varchar2,
    cd_profissional_aceite_parcela number,
    st_homologacao_parcela char(1),
    dt_homologacao_parcela timestamp,
    tx_obs_homologacao_parcela varchar2,
    cd_prof_homologacao_parcela number,
    st_ativo char(1),
    st_pendente char(1),
    dt_inicio_pendencia timestamp,
    dt_fim_pendencia timestamp,
    id number(8,0)
);


ALTER TABLE oasis.s_processamento_parcela OWNER TO oasis;

--
-- TOC entry 301 (class 1259 OID 17247)
-- Dependencies: 11
-- Name: s_processamento_proposta; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_processamento_proposta (
    cd_processamento_proposta number(8,0) NOT NULL,
    cd_projeto number NOT NULL,
    cd_proposta number NOT NULL,
    st_fechamento_proposta char(1),
    dt_fechamento_proposta timestamp,
    cd_prof_fechamento_proposta number,
    st_parecer_tecnico_proposta char(1),
    dt_parecer_tecnico_proposta timestamp,
    tx_obs_parecer_tecnico_prop varchar2,
    cd_prof_parecer_tecnico_propos number,
    st_aceite_proposta char(1),
    dt_aceite_proposta timestamp,
    tx_obs_aceite_proposta varchar2,
    cd_prof_aceite_proposta number,
    st_homologacao_proposta char(1),
    dt_homologacao_proposta timestamp,
    tx_obs_homologacao_proposta varchar2,
    cd_prof_homologacao_proposta number,
    st_alocacao_proposta char(1),
    dt_alocacao_proposta timestamp,
    cd_prof_alocacao_proposta number,
    st_ativo char(1),
    tx_motivo_alteracao_proposta varchar2(4000),
    id number(8,0)
);


ALTER TABLE oasis.s_processamento_proposta OWNER TO oasis;

--
-- TOC entry 302 (class 1259 OID 17253)
-- Dependencies: 11
-- Name: s_produto_parcela; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_produto_parcela (
    cd_produto_parcela number NOT NULL,
    cd_proposta number NOT NULL,
    cd_projeto number NOT NULL,
    cd_parcela number NOT NULL,
    tx_produto_parcela varchar2,
    cd_tipo_produto number,
    id number(8,0)
);


ALTER TABLE oasis.s_produto_parcela OWNER TO oasis;

--
-- TOC entry 303 (class 1259 OID 17259)
-- Dependencies: 11
-- Name: s_profissional; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_profissional (
    cd_profissional number NOT NULL,
    tx_profissional varchar2,
    cd_relacao_contratual number,
    cd_empresa number(8,0),
    tx_nome_conhecido varchar2(100),
    tx_telefone_residencial varchar2(20),
    tx_celular_profissional varchar2(20),
    tx_ramal_profissional varchar2(10),
    tx_endereco_profissional varchar2(200),
    dt_nascimento_profissional date,
    dt_inicio_trabalho date,
    dt_saida_profissional date,
    tx_email_institucional varchar2(200),
    tx_email_pessoal varchar2(200),
    st_nova_senha char(1),
    st_inativo char(1),
    tx_senha varchar2(50),
    tx_data_ultimo_acesso varchar2,
    tx_hora_ultimo_acesso varchar2,
    cd_perfil number(8,0),
    st_dados_todos_contratos char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_profissional OWNER TO oasis;

--
-- TOC entry 304 (class 1259 OID 17265)
-- Dependencies: 4442 4443 11
-- Name: s_projeto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_projeto (
    cd_projeto number NOT NULL,
    cd_profissional_gerente number,
    cd_unidade number,
    cd_status number,
    tx_projeto varchar2,
    tx_obs_projeto varchar2,
    tx_sigla_projeto varchar2,
    tx_gestor_projeto varchar2,
    st_impacto_projeto char(1),
    st_prioridade_projeto char(1),
    tx_escopo_projeto varchar2,
    tx_contexto_geral_projeto varchar2,
    tx_publico_alcancado varchar2(200),
    ni_mes_inicio_previsto number(4,0),
    ni_ano_inicio_previsto number(4,0),
    ni_mes_termino_previsto number(4,0),
    ni_ano_termino_previsto number(4,0),
    tx_co_gestor_projeto varchar2(200),
    st_dicionario_dados char(1) DEFAULT 0,
    st_informacoes_tecnicas char(1) DEFAULT 0,
    id number(8,0)
);


ALTER TABLE oasis.s_projeto OWNER TO oasis;

--
-- TOC entry 305 (class 1259 OID 17273)
-- Dependencies: 11
-- Name: s_projeto_continuado; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_projeto_continuado (
    cd_projeto_continuado number NOT NULL,
    cd_objeto number NOT NULL,
    tx_projeto_continuado varchar2,
    tx_objetivo_projeto_continuado varchar2,
    tx_obs_projeto_continuado varchar2,
    st_prioridade_proj_continuado char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_projeto_continuado OWNER TO oasis;

--
-- TOC entry 306 (class 1259 OID 17279)
-- Dependencies: 11
-- Name: s_projeto_previsto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_projeto_previsto (
    cd_projeto_previsto number NOT NULL,
    cd_contrato number NOT NULL,
    cd_unidade number,
    tx_projeto_previsto varchar2,
    ni_horas_projeto_previsto number,
    st_projeto_previsto char(1),
    tx_descricao_projeto_previsto varchar2,
    id number(8,0),
    cd_definicao_metrica number(8,0)
);


ALTER TABLE oasis.s_projeto_previsto OWNER TO oasis;

--
-- TOC entry 307 (class 1259 OID 17285)
-- Dependencies: 4444 4445 4446 4447 4448 4449 4450 4451 4452 4453 11
-- Name: s_proposta; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_proposta (
    cd_proposta number NOT NULL,
    cd_projeto number NOT NULL,
    cd_objeto number NOT NULL,
    ni_ano_solicitacao number NOT NULL,
    ni_solicitacao number NOT NULL,
    st_encerramento_proposta char(1),
    dt_encerramento_proposta timestamp,
    cd_prof_encerramento_proposta number,
    ni_horas_proposta number(8,1),
    st_alteracao_proposta char(1),
    st_contrato_anterior char(1),
    tx_motivo_insatisfacao varchar2(4000),
    tx_gestao_qualidade varchar2(4000),
    st_descricao char(1) DEFAULT 0,
    st_profissional char(1) DEFAULT 0,
    st_metrica char(1) DEFAULT 0,
    st_documentacao char(1) DEFAULT 0,
    st_modulo char(1) DEFAULT 0,
    st_parcela char(1) DEFAULT 0,
    st_produto char(1) DEFAULT 0,
    st_caso_de_uso char(1) DEFAULT 0,
    ni_mes_proposta number(4,0),
    ni_ano_proposta number(4,0),
    tx_objetivo_proposta varchar2(4000),
    id number(8,0),
    st_requisito char(1) DEFAULT 0,
    nf_indice_avaliacao_proposta number(8,0),
    st_objetivo_proposta char(1) DEFAULT 0,
    st_suspensao_proposta char(1)
);


ALTER TABLE oasis.s_proposta OWNER TO oasis;

--
-- TOC entry 308 (class 1259 OID 17301)
-- Dependencies: 11
-- Name: s_regra_negocio; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_regra_negocio (
    cd_regra_negocio number NOT NULL,
    dt_regra_negocio timestamp NOT NULL,
    cd_projeto_regra_negocio number(8,0) NOT NULL,
    tx_regra_negocio varchar2,
    tx_descricao_regra_negocio varchar2,
    st_regra_negocio char(1),
    ni_versao_regra_negocio number,
    dt_fechamento_regra_negocio date,
    ni_ordem_regra_negocio number(8,0),
    st_fechamento_regra_negocio char(1),
    id number(8,0)
);


ALTER TABLE oasis.s_regra_negocio OWNER TO oasis;

--
-- TOC entry 309 (class 1259 OID 17307)
-- Dependencies: 11
-- Name: s_requisito; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_requisito (
    cd_requisito number NOT NULL,
    dt_versao_requisito timestamp NOT NULL,
    cd_projeto number(8,0) NOT NULL,
    st_tipo_requisito char(1),
    tx_requisito varchar2,
    tx_descricao_requisito varchar2,
    ni_versao_requisito number(8,0),
    st_prioridade_requisito char(1),
    st_requisito char(1),
    tx_usuario_solicitante varchar2,
    tx_nivel_solicitante varchar2,
    st_fechamento_requisito char(1),
    dt_fechamento_requisito date,
    ni_ordem number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.s_requisito OWNER TO oasis;

--
-- TOC entry 310 (class 1259 OID 17313)
-- Dependencies: 11
-- Name: s_reuniao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_reuniao (
    cd_reuniao number NOT NULL,
    cd_projeto number NOT NULL,
    dt_reuniao date,
    tx_pauta varchar2,
    tx_participantes varchar2,
    tx_ata varchar2,
    tx_local_reuniao varchar2,
    cd_profissional number,
    id number(8,0)
);


ALTER TABLE oasis.s_reuniao OWNER TO oasis;

--
-- TOC entry 311 (class 1259 OID 17319)
-- Dependencies: 11
-- Name: s_reuniao_geral; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_reuniao_geral (
    cd_objeto number NOT NULL,
    cd_reuniao_geral number NOT NULL,
    dt_reuniao date,
    tx_pauta varchar2,
    tx_participantes varchar2,
    tx_ata varchar2,
    tx_local_reuniao varchar2,
    cd_profissional number,
    id number(8,0)
);


ALTER TABLE oasis.s_reuniao_geral OWNER TO oasis;

--
-- TOC entry 312 (class 1259 OID 17325)
-- Dependencies: 11
-- Name: s_situacao_projeto; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_situacao_projeto (
    cd_projeto number NOT NULL,
    ni_mes_situacao_projeto number NOT NULL,
    ni_ano_situacao_projeto number NOT NULL,
    tx_situacao_projeto varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_situacao_projeto OWNER TO oasis;

--
-- TOC entry 313 (class 1259 OID 17331)
-- Dependencies: 11
-- Name: s_solicitacao; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_solicitacao (
    ni_solicitacao number NOT NULL,
    ni_ano_solicitacao number NOT NULL,
    cd_objeto number NOT NULL,
    cd_profissional number,
    cd_unidade number,
    tx_solicitacao varchar2,
    st_solicitacao char(1),
    tx_justificativa_solicitacao varchar2,
    dt_justificativa timestamp,
    st_aceite char(1),
    dt_aceite timestamp,
    tx_obs_aceite varchar2,
    st_fechamento char(1),
    dt_fechamento timestamp,
    st_homologacao char(1),
    dt_homologacao timestamp,
    tx_obs_homologacao varchar2,
    ni_dias_execucao number,
    tx_problema_encontrado varchar2,
    tx_solucao_solicitacao varchar2,
    st_grau_satisfacao char(1),
    tx_obs_grau_satisfacao varchar2,
    dt_grau_satisfacao timestamp,
    dt_leitura_solicitacao timestamp,
    dt_solicitacao timestamp,
    tx_solicitante varchar2,
    tx_sala_solicitante varchar2,
    tx_telefone_solicitante varchar2,
    tx_obs_solicitacao varchar2,
    tx_execucao_solicitacao varchar2(4000),
    ni_prazo_atendimento number(8,0),
    id number(8,0),
    st_aceite_just_solicitacao char(1),
    tx_obs_aceite_just_solicitacao varchar2(1000),
    cd_item_inventariado number(8,0),
    cd_item_inventario number(8,0),
    cd_inventario number(8,0)
);


ALTER TABLE oasis.s_solicitacao OWNER TO oasis;

--
-- TOC entry 314 (class 1259 OID 17337)
-- Dependencies: 4454 4455 11
-- Name: s_solicitacao_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_solicitacao_pedido (
    cd_solicitacao_pedido number(8,0) NOT NULL,
    cd_usuario_pedido number(8,0) NOT NULL,
    cd_unidade_pedido number NOT NULL,
    dt_solicitacao_pedido timestamp DEFAULT now() NOT NULL,
    st_situacao_pedido char(1) DEFAULT 'P' NOT NULL,
    tx_observacao_pedido text,
    dt_encaminhamento_pedido timestamp,
    dt_autorizacao_competente timestamp,
    tx_analise_aut_competente varchar2,
    dt_analise_area_ti_solicitacao timestamp,
    tx_analise_area_ti_solicitacao varchar2,
    dt_analise_area_ti_chefia_sol timestamp,
    tx_analise_area_ti_chefia_sol varchar2,
    dt_analise_comite timestamp,
    tx_analise_comite varchar2,
    dt_analise_area_ti_chefia_exec timestamp,
    tx_analise_area_ti_chefia_exec varchar2,
    cd_usuario_aut_competente number(8,0),
    id number(8,0)
);


ALTER TABLE oasis.s_solicitacao_pedido OWNER TO oasis;

--
-- TOC entry 315 (class 1259 OID 17345)
-- Dependencies: 11
-- Name: s_tabela; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_tabela (
    tx_tabela varchar2 NOT NULL,
    cd_projeto number NOT NULL,
    tx_descricao varchar2,
    id number(8,0)
);


ALTER TABLE oasis.s_tabela OWNER TO oasis;

--
-- TOC entry 316 (class 1259 OID 17351)
-- Dependencies: 4456 4457 11
-- Name: s_usuario_pedido; Type: TABLE; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE TABLE s_usuario_pedido (
    cd_usuario_pedido number(8,0) NOT NULL,
    cd_unidade_usuario number NOT NULL,
    st_autoridade char(1) DEFAULT 'N' NOT NULL,
    st_inativo char(1) DEFAULT 'N' NOT NULL,
    tx_nome_usuario varchar2(100) NOT NULL,
    tx_email_usuario varchar2(100) NOT NULL,
    tx_senha_acesso varchar2(40) NOT NULL,
    tx_sala_usuario varchar2(50),
    tx_telefone_usuario varchar2(50),
    id number(8,0)
);


ALTER TABLE oasis.s_usuario_pedido OWNER TO oasis;

--
-- TOC entry 5244 (class 0 OID 0)
-- Dependencies: 316
-- Name: COLUMN s_usuario_pedido.st_autoridade; Type: COMMENT; Schema: oasis; Owner: postgres
--

COMMENT ON COLUMN s_usuario_pedido.st_autoridade IS 'A = Autoridade Competente
C = Comite
N = Usuário comum
T = Todos';


--
-- TOC entry 4503 (class 2606 OID 17760)
-- Dependencies: 161 161 161 161 161 161
-- Name: a_form_inventario_pk; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_form_inventario
    ADD CONSTRAINT a_form_inventario_pk PRIMARY KEY (cd_inventario, cd_item_inventario, cd_item_inventariado, cd_subitem_inventario, cd_subitem_inv_descri);


--
-- TOC entry 4511 (class 2606 OID 17762)
-- Dependencies: 165 165 165 165
-- Name: a_item_inventariado_pk; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_pk PRIMARY KEY (cd_item_inventariado, cd_inventario, cd_item_inventario);


--
-- TOC entry 4640 (class 2606 OID 17764)
-- Dependencies: 219 219
-- Name: b_item_inventario_pk; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_item_inventario
    ADD CONSTRAINT b_item_inventario_pk PRIMARY KEY (cd_item_inventario);


--
-- TOC entry 4686 (class 2606 OID 17766)
-- Dependencies: 240 240 240 240
-- Name: b_subitem_inv_descri_pk; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_subitem_inv_descri
    ADD CONSTRAINT b_subitem_inv_descri_pk PRIMARY KEY (cd_item_inventario, cd_subitem_inventario, cd_subitem_inv_descri);


--
-- TOC entry 4688 (class 2606 OID 17768)
-- Dependencies: 241 241 241
-- Name: b_subitem_inventario_pk; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_subitem_inventario
    ADD CONSTRAINT b_subitem_inventario_pk PRIMARY KEY (cd_item_inventario, cd_subitem_inventario);


--
-- TOC entry 4495 (class 2606 OID 17770)
-- Dependencies: 157 157 157 157 157
-- Name: doc_projeto_continuo_pk; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_pk PRIMARY KEY (dt_doc_projeto_continuo, cd_projeto_continuado, cd_objeto, cd_tipo_documentacao);


--
-- TOC entry 4850 (class 2606 OID 17772)
-- Dependencies: 316 316
-- Name: pk_oasis_001; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_usuario_pedido
    ADD CONSTRAINT pk_oasis_001 PRIMARY KEY (cd_usuario_pedido);


--
-- TOC entry 4848 (class 2606 OID 17774)
-- Dependencies: 315 315 315
-- Name: pk_oasis_002; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_tabela
    ADD CONSTRAINT pk_oasis_002 PRIMARY KEY (tx_tabela, cd_projeto);


--
-- TOC entry 4844 (class 2606 OID 17776)
-- Dependencies: 313 313 313 313
-- Name: pk_oasis_003; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_solicitacao
    ADD CONSTRAINT pk_oasis_003 PRIMARY KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto);


--
-- TOC entry 4842 (class 2606 OID 17778)
-- Dependencies: 312 312 312 312
-- Name: pk_oasis_004; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_situacao_projeto
    ADD CONSTRAINT pk_oasis_004 PRIMARY KEY (cd_projeto, ni_mes_situacao_projeto, ni_ano_situacao_projeto);


--
-- TOC entry 4838 (class 2606 OID 17780)
-- Dependencies: 310 310 310
-- Name: pk_oasis_005; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_reuniao
    ADD CONSTRAINT pk_oasis_005 PRIMARY KEY (cd_reuniao, cd_projeto);


--
-- TOC entry 4836 (class 2606 OID 17782)
-- Dependencies: 309 309 309 309
-- Name: pk_oasis_006; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_requisito
    ADD CONSTRAINT pk_oasis_006 PRIMARY KEY (cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4834 (class 2606 OID 17784)
-- Dependencies: 308 308 308 308
-- Name: pk_oasis_007; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_regra_negocio
    ADD CONSTRAINT pk_oasis_007 PRIMARY KEY (cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio);


--
-- TOC entry 4832 (class 2606 OID 17786)
-- Dependencies: 307 307 307
-- Name: pk_oasis_008; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_proposta
    ADD CONSTRAINT pk_oasis_008 PRIMARY KEY (cd_proposta, cd_projeto);


--
-- TOC entry 4829 (class 2606 OID 17788)
-- Dependencies: 306 306 306
-- Name: pk_oasis_009; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_projeto_previsto
    ADD CONSTRAINT pk_oasis_009 PRIMARY KEY (cd_projeto_previsto, cd_contrato);


--
-- TOC entry 4827 (class 2606 OID 17790)
-- Dependencies: 305 305 305
-- Name: pk_oasis_010; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_projeto_continuado
    ADD CONSTRAINT pk_oasis_010 PRIMARY KEY (cd_projeto_continuado, cd_objeto);


--
-- TOC entry 4825 (class 2606 OID 17792)
-- Dependencies: 304 304
-- Name: pk_oasis_011; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_projeto
    ADD CONSTRAINT pk_oasis_011 PRIMARY KEY (cd_projeto);


--
-- TOC entry 4823 (class 2606 OID 17794)
-- Dependencies: 303 303
-- Name: pk_oasis_012; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_profissional
    ADD CONSTRAINT pk_oasis_012 PRIMARY KEY (cd_profissional);


--
-- TOC entry 4821 (class 2606 OID 17796)
-- Dependencies: 302 302 302 302 302
-- Name: pk_oasis_013; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_produto_parcela
    ADD CONSTRAINT pk_oasis_013 PRIMARY KEY (cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela);


--
-- TOC entry 4819 (class 2606 OID 17798)
-- Dependencies: 301 301 301 301
-- Name: pk_oasis_014; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_processamento_proposta
    ADD CONSTRAINT pk_oasis_014 PRIMARY KEY (cd_processamento_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 4817 (class 2606 OID 17800)
-- Dependencies: 300 300 300 300 300
-- Name: pk_oasis_015; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_processamento_parcela
    ADD CONSTRAINT pk_oasis_015 PRIMARY KEY (cd_processamento_parcela, cd_proposta, cd_projeto, cd_parcela);


--
-- TOC entry 4815 (class 2606 OID 17802)
-- Dependencies: 299 299 299 299
-- Name: pk_oasis_016; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_previsao_projeto_diario
    ADD CONSTRAINT pk_oasis_016 PRIMARY KEY (cd_projeto, ni_mes, ni_dia);


--
-- TOC entry 4813 (class 2606 OID 17804)
-- Dependencies: 298 298 298
-- Name: pk_oasis_017; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_pre_projeto_evolutivo
    ADD CONSTRAINT pk_oasis_017 PRIMARY KEY (cd_pre_projeto_evolutivo, cd_projeto);


--
-- TOC entry 4811 (class 2606 OID 17806)
-- Dependencies: 297 297
-- Name: pk_oasis_018; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_pre_projeto
    ADD CONSTRAINT pk_oasis_018 PRIMARY KEY (cd_pre_projeto);


--
-- TOC entry 4809 (class 2606 OID 17808)
-- Dependencies: 296 296
-- Name: pk_oasis_019; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_pre_demanda
    ADD CONSTRAINT pk_oasis_019 PRIMARY KEY (cd_pre_demanda);


--
-- TOC entry 4807 (class 2606 OID 17810)
-- Dependencies: 295 295 295
-- Name: pk_oasis_020; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_plano_implantacao
    ADD CONSTRAINT pk_oasis_020 PRIMARY KEY (cd_projeto, cd_proposta);


--
-- TOC entry 4805 (class 2606 OID 17812)
-- Dependencies: 294 294 294 294
-- Name: pk_oasis_021; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_penalizacao
    ADD CONSTRAINT pk_oasis_021 PRIMARY KEY (dt_penalizacao, cd_contrato, cd_penalidade);


--
-- TOC entry 4803 (class 2606 OID 17814)
-- Dependencies: 293 293 293 293
-- Name: pk_oasis_022; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_parcela
    ADD CONSTRAINT pk_oasis_022 PRIMARY KEY (cd_parcela, cd_projeto, cd_proposta);


--
-- TOC entry 4801 (class 2606 OID 17816)
-- Dependencies: 292 292 292 292
-- Name: pk_oasis_023; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_ocorrencia_administrativa
    ADD CONSTRAINT pk_oasis_023 PRIMARY KEY (dt_ocorrencia_administrativa, cd_evento, cd_contrato);


--
-- TOC entry 4799 (class 2606 OID 17818)
-- Dependencies: 291 291
-- Name: pk_oasis_024; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_objeto_contrato
    ADD CONSTRAINT pk_oasis_024 PRIMARY KEY (cd_objeto);


--
-- TOC entry 4797 (class 2606 OID 17820)
-- Dependencies: 290 290 290 290
-- Name: pk_oasis_025; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_modulo_continuado
    ADD CONSTRAINT pk_oasis_025 PRIMARY KEY (cd_modulo_continuado, cd_objeto, cd_projeto_continuado);


--
-- TOC entry 4795 (class 2606 OID 17822)
-- Dependencies: 289 289 289
-- Name: pk_oasis_026; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_modulo
    ADD CONSTRAINT pk_oasis_026 PRIMARY KEY (cd_modulo, cd_projeto);


--
-- TOC entry 4793 (class 2606 OID 17824)
-- Dependencies: 288 288
-- Name: pk_oasis_027; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_mensageria
    ADD CONSTRAINT pk_oasis_027 PRIMARY KEY (cd_mensageria);


--
-- TOC entry 4791 (class 2606 OID 17826)
-- Dependencies: 287 287
-- Name: pk_oasis_028; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_medicao
    ADD CONSTRAINT pk_oasis_028 PRIMARY KEY (cd_medicao);


--
-- TOC entry 4789 (class 2606 OID 17828)
-- Dependencies: 286 286 286
-- Name: pk_oasis_029; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_log
    ADD CONSTRAINT pk_oasis_029 PRIMARY KEY (dt_ocorrencia, cd_log);


--
-- TOC entry 4785 (class 2606 OID 17830)
-- Dependencies: 284 284 284 284 284 284
-- Name: pk_oasis_030; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_interacao
    ADD CONSTRAINT pk_oasis_030 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_interacao);


--
-- TOC entry 4783 (class 2606 OID 17832)
-- Dependencies: 283 283 283 283 283 283
-- Name: pk_oasis_032; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico_proposta_produto
    ADD CONSTRAINT pk_oasis_032 PRIMARY KEY (cd_historico_proposta_produto, dt_historico_proposta, cd_projeto, cd_proposta, cd_historico_proposta_parcela);


--
-- TOC entry 4781 (class 2606 OID 17834)
-- Dependencies: 282 282 282 282 282
-- Name: pk_oasis_033; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico_proposta_parcela
    ADD CONSTRAINT pk_oasis_033 PRIMARY KEY (cd_proposta, cd_projeto, dt_historico_proposta, cd_historico_proposta_parcela);


--
-- TOC entry 4777 (class 2606 OID 17836)
-- Dependencies: 281 281 281 281 281
-- Name: pk_oasis_034; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico_proposta_metrica
    ADD CONSTRAINT pk_oasis_034 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta, cd_definicao_metrica);


--
-- TOC entry 4773 (class 2606 OID 17838)
-- Dependencies: 280 280 280 280
-- Name: pk_oasis_035; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico_proposta
    ADD CONSTRAINT pk_oasis_035 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 4771 (class 2606 OID 17840)
-- Dependencies: 279 279 279 279 279 279 279
-- Name: pk_oasis_036; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico_projeto_continuado
    ADD CONSTRAINT pk_oasis_036 PRIMARY KEY (cd_historico_proj_continuado, cd_objeto, cd_projeto_continuado, cd_modulo_continuado, cd_etapa, cd_atividade);


--
-- TOC entry 4765 (class 2606 OID 17842)
-- Dependencies: 276 276 276 276
-- Name: pk_oasis_037; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico_execucao_demanda
    ADD CONSTRAINT pk_oasis_037 PRIMARY KEY (cd_historico_execucao_demanda, cd_profissional, cd_demanda);


--
-- TOC entry 4763 (class 2606 OID 17844)
-- Dependencies: 275 275 275 275 275 275 275
-- Name: pk_oasis_038; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico
    ADD CONSTRAINT pk_oasis_038 PRIMARY KEY (cd_historico, cd_projeto, cd_proposta, cd_modulo, cd_etapa, cd_atividade);


--
-- TOC entry 4759 (class 2606 OID 17846)
-- Dependencies: 274 274 274 274 274 274 274
-- Name: pk_oasis_039; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_hist_prop_sub_item_metrica
    ADD CONSTRAINT pk_oasis_039 PRIMARY KEY (dt_historico_proposta, cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);


--
-- TOC entry 4755 (class 2606 OID 17848)
-- Dependencies: 273 273 273 273
-- Name: pk_oasis_040; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_gerencia_qualidade
    ADD CONSTRAINT pk_oasis_040 PRIMARY KEY (cd_gerencia_qualidade, cd_projeto, cd_proposta);


--
-- TOC entry 4753 (class 2606 OID 17850)
-- Dependencies: 272 272
-- Name: pk_oasis_041; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_fale_conosco
    ADD CONSTRAINT pk_oasis_041 PRIMARY KEY (cd_fale_conosco);


--
-- TOC entry 4751 (class 2606 OID 17852)
-- Dependencies: 271 271 271 271 271 271 271
-- Name: pk_oasis_042; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_extrato_mensal_parcela
    ADD CONSTRAINT pk_oasis_042 PRIMARY KEY (cd_contrato, ni_ano_extrato, ni_mes_extrato, cd_proposta, cd_projeto, cd_parcela);


--
-- TOC entry 4749 (class 2606 OID 17854)
-- Dependencies: 270 270 270 270
-- Name: pk_oasis_043; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_extrato_mensal
    ADD CONSTRAINT pk_oasis_043 PRIMARY KEY (ni_mes_extrato, ni_ano_extrato, cd_contrato);


--
-- TOC entry 4745 (class 2606 OID 17856)
-- Dependencies: 268 268
-- Name: pk_oasis_044; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_empresa
    ADD CONSTRAINT pk_oasis_044 PRIMARY KEY (cd_empresa);


--
-- TOC entry 4743 (class 2606 OID 17858)
-- Dependencies: 267 267 267
-- Name: pk_oasis_045; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_disponibilidade_servico
    ADD CONSTRAINT pk_oasis_045 PRIMARY KEY (cd_disponibilidade_servico, cd_objeto);


--
-- TOC entry 4741 (class 2606 OID 17860)
-- Dependencies: 266 266
-- Name: pk_oasis_046; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_demanda
    ADD CONSTRAINT pk_oasis_046 PRIMARY KEY (cd_demanda);


--
-- TOC entry 4739 (class 2606 OID 17862)
-- Dependencies: 265 265 265 265
-- Name: pk_oasis_047; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_custo_contrato_demanda
    ADD CONSTRAINT pk_oasis_047 PRIMARY KEY (cd_contrato, ni_mes_custo_contrato_demanda, ni_ano_custo_contrato_demanda);


--
-- TOC entry 4737 (class 2606 OID 17864)
-- Dependencies: 264 264
-- Name: pk_oasis_048; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_contrato
    ADD CONSTRAINT pk_oasis_048 PRIMARY KEY (cd_contrato);


--
-- TOC entry 4735 (class 2606 OID 17866)
-- Dependencies: 263 263 263
-- Name: pk_oasis_049; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_contato_empresa
    ADD CONSTRAINT pk_oasis_049 PRIMARY KEY (cd_contato_empresa, cd_empresa);


--
-- TOC entry 4733 (class 2606 OID 17868)
-- Dependencies: 262 262
-- Name: pk_oasis_050; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_config_banco_de_dados
    ADD CONSTRAINT pk_oasis_050 PRIMARY KEY (cd_projeto);


--
-- TOC entry 4731 (class 2606 OID 17870)
-- Dependencies: 261 261 261 261 261 261
-- Name: pk_oasis_051; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_condicao
    ADD CONSTRAINT pk_oasis_051 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_condicao);


--
-- TOC entry 4729 (class 2606 OID 17872)
-- Dependencies: 260 260 260 260 260 260
-- Name: pk_oasis_052; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_complemento
    ADD CONSTRAINT pk_oasis_052 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso, cd_complemento);


--
-- TOC entry 4727 (class 2606 OID 17874)
-- Dependencies: 259 259 259 259
-- Name: pk_oasis_053; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_coluna
    ADD CONSTRAINT pk_oasis_053 PRIMARY KEY (tx_tabela, tx_coluna, cd_projeto);


--
-- TOC entry 4725 (class 2606 OID 17876)
-- Dependencies: 258 258 258 258 258
-- Name: pk_oasis_054; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_caso_de_uso
    ADD CONSTRAINT pk_oasis_054 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);


--
-- TOC entry 4723 (class 2606 OID 17878)
-- Dependencies: 257 257 257
-- Name: pk_oasis_055; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_baseline
    ADD CONSTRAINT pk_oasis_055 PRIMARY KEY (dt_baseline, cd_projeto);


--
-- TOC entry 4721 (class 2606 OID 17880)
-- Dependencies: 256 256 256
-- Name: pk_oasis_056; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_base_conhecimento
    ADD CONSTRAINT pk_oasis_056 PRIMARY KEY (cd_base_conhecimento, cd_area_conhecimento);


--
-- TOC entry 4719 (class 2606 OID 17882)
-- Dependencies: 255 255
-- Name: pk_oasis_057; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_ator
    ADD CONSTRAINT pk_oasis_057 PRIMARY KEY (cd_ator);


--
-- TOC entry 4714 (class 2606 OID 17884)
-- Dependencies: 253 253 253 253 253 253 253
-- Name: pk_oasis_058; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_analise_risco
    ADD CONSTRAINT pk_oasis_058 PRIMARY KEY (dt_analise_risco, cd_projeto, cd_proposta, cd_etapa, cd_atividade, cd_item_risco);


--
-- TOC entry 4710 (class 2606 OID 17886)
-- Dependencies: 252 252 252 252
-- Name: pk_oasis_059; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_analise_medicao
    ADD CONSTRAINT pk_oasis_059 PRIMARY KEY (dt_analise_medicao, cd_medicao, cd_box_inicio);


--
-- TOC entry 4708 (class 2606 OID 17888)
-- Dependencies: 251 251 251 251
-- Name: pk_oasis_060; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_analise_matriz_rastreab
    ADD CONSTRAINT pk_oasis_060 PRIMARY KEY (cd_analise_matriz_rastreab, cd_projeto, st_matriz_rastreabilidade);


--
-- TOC entry 4706 (class 2606 OID 17890)
-- Dependencies: 250 250 250
-- Name: pk_oasis_061; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_analise_execucao_projeto
    ADD CONSTRAINT pk_oasis_061 PRIMARY KEY (dt_analise_execucao_projeto, cd_projeto);


--
-- TOC entry 4704 (class 2606 OID 17892)
-- Dependencies: 249 249 249 249
-- Name: pk_oasis_062; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_agenda_plano_implantacao
    ADD CONSTRAINT pk_oasis_062 PRIMARY KEY (dt_agenda_plano_implantacao, cd_proposta, cd_projeto);


--
-- TOC entry 4702 (class 2606 OID 17894)
-- Dependencies: 248 248 248 248
-- Name: pk_oasis_063; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_acompanhamento_proposta
    ADD CONSTRAINT pk_oasis_063 PRIMARY KEY (cd_acompanhamento_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 4700 (class 2606 OID 17896)
-- Dependencies: 247 247
-- Name: pk_oasis_064; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_unidade
    ADD CONSTRAINT pk_oasis_064 PRIMARY KEY (cd_unidade);


--
-- TOC entry 4698 (class 2606 OID 17898)
-- Dependencies: 246 246
-- Name: pk_oasis_065; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_treinamento
    ADD CONSTRAINT pk_oasis_065 PRIMARY KEY (cd_treinamento);


--
-- TOC entry 4696 (class 2606 OID 17900)
-- Dependencies: 245 245
-- Name: pk_oasis_066; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_tipo_produto
    ADD CONSTRAINT pk_oasis_066 PRIMARY KEY (cd_tipo_produto);


--
-- TOC entry 4694 (class 2606 OID 17902)
-- Dependencies: 244 244
-- Name: pk_oasis_068; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_tipo_documentacao
    ADD CONSTRAINT pk_oasis_068 PRIMARY KEY (cd_tipo_documentacao);


--
-- TOC entry 4692 (class 2606 OID 17904)
-- Dependencies: 243 243
-- Name: pk_oasis_069; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_tipo_dado_tecnico
    ADD CONSTRAINT pk_oasis_069 PRIMARY KEY (cd_tipo_dado_tecnico);


--
-- TOC entry 4690 (class 2606 OID 17906)
-- Dependencies: 242 242
-- Name: pk_oasis_070; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_tipo_conhecimento
    ADD CONSTRAINT pk_oasis_070 PRIMARY KEY (cd_tipo_conhecimento);


--
-- TOC entry 4683 (class 2606 OID 17908)
-- Dependencies: 239 239 239 239
-- Name: pk_oasis_071; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_sub_item_metrica
    ADD CONSTRAINT pk_oasis_071 PRIMARY KEY (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);


--
-- TOC entry 4678 (class 2606 OID 17910)
-- Dependencies: 237 237
-- Name: pk_oasis_072; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_status
    ADD CONSTRAINT pk_oasis_072 PRIMARY KEY (cd_status);


--
-- TOC entry 4672 (class 2606 OID 17912)
-- Dependencies: 234 234
-- Name: pk_oasis_073; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_relacao_contratual
    ADD CONSTRAINT pk_oasis_073 PRIMARY KEY (cd_relacao_contratual);


--
-- TOC entry 4670 (class 2606 OID 17914)
-- Dependencies: 233 233 233 233 233
-- Name: pk_oasis_074; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_questao_analise_risco
    ADD CONSTRAINT pk_oasis_074 PRIMARY KEY (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);


--
-- TOC entry 4666 (class 2606 OID 17916)
-- Dependencies: 231 231
-- Name: pk_oasis_075; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_perfil_profissional
    ADD CONSTRAINT pk_oasis_075 PRIMARY KEY (cd_perfil_profissional);


--
-- TOC entry 4664 (class 2606 OID 17918)
-- Dependencies: 230 230
-- Name: pk_oasis_076; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_perfil
    ADD CONSTRAINT pk_oasis_076 PRIMARY KEY (cd_perfil);


--
-- TOC entry 4662 (class 2606 OID 17920)
-- Dependencies: 229 229 229
-- Name: pk_oasis_077; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_penalidade
    ADD CONSTRAINT pk_oasis_077 PRIMARY KEY (cd_penalidade, cd_contrato);


--
-- TOC entry 4660 (class 2606 OID 17922)
-- Dependencies: 228 228
-- Name: pk_oasis_078; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_papel_profissional
    ADD CONSTRAINT pk_oasis_078 PRIMARY KEY (cd_papel_profissional);


--
-- TOC entry 4658 (class 2606 OID 55054)
-- Dependencies: 227 227
-- Name: pk_oasis_079; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_nivel_servico
    ADD CONSTRAINT pk_oasis_079 PRIMARY KEY (cd_nivel_servico);


--
-- TOC entry 4656 (class 2606 OID 17926)
-- Dependencies: 226 226 226
-- Name: pk_oasis_080; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_msg_email
    ADD CONSTRAINT pk_oasis_080 PRIMARY KEY (cd_msg_email, cd_menu);


--
-- TOC entry 4654 (class 2606 OID 17928)
-- Dependencies: 225 225
-- Name: pk_oasis_081; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_menu
    ADD CONSTRAINT pk_oasis_081 PRIMARY KEY (cd_menu);


--
-- TOC entry 4652 (class 2606 OID 17930)
-- Dependencies: 224 224
-- Name: pk_oasis_082; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_medida
    ADD CONSTRAINT pk_oasis_082 PRIMARY KEY (cd_medida);


--
-- TOC entry 4650 (class 2606 OID 17932)
-- Dependencies: 223 223
-- Name: pk_oasis_083; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_item_teste
    ADD CONSTRAINT pk_oasis_083 PRIMARY KEY (cd_item_teste);


--
-- TOC entry 4648 (class 2606 OID 17934)
-- Dependencies: 222 222 222 222
-- Name: pk_oasis_084; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_item_risco
    ADD CONSTRAINT pk_oasis_084 PRIMARY KEY (cd_item_risco, cd_etapa, cd_atividade);


--
-- TOC entry 4646 (class 2606 OID 17936)
-- Dependencies: 221 221
-- Name: pk_oasis_085; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_item_parecer_tecnico
    ADD CONSTRAINT pk_oasis_085 PRIMARY KEY (cd_item_parecer_tecnico);


--
-- TOC entry 4643 (class 2606 OID 17938)
-- Dependencies: 220 220 220
-- Name: pk_oasis_086; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_item_metrica
    ADD CONSTRAINT pk_oasis_086 PRIMARY KEY (cd_item_metrica, cd_definicao_metrica);


--
-- TOC entry 4638 (class 2606 OID 17940)
-- Dependencies: 218 218 218
-- Name: pk_oasis_088; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_item_grupo_fator
    ADD CONSTRAINT pk_oasis_088 PRIMARY KEY (cd_item_grupo_fator, cd_grupo_fator);


--
-- TOC entry 4636 (class 2606 OID 17942)
-- Dependencies: 217 217
-- Name: pk_oasis_089; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_item_controle_baseline
    ADD CONSTRAINT pk_oasis_089 PRIMARY KEY (cd_item_controle_baseline);


--
-- TOC entry 4634 (class 2606 OID 17944)
-- Dependencies: 216 216
-- Name: pk_oasis_090; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_grupo_fator
    ADD CONSTRAINT pk_oasis_090 PRIMARY KEY (cd_grupo_fator);


--
-- TOC entry 4632 (class 2606 OID 17946)
-- Dependencies: 215 215
-- Name: pk_oasis_091; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_funcionalidade
    ADD CONSTRAINT pk_oasis_091 PRIMARY KEY (cd_funcionalidade);


--
-- TOC entry 4630 (class 2606 OID 17948)
-- Dependencies: 214 214
-- Name: pk_oasis_092; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_evento
    ADD CONSTRAINT pk_oasis_092 PRIMARY KEY (cd_evento);


--
-- TOC entry 4628 (class 2606 OID 17950)
-- Dependencies: 213 213
-- Name: pk_oasis_093; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_etapa
    ADD CONSTRAINT pk_oasis_093 PRIMARY KEY (cd_etapa);


--
-- TOC entry 4626 (class 2606 OID 17952)
-- Dependencies: 212 212
-- Name: pk_oasis_094; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_definicao_metrica
    ADD CONSTRAINT pk_oasis_094 PRIMARY KEY (cd_definicao_metrica);


--
-- TOC entry 4624 (class 2606 OID 17954)
-- Dependencies: 211 211
-- Name: pk_oasis_095; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_conjunto_medida
    ADD CONSTRAINT pk_oasis_095 PRIMARY KEY (cd_conjunto_medida);


--
-- TOC entry 4622 (class 2606 OID 17956)
-- Dependencies: 210 210 210
-- Name: pk_oasis_096; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_conhecimento
    ADD CONSTRAINT pk_oasis_096 PRIMARY KEY (cd_conhecimento, cd_tipo_conhecimento);


--
-- TOC entry 4620 (class 2606 OID 17958)
-- Dependencies: 209 209 209 209 209
-- Name: pk_oasis_097; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_condicao_sub_item_metrica
    ADD CONSTRAINT pk_oasis_097 PRIMARY KEY (cd_condicao_sub_item_metrica, cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica);


--
-- TOC entry 4616 (class 2606 OID 17960)
-- Dependencies: 208 208
-- Name: pk_oasis_098; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_box_inicio
    ADD CONSTRAINT pk_oasis_098 PRIMARY KEY (cd_box_inicio);


--
-- TOC entry 4614 (class 2606 OID 17962)
-- Dependencies: 207 207 207
-- Name: pk_oasis_099; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_atividade
    ADD CONSTRAINT pk_oasis_099 PRIMARY KEY (cd_atividade, cd_etapa);


--
-- TOC entry 4612 (class 2606 OID 17964)
-- Dependencies: 206 206
-- Name: pk_oasis_100; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_area_conhecimento
    ADD CONSTRAINT pk_oasis_100 PRIMARY KEY (cd_area_conhecimento);


--
-- TOC entry 4610 (class 2606 OID 17966)
-- Dependencies: 205 205
-- Name: pk_oasis_101; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_area_atuacao_ti
    ADD CONSTRAINT pk_oasis_101 PRIMARY KEY (cd_area_atuacao_ti);


--
-- TOC entry 4608 (class 2606 OID 17968)
-- Dependencies: 204 204 204
-- Name: pk_oasis_102; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_treinamento_profissional
    ADD CONSTRAINT pk_oasis_102 PRIMARY KEY (cd_treinamento, cd_profissional);


--
-- TOC entry 4602 (class 2606 OID 17970)
-- Dependencies: 201 201 201 201
-- Name: pk_oasis_103; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_reuniao_profissional
    ADD CONSTRAINT pk_oasis_103 PRIMARY KEY (cd_projeto, cd_reuniao, cd_profissional);


--
-- TOC entry 4598 (class 2606 OID 17972)
-- Dependencies: 199 199 199 199 199 199 199
-- Name: pk_oasis_104; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_requisito_dependente
    ADD CONSTRAINT pk_oasis_104 PRIMARY KEY (cd_requisito_ascendente, dt_versao_requisito_ascendente, cd_projeto_ascendente, cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4594 (class 2606 OID 17974)
-- Dependencies: 198 198 198 198 198 198 198
-- Name: pk_oasis_105; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_requisito_caso_de_uso
    ADD CONSTRAINT pk_oasis_105 PRIMARY KEY (cd_projeto, dt_versao_requisito, cd_requisito, dt_versao_caso_de_uso, cd_caso_de_uso, cd_modulo);


--
-- TOC entry 4590 (class 2606 OID 17976)
-- Dependencies: 197 197 197 197 197 197 197
-- Name: pk_oasis_106; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_regra_negocio_requisito
    ADD CONSTRAINT pk_oasis_106 PRIMARY KEY (cd_projeto_regra_negocio, dt_regra_negocio, cd_regra_negocio, dt_versao_requisito, cd_requisito, cd_projeto);


--
-- TOC entry 4588 (class 2606 OID 17978)
-- Dependencies: 196 196 196 196 196 196 196 196
-- Name: pk_oasis_107; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_questionario_analise_risco
    ADD CONSTRAINT pk_oasis_107 PRIMARY KEY (dt_analise_risco, cd_projeto, cd_proposta, cd_etapa, cd_atividade, cd_item_risco, cd_questao_analise_risco);


--
-- TOC entry 4583 (class 2606 OID 17980)
-- Dependencies: 195 195 195 195 195
-- Name: pk_oasis_108; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_quest_avaliacao_qualidade
    ADD CONSTRAINT pk_oasis_108 PRIMARY KEY (cd_projeto, cd_proposta, cd_grupo_fator, cd_item_grupo_fator);


--
-- TOC entry 4581 (class 2606 OID 17982)
-- Dependencies: 194 194 194 194 194 194
-- Name: pk_oasis_109; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_proposta_sub_item_metrica
    ADD CONSTRAINT pk_oasis_109 PRIMARY KEY (cd_projeto, cd_proposta, cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica);


--
-- TOC entry 4579 (class 2606 OID 17984)
-- Dependencies: 193 193 193 193
-- Name: pk_oasis_110; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_proposta_modulo
    ADD CONSTRAINT pk_oasis_110 PRIMARY KEY (cd_projeto, cd_modulo, cd_proposta);


--
-- TOC entry 4575 (class 2606 OID 17986)
-- Dependencies: 192 192 192 192
-- Name: pk_oasis_111; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_proposta_definicao_metrica
    ADD CONSTRAINT pk_oasis_111 PRIMARY KEY (cd_projeto, cd_proposta, cd_definicao_metrica);


--
-- TOC entry 4571 (class 2606 OID 17988)
-- Dependencies: 191 191 191 191
-- Name: pk_oasis_112; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_profissional_projeto
    ADD CONSTRAINT pk_oasis_112 PRIMARY KEY (cd_profissional, cd_projeto, cd_papel_profissional);


--
-- TOC entry 4569 (class 2606 OID 17990)
-- Dependencies: 190 190 190 190 190 190
-- Name: pk_oasis_113; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_profissional_produto
    ADD CONSTRAINT pk_oasis_113 PRIMARY KEY (cd_profissional, cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela);


--
-- TOC entry 4567 (class 2606 OID 17992)
-- Dependencies: 189 189 189
-- Name: pk_oasis_114; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_profissional_objeto_contrato
    ADD CONSTRAINT pk_oasis_114 PRIMARY KEY (cd_profissional, cd_objeto);


--
-- TOC entry 4565 (class 2606 OID 17994)
-- Dependencies: 188 188 188 188
-- Name: pk_oasis_115; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_profissional_menu
    ADD CONSTRAINT pk_oasis_115 PRIMARY KEY (cd_menu, cd_profissional, cd_objeto);


--
-- TOC entry 4563 (class 2606 OID 17996)
-- Dependencies: 187 187 187
-- Name: pk_oasis_116; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_profissional_mensageria
    ADD CONSTRAINT pk_oasis_116 PRIMARY KEY (cd_profissional, cd_mensageria);


--
-- TOC entry 4561 (class 2606 OID 17998)
-- Dependencies: 186 186 186 186
-- Name: pk_oasis_117; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_profissional_conhecimento
    ADD CONSTRAINT pk_oasis_117 PRIMARY KEY (cd_profissional, cd_tipo_conhecimento, cd_conhecimento);


--
-- TOC entry 4559 (class 2606 OID 18000)
-- Dependencies: 185 185 185 185 185
-- Name: pk_oasis_118; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_planejamento
    ADD CONSTRAINT pk_oasis_118 PRIMARY KEY (cd_etapa, cd_atividade, cd_projeto, cd_modulo);


--
-- TOC entry 4555 (class 2606 OID 18002)
-- Dependencies: 183 183 183
-- Name: pk_oasis_119; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_perfil_prof_papel_prof
    ADD CONSTRAINT pk_oasis_119 PRIMARY KEY (cd_perfil_profissional, cd_papel_profissional);


--
-- TOC entry 4549 (class 2606 OID 18004)
-- Dependencies: 182 182 182 182
-- Name: pk_oasis_120; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_perfil_menu_sistema
    ADD CONSTRAINT pk_oasis_120 PRIMARY KEY (cd_perfil, cd_menu, st_perfil_menu);


--
-- TOC entry 4547 (class 2606 OID 18006)
-- Dependencies: 181 181 181 181
-- Name: pk_oasis_121; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_perfil_menu
    ADD CONSTRAINT pk_oasis_121 PRIMARY KEY (cd_menu, cd_perfil, cd_objeto);


--
-- TOC entry 4545 (class 2606 OID 18008)
-- Dependencies: 180 180 180 180
-- Name: pk_oasis_122; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_perfil_box_inicio
    ADD CONSTRAINT pk_oasis_122 PRIMARY KEY (cd_perfil, cd_box_inicio, cd_objeto);


--
-- TOC entry 4543 (class 2606 OID 18010)
-- Dependencies: 179 179 179 179 179
-- Name: pk_oasis_123; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_parecer_tecnico_proposta
    ADD CONSTRAINT pk_oasis_123 PRIMARY KEY (cd_item_parecer_tecnico, cd_proposta, cd_projeto, cd_processamento_proposta);


--
-- TOC entry 4541 (class 2606 OID 18012)
-- Dependencies: 178 178 178 178 178 178
-- Name: pk_oasis_124; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_parecer_tecnico_parcela
    ADD CONSTRAINT pk_oasis_124 PRIMARY KEY (cd_projeto, cd_proposta, cd_parcela, cd_item_parecer_tecnico, cd_processamento_parcela);


--
-- TOC entry 4535 (class 2606 OID 18014)
-- Dependencies: 175 175 175
-- Name: pk_oasis_125; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_objeto_contrato_perfil_prof
    ADD CONSTRAINT pk_oasis_125 PRIMARY KEY (cd_objeto, cd_perfil_profissional);


--
-- TOC entry 4533 (class 2606 OID 18016)
-- Dependencies: 174 174 174
-- Name: pk_oasis_126; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_objeto_contrato_papel_prof
    ADD CONSTRAINT pk_oasis_126 PRIMARY KEY (cd_objeto, cd_papel_profissional);


--
-- TOC entry 4531 (class 2606 OID 18018)
-- Dependencies: 173 173 173 173
-- Name: pk_oasis_127; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_objeto_contrato_atividade
    ADD CONSTRAINT pk_oasis_127 PRIMARY KEY (cd_objeto, cd_etapa, cd_atividade);


--
-- TOC entry 4529 (class 2606 OID 18020)
-- Dependencies: 172 172 172
-- Name: pk_oasis_128; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_medicao_medida
    ADD CONSTRAINT pk_oasis_128 PRIMARY KEY (cd_medicao, cd_medida);


--
-- TOC entry 4523 (class 2606 OID 18022)
-- Dependencies: 171 171 171 171 171 171 171
-- Name: pk_oasis_129; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_item_teste_requisito_doc
    ADD CONSTRAINT pk_oasis_129 PRIMARY KEY (cd_arq_item_teste_requisito, cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste);


--
-- TOC entry 4521 (class 2606 OID 18024)
-- Dependencies: 170 170 170 170 170 170
-- Name: pk_oasis_130; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_item_teste_requisito
    ADD CONSTRAINT pk_oasis_130 PRIMARY KEY (cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste);


--
-- TOC entry 4519 (class 2606 OID 18026)
-- Dependencies: 169 169 169 169 169 169 169
-- Name: pk_oasis_131; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_item_teste_regra_negocio_doc
    ADD CONSTRAINT pk_oasis_131 PRIMARY KEY (cd_arq_item_teste_regra_neg, dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio);


--
-- TOC entry 4517 (class 2606 OID 18028)
-- Dependencies: 168 168 168 168 168 168
-- Name: pk_oasis_132; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_item_teste_regra_negocio
    ADD CONSTRAINT pk_oasis_132 PRIMARY KEY (dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio);


--
-- TOC entry 4515 (class 2606 OID 18030)
-- Dependencies: 167 167 167 167 167 167 167 167
-- Name: pk_oasis_133; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_item_teste_caso_de_uso_doc
    ADD CONSTRAINT pk_oasis_133 PRIMARY KEY (cd_arq_item_teste_caso_de_uso, cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso);


--
-- TOC entry 4513 (class 2606 OID 18032)
-- Dependencies: 166 166 166 166 166 166 166
-- Name: pk_oasis_134; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_item_teste_caso_de_uso
    ADD CONSTRAINT pk_oasis_134 PRIMARY KEY (cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso);


--
-- TOC entry 4509 (class 2606 OID 18034)
-- Dependencies: 164 164 164
-- Name: pk_oasis_135; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_informacao_tecnica
    ADD CONSTRAINT pk_oasis_135 PRIMARY KEY (cd_projeto, cd_tipo_dado_tecnico);


--
-- TOC entry 4507 (class 2606 OID 18036)
-- Dependencies: 163 163 163 163 163 163
-- Name: pk_oasis_136; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_gerencia_mudanca
    ADD CONSTRAINT pk_oasis_136 PRIMARY KEY (dt_gerencia_mudanca, cd_item_controle_baseline, cd_projeto, cd_item_controlado, dt_versao_item_controlado);


--
-- TOC entry 4505 (class 2606 OID 18038)
-- Dependencies: 162 162 162
-- Name: pk_oasis_137; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_funcionalidade_menu
    ADD CONSTRAINT pk_oasis_137 PRIMARY KEY (cd_funcionalidade, cd_menu);


--
-- TOC entry 4501 (class 2606 OID 18040)
-- Dependencies: 160 160 160 160
-- Name: pk_oasis_138; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_documentacao_projeto
    ADD CONSTRAINT pk_oasis_138 PRIMARY KEY (dt_documentacao_projeto, cd_projeto, cd_tipo_documentacao);


--
-- TOC entry 4499 (class 2606 OID 18042)
-- Dependencies: 159 159 159 159
-- Name: pk_oasis_139; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_documentacao_profissional
    ADD CONSTRAINT pk_oasis_139 PRIMARY KEY (dt_documentacao_profissional, cd_tipo_documentacao, cd_profissional);


--
-- TOC entry 4493 (class 2606 OID 18044)
-- Dependencies: 156 156 156 156 156
-- Name: pk_oasis_140; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_disponibilidade_servico_doc
    ADD CONSTRAINT pk_oasis_140 PRIMARY KEY (cd_disponibilidade_servico, cd_objeto, cd_tipo_documentacao, dt_doc_disponibilidade_servico);


--
-- TOC entry 4491 (class 2606 OID 18046)
-- Dependencies: 155 155 155
-- Name: pk_oasis_141; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_demanda_profissional
    ADD CONSTRAINT pk_oasis_141 PRIMARY KEY (cd_profissional, cd_demanda);


--
-- TOC entry 4489 (class 2606 OID 18048)
-- Dependencies: 154 154 154 154
-- Name: pk_oasis_142; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_demanda_prof_nivel_servico
    ADD CONSTRAINT pk_oasis_142 PRIMARY KEY (cd_demanda, cd_profissional, cd_nivel_servico);


--
-- TOC entry 4487 (class 2606 OID 18050)
-- Dependencies: 153 153 153 153
-- Name: pk_oasis_143; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_definicao_processo
    ADD CONSTRAINT pk_oasis_143 PRIMARY KEY (cd_perfil, cd_funcionalidade, st_definicao_processo);


--
-- TOC entry 4485 (class 2606 OID 18052)
-- Dependencies: 152 152 152 152 152 152
-- Name: pk_oasis_144; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_controle
    ADD CONSTRAINT pk_oasis_144 PRIMARY KEY (cd_controle, cd_projeto_previsto, cd_projeto, cd_proposta, cd_contrato);


--
-- TOC entry 4481 (class 2606 OID 18054)
-- Dependencies: 151 151 151
-- Name: pk_oasis_145; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_contrato_projeto
    ADD CONSTRAINT pk_oasis_145 PRIMARY KEY (cd_contrato, cd_projeto);


--
-- TOC entry 4475 (class 2606 OID 18056)
-- Dependencies: 149 149 149
-- Name: pk_oasis_146; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_contrato_evento
    ADD CONSTRAINT pk_oasis_146 PRIMARY KEY (cd_contrato, cd_evento);


--
-- TOC entry 4471 (class 2606 OID 18058)
-- Dependencies: 148 148 148
-- Name: pk_oasis_147; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_contrato_definicao_metrica
    ADD CONSTRAINT pk_oasis_147 PRIMARY KEY (cd_contrato, cd_definicao_metrica);


--
-- TOC entry 4467 (class 2606 OID 18060)
-- Dependencies: 147 147 147 147
-- Name: pk_oasis_148; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_conjunto_medida_medicao
    ADD CONSTRAINT pk_oasis_148 PRIMARY KEY (cd_conjunto_medida, cd_box_inicio, cd_medicao);


--
-- TOC entry 4465 (class 2606 OID 18062)
-- Dependencies: 146 146 146 146
-- Name: pk_oasis_149; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_conhecimento_projeto
    ADD CONSTRAINT pk_oasis_149 PRIMARY KEY (cd_tipo_conhecimento, cd_conhecimento, cd_projeto);


--
-- TOC entry 4462 (class 2606 OID 18064)
-- Dependencies: 145 145 145 145 145 145
-- Name: pk_oasis_150; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_baseline_item_controle
    ADD CONSTRAINT pk_oasis_150 PRIMARY KEY (cd_projeto, dt_baseline, cd_item_controle_baseline, cd_item_controlado, dt_versao_item_controlado);


--
-- TOC entry 4668 (class 2606 OID 18066)
-- Dependencies: 232 232
-- Name: pk_oasis_151; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_pergunta_pedido
    ADD CONSTRAINT pk_oasis_151 PRIMARY KEY (cd_pergunta_pedido);


--
-- TOC entry 4674 (class 2606 OID 18068)
-- Dependencies: 235 235
-- Name: pk_oasis_152; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_resposta_pedido
    ADD CONSTRAINT pk_oasis_152 PRIMARY KEY (cd_resposta_pedido);


--
-- TOC entry 4846 (class 2606 OID 18070)
-- Dependencies: 314 314
-- Name: pk_oasis_153; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_solicitacao_pedido
    ADD CONSTRAINT pk_oasis_153 PRIMARY KEY (cd_solicitacao_pedido);


--
-- TOC entry 4769 (class 2606 OID 18072)
-- Dependencies: 278 278
-- Name: pk_oasis_154; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico_pedido
    ADD CONSTRAINT pk_oasis_154 PRIMARY KEY (cd_historico_pedido);


--
-- TOC entry 4606 (class 2606 OID 18074)
-- Dependencies: 203 203 203 203
-- Name: pk_oasis_155; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_solicitacao_resposta_pedido
    ADD CONSTRAINT pk_oasis_155 PRIMARY KEY (cd_solicitacao_pedido, cd_pergunta_pedido, cd_resposta_pedido);


--
-- TOC entry 4717 (class 2606 OID 18076)
-- Dependencies: 254 254
-- Name: pk_oasis_156; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_arquivo_pedido
    ADD CONSTRAINT pk_oasis_156 PRIMARY KEY (cd_arquivo_pedido);


--
-- TOC entry 4539 (class 2606 OID 18078)
-- Dependencies: 177 177 177
-- Name: pk_oasis_157; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_opcao_resp_pergunta_pedido
    ADD CONSTRAINT pk_oasis_157 PRIMARY KEY (cd_pergunta_pedido, cd_resposta_pedido);


--
-- TOC entry 4557 (class 2606 OID 18080)
-- Dependencies: 184 184 184 184
-- Name: pk_oasis_158; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_pergunta_depende_resp_pedido
    ADD CONSTRAINT pk_oasis_158 PRIMARY KEY (cd_pergunta_depende, cd_pergunta_pedido, cd_resposta_pedido);


--
-- TOC entry 4497 (class 2606 OID 18082)
-- Dependencies: 158 158 158 158
-- Name: pk_oasis_159; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_documentacao_contrato
    ADD CONSTRAINT pk_oasis_159 PRIMARY KEY (dt_documentacao_contrato, cd_contrato, cd_tipo_documentacao);


--
-- TOC entry 4676 (class 2606 OID 18084)
-- Dependencies: 236 236
-- Name: pk_oasis_160; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_rotina
    ADD CONSTRAINT pk_oasis_160 PRIMARY KEY (cd_rotina);


--
-- TOC entry 4680 (class 2606 OID 18086)
-- Dependencies: 238 238
-- Name: pk_oasis_161; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE b_status_atendimento
    ADD CONSTRAINT pk_oasis_161 PRIMARY KEY (cd_status_atendimento);


--
-- TOC entry 4537 (class 2606 OID 18088)
-- Dependencies: 176 176 176
-- Name: pk_oasis_162; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_objeto_contrato_rotina
    ADD CONSTRAINT pk_oasis_162 PRIMARY KEY (cd_objeto, cd_rotina);


--
-- TOC entry 4604 (class 2606 OID 18090)
-- Dependencies: 202 202 202 202
-- Name: pk_oasis_163; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_rotina_profissional
    ADD CONSTRAINT pk_oasis_163 PRIMARY KEY (cd_objeto, cd_profissional, cd_rotina);


--
-- TOC entry 4747 (class 2606 OID 18092)
-- Dependencies: 269 269 269 269 269
-- Name: pk_oasis_164; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_execucao_rotina
    ADD CONSTRAINT pk_oasis_164 PRIMARY KEY (dt_execucao_rotina, cd_profissional, cd_objeto, cd_rotina);


--
-- TOC entry 4767 (class 2606 OID 18094)
-- Dependencies: 277 277 277 277 277 277
-- Name: pk_oasis_165; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_historico_execucao_rotina
    ADD CONSTRAINT pk_oasis_165 PRIMARY KEY (dt_historico_execucao_rotina, cd_rotina, cd_objeto, cd_profissional, dt_execucao_rotina);


--
-- TOC entry 4840 (class 2606 OID 18096)
-- Dependencies: 311 311 311
-- Name: pk_oasis_2005; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_reuniao_geral
    ADD CONSTRAINT pk_oasis_2005 PRIMARY KEY (cd_reuniao_geral, cd_objeto);


--
-- TOC entry 4600 (class 2606 OID 18098)
-- Dependencies: 200 200 200 200
-- Name: pk_oasis_2103; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_reuniao_geral_profissional
    ADD CONSTRAINT pk_oasis_2103 PRIMARY KEY (cd_objeto, cd_reuniao_geral, cd_profissional);


--
-- TOC entry 4477 (class 2606 OID 18100)
-- Dependencies: 150 150 150
-- Name: pk_oasis_item_inventario_145; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE a_contrato_item_inventario
    ADD CONSTRAINT pk_oasis_item_inventario_145 PRIMARY KEY (cd_contrato, cd_item_inventario);


--
-- TOC entry 4787 (class 2606 OID 18102)
-- Dependencies: 285 285
-- Name: s_inventario_pk; Type: CONSTRAINT; Schema: oasis; Owner: postgres; Tablespace: 
--

ALTER TABLE s_inventario
    ADD CONSTRAINT s_inventario_pk PRIMARY KEY (cd_inventario);


--
-- TOC entry 4711 (class 1259 OID 18103)
-- Dependencies: 253 253 253
-- Name: a_analise_risco_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX a_analise_risco_fkindex2 ON s_analise_risco (cd_item_risco, cd_etapa, cd_atividade);


--
-- TOC entry 4458 (class 1259 OID 18104)
-- Dependencies: 145 145
-- Name: a_baseline_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX a_baseline_fkindex2 ON a_baseline_item_controle (dt_baseline, cd_projeto);


--
-- TOC entry 4524 (class 1259 OID 18105)
-- Dependencies: 172
-- Name: a_conjunto_medida_medicao_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX a_conjunto_medida_medicao_fkindex2 ON a_medicao_medida (cd_medida);


--
-- TOC entry 4584 (class 1259 OID 18106)
-- Dependencies: 196 196 196 196
-- Name: a_questionario_analise_risco_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX a_questionario_analise_risco_fkindex2 ON a_questionario_analise_risco (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);


--
-- TOC entry 4617 (class 1259 OID 18107)
-- Dependencies: 209 209 209
-- Name: b_condicao_sub_item_metrica_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX b_condicao_sub_item_metrica_fkindex1 ON b_condicao_sub_item_metrica (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);


--
-- TOC entry 4525 (class 1259 OID 18108)
-- Dependencies: 172
-- Name: b_conjunto_medida_has_s_medicao_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX b_conjunto_medida_has_s_medicao_fkindex2 ON a_medicao_medida (cd_medicao);


--
-- TOC entry 4550 (class 1259 OID 18109)
-- Dependencies: 183
-- Name: b_perfil_profissional_has_b_papel_profissional_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX b_perfil_profissional_has_b_papel_profissional_fkindex1 ON a_perfil_prof_papel_prof (cd_perfil_profissional);


--
-- TOC entry 4551 (class 1259 OID 18110)
-- Dependencies: 183
-- Name: b_perfil_profissional_has_b_papel_profissional_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX b_perfil_profissional_has_b_papel_profissional_fkindex2 ON a_perfil_prof_papel_prof (cd_papel_profissional);


--
-- TOC entry 4641 (class 1259 OID 18111)
-- Dependencies: 220
-- Name: ifk_rel_05; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_05 ON b_item_metrica (cd_definicao_metrica);


--
-- TOC entry 4681 (class 1259 OID 18112)
-- Dependencies: 239 239
-- Name: ifk_rel_06; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_06 ON b_sub_item_metrica (cd_item_metrica, cd_definicao_metrica);


--
-- TOC entry 4618 (class 1259 OID 18113)
-- Dependencies: 209 209 209
-- Name: ifk_rel_08; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_08 ON b_condicao_sub_item_metrica (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);


--
-- TOC entry 4756 (class 1259 OID 18114)
-- Dependencies: 274 274 274 274 274
-- Name: ifk_rel_10; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_10 ON s_hist_prop_sub_item_metrica (cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);


--
-- TOC entry 4468 (class 1259 OID 18115)
-- Dependencies: 148
-- Name: ifk_rel_101; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_101 ON a_contrato_definicao_metrica (cd_contrato);


--
-- TOC entry 4526 (class 1259 OID 18116)
-- Dependencies: 172
-- Name: ifk_rel_11; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_11 ON a_medicao_medida (cd_medicao);


--
-- TOC entry 4469 (class 1259 OID 18117)
-- Dependencies: 148
-- Name: ifk_rel_112; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_112 ON a_contrato_definicao_metrica (cd_definicao_metrica);


--
-- TOC entry 4757 (class 1259 OID 18118)
-- Dependencies: 274 274 274
-- Name: ifk_rel_116; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_116 ON s_hist_prop_sub_item_metrica (dt_historico_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 4572 (class 1259 OID 18119)
-- Dependencies: 192
-- Name: ifk_rel_12; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_12 ON a_proposta_definicao_metrica (cd_definicao_metrica);


--
-- TOC entry 4478 (class 1259 OID 18120)
-- Dependencies: 151
-- Name: ifk_rel_13; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_13 ON a_contrato_projeto (cd_contrato);


--
-- TOC entry 4479 (class 1259 OID 18121)
-- Dependencies: 151
-- Name: ifk_rel_14; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_14 ON a_contrato_projeto (cd_projeto);


--
-- TOC entry 4774 (class 1259 OID 18122)
-- Dependencies: 281 281 281
-- Name: ifk_rel_15; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_15 ON s_historico_proposta_metrica (dt_historico_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 4775 (class 1259 OID 18123)
-- Dependencies: 281 281 281
-- Name: ifk_rel_16; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_16 ON s_historico_proposta_metrica (cd_projeto, cd_proposta, cd_definicao_metrica);


--
-- TOC entry 4459 (class 1259 OID 18124)
-- Dependencies: 145
-- Name: ifk_rel_52; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_52 ON a_baseline_item_controle (cd_item_controle_baseline);


--
-- TOC entry 4527 (class 1259 OID 18125)
-- Dependencies: 172
-- Name: ifk_rel_54; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_54 ON a_medicao_medida (cd_medida);


--
-- TOC entry 4712 (class 1259 OID 18126)
-- Dependencies: 253 253 253
-- Name: ifk_rel_56; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_56 ON s_analise_risco (cd_item_risco, cd_etapa, cd_atividade);


--
-- TOC entry 4585 (class 1259 OID 18127)
-- Dependencies: 196 196 196 196
-- Name: ifk_rel_58; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_58 ON a_questionario_analise_risco (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);


--
-- TOC entry 4460 (class 1259 OID 18128)
-- Dependencies: 145 145
-- Name: ifk_rel_61; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_61 ON a_baseline_item_controle (dt_baseline, cd_projeto);


--
-- TOC entry 4586 (class 1259 OID 18129)
-- Dependencies: 196 196 196 196 196 196
-- Name: ifk_rel_63; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_63 ON a_questionario_analise_risco (dt_analise_risco, cd_projeto, cd_proposta, cd_atividade, cd_etapa, cd_item_risco);


--
-- TOC entry 4552 (class 1259 OID 18130)
-- Dependencies: 183
-- Name: ifk_rel_64; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_64 ON a_perfil_prof_papel_prof (cd_papel_profissional);


--
-- TOC entry 4591 (class 1259 OID 18131)
-- Dependencies: 198 198 198
-- Name: ifk_rel_69; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_69 ON a_requisito_caso_de_uso (cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4592 (class 1259 OID 18132)
-- Dependencies: 198 198 198 198
-- Name: ifk_rel_70; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_70 ON a_requisito_caso_de_uso (cd_projeto, cd_modulo, cd_caso_de_uso, dt_versao_caso_de_uso);


--
-- TOC entry 4573 (class 1259 OID 18133)
-- Dependencies: 192 192
-- Name: ifk_rel_91; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_91 ON a_proposta_definicao_metrica (cd_proposta, cd_projeto);


--
-- TOC entry 4553 (class 1259 OID 18134)
-- Dependencies: 183
-- Name: ifk_rel_93; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ifk_rel_93 ON a_perfil_prof_papel_prof (cd_perfil_profissional);


--
-- TOC entry 4830 (class 1259 OID 18135)
-- Dependencies: 307
-- Name: ix_ak_proposta_ni_solicitacao; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX ix_ak_proposta_ni_solicitacao ON s_proposta (ni_solicitacao);


--
-- TOC entry 4463 (class 1259 OID 18136)
-- Dependencies: 145
-- Name: s_baseline_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_baseline_fkindex1 ON a_baseline_item_controle (cd_item_controle_baseline);


--
-- TOC entry 4472 (class 1259 OID 18137)
-- Dependencies: 148
-- Name: s_contrato_has_b_definicao_metrica_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_contrato_has_b_definicao_metrica_fkindex1 ON a_contrato_definicao_metrica (cd_contrato);


--
-- TOC entry 4473 (class 1259 OID 18138)
-- Dependencies: 148
-- Name: s_contrato_has_b_definicao_metrica_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_contrato_has_b_definicao_metrica_fkindex2 ON a_contrato_definicao_metrica (cd_definicao_metrica);


--
-- TOC entry 4482 (class 1259 OID 18139)
-- Dependencies: 151
-- Name: s_contrato_has_s_projeto_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_contrato_has_s_projeto_fkindex1 ON a_contrato_projeto (cd_contrato);


--
-- TOC entry 4483 (class 1259 OID 18140)
-- Dependencies: 151
-- Name: s_contrato_has_s_projeto_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_contrato_has_s_projeto_fkindex2 ON a_contrato_projeto (cd_projeto);


--
-- TOC entry 4760 (class 1259 OID 18141)
-- Dependencies: 274 274 274
-- Name: s_historico_metrica_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_historico_metrica_fkindex1 ON s_hist_prop_sub_item_metrica (dt_historico_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 4761 (class 1259 OID 18142)
-- Dependencies: 274 274 274 274 274
-- Name: s_historico_metrica_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_historico_metrica_fkindex2 ON s_hist_prop_sub_item_metrica (cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);


--
-- TOC entry 4778 (class 1259 OID 18143)
-- Dependencies: 281 281 281
-- Name: s_historico_proposta_has_a_proposta_definicao_metrica_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_historico_proposta_has_a_proposta_definicao_metrica_fkindex1 ON s_historico_proposta_metrica (dt_historico_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 4779 (class 1259 OID 18144)
-- Dependencies: 281 281 281
-- Name: s_historico_proposta_has_a_proposta_definicao_metrica_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_historico_proposta_has_a_proposta_definicao_metrica_fkindex2 ON s_historico_proposta_metrica (cd_projeto, cd_proposta, cd_definicao_metrica);


--
-- TOC entry 4644 (class 1259 OID 18145)
-- Dependencies: 220
-- Name: s_item_metrica_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_item_metrica_fkindex1 ON b_item_metrica (cd_definicao_metrica);


--
-- TOC entry 4576 (class 1259 OID 18146)
-- Dependencies: 192 192
-- Name: s_proposta_has_b_definicao_metrica_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_proposta_has_b_definicao_metrica_fkindex1 ON a_proposta_definicao_metrica (cd_proposta, cd_projeto);


--
-- TOC entry 4577 (class 1259 OID 18147)
-- Dependencies: 192
-- Name: s_proposta_has_b_definicao_metrica_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_proposta_has_b_definicao_metrica_fkindex2 ON a_proposta_definicao_metrica (cd_definicao_metrica);


--
-- TOC entry 4715 (class 1259 OID 18148)
-- Dependencies: 253 253
-- Name: s_proposta_has_b_item_risco_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_proposta_has_b_item_risco_fkindex1 ON s_analise_risco (cd_proposta, cd_projeto);


--
-- TOC entry 4595 (class 1259 OID 18149)
-- Dependencies: 198 198 198
-- Name: s_requisito_has_s_caso_de_uso_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_requisito_has_s_caso_de_uso_fkindex1 ON a_requisito_caso_de_uso (cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4596 (class 1259 OID 18150)
-- Dependencies: 198 198 198 198
-- Name: s_requisito_has_s_caso_de_uso_fkindex2; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_requisito_has_s_caso_de_uso_fkindex2 ON a_requisito_caso_de_uso (cd_projeto, cd_modulo, cd_caso_de_uso, dt_versao_caso_de_uso);


--
-- TOC entry 4684 (class 1259 OID 18151)
-- Dependencies: 239 239
-- Name: s_sub_item_metrica_fkindex1; Type: INDEX; Schema: oasis; Owner: postgres; Tablespace: 
--

CREATE INDEX s_sub_item_metrica_fkindex1 ON b_sub_item_metrica (cd_item_metrica, cd_definicao_metrica);


--
-- TOC entry 4890 (class 2606 OID 18152)
-- Dependencies: 165 219 4639
-- Name: a_item_inventariado_fk1; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_fk1 FOREIGN KEY (cd_item_inventario) REFERENCES b_item_inventario(cd_item_inventario);


--
-- TOC entry 4889 (class 2606 OID 18157)
-- Dependencies: 165 285 4786
-- Name: a_item_inventariado_fk2; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_inventariado
    ADD CONSTRAINT a_item_inventariado_fk2 FOREIGN KEY (cd_inventario) REFERENCES s_inventario(cd_inventario);


--
-- TOC entry 4987 (class 2606 OID 18162)
-- Dependencies: 219 205 4609
-- Name: b_item_inventario_fk1; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_item_inventario
    ADD CONSTRAINT b_item_inventario_fk1 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);


--
-- TOC entry 4999 (class 2606 OID 18167)
-- Dependencies: 240 240 241 241 4687
-- Name: b_subitem_inv_descri_fk1; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_subitem_inv_descri
    ADD CONSTRAINT b_subitem_inv_descri_fk1 FOREIGN KEY (cd_item_inventario, cd_subitem_inventario) REFERENCES b_subitem_inventario(cd_item_inventario, cd_subitem_inventario);


--
-- TOC entry 5000 (class 2606 OID 18172)
-- Dependencies: 241 219 4639
-- Name: b_subitem_inventario_fk1; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_subitem_inventario
    ADD CONSTRAINT b_subitem_inventario_fk1 FOREIGN KEY (cd_item_inventario) REFERENCES b_item_inventario(cd_item_inventario);


--
-- TOC entry 4875 (class 2606 OID 18177)
-- Dependencies: 157 244 4693
-- Name: doc_projeto_continuo_fk1; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_fk1 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);


--
-- TOC entry 4874 (class 2606 OID 18182)
-- Dependencies: 157 157 305 305 4826
-- Name: doc_projeto_continuo_fk2; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_doc_projeto_continuo
    ADD CONSTRAINT doc_projeto_continuo_fk2 FOREIGN KEY (cd_projeto_continuado, cd_objeto) REFERENCES s_projeto_continuado(cd_projeto_continuado, cd_objeto);


--
-- TOC entry 5096 (class 2606 OID 18187)
-- Dependencies: 316 247 4699
-- Name: fk_oasis_001; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_usuario_pedido
    ADD CONSTRAINT fk_oasis_001 FOREIGN KEY (cd_unidade_usuario) REFERENCES b_unidade(cd_unidade);


--
-- TOC entry 5095 (class 2606 OID 18192)
-- Dependencies: 315 304 4824
-- Name: fk_oasis_002; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_tabela
    ADD CONSTRAINT fk_oasis_002 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5092 (class 2606 OID 18197)
-- Dependencies: 313 303 4822
-- Name: fk_oasis_003; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_solicitacao
    ADD CONSTRAINT fk_oasis_003 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 5091 (class 2606 OID 18202)
-- Dependencies: 313 291 4798
-- Name: fk_oasis_004; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_solicitacao
    ADD CONSTRAINT fk_oasis_004 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 5090 (class 2606 OID 18207)
-- Dependencies: 313 247 4699
-- Name: fk_oasis_005; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_solicitacao
    ADD CONSTRAINT fk_oasis_005 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);


--
-- TOC entry 5089 (class 2606 OID 18212)
-- Dependencies: 312 304 4824
-- Name: fk_oasis_006; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_situacao_projeto
    ADD CONSTRAINT fk_oasis_006 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5087 (class 2606 OID 18217)
-- Dependencies: 310 304 4824
-- Name: fk_oasis_007; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_reuniao
    ADD CONSTRAINT fk_oasis_007 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5086 (class 2606 OID 18222)
-- Dependencies: 309 304 4824
-- Name: fk_oasis_008; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_requisito
    ADD CONSTRAINT fk_oasis_008 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5085 (class 2606 OID 18227)
-- Dependencies: 308 304 4824
-- Name: fk_oasis_009; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_regra_negocio
    ADD CONSTRAINT fk_oasis_009 FOREIGN KEY (cd_projeto_regra_negocio) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5084 (class 2606 OID 18232)
-- Dependencies: 307 307 307 313 313 313 4843
-- Name: fk_oasis_010; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_proposta
    ADD CONSTRAINT fk_oasis_010 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto) REFERENCES s_solicitacao(ni_solicitacao, ni_ano_solicitacao, cd_objeto);


--
-- TOC entry 5083 (class 2606 OID 18237)
-- Dependencies: 307 304 4824
-- Name: fk_oasis_011; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_proposta
    ADD CONSTRAINT fk_oasis_011 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5082 (class 2606 OID 18242)
-- Dependencies: 306 247 4699
-- Name: fk_oasis_012; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_projeto_previsto
    ADD CONSTRAINT fk_oasis_012 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);


--
-- TOC entry 5081 (class 2606 OID 18247)
-- Dependencies: 306 264 4736
-- Name: fk_oasis_013; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_projeto_previsto
    ADD CONSTRAINT fk_oasis_013 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 5080 (class 2606 OID 18252)
-- Dependencies: 305 291 4798
-- Name: fk_oasis_014; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_projeto_continuado
    ADD CONSTRAINT fk_oasis_014 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 5079 (class 2606 OID 18257)
-- Dependencies: 304 247 4699
-- Name: fk_oasis_015; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_projeto
    ADD CONSTRAINT fk_oasis_015 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);


--
-- TOC entry 5078 (class 2606 OID 18262)
-- Dependencies: 304 237 4677
-- Name: fk_oasis_016; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_projeto
    ADD CONSTRAINT fk_oasis_016 FOREIGN KEY (cd_status) REFERENCES b_status(cd_status);


--
-- TOC entry 5077 (class 2606 OID 18267)
-- Dependencies: 304 303 4822
-- Name: fk_oasis_017; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_projeto
    ADD CONSTRAINT fk_oasis_017 FOREIGN KEY (cd_profissional_gerente) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 5076 (class 2606 OID 18272)
-- Dependencies: 303 234 4671
-- Name: fk_oasis_018; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_profissional
    ADD CONSTRAINT fk_oasis_018 FOREIGN KEY (cd_relacao_contratual) REFERENCES b_relacao_contratual(cd_relacao_contratual);


--
-- TOC entry 5075 (class 2606 OID 18277)
-- Dependencies: 303 230 4663
-- Name: fk_oasis_019; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_profissional
    ADD CONSTRAINT fk_oasis_019 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);


--
-- TOC entry 5074 (class 2606 OID 18282)
-- Dependencies: 303 268 4744
-- Name: fk_oasis_020; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_profissional
    ADD CONSTRAINT fk_oasis_020 FOREIGN KEY (cd_empresa) REFERENCES s_empresa(cd_empresa);


--
-- TOC entry 5073 (class 2606 OID 18287)
-- Dependencies: 302 245 4695
-- Name: fk_oasis_021; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_produto_parcela
    ADD CONSTRAINT fk_oasis_021 FOREIGN KEY (cd_tipo_produto) REFERENCES b_tipo_produto(cd_tipo_produto);


--
-- TOC entry 5072 (class 2606 OID 18292)
-- Dependencies: 302 302 302 293 293 293 4802
-- Name: fk_oasis_022; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_produto_parcela
    ADD CONSTRAINT fk_oasis_022 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta) REFERENCES s_parcela(cd_parcela, cd_projeto, cd_proposta);


--
-- TOC entry 5071 (class 2606 OID 18297)
-- Dependencies: 301 301 307 307 4831
-- Name: fk_oasis_023; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_processamento_proposta
    ADD CONSTRAINT fk_oasis_023 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 5070 (class 2606 OID 18302)
-- Dependencies: 300 300 300 293 293 293 4802
-- Name: fk_oasis_024; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_processamento_parcela
    ADD CONSTRAINT fk_oasis_024 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta) REFERENCES s_parcela(cd_parcela, cd_projeto, cd_proposta);


--
-- TOC entry 5069 (class 2606 OID 18307)
-- Dependencies: 299 304 4824
-- Name: fk_oasis_025; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_previsao_projeto_diario
    ADD CONSTRAINT fk_oasis_025 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5068 (class 2606 OID 18312)
-- Dependencies: 298 304 4824
-- Name: fk_oasis_026; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_pre_projeto_evolutivo
    ADD CONSTRAINT fk_oasis_026 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5065 (class 2606 OID 18317)
-- Dependencies: 296 296 296 313 313 313 4843
-- Name: fk_oasis_027; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_pre_demanda
    ADD CONSTRAINT fk_oasis_027 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto_receptor) REFERENCES s_solicitacao(ni_solicitacao, ni_ano_solicitacao, cd_objeto);


--
-- TOC entry 5064 (class 2606 OID 18322)
-- Dependencies: 296 291 4798
-- Name: fk_oasis_028; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_pre_demanda
    ADD CONSTRAINT fk_oasis_028 FOREIGN KEY (cd_objeto_emissor) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 5063 (class 2606 OID 18327)
-- Dependencies: 296 247 4699
-- Name: fk_oasis_029; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_pre_demanda
    ADD CONSTRAINT fk_oasis_029 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);


--
-- TOC entry 5062 (class 2606 OID 18332)
-- Dependencies: 295 295 307 307 4831
-- Name: fk_oasis_030; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_plano_implantacao
    ADD CONSTRAINT fk_oasis_030 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 5061 (class 2606 OID 18337)
-- Dependencies: 294 294 229 229 4661
-- Name: fk_oasis_031; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_penalizacao
    ADD CONSTRAINT fk_oasis_031 FOREIGN KEY (cd_penalidade, cd_contrato) REFERENCES b_penalidade(cd_penalidade, cd_contrato);


--
-- TOC entry 5060 (class 2606 OID 18342)
-- Dependencies: 293 293 307 307 4831
-- Name: fk_oasis_032; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_parcela
    ADD CONSTRAINT fk_oasis_032 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 5058 (class 2606 OID 18347)
-- Dependencies: 291 264 4736
-- Name: fk_oasis_033; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_objeto_contrato
    ADD CONSTRAINT fk_oasis_033 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 5057 (class 2606 OID 18352)
-- Dependencies: 290 290 305 305 4826
-- Name: fk_oasis_034; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_modulo_continuado
    ADD CONSTRAINT fk_oasis_034 FOREIGN KEY (cd_projeto_continuado, cd_objeto) REFERENCES s_projeto_continuado(cd_projeto_continuado, cd_objeto);


--
-- TOC entry 5056 (class 2606 OID 18357)
-- Dependencies: 289 237 4677
-- Name: fk_oasis_035; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_modulo
    ADD CONSTRAINT fk_oasis_035 FOREIGN KEY (cd_status) REFERENCES b_status(cd_status);


--
-- TOC entry 5055 (class 2606 OID 18362)
-- Dependencies: 289 304 4824
-- Name: fk_oasis_036; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_modulo
    ADD CONSTRAINT fk_oasis_036 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5054 (class 2606 OID 18367)
-- Dependencies: 288 230 4663
-- Name: fk_oasis_037; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_mensageria
    ADD CONSTRAINT fk_oasis_037 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);


--
-- TOC entry 5053 (class 2606 OID 18372)
-- Dependencies: 288 291 4798
-- Name: fk_oasis_038; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_mensageria
    ADD CONSTRAINT fk_oasis_038 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 5050 (class 2606 OID 18377)
-- Dependencies: 284 284 284 284 258 258 258 258 4724
-- Name: fk_oasis_039; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_interacao
    ADD CONSTRAINT fk_oasis_039 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);


--
-- TOC entry 5049 (class 2606 OID 18382)
-- Dependencies: 284 255 4718
-- Name: fk_oasis_040; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_interacao
    ADD CONSTRAINT fk_oasis_040 FOREIGN KEY (cd_ator) REFERENCES s_ator(cd_ator);


--
-- TOC entry 5048 (class 2606 OID 18387)
-- Dependencies: 283 245 4695
-- Name: fk_oasis_042; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_proposta_produto
    ADD CONSTRAINT fk_oasis_042 FOREIGN KEY (cd_tipo_produto) REFERENCES b_tipo_produto(cd_tipo_produto);


--
-- TOC entry 5047 (class 2606 OID 18392)
-- Dependencies: 283 283 283 283 282 282 282 282 4780
-- Name: fk_oasis_043; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_proposta_produto
    ADD CONSTRAINT fk_oasis_043 FOREIGN KEY (cd_proposta, cd_projeto, dt_historico_proposta, cd_historico_proposta_parcela) REFERENCES s_historico_proposta_parcela(cd_proposta, cd_projeto, dt_historico_proposta, cd_historico_proposta_parcela);


--
-- TOC entry 5046 (class 2606 OID 18397)
-- Dependencies: 282 282 282 280 280 280 4772
-- Name: fk_oasis_044; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_proposta_parcela
    ADD CONSTRAINT fk_oasis_044 FOREIGN KEY (dt_historico_proposta, cd_projeto, cd_proposta) REFERENCES s_historico_proposta(dt_historico_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 5045 (class 2606 OID 18402)
-- Dependencies: 281 281 281 280 280 280 4772
-- Name: fk_oasis_045; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_proposta_metrica
    ADD CONSTRAINT fk_oasis_045 FOREIGN KEY (dt_historico_proposta, cd_projeto, cd_proposta) REFERENCES s_historico_proposta(dt_historico_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 5044 (class 2606 OID 18407)
-- Dependencies: 280 280 307 307 4831
-- Name: fk_oasis_047; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_proposta
    ADD CONSTRAINT fk_oasis_047 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 5043 (class 2606 OID 18412)
-- Dependencies: 279 279 279 290 290 290 4796
-- Name: fk_oasis_048; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_projeto_continuado
    ADD CONSTRAINT fk_oasis_048 FOREIGN KEY (cd_modulo_continuado, cd_objeto, cd_projeto_continuado) REFERENCES s_modulo_continuado(cd_modulo_continuado, cd_objeto, cd_projeto_continuado);


--
-- TOC entry 5042 (class 2606 OID 18417)
-- Dependencies: 279 279 207 207 4613
-- Name: fk_oasis_049; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_projeto_continuado
    ADD CONSTRAINT fk_oasis_049 FOREIGN KEY (cd_atividade, cd_etapa) REFERENCES b_atividade(cd_atividade, cd_etapa);


--
-- TOC entry 5039 (class 2606 OID 18422)
-- Dependencies: 276 276 276 154 154 154 4488
-- Name: fk_oasis_050; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_execucao_demanda
    ADD CONSTRAINT fk_oasis_050 FOREIGN KEY (cd_demanda, cd_profissional, cd_nivel_servico) REFERENCES a_demanda_prof_nivel_servico(cd_demanda, cd_profissional, cd_nivel_servico);


--
-- TOC entry 5038 (class 2606 OID 18427)
-- Dependencies: 275 275 207 207 4613
-- Name: fk_oasis_051; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico
    ADD CONSTRAINT fk_oasis_051 FOREIGN KEY (cd_atividade, cd_etapa) REFERENCES b_atividade(cd_atividade, cd_etapa);


--
-- TOC entry 5037 (class 2606 OID 18432)
-- Dependencies: 274 274 274 274 274 194 194 194 194 194 4580
-- Name: fk_oasis_052; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_hist_prop_sub_item_metrica
    ADD CONSTRAINT fk_oasis_052 FOREIGN KEY (cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica) REFERENCES a_proposta_sub_item_metrica(cd_projeto, cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica);


--
-- TOC entry 5036 (class 2606 OID 18437)
-- Dependencies: 274 274 274 280 280 280 4772
-- Name: fk_oasis_053; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_hist_prop_sub_item_metrica
    ADD CONSTRAINT fk_oasis_053 FOREIGN KEY (dt_historico_proposta, cd_projeto, cd_proposta) REFERENCES s_historico_proposta(dt_historico_proposta, cd_projeto, cd_proposta);


--
-- TOC entry 5035 (class 2606 OID 18442)
-- Dependencies: 273 273 307 307 4831
-- Name: fk_oasis_054; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_gerencia_qualidade
    ADD CONSTRAINT fk_oasis_054 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 5034 (class 2606 OID 18447)
-- Dependencies: 273 303 4822
-- Name: fk_oasis_055; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_gerencia_qualidade
    ADD CONSTRAINT fk_oasis_055 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 5033 (class 2606 OID 18452)
-- Dependencies: 271 271 271 270 270 270 4748
-- Name: fk_oasis_056; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_extrato_mensal_parcela
    ADD CONSTRAINT fk_oasis_056 FOREIGN KEY (ni_mes_extrato, ni_ano_extrato, cd_contrato) REFERENCES s_extrato_mensal(ni_mes_extrato, ni_ano_extrato, cd_contrato);


--
-- TOC entry 5032 (class 2606 OID 18457)
-- Dependencies: 271 271 271 293 293 293 4802
-- Name: fk_oasis_057; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_extrato_mensal_parcela
    ADD CONSTRAINT fk_oasis_057 FOREIGN KEY (cd_parcela, cd_projeto, cd_proposta) REFERENCES s_parcela(cd_parcela, cd_projeto, cd_proposta);


--
-- TOC entry 5031 (class 2606 OID 18462)
-- Dependencies: 270 264 4736
-- Name: fk_oasis_058; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_extrato_mensal
    ADD CONSTRAINT fk_oasis_058 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 5029 (class 2606 OID 18467)
-- Dependencies: 267 291 4798
-- Name: fk_oasis_059; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_disponibilidade_servico
    ADD CONSTRAINT fk_oasis_059 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 5028 (class 2606 OID 18472)
-- Dependencies: 266 266 266 313 313 313 4843
-- Name: fk_oasis_060; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_demanda
    ADD CONSTRAINT fk_oasis_060 FOREIGN KEY (ni_solicitacao, ni_ano_solicitacao, cd_objeto) REFERENCES s_solicitacao(ni_solicitacao, ni_ano_solicitacao, cd_objeto);


--
-- TOC entry 5027 (class 2606 OID 18477)
-- Dependencies: 266 247 4699
-- Name: fk_oasis_061; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_demanda
    ADD CONSTRAINT fk_oasis_061 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);


--
-- TOC entry 5024 (class 2606 OID 18482)
-- Dependencies: 264 268 4744
-- Name: fk_oasis_062; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_contrato
    ADD CONSTRAINT fk_oasis_062 FOREIGN KEY (cd_empresa) REFERENCES s_empresa(cd_empresa);


--
-- TOC entry 5023 (class 2606 OID 18487)
-- Dependencies: 264 264 263 263 4734
-- Name: fk_oasis_063; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_contrato
    ADD CONSTRAINT fk_oasis_063 FOREIGN KEY (cd_contato_empresa, cd_empresa) REFERENCES s_contato_empresa(cd_contato_empresa, cd_empresa);


--
-- TOC entry 5022 (class 2606 OID 18492)
-- Dependencies: 263 268 4744
-- Name: fk_oasis_064; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_contato_empresa
    ADD CONSTRAINT fk_oasis_064 FOREIGN KEY (cd_empresa) REFERENCES s_empresa(cd_empresa);


--
-- TOC entry 5020 (class 2606 OID 18497)
-- Dependencies: 261 261 261 261 258 258 258 258 4724
-- Name: fk_oasis_065; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_condicao
    ADD CONSTRAINT fk_oasis_065 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);


--
-- TOC entry 5019 (class 2606 OID 18502)
-- Dependencies: 260 260 260 260 258 258 258 258 4724
-- Name: fk_oasis_066; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_complemento
    ADD CONSTRAINT fk_oasis_066 FOREIGN KEY (cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_caso_de_uso, cd_projeto, cd_modulo, dt_versao_caso_de_uso);


--
-- TOC entry 5018 (class 2606 OID 18507)
-- Dependencies: 259 259 315 315 4847
-- Name: fk_oasis_067; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_coluna
    ADD CONSTRAINT fk_oasis_067 FOREIGN KEY (tx_tabela_referencia, cd_projeto_referencia) REFERENCES s_tabela(tx_tabela, cd_projeto);


--
-- TOC entry 5017 (class 2606 OID 18512)
-- Dependencies: 259 259 315 315 4847
-- Name: fk_oasis_068; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_coluna
    ADD CONSTRAINT fk_oasis_068 FOREIGN KEY (tx_tabela, cd_projeto) REFERENCES s_tabela(tx_tabela, cd_projeto);


--
-- TOC entry 5016 (class 2606 OID 18517)
-- Dependencies: 258 258 289 289 4794
-- Name: fk_oasis_069; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_caso_de_uso
    ADD CONSTRAINT fk_oasis_069 FOREIGN KEY (cd_modulo, cd_projeto) REFERENCES s_modulo(cd_modulo, cd_projeto);


--
-- TOC entry 5015 (class 2606 OID 18522)
-- Dependencies: 257 304 4824
-- Name: fk_oasis_070; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_baseline
    ADD CONSTRAINT fk_oasis_070 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5014 (class 2606 OID 18527)
-- Dependencies: 256 206 4611
-- Name: fk_oasis_071; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_base_conhecimento
    ADD CONSTRAINT fk_oasis_071 FOREIGN KEY (cd_area_conhecimento) REFERENCES b_area_conhecimento(cd_area_conhecimento);


--
-- TOC entry 5013 (class 2606 OID 18532)
-- Dependencies: 255 304 4824
-- Name: fk_oasis_072; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_ator
    ADD CONSTRAINT fk_oasis_072 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5011 (class 2606 OID 18537)
-- Dependencies: 253 253 307 307 4831
-- Name: fk_oasis_073; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_analise_risco
    ADD CONSTRAINT fk_oasis_073 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 5010 (class 2606 OID 18542)
-- Dependencies: 253 303 4822
-- Name: fk_oasis_074; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_analise_risco
    ADD CONSTRAINT fk_oasis_074 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 5009 (class 2606 OID 18547)
-- Dependencies: 253 253 253 222 222 222 4647
-- Name: fk_oasis_075; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_analise_risco
    ADD CONSTRAINT fk_oasis_075 FOREIGN KEY (cd_item_risco, cd_etapa, cd_atividade) REFERENCES b_item_risco(cd_item_risco, cd_etapa, cd_atividade);


--
-- TOC entry 5008 (class 2606 OID 18552)
-- Dependencies: 252 303 4822
-- Name: fk_oasis_076; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_analise_medicao
    ADD CONSTRAINT fk_oasis_076 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 5007 (class 2606 OID 18557)
-- Dependencies: 252 287 4790
-- Name: fk_oasis_077; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_analise_medicao
    ADD CONSTRAINT fk_oasis_077 FOREIGN KEY (cd_medicao) REFERENCES s_medicao(cd_medicao);


--
-- TOC entry 5006 (class 2606 OID 18562)
-- Dependencies: 252 208 4615
-- Name: fk_oasis_078; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_analise_medicao
    ADD CONSTRAINT fk_oasis_078 FOREIGN KEY (cd_box_inicio) REFERENCES b_box_inicio(cd_box_inicio);


--
-- TOC entry 5005 (class 2606 OID 18567)
-- Dependencies: 251 304 4824
-- Name: fk_oasis_079; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_analise_matriz_rastreab
    ADD CONSTRAINT fk_oasis_079 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5004 (class 2606 OID 18572)
-- Dependencies: 250 304 4824
-- Name: fk_oasis_080; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_analise_execucao_projeto
    ADD CONSTRAINT fk_oasis_080 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5003 (class 2606 OID 18577)
-- Dependencies: 249 249 295 295 4806
-- Name: fk_oasis_081; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_agenda_plano_implantacao
    ADD CONSTRAINT fk_oasis_081 FOREIGN KEY (cd_projeto, cd_proposta) REFERENCES s_plano_implantacao(cd_projeto, cd_proposta);


--
-- TOC entry 5001 (class 2606 OID 18582)
-- Dependencies: 245 212 4625
-- Name: fk_oasis_082; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_tipo_produto
    ADD CONSTRAINT fk_oasis_082 FOREIGN KEY (cd_definicao_metrica) REFERENCES b_definicao_metrica(cd_definicao_metrica);


--
-- TOC entry 4998 (class 2606 OID 18587)
-- Dependencies: 239 239 220 220 4642
-- Name: fk_oasis_083; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_sub_item_metrica
    ADD CONSTRAINT fk_oasis_083 FOREIGN KEY (cd_item_metrica, cd_definicao_metrica) REFERENCES b_item_metrica(cd_item_metrica, cd_definicao_metrica);


--
-- TOC entry 4996 (class 2606 OID 18592)
-- Dependencies: 233 233 233 222 222 222 4647
-- Name: fk_oasis_084; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_questao_analise_risco
    ADD CONSTRAINT fk_oasis_084 FOREIGN KEY (cd_item_risco, cd_etapa, cd_atividade) REFERENCES b_item_risco(cd_item_risco, cd_etapa, cd_atividade);


--
-- TOC entry 4995 (class 2606 OID 18597)
-- Dependencies: 231 205 4609
-- Name: fk_oasis_085; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_perfil_profissional
    ADD CONSTRAINT fk_oasis_085 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);


--
-- TOC entry 4994 (class 2606 OID 18602)
-- Dependencies: 229 264 4736
-- Name: fk_oasis_086; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_penalidade
    ADD CONSTRAINT fk_oasis_086 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 4993 (class 2606 OID 18607)
-- Dependencies: 228 205 4609
-- Name: fk_oasis_087; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_papel_profissional
    ADD CONSTRAINT fk_oasis_087 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);


--
-- TOC entry 4992 (class 2606 OID 55067)
-- Dependencies: 291 227 4798
-- Name: fk_oasis_088; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_nivel_servico
    ADD CONSTRAINT fk_oasis_088 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4990 (class 2606 OID 18617)
-- Dependencies: 225 225 4653
-- Name: fk_oasis_089; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_menu
    ADD CONSTRAINT fk_oasis_089 FOREIGN KEY (cd_menu_pai) REFERENCES b_menu(cd_menu);


--
-- TOC entry 4989 (class 2606 OID 18622)
-- Dependencies: 222 222 207 207 4613
-- Name: fk_oasis_090; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_item_risco
    ADD CONSTRAINT fk_oasis_090 FOREIGN KEY (cd_atividade, cd_etapa) REFERENCES b_atividade(cd_atividade, cd_etapa);


--
-- TOC entry 4988 (class 2606 OID 18627)
-- Dependencies: 220 212 4625
-- Name: fk_oasis_091; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_item_metrica
    ADD CONSTRAINT fk_oasis_091 FOREIGN KEY (cd_definicao_metrica) REFERENCES b_definicao_metrica(cd_definicao_metrica);


--
-- TOC entry 4985 (class 2606 OID 18632)
-- Dependencies: 213 205 4609
-- Name: fk_oasis_093; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_etapa
    ADD CONSTRAINT fk_oasis_093 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);


--
-- TOC entry 4984 (class 2606 OID 18637)
-- Dependencies: 210 242 4689
-- Name: fk_oasis_094; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_conhecimento
    ADD CONSTRAINT fk_oasis_094 FOREIGN KEY (cd_tipo_conhecimento) REFERENCES b_tipo_conhecimento(cd_tipo_conhecimento);


--
-- TOC entry 4983 (class 2606 OID 18642)
-- Dependencies: 209 209 209 239 239 239 4682
-- Name: fk_oasis_095; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_condicao_sub_item_metrica
    ADD CONSTRAINT fk_oasis_095 FOREIGN KEY (cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica) REFERENCES b_sub_item_metrica(cd_sub_item_metrica, cd_definicao_metrica, cd_item_metrica);


--
-- TOC entry 4982 (class 2606 OID 18647)
-- Dependencies: 207 213 4627
-- Name: fk_oasis_096; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_atividade
    ADD CONSTRAINT fk_oasis_096 FOREIGN KEY (cd_etapa) REFERENCES b_etapa(cd_etapa);


--
-- TOC entry 4981 (class 2606 OID 18652)
-- Dependencies: 204 246 4697
-- Name: fk_oasis_097; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_treinamento_profissional
    ADD CONSTRAINT fk_oasis_097 FOREIGN KEY (cd_treinamento) REFERENCES b_treinamento(cd_treinamento);


--
-- TOC entry 4980 (class 2606 OID 18657)
-- Dependencies: 204 303 4822
-- Name: fk_oasis_098; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_treinamento_profissional
    ADD CONSTRAINT fk_oasis_098 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4975 (class 2606 OID 18662)
-- Dependencies: 201 201 310 310 4837
-- Name: fk_oasis_099; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_reuniao_profissional
    ADD CONSTRAINT fk_oasis_099 FOREIGN KEY (cd_reuniao, cd_projeto) REFERENCES s_reuniao(cd_reuniao, cd_projeto);


--
-- TOC entry 4974 (class 2606 OID 18667)
-- Dependencies: 201 303 4822
-- Name: fk_oasis_100; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_reuniao_profissional
    ADD CONSTRAINT fk_oasis_100 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4971 (class 2606 OID 18672)
-- Dependencies: 199 199 199 309 309 309 4835
-- Name: fk_oasis_101; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_requisito_dependente
    ADD CONSTRAINT fk_oasis_101 FOREIGN KEY (cd_requisito, dt_versao_requisito, cd_projeto) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4970 (class 2606 OID 18677)
-- Dependencies: 4835 309 309 199 199 199 309
-- Name: fk_oasis_102; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_requisito_dependente
    ADD CONSTRAINT fk_oasis_102 FOREIGN KEY (cd_requisito_ascendente, dt_versao_requisito_ascendente, cd_projeto_ascendente) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4969 (class 2606 OID 18682)
-- Dependencies: 198 198 198 309 309 309 4835
-- Name: fk_oasis_103; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_requisito_caso_de_uso
    ADD CONSTRAINT fk_oasis_103 FOREIGN KEY (cd_requisito, dt_versao_requisito, cd_projeto) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4968 (class 2606 OID 18687)
-- Dependencies: 198 198 198 198 258 258 258 258 4724
-- Name: fk_oasis_104; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_requisito_caso_de_uso
    ADD CONSTRAINT fk_oasis_104 FOREIGN KEY (cd_projeto, cd_modulo, cd_caso_de_uso, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_projeto, cd_modulo, cd_caso_de_uso, dt_versao_caso_de_uso);


--
-- TOC entry 4967 (class 2606 OID 18692)
-- Dependencies: 197 197 197 309 309 309 4835
-- Name: fk_oasis_105; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_regra_negocio_requisito
    ADD CONSTRAINT fk_oasis_105 FOREIGN KEY (cd_requisito, dt_versao_requisito, cd_projeto) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4966 (class 2606 OID 18697)
-- Dependencies: 197 197 197 308 308 308 4833
-- Name: fk_oasis_106; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_regra_negocio_requisito
    ADD CONSTRAINT fk_oasis_106 FOREIGN KEY (cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio) REFERENCES s_regra_negocio(cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio);


--
-- TOC entry 4965 (class 2606 OID 18702)
-- Dependencies: 196 196 196 196 196 196 253 253 253 253 253 253 4713
-- Name: fk_oasis_107; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_questionario_analise_risco
    ADD CONSTRAINT fk_oasis_107 FOREIGN KEY (dt_analise_risco, cd_projeto, cd_proposta, cd_atividade, cd_etapa, cd_item_risco) REFERENCES s_analise_risco(dt_analise_risco, cd_projeto, cd_proposta, cd_atividade, cd_etapa, cd_item_risco);


--
-- TOC entry 4964 (class 2606 OID 18707)
-- Dependencies: 196 196 196 196 233 233 233 233 4669
-- Name: fk_oasis_108; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_questionario_analise_risco
    ADD CONSTRAINT fk_oasis_108 FOREIGN KEY (cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco) REFERENCES b_questao_analise_risco(cd_questao_analise_risco, cd_atividade, cd_etapa, cd_item_risco);


--
-- TOC entry 4963 (class 2606 OID 18712)
-- Dependencies: 196 303 4822
-- Name: fk_oasis_109; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_questionario_analise_risco
    ADD CONSTRAINT fk_oasis_109 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4962 (class 2606 OID 18717)
-- Dependencies: 195 195 307 307 4831
-- Name: fk_oasis_110; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_quest_avaliacao_qualidade
    ADD CONSTRAINT fk_oasis_110 FOREIGN KEY (cd_projeto, cd_proposta) REFERENCES s_proposta(cd_projeto, cd_proposta);


--
-- TOC entry 4961 (class 2606 OID 18722)
-- Dependencies: 195 195 218 218 4637
-- Name: fk_oasis_111; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_quest_avaliacao_qualidade
    ADD CONSTRAINT fk_oasis_111 FOREIGN KEY (cd_item_grupo_fator, cd_grupo_fator) REFERENCES b_item_grupo_fator(cd_item_grupo_fator, cd_grupo_fator);


--
-- TOC entry 4960 (class 2606 OID 18727)
-- Dependencies: 194 194 307 307 4831
-- Name: fk_oasis_113; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_proposta_sub_item_metrica
    ADD CONSTRAINT fk_oasis_113 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 4959 (class 2606 OID 18732)
-- Dependencies: 193 193 307 307 4831
-- Name: fk_oasis_114; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_proposta_modulo
    ADD CONSTRAINT fk_oasis_114 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 4958 (class 2606 OID 18737)
-- Dependencies: 193 193 289 289 4794
-- Name: fk_oasis_115; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_proposta_modulo
    ADD CONSTRAINT fk_oasis_115 FOREIGN KEY (cd_modulo, cd_projeto) REFERENCES s_modulo(cd_modulo, cd_projeto);


--
-- TOC entry 4957 (class 2606 OID 18742)
-- Dependencies: 192 192 307 307 4831
-- Name: fk_oasis_116; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_proposta_definicao_metrica
    ADD CONSTRAINT fk_oasis_116 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 4956 (class 2606 OID 18747)
-- Dependencies: 192 212 4625
-- Name: fk_oasis_117; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_proposta_definicao_metrica
    ADD CONSTRAINT fk_oasis_117 FOREIGN KEY (cd_definicao_metrica) REFERENCES b_definicao_metrica(cd_definicao_metrica);


--
-- TOC entry 4955 (class 2606 OID 18752)
-- Dependencies: 191 304 4824
-- Name: fk_oasis_118; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_projeto
    ADD CONSTRAINT fk_oasis_118 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 4954 (class 2606 OID 18757)
-- Dependencies: 191 303 4822
-- Name: fk_oasis_119; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_projeto
    ADD CONSTRAINT fk_oasis_119 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4953 (class 2606 OID 18762)
-- Dependencies: 191 228 4659
-- Name: fk_oasis_120; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_projeto
    ADD CONSTRAINT fk_oasis_120 FOREIGN KEY (cd_papel_profissional) REFERENCES b_papel_profissional(cd_papel_profissional);


--
-- TOC entry 4952 (class 2606 OID 18767)
-- Dependencies: 190 190 190 190 302 302 302 302 4820
-- Name: fk_oasis_121; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_produto
    ADD CONSTRAINT fk_oasis_121 FOREIGN KEY (cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela) REFERENCES s_produto_parcela(cd_produto_parcela, cd_proposta, cd_projeto, cd_parcela);


--
-- TOC entry 4951 (class 2606 OID 18772)
-- Dependencies: 190 303 4822
-- Name: fk_oasis_122; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_produto
    ADD CONSTRAINT fk_oasis_122 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4950 (class 2606 OID 18777)
-- Dependencies: 189 303 4822
-- Name: fk_oasis_123; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_objeto_contrato
    ADD CONSTRAINT fk_oasis_123 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4949 (class 2606 OID 18782)
-- Dependencies: 189 231 4665
-- Name: fk_oasis_124; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_objeto_contrato
    ADD CONSTRAINT fk_oasis_124 FOREIGN KEY (cd_perfil_profissional) REFERENCES b_perfil_profissional(cd_perfil_profissional);


--
-- TOC entry 4948 (class 2606 OID 18787)
-- Dependencies: 189 291 4798
-- Name: fk_oasis_125; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_objeto_contrato
    ADD CONSTRAINT fk_oasis_125 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4947 (class 2606 OID 18792)
-- Dependencies: 188 291 4798
-- Name: fk_oasis_126; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_menu
    ADD CONSTRAINT fk_oasis_126 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4946 (class 2606 OID 18797)
-- Dependencies: 188 303 4822
-- Name: fk_oasis_127; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_menu
    ADD CONSTRAINT fk_oasis_127 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4945 (class 2606 OID 18802)
-- Dependencies: 188 225 4653
-- Name: fk_oasis_128; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_menu
    ADD CONSTRAINT fk_oasis_128 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);


--
-- TOC entry 4944 (class 2606 OID 18807)
-- Dependencies: 187 303 4822
-- Name: fk_oasis_129; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_mensageria
    ADD CONSTRAINT fk_oasis_129 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4943 (class 2606 OID 18812)
-- Dependencies: 187 288 4792
-- Name: fk_oasis_130; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_mensageria
    ADD CONSTRAINT fk_oasis_130 FOREIGN KEY (cd_mensageria) REFERENCES s_mensageria(cd_mensageria);


--
-- TOC entry 4942 (class 2606 OID 18817)
-- Dependencies: 186 303 4822
-- Name: fk_oasis_131; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_conhecimento
    ADD CONSTRAINT fk_oasis_131 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4941 (class 2606 OID 18822)
-- Dependencies: 186 186 210 210 4621
-- Name: fk_oasis_132; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_profissional_conhecimento
    ADD CONSTRAINT fk_oasis_132 FOREIGN KEY (cd_conhecimento, cd_tipo_conhecimento) REFERENCES b_conhecimento(cd_conhecimento, cd_tipo_conhecimento);


--
-- TOC entry 4940 (class 2606 OID 18827)
-- Dependencies: 185 185 289 289 4794
-- Name: fk_oasis_133; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_planejamento
    ADD CONSTRAINT fk_oasis_133 FOREIGN KEY (cd_modulo, cd_projeto) REFERENCES s_modulo(cd_modulo, cd_projeto);


--
-- TOC entry 4939 (class 2606 OID 18832)
-- Dependencies: 185 185 207 207 4613
-- Name: fk_oasis_134; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_planejamento
    ADD CONSTRAINT fk_oasis_134 FOREIGN KEY (cd_atividade, cd_etapa) REFERENCES b_atividade(cd_atividade, cd_etapa);


--
-- TOC entry 4936 (class 2606 OID 18837)
-- Dependencies: 183 231 4665
-- Name: fk_oasis_135; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_prof_papel_prof
    ADD CONSTRAINT fk_oasis_135 FOREIGN KEY (cd_perfil_profissional) REFERENCES b_perfil_profissional(cd_perfil_profissional);


--
-- TOC entry 4935 (class 2606 OID 18842)
-- Dependencies: 183 228 4659
-- Name: fk_oasis_136; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_prof_papel_prof
    ADD CONSTRAINT fk_oasis_136 FOREIGN KEY (cd_papel_profissional) REFERENCES b_papel_profissional(cd_papel_profissional);


--
-- TOC entry 4934 (class 2606 OID 18847)
-- Dependencies: 182 230 4663
-- Name: fk_oasis_137; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_menu_sistema
    ADD CONSTRAINT fk_oasis_137 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);


--
-- TOC entry 4933 (class 2606 OID 18852)
-- Dependencies: 182 225 4653
-- Name: fk_oasis_138; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_menu_sistema
    ADD CONSTRAINT fk_oasis_138 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);


--
-- TOC entry 4932 (class 2606 OID 18857)
-- Dependencies: 181 291 4798
-- Name: fk_oasis_139; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_menu
    ADD CONSTRAINT fk_oasis_139 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4931 (class 2606 OID 18862)
-- Dependencies: 181 230 4663
-- Name: fk_oasis_140; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_menu
    ADD CONSTRAINT fk_oasis_140 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);


--
-- TOC entry 4930 (class 2606 OID 18867)
-- Dependencies: 181 225 4653
-- Name: fk_oasis_141; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_menu
    ADD CONSTRAINT fk_oasis_141 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);


--
-- TOC entry 4929 (class 2606 OID 18872)
-- Dependencies: 180 230 4663
-- Name: fk_oasis_142; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_box_inicio
    ADD CONSTRAINT fk_oasis_142 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);


--
-- TOC entry 4928 (class 2606 OID 18877)
-- Dependencies: 180 291 4798
-- Name: fk_oasis_143; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_box_inicio
    ADD CONSTRAINT fk_oasis_143 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4927 (class 2606 OID 18882)
-- Dependencies: 180 208 4615
-- Name: fk_oasis_144; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_perfil_box_inicio
    ADD CONSTRAINT fk_oasis_144 FOREIGN KEY (cd_box_inicio) REFERENCES b_box_inicio(cd_box_inicio);


--
-- TOC entry 4926 (class 2606 OID 18887)
-- Dependencies: 179 221 4645
-- Name: fk_oasis_145; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_parecer_tecnico_proposta
    ADD CONSTRAINT fk_oasis_145 FOREIGN KEY (cd_item_parecer_tecnico) REFERENCES b_item_parecer_tecnico(cd_item_parecer_tecnico);


--
-- TOC entry 4925 (class 2606 OID 18892)
-- Dependencies: 178 178 178 178 300 300 300 300 4816
-- Name: fk_oasis_146; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_parecer_tecnico_parcela
    ADD CONSTRAINT fk_oasis_146 FOREIGN KEY (cd_proposta, cd_projeto, cd_parcela, cd_processamento_parcela) REFERENCES s_processamento_parcela(cd_proposta, cd_projeto, cd_parcela, cd_processamento_parcela);


--
-- TOC entry 4924 (class 2606 OID 18897)
-- Dependencies: 178 221 4645
-- Name: fk_oasis_147; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_parecer_tecnico_parcela
    ADD CONSTRAINT fk_oasis_147 FOREIGN KEY (cd_item_parecer_tecnico) REFERENCES b_item_parecer_tecnico(cd_item_parecer_tecnico);


--
-- TOC entry 4919 (class 2606 OID 18902)
-- Dependencies: 175 231 4665
-- Name: fk_oasis_148; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_objeto_contrato_perfil_prof
    ADD CONSTRAINT fk_oasis_148 FOREIGN KEY (cd_perfil_profissional) REFERENCES b_perfil_profissional(cd_perfil_profissional);


--
-- TOC entry 4918 (class 2606 OID 18907)
-- Dependencies: 175 291 4798
-- Name: fk_oasis_149; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_objeto_contrato_perfil_prof
    ADD CONSTRAINT fk_oasis_149 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4917 (class 2606 OID 18912)
-- Dependencies: 174 228 4659
-- Name: fk_oasis_150; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_objeto_contrato_papel_prof
    ADD CONSTRAINT fk_oasis_150 FOREIGN KEY (cd_papel_profissional) REFERENCES b_papel_profissional(cd_papel_profissional);


--
-- TOC entry 4916 (class 2606 OID 18917)
-- Dependencies: 174 291 4798
-- Name: fk_oasis_151; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_objeto_contrato_papel_prof
    ADD CONSTRAINT fk_oasis_151 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4915 (class 2606 OID 18922)
-- Dependencies: 173 173 207 207 4613
-- Name: fk_oasis_152; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_objeto_contrato_atividade
    ADD CONSTRAINT fk_oasis_152 FOREIGN KEY (cd_etapa, cd_atividade) REFERENCES b_atividade(cd_etapa, cd_atividade);


--
-- TOC entry 4914 (class 2606 OID 18927)
-- Dependencies: 173 291 4798
-- Name: fk_oasis_153; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_objeto_contrato_atividade
    ADD CONSTRAINT fk_oasis_153 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4913 (class 2606 OID 18932)
-- Dependencies: 172 224 4651
-- Name: fk_oasis_154; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_medicao_medida
    ADD CONSTRAINT fk_oasis_154 FOREIGN KEY (cd_medida) REFERENCES b_medida(cd_medida);


--
-- TOC entry 4912 (class 2606 OID 18937)
-- Dependencies: 172 287 4790
-- Name: fk_oasis_155; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_medicao_medida
    ADD CONSTRAINT fk_oasis_155 FOREIGN KEY (cd_medicao) REFERENCES s_medicao(cd_medicao);


--
-- TOC entry 4911 (class 2606 OID 18942)
-- Dependencies: 171 244 4693
-- Name: fk_oasis_156; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_requisito_doc
    ADD CONSTRAINT fk_oasis_156 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);


--
-- TOC entry 4910 (class 2606 OID 18947)
-- Dependencies: 171 171 171 171 171 170 170 170 170 170 4520
-- Name: fk_oasis_157; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_requisito_doc
    ADD CONSTRAINT fk_oasis_157 FOREIGN KEY (cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste) REFERENCES a_item_teste_requisito(cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, cd_item_teste);


--
-- TOC entry 4909 (class 2606 OID 18952)
-- Dependencies: 170 170 170 309 309 309 4835
-- Name: fk_oasis_158; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_requisito
    ADD CONSTRAINT fk_oasis_158 FOREIGN KEY (cd_requisito, dt_versao_requisito, cd_projeto) REFERENCES s_requisito(cd_requisito, dt_versao_requisito, cd_projeto);


--
-- TOC entry 4908 (class 2606 OID 18957)
-- Dependencies: 170 303 4822
-- Name: fk_oasis_159; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_requisito
    ADD CONSTRAINT fk_oasis_159 FOREIGN KEY (cd_profissional_solucao) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4907 (class 2606 OID 18962)
-- Dependencies: 170 303 4822
-- Name: fk_oasis_160; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_requisito
    ADD CONSTRAINT fk_oasis_160 FOREIGN KEY (cd_profissional_homologacao) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4906 (class 2606 OID 18967)
-- Dependencies: 170 303 4822
-- Name: fk_oasis_161; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_requisito
    ADD CONSTRAINT fk_oasis_161 FOREIGN KEY (cd_profissional_analise) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4905 (class 2606 OID 18972)
-- Dependencies: 170 223 4649
-- Name: fk_oasis_162; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_requisito
    ADD CONSTRAINT fk_oasis_162 FOREIGN KEY (cd_item_teste) REFERENCES b_item_teste(cd_item_teste);


--
-- TOC entry 4904 (class 2606 OID 18977)
-- Dependencies: 169 169 169 169 169 168 168 168 168 168 4516
-- Name: fk_oasis_163; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_regra_negocio_doc
    ADD CONSTRAINT fk_oasis_163 FOREIGN KEY (dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio) REFERENCES a_item_teste_regra_negocio(dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio);


--
-- TOC entry 4903 (class 2606 OID 18982)
-- Dependencies: 169 244 4693
-- Name: fk_oasis_164; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_regra_negocio_doc
    ADD CONSTRAINT fk_oasis_164 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);


--
-- TOC entry 4902 (class 2606 OID 18987)
-- Dependencies: 168 303 4822
-- Name: fk_oasis_165; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_regra_negocio
    ADD CONSTRAINT fk_oasis_165 FOREIGN KEY (cd_profissional_solucao) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4901 (class 2606 OID 18992)
-- Dependencies: 168 303 4822
-- Name: fk_oasis_166; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_regra_negocio
    ADD CONSTRAINT fk_oasis_166 FOREIGN KEY (cd_profissional_homologacao) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4900 (class 2606 OID 18997)
-- Dependencies: 168 303 4822
-- Name: fk_oasis_167; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_regra_negocio
    ADD CONSTRAINT fk_oasis_167 FOREIGN KEY (cd_profissional_analise) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4899 (class 2606 OID 19002)
-- Dependencies: 168 223 4649
-- Name: fk_oasis_168; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_regra_negocio
    ADD CONSTRAINT fk_oasis_168 FOREIGN KEY (cd_item_teste) REFERENCES b_item_teste(cd_item_teste);


--
-- TOC entry 4898 (class 2606 OID 19007)
-- Dependencies: 168 168 168 308 308 308 4833
-- Name: fk_oasis_169; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_regra_negocio
    ADD CONSTRAINT fk_oasis_169 FOREIGN KEY (cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio) REFERENCES s_regra_negocio(cd_regra_negocio, dt_regra_negocio, cd_projeto_regra_negocio);


--
-- TOC entry 4897 (class 2606 OID 19012)
-- Dependencies: 167 244 4693
-- Name: fk_oasis_170; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_caso_de_uso_doc
    ADD CONSTRAINT fk_oasis_170 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);


--
-- TOC entry 4896 (class 2606 OID 19017)
-- Dependencies: 167 167 167 167 167 167 166 166 166 166 166 166 4512
-- Name: fk_oasis_171; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_caso_de_uso_doc
    ADD CONSTRAINT fk_oasis_171 FOREIGN KEY (cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso) REFERENCES a_item_teste_caso_de_uso(cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso);


--
-- TOC entry 4895 (class 2606 OID 19022)
-- Dependencies: 166 303 4822
-- Name: fk_oasis_172; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_oasis_172 FOREIGN KEY (cd_profissional_solucao) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4894 (class 2606 OID 19027)
-- Dependencies: 166 303 4822
-- Name: fk_oasis_173; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_oasis_173 FOREIGN KEY (cd_profissional_homologacao) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4893 (class 2606 OID 19032)
-- Dependencies: 166 303 4822
-- Name: fk_oasis_174; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_oasis_174 FOREIGN KEY (cd_profissional_analise) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4892 (class 2606 OID 19037)
-- Dependencies: 166 166 166 166 258 258 258 258 4724
-- Name: fk_oasis_175; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_oasis_175 FOREIGN KEY (cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso) REFERENCES s_caso_de_uso(cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso);


--
-- TOC entry 4891 (class 2606 OID 19042)
-- Dependencies: 166 223 4649
-- Name: fk_oasis_176; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_item_teste_caso_de_uso
    ADD CONSTRAINT fk_oasis_176 FOREIGN KEY (cd_item_teste) REFERENCES b_item_teste(cd_item_teste);


--
-- TOC entry 4888 (class 2606 OID 19047)
-- Dependencies: 164 243 4691
-- Name: fk_oasis_177; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_informacao_tecnica
    ADD CONSTRAINT fk_oasis_177 FOREIGN KEY (cd_tipo_dado_tecnico) REFERENCES b_tipo_dado_tecnico(cd_tipo_dado_tecnico);


--
-- TOC entry 4887 (class 2606 OID 19052)
-- Dependencies: 164 304 4824
-- Name: fk_oasis_178; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_informacao_tecnica
    ADD CONSTRAINT fk_oasis_178 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 4886 (class 2606 OID 19057)
-- Dependencies: 163 163 310 310 4837
-- Name: fk_oasis_179; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_gerencia_mudanca
    ADD CONSTRAINT fk_oasis_179 FOREIGN KEY (cd_reuniao, cd_projeto_reuniao) REFERENCES s_reuniao(cd_reuniao, cd_projeto);


--
-- TOC entry 4885 (class 2606 OID 19062)
-- Dependencies: 163 304 4824
-- Name: fk_oasis_180; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_gerencia_mudanca
    ADD CONSTRAINT fk_oasis_180 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 4884 (class 2606 OID 19067)
-- Dependencies: 163 217 4635
-- Name: fk_oasis_181; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_gerencia_mudanca
    ADD CONSTRAINT fk_oasis_181 FOREIGN KEY (cd_item_controle_baseline) REFERENCES b_item_controle_baseline(cd_item_controle_baseline);


--
-- TOC entry 4883 (class 2606 OID 19072)
-- Dependencies: 162 225 4653
-- Name: fk_oasis_182; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_funcionalidade_menu
    ADD CONSTRAINT fk_oasis_182 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);


--
-- TOC entry 4882 (class 2606 OID 19077)
-- Dependencies: 162 215 4631
-- Name: fk_oasis_183; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_funcionalidade_menu
    ADD CONSTRAINT fk_oasis_183 FOREIGN KEY (cd_funcionalidade) REFERENCES b_funcionalidade(cd_funcionalidade);


--
-- TOC entry 4881 (class 2606 OID 19082)
-- Dependencies: 160 244 4693
-- Name: fk_oasis_184; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_documentacao_projeto
    ADD CONSTRAINT fk_oasis_184 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);


--
-- TOC entry 4880 (class 2606 OID 19087)
-- Dependencies: 160 304 4824
-- Name: fk_oasis_185; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_documentacao_projeto
    ADD CONSTRAINT fk_oasis_185 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 4879 (class 2606 OID 19092)
-- Dependencies: 159 244 4693
-- Name: fk_oasis_186; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_documentacao_profissional
    ADD CONSTRAINT fk_oasis_186 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);


--
-- TOC entry 4878 (class 2606 OID 19097)
-- Dependencies: 159 303 4822
-- Name: fk_oasis_187; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_documentacao_profissional
    ADD CONSTRAINT fk_oasis_187 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4873 (class 2606 OID 19102)
-- Dependencies: 156 156 267 267 4742
-- Name: fk_oasis_188; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_disponibilidade_servico_doc
    ADD CONSTRAINT fk_oasis_188 FOREIGN KEY (cd_disponibilidade_servico, cd_objeto) REFERENCES s_disponibilidade_servico(cd_disponibilidade_servico, cd_objeto);


--
-- TOC entry 4872 (class 2606 OID 19107)
-- Dependencies: 156 244 4693
-- Name: fk_oasis_189; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_disponibilidade_servico_doc
    ADD CONSTRAINT fk_oasis_189 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);


--
-- TOC entry 4871 (class 2606 OID 19112)
-- Dependencies: 155 303 4822
-- Name: fk_oasis_190; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_demanda_profissional
    ADD CONSTRAINT fk_oasis_190 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4870 (class 2606 OID 19117)
-- Dependencies: 155 266 4740
-- Name: fk_oasis_191; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_demanda_profissional
    ADD CONSTRAINT fk_oasis_191 FOREIGN KEY (cd_demanda) REFERENCES s_demanda(cd_demanda);


--
-- TOC entry 4868 (class 2606 OID 55055)
-- Dependencies: 227 154 4657
-- Name: fk_oasis_192; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_demanda_prof_nivel_servico
    ADD CONSTRAINT fk_oasis_192 FOREIGN KEY (cd_nivel_servico) REFERENCES b_nivel_servico(cd_nivel_servico);


--
-- TOC entry 4869 (class 2606 OID 19127)
-- Dependencies: 154 154 155 155 4490
-- Name: fk_oasis_193; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_demanda_prof_nivel_servico
    ADD CONSTRAINT fk_oasis_193 FOREIGN KEY (cd_demanda, cd_profissional) REFERENCES a_demanda_profissional(cd_demanda, cd_profissional);


--
-- TOC entry 4867 (class 2606 OID 19132)
-- Dependencies: 153 230 4663
-- Name: fk_oasis_194; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_definicao_processo
    ADD CONSTRAINT fk_oasis_194 FOREIGN KEY (cd_perfil) REFERENCES b_perfil(cd_perfil);


--
-- TOC entry 4866 (class 2606 OID 19137)
-- Dependencies: 153 215 4631
-- Name: fk_oasis_195; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_definicao_processo
    ADD CONSTRAINT fk_oasis_195 FOREIGN KEY (cd_funcionalidade) REFERENCES b_funcionalidade(cd_funcionalidade);


--
-- TOC entry 4865 (class 2606 OID 19142)
-- Dependencies: 152 152 307 307 4831
-- Name: fk_oasis_196; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_controle
    ADD CONSTRAINT fk_oasis_196 FOREIGN KEY (cd_proposta, cd_projeto) REFERENCES s_proposta(cd_proposta, cd_projeto);


--
-- TOC entry 4864 (class 2606 OID 19147)
-- Dependencies: 152 152 306 306 4828
-- Name: fk_oasis_197; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_controle
    ADD CONSTRAINT fk_oasis_197 FOREIGN KEY (cd_projeto_previsto, cd_contrato) REFERENCES s_projeto_previsto(cd_projeto_previsto, cd_contrato);


--
-- TOC entry 4863 (class 2606 OID 19152)
-- Dependencies: 151 304 4824
-- Name: fk_oasis_198; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_contrato_projeto
    ADD CONSTRAINT fk_oasis_198 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 4862 (class 2606 OID 19157)
-- Dependencies: 151 264 4736
-- Name: fk_oasis_199; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_contrato_projeto
    ADD CONSTRAINT fk_oasis_199 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 4857 (class 2606 OID 19162)
-- Dependencies: 148 212 4625
-- Name: fk_oasis_200; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_contrato_definicao_metrica
    ADD CONSTRAINT fk_oasis_200 FOREIGN KEY (cd_definicao_metrica) REFERENCES b_definicao_metrica(cd_definicao_metrica);


--
-- TOC entry 5088 (class 2606 OID 19167)
-- Dependencies: 311 291 4798
-- Name: fk_oasis_2007; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_reuniao_geral
    ADD CONSTRAINT fk_oasis_2007 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 4856 (class 2606 OID 19172)
-- Dependencies: 148 264 4736
-- Name: fk_oasis_201; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_contrato_definicao_metrica
    ADD CONSTRAINT fk_oasis_201 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 4855 (class 2606 OID 19177)
-- Dependencies: 147 211 4623
-- Name: fk_oasis_202; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_conjunto_medida_medicao
    ADD CONSTRAINT fk_oasis_202 FOREIGN KEY (cd_conjunto_medida) REFERENCES b_conjunto_medida(cd_conjunto_medida);


--
-- TOC entry 4854 (class 2606 OID 19182)
-- Dependencies: 146 304 4824
-- Name: fk_oasis_203; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_conhecimento_projeto
    ADD CONSTRAINT fk_oasis_203 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 4853 (class 2606 OID 19187)
-- Dependencies: 146 146 210 210 4621
-- Name: fk_oasis_204; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_conhecimento_projeto
    ADD CONSTRAINT fk_oasis_204 FOREIGN KEY (cd_conhecimento, cd_tipo_conhecimento) REFERENCES b_conhecimento(cd_conhecimento, cd_tipo_conhecimento);


--
-- TOC entry 4852 (class 2606 OID 19192)
-- Dependencies: 145 145 257 257 4722
-- Name: fk_oasis_205; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_baseline_item_controle
    ADD CONSTRAINT fk_oasis_205 FOREIGN KEY (dt_baseline, cd_projeto) REFERENCES s_baseline(dt_baseline, cd_projeto);


--
-- TOC entry 4851 (class 2606 OID 19197)
-- Dependencies: 145 217 4635
-- Name: fk_oasis_206; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_baseline_item_controle
    ADD CONSTRAINT fk_oasis_206 FOREIGN KEY (cd_item_controle_baseline) REFERENCES b_item_controle_baseline(cd_item_controle_baseline);


--
-- TOC entry 5094 (class 2606 OID 19202)
-- Dependencies: 314 247 4699
-- Name: fk_oasis_207; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_solicitacao_pedido
    ADD CONSTRAINT fk_oasis_207 FOREIGN KEY (cd_unidade_pedido) REFERENCES b_unidade(cd_unidade);


--
-- TOC entry 5026 (class 2606 OID 19207)
-- Dependencies: 266 238 4679
-- Name: fk_oasis_207; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_demanda
    ADD CONSTRAINT fk_oasis_207 FOREIGN KEY (cd_status_atendimento) REFERENCES b_status_atendimento(cd_status_atendimento);


--
-- TOC entry 5093 (class 2606 OID 19212)
-- Dependencies: 314 316 4849
-- Name: fk_oasis_208; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_solicitacao_pedido
    ADD CONSTRAINT fk_oasis_208 FOREIGN KEY (cd_usuario_pedido) REFERENCES s_usuario_pedido(cd_usuario_pedido);


--
-- TOC entry 4997 (class 2606 OID 19217)
-- Dependencies: 236 205 4609
-- Name: fk_oasis_208; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_rotina
    ADD CONSTRAINT fk_oasis_208 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);


--
-- TOC entry 4921 (class 2606 OID 19222)
-- Dependencies: 176 291 4798
-- Name: fk_oasis_208; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_objeto_contrato_rotina
    ADD CONSTRAINT fk_oasis_208 FOREIGN KEY (cd_objeto) REFERENCES s_objeto_contrato(cd_objeto);


--
-- TOC entry 5041 (class 2606 OID 19227)
-- Dependencies: 278 314 4845
-- Name: fk_oasis_209; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_pedido
    ADD CONSTRAINT fk_oasis_209 FOREIGN KEY (cd_solicitacao_historico) REFERENCES s_solicitacao_pedido(cd_solicitacao_pedido);


--
-- TOC entry 4877 (class 2606 OID 19232)
-- Dependencies: 158 244 4693
-- Name: fk_oasis_209; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_documentacao_contrato
    ADD CONSTRAINT fk_oasis_209 FOREIGN KEY (cd_tipo_documentacao) REFERENCES b_tipo_documentacao(cd_tipo_documentacao);


--
-- TOC entry 4920 (class 2606 OID 19237)
-- Dependencies: 176 236 4675
-- Name: fk_oasis_209; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_objeto_contrato_rotina
    ADD CONSTRAINT fk_oasis_209 FOREIGN KEY (cd_rotina) REFERENCES b_rotina(cd_rotina);


--
-- TOC entry 4973 (class 2606 OID 19242)
-- Dependencies: 200 200 311 311 4839
-- Name: fk_oasis_2099; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_reuniao_geral_profissional
    ADD CONSTRAINT fk_oasis_2099 FOREIGN KEY (cd_reuniao_geral, cd_objeto) REFERENCES s_reuniao_geral(cd_reuniao_geral, cd_objeto);


--
-- TOC entry 4979 (class 2606 OID 19247)
-- Dependencies: 203 203 177 177 4538
-- Name: fk_oasis_210; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_solicitacao_resposta_pedido
    ADD CONSTRAINT fk_oasis_210 FOREIGN KEY (cd_pergunta_pedido, cd_resposta_pedido) REFERENCES a_opcao_resp_pergunta_pedido(cd_pergunta_pedido, cd_resposta_pedido);


--
-- TOC entry 4876 (class 2606 OID 19252)
-- Dependencies: 158 264 4736
-- Name: fk_oasis_210; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_documentacao_contrato
    ADD CONSTRAINT fk_oasis_210 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 4977 (class 2606 OID 19257)
-- Dependencies: 202 202 189 189 4566
-- Name: fk_oasis_210; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_rotina_profissional
    ADD CONSTRAINT fk_oasis_210 FOREIGN KEY (cd_profissional, cd_objeto) REFERENCES a_profissional_objeto_contrato(cd_profissional, cd_objeto);


--
-- TOC entry 4972 (class 2606 OID 19262)
-- Dependencies: 200 303 4822
-- Name: fk_oasis_2100; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_reuniao_geral_profissional
    ADD CONSTRAINT fk_oasis_2100 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 4978 (class 2606 OID 19267)
-- Dependencies: 203 314 4845
-- Name: fk_oasis_211; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_solicitacao_resposta_pedido
    ADD CONSTRAINT fk_oasis_211 FOREIGN KEY (cd_solicitacao_pedido) REFERENCES s_solicitacao_pedido(cd_solicitacao_pedido);


--
-- TOC entry 4976 (class 2606 OID 19272)
-- Dependencies: 202 202 176 176 4536
-- Name: fk_oasis_211; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_rotina_profissional
    ADD CONSTRAINT fk_oasis_211 FOREIGN KEY (cd_rotina, cd_objeto) REFERENCES a_objeto_contrato_rotina(cd_rotina, cd_objeto);


--
-- TOC entry 5012 (class 2606 OID 19277)
-- Dependencies: 254 254 254 203 203 203 4605
-- Name: fk_oasis_212; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_arquivo_pedido
    ADD CONSTRAINT fk_oasis_212 FOREIGN KEY (cd_solicitacao_pedido, cd_resposta_pedido, cd_pergunta_pedido) REFERENCES a_solicitacao_resposta_pedido(cd_solicitacao_pedido, cd_resposta_pedido, cd_pergunta_pedido);


--
-- TOC entry 5030 (class 2606 OID 19282)
-- Dependencies: 269 269 269 202 202 202 4603
-- Name: fk_oasis_212; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_execucao_rotina
    ADD CONSTRAINT fk_oasis_212 FOREIGN KEY (cd_rotina, cd_objeto, cd_profissional) REFERENCES a_rotina_profissional(cd_rotina, cd_objeto, cd_profissional);


--
-- TOC entry 4923 (class 2606 OID 19287)
-- Dependencies: 177 232 4667
-- Name: fk_oasis_213; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_opcao_resp_pergunta_pedido
    ADD CONSTRAINT fk_oasis_213 FOREIGN KEY (cd_pergunta_pedido) REFERENCES b_pergunta_pedido(cd_pergunta_pedido);


--
-- TOC entry 5040 (class 2606 OID 19292)
-- Dependencies: 277 277 277 277 269 269 269 269 4746
-- Name: fk_oasis_213; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_historico_execucao_rotina
    ADD CONSTRAINT fk_oasis_213 FOREIGN KEY (dt_execucao_rotina, cd_rotina, cd_objeto, cd_profissional) REFERENCES s_execucao_rotina(dt_execucao_rotina, cd_rotina, cd_objeto, cd_profissional);


--
-- TOC entry 4922 (class 2606 OID 19297)
-- Dependencies: 177 235 4673
-- Name: fk_oasis_214; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_opcao_resp_pergunta_pedido
    ADD CONSTRAINT fk_oasis_214 FOREIGN KEY (cd_resposta_pedido) REFERENCES b_resposta_pedido(cd_resposta_pedido);


--
-- TOC entry 4938 (class 2606 OID 19302)
-- Dependencies: 184 232 4667
-- Name: fk_oasis_215; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_pergunta_depende_resp_pedido
    ADD CONSTRAINT fk_oasis_215 FOREIGN KEY (cd_pergunta_depende) REFERENCES b_pergunta_pedido(cd_pergunta_pedido);


--
-- TOC entry 4937 (class 2606 OID 19307)
-- Dependencies: 184 184 177 177 4538
-- Name: fk_oasis_216; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_pergunta_depende_resp_pedido
    ADD CONSTRAINT fk_oasis_216 FOREIGN KEY (cd_pergunta_pedido, cd_resposta_pedido) REFERENCES a_opcao_resp_pergunta_pedido(cd_pergunta_pedido, cd_resposta_pedido);


--
-- TOC entry 4859 (class 2606 OID 19312)
-- Dependencies: 149 264 4736
-- Name: fk_oasis_217; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_contrato_evento
    ADD CONSTRAINT fk_oasis_217 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 4858 (class 2606 OID 19317)
-- Dependencies: 149 214 4629
-- Name: fk_oasis_218; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_contrato_evento
    ADD CONSTRAINT fk_oasis_218 FOREIGN KEY (cd_evento) REFERENCES b_evento(cd_evento);


--
-- TOC entry 4986 (class 2606 OID 19322)
-- Dependencies: 218 216 4633
-- Name: fk_oasis_219; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_item_grupo_fator
    ADD CONSTRAINT fk_oasis_219 FOREIGN KEY (cd_grupo_fator) REFERENCES b_grupo_fator(cd_grupo_fator);


--
-- TOC entry 4991 (class 2606 OID 19327)
-- Dependencies: 226 225 4653
-- Name: fk_oasis_220; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE b_msg_email
    ADD CONSTRAINT fk_oasis_220 FOREIGN KEY (cd_menu) REFERENCES b_menu(cd_menu);


--
-- TOC entry 5002 (class 2606 OID 19332)
-- Dependencies: 248 248 307 307 4831
-- Name: fk_oasis_221; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_acompanhamento_proposta
    ADD CONSTRAINT fk_oasis_221 FOREIGN KEY (cd_projeto, cd_proposta) REFERENCES s_proposta(cd_projeto, cd_proposta);


--
-- TOC entry 5021 (class 2606 OID 19337)
-- Dependencies: 262 304 4824
-- Name: fk_oasis_222; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_config_banco_de_dados
    ADD CONSTRAINT fk_oasis_222 FOREIGN KEY (cd_projeto) REFERENCES s_projeto(cd_projeto);


--
-- TOC entry 5052 (class 2606 OID 19342)
-- Dependencies: 286 303 4822
-- Name: fk_oasis_223; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_log
    ADD CONSTRAINT fk_oasis_223 FOREIGN KEY (cd_profissional) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 5059 (class 2606 OID 19347)
-- Dependencies: 292 292 149 149 4474
-- Name: fk_oasis_224; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_ocorrencia_administrativa
    ADD CONSTRAINT fk_oasis_224 FOREIGN KEY (cd_contrato, cd_evento) REFERENCES a_contrato_evento(cd_contrato, cd_evento);


--
-- TOC entry 5067 (class 2606 OID 19352)
-- Dependencies: 297 247 4699
-- Name: fk_oasis_225; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_pre_projeto
    ADD CONSTRAINT fk_oasis_225 FOREIGN KEY (cd_unidade) REFERENCES b_unidade(cd_unidade);


--
-- TOC entry 5066 (class 2606 OID 19357)
-- Dependencies: 297 303 4822
-- Name: fk_oasis_226; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_pre_projeto
    ADD CONSTRAINT fk_oasis_226 FOREIGN KEY (cd_gerente_pre_projeto) REFERENCES s_profissional(cd_profissional);


--
-- TOC entry 5025 (class 2606 OID 19362)
-- Dependencies: 266 238 4679
-- Name: fk_oasis_227; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_demanda
    ADD CONSTRAINT fk_oasis_227 FOREIGN KEY (cd_status_atendimento) REFERENCES b_status_atendimento(cd_status_atendimento);


--
-- TOC entry 4861 (class 2606 OID 19367)
-- Dependencies: 150 264 4736
-- Name: fk_oasis_contrato_199; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_contrato_item_inventario
    ADD CONSTRAINT fk_oasis_contrato_199 FOREIGN KEY (cd_contrato) REFERENCES s_contrato(cd_contrato);


--
-- TOC entry 4860 (class 2606 OID 19372)
-- Dependencies: 150 219 4639
-- Name: fk_oasis_item_inventario_198; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE a_contrato_item_inventario
    ADD CONSTRAINT fk_oasis_item_inventario_198 FOREIGN KEY (cd_item_inventario) REFERENCES b_item_inventario(cd_item_inventario);


--
-- TOC entry 5051 (class 2606 OID 19377)
-- Dependencies: 285 205 4609
-- Name: s_inventario_fk1; Type: FK CONSTRAINT; Schema: oasis; Owner: postgres
--

ALTER TABLE s_inventario
    ADD CONSTRAINT s_inventario_fk1 FOREIGN KEY (cd_area_atuacao_ti) REFERENCES b_area_atuacao_ti(cd_area_atuacao_ti);


--
-- TOC entry 5100 (class 0 OID 0)
-- Dependencies: 11
-- Name: oasis; Type: ACL; Schema: -; Owner: postgres
--


REVOKE ALL ON SCHEMA oasis FROM oasis;
GRANT ALL ON SCHEMA oasis TO oasis;


--
-- TOC entry 5104 (class 0 OID 0)
-- Dependencies: 146
-- Name: a_conhecimento_projeto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_conhecimento_projeto FROM PUBLIC;
-- REVOKE ALL ON TABLE a_conhecimento_projeto FROM oasis;
GRANT ALL ON TABLE a_conhecimento_projeto TO oasis;


--
-- TOC entry 5105 (class 0 OID 0)
-- Dependencies: 149
-- Name: a_contrato_evento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_contrato_evento FROM PUBLIC;
-- REVOKE ALL ON TABLE a_contrato_evento FROM oasis;
GRANT ALL ON TABLE a_contrato_evento TO oasis;


--
-- TOC entry 5106 (class 0 OID 0)
-- Dependencies: 152
-- Name: a_controle; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_controle FROM PUBLIC;
-- REVOKE ALL ON TABLE a_controle FROM oasis;
GRANT ALL ON TABLE a_controle TO oasis;


--
-- TOC entry 5109 (class 0 OID 0)
-- Dependencies: 154
-- Name: a_demanda_prof_nivel_servico; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_demanda_prof_nivel_servico FROM PUBLIC;
-- REVOKE ALL ON TABLE a_demanda_prof_nivel_servico FROM oasis;
GRANT ALL ON TABLE a_demanda_prof_nivel_servico TO oasis;


--
-- TOC entry 5110 (class 0 OID 0)
-- Dependencies: 155
-- Name: a_demanda_profissional; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_demanda_profissional FROM PUBLIC;
-- REVOKE ALL ON TABLE a_demanda_profissional FROM oasis;
GRANT ALL ON TABLE a_demanda_profissional TO oasis;


--
-- TOC entry 5118 (class 0 OID 0)
-- Dependencies: 159
-- Name: a_documentacao_profissional; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_documentacao_profissional FROM PUBLIC;
-- REVOKE ALL ON TABLE a_documentacao_profissional FROM oasis;
GRANT ALL ON TABLE a_documentacao_profissional TO oasis;


--
-- TOC entry 5119 (class 0 OID 0)
-- Dependencies: 160
-- Name: a_documentacao_projeto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_documentacao_projeto FROM PUBLIC;
-- REVOKE ALL ON TABLE a_documentacao_projeto FROM oasis;
GRANT ALL ON TABLE a_documentacao_projeto TO oasis;


--
-- TOC entry 5120 (class 0 OID 0)
-- Dependencies: 164
-- Name: a_informacao_tecnica; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_informacao_tecnica FROM PUBLIC;
-- REVOKE ALL ON TABLE a_informacao_tecnica FROM oasis;
GRANT ALL ON TABLE a_informacao_tecnica TO oasis;


--
-- TOC entry 5135 (class 0 OID 0)
-- Dependencies: 178
-- Name: a_parecer_tecnico_parcela; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_parecer_tecnico_parcela FROM PUBLIC;
-- REVOKE ALL ON TABLE a_parecer_tecnico_parcela FROM oasis;
GRANT ALL ON TABLE a_parecer_tecnico_parcela TO oasis;


--
-- TOC entry 5136 (class 0 OID 0)
-- Dependencies: 179
-- Name: a_parecer_tecnico_proposta; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_parecer_tecnico_proposta FROM PUBLIC;
-- REVOKE ALL ON TABLE a_parecer_tecnico_proposta FROM oasis;
GRANT ALL ON TABLE a_parecer_tecnico_proposta TO oasis;


--
-- TOC entry 5137 (class 0 OID 0)
-- Dependencies: 181
-- Name: a_perfil_menu; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_perfil_menu FROM PUBLIC;
-- REVOKE ALL ON TABLE a_perfil_menu FROM oasis;
GRANT ALL ON TABLE a_perfil_menu TO oasis;


--
-- TOC entry 5145 (class 0 OID 0)
-- Dependencies: 185
-- Name: a_planejamento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_planejamento FROM PUBLIC;
-- REVOKE ALL ON TABLE a_planejamento FROM oasis;
GRANT ALL ON TABLE a_planejamento TO oasis;


--
-- TOC entry 5146 (class 0 OID 0)
-- Dependencies: 186
-- Name: a_profissional_conhecimento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_profissional_conhecimento FROM PUBLIC;
-- REVOKE ALL ON TABLE a_profissional_conhecimento FROM oasis;
GRANT ALL ON TABLE a_profissional_conhecimento TO oasis;


--
-- TOC entry 5147 (class 0 OID 0)
-- Dependencies: 188
-- Name: a_profissional_menu; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_profissional_menu FROM PUBLIC;
-- REVOKE ALL ON TABLE a_profissional_menu FROM oasis;
GRANT ALL ON TABLE a_profissional_menu TO oasis;


--
-- TOC entry 5148 (class 0 OID 0)
-- Dependencies: 189
-- Name: a_profissional_objeto_contrato; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_profissional_objeto_contrato FROM PUBLIC;
-- REVOKE ALL ON TABLE a_profissional_objeto_contrato FROM oasis;
GRANT ALL ON TABLE a_profissional_objeto_contrato TO oasis;


--
-- TOC entry 5149 (class 0 OID 0)
-- Dependencies: 191
-- Name: a_profissional_projeto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_profissional_projeto FROM PUBLIC;
-- REVOKE ALL ON TABLE a_profissional_projeto FROM oasis;
GRANT ALL ON TABLE a_profissional_projeto TO oasis;


--
-- TOC entry 5150 (class 0 OID 0)
-- Dependencies: 193
-- Name: a_proposta_modulo; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_proposta_modulo FROM PUBLIC;
-- REVOKE ALL ON TABLE a_proposta_modulo FROM oasis;
GRANT ALL ON TABLE a_proposta_modulo TO oasis;


--
-- TOC entry 5151 (class 0 OID 0)
-- Dependencies: 195
-- Name: a_quest_avaliacao_qualidade; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_quest_avaliacao_qualidade FROM PUBLIC;
-- REVOKE ALL ON TABLE a_quest_avaliacao_qualidade FROM oasis;
GRANT ALL ON TABLE a_quest_avaliacao_qualidade TO oasis;


--
-- TOC entry 5152 (class 0 OID 0)
-- Dependencies: 200
-- Name: a_reuniao_geral_profissional; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_reuniao_geral_profissional FROM PUBLIC;
-- REVOKE ALL ON TABLE a_reuniao_geral_profissional FROM oasis;
GRANT ALL ON TABLE a_reuniao_geral_profissional TO oasis;


--
-- TOC entry 5153 (class 0 OID 0)
-- Dependencies: 201
-- Name: a_reuniao_profissional; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE a_reuniao_profissional FROM PUBLIC;
-- REVOKE ALL ON TABLE a_reuniao_profissional FROM oasis;
GRANT ALL ON TABLE a_reuniao_profissional TO oasis;


--
-- TOC entry 5168 (class 0 OID 0)
-- Dependencies: 206
-- Name: b_area_conhecimento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_area_conhecimento FROM PUBLIC;
-- REVOKE ALL ON TABLE b_area_conhecimento FROM oasis;
GRANT ALL ON TABLE b_area_conhecimento TO oasis;


--
-- TOC entry 5170 (class 0 OID 0)
-- Dependencies: 207
-- Name: b_atividade; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_atividade FROM PUBLIC;
-- REVOKE ALL ON TABLE b_atividade FROM oasis;
GRANT ALL ON TABLE b_atividade TO oasis;


--
-- TOC entry 5175 (class 0 OID 0)
-- Dependencies: 208
-- Name: b_box_inicio; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_box_inicio FROM PUBLIC;
-- REVOKE ALL ON TABLE b_box_inicio FROM oasis;
GRANT ALL ON TABLE b_box_inicio TO oasis;


--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 210
-- Name: b_conhecimento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_conhecimento FROM PUBLIC;
-- REVOKE ALL ON TABLE b_conhecimento FROM oasis;
GRANT ALL ON TABLE b_conhecimento TO oasis;


--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 213
-- Name: b_etapa; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_etapa FROM PUBLIC;
-- REVOKE ALL ON TABLE b_etapa FROM oasis;
GRANT ALL ON TABLE b_etapa TO oasis;


--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 214
-- Name: b_evento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_evento FROM PUBLIC;
-- REVOKE ALL ON TABLE b_evento FROM oasis;
GRANT ALL ON TABLE b_evento TO oasis;


--
-- TOC entry 5179 (class 0 OID 0)
-- Dependencies: 216
-- Name: b_grupo_fator; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_grupo_fator FROM PUBLIC;
-- REVOKE ALL ON TABLE b_grupo_fator FROM oasis;
GRANT ALL ON TABLE b_grupo_fator TO oasis;


--
-- TOC entry 5180 (class 0 OID 0)
-- Dependencies: 218
-- Name: b_item_grupo_fator; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_item_grupo_fator FROM PUBLIC;
-- REVOKE ALL ON TABLE b_item_grupo_fator FROM oasis;
GRANT ALL ON TABLE b_item_grupo_fator TO oasis;


--
-- TOC entry 5181 (class 0 OID 0)
-- Dependencies: 221
-- Name: b_item_parecer_tecnico; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_item_parecer_tecnico FROM PUBLIC;
-- REVOKE ALL ON TABLE b_item_parecer_tecnico FROM oasis;
GRANT ALL ON TABLE b_item_parecer_tecnico TO oasis;


--
-- TOC entry 5182 (class 0 OID 0)
-- Dependencies: 225
-- Name: b_menu; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_menu FROM PUBLIC;
-- REVOKE ALL ON TABLE b_menu FROM oasis;
GRANT ALL ON TABLE b_menu TO oasis;


--
-- TOC entry 5183 (class 0 OID 0)
-- Dependencies: 227
-- Name: b_nivel_servico; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_nivel_servico FROM PUBLIC;
-- REVOKE ALL ON TABLE b_nivel_servico FROM oasis;
GRANT ALL ON TABLE b_nivel_servico TO oasis;


--
-- TOC entry 5184 (class 0 OID 0)
-- Dependencies: 229
-- Name: b_penalidade; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_penalidade FROM PUBLIC;
-- REVOKE ALL ON TABLE b_penalidade FROM oasis;
GRANT ALL ON TABLE b_penalidade TO oasis;


--
-- TOC entry 5187 (class 0 OID 0)
-- Dependencies: 230
-- Name: b_perfil; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_perfil FROM PUBLIC;
-- REVOKE ALL ON TABLE b_perfil FROM oasis;
GRANT ALL ON TABLE b_perfil TO oasis;


--
-- TOC entry 5188 (class 0 OID 0)
-- Dependencies: 234
-- Name: b_relacao_contratual; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_relacao_contratual FROM PUBLIC;
-- REVOKE ALL ON TABLE b_relacao_contratual FROM oasis;
GRANT ALL ON TABLE b_relacao_contratual TO oasis;


--
-- TOC entry 5189 (class 0 OID 0)
-- Dependencies: 237
-- Name: b_status; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_status FROM PUBLIC;
-- REVOKE ALL ON TABLE b_status FROM oasis;
GRANT ALL ON TABLE b_status TO oasis;


--
-- TOC entry 5190 (class 0 OID 0)
-- Dependencies: 242
-- Name: b_tipo_conhecimento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_tipo_conhecimento FROM PUBLIC;
-- REVOKE ALL ON TABLE b_tipo_conhecimento FROM oasis;
GRANT ALL ON TABLE b_tipo_conhecimento TO oasis;


--
-- TOC entry 5191 (class 0 OID 0)
-- Dependencies: 243
-- Name: b_tipo_dado_tecnico; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_tipo_dado_tecnico FROM PUBLIC;
-- REVOKE ALL ON TABLE b_tipo_dado_tecnico FROM oasis;
GRANT ALL ON TABLE b_tipo_dado_tecnico TO oasis;


--
-- TOC entry 5193 (class 0 OID 0)
-- Dependencies: 244
-- Name: b_tipo_documentacao; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_tipo_documentacao FROM PUBLIC;
-- REVOKE ALL ON TABLE b_tipo_documentacao FROM oasis;
GRANT ALL ON TABLE b_tipo_documentacao TO oasis;


--
-- TOC entry 5194 (class 0 OID 0)
-- Dependencies: 245
-- Name: b_tipo_produto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_tipo_produto FROM PUBLIC;
-- REVOKE ALL ON TABLE b_tipo_produto FROM oasis;
GRANT ALL ON TABLE b_tipo_produto TO oasis;


--
-- TOC entry 5195 (class 0 OID 0)
-- Dependencies: 247
-- Name: b_unidade; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE b_unidade FROM PUBLIC;
-- REVOKE ALL ON TABLE b_unidade FROM oasis;
GRANT ALL ON TABLE b_unidade TO oasis;


--
-- TOC entry 5196 (class 0 OID 0)
-- Dependencies: 248
-- Name: s_acompanhamento_proposta; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_acompanhamento_proposta FROM PUBLIC;
-- REVOKE ALL ON TABLE s_acompanhamento_proposta FROM oasis;
GRANT ALL ON TABLE s_acompanhamento_proposta TO oasis;


--
-- TOC entry 5199 (class 0 OID 0)
-- Dependencies: 255
-- Name: s_ator; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_ator FROM PUBLIC;
-- REVOKE ALL ON TABLE s_ator FROM oasis;
GRANT ALL ON TABLE s_ator TO oasis;


--
-- TOC entry 5200 (class 0 OID 0)
-- Dependencies: 256
-- Name: s_base_conhecimento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_base_conhecimento FROM PUBLIC;
-- REVOKE ALL ON TABLE s_base_conhecimento FROM oasis;
GRANT ALL ON TABLE s_base_conhecimento TO oasis;


--
-- TOC entry 5201 (class 0 OID 0)
-- Dependencies: 258
-- Name: s_caso_de_uso; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_caso_de_uso FROM PUBLIC;
-- REVOKE ALL ON TABLE s_caso_de_uso FROM oasis;
GRANT ALL ON TABLE s_caso_de_uso TO oasis;


--
-- TOC entry 5202 (class 0 OID 0)
-- Dependencies: 259
-- Name: s_coluna; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_coluna FROM PUBLIC;
-- REVOKE ALL ON TABLE s_coluna FROM oasis;
GRANT ALL ON TABLE s_coluna TO oasis;


--
-- TOC entry 5203 (class 0 OID 0)
-- Dependencies: 260
-- Name: s_complemento; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_complemento FROM PUBLIC;
-- REVOKE ALL ON TABLE s_complemento FROM oasis;
GRANT ALL ON TABLE s_complemento TO oasis;


--
-- TOC entry 5204 (class 0 OID 0)
-- Dependencies: 261
-- Name: s_condicao; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_condicao FROM PUBLIC;
-- REVOKE ALL ON TABLE s_condicao FROM oasis;
GRANT ALL ON TABLE s_condicao TO oasis;


--
-- TOC entry 5205 (class 0 OID 0)
-- Dependencies: 262
-- Name: s_config_banco_de_dados; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_config_banco_de_dados FROM PUBLIC;
-- REVOKE ALL ON TABLE s_config_banco_de_dados FROM oasis;
GRANT ALL ON TABLE s_config_banco_de_dados TO oasis;


--
-- TOC entry 5206 (class 0 OID 0)
-- Dependencies: 263
-- Name: s_contato_empresa; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_contato_empresa FROM PUBLIC;
-- REVOKE ALL ON TABLE s_contato_empresa FROM oasis;
GRANT ALL ON TABLE s_contato_empresa TO oasis;


--
-- TOC entry 5207 (class 0 OID 0)
-- Dependencies: 264
-- Name: s_contrato; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_contrato FROM PUBLIC;
-- REVOKE ALL ON TABLE s_contrato FROM oasis;
GRANT ALL ON TABLE s_contrato TO oasis;


--
-- TOC entry 5208 (class 0 OID 0)
-- Dependencies: 266
-- Name: s_demanda; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_demanda FROM PUBLIC;
-- REVOKE ALL ON TABLE s_demanda FROM oasis;
GRANT ALL ON TABLE s_demanda TO oasis;


--
-- TOC entry 5209 (class 0 OID 0)
-- Dependencies: 268
-- Name: s_empresa; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_empresa FROM PUBLIC;
-- REVOKE ALL ON TABLE s_empresa FROM oasis;
GRANT ALL ON TABLE s_empresa TO oasis;


--
-- TOC entry 5210 (class 0 OID 0)
-- Dependencies: 270
-- Name: s_extrato_mensal; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_extrato_mensal FROM PUBLIC;
-- REVOKE ALL ON TABLE s_extrato_mensal FROM oasis;
GRANT ALL ON TABLE s_extrato_mensal TO oasis;


--
-- TOC entry 5211 (class 0 OID 0)
-- Dependencies: 271
-- Name: s_extrato_mensal_parcela; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_extrato_mensal_parcela FROM PUBLIC;
-- REVOKE ALL ON TABLE s_extrato_mensal_parcela FROM oasis;
GRANT ALL ON TABLE s_extrato_mensal_parcela TO oasis;


--
-- TOC entry 5212 (class 0 OID 0)
-- Dependencies: 272
-- Name: s_fale_conosco; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_fale_conosco FROM PUBLIC;
-- REVOKE ALL ON TABLE s_fale_conosco FROM oasis;
GRANT ALL ON TABLE s_fale_conosco TO oasis;


--
-- TOC entry 5213 (class 0 OID 0)
-- Dependencies: 275
-- Name: s_historico; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_historico FROM PUBLIC;
-- REVOKE ALL ON TABLE s_historico FROM oasis;
GRANT ALL ON TABLE s_historico TO oasis;


--
-- TOC entry 5214 (class 0 OID 0)
-- Dependencies: 276
-- Name: s_historico_execucao_demanda; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_historico_execucao_demanda FROM PUBLIC;
-- REVOKE ALL ON TABLE s_historico_execucao_demanda FROM oasis;
GRANT ALL ON TABLE s_historico_execucao_demanda TO oasis;


--
-- TOC entry 5216 (class 0 OID 0)
-- Dependencies: 279
-- Name: s_historico_projeto_continuado; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_historico_projeto_continuado FROM PUBLIC;
-- REVOKE ALL ON TABLE s_historico_projeto_continuado FROM oasis;
GRANT ALL ON TABLE s_historico_projeto_continuado TO oasis;


--
-- TOC entry 5217 (class 0 OID 0)
-- Dependencies: 280
-- Name: s_historico_proposta; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_historico_proposta FROM PUBLIC;
-- REVOKE ALL ON TABLE s_historico_proposta FROM oasis;
GRANT ALL ON TABLE s_historico_proposta TO oasis;


--
-- TOC entry 5218 (class 0 OID 0)
-- Dependencies: 282
-- Name: s_historico_proposta_parcela; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_historico_proposta_parcela FROM PUBLIC;
-- REVOKE ALL ON TABLE s_historico_proposta_parcela FROM oasis;
GRANT ALL ON TABLE s_historico_proposta_parcela TO oasis;


--
-- TOC entry 5219 (class 0 OID 0)
-- Dependencies: 283
-- Name: s_historico_proposta_produto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_historico_proposta_produto FROM PUBLIC;
-- REVOKE ALL ON TABLE s_historico_proposta_produto FROM oasis;
GRANT ALL ON TABLE s_historico_proposta_produto TO oasis;


--
-- TOC entry 5220 (class 0 OID 0)
-- Dependencies: 284
-- Name: s_interacao; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_interacao FROM PUBLIC;
-- REVOKE ALL ON TABLE s_interacao FROM oasis;
GRANT ALL ON TABLE s_interacao TO oasis;


--
-- TOC entry 5222 (class 0 OID 0)
-- Dependencies: 289
-- Name: s_modulo; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_modulo FROM PUBLIC;
-- REVOKE ALL ON TABLE s_modulo FROM oasis;
GRANT ALL ON TABLE s_modulo TO oasis;


--
-- TOC entry 5223 (class 0 OID 0)
-- Dependencies: 290
-- Name: s_modulo_continuado; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_modulo_continuado FROM PUBLIC;
-- REVOKE ALL ON TABLE s_modulo_continuado FROM oasis;
GRANT ALL ON TABLE s_modulo_continuado TO oasis;


--
-- TOC entry 5224 (class 0 OID 0)
-- Dependencies: 291
-- Name: s_objeto_contrato; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_objeto_contrato FROM PUBLIC;
-- REVOKE ALL ON TABLE s_objeto_contrato FROM oasis;
GRANT ALL ON TABLE s_objeto_contrato TO oasis;


--
-- TOC entry 5225 (class 0 OID 0)
-- Dependencies: 292
-- Name: s_ocorrencia_administrativa; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_ocorrencia_administrativa FROM PUBLIC;
-- REVOKE ALL ON TABLE s_ocorrencia_administrativa FROM oasis;
GRANT ALL ON TABLE s_ocorrencia_administrativa TO oasis;


--
-- TOC entry 5226 (class 0 OID 0)
-- Dependencies: 293
-- Name: s_parcela; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_parcela FROM PUBLIC;
-- REVOKE ALL ON TABLE s_parcela FROM oasis;
GRANT ALL ON TABLE s_parcela TO oasis;


--
-- TOC entry 5227 (class 0 OID 0)
-- Dependencies: 294
-- Name: s_penalizacao; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_penalizacao FROM PUBLIC;
-- REVOKE ALL ON TABLE s_penalizacao FROM oasis;
GRANT ALL ON TABLE s_penalizacao TO oasis;


--
-- TOC entry 5228 (class 0 OID 0)
-- Dependencies: 296
-- Name: s_pre_demanda; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_pre_demanda FROM PUBLIC;
-- REVOKE ALL ON TABLE s_pre_demanda FROM oasis;
GRANT ALL ON TABLE s_pre_demanda TO oasis;


--
-- TOC entry 5229 (class 0 OID 0)
-- Dependencies: 297
-- Name: s_pre_projeto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_pre_projeto FROM PUBLIC;
-- REVOKE ALL ON TABLE s_pre_projeto FROM oasis;
GRANT ALL ON TABLE s_pre_projeto TO oasis;


--
-- TOC entry 5230 (class 0 OID 0)
-- Dependencies: 299
-- Name: s_previsao_projeto_diario; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_previsao_projeto_diario FROM PUBLIC;
-- REVOKE ALL ON TABLE s_previsao_projeto_diario FROM oasis;
GRANT ALL ON TABLE s_previsao_projeto_diario TO oasis;


--
-- TOC entry 5231 (class 0 OID 0)
-- Dependencies: 300
-- Name: s_processamento_parcela; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_processamento_parcela FROM PUBLIC;
-- REVOKE ALL ON TABLE s_processamento_parcela FROM oasis;
GRANT ALL ON TABLE s_processamento_parcela TO oasis;


--
-- TOC entry 5232 (class 0 OID 0)
-- Dependencies: 301
-- Name: s_processamento_proposta; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_processamento_proposta FROM PUBLIC;
-- REVOKE ALL ON TABLE s_processamento_proposta FROM oasis;
GRANT ALL ON TABLE s_processamento_proposta TO oasis;


--
-- TOC entry 5233 (class 0 OID 0)
-- Dependencies: 302
-- Name: s_produto_parcela; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_produto_parcela FROM PUBLIC;
-- REVOKE ALL ON TABLE s_produto_parcela FROM oasis;
GRANT ALL ON TABLE s_produto_parcela TO oasis;


--
-- TOC entry 5234 (class 0 OID 0)
-- Dependencies: 303
-- Name: s_profissional; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_profissional FROM PUBLIC;
-- REVOKE ALL ON TABLE s_profissional FROM oasis;
GRANT ALL ON TABLE s_profissional TO oasis;


--
-- TOC entry 5235 (class 0 OID 0)
-- Dependencies: 304
-- Name: s_projeto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_projeto FROM PUBLIC;
-- REVOKE ALL ON TABLE s_projeto FROM oasis;
GRANT ALL ON TABLE s_projeto TO oasis;


--
-- TOC entry 5236 (class 0 OID 0)
-- Dependencies: 305
-- Name: s_projeto_continuado; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_projeto_continuado FROM PUBLIC;
-- REVOKE ALL ON TABLE s_projeto_continuado FROM oasis;
GRANT ALL ON TABLE s_projeto_continuado TO oasis;


--
-- TOC entry 5237 (class 0 OID 0)
-- Dependencies: 306
-- Name: s_projeto_previsto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_projeto_previsto FROM PUBLIC;
-- REVOKE ALL ON TABLE s_projeto_previsto FROM oasis;
GRANT ALL ON TABLE s_projeto_previsto TO oasis;


--
-- TOC entry 5238 (class 0 OID 0)
-- Dependencies: 307
-- Name: s_proposta; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_proposta FROM PUBLIC;
-- REVOKE ALL ON TABLE s_proposta FROM oasis;
GRANT ALL ON TABLE s_proposta TO oasis;


--
-- TOC entry 5239 (class 0 OID 0)
-- Dependencies: 310
-- Name: s_reuniao; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_reuniao FROM PUBLIC;
-- REVOKE ALL ON TABLE s_reuniao FROM oasis;
GRANT ALL ON TABLE s_reuniao TO oasis;


--
-- TOC entry 5240 (class 0 OID 0)
-- Dependencies: 311
-- Name: s_reuniao_geral; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_reuniao_geral FROM PUBLIC;
-- REVOKE ALL ON TABLE s_reuniao_geral FROM oasis;
GRANT ALL ON TABLE s_reuniao_geral TO oasis;


--
-- TOC entry 5241 (class 0 OID 0)
-- Dependencies: 312
-- Name: s_situacao_projeto; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_situacao_projeto FROM PUBLIC;
-- REVOKE ALL ON TABLE s_situacao_projeto FROM oasis;
GRANT ALL ON TABLE s_situacao_projeto TO oasis;


--
-- TOC entry 5242 (class 0 OID 0)
-- Dependencies: 313
-- Name: s_solicitacao; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_solicitacao FROM PUBLIC;
-- REVOKE ALL ON TABLE s_solicitacao FROM oasis;
GRANT ALL ON TABLE s_solicitacao TO oasis;


--
-- TOC entry 5243 (class 0 OID 0)
-- Dependencies: 315
-- Name: s_tabela; Type: ACL; Schema: oasis; Owner: postgres
--

-- REVOKE ALL ON TABLE s_tabela FROM PUBLIC;
-- REVOKE ALL ON TABLE s_tabela FROM oasis;
GRANT ALL ON TABLE s_tabela TO oasis;


-- Completed on 2011-10-25 13:39:12 BRST

--
-- PostgreSQL database dump complete
--

