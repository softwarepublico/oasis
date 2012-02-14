
/*
OBS: Antes de rodar o script substituir:
     K_SCHEMA pelo esquema de seu banco e 
     K_USER pelo usuário de seu banco. 
*/

/*
  Campos Incluídos nas Tabelas

	Tabela a_form_inventario 		-> campo	tx_valor_subitem_inventario character varying(1000)
	Tabela a_form_inventario 		-> campo	cd_subitem_inv_descri numeric(8,0)
	Tabela s_solicitacao    		-> campo	cd_inventario numeric(8,0)
	Tabela b_perfil 	    		-> campo	st_tipo_perfil character(1)
	Tabela b_nivel_servico 	    		-> campo	ni_horas_prazo_execucao numeric(8,0)
	Tabela b_subitem_inventario    		-> campo	st_info_chamado_tecnico character(1)
	Tabela s_solicitacao    		-> campo	cd_item_inventario numeric(8,0)
	Tabela s_solicitacao    		-> campo	cd_item_inventariado numeric(8,0)
	Tabela b_perfil		    		-> campo	st_tipo_atuacao character(1)
	Tabela s_objeto_contrato    		-> campo	cd_area_atuacao_ti numeric(8,0)
	Tabela b_nivel_servico    		-> campo	cd_objeto numeric(8,0)
	Tabela b_nivel_servico    		-> campo	cd_nivel_servico numeric(8,0)

*/

	ALTER TABLE K_SCHEMA.a_form_inventario     	  ADD COLUMN tx_valor_subitem_inventario character varying(1000);
	ALTER TABLE K_SCHEMA.a_form_inventario     	  ADD COLUMN cd_subitem_inv_descri numeric(8,0);
	ALTER TABLE K_SCHEMA.s_solicitacao      	  ADD COLUMN cd_inventario numeric(8,0);
	ALTER TABLE K_SCHEMA.b_perfil		      	  ADD COLUMN st_tipo_perfil character(1);
	ALTER TABLE K_SCHEMA.b_nivel_servico	      	  ADD COLUMN ni_horas_prazo_execucao numeric(8,0);
	ALTER TABLE K_SCHEMA.b_subitem_inventario	  ADD COLUMN st_info_chamado_tecnico character(1);
	ALTER TABLE K_SCHEMA.s_solicitacao		  ADD COLUMN cd_item_inventario numeric(8,0);
	ALTER TABLE K_SCHEMA.s_solicitacao		  ADD COLUMN cd_item_inventariado numeric(8,0);
	ALTER TABLE K_SCHEMA.b_perfil		      	  ADD COLUMN st_tipo_atuacao character(1);
	ALTER TABLE K_SCHEMA.s_objeto_contrato	      	  ADD COLUMN cd_area_atuacao_ti numeric(8,0);
	ALTER TABLE K_SCHEMA.b_nivel_servico	      	  ADD COLUMN cd_objeto numeric(8,0);
	ALTER TABLE K_SCHEMA.b_nivel_servico	      	  ADD COLUMN cd_nivel_servico numeric(8,0);

	

/*
   Diferença de colunas
	
*/


/*
  TABELAS INCLUÍDAS NA VERSÃO 2.20 DO OASIS 

	"b_subitem_inv_descri"
	"a_contrato_item_inventario"


*/

CREATE TABLE K_SCHEMA.b_subitem_inv_descri
(
  cd_item_inventario numeric(8,0) NOT NULL,
  cd_subitem_inventario numeric(8,0) NOT NULL,
  cd_subitem_inv_descri numeric(8,0) NOT NULL,
  tx_subitem_inv_descri character varying(100),
  id numeric(8,0),
  ni_ordem integer,
  CONSTRAINT K_SCHEMA_b_subitem_inv_descri_pk PRIMARY KEY (cd_item_inventario, cd_subitem_inventario, cd_subitem_inv_descri),
  CONSTRAINT K_SCHEMA_b_subitem_inv_descri_fk1 FOREIGN KEY (cd_item_inventario, cd_subitem_inventario)
      REFERENCES oasis.b_subitem_inventario (cd_item_inventario, cd_subitem_inventario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);
ALTER TABLE K_SCHEMA.b_subitem_inv_descri OWNER TO K_USER;


CREATE TABLE K_SCHEMA.a_contrato_item_inventario
(
  cd_contrato numeric(8,0) NOT NULL,
  cd_item_inventario numeric(8,0) NOT NULL,
  id numeric(8,0),
  cd_inventario numeric(8,0),
  CONSTRAINT pk_K_SCHEMA_item_inventario_145 PRIMARY KEY (cd_contrato, cd_item_inventario),
  CONSTRAINT fk_K_SCHEMA_contrato_199 FOREIGN KEY (cd_contrato)
      REFERENCES K_SCHEMA.s_contrato (cd_contrato) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT fk_K_SCHEMA_item_inventario_198 FOREIGN KEY (cd_item_inventario)
      REFERENCES K_SCHEMA.b_item_inventario (cd_item_inventario) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);
ALTER TABLE K_SCHEMA.a_contrato_item_inventario OWNER TO postgres;


/*
   Inclusão no Menu
*/
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(314,NULL,'L_MENU_SOLICITACAO_SERVICO_SERVICE_DESK',NULL,'solicitacao-servico-service-desk',NULL,NULL,NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(315,314,'L_MENU_SOLICITACAO_SERVICE_DESK',NULL,'solicitacao-service-desk',NULL,NULL,NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(313,119,'L_MENU_ASSOCIAR_ITEM_INVENTARIO_CONTRATO',NULL,'associar-item-inventario-contrato',NULL,NULL,NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(53,55,'L_MENU_CASO_DE_USO_ATOR',NULL,'caso-de-uso-ator',NULL,NULL,NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(56,55,'L_MENU_CASO_DE_USO_DEFINICAO',NULL,'caso-de-uso-definicao',NULL,NULL,NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(317,255,'L_MENU_CHAMADO_TECNICO',NULL,'chamado-tecnico',NULL,'relatorioDemanda',NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(319,249,'L_MENU_ITEM_PARECER_TECNICO',NULL,'item-parecer',NULL,'relatorioDiverso',NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(316,14,'L_MENU_SUBITEM_INV_DESCRI',NULL,'subitem-inv-descri',NULL,NULL,NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(55,68,'L_MENU_CASO_DE_USO',NULL,'caso-de-uso',NULL,NULL,NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(54,55,'L_MENU_CASO_DE_USO_COMPLEMENTO',NULL,'caso-de-uso-complemento',NULL,NULL,NULL);
INSERT INTO K_SCHEMA.b_menu (cd_menu, cd_menu_pai, tx_menu, ni_nivel_menu, tx_pagina, st_menu, tx_modulo, id) VALUES
(57,55,'L_MENU_CASO_DE_USO_INTERACAO',NULL,'caso-de-uso-interacao',NULL,NULL,NULL);


