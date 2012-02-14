<?php
class Base_Log_Writer_Stream extends Zend_Log_Writer_Stream {

	/**
	 * Holds the PHP stream to log to.
	 * @var null|stream
	 */
	protected $_stream			= null;
	protected $fileMaxSize      = K_SIZE_FILE_LOG;
	protected $maxFiles         = K_MAX_FILE_LOG;
	protected $_filename        = null;

	private $arrCabecalho       = array();
	private $separadorTitulo	= "|";
	private $tamSeparadorTitulo = "%-6s";


	/**
	 * Class Constructor
	 *
	 * @param  streamOrUrl     Stream or URL to open as a stream
	 * @param  mode            Mode, only applicable if a URL is given
	 */
	public function __construct($streamOrUrl, $mode = 'a', $logError=false) {
		if (is_resource ( $streamOrUrl )) {
			$stream_data = stream_get_meta_data ( $streamOrUrl );
			if (get_resource_type ( $streamOrUrl ) != 'plainfile') {
				throw new Zend_Log_Exception ( 'Resource is not a plainfile' );
			}
			if ($mode != 'a') {
				throw new Zend_Log_Exception ( 'Mode cannot be changed on existing streams' );
			}
			$filename = $stream_data ['uri'];
			$this->_stream = $streamOrUrl;
		} else {
			//incluindo os dados para o titulo do log
			$this->setArrCabecalho($logError);
			$this->setCabecalho($streamOrUrl, $mode);
			
			if (! $this->_stream = @fopen ( $streamOrUrl, $mode, false )) {
				$msg = "\"$streamOrUrl\" cannot be opened with mode \"$mode\"";
				throw new Zend_Log_Exception ( $msg );
			}
			$filename = $streamOrUrl;
		}
		$this->_fileName = $filename;
		fseek ( $this->_stream, filesize ( $this->_fileName ) - 1 );
	}

	private function setArrCabecalho($logErro=false) {
		if($logErro === false){
			$this->arrCabecalho['Profissional'] = "%-15s";
			$this->arrCabecalho['Data'		  ] = "%-25s";
			$this->arrCabecalho['Prioridade'  ] = "%-15s";
			$this->arrCabecalho['Tabela'	  ] = "%-50s";
			$this->arrCabecalho['Controller'  ] = "%-50s";
			$this->arrCabecalho['IP'		  ] = "%-51s";
			$this->arrCabecalho['Host'		  ] = "%-51s";
			$this->arrCabecalho['Mensagem'	  ] = "%-10s";
		}else{
			$this->arrCabecalho['Profissional'] = "%-15s";
			$this->arrCabecalho['Data'		  ] = "%-25s";
			$this->arrCabecalho['Linha'		  ] = "%-10s";
			$this->arrCabecalho['Função'	  ] = "%-40s";
			$this->arrCabecalho['Classe'	  ] = "%-40s";
			$this->arrCabecalho['URL'		  ] = "%-80s";
			$this->arrCabecalho['Arquivo'	  ] = "%-80s";
			$this->arrCabecalho['Mensagem'	  ] = "%-50s";
		}
	}

	/**
	 * @return int
	 */
	public function getFileMaxSize() {
		return $this->fileMaxSize;
	}

	/**
	 * Set the maximum size that the output file is allowed to reach
	 * before being rolled over to backup files.
	 *
	 * The maxFileSize option takes an long integer in the range 0 - 2^63.
	 * You can specify the value with the suffixes "KB", "MB" or "GB"
	 * so that the integer is interpreted being expressed respectively
	 * in kilobytes, megabytes or gigabytes. For example, the value "10KB"
	 * will be interpreted as 10240.
	 *
	 * @param int|string $fileMaxSize
	 */
	public function setFileMaxSize($fileMaxSize) {
		if (is_string($fileMaxSize)) {
			$this->fileMaxSize = $this->_convertMaxFileSize($fileMaxSize);
		} else {
			$this->fileMaxSize = $fileMaxSize;
		}
	}

	/**
	 * @return int
	 */
	public function getMaxFiles() {
		return $this->maxFiles;
	}

	/**
	 * @param int $maxFiles
	 */
	public function setMaxFiles($maxFiles) {
		$this->maxFiles = $maxFiles;
	}

	/**
	 * Close the stream resource.
	 *
	 * @return void
	 */
	public function shutdown() {
		if (is_resource ( $this->_stream )) {
			fclose ( $this->_stream );
		}
	}

	/**
	 * Write a message to the log.
	 *
	 * @param  array  $event  event data
	 * @return void
	 */
	protected function _write($event) {
		$line = $this->_formatter->format ( $event );

		if (false === @fwrite( $this->_stream, $line )) {
			throw new Zend_Log_Exception ( "Unable to write to stream" );
		}

		if (ftell( $this->_stream ) > $this->fileMaxSize)
			$this->_rollOver();
	}

	protected function _rollOver() {

		if ($this->maxFiles > 0) {
			$file = $this->_fileName . '.' . $this->maxFiles;

			//verifica se possui o maximo de arquivo para se apagado
			if (is_writable( $file ))unlink( $file );

			for($i = $this->maxFiles - 1; $i >= 1; $i --) {
				$file = $this->_fileName . '.' . $i;
				if (is_readable ( $file )) {
					$target = $this->_fileName . '.' . ($i + 1);
					rename ( $file, $target );
				}
			}
			$target = $this->_fileName . '.1';
			$this->shutdown ();
			rename( $this->_fileName, $target );
		}
		$this->__construct( $this->_fileName, 'a' );
	}

	/**
	 * Convert maxFileSize value
	 *
	 * @param mixed $value
	 */
	private function _convertMaxFileSize($value) {
		$maxFileSize = null;
		$numpart = substr ( $value, 0, strlen ( $value ) - 2 );
		$suffix = strtoupper ( substr ( $value, - 2 ) );

		switch ($suffix) {
			case 'KB' :
				$maxFileSize = ( int ) (( int ) $numpart * 1024);
				break;
			case 'MB' :
				$maxFileSize = ( int ) (( int ) $numpart * 1024 * 1024);
				break;
			case 'GB' :
				$maxFileSize = ( int ) (( int ) $numpart * 1024 * 1024 * 1024);
				break;
			default :
				throw new Zend_Log_Exception ( 'Tamanho inválido.' );
		}
		return $maxFileSize;
	}

	/**
	 * Seta o cabeçalho do arquivo de log gerado
	 *
	 * @param string $pathArquivo
	 * @param string $mode M
	 */
	private function setCabecalho( $pathArquivo, $mode = 'a' )
	{
		$strTitulo = '';

		if( !file_exists( $pathArquivo ) ) {

			$qtdItemCabecalho = count($this->arrCabecalho) -1 ;
			$count = 0;
			foreach( $this->arrCabecalho as $titulo=>$tamanho ) {
				if($count < $qtdItemCabecalho){
					$strTitulo .= sprintf( $tamanho, $titulo);
					$strTitulo .= sprintf( $this->tamSeparadorTitulo, $this->separadorTitulo);
				}else{
					$strTitulo .= sprintf( $tamanho, $titulo);
				}
				$count++;
			}
			$strTitulo .= PHP_EOL;
            $sizeTitulo = strlen($strTitulo);
            for ($i=0; $i<=$sizeTitulo; $i++){
                $strTitulo .= '-';
            }
			$strTitulo .= PHP_EOL;
			$arq = fopen ( $pathArquivo, $mode, false );
			fwrite( $arq, $strTitulo );
			fclose ( $arq );
		}
	}
}