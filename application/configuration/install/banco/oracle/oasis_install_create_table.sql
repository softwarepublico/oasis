--
-- Oracle database dump
--

CREATE TABLE a_baseline_item_controle
    (cd_projeto                     NUMBER(8,0) NOT NULL,
    dt_baseline                    TIMESTAMP (6) NOT NULL,
    cd_item_controle_baseline      NUMBER(8,0) NOT NULL,
    cd_item_controlado             NUMBER(8,0) NOT NULL,
    dt_versao_item_controlado      TIMESTAMP (6) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_baseline_item_controle
ADD CONSTRAINT pk_oasis2_150 PRIMARY KEY (cd_projeto, dt_baseline, 
  cd_item_controle_baseline, cd_item_controlado, dt_versao_item_controlado)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON TABLE a_baseline_item_controle IS 'Teste do nil na tabela do oasis2'
/
COMMENT ON COLUMN a_baseline_item_controle.cd_item_controlado IS 'código do item de controle da tabela'
/
COMMENT ON COLUMN a_baseline_item_controle.cd_item_controle_baseline IS 'código do item de controle da baseline'
/
COMMENT ON COLUMN a_baseline_item_controle.cd_projeto IS 'código de integridade dos projetos '
/
COMMENT ON COLUMN a_baseline_item_controle.dt_baseline IS 'data da geração da baseline do projeto'
/

CREATE TABLE a_conhecimento_projeto
    (cd_tipo_conhecimento           NUMBER(*,0) NOT NULL,
    cd_conhecimento                NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_conhecimento_projeto
ADD CONSTRAINT pk_oasis2_149 PRIMARY KEY (cd_tipo_conhecimento, cd_conhecimento, 
  cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON TABLE a_conhecimento_projeto IS 'tabela de conhecimento do projeto para o sistema oasis2'
/
COMMENT ON COLUMN a_conhecimento_projeto.cd_conhecimento IS 'código do conhecimento e é do tipo de numerico'
/
COMMENT ON COLUMN a_conhecimento_projeto.cd_projeto IS 'fk da tabela projeto'
/
COMMENT ON COLUMN a_conhecimento_projeto.cd_tipo_conhecimento IS 'chave da tabela do tipo de conhecimento'
/

CREATE TABLE a_conjunto_medida_medicao
    (cd_conjunto_medida             NUMBER(8,0) NOT NULL,
    cd_box_inicio                  NUMBER(8,0) NOT NULL,
    cd_medicao                     NUMBER(8,0) NOT NULL,
    st_nivel_conjunto_medida       CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_conjunto_medida_medicao
ADD CONSTRAINT pk_oasis2_148 PRIMARY KEY (cd_conjunto_medida, cd_box_inicio, 
  cd_medicao)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_contrato_definicao_metrica
    (cd_contrato                    NUMBER(8,0) NOT NULL,
    cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0),
    nf_fator_relacao_metrica_pad   NUMBER(4,0),
    st_metrica_padrao              CHAR(1))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_contrato_definicao_metrica
ADD CONSTRAINT pk_oasis2_147 PRIMARY KEY (cd_contrato, cd_definicao_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_contrato_evento
    (cd_contrato                    NUMBER(*,0) NOT NULL,
    cd_evento                      NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_contrato_evento
ADD CONSTRAINT pk_oasis2_146 PRIMARY KEY (cd_contrato, cd_evento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_contrato_projeto
    (cd_contrato                    NUMBER(8,0) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_contrato_projeto
ADD CONSTRAINT pk_oasis2_145 PRIMARY KEY (cd_contrato, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_controle
    (cd_controle                    NUMBER(*,0) NOT NULL,
    cd_projeto_previsto            NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_contrato                    NUMBER(*,0) NOT NULL,
    ni_horas                       NUMBER(8,1),
    st_controle                    CHAR(1),
    st_modulo_proposta             CHAR(1),
    dt_lancamento                  TIMESTAMP (6),
    cd_profissional                NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_controle
ADD CONSTRAINT pk_oasis2_144 PRIMARY KEY (cd_controle, cd_projeto_previsto, 
  cd_projeto, cd_proposta, cd_contrato)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_definicao_processo
    (cd_perfil                      NUMBER(*,0) NOT NULL,
    cd_funcionalidade              NUMBER(8,0) NOT NULL,
    st_definicao_processo          CHAR(1) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_definicao_processo
ADD CONSTRAINT pk_oasis2_143 PRIMARY KEY (cd_perfil, cd_funcionalidade, 
  st_definicao_processo)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_demanda_prof_nivel_servico
    (cd_demanda                     NUMBER(*,0) NOT NULL,
    cd_profissional                NUMBER(*,0) NOT NULL,
    cd_nivel_servico               NUMBER(*,0) NOT NULL,
    dt_fechamento_nivel_servico    TIMESTAMP (6),
    st_fechamento_nivel_servico    CHAR(1),
    st_fechamento_gerente          CHAR(1),
    dt_fechamento_gerente          TIMESTAMP (6),
    dt_leitura_nivel_servico       TIMESTAMP (6),
    dt_demanda_nivel_servico       TIMESTAMP (6),
    tx_motivo_fechamento           VARCHAR2(4000),
    tx_obs_nivel_servico           VARCHAR2(4000),
    dt_justificativa_nivel_servico TIMESTAMP (6),
    tx_justificativa_nivel_servico VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_demanda_prof_nivel_servico
ADD CONSTRAINT pk_oasis2_142 PRIMARY KEY (cd_demanda, cd_profissional, 
  cd_nivel_servico)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_demanda_profissional
    (cd_profissional                NUMBER(*,0) NOT NULL,
    cd_demanda                     NUMBER(*,0) NOT NULL,
    dt_demanda_profissional        TIMESTAMP (6),
    st_fechamento_demanda          CHAR(1),
    dt_fechamento_demanda          TIMESTAMP (6),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_demanda_profissional
ADD CONSTRAINT pk_oasis2_141 PRIMARY KEY (cd_profissional, cd_demanda)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_disponibilidade_servico_doc
    (cd_disponibilidade_servico     NUMBER(8,0) NOT NULL,
    cd_objeto                      NUMBER(8,0) NOT NULL,
    cd_tipo_documentacao           NUMBER(8,0) NOT NULL,
    dt_doc_disponibilidade_servico TIMESTAMP (6) NOT NULL,
    tx_nome_arq_disp_servico       VARCHAR2(4000),
    tx_arquivo_disp_servico        VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_disponibilidade_servico_doc
ADD CONSTRAINT pk_oasis2_140 PRIMARY KEY (cd_disponibilidade_servico, cd_objeto, 
  cd_tipo_documentacao, dt_doc_disponibilidade_servico)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_documentacao_profissional
    (dt_documentacao_profissional   TIMESTAMP (6) NOT NULL,
    cd_tipo_documentacao           NUMBER(*,0) NOT NULL,
    cd_profissional                NUMBER(*,0) NOT NULL,
    tx_arq_documentacao_prof       VARCHAR2(4000),
    tx_nome_arquivo                VARCHAR2(100),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_documentacao_profissional
ADD CONSTRAINT pk_oasis2_139 PRIMARY KEY (dt_documentacao_profissional, 
  cd_tipo_documentacao, cd_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_documentacao_projeto
    (dt_documentacao_projeto        TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_tipo_documentacao           NUMBER(*,0) NOT NULL,
    tx_arq_documentacao_projeto    VARCHAR2(4000),
    tx_nome_arquivo                VARCHAR2(100),
    st_documentacao_controle       CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_documentacao_projeto
ADD CONSTRAINT pk_oasis2_138 PRIMARY KEY (dt_documentacao_projeto, cd_projeto, 
  cd_tipo_documentacao)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_funcionalidade_menu
    (cd_funcionalidade              NUMBER(8,0) NOT NULL,
    cd_menu                        NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_funcionalidade_menu
ADD CONSTRAINT pk_oasis2_137 PRIMARY KEY (cd_funcionalidade, cd_menu)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_gerencia_mudanca
    (dt_gerencia_mudanca            TIMESTAMP (6) NOT NULL,
    cd_item_controle_baseline      NUMBER(8,0) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_item_controlado             NUMBER(8,0) NOT NULL,
    dt_versao_item_controlado      TIMESTAMP (6) NOT NULL,
    tx_motivo_mudanca              VARCHAR2(4000),
    st_mudanca_metrica             CHAR(1),
    ni_custo_provavel_mudanca      NUMBER(8,0),
    st_reuniao                     CHAR(1),
    tx_decisao_mudanca             VARCHAR2(4000),
    dt_decisao_mudanca             DATE,
    cd_reuniao                     NUMBER(8,0),
    cd_projeto_reuniao             NUMBER(8,0),
    st_decisao_mudanca             CHAR(1),
    st_execucao_mudanca            CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_gerencia_mudanca
ADD CONSTRAINT pk_oasis2_136 PRIMARY KEY (dt_gerencia_mudanca, 
  cd_item_controle_baseline, cd_projeto, cd_item_controlado, 
  dt_versao_item_controlado)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_informacao_tecnica
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_tipo_dado_tecnico           NUMBER(*,0) NOT NULL,
    tx_conteudo_informacao_tecnica VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_informacao_tecnica
ADD CONSTRAINT pk_oasis2_135 PRIMARY KEY (cd_projeto, cd_tipo_dado_tecnico)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_item_teste_caso_de_uso
    (cd_item_teste_caso_de_uso      NUMBER(*,0) NOT NULL,
    cd_item_teste                  NUMBER(8,0) NOT NULL,
    cd_caso_de_uso                 NUMBER(*,0) NOT NULL,
    dt_versao_caso_de_uso          TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    st_analise                     CHAR(1),
    tx_analise                     VARCHAR2(4000),
    dt_analise                     DATE,
    cd_profissional_analise        NUMBER(*,0),
    st_solucao                     CHAR(1),
    tx_solucao                     VARCHAR2(4000),
    dt_solucao                     DATE,
    cd_profissional_solucao        NUMBER(*,0),
    st_homologacao                 CHAR(1),
    tx_homologacao                 VARCHAR2(4000),
    dt_homologacao                 DATE,
    cd_profissional_homologacao    NUMBER(*,0),
    st_item_teste_caso_de_uso      CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_item_teste_caso_de_uso
ADD CONSTRAINT pk_oasis2_134 PRIMARY KEY (cd_item_teste, cd_modulo, cd_projeto, 
  cd_caso_de_uso, dt_versao_caso_de_uso, cd_item_teste_caso_de_uso)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN a_item_teste_caso_de_uso.st_item_teste_caso_de_uso IS 'null : em aberto
''H''  : para homologar
''F''  : homologado'
/

CREATE TABLE a_item_teste_caso_de_uso_doc
    (cd_arq_item_teste_caso_de_uso  NUMBER(8,0) NOT NULL,
    cd_item_teste_caso_de_uso      NUMBER(*,0) NOT NULL,
    cd_item_teste                  NUMBER(8,0) NOT NULL,
    cd_caso_de_uso                 NUMBER(*,0) NOT NULL,
    dt_versao_caso_de_uso          TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    cd_tipo_documentacao           NUMBER(*,0) NOT NULL,
    tx_nome_arq_teste_caso_de_uso  VARCHAR2(4000),
    tx_arq_item_teste_caso_de_uso  VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_item_teste_caso_de_uso_doc
ADD CONSTRAINT pk_oasis2_133 PRIMARY KEY (cd_arq_item_teste_caso_de_uso, 
  cd_item_teste, cd_modulo, cd_projeto, cd_caso_de_uso, dt_versao_caso_de_uso, 
  cd_item_teste_caso_de_uso)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_item_teste_regra_negocio
    (cd_item_teste_regra_negocio    NUMBER(*,0) NOT NULL,
    cd_item_teste                  NUMBER(*,0) NOT NULL,
    cd_regra_negocio               NUMBER(*,0) NOT NULL,
    dt_regra_negocio               TIMESTAMP (6) NOT NULL,
    cd_projeto_regra_negocio       NUMBER(8,0) NOT NULL,
    st_analise                     CHAR(1),
    tx_analise                     VARCHAR2(4000),
    dt_analise                     DATE,
    cd_profissional_analise        NUMBER(*,0),
    st_solucao                     CHAR(1),
    tx_solucao                     VARCHAR2(4000),
    dt_solucao                     DATE,
    cd_profissional_solucao        NUMBER(*,0),
    st_homologacao                 CHAR(1),
    tx_homologacao                 VARCHAR2(4000),
    dt_homologacao                 DATE,
    cd_profissional_homologacao    NUMBER(*,0),
    st_item_teste_regra_negocio    CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_item_teste_regra_negocio
ADD CONSTRAINT pk_oasis2_132 PRIMARY KEY (dt_regra_negocio, cd_regra_negocio, 
  cd_item_teste, cd_projeto_regra_negocio, cd_item_teste_regra_negocio)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN a_item_teste_regra_negocio.st_item_teste_regra_negocio IS 'null : em aberto
''H''  : para homologar
''F''  : homologado'
/

CREATE TABLE a_item_teste_regra_negocio_doc
    (cd_arq_item_teste_regra_neg    NUMBER(8,0) NOT NULL,
    cd_item_teste_regra_negocio    NUMBER(*,0) NOT NULL,
    cd_item_teste                  NUMBER(*,0) NOT NULL,
    cd_regra_negocio               NUMBER(*,0) NOT NULL,
    dt_regra_negocio               TIMESTAMP (6) NOT NULL,
    cd_projeto_regra_negocio       NUMBER(8,0) NOT NULL,
    cd_tipo_documentacao           NUMBER(*,0) NOT NULL,
    tx_nome_arq_teste_regra_negoc  VARCHAR2(4000),
    tx_arq_item_teste_regra_negoc  VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_item_teste_regra_negocio_doc
ADD CONSTRAINT pk_oasis2_131 PRIMARY KEY (cd_arq_item_teste_regra_neg, 
  dt_regra_negocio, cd_regra_negocio, cd_item_teste, cd_projeto_regra_negocio, 
  cd_item_teste_regra_negocio)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_item_teste_requisito
    (cd_item_teste_requisito        NUMBER(*,0) NOT NULL,
    cd_item_teste                  NUMBER(*,0) NOT NULL,
    cd_requisito                   NUMBER(*,0) NOT NULL,
    dt_versao_requisito            TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    st_analise                     CHAR(1),
    tx_analise                     VARCHAR2(4000),
    dt_analise                     DATE,
    cd_profissional_analise        NUMBER(*,0),
    st_solucao                     CHAR(1),
    tx_solucao                     VARCHAR2(4000),
    dt_solucao                     DATE,
    cd_profissional_solucao        NUMBER(*,0),
    st_homologacao                 CHAR(1),
    tx_homologacao                 VARCHAR2(4000),
    dt_homologacao                 DATE,
    cd_profissional_homologacao    NUMBER(*,0),
    st_item_teste_requisito        CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_item_teste_requisito
ADD CONSTRAINT pk_oasis2_130 PRIMARY KEY (cd_item_teste_requisito, cd_requisito, 
  dt_versao_requisito, cd_projeto, cd_item_teste)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN a_item_teste_requisito.st_item_teste_requisito IS 'null : em aberto
''H''  : para homologar
''F''  : homologado'
/

CREATE TABLE a_item_teste_requisito_doc
    (cd_arq_item_teste_requisito    NUMBER(8,0) NOT NULL,
    cd_item_teste_requisito        NUMBER(*,0) NOT NULL,
    cd_item_teste                  NUMBER(*,0) NOT NULL,
    cd_requisito                   NUMBER(*,0) NOT NULL,
    dt_versao_requisito            TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_tipo_documentacao           NUMBER(*,0) NOT NULL,
    tx_nome_arq_teste_requisito    VARCHAR2(4000),
    tx_arq_item_teste_requisito    VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_item_teste_requisito_doc
ADD CONSTRAINT pk_oasis2_129 PRIMARY KEY (cd_arq_item_teste_requisito, 
  cd_item_teste_requisito, cd_requisito, dt_versao_requisito, cd_projeto, 
  cd_item_teste)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_medicao_medida
    (cd_medicao                     NUMBER(*,0) NOT NULL,
    cd_medida                      NUMBER(*,0) NOT NULL,
    st_prioridade_medida           CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_medicao_medida
ADD CONSTRAINT pk_oasis2_128 PRIMARY KEY (cd_medicao, cd_medida)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN a_medicao_medida.st_prioridade_medida IS 'valores possíveis:
A=Altíssima;
L=Alta;
M=Média; e
B=Baixa.'
/

CREATE TABLE a_objeto_contrato_atividade
    (cd_objeto                      NUMBER(8,0) NOT NULL,
    cd_etapa                       NUMBER(8,0) NOT NULL,
    cd_atividade                   NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_objeto_contrato_atividade
ADD CONSTRAINT pk_oasis2_127 PRIMARY KEY (cd_objeto, cd_etapa, cd_atividade)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_objeto_contrato_papel_prof
    (cd_objeto                      NUMBER(8,0) NOT NULL,
    cd_papel_profissional          NUMBER(8,0) NOT NULL,
    tx_descricao_papel_prof        VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_objeto_contrato_papel_prof
ADD CONSTRAINT pk_oasis2_126 PRIMARY KEY (cd_objeto, cd_papel_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_objeto_contrato_perfil_prof
    (cd_objeto                      NUMBER(8,0) NOT NULL,
    cd_perfil_profissional         NUMBER(8,0) NOT NULL,
    tx_descricao_perfil_prof       VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_objeto_contrato_perfil_prof
ADD CONSTRAINT pk_oasis2_125 PRIMARY KEY (cd_objeto, cd_perfil_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_opcao_resp_pergunta_pedido
    (cd_pergunta_pedido             NUMBER(8,0) NOT NULL,
    cd_resposta_pedido             NUMBER(8,0) NOT NULL,
    st_resposta_texto              CHAR(1) DEFAULT 'N'  NOT NULL,
    ni_ordem_apresenta             NUMBER(8,0) DEFAULT 0  NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_opcao_resp_pergunta_pedido
ADD CONSTRAINT pk_oasis_157 PRIMARY KEY (cd_pergunta_pedido, cd_resposta_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_parecer_tecnico_parcela
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_parcela                     NUMBER(*,0) NOT NULL,
    cd_item_parecer_tecnico        NUMBER(*,0) NOT NULL,
    cd_processamento_parcela       NUMBER(8,0) NOT NULL,
    st_avaliacao                   CHAR(2),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_parecer_tecnico_parcela
ADD CONSTRAINT pk_oasis2_124 PRIMARY KEY (cd_projeto, cd_proposta, cd_parcela, 
  cd_item_parecer_tecnico, cd_processamento_parcela)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_parecer_tecnico_proposta
    (cd_item_parecer_tecnico        NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_processamento_proposta      NUMBER(8,0) NOT NULL,
    st_avaliacao                   CHAR(2),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_parecer_tecnico_proposta
ADD CONSTRAINT pk_oasis2_123 PRIMARY KEY (cd_item_parecer_tecnico, cd_proposta, 
  cd_projeto, cd_processamento_proposta)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_perfil_box_inicio
    (cd_perfil                      NUMBER(*,0) NOT NULL,
    cd_box_inicio                  NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_perfil_box_inicio
ADD CONSTRAINT pk_oasis2_122 PRIMARY KEY (cd_perfil, cd_box_inicio, cd_objeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_perfil_menu
    (cd_perfil                      NUMBER(*,0) NOT NULL,
    cd_menu                        NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_perfil_menu
ADD CONSTRAINT pk_oasis2_121 PRIMARY KEY (cd_menu, cd_perfil, cd_objeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_perfil_menu_sistema
    (cd_perfil                      NUMBER(*,0) NOT NULL,
    cd_menu                        NUMBER(*,0) NOT NULL,
    st_perfil_menu                 CHAR(1) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_perfil_menu_sistema
ADD CONSTRAINT pk_oasis2_120 PRIMARY KEY (cd_perfil, cd_menu, st_perfil_menu)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN a_perfil_menu_sistema.st_perfil_menu IS 'P - Projeto
D - Demanda'
/

CREATE TABLE a_perfil_prof_papel_prof
    (cd_perfil_profissional         NUMBER(8,0) NOT NULL,
    cd_papel_profissional          NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_perfil_prof_papel_prof
ADD CONSTRAINT pk_oasis2_119 PRIMARY KEY (cd_perfil_profissional, 
  cd_papel_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_pergunta_depende_resp_pedido
    (cd_pergunta_depende            NUMBER(8,0) NOT NULL,
    cd_pergunta_pedido             NUMBER(8,0) NOT NULL,
    cd_resposta_pedido             NUMBER(8,0) NOT NULL,
    st_tipo_dependencia            CHAR(1) DEFAULT 'S'  NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_pergunta_depende_resp_pedido
ADD CONSTRAINT pk_oasis_158 PRIMARY KEY (cd_pergunta_depende, cd_pergunta_pedido, 
  cd_resposta_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_planejamento
    (cd_etapa                       NUMBER(*,0) NOT NULL,
    cd_atividade                   NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    dt_inicio_atividade            DATE,
    dt_fim_atividade               DATE,
    nf_porcentagem_execucao        NUMBER(*,0),
    tx_obs_atividade               VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_planejamento
ADD CONSTRAINT pk_oasis2_118 PRIMARY KEY (cd_etapa, cd_atividade, cd_projeto, 
  cd_modulo)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_profissional_conhecimento
    (cd_profissional                NUMBER(*,0) NOT NULL,
    cd_tipo_conhecimento           NUMBER(*,0) NOT NULL,
    cd_conhecimento                NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_profissional_conhecimento
ADD CONSTRAINT pk_oasis2_117 PRIMARY KEY (cd_profissional, cd_tipo_conhecimento, 
  cd_conhecimento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_profissional_mensageria
    (cd_profissional                NUMBER(*,0) NOT NULL,
    cd_mensageria                  NUMBER(8,0) NOT NULL,
    dt_leitura_mensagem            TIMESTAMP (6),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_profissional_mensageria
ADD CONSTRAINT pk_oasis2_116 PRIMARY KEY (cd_profissional, cd_mensageria)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_profissional_menu
    (cd_menu                        NUMBER(*,0) NOT NULL,
    cd_profissional                NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_profissional_menu
ADD CONSTRAINT pk_oasis2_115 PRIMARY KEY (cd_menu, cd_profissional, cd_objeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_profissional_objeto_contrato
    (cd_profissional                NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    st_recebe_email                CHAR(1),
    tx_posicao_box_inicio          VARCHAR2(4000),
    st_objeto_padrao               CHAR(1),
    cd_perfil_profissional         NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_profissional_objeto_contrato
ADD CONSTRAINT pk_oasis2_114 PRIMARY KEY (cd_profissional, cd_objeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_profissional_produto
    (cd_profissional                NUMBER(8,0) NOT NULL,
    cd_produto_parcela             NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(8,0) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_parcela                     NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_profissional_produto
ADD CONSTRAINT pk_oasis2_113 PRIMARY KEY (cd_profissional, cd_produto_parcela, 
  cd_proposta, cd_projeto, cd_parcela)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_profissional_projeto
    (cd_profissional                NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_papel_profissional          NUMBER(8,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_profissional_projeto
ADD CONSTRAINT pk_oasis2_112 PRIMARY KEY (cd_profissional, cd_projeto, 
  cd_papel_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_proposta_definicao_metrica
    (cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(8,0) NOT NULL,
    cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    ni_horas_proposta_metrica      NUMBER(8,1),
    tx_justificativa_metrica       VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_proposta_definicao_metrica
ADD CONSTRAINT pk_oasis2_111 PRIMARY KEY (cd_projeto, cd_proposta, 
  cd_definicao_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_proposta_modulo
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    st_criacao_modulo              CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_proposta_modulo
ADD CONSTRAINT pk_oasis2_110 PRIMARY KEY (cd_projeto, cd_modulo, cd_proposta)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_proposta_sub_item_metrica
    (cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(8,0) NOT NULL,
    cd_item_metrica                NUMBER(8,0) NOT NULL,
    cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    cd_sub_item_metrica            NUMBER(8,0) NOT NULL,
    ni_valor_sub_item_metrica      NUMBER(8,1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_proposta_sub_item_metrica
ADD CONSTRAINT pk_oasis2_109 PRIMARY KEY (cd_projeto, cd_proposta, 
  cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_quest_avaliacao_qualidade
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_grupo_fator                 NUMBER(8,0) NOT NULL,
    cd_item_grupo_fator            NUMBER(8,0) NOT NULL,
    st_avaliacao_qualidade         CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_quest_avaliacao_qualidade
ADD CONSTRAINT pk_oasis2_108 PRIMARY KEY (cd_projeto, cd_proposta, 
  cd_grupo_fator, cd_item_grupo_fator)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_questionario_analise_risco
    (dt_analise_risco               TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_etapa                       NUMBER(8,0) NOT NULL,
    cd_atividade                   NUMBER(8,0) NOT NULL,
    cd_item_risco                  NUMBER(*,0) NOT NULL,
    cd_questao_analise_risco       NUMBER(*,0) NOT NULL,
    st_resposta_analise_risco      CHAR(3),
    cd_profissional                NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_questionario_analise_risco
ADD CONSTRAINT pk_oasis2_107 PRIMARY KEY (dt_analise_risco, cd_projeto, 
  cd_proposta, cd_etapa, cd_atividade, cd_item_risco, cd_questao_analise_risco)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_regra_negocio_requisito
    (cd_projeto_regra_negocio       NUMBER(8,0) NOT NULL,
    dt_regra_negocio               TIMESTAMP (6) NOT NULL,
    cd_regra_negocio               NUMBER(*,0) NOT NULL,
    dt_versao_requisito            TIMESTAMP (6) NOT NULL,
    cd_requisito                   NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    dt_inativacao_regra            DATE,
    st_inativo                     CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_regra_negocio_requisito
ADD CONSTRAINT pk_oasis2_106 PRIMARY KEY (cd_projeto_regra_negocio, 
  dt_regra_negocio, cd_regra_negocio, dt_versao_requisito, cd_requisito, 
  cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_requisito_caso_de_uso
    (cd_projeto                     NUMBER(8,0) NOT NULL,
    dt_versao_requisito            TIMESTAMP (6) NOT NULL,
    cd_requisito                   NUMBER(*,0) NOT NULL,
    dt_versao_caso_de_uso          TIMESTAMP (6) NOT NULL,
    cd_caso_de_uso                 NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(8,0) NOT NULL,
    dt_inativacao_caso_de_uso      DATE,
    st_inativo                     CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_requisito_caso_de_uso
ADD CONSTRAINT pk_oasis2_105 PRIMARY KEY (cd_projeto, dt_versao_requisito, 
  cd_requisito, dt_versao_caso_de_uso, cd_caso_de_uso, cd_modulo)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_requisito_dependente
    (cd_requisito_ascendente        NUMBER(*,0) NOT NULL,
    dt_versao_requisito_ascendente TIMESTAMP (6) NOT NULL,
    cd_projeto_ascendente          NUMBER(8,0) NOT NULL,
    cd_requisito                   NUMBER(*,0) NOT NULL,
    dt_versao_requisito            TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    st_inativo                     CHAR(1),
    dt_inativacao_requisito        DATE,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_requisito_dependente
ADD CONSTRAINT pk_oasis2_104 PRIMARY KEY (cd_requisito_ascendente, 
  dt_versao_requisito_ascendente, cd_projeto_ascendente, cd_requisito, 
  dt_versao_requisito, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_reuniao_profissional
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_reuniao                     NUMBER(*,0) NOT NULL,
    cd_profissional                NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_reuniao_profissional
ADD CONSTRAINT pk_oasis2_103 PRIMARY KEY (cd_projeto, cd_reuniao, 
  cd_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_solicitacao_resposta_pedido
    (cd_solicitacao_pedido          NUMBER(8,0) NOT NULL,
    cd_pergunta_pedido             NUMBER(8,0) NOT NULL,
    cd_resposta_pedido             NUMBER(8,0) NOT NULL,
    tx_descricao_resposta          VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_solicitacao_resposta_pedido
ADD CONSTRAINT pk_oasis_155 PRIMARY KEY (cd_solicitacao_pedido, 
  cd_pergunta_pedido, cd_resposta_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE a_treinamento_profissional
    (cd_treinamento                 NUMBER(8,0) NOT NULL,
    cd_profissional                NUMBER(*,0) NOT NULL,
    dt_treinamento_profissional    DATE,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE a_treinamento_profissional
ADD CONSTRAINT pk_oasis2_102 PRIMARY KEY (cd_treinamento, cd_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_area_atuacao_ti
    (cd_area_atuacao_ti             NUMBER(8,0) NOT NULL,
    tx_area_atuacao_ti             VARCHAR2(200),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_area_atuacao_ti
ADD CONSTRAINT pk_oasis2_101 PRIMARY KEY (cd_area_atuacao_ti)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_area_conhecimento
    (cd_area_conhecimento           NUMBER(*,0) NOT NULL,
    tx_area_conhecimento           VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_area_conhecimento
ADD CONSTRAINT pk_oasis2_100 PRIMARY KEY (cd_area_conhecimento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_atividade
    (cd_atividade                   NUMBER(*,0) NOT NULL,
    cd_etapa                       NUMBER(*,0) NOT NULL,
    tx_atividade                   VARCHAR2(4000),
    ni_ordem_atividade             NUMBER(4,0),
    tx_descricao_atividade         VARCHAR2(4000),
    id                             NUMBER(8,0),
    st_atividade_inativa           CHAR(1))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_atividade
ADD CONSTRAINT pk_oasis2_099 PRIMARY KEY (cd_atividade, cd_etapa)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_box_inicio
    (cd_box_inicio                  NUMBER(*,0) NOT NULL,
    tx_box_inicio                  VARCHAR2(100) NOT NULL,
    st_tipo_box_inicio             CHAR(1) NOT NULL,
    tx_titulo_box_inicio           VARCHAR2(100) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_box_inicio
ADD CONSTRAINT pk_oasis2_098 PRIMARY KEY (cd_box_inicio)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_condicao_sub_item_metrica
    (cd_condicao_sub_item_metrica   NUMBER(8,0) NOT NULL,
    cd_item_metrica                NUMBER(8,0) NOT NULL,
    cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    cd_sub_item_metrica            NUMBER(8,0) NOT NULL,
    tx_condicao_sub_item_metrica   VARCHAR2(100),
    ni_valor_condicao_satisfeita   NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_condicao_sub_item_metrica
ADD CONSTRAINT pk_oasis2_097 PRIMARY KEY (cd_condicao_sub_item_metrica, 
  cd_item_metrica, cd_definicao_metrica, cd_sub_item_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_conhecimento
    (cd_conhecimento                NUMBER(*,0) NOT NULL,
    cd_tipo_conhecimento           NUMBER(*,0) NOT NULL,
    tx_conhecimento                VARCHAR2(4000),
    st_padrao                      CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_conhecimento
ADD CONSTRAINT pk_oasis2_096 PRIMARY KEY (cd_conhecimento, cd_tipo_conhecimento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_conjunto_medida
    (cd_conjunto_medida             NUMBER(8,0) NOT NULL,
    tx_conjunto_medida             VARCHAR2(500),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_conjunto_medida
ADD CONSTRAINT pk_oasis2_095 PRIMARY KEY (cd_conjunto_medida)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_definicao_metrica
    (cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    tx_nome_metrica                VARCHAR2(4000),
    tx_sigla_metrica               VARCHAR2(4000),
    tx_descricao_metrica           VARCHAR2(4000),
    tx_formula_metrica             VARCHAR2(4000),
    st_justificativa_metrica       CHAR(1),
    id                             NUMBER(8,0),
    tx_sigla_unidade_metrica       VARCHAR2(10),
    tx_unidade_metrica             VARCHAR2(100))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_definicao_metrica
ADD CONSTRAINT pk_oasis2_094 PRIMARY KEY (cd_definicao_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_etapa
    (cd_etapa                       NUMBER(*,0) NOT NULL,
    tx_etapa                       VARCHAR2(4000),
    ni_ordem_etapa                 NUMBER(4,0),
    tx_descricao_etapa             VARCHAR2(4000),
    id                             NUMBER(8,0),
    cd_area_atuacao_ti             NUMBER(8,0),
    st_etapa_inativa               CHAR(1))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_etapa
ADD CONSTRAINT pk_oasis2_093 PRIMARY KEY (cd_etapa)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_evento
    (cd_evento                      NUMBER(8,0) NOT NULL,
    tx_evento                      VARCHAR2(200),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_evento
ADD CONSTRAINT pk_oasis2_092 PRIMARY KEY (cd_evento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_funcionalidade
    (cd_funcionalidade              NUMBER(8,0) NOT NULL,
    tx_codigo_funcionalidade       VARCHAR2(20),
    tx_funcionalidade              VARCHAR2(200),
    st_funcionalidade              CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_funcionalidade
ADD CONSTRAINT pk_oasis2_091 PRIMARY KEY (cd_funcionalidade)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_grupo_fator
    (cd_grupo_fator                 NUMBER(8,0) NOT NULL,
    tx_grupo_fator                 VARCHAR2(100),
    ni_peso_grupo_fator            NUMBER(8,0) NOT NULL,
    ni_ordem_grupo_fator           NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0),
    ni_indice_grupo_fator          NUMBER(8,0) NOT NULL)
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_grupo_fator
ADD CONSTRAINT pk_oasis2_090 PRIMARY KEY (cd_grupo_fator)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_item_controle_baseline
    (cd_item_controle_baseline      NUMBER(8,0) NOT NULL,
    tx_item_controle_baseline      VARCHAR2(500),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_item_controle_baseline
ADD CONSTRAINT pk_oasis2_089 PRIMARY KEY (cd_item_controle_baseline)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_item_grupo_fator
    (cd_item_grupo_fator            NUMBER(8,0) NOT NULL,
    cd_grupo_fator                 NUMBER(8,0) NOT NULL,
    tx_item_grupo_fator            VARCHAR2(300),
    ni_ordem_item_grupo_fator      NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_item_grupo_fator
ADD CONSTRAINT pk_oasis2_088 PRIMARY KEY (cd_item_grupo_fator, cd_grupo_fator)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_item_inventario
    (cd_item_inventario             NUMBER(8,0) NOT NULL,
    cd_tipo_inventario             NUMBER(8,0) NOT NULL,
    tx_item_inventario             VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_item_inventario
ADD CONSTRAINT pk_oasis2_087 PRIMARY KEY (cd_item_inventario, cd_tipo_inventario)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_item_metrica
    (cd_item_metrica                NUMBER(8,0) NOT NULL,
    cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    tx_item_metrica                VARCHAR2(4000),
    tx_variavel_item_metrica       VARCHAR2(4000),
    ni_ordem_item_metrica          NUMBER(*,0),
    tx_formula_item_metrica        VARCHAR2(500),
    st_interno_item_metrica        CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_item_metrica
ADD CONSTRAINT pk_oasis2_086 PRIMARY KEY (cd_item_metrica, cd_definicao_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_item_parecer_tecnico
    (cd_item_parecer_tecnico        NUMBER(*,0) NOT NULL,
    tx_item_parecer_tecnico        VARCHAR2(4000),
    st_proposta                    CHAR(1),
    st_parcela                     CHAR(1),
    st_viagem                      CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_item_parecer_tecnico
ADD CONSTRAINT pk_oasis2_085 PRIMARY KEY (cd_item_parecer_tecnico)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_item_risco
    (cd_item_risco                  NUMBER(*,0) NOT NULL,
    cd_etapa                       NUMBER(8,0) NOT NULL,
    cd_atividade                   NUMBER(8,0) NOT NULL,
    tx_item_risco                  VARCHAR2(4000),
    tx_descricao_item_risco        VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_item_risco
ADD CONSTRAINT pk_oasis2_084 PRIMARY KEY (cd_item_risco, cd_etapa, cd_atividade)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_item_teste
    (cd_item_teste                  NUMBER(8,0) NOT NULL,
    tx_item_teste                  VARCHAR2(1000),
    st_item_teste                  CHAR(1),
    st_obrigatorio                 CHAR(1),
    st_tipo_item_teste             CHAR(1),
    ni_ordem_item_teste            NUMBER(*,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_item_teste
ADD CONSTRAINT pk_oasis2_083 PRIMARY KEY (cd_item_teste)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_medida
    (cd_medida                      NUMBER(*,0) NOT NULL,
    tx_medida                      VARCHAR2(4000),
    tx_objetivo_medida             VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_medida
ADD CONSTRAINT pk_oasis2_082 PRIMARY KEY (cd_medida)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_menu
    (cd_menu                        NUMBER(*,0) NOT NULL,
    cd_menu_pai                    NUMBER(*,0),
    tx_menu                        VARCHAR2(4000),
    ni_nivel_menu                  NUMBER(*,0),
    tx_pagina                      VARCHAR2(4000),
    st_menu                        CHAR(1),
    tx_modulo                      VARCHAR2(50),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_menu
ADD CONSTRAINT pk_oasis2_081 PRIMARY KEY (cd_menu)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_msg_email
    (cd_msg_email                   NUMBER(8,0) NOT NULL,
    cd_menu                        NUMBER(8,0) NOT NULL,
    tx_metodo_msg_email            VARCHAR2(300),
    tx_msg_email                   VARCHAR2(1000),
    st_msg_email                   CHAR(1),
    tx_assunto_msg_email           VARCHAR2(200),
    tx_metodo_msg_email_bkp        VARCHAR2(4000))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_msg_email
ADD CONSTRAINT pk_oasis2_080 PRIMARY KEY (cd_msg_email, cd_menu)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_nivel_servico
    (cd_nivel_servico               NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    tx_nivel_servico               VARCHAR2(4000),
    st_nivel_servico               CHAR(1),
    ni_horas_prazo_execucao        NUMBER(*,1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_nivel_servico
ADD CONSTRAINT pk_oasis2_079 PRIMARY KEY (cd_nivel_servico)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_papel_profissional
    (cd_papel_profissional          NUMBER(8,0) NOT NULL,
    tx_papel_profissional          VARCHAR2(200),
    id                             NUMBER(8,0),
    cd_area_atuacao_ti             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_papel_profissional
ADD CONSTRAINT pk_oasis2_078 PRIMARY KEY (cd_papel_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_penalidade
    (cd_penalidade                  NUMBER(*,0) NOT NULL,
    cd_contrato                    NUMBER(*,0) NOT NULL,
    tx_penalidade                  VARCHAR2(4000),
    tx_abreviacao_penalidade       VARCHAR2(4000),
    ni_valor_penalidade            NUMBER(8,2),
    ni_penalidade                  NUMBER(*,0),
    st_ocorrencia                  CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_penalidade
ADD CONSTRAINT pk_oasis2_077 PRIMARY KEY (cd_penalidade, cd_contrato)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_perfil
    (cd_perfil                      NUMBER(*,0) NOT NULL,
    tx_perfil                      VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_perfil
ADD CONSTRAINT pk_oasis2_076 PRIMARY KEY (cd_perfil)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_perfil_profissional
    (cd_perfil_profissional         NUMBER(8,0) NOT NULL,
    tx_perfil_profissional         VARCHAR2(200),
    id                             NUMBER(8,0),
    cd_area_atuacao_ti             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_perfil_profissional
ADD CONSTRAINT pk_oasis2_075 PRIMARY KEY (cd_perfil_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_pergunta_pedido
    (cd_pergunta_pedido             NUMBER(8,0) NOT NULL,
    tx_titulo_pergunta             VARCHAR2(200) NOT NULL,
    st_multipla_resposta           CHAR(1) DEFAULT 'N'  NOT NULL,
    st_obriga_resposta             CHAR(1) DEFAULT 'N'  NOT NULL,
    tx_ajuda_pergunta              VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_pergunta_pedido
ADD CONSTRAINT pk_oasis_151 PRIMARY KEY (cd_pergunta_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_questao_analise_risco
    (cd_questao_analise_risco       NUMBER(*,0) NOT NULL,
    cd_atividade                   NUMBER(8,0) NOT NULL,
    cd_etapa                       NUMBER(8,0) NOT NULL,
    cd_item_risco                  NUMBER(*,0) NOT NULL,
    tx_questao_analise_risco       VARCHAR2(4000),
    tx_obj_questao_analise_risco   VARCHAR2(4000),
    ni_peso_questao_analise_risco  NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_questao_analise_risco
ADD CONSTRAINT pk_oasis2_074 PRIMARY KEY (cd_questao_analise_risco, cd_atividade, 
  cd_etapa, cd_item_risco)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_relacao_contratual
    (cd_relacao_contratual          NUMBER(*,0) NOT NULL,
    tx_relacao_contratual          VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_relacao_contratual
ADD CONSTRAINT pk_oasis2_073 PRIMARY KEY (cd_relacao_contratual)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_resposta_pedido
    (cd_resposta_pedido             NUMBER(8,0) NOT NULL,
    tx_titulo_resposta             VARCHAR2(150) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_resposta_pedido
ADD CONSTRAINT pk_oasis_152 PRIMARY KEY (cd_resposta_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_status
    (cd_status                      NUMBER(*,0) NOT NULL,
    tx_status                      VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_status
ADD CONSTRAINT pk_oasis2_072 PRIMARY KEY (cd_status)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_sub_item_metrica
    (cd_sub_item_metrica            NUMBER(8,0) NOT NULL,
    cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    cd_item_metrica                NUMBER(8,0) NOT NULL,
    tx_sub_item_metrica            VARCHAR2(4000),
    tx_variavel_sub_item_metrica   VARCHAR2(4000),
    st_interno                     CHAR(1),
    ni_ordem_sub_item_metrica      NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_sub_item_metrica
ADD CONSTRAINT pk_oasis2_071 PRIMARY KEY (cd_sub_item_metrica, 
  cd_definicao_metrica, cd_item_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_tipo_conhecimento
    (cd_tipo_conhecimento           NUMBER(*,0) NOT NULL,
    tx_tipo_conhecimento           VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_tipo_conhecimento
ADD CONSTRAINT pk_oasis2_070 PRIMARY KEY (cd_tipo_conhecimento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_tipo_dado_tecnico
    (cd_tipo_dado_tecnico           NUMBER(*,0) NOT NULL,
    tx_tipo_dado_tecnico           VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_tipo_dado_tecnico
ADD CONSTRAINT pk_oasis2_069 PRIMARY KEY (cd_tipo_dado_tecnico)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_tipo_documentacao
    (cd_tipo_documentacao           NUMBER(*,0) NOT NULL,
    tx_tipo_documentacao           VARCHAR2(4000),
    tx_extensao_documentacao       VARCHAR2(4000),
    st_classificacao               CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_tipo_documentacao
ADD CONSTRAINT pk_oasis2_068 PRIMARY KEY (cd_tipo_documentacao)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN b_tipo_documentacao.st_classificacao IS 'C = Controle
D = Disponibilidade de Serviço
P = Projeto
R = Profissional
T = Itens de Teste

'
/

CREATE TABLE b_tipo_inventario
    (cd_tipo_inventario             NUMBER(8,0) NOT NULL,
    tx_tipo_inventario             VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_tipo_inventario
ADD CONSTRAINT pk_oasis2_067 PRIMARY KEY (cd_tipo_inventario)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_tipo_produto
    (cd_tipo_produto                NUMBER(*,0) NOT NULL,
    tx_tipo_produto                VARCHAR2(4000),
    ni_ordem_tipo_produto          NUMBER(4,0),
    cd_definicao_metrica           NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_tipo_produto
ADD CONSTRAINT pk_oasis2_066 PRIMARY KEY (cd_tipo_produto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_treinamento
    (cd_treinamento                 NUMBER(8,0) NOT NULL,
    tx_treinamento                 VARCHAR2(500),
    tx_obs_treinamento             VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_treinamento
ADD CONSTRAINT pk_oasis2_065 PRIMARY KEY (cd_treinamento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE b_unidade
    (cd_unidade                     NUMBER(*,0) NOT NULL,
    tx_sigla_unidade               VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE b_unidade
ADD CONSTRAINT pk_oasis2_064 PRIMARY KEY (cd_unidade)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_acompanhamento_proposta
    (cd_acompanhamento_proposta     NUMBER(8,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    tx_acompanhamento_proposta     VARCHAR2(4000),
    st_restrito                    CHAR(1),
    dt_acompanhamento_proposta     TIMESTAMP (6),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_acompanhamento_proposta
ADD CONSTRAINT pk_oasis2_063 PRIMARY KEY (cd_acompanhamento_proposta, cd_projeto, 
  cd_proposta)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_agenda_plano_implantacao
    (dt_agenda_plano_implantacao    TIMESTAMP (6) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    tx_agenda_plano_implantacao    VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_agenda_plano_implantacao
ADD CONSTRAINT pk_oasis2_062 PRIMARY KEY (dt_agenda_plano_implantacao, 
  cd_proposta, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_analise_execucao_projeto
    (dt_analise_execucao_projeto    TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    tx_resultado_analise_execucao  VARCHAR2(4000),
    tx_decisao_analise_execucao    VARCHAR2(4000),
    dt_decisao_analise_execucao    DATE,
    st_fecha_analise_execucao_proj CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_analise_execucao_projeto
ADD CONSTRAINT pk_oasis2_061 PRIMARY KEY (dt_analise_execucao_projeto, 
  cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_analise_matriz_rastreab
    (cd_analise_matriz_rastreab     NUMBER(8,0) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    st_matriz_rastreabilidade      CHAR(2) NOT NULL,
    dt_analise_matriz_rastreab     DATE NOT NULL,
    tx_analise_matriz_rastreab     VARCHAR2(4000),
    st_fechamento                  CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_analise_matriz_rastreab
ADD CONSTRAINT pk_oasis2_060 PRIMARY KEY (cd_analise_matriz_rastreab, cd_projeto, 
  st_matriz_rastreabilidade)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN s_analise_matriz_rastreab.st_matriz_rastreabilidade IS 'RR-Requisito X Requisito;RN-Requisito X Regra de Negócio;RC-Requisito X Caso de Uso'
/

CREATE TABLE s_analise_medicao
    (dt_analise_medicao             TIMESTAMP (6) NOT NULL,
    cd_medicao                     NUMBER(*,0) NOT NULL,
    cd_box_inicio                  NUMBER(*,0) NOT NULL,
    cd_profissional                NUMBER(*,0) NOT NULL,
    tx_resultado_analise_medicao   VARCHAR2(4000),
    tx_dados_medicao               VARCHAR2(4000),
    tx_decisao                     VARCHAR2(4000),
    dt_decisao                     DATE,
    st_decisao_executada           CHAR(1),
    dt_decisao_executada           DATE,
    tx_obs_decisao_executada       VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_analise_medicao
ADD CONSTRAINT pk_oasis2_059 PRIMARY KEY (dt_analise_medicao, cd_medicao, 
  cd_box_inicio)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN s_analise_medicao.st_decisao_executada IS 'valores possíveis:
E=Executada;
N=Não Executada;'
/

CREATE TABLE s_analise_risco
    (dt_analise_risco               TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_etapa                       NUMBER(8,0) NOT NULL,
    cd_atividade                   NUMBER(8,0) NOT NULL,
    cd_item_risco                  NUMBER(*,0) NOT NULL,
    st_fator_risco                 CHAR(3),
    st_impacto_projeto_risco       CHAR(3),
    st_impacto_tecnico_risco       CHAR(3),
    st_impacto_custo_risco         CHAR(3),
    st_impacto_cronograma_risco    CHAR(3),
    tx_analise_risco               VARCHAR2(4000),
    tx_acao_analise_risco          VARCHAR2(4000),
    st_fechamento_risco            CHAR(1),
    cd_profissional                NUMBER(8,0),
    cd_profissional_responsavel    NUMBER(8,0),
    dt_limite_acao                 DATE,
    st_acao                        CHAR(1),
    tx_observacao_acao             VARCHAR2(4000),
    dt_fechamento_risco            DATE,
    tx_cor_impacto_cronog_risco    CHAR(20),
    tx_cor_impacto_custo_risco     CHAR(20),
    tx_cor_impacto_projeto_risco   CHAR(20),
    tx_cor_impacto_tecnico_risco   CHAR(20),
    id                             NUMBER(8,0),
    st_nao_aplica_risco            CHAR(1),
    tx_mitigacao_risco             VARCHAR2(4000))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_analise_risco
ADD CONSTRAINT pk_oasis2_058 PRIMARY KEY (dt_analise_risco, cd_projeto, 
  cd_proposta, cd_etapa, cd_atividade, cd_item_risco)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_arquivo_pedido
    (cd_arquivo_pedido              NUMBER(8,0) NOT NULL,
    cd_pergunta_pedido             NUMBER(8,0) NOT NULL,
    cd_resposta_pedido             NUMBER(8,0) NOT NULL,
    cd_solicitacao_pedido          NUMBER(8,0) NOT NULL,
    tx_titulo_arquivo              VARCHAR2(100) NOT NULL,
    tx_nome_arquivo                VARCHAR2(20) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_arquivo_pedido
ADD CONSTRAINT pk_oasis_156 PRIMARY KEY (cd_arquivo_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_ator
    (cd_ator                        NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    tx_ator                        VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_ator
ADD CONSTRAINT pk_oasis2_057 PRIMARY KEY (cd_ator)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_base_conhecimento
    (cd_base_conhecimento           NUMBER(*,0) NOT NULL,
    cd_area_conhecimento           NUMBER(*,0) NOT NULL,
    tx_assunto                     VARCHAR2(4000),
    tx_problema                    VARCHAR2(4000),
    tx_solucao                     VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_base_conhecimento
ADD CONSTRAINT pk_oasis2_056 PRIMARY KEY (cd_base_conhecimento, 
  cd_area_conhecimento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_baseline
    (dt_baseline                    TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    st_ativa                       CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_baseline
ADD CONSTRAINT pk_oasis2_055 PRIMARY KEY (dt_baseline, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_caso_de_uso
    (cd_caso_de_uso                 NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    ni_ordem_caso_de_uso           NUMBER(*,0),
    tx_caso_de_uso                 VARCHAR2(4000),
    tx_descricao_caso_de_uso       VARCHAR2(4000),
    dt_fechamento_caso_de_uso      TIMESTAMP (6),
    dt_versao_caso_de_uso          TIMESTAMP (6) NOT NULL,
    ni_versao_caso_de_uso          NUMBER(8,0),
    st_fechamento_caso_de_uso      CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_caso_de_uso
ADD CONSTRAINT pk_oasis2_054 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_coluna
    (tx_tabela                      VARCHAR2(100) NOT NULL,
    tx_coluna                      VARCHAR2(100) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    tx_descricao                   VARCHAR2(4000),
    st_chave                       CHAR(1),
    tx_tabela_referencia           VARCHAR2(4000),
    cd_projeto_referencia          NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_coluna
ADD CONSTRAINT pk_oasis2_053 PRIMARY KEY (tx_tabela, tx_coluna, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_complemento
    (cd_complemento                 NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_caso_de_uso                 NUMBER(*,0) NOT NULL,
    tx_complemento                 VARCHAR2(4000),
    st_complemento                 CHAR(1),
    ni_ordem_complemento           NUMBER(*,0),
    dt_versao_caso_de_uso          TIMESTAMP (6) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_complemento
ADD CONSTRAINT pk_oasis2_052 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso, cd_complemento)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_condicao
    (cd_condicao                    NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_caso_de_uso                 NUMBER(*,0) NOT NULL,
    tx_condicao                    VARCHAR2(4000),
    st_condicao                    CHAR(1),
    dt_versao_caso_de_uso          TIMESTAMP (6) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_condicao
ADD CONSTRAINT pk_oasis2_051 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso, cd_condicao)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_config_banco_de_dados
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    tx_adapter                     VARCHAR2(100),
    tx_host                        VARCHAR2(100),
    tx_dbname                      VARCHAR2(100),
    tx_username                    VARCHAR2(100),
    tx_password                    VARCHAR2(100),
    tx_schema                      VARCHAR2(100),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_config_banco_de_dados
ADD CONSTRAINT pk_oasis2_050 PRIMARY KEY (cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_contato_empresa
    (cd_contato_empresa             NUMBER(8,0) NOT NULL,
    cd_empresa                     NUMBER(*,0) NOT NULL,
    tx_contato_empresa             VARCHAR2(4000),
    tx_telefone_contato            VARCHAR2(4000),
    tx_email_contato               VARCHAR2(4000),
    tx_celular_contato             VARCHAR2(4000),
    st_gerente_conta               CHAR(1),
    tx_obs_contato                 VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_contato_empresa
ADD CONSTRAINT pk_oasis2_049 PRIMARY KEY (cd_contato_empresa, cd_empresa)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_contrato
    (cd_contrato                    NUMBER(*,0) NOT NULL,
    cd_empresa                     NUMBER(*,0) NOT NULL,
    tx_numero_contrato             VARCHAR2(4000),
    dt_inicio_contrato             DATE,
    dt_fim_contrato                DATE,
    st_aditivo                     CHAR(1),
    tx_cpf_gestor                  VARCHAR2(4000),
    ni_horas_previstas             NUMBER(*,0),
    tx_objeto                      VARCHAR2(4000),
    tx_gestor_contrato             VARCHAR2(4000),
    tx_fone_gestor_contrato        VARCHAR2(4000),
    tx_numero_processo             VARCHAR2(20),
    tx_obs_contrato                VARCHAR2(4000),
    tx_localizacao_arquivo         VARCHAR2(4000),
    tx_co_gestor                   VARCHAR2(4000),
    tx_cpf_co_gestor               VARCHAR2(4000),
    tx_fone_co_gestor_contrato     VARCHAR2(4000),
    nf_valor_passagens_diarias     NUMBER(15,2),
    nf_valor_unitario_diaria       NUMBER(15,2),
    st_contrato                    CHAR(1),
    ni_mes_inicial_contrato        NUMBER(4,0),
    ni_ano_inicial_contrato        NUMBER(4,0),
    ni_mes_final_contrato          NUMBER(4,0),
    ni_ano_final_contrato          NUMBER(4,0),
    ni_qtd_meses_contrato          NUMBER(4,0),
    nf_valor_unitario_hora         NUMBER(15,2),
    nf_valor_contrato              NUMBER(15,2),
    cd_contato_empresa             NUMBER(8,0),
    id                             NUMBER(8,0),
    cd_definicao_metrica           NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_contrato
ADD CONSTRAINT pk_oasis2_048 PRIMARY KEY (cd_contrato)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_custo_contrato_demanda
    (cd_contrato                    NUMBER(*,0) NOT NULL,
    ni_mes_custo_contrato_demanda  NUMBER(*,0) NOT NULL,
    ni_ano_custo_contrato_demanda  NUMBER(*,0) NOT NULL,
    nf_total_multa                 NUMBER(8,2),
    nf_total_glosa                 NUMBER(8,2),
    nf_total_pago                  NUMBER(8,2),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_custo_contrato_demanda
ADD CONSTRAINT pk_oasis2_047 PRIMARY KEY (cd_contrato, 
  ni_mes_custo_contrato_demanda, ni_ano_custo_contrato_demanda)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_demanda
    (cd_demanda                     NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0),
    ni_ano_solicitacao             NUMBER(*,0),
    ni_solicitacao                 NUMBER(*,0),
    dt_demanda                     TIMESTAMP (6),
    tx_demanda                     VARCHAR2(4000),
    st_conclusao_demanda           CHAR(1),
    dt_conclusao_demanda           TIMESTAMP (6),
    tx_solicitante_demanda         VARCHAR2(200),
    cd_unidade                     NUMBER(8,0),
    st_fechamento_demanda          CHAR(1),
    dt_fechamento_demanda          TIMESTAMP (6),
    st_prioridade_demanda          CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_demanda
ADD CONSTRAINT pk_oasis2_046 PRIMARY KEY (cd_demanda)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_disponibilidade_servico
    (cd_disponibilidade_servico     NUMBER(8,0) NOT NULL,
    cd_objeto                      NUMBER(8,0) NOT NULL,
    dt_inicio_analise_disp_servico DATE,
    dt_fim_analise_disp_servico    DATE,
    tx_analise_disp_servico        VARCHAR2(4000),
    ni_indice_disp_servico         NUMBER(8,2),
    tx_parecer_disp_servico        VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_disponibilidade_servico
ADD CONSTRAINT pk_oasis2_045 PRIMARY KEY (cd_disponibilidade_servico, cd_objeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_empresa
    (cd_empresa                     NUMBER(*,0) NOT NULL,
    tx_empresa                     VARCHAR2(4000),
    tx_cnpj_empresa                VARCHAR2(4000),
    tx_endereco_empresa            VARCHAR2(4000),
    tx_telefone_empresa            VARCHAR2(20),
    tx_email_empresa               VARCHAR2(200),
    tx_fax_empresa                 VARCHAR2(30),
    tx_arquivo_logomarca           VARCHAR2(100),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_empresa
ADD CONSTRAINT pk_oasis2_044 PRIMARY KEY (cd_empresa)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_extrato_mensal
    (ni_mes_extrato                 NUMBER(*,0) NOT NULL,
    ni_ano_extrato                 NUMBER(*,0) NOT NULL,
    cd_contrato                    NUMBER(*,0) NOT NULL,
    dt_fechamento_extrato          DATE,
    ni_horas_extrato               NUMBER(*,0),
    ni_qtd_parcela                 NUMBER(*,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_extrato_mensal
ADD CONSTRAINT pk_oasis2_043 PRIMARY KEY (ni_mes_extrato, ni_ano_extrato, 
  cd_contrato)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_extrato_mensal_parcela
    (cd_contrato                    NUMBER(*,0) NOT NULL,
    ni_ano_extrato                 NUMBER(*,0) NOT NULL,
    ni_mes_extrato                 NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_parcela                     NUMBER(*,0) NOT NULL,
    ni_hora_parcela_extrato        NUMBER(*,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_extrato_mensal_parcela
ADD CONSTRAINT pk_oasis2_042 PRIMARY KEY (cd_contrato, ni_ano_extrato, 
  ni_mes_extrato, cd_proposta, cd_projeto, cd_parcela)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_fale_conosco
    (cd_fale_conosco                NUMBER(*,0) NOT NULL,
    tx_nome                        VARCHAR2(4000),
    tx_email                       VARCHAR2(4000),
    tx_assunto                     VARCHAR2(4000),
    tx_mensagem                    VARCHAR2(4000),
    tx_resposta                    VARCHAR2(4000),
    st_respondida                  CHAR(1),
    dt_registro                    TIMESTAMP (6),
    st_pendente                    CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_fale_conosco
ADD CONSTRAINT pk_oasis2_041 PRIMARY KEY (cd_fale_conosco)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_gerencia_qualidade
    (cd_gerencia_qualidade          NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_profissional                NUMBER(*,0) NOT NULL,
    dt_auditoria_qualidade         DATE,
    tx_fase_projeto                VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_gerencia_qualidade
ADD CONSTRAINT pk_oasis2_040 PRIMARY KEY (cd_gerencia_qualidade, cd_projeto, 
  cd_proposta)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_hist_prop_sub_item_metrica
    (dt_historico_proposta          TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(8,0) NOT NULL,
    cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    cd_item_metrica                NUMBER(8,0) NOT NULL,
    cd_sub_item_metrica            NUMBER(8,0) NOT NULL,
    ni_valor_sub_item_metrica      NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_hist_prop_sub_item_metrica
ADD CONSTRAINT pk_oasis2_039 PRIMARY KEY (dt_historico_proposta, cd_projeto, 
  cd_proposta, cd_definicao_metrica, cd_item_metrica, cd_sub_item_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_historico
    (cd_historico                   NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    cd_etapa                       NUMBER(*,0) NOT NULL,
    cd_atividade                   NUMBER(*,0) NOT NULL,
    dt_inicio_historico            DATE,
    dt_fim_historico               DATE,
    tx_historico                   VARCHAR2(4000),
    cd_profissional                NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_historico
ADD CONSTRAINT pk_oasis2_038 PRIMARY KEY (cd_historico, cd_projeto, cd_proposta, 
  cd_modulo, cd_etapa, cd_atividade)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_historico_execucao_demanda
    (cd_historico_execucao_demanda  NUMBER(*,0) NOT NULL,
    cd_profissional                NUMBER(*,0) NOT NULL,
    cd_demanda                     NUMBER(*,0) NOT NULL,
    cd_nivel_servico               NUMBER(*,0) NOT NULL,
    dt_inicio                      TIMESTAMP (6),
    dt_fim                         TIMESTAMP (6),
    tx_historico                   VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_historico_execucao_demanda
ADD CONSTRAINT pk_oasis2_037 PRIMARY KEY (cd_historico_execucao_demanda, 
  cd_profissional, cd_demanda)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_historico_pedido
    (cd_historico_pedido            NUMBER(8,0) NOT NULL,
    cd_solicitacao_historico       NUMBER(8,0) NOT NULL,
    dt_registro_historico          TIMESTAMP (6) NOT NULL,
    st_acao_historico              CHAR(1) DEFAULT 'P'  NOT NULL,
    tx_descricao_historico         VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_historico_pedido
ADD CONSTRAINT pk_oasis_154 PRIMARY KEY (cd_historico_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_historico_projeto_continuado
    (cd_historico_proj_continuado   NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    cd_projeto_continuado          NUMBER(*,0) NOT NULL,
    cd_modulo_continuado           NUMBER(*,0) NOT NULL,
    cd_etapa                       NUMBER(*,0) NOT NULL,
    cd_atividade                   NUMBER(*,0) NOT NULL,
    dt_inicio_hist_proj_continuado DATE,
    dt_fim_hist_projeto_continuado DATE,
    tx_hist_projeto_continuado     VARCHAR2(4000),
    cd_profissional                NUMBER(*,0) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_historico_projeto_continuado
ADD CONSTRAINT pk_oasis2_036 PRIMARY KEY (cd_historico_proj_continuado, 
  cd_objeto, cd_projeto_continuado, cd_modulo_continuado, cd_etapa, cd_atividade)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_historico_proposta
    (dt_historico_proposta          TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    tx_sigla_projeto               VARCHAR2(4000),
    tx_projeto                     VARCHAR2(4000),
    tx_contexdo_geral              VARCHAR2(4000),
    tx_escopo_projeto              VARCHAR2(4000),
    tx_sigla_unidade               VARCHAR2(4000),
    tx_gestor_projeto              VARCHAR2(4000),
    tx_impacto_projeto             VARCHAR2(4000),
    tx_gerente_projeto             VARCHAR2(4000),
    st_metrica_historico           CHAR(3),
    tx_inicio_previsto             VARCHAR2(4000),
    tx_termino_previsto            VARCHAR2(4000),
    ni_horas_proposta              NUMBER(8,1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_historico_proposta
ADD CONSTRAINT pk_oasis2_035 PRIMARY KEY (dt_historico_proposta, cd_projeto, 
  cd_proposta)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_historico_proposta_metrica
    (dt_historico_proposta          TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(8,0) NOT NULL,
    cd_definicao_metrica           NUMBER(8,0) NOT NULL,
    ni_um_prop_metrica_historico   NUMBER(8,1),
    tx_just_metrica_historico      VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_historico_proposta_metrica
ADD CONSTRAINT pk_oasis2_034 PRIMARY KEY (dt_historico_proposta, cd_projeto, 
  cd_proposta, cd_definicao_metrica)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_historico_proposta_parcela
    (cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    dt_historico_proposta          TIMESTAMP (6) NOT NULL,
    cd_historico_proposta_parcela  NUMBER(*,0) NOT NULL,
    ni_parcela                     NUMBER(*,0) NOT NULL,
    ni_mes_previsao_parcela        NUMBER(*,0),
    ni_ano_previsao_parcela        NUMBER(*,0),
    ni_horas_parcela               NUMBER(8,1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_historico_proposta_parcela
ADD CONSTRAINT pk_oasis2_033 PRIMARY KEY (cd_proposta, cd_projeto, 
  dt_historico_proposta, cd_historico_proposta_parcela)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_historico_proposta_produto
    (cd_historico_proposta_produto  NUMBER(*,0) NOT NULL,
    dt_historico_proposta          TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_historico_proposta_parcela  NUMBER(*,0) NOT NULL,
    tx_produto                     VARCHAR2(4000),
    cd_tipo_produto                NUMBER(*,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_historico_proposta_produto
ADD CONSTRAINT pk_oasis2_032 PRIMARY KEY (cd_historico_proposta_produto, 
  dt_historico_proposta, cd_projeto, cd_proposta, cd_historico_proposta_parcela)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_hw_inventario
    (cd_projeto_continuado          NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    cd_modulo_continuado           NUMBER(*,0) NOT NULL,
    tx_servidor                    VARCHAR2(100) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_hw_inventario
ADD CONSTRAINT pk_oasis2_031 PRIMARY KEY (cd_projeto_continuado, cd_objeto, 
  cd_modulo_continuado)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_interacao
    (cd_interacao                   NUMBER(*,0) NOT NULL,
    cd_modulo                      NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_caso_de_uso                 NUMBER(*,0) NOT NULL,
    cd_ator                        NUMBER(*,0) NOT NULL,
    tx_interacao                   VARCHAR2(4000),
    ni_ordem_interacao             NUMBER(*,0),
    st_interacao                   CHAR(1),
    dt_versao_caso_de_uso          TIMESTAMP (6) NOT NULL,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_interacao
ADD CONSTRAINT pk_oasis2_030 PRIMARY KEY (cd_caso_de_uso, cd_projeto, cd_modulo, 
  dt_versao_caso_de_uso, cd_interacao)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_log
    (dt_ocorrencia                  DATE NOT NULL,
    cd_log                         NUMBER(8,0) NOT NULL,
    cd_profissional                NUMBER(8,0),
    tx_msg_log                     VARCHAR2(100) NOT NULL,
    ni_prioridade                  NUMBER(8,0),
    tx_tabela                      VARCHAR2(4000),
    tx_controller                  VARCHAR2(4000),
    tx_ip                          VARCHAR2(15),
    tx_host                        VARCHAR2(4000))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_log
ADD CONSTRAINT pk_oasis2_029 PRIMARY KEY (dt_ocorrencia, cd_log)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_medicao
    (cd_medicao                     NUMBER(*,0) NOT NULL,
    tx_medicao                     VARCHAR2(200),
    tx_objetivo_medicao            VARCHAR2(4000),
    st_nivel_medicao               CHAR(1),
    tx_procedimento_coleta         VARCHAR2(4000),
    tx_procedimento_analise        VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_medicao
ADD CONSTRAINT pk_oasis2_028 PRIMARY KEY (cd_medicao)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN s_medicao.st_nivel_medicao IS '''Valores possíveis:
E=Estratégico; e
T=Técnico'''
/

CREATE TABLE s_mensageria
    (cd_mensageria                  NUMBER(8,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    cd_perfil                      NUMBER(*,0),
    tx_mensagem                    VARCHAR2(4000),
    dt_postagem                    TIMESTAMP (6),
    dt_encerramento                TIMESTAMP (6),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_mensageria
ADD CONSTRAINT pk_oasis2_027 PRIMARY KEY (cd_mensageria)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_modulo
    (cd_modulo                      NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_status                      NUMBER(*,0),
    tx_modulo                      VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_modulo
ADD CONSTRAINT pk_oasis2_026 PRIMARY KEY (cd_modulo, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_modulo_continuado
    (cd_modulo_continuado           NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    cd_projeto_continuado          NUMBER(*,0) NOT NULL,
    tx_modulo_continuado           VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_modulo_continuado
ADD CONSTRAINT pk_oasis2_025 PRIMARY KEY (cd_modulo_continuado, cd_objeto, 
  cd_projeto_continuado)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_objeto_contrato
    (cd_objeto                      NUMBER(*,0) NOT NULL,
    cd_contrato                    NUMBER(*,0) NOT NULL,
    tx_objeto                      VARCHAR2(4000),
    ni_horas_objeto                NUMBER(*,0),
    st_objeto_contrato             CHAR(1),
    st_viagem                      CHAR(1),
    id                             NUMBER(8,0),
    st_parcela_orcamento           CHAR(1),
    ni_porcentagem_parc_orcamento  NUMBER(3,0),
    st_necessita_justificativa     CHAR(1),
    ni_minutos_justificativa       NUMBER(8,0),
    tx_hora_inicio_just_periodo_1  VARCHAR2(8),
    tx_hora_fim_just_periodo_1     VARCHAR2(8),
    tx_hora_inicio_just_periodo_2  VARCHAR2(8),
    tx_hora_fim_just_periodo_2     VARCHAR2(8))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_objeto_contrato
ADD CONSTRAINT pk_oasis2_024 PRIMARY KEY (cd_objeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_ocorrencia_administrativa
    (dt_ocorrencia_administrativa   DATE NOT NULL,
    cd_evento                      NUMBER(8,0) NOT NULL,
    cd_contrato                    NUMBER(*,0) NOT NULL,
    tx_ocorrencia_administrativa   VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_ocorrencia_administrativa
ADD CONSTRAINT pk_oasis2_023 PRIMARY KEY (dt_ocorrencia_administrativa, 
  cd_evento, cd_contrato)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_parcela
    (cd_parcela                     NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    ni_parcela                     NUMBER(*,0),
    ni_horas_parcela               NUMBER(8,1),
    ni_mes_previsao_parcela        NUMBER(*,0),
    ni_ano_previsao_parcela        NUMBER(*,0),
    ni_mes_execucao_parcela        NUMBER(*,0),
    ni_ano_execucao_parcela        NUMBER(*,0),
    st_modulo_proposta             CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_parcela
ADD CONSTRAINT pk_oasis2_022 PRIMARY KEY (cd_parcela, cd_projeto, cd_proposta)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_penalizacao
    (dt_penalizacao                 DATE NOT NULL,
    cd_contrato                    NUMBER(*,0) NOT NULL,
    cd_penalidade                  NUMBER(*,0) NOT NULL,
    tx_obs_penalizacao             VARCHAR2(4000),
    tx_justificativa_penalizacao   VARCHAR2(4000),
    ni_qtd_ocorrencia              NUMBER(*,0),
    st_aceite_justificativa        CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_penalizacao
ADD CONSTRAINT pk_oasis2_021 PRIMARY KEY (dt_penalizacao, cd_contrato, 
  cd_penalidade)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_plano_implantacao
    (cd_projeto                     NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    tx_descricao_plano_implantacao VARCHAR2(100) NOT NULL,
    cd_prof_plano_implantacao      NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_plano_implantacao
ADD CONSTRAINT pk_oasis2_020 PRIMARY KEY (cd_projeto, cd_proposta)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_pre_demanda
    (cd_pre_demanda                 NUMBER(*,0) NOT NULL,
    cd_objeto_emissor              NUMBER(*,0),
    cd_objeto_receptor             NUMBER(*,0),
    ni_ano_solicitacao             NUMBER(*,0),
    ni_solicitacao                 NUMBER(*,0),
    tx_pre_demanda                 VARCHAR2(4000),
    st_aceite_pre_demanda          CHAR(1),
    dt_pre_demanda                 TIMESTAMP (6),
    cd_profissional_solicitante    NUMBER(8,0),
    dt_fim_pre_demanda             TIMESTAMP (6),
    st_fim_pre_demanda             CHAR(1),
    dt_aceite_pre_demanda          TIMESTAMP (6),
    tx_obs_aceite_pre_demanda      VARCHAR2(4000),
    tx_obs_reabertura_pre_demanda  VARCHAR2(4000),
    st_reabertura_pre_demanda      CHAR(1),
    cd_unidade                     NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_pre_demanda
ADD CONSTRAINT pk_oasis2_019 PRIMARY KEY (cd_pre_demanda)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_pre_projeto
    (cd_pre_projeto                 NUMBER(8,0) NOT NULL,
    cd_unidade                     NUMBER(*,0),
    cd_gerente_pre_projeto         NUMBER(*,0),
    tx_pre_projeto                 VARCHAR2(200),
    tx_sigla_pre_projeto           VARCHAR2(100),
    tx_contexto_geral_pre_projeto  VARCHAR2(4000),
    tx_escopo_pre_projeto          VARCHAR2(4000),
    tx_gestor_pre_projeto          VARCHAR2(200),
    tx_obs_pre_projeto             VARCHAR2(4000),
    st_impacto_pre_projeto         CHAR(1),
    st_prioridade_pre_projeto      CHAR(1),
    tx_horas_estimadas             VARCHAR2(10),
    tx_pub_alcancado_pre_proj      VARCHAR2(200),
    cd_contrato                    NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_pre_projeto
ADD CONSTRAINT pk_oasis2_018 PRIMARY KEY (cd_pre_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_pre_projeto_evolutivo
    (cd_pre_projeto_evolutivo       NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    tx_pre_projeto_evolutivo       VARCHAR2(200),
    tx_objetivo_pre_proj_evol      VARCHAR2(4000),
    st_gerencia_mudanca            CHAR(1),
    dt_gerencia_mudanca            DATE,
    cd_contrato                    NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_pre_projeto_evolutivo
ADD CONSTRAINT pk_oasis2_017 PRIMARY KEY (cd_pre_projeto_evolutivo, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_previsao_projeto_diario
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    ni_mes                         NUMBER(*,0) NOT NULL,
    ni_dia                         NUMBER(*,0) NOT NULL,
    ni_horas                       NUMBER(*,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_previsao_projeto_diario
ADD CONSTRAINT pk_oasis2_016 PRIMARY KEY (cd_projeto, ni_mes, ni_dia)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_processamento_parcela
    (cd_processamento_parcela       NUMBER(8,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_parcela                     NUMBER(*,0) NOT NULL,
    cd_objeto_execucao             NUMBER(*,0),
    ni_ano_solicitacao_execucao    NUMBER(*,0),
    ni_solicitacao_execucao        NUMBER(*,0),
    st_autorizacao_parcela         CHAR(1),
    dt_autorizacao_parcela         TIMESTAMP (6),
    cd_prof_autorizacao_parcela    NUMBER(8,0),
    st_fechamento_parcela          CHAR(1),
    dt_fechamento_parcela          TIMESTAMP (6),
    cd_prof_fechamento_parcela     NUMBER(*,0),
    st_parecer_tecnico_parcela     CHAR(1),
    dt_parecer_tecnico_parcela     TIMESTAMP (6),
    tx_obs_parecer_tecnico_parcela VARCHAR2(4000),
    cd_prof_parecer_tecnico_parc   NUMBER(*,0),
    st_aceite_parcela              CHAR(1),
    dt_aceite_parcela              TIMESTAMP (6),
    tx_obs_aceite_parcela          VARCHAR2(4000),
    cd_profissional_aceite_parcela NUMBER(*,0),
    st_homologacao_parcela         CHAR(1),
    dt_homologacao_parcela         TIMESTAMP (6),
    tx_obs_homologacao_parcela     VARCHAR2(4000),
    cd_prof_homologacao_parcela    NUMBER(*,0),
    st_ativo                       CHAR(1),
    st_pendente                    CHAR(1),
    dt_inicio_pendencia            TIMESTAMP (6),
    dt_fim_pendencia               TIMESTAMP (6),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_processamento_parcela
ADD CONSTRAINT pk_oasis2_015 PRIMARY KEY (cd_processamento_parcela, cd_proposta, 
  cd_projeto, cd_parcela)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_processamento_proposta
    (cd_processamento_proposta      NUMBER(8,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    st_fechamento_proposta         CHAR(1),
    dt_fechamento_proposta         TIMESTAMP (6),
    cd_prof_fechamento_proposta    NUMBER(*,0),
    st_parecer_tecnico_proposta    CHAR(1),
    dt_parecer_tecnico_proposta    TIMESTAMP (6),
    tx_obs_parecer_tecnico_prop    VARCHAR2(4000),
    cd_prof_parecer_tecnico_propos NUMBER(*,0),
    st_aceite_proposta             CHAR(1),
    dt_aceite_proposta             TIMESTAMP (6),
    tx_obs_aceite_proposta         VARCHAR2(4000),
    cd_prof_aceite_proposta        NUMBER(*,0),
    st_homologacao_proposta        CHAR(1),
    dt_homologacao_proposta        TIMESTAMP (6),
    tx_obs_homologacao_proposta    VARCHAR2(4000),
    cd_prof_homologacao_proposta   NUMBER(*,0),
    st_alocacao_proposta           CHAR(1),
    dt_alocacao_proposta           TIMESTAMP (6),
    cd_prof_alocacao_proposta      NUMBER(*,0),
    st_ativo                       CHAR(1),
    tx_motivo_alteracao_proposta   VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_processamento_proposta
ADD CONSTRAINT pk_oasis2_014 PRIMARY KEY (cd_processamento_proposta, cd_projeto, 
  cd_proposta)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_produto_parcela
    (cd_produto_parcela             NUMBER(*,0) NOT NULL,
    cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_parcela                     NUMBER(*,0) NOT NULL,
    tx_produto_parcela             VARCHAR2(4000),
    cd_tipo_produto                NUMBER(*,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_produto_parcela
ADD CONSTRAINT pk_oasis2_013 PRIMARY KEY (cd_produto_parcela, cd_proposta, 
  cd_projeto, cd_parcela)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_profissional
    (cd_profissional                NUMBER(*,0) NOT NULL,
    tx_profissional                VARCHAR2(4000),
    cd_relacao_contratual          NUMBER(*,0),
    cd_empresa                     NUMBER(8,0),
    tx_nome_conhecido              VARCHAR2(100),
    tx_telefone_residencial        VARCHAR2(20),
    tx_celular_profissional        VARCHAR2(20),
    tx_ramal_profissional          VARCHAR2(10),
    tx_endereco_profissional       VARCHAR2(200),
    dt_nascimento_profissional     DATE,
    dt_inicio_trabalho             DATE,
    dt_saida_profissional          DATE,
    tx_email_institucional         VARCHAR2(200),
    tx_email_pessoal               VARCHAR2(200),
    st_nova_senha                  CHAR(1),
    st_inativo                     CHAR(1),
    tx_senha                       VARCHAR2(50),
    tx_data_ultimo_acesso          VARCHAR2(4000),
    tx_hora_ultimo_acesso          VARCHAR2(4000),
    cd_perfil                      NUMBER(8,0),
    st_dados_todos_contratos       CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_profissional
ADD CONSTRAINT pk_oasis2_012 PRIMARY KEY (cd_profissional)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_projeto
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_profissional_gerente        NUMBER(*,0),
    cd_unidade                     NUMBER(*,0),
    cd_status                      NUMBER(*,0),
    tx_projeto                     VARCHAR2(4000),
    tx_obs_projeto                 VARCHAR2(4000),
    tx_sigla_projeto               VARCHAR2(4000),
    tx_gestor_projeto              VARCHAR2(4000),
    st_impacto_projeto             CHAR(1),
    st_prioridade_projeto          CHAR(1),
    tx_escopo_projeto              VARCHAR2(4000),
    tx_contexto_geral_projeto      VARCHAR2(4000),
    tx_publico_alcancado           VARCHAR2(200),
    ni_mes_inicio_previsto         NUMBER(4,0),
    ni_ano_inicio_previsto         NUMBER(4,0),
    ni_mes_termino_previsto        NUMBER(4,0),
    ni_ano_termino_previsto        NUMBER(4,0),
    tx_co_gestor_projeto           VARCHAR2(200),
    st_dicionario_dados            CHAR(1) DEFAULT 0,
    st_informacoes_tecnicas        CHAR(1) DEFAULT 0,
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_projeto
ADD CONSTRAINT pk_oasis2_011 PRIMARY KEY (cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_projeto_continuado
    (cd_projeto_continuado          NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    tx_projeto_continuado          VARCHAR2(4000),
    tx_objetivo_projeto_continuado VARCHAR2(4000),
    tx_obs_projeto_continuado      VARCHAR2(4000),
    st_prioridade_proj_continuado  CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_projeto_continuado
ADD CONSTRAINT pk_oasis2_010 PRIMARY KEY (cd_projeto_continuado, cd_objeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_projeto_previsto
    (cd_projeto_previsto            NUMBER(*,0) NOT NULL,
    cd_contrato                    NUMBER(*,0) NOT NULL,
    cd_unidade                     NUMBER(*,0),
    tx_projeto_previsto            VARCHAR2(4000),
    ni_horas_projeto_previsto      NUMBER(*,0),
    st_projeto_previsto            CHAR(1),
    tx_descricao_projeto_previsto  VARCHAR2(4000),
    id                             NUMBER(8,0),
    cd_definicao_metrica           NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_projeto_previsto
ADD CONSTRAINT pk_oasis2_009 PRIMARY KEY (cd_projeto_previsto, cd_contrato)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_proposta
    (cd_proposta                    NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    ni_ano_solicitacao             NUMBER(*,0) NOT NULL,
    ni_solicitacao                 NUMBER(*,0) NOT NULL,
    st_encerramento_proposta       CHAR(1),
    dt_encerramento_proposta       TIMESTAMP (6),
    cd_prof_encerramento_proposta  NUMBER(*,0),
    ni_horas_proposta              NUMBER(8,1),
    st_alteracao_proposta          CHAR(1),
    st_contrato_anterior           CHAR(1),
    tx_motivo_insatisfacao         VARCHAR2(4000),
    tx_gestao_qualidade            VARCHAR2(4000),
    st_descricao                   CHAR(1) DEFAULT 0,
    st_profissional                CHAR(1) DEFAULT 0,
    st_metrica                     CHAR(1) DEFAULT 0,
    st_documentacao                CHAR(1) DEFAULT 0,
    st_modulo                      CHAR(1) DEFAULT 0,
    st_parcela                     CHAR(1) DEFAULT 0,
    st_produto                     CHAR(1) DEFAULT 0,
    st_caso_de_uso                 CHAR(1) DEFAULT 0,
    ni_mes_proposta                NUMBER(4,0),
    ni_ano_proposta                NUMBER(4,0),
    tx_objetivo_proposta           VARCHAR2(4000),
    id                             NUMBER(8,0),
    st_requisito                   CHAR(1) DEFAULT 0,
    nf_indice_avaliacao_proposta   NUMBER(8,0),
    st_objetivo_proposta           CHAR(1) DEFAULT 0
)
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_proposta
ADD CONSTRAINT pk_oasis2_008 PRIMARY KEY (cd_proposta, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_regra_negocio
    (cd_regra_negocio               NUMBER(*,0) NOT NULL,
    dt_regra_negocio               TIMESTAMP (6) NOT NULL,
    cd_projeto_regra_negocio       NUMBER(8,0) NOT NULL,
    tx_regra_negocio               VARCHAR2(4000),
    tx_descricao_regra_negocio     VARCHAR2(4000),
    st_regra_negocio               CHAR(1),
    ni_versao_regra_negocio        NUMBER(*,0),
    dt_fechamento_regra_negocio    DATE,
    ni_ordem_regra_negocio         NUMBER(8,0),
    st_fechamento_regra_negocio    CHAR(1),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_regra_negocio
ADD CONSTRAINT pk_oasis2_007 PRIMARY KEY (cd_regra_negocio, dt_regra_negocio, 
  cd_projeto_regra_negocio)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_requisito
    (cd_requisito                   NUMBER(*,0) NOT NULL,
    dt_versao_requisito            TIMESTAMP (6) NOT NULL,
    cd_projeto                     NUMBER(8,0) NOT NULL,
    st_tipo_requisito              CHAR(1),
    tx_requisito                   VARCHAR2(4000),
    tx_descricao_requisito         VARCHAR2(4000),
    ni_versao_requisito            NUMBER(8,0),
    st_prioridade_requisito        CHAR(1),
    st_requisito                   CHAR(1),
    tx_usuario_solicitante         VARCHAR2(4000),
    tx_nivel_solicitante           VARCHAR2(4000),
    st_fechamento_requisito        CHAR(1),
    dt_fechamento_requisito        DATE,
    ni_ordem                       NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_requisito
ADD CONSTRAINT pk_oasis2_006 PRIMARY KEY (cd_requisito, dt_versao_requisito, 
  cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_reuniao
    (cd_reuniao                     NUMBER(*,0) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    dt_reuniao                     DATE,
    tx_pauta                       VARCHAR2(4000),
    tx_participantes               VARCHAR2(4000),
    tx_ata                         VARCHAR2(4000),
    tx_local_reuniao               VARCHAR2(4000),
    cd_profissional                NUMBER(*,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_reuniao
ADD CONSTRAINT pk_oasis2_005 PRIMARY KEY (cd_reuniao, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_situacao_projeto
    (cd_projeto                     NUMBER(*,0) NOT NULL,
    ni_mes_situacao_projeto        NUMBER(*,0) NOT NULL,
    ni_ano_situacao_projeto        NUMBER(*,0) NOT NULL,
    tx_situacao_projeto            VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_situacao_projeto
ADD CONSTRAINT pk_oasis2_004 PRIMARY KEY (cd_projeto, ni_mes_situacao_projeto, 
  ni_ano_situacao_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_solicitacao
    (ni_solicitacao                 NUMBER(*,0) NOT NULL,
    ni_ano_solicitacao             NUMBER(*,0) NOT NULL,
    cd_objeto                      NUMBER(*,0) NOT NULL,
    cd_profissional                NUMBER(*,0),
    cd_unidade                     NUMBER(*,0),
    tx_solicitacao                 VARCHAR2(4000),
    st_solicitacao                 CHAR(1),
    tx_justificativa_solicitacao   VARCHAR2(4000),
    dt_justificativa               TIMESTAMP (6),
    st_aceite                      CHAR(1),
    dt_aceite                      TIMESTAMP (6),
    tx_obs_aceite                  VARCHAR2(4000),
    st_fechamento                  CHAR(1),
    dt_fechamento                  TIMESTAMP (6),
    st_homologacao                 CHAR(1),
    dt_homologacao                 TIMESTAMP (6),
    tx_obs_homologacao             VARCHAR2(4000),
    ni_dias_execucao               NUMBER(*,0),
    tx_problema_encontrado         VARCHAR2(4000),
    tx_solucao_solicitacao         VARCHAR2(4000),
    st_grau_satisfacao             CHAR(1),
    tx_obs_grau_satisfacao         VARCHAR2(4000),
    dt_grau_satisfacao             TIMESTAMP (6),
    dt_leitura_solicitacao         TIMESTAMP (6),
    dt_solicitacao                 TIMESTAMP (6),
    tx_solicitante                 VARCHAR2(4000),
    tx_sala_solicitante            VARCHAR2(4000),
    tx_telefone_solicitante        VARCHAR2(4000),
    tx_obs_solicitacao             VARCHAR2(4000),
    tx_execucao_solicitacao        VARCHAR2(4000),
    ni_prazo_atendimento           NUMBER(8,0),
    id                             NUMBER(8,0),
    st_aceite_just_solicitacao     CHAR(1),
    tx_obs_aceite_just_solicitacao VARCHAR2(1000))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_solicitacao
ADD CONSTRAINT pk_oasis2_003 PRIMARY KEY (ni_solicitacao, ni_ano_solicitacao, 
  cd_objeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_solicitacao_pedido
    (cd_solicitacao_pedido          NUMBER(8,0) NOT NULL,
    cd_usuario_pedido              NUMBER(8,0) NOT NULL,
    cd_unidade_pedido              NUMBER(*,0) NOT NULL,
    dt_solicitacao_pedido          TIMESTAMP (6) NOT NULL,
    st_situacao_pedido             CHAR(1) DEFAULT 'P'  NOT NULL,
    tx_observacao_pedido           VARCHAR2(4000),
    dt_encaminhamento_pedido       TIMESTAMP (6),
    dt_autorizacao_competente      TIMESTAMP (6),
    tx_analise_aut_competente      VARCHAR2(4000),
    dt_analise_area_ti_solicitacao TIMESTAMP (6),
    tx_analise_area_ti_solicitacao VARCHAR2(4000),
    dt_analise_area_ti_chefia_sol  TIMESTAMP (6),
    tx_analise_area_ti_chefia_sol  VARCHAR2(4000),
    dt_analise_comite              TIMESTAMP (6),
    tx_analise_comite              VARCHAR2(4000),
    dt_analise_area_ti_chefia_exec TIMESTAMP (6),
    tx_analise_area_ti_chefia_exec VARCHAR2(4000),
    cd_usuario_aut_competente      NUMBER(8,0),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_solicitacao_pedido
ADD CONSTRAINT pk_oasis_153 PRIMARY KEY (cd_solicitacao_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_tabela
    (tx_tabela                      VARCHAR2(100) NOT NULL,
    cd_projeto                     NUMBER(*,0) NOT NULL,
    tx_descricao                   VARCHAR2(4000),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_tabela
ADD CONSTRAINT pk_oasis2_002 PRIMARY KEY (tx_tabela, cd_projeto)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

CREATE TABLE s_usuario_pedido
    (cd_usuario_pedido              NUMBER(*,0) NOT NULL,
    cd_unidade_usuario             NUMBER(*,0) NOT NULL,
    st_autoridade                  CHAR(1) DEFAULT 'N'  NOT NULL,
    st_inativo                     CHAR(1) DEFAULT 'N'  NOT NULL,
    tx_nome_usuario                VARCHAR2(100) NOT NULL,
    tx_email_institucional               VARCHAR2(100) NOT NULL,
    tx_senha_acesso                VARCHAR2(40),
    tx_sala_usuario                VARCHAR2(50),
    tx_telefone_usuario            VARCHAR2(50),
    id                             NUMBER(8,0))
  PCTFREE     10
  INITRANS    1
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

ALTER TABLE s_usuario_pedido
ADD CONSTRAINT pk_oasis2_001 PRIMARY KEY (cd_usuario_pedido)
USING INDEX
  PCTFREE     10
  INITRANS    2
  MAXTRANS    255
  TABLESPACE  oasis
  STORAGE   (
    INITIAL     65536
    MINEXTENTS  1
    MAXEXTENTS  2147483645
  )
/

COMMENT ON COLUMN s_usuario_pedido.st_autoridade IS 'A = Autoridade Competente
C = Comite
N = Usuário comum
T = Todos'
/


