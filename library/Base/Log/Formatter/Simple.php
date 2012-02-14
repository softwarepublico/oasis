<?php
class Base_Log_Formatter_Simple implements Zend_Log_Formatter_Interface
{

    /**
     * @var Array
     */
	private $arrFormat = array();

    /**
     * Contrutor da Classe
     *
     * @param  array  $arrFormat  Format specifier for log messages
     * @throws Zend_Log_Exception
     */
    public function __construct(Array $arrFormat = array())
    {
        if (!is_array($arrFormat)){
            throw new Zend_Log_Exception('O formato tem que ser um Array()');
        }
		if(count($arrFormat) == 0){
			$this->setArrFormat();
		} else {
			$this->arrFormat = $arrFormat;
		}
    }

	/**
	 * Método para setar o array de formatação do conteúdo do Log
	 */
	private  function setArrFormat()
	{
		$this->arrFormat['cd_profissional'] = "%-15s";
		$this->arrFormat['timestamp'      ] = "%-25s";
		$this->arrFormat['priority'		  ] = "%-15s";
		$this->arrFormat['table_name'	  ] = "%-50s";
		$this->arrFormat['controller_name'] = "%-50s";
		$this->arrFormat['remote_addr'	  ] = "%-51s";
		$this->arrFormat['remote_host'	  ] = "%-51s";
		$this->arrFormat['message'		  ] = "%-10s";
	}

    /**
     * Formata a string a ser impressa no log
     *
     * @param  array    $event    dados dos events
     * @return string
     */
    public function format($event)
    {
		$output = "";

		$qtdItemFormat = count($this->arrFormat) -1 ;
		$count = 0;
		foreach($this->arrFormat as $formatEvent=>$format){
			foreach ($event as $name=>$value) {
				if($formatEvent == $name){
					if($count < $qtdItemFormat){
						$output .= sprintf( $format, $value);
						$output .= sprintf( "%-6s", "|");
					}else{
						$output .= sprintf( $format, $value);
					}
					break;
				}
			}
			$count++;
		}
		return $output.PHP_EOL;
    }

}
