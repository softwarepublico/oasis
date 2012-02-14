<?php
//Constantes do OASIS
//Constantes de Configuração
//Definindo a constante com endereço do sistema...
$systemName = str_ireplace('/public/index.php', '', $_SERVER['PHP_SELF']);
define('SYSTEM_PATH', "http://{$_SERVER['HTTP_HOST']}{$systemName}");
define('SYSTEM_PATH_ABSOLUTE', "{$_SERVER['DOCUMENT_ROOT']}{$systemName}");
//Constante para verificação se o sistema já esta instalado.
define("K_INSTALL","S");
//TODO colocar na instalacao
define("K_LANGUAGE","pt_br");
// Constante para o endereço de rodapé
define('SYSTEM_NAME',str_replace('/','',$systemName));
define("K_NOME_ORGAO","aaaaaaaaaa");
define("K_ADDRES_ORGAO","OASIS - Exemplo com Dados <br /> Endereço , Bloco 'X' <br /> Brasília, DF, 70000-000 Brasil +55 (61) 0000-0000 ");
define("K_DDD_PREFIXO_TELEFONE","(11) 1111");
define("K_TELEFONE_ORGAO","(11) 1111-1111");
define('K_ANO_INICIO_COMBOS', 2010); //ano inicial para exibicao nos combos

//Constantes do Sistema
// Constante indica se será enviado Email pelo sistema ou não
define('K_EDITOR',805368827);
//constante indicando o tamanho maximo do aquivo de log
define('K_SIZE_FILE_LOG',524288); // Default 500KB
//constante indicando a quantidade máxima de arquivos de log por mês
define('K_MAX_FILE_LOG',1000); // Default mil arquivos de log

define('K_SIZE_10MB_EM_BYTE',10485760); //((10485760/1024)/1024) = 10MB
define('K_SIZE_200MB_EM_BYTE',209715200); //((209715200/1024)/1024) = 200MB

//Constantes de Configuração das telas
// Constante indicando qual o perfil técnico cadastrado para disponibilização de funcionalidades
define('K_CODIGO_PERFIL_GERENTE_PROJETO', 3);//Código do Perfil Gerente de Projeto
define('K_CODIGO_PERFIL_TECNICO', 4);//Código do Perfil Técnico
define('K_CODIGO_PERFIL_CONTROLE', 2);//Código do Perfil Controle
define('K_CODIGO_PERFIL_COORDENADOR', 1);//Código do Perfil Coordenador
//Constante para indicação da unidade default da área de TI
define('K_CD_UNIDADE', 1); //Código da unidade SE/SPOA/CGMI (COORDENAÇÃO-GERAL DE MODERNIZAÇÃO E INFORMÁTICA)

//Constantes para o relatório
define('K_HEADER_LOGO_RETRATO', 'logoRetrato.png');//Definindo imagem para o papel Retrato
define('K_HEADER_LOGO_PAISAGEM', 'logoPaisagem.png');//Defininfo imagem para o papel Paisagem
define('K_PATH_IMAGEM',SYSTEM_PATH_ABSOLUTE."/public/img/");
define('K_CREATOR_SYSTEM','OASIS');
define('K_TITLE_SYSTEM','Sistema de Gestão de Projetos, Demanda e Serviços de TI');
define('K_HEADER_TOP',33);
define("K_HEADER_COORDENACAO","COORDENAÇÃO GERAL DE TI");

//Constantes de E-mail
define("K_ENVIAR_EMAIL","N");
define("K_DOMINIO_EMAIL_PADRAO","@mdic.gov.br");
define("K_EMAIL_OASIS","");
define('K_NOME_CABECALHO_EMAIL', 'OASIS'); // Nome do cabecalho do e-mail enviado
define("K_SERVIDOR_EMAIL","");
define("K_PORTA_EMAIL","");

//Constantes do Banco de Dados
define("K_SCHEMA","oasis");
define("K_USER","postgres");

//Constante para definir o tipo de autenticação
//S => autentica via ldap, N => autenticação padrão oasis
define("K_LDAP_AUTENTICATE","N");

// Constante que indica o parecer vai seguir o fulxo normal (N) ou se vai para coordenação (S)
define("K_PARECER_TECNICO_NEGATIVO_COORDENACAO","S");
