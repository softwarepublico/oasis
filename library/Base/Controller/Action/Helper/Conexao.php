<?php
class Base_Controller_Action_Helper_Conexao extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Constantes dos adapter do PDO
     */
    const ADAPTER_PDO_POSTGRES = "Pdo_Pgsql";
    const ADAPTER_PDO_ORACLE   = "Pdo_Oci";
    const ADAPTER_PDO_MYSQL    = "Pdo_Mysql";
    const ADAPTER_PDO_MSSQL    = "Pdo_Mssql";
    
    /**
     * Constantes dos adapter sem PDO
     */
    const ADAPTER_POSTGRES = "Postgres";
    const ADAPTER_ORACLE   = "Oracle";
    const ADAPTER_MYSQL    = "Mysqli";
    const ADAPTER_MSSQL    = "Sqlsrv";

    /**
     * Atributo que recebe o valor da conexão criada
     */
    public $_connection;

    /**
     * Atributo que recebe um array com as tabelas da conexÃ£o criada;
     */
    private $_arrTable;

    /**
     * Atributo que recebe um array com as colunas da conexÃ£o criada;
     */
    private $_arrColumn;

    /**
     * Atributo que recebe um array com as tabelas da conexÃ£o criada;
     */
    private $_arrQuantTable;

    /**
     * Atributo que recebe o código do projeto
     */
    private $_cd_projeto;

    public function setArrTable(Array $arrTable)
    {
        $this->_arrTable = $arrTable;
    }

    public function getArrTable()
    {
        return $this->_arrTable;
    }

    public function setArrColumn(Array $arrColumn)
    {
        $this->_arrColumn = $arrColumn;
    }

    public function getArrColumn()
    {
        return $this->_arrColumn;
    }

    public function setArrQuantTable(Array $arrQuantTable)
    {
        $this->_arrQuantTable = $arrQuantTable;
    }

    public function getArrQuantTable()
    {
        return $this->_arrQuantTable;
    }

    public function setConnection($connection)
    {
        $this->_connection = $connection;
    }

    public function getConnection()
    {
        return $this->_connection;
    }

    public function setProjeto($cd_projeto)
    {
        $this->_cd_projeto = $cd_projeto;
    }

    public function getProjeto()
    {
        return $this->_cd_projeto;
    }

    /**
     * Método que testa conexão com o banco de dados.
     * Utilizado na tela de Dicionário de Dados e no instalador,
     * verificando se os dados informados são mesmo de uma conexão
     * valida.
     * @param array $arrDataConection['tx_adapter']
     * @param array $arrDataConection['tx_host']
     * @param array $arrDataConection['tx_username']
     * @param array $arrDataConection['tx_username']
     * @param array $arrDataConection['tx_dbname']
     * @param array $arrDataConection['tx_port']
     * @param array $arrDataConection
     */
    public function validaConexao(array $arrDataConection)
    {
        $cod          = 2;
        $msg          = Base_Util::getTranslator('L_MSG_ERRO_RECUPERAR_MENSAGEM');
        $errorConexao = false;
        if(count($arrDataConection) > 0) {
            try {
                $arrConection = array(
                    'host'     => $arrDataConection['tx_host'],
                    'username' => $arrDataConection['tx_username'],
                    'password' => (K_INSTALL == "S") ? base64_decode( $arrDataConection['tx_password'] ) : $arrDataConection['tx_password'],
                    'dbname'   => $arrDataConection['tx_dbname'],
                    'port'     => $arrDataConection['tx_port']
                );
                if(($arrDataConection['tx_adapter'] == self::ADAPTER_MYSQL || $arrDataConection['tx_adapter'] == self::ADAPTER_PDO_MYSQL) && K_INSTALL == 'N'){
                    $arrConection['driver_options'] = array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => 0);
                }
                if(($arrDataConection['tx_adapter'] == self::ADAPTER_MSSQL || $arrDataConection['tx_adapter'] == self::ADAPTER_PDO_MSSQL) && K_INSTALL == 'N'){
                    $arrConection['charset'] = 'utf8';
                }
                $this->setConnection(Zend_Db::factory(strtolower($arrDataConection['tx_adapter']),$arrConection));
                if(!$this->getConnection()) {
                    $cod          = 2;
                    $errorConexao = true;
                    $msg          = Base_Util::getTranslator('L_MSG_ERRO_ESTABELECER_CONEXAO');
                } else {
                //Condição que verifica se o schema do banco de dados Postgres existe
                    if($arrDataConection['tx_adapter'] == self::ADAPTER_PDO_POSTGRES) {
                        $arrSchema = $this->validaSchemaPostgres($arrDataConection['tx_schema']);
                        if(count($arrSchema) > 0) {
                            $cod = 1;
                            $msg = Base_Util::getTranslator('L_MSG_SUCESS_VALIDAR_INFORMACOES');
//                            $errorConexao = false;
                        } else {
                            $cod = 2;
                            $msg = Base_Util::getTranslator('L_MSG_ALERT_SCHEMA_SEM_TABELA_OU_INEXISTENTE');
                            if(K_INSTALL == "N") {
                                $msg .= "<br /> ".Base_Util::getTranslator('L_MSG_ALERT_SCHEMA_SERA_CRIADO');
                            }
                        }
                    //condição que verifica se o schema do banco de dados ORACLE existe
                    } else if(($arrDataConection['tx_adapter'] == self::ADAPTER_PDO_ORACLE) || ($arrDataConection['tx_adapter'] == self::ADAPTER_ORACLE)) {
                            $this->_getTableOracle($arrDataConection['tx_schema']);
                            if(count($this->getArrTable()) > 0) {
                                $cod = 1;
                                $msg = Base_Util::getTranslator('L_MSG_SUCESS_VALIDAR_INFORMACOES');
//                                $errorConexao = false;
                            } else {
                                $cod = 2;
                                $msg = Base_Util::getTranslator('L_MSG_ALERT_SCHEMA_SEM_TABELA_OU_INEXISTENTE');
                                if(K_INSTALL == "N") {
                                    $msg .= "<br /> ".Base_Util::getTranslator('L_MSG_ALERT_SCHEMA_SERA_CRIADO');
                                }
                            }
                        } else if(($arrDataConection['tx_adapter'] == self::ADAPTER_PDO_MSSQL)||($arrDataConection['tx_adapter'] == self::ADAPTER_MSSQL)) {
                                $this->_getTableSqlserver($arrDataConection['tx_dbname']);
                                if(count($this->getArrTable()) > 0) {
                                    $cod = 1;
                                    $msg = Base_Util::getTranslator('L_MSG_SUCESS_VALIDAR_INFORMACOES');
//                                    $errorConexao = false;
                                } else {
                                    $cod = 2;
                                    $msg = Base_Util::getTranslator('L_MSG_ALERT_BANCO_DADOS_SEM_TABELA_OU_INEXISTENTE');
                                }
                            } else if(($arrDataConection['tx_adapter'] == self::ADAPTER_PDO_MYSQL) || ($arrDataConection['tx_adapter'] == self::ADAPTER_MYSQL)) {
                                    $this->_getTableMysql($arrDataConection['tx_schema']);
                                    if($this->getArrTable()) {
                                        $cod = 1;
                                        $msg = Base_Util::getTranslator('L_MSG_SUCESS_VALIDAR_INFORMACOES');
//                                        $errorConexao = false;
                                    } else {
                                        $cod = 2;
                                        $msg = Base_Util::getTranslator('L_MSG_ALERT_BANCO_DADOS_SEM_TABELA_OU_INEXISTENTE');
                                    }
                                }
                }
            } catch (Zend_Db_Adapter_Exception $e) {
                $error = strpos($e->getMessage(),'host');
                if(!$error) {
                    $error = strpos($e->getMessage(),'password');
                    if(!$error) {
                        $error = strpos($e->getMessage(),'database');
                        if(!$error) {
                            $error = strpos($e->getMessage(),'driver');
                            if($error) {
                                $cod          = 2;
                                $errorConexao = true;
                                $msg          = Base_Util::getTranslator('L_MSG_ERRO_BANCO_DADOS_SEM_DRIVER_INSTALADO');
                            } else {
                                $cod          = 2;
                                $errorConexao = true;
                                $msg          = Base_Util::getTranslator('L_MSG_ERRO_EXECUCAO_OPERACAO').$e->getMessage();
                            }
                        } else {
                            $cod          = 2;
                            $errorConexao = true;
                            $msg          = Base_Util::getTranslator('L_MSG_ERRO_BANCO_DADOS_INEXISTENTE');
                        }
                    } else {
                        $cod          = 2;
                        $errorConexao = true;
                        $msg          = Base_Util::getTranslator('L_MSG_ERRO_USUARIO_SENHA_ACESSO_INVALIDO');
                    }
                } else {
                    $cod          = 2;
                    $errorConexao = true;
                    $msg          = Base_Util::getTranslator('L_MSG_ERRO_NOME_SERVIDOR_INVALIDO');
                }
            }
        }
        return $arrRetorno = array('type'=>$cod,'msg'=>$msg,'error'=>$errorConexao);
    }

    /**
     * Método que verifica se o schema existe em um
     * determinado dataBase no banco de dados POSTGRESQL
     *
     * @param text $schema;
     * return boolean
     */
    public function validaSchemaPostgres($schema)
    {
        $sql = " SELECT *
                 FROM
                    pg_namespace
                 WHERE
                    nspname = '{$schema}' ";
        return $this->getConnection()->fetchCol($sql);
    }

    public function getRecuperaComentario($tx_adapter,$tx_tabela,$tx_schema)
    {
        switch ($tx_adapter) {
            case self::ADAPTER_PDO_POSTGRES:
            case self::ADAPTER_POSTGRES:
                return $this->_getRecuperaComentarioPgsql($tx_tabela,$tx_schema);
                break;
            case self::ADAPTER_PDO_ORACLE:
            case self::ADAPTER_ORACLE:
                return $this->_getRecuperaComentarioOracle($tx_tabela,$tx_schema);
                break;
            case self::ADAPTER_PDO_MSSQL:
            case self::ADAPTER_MSSQL:
                return $this->_getRecuperaComentarioSqlserver($tx_tabela,$tx_schema);
                break;
            case self::ADAPTER_PDO_MYSQL:
            case self::ADAPTER_MYSQL:
                return $this->_getRecuperaComentarioTableMysql($tx_tabela,$tx_schema);
                break;
        }
    }

    protected function _getRecuperaComentarioPgsql($tx_tabela,$tx_schema)
    {
        $sql = "SELECT
                    c.relname as table_name,
                    pa.attname as column,
                    pd.description
                FROM
                    pg_catalog.pg_class c
                JOIN
                    pg_namespace n
                ON
                    (c.relnamespace = n.oid and n.nspname not in ('information_schema','pg_catalog')
                AND
                    c.relkind = 'r')
                JOIN
                    pg_description as pd
                ON
                    pd.objoid = c.oid
                LEFT JOIN
                    pg_attribute as pa
                ON
                    pd.objsubid = pa.attnum and pa.attrelid = c.oid
                WHERE
                    n.nspname = '{$tx_schema}'
                AND
                    c.relname = '{$tx_tabela}'
                ORDER BY
                    c.relname ";
        return $this->getConnection()->fetchAll($sql);
    }

    protected function _getRecuperaComentarioOracle($tx_tabela,$tx_schema)
    {
        $sql = "SELECT
                  ALL_TABLES.TABLE_NAME, 
                  null AS \"COLUMN\",
                  DBA_TAB_COMMENTS.COMMENTS as DESCRIPTION
                from
                  ALL_TABLES
                JOIN
                  DBA_TAB_COMMENTS
                ON
                  ALL_TABLES.TABLE_NAME = DBA_TAB_COMMENTS.TABLE_NAME
                AND
                  ALL_TABLES.OWNER = DBA_TAB_COMMENTS.OWNER
                WHERE
                  ALL_TABLES.TABLE_NAME = UPPER('{$tx_tabela}')
                and
                  ALL_TABLES.OWNER = UPPER('{$tx_schema}')
                UNION
                SELECT
                  ALL_TABLES.TABLE_NAME, 
                  DBA_TAB_COLUMNS.COLUMN_NAME as \"COLUMN\",
                  DBA_COL_COMMENTS.COMMENTS as DESCRIPTION
                from
                  ALL_TABLES
                JOIN
                  DBA_TAB_COMMENTS
                ON
                  ALL_TABLES.TABLE_NAME = DBA_TAB_COMMENTS.table_name
                AND
                  ALL_TABLES.owner = DBA_TAB_COMMENTS.owner
                JOIN
                  DBA_TAB_COLUMNS
                ON
                  DBA_TAB_COLUMNS.OWNER = ALL_TABLES.OWNER
                AND
                  DBA_TAB_COLUMNS.TABLE_NAME = ALL_TABLES.TABLE_NAME
                JOIN
                  DBA_COL_COMMENTS
                ON
                  DBA_TAB_COLUMNS.TABLE_NAME = DBA_COL_COMMENTS.TABLE_NAME
                AND
                  DBA_TAB_COLUMNS.OWNER = DBA_COL_COMMENTS.OWNER
                AND
                  DBA_TAB_COLUMNS.COLUMN_NAME = DBA_COL_COMMENTS.COLUMN_NAME
                WHERE
                  DBA_TAB_COLUMNS.TABLE_NAME = UPPER('{$tx_tabela}')
                and
                  DBA_TAB_COLUMNS.OWNER = UPPER('{$tx_schema}')
                order by
                  \"COLUMN\" NULLS FIRST
		";
        return $this->getConnection()->fetchAll($sql);
    }

    protected function _getRecuperaComentarioSqlserver($tx_tabela,$tx_schema)
    {
        $sql = "";
        return $this->getConnection()->fetchAll($sql);
    }

    protected function _getRecuperaComentarioTableMysql($tx_tabela,$tx_schema)
    {
        $sql = "";
        return $this->getConnection()->fetchAll($sql);
    }

    /**
     * Método que verifica qual banco esta
     * @param text $tx_adapter
     * @param text $schema
     */
    public function getListTable($tx_adapter, $schema = "")
    {
        switch ($tx_adapter) {
            case self::ADAPTER_PDO_POSTGRES:
            case self::ADAPTER_POSTGRES:
                $this->_getTablePostgres($schema);
                break;
            case self::ADAPTER_PDO_ORACLE:
            case self::ADAPTER_ORACLE:
                $this->_getTableOracle($schema);
                break;
            case self::ADAPTER_PDO_MSSQL:
            case self::ADAPTER_MSSQL:
                $this->_getTableSqlserver($schema);
                break;
            case self::ADAPTER_PDO_MYSQL:
            case self::ADAPTER_MYSQL:
                $this->_getTableMysql($schema);
                break;
        }
    }

    protected function _getTablePostgres($schema)
    {
        $sql = "SELECT
                    c.relname AS tabela,
                    pg_catalog.pg_get_userbyid(c.relowner) AS dono,
                    pg_catalog.obj_description(c.oid, 'pg_class') AS comentario
                FROM
                    pg_catalog.pg_class c
                LEFT JOIN
                    pg_catalog.pg_namespace n
                ON
                    n.oid = c.relnamespace
                WHERE
                    c.relkind = 'r'
                AND
                    nspname='{$schema}'
                ORDER BY
                    c.relname";

        $this->setArrTable($this->getConnection()->fetchAll($sql));
    }

    protected function _getTableOracle($schema)
    {
        $sql = "SELECT
					all_tables.table_name as tabela,
                    all_tables.owner as dono,
					comments as comentario
				from
					all_tables
				JOIN
					DBA_TAB_COMMENTS
				ON
					all_tables.table_name = DBA_TAB_COMMENTS.table_name
				AND
					all_tables.owner = DBA_TAB_COMMENTS.owner
				WHERE
					upper(all_tables.owner) = upper('{$schema}')
				order by
					all_tables.table_name";

        $this->setArrTable($this->getConnection()->fetchAll($sql));
    }

    protected function _getTableSqlserver($schema)
    {
        $sql = "select
                    name table_name,
                    '{$schema}' as dono,
                    null comentario
                from
                    {$schema}.dbo.sysobjects
                where
                    xtype = 'u'
                and
                    name <> 'dtproperties'
                order by
                    name";

        $this->setArrTable($this->getConnection()->listTables());
    }

    protected function _getTableMysql()
    {
        $this->setArrTable($this->getConnection()->listTables());
    }


    public function getListColumn($tx_adapter,$tx_schema,$tx_tabela)
    {
        switch ($tx_adapter) {
            case self::ADAPTER_PDO_POSTGRES:
            case self::ADAPTER_POSTGRES:
                return $this->_getColumnPostgres($tx_tabela,$tx_schema);
                break;
            case self::ADAPTER_PDO_ORACLE:
            case self::ADAPTER_ORACLE:
                return $this->_getColumnOracle($tx_tabela,$tx_schema);
                break;
            case self::ADAPTER_PDO_MSSQL:
            case self::ADAPTER_MSSQL:
                return $this->_getColumnSqlserver($tx_tabela,$tx_schema);
                break;
            case self::ADAPTER_PDO_MYSQL:
            case self::ADAPTER_MYSQL:
                return $this->_getColumnMysql($tx_tabela,$tx_schema);
                break;
        }
    }
	
    protected function _getColumnPostgres($table, $schema)
    {
        $sql = "SELECT
					table_name as TX_TABELA,
					column_name,
                    data_type,
					CASE data_type WHEN 'numeric' then numeric_precision else character_maximum_length end as DATA_LENGTH,
					substring(is_nullable,1,1) as NULLABLE,
					pg_catalog.col_description(c.oid, ordinal_position) as comentario_banco
				from
					pg_catalog.pg_class c
				LEFT JOIN
					pg_catalog.pg_namespace n
				ON
					n.oid = c.relnamespace
				JOIN
					information_schema.columns
				ON
					table_schema = nspname and table_name = c.relname
				WHERE
					upper(table_name) = upper('{$table}')
				AND
					upper(table_schema) = upper('{$schema}')
				AND
					c.relkind = 'r'
				ORDER BY
					ordinal_position ";
					
        $this->setArrColumn($this->getConnection()->fetchAll($sql));
    }


    protected function _getColumnOracle($table, $schema)
    {
        //Código inserido para evitar timeout
		set_time_limit(120);
        
        $sql = "SELECT
                    DBA_TAB_COLUMNS.TABLE_NAME TX_TABELA,
                    DBA_TAB_COLUMNS.COLUMN_NAME,
                    DATA_TYPE,
                    DECODE(DATA_TYPE,'NUMBER', DATA_PRECISION || ',' || DATA_SCALE, DATA_LENGTH) AS DATA_LENGTH,
                    NULLABLE,
                    COMMENTS as comentario_banco
                FROM
                    DBA_TAB_COLUMNS
                JOIN
                    DBA_COL_COMMENTS
                ON
                    DBA_TAB_COLUMNS.TABLE_NAME = DBA_COL_COMMENTS.TABLE_NAME
                AND
                    DBA_TAB_COLUMNS.OWNER = DBA_COL_COMMENTS.OWNER
                AND
                    DBA_TAB_COLUMNS.COLUMN_NAME = DBA_COL_COMMENTS.COLUMN_NAME
                WHERE
                    UPPER(DBA_TAB_COLUMNS.TABLE_NAME) = UPPER('{$table}')
                AND
                    UPPER(DBA_TAB_COLUMNS.OWNER) = UPPER('{$schema}')
                ORDER BY
                    COLUMN_ID";
        $this->setArrColumn($this->getConnection()->fetchAll($sql));
    }

    protected function _getColumnSqlserver($table, $schema)
    {
        $sql = "SELECT
                    o.name TX_TABELA,
                    c.name COLUMN_NAME,
                    t.name DATA_TYPE,
                    case t.name when 'float' then c.xprec + ',' + c.xscale else c.length end AS DATA_LENGTH,
                    CASE c.isnullable WHEN 0 THEN 'N' WHEN 1 THEN 'Y' END NULLABLE,
                    p.value comentario_banco
                FROM
                    {$schema}..syscolumns c
                join
                    {$schema}..systypes t
                on
                    c.xtype = t.xtype
                join
                    {$schema}..sysobjects o
                on
                    c.id = o.id
                left join
                    {$schema}.dbo.sysproperties p
                on
                    p.id = o.id
                and
                    c.id = p.id
                and
                    p.smallid = c.colid
                where
                    o.name = '{$table}'
                order by
                    c.colid ";

        $this->setArrColumn($this->getConnection()->fetchAll($sql));
    }

    protected function _getColumnMysql()
    {
        $this->setArrTable($this->getConnection()->listTables());
    }

    public function getListQuantTable($tx_adapter,$tx_schema)
    {
        switch ($tx_adapter) {
            case self::ADAPTER_PDO_POSTGRES:
            case self::ADAPTER_POSTGRES:
                return $this->_getListQuantTablePostgres($tx_schema);
                break;
            case self::ADAPTER_PDO_ORACLE:
            case self::ADAPTER_ORACLE:
                return $this->_getListQuantTableOracle($tx_schema);
                break;
            case self::ADAPTER_PDO_MSSQL:
            case self::ADAPTER_MSSQL:
                return $this->_getListQuantTableSqlserver($tx_schema);
                break;
            case self::ADAPTER_PDO_MYSQL:
            case self::ADAPTER_MYSQL:
                return $this->_getListQuantTableMysql($tx_schema);
                break;
        }
    }

    protected function _getListQuantTablePostgres($schema)
    {
        $sql = "SELECT
                    count(c.relname) AS quant_table
                FROM
                    pg_catalog.pg_class c
                LEFT JOIN
                    pg_catalog.pg_namespace n
                ON
                    n.oid = c.relnamespace
                WHERE
                    c.relkind = 'r'
                AND
                    nspname='{$schema}'";

        $this->setArrQuantTable($this->getConnection()->fetchRow($sql));
    }


    protected function _getListQuantTableOracle($schema)
    {
        $sql = "SELECT
                  count(all_tables.table_name) as quant_table
                from
                  all_tables
                WHERE
                  upper(all_tables.owner) = upper('{$schema}')
                order by
                  all_tables.table_name";

        $this->setArrQuantTable($this->getConnection()->fetchRow($sql));
    }

    protected function _getListQuantTableSqlserver()
    {
        $this->setArrQuantTable($this->getConnection()->listTables());
    }

    protected function _getListQuantTableMysql()
    {
        $this->setArrQuantTable($this->getConnection()->listTables());
    }
}

