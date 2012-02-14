<?php
class Base_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
	protected $_rowClass 	 = 'Base_Db_Table_Row';
	private $controller 	 = null;
	private $cd_profissional = null;

	public function  __construct($controller = null) {
		parent::__construct();
		$this->controller = $controller;
		$this->cd_profissional = (array_key_exists('oasis_logged',$_SESSION)) ? $_SESSION['oasis_logged'][0]['cd_profissional'] : null ;
	}

	public function getNewValue()
	{
		$sql = "SELECT MAX({$this->_primary[1]}) as new_value FROM {$this->_schema}.{$this->_name}";
		$res = $this->getDefaultAdapter()->fetchRow($sql);
		$new = 0;
		if (is_null($res['new_value'])) {
			$new = 1;
		} else {
			$new = $res['new_value'] +1;
		}
		return $new;
	}

    /**
     * Retorna o proximo valor do campo especificado
     *
     * @param string $campo
     * @param string $where = false
     * @return integer proximo numero | boolean false
     */
    public function getNextValueOfField( $campo , $where = false)
    {
        $sql = " select max({$campo})+1 as {$campo} from {$this->_schema}.{$this->_name} ";
        if( $where ){
            $sql .= " where {$where} ";
        }
        $res = $this->getDefaultAdapter()->fetchAll( $sql );
        if( isset($res[0][$campo]) ){
            return $res[0][$campo];
        } else {
            return 1;
        }
    }

    /**
     * Deletes existing rows.
     *
     * @param  array|string $where SQL WHERE clause(s).
     * @return int          The number of rows deleted.
     */
	public function delete($where)
	{
		if( parent::delete($where) ) {
			$controller = ( !is_null($this->controller) ) ? $this->controller : "Não passou por controller";


			if($controller != 'base-conhecimento'){
				$objLog		= new Base_Log();

                if(is_array($where)){
                    $strWhere = '';
                    foreach($where as $key=>$value){
                        if(strpos($key, '?')){
                            $value = str_replace('?', $value, $key).' ';
                        }
                        $strWhere .= $value;
                    }
                    $whereLog = $strWhere;
                }else{
                    $whereLog = $where;
                }
				$objLog->escreveLog( 10, $controller, $this->_name, $this->retiraQuebraLinha($whereLog) );
			}
			return 1;
		}else{
			return 0;
		}
	}

    /**
     * Updates existing rows.
     *
     * @param  array        $data  Column-value pairs.
     * @param  array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses.
     * @return int          The number of rows updated.
     */
	public function update(array $data, $where)
	{
		if(is_array($where)){
			$strWhere = '';
			foreach($where as $key=>$value){
                if(strpos($key, '?')){
                    $value = str_replace('?', $value, $key).' ';
                }
				$strWhere .= $value;
			}
			$whereLog = $strWhere;
		}else{
			$whereLog = $where;
		}

		//acrescenta o cd_profissional logado no campo id da tabela
		$data['id'] = $this->cd_profissional;

		if( parent::update( $data, $where) ) {

			$controller = ( !is_null($this->controller) ) ? $this->controller : "Não passou por controller";

			if($controller != 'base-conhecimento'){
				$objLog		= new Base_Log();
				if( ($controller != 'auth') && ($controller != 'alterar-senha') ){
					$objLog->escreveLog( 9, $controller, $this->_name, $this->retiraQuebraLinha($whereLog) );
				}else if($controller == 'auth') {
					$objLog->escreveLog( Zend_Log::INFO, $controller, "Sem tabela", "Acesso ao sistema" );
				}else if($controller == 'alterar-senha') {
					$arrWhere = explode("=", $where);
					$objLog->escreveLog( Zend_Log::ALERT, $controller, $this->_name, "Alteração de senha de acesso", trim($arrWhere[1]) );
				}
			}
			return 1;
		}else{
			return 0;
		}
	}

    public function insert(array $data)
    {
		//acrescenta o cd_profissional logado no campo id da tabela
		$data['id'] = $this->cd_profissional;
		$retorno = parent::insert($data);
		if( $retorno ){

			if(is_array($retorno)){
				$where = '';
				foreach($retorno as $pk=>$value){
					$where .= $pk." = ".$value." ";
				}
				$whereLog = $where;
			}else{
				$whereLog = $this->_primary[1]." = ".$retorno;
			}
			$controller = ( !is_null($this->controller) ) ? $this->controller : "N&atilde;o passou por controller";
			if($controller != 'base-conhecimento' && $controller != 'fale-conosco'){
				$objLog		= new Base_Log();
				$objLog->escreveLog( 8, $controller, $this->_name, $this->retiraQuebraLinha($whereLog) );
			}
		}
		return $retorno;
	}

	private function retiraQuebraLinha($string)
	{
		$string = str_replace("
		"," ",$string);
		
		return $string;
	}

    protected function _mountWhere(Array $arrParam, Zend_Db_Table_Select &$select)
    {
        if(count($arrParam) > 0) {
            foreach($arrParam as $cond=>$value) {
                if(!is_null($value)){
                    $select->where($cond, $value);
                }
            }
        }
    }

	/**
     * Converte a função TO_CHAR() do PostgreSQL para a função
	 * correspondente à do Adapter indicado (Oracle, MySQL, SQL Server)
     *
     * @param String $string
     * @param String $format  
     * @return String
     *
     */
	public function to_char($string, $format){

		$arrFormat      = array('MM/YYYY', 'YYYY-MM-DD HH24:MI:SS','DD/MM/YYYY','DD/MM/YYYY HH24:MI:SS', 'MM', 'YYYY');
		$arrFormatMySQL = array('%m/%Y', '%Y-%m-%d %H:%i:%s','%d/%m/%Y','%d/%m/%Y %H:%i:%s', '%m', '%Y');
		$arrFormatMsSQL = array("right('00'+cast(month(".$string.") as varchar),2) +'/'+ cast(year(".$string.")as varchar)",
								'convert(varchar(25),'.$string.',120)',
								'convert(varchar(10),'.$string.',103)',
								"convert(varchar(10),".$string.",103) + ' ' + convert(varchar(5),".$string.",108)",
								'month('.$string.')',
								'year('.$string.')');

		$indice         = array_search($format, $arrFormat);
		
		switch (get_class($this->getAdapter())) {
			case 'Zend_Db_Adapter_Pdo_Pgsql':
				$str = "to_char({$string}, '{$format}')";
				break;
			case 'Zend_Db_Adapter_Pdo_Oracle':
				$str = "to_char({$string}, '{$format}')";
				break;
			case 'Zend_Db_Adapter_Pdo_Mysql':
				$str = "date_format({$string}, '{$arrFormatMySQL[$indice]}')";
				break;
			case 'Zend_Db_Adapter_Sqlsrv':
				$str = $arrFormatMsSQL[$indice];
				break;

			default:
				break;
		}
		return $str;
	}
	
	/**
     * Converte a função TO_DATE() do PostgreSQL para a função
	 * correspondente à do Adapter indicado (Oracle, MySQL, SQL Server)
     *
     * @param String $string
     * @param String $format
     * @return String
     *
     */
	public function to_date($string, $format){

		$arrFormat      = array('YYYY-MM-DD', 'DD/MM/YYYY', 'YYYYMMDD');
		$arrFormatMySQL = array('%Y-%m-%d','%d/%m/%Y','%Y%m%d');
		$arrFormatMsSQL = array('convert(datetime,'.$string.',120)',
								'convert(datetime,'.$string.',103)',
								'convert(datetime,'.$string.',112)');

		$indice         = array_search($format, $arrFormat);

		switch (get_class($this->getAdapter())) {
			case 'Zend_Db_Adapter_Pdo_Pgsql':
				$str = "to_date({$string}, '{$format}')";
				break;
			case 'Zend_Db_Adapter_Pdo_Oracle':
				$str = "to_date({$string}, '{$format}')";
				break;
			case 'Zend_Db_Adapter_Pdo_Mysql':
				$str = "str_to_date({$string}, '{$arrFormatMySQL[$indice]}')";
				break;
			case 'Zend_Db_Adapter_Sqlsrv':
				$str = $arrFormatMsSQL[$indice];
				break;
			default:
				break;
		}
		return $str;
	}

	/**
     * Converte a função TO_CHAR() do PostgreSQL para a função
	 * correspondente à do Adapter indicado (Oracle, MySQL, SQL Server)
     *
     * @param String $string
     * @param String $format
     * @return String
     *
     */
	public function to_timestamp($string, $format){

		$arrFormat      = array('YYYY-MM-DD HH24:MI:SS', 'DD/MM/YYYY HH24:MI:SS');
		$arrFormatMySQL = array('%Y-%m-%d %H:%i:%s', '%d/%m/%Y %H:%i:%s');
		$arrFormatMsSQL = array('convert(datetime, '.$string.',120)',
								'convert(datetime, '.$string.',103)');

		$indice         = array_search($format, $arrFormat);

		switch (get_class($this->getAdapter())) {
			case 'Zend_Db_Adapter_Pdo_Pgsql':
			case 'Zend_Db_Adapter_Pdo_Oracle':
				$str = "to_timestamp({$string}, '{$format}')";
				break;
			case 'Zend_Db_Adapter_Pdo_Mysql':
				$str = "str_to_date({$string}, '{$arrFormatMySQL[$indice]}')";
				break;
			case 'Zend_Db_Adapter_Sqlsrv':
				$str = $arrFormatMsSQL[$indice];
				break;

			default:
				break;
		}
		return $str;
	}

	/**
     * Converte o operador de concatenação || do PostgreSQL para o operador ou função (MySQL)
	 * correspondente à do Adapter indicado (Oracle, MySQL, SQL Server)
     *
     * @param String $string1
     * @param String $string2
     * @return String
     *
     */
	public function concat(){

		switch (get_class($this->getAdapter())) {
			case 'Zend_Db_Adapter_Pdo_Pgsql':
				$str = "||";
				break;
			case 'Zend_Db_Adapter_Pdo_Oracle':
				$str = "||";
				break;
			case 'Zend_Db_Adapter_Pdo_Mysql':
				$str = "||";
				break;
			case 'Zend_Db_Adapter_Sqlsrv':
				$str = "+";
				break;
			default:
				break;
		}
		return $str;
	}

	/**
     * Converte a função POSITION() do PostgreSQL para a função
	 * correspondente à do Adapter indicado (Oracle, MySQL, SQL Server)
     *
     * @param String $string_search String procurada
     * @param String $string String na qual se procurará a string
     * @return String
     *
     */
	public function position($string_search, $string){

		switch (get_class($this->getAdapter())) {
			case 'Zend_Db_Adapter_Pdo_Pgsql':
				$str = "position('{$string_search}' in {$string})";
				break;
			case 'Zend_Db_Adapter_Pdo_Oracle':
				$str = "instr({$string}, '{$string_search}')";
				break;
			case 'Zend_Db_Adapter_Pdo_Mysql':
				$str = "instr({$string}, '{$string_search}')";
				break;
			case 'Zend_Db_Adapter_Sqlsrv':
				$str = "charindex('{$string_search}', {$string})";
				break;
			default:
				break;
		}
		return $str;
	}

	/**
     * Converte a função SUBSTRING() do PostgreSQL para a função
	 * correspondente à do Adapter indicado (Oracle, MySQL, SQL Server)
     *
     * @param String $string String analisada
     * @param String $begin posição inicial da análise
     * @param String $positions quantidade de caracteres analisados
     * @return String
     *
     */
	public function substring($string, $begin, $positions){

		switch (get_class($this->getAdapter())) {
			case 'Zend_Db_Adapter_Pdo_Pgsql':
				$str = "substring({$string},{$begin},{$positions})";
				break;
			case 'Zend_Db_Adapter_Pdo_Oracle':
				$str = "substr({$string},{$begin},{$positions})";
				break;
			case 'Zend_Db_Adapter_Pdo_Mysql':
				$str = "substr({$string},{$begin},{$positions})";
				break;
			case 'Zend_Db_Adapter_Sqlsrv':
				$str = "substring({$string},{$begin},{$positions})";
				break;
			default:
				break;
		}
		return $str;
	}

	/**
     * Converte a função LENGTH() do PostgreSQL para a função
	 * correspondente à do Adapter indicado (Oracle, MySQL, SQL Server)
     *
     * @param String $string String analisada
     * @return String
     *
     */
	public function length($string){

		switch (get_class($this->getAdapter())) {
			case 'Zend_Db_Adapter_Pdo_Pgsql':
				$str = "length({$string})";
				break;
			case 'Zend_Db_Adapter_Pdo_Oracle':
				$str = "length({$string})";
				break;
			case 'Zend_Db_Adapter_Pdo_Mysql':
				$str = "length({$string})";
				break;
			case 'Zend_Db_Adapter_Sqlsrv':
				$str = "len({$string})";
				break;
			default:
				break;
		}
		return $str;
	}

	/**
     * Converte a expressão de ordenação do número do contrato
	 * substring(tx_numero_contrato,position('/' in tx_numero_contrato)+1,4) desc,
	   substring(tx_numero_contrato,1,position('/' in tx_numero_contrato)-1) desc
	 * do PostgreSQL para a expressão
	 * correspondente à do Adapter indicado (Oracle, MySQL, SQL Server)
     *
     * @return String
     *
     */
	public function substringOrderNumeroContrato(){

		switch (get_class($this->getAdapter())) {
			case 'Zend_Db_Adapter_Pdo_Pgsql':
				$str = "substring(tx_numero_contrato,position('/' in tx_numero_contrato)+1,4) DESC,
						substring(tx_numero_contrato,1,position('/' in tx_numero_contrato)-1) DESC";
				break;
			case 'Zend_Db_Adapter_Pdo_Oracle':
				$str = "substr(tx_numero_contrato, instr(tx_numero_contrato, '/')+1, 4) DESC,
						substr(tx_numero_contrato, 1, instr(tx_numero_contrato, '/')-1) DESC";
				break;
			case 'Zend_Db_Adapter_Pdo_Mysql':
				$str = "substr(tx_numero_contrato, instr(tx_numero_contrato, '/')+1, 4) DESC,
						substr(tx_numero_contrato, 1, instr(tx_numero_contrato, '/')-1) DESC";
				break;
			case 'Zend_Db_Adapter_Sqlsrv':
				$str = "substring(tx_numero_contrato, charindex('/', tx_numero_contrato)+1, 4) DESC,
						substring(tx_numero_contrato, 1, charindex('/', tx_numero_contrato)-1) DESC";
				break;
			default:
				break;
		}
		return $str;
	}
}