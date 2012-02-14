<?php
class Base_Log extends Zend_Log
{
	private $_tx_controller;
	private $_cd_profissional;
	private $_tx_tabela;
	private $_tx_mensagem;
	private $_tx_prioridade;
	private $_barra = DIRECTORY_SEPARATOR;
	private $_path;

	public function __construct( Zend_Log_Writer_Abstract $writer = null )
	{
		parent::__construct($writer);
		$this->_path = SYSTEM_PATH_ABSOLUTE.
						$this->_barra ."public".
						$this->_barra ."documentacao".
						$this->_barra ."log".$this->_barra;
	}

	/**
	 * Método que gera os Logs
	 *
	 * @param <int>	   $prioridade		Número da prioridade do registro de log
	 * @param <string> $controller		Nome do controller acessado
	 * @param <string> $tabela			Nome da tabela na qual o registro sofreu alteração
	 * @param <string> $msg				Mensagem a ser impressa no geristro de log
	 */
	public function escreveLog( $prioridade, $controller, $tabela, $msg, $cdProfissionalSemSessao = null )
	{
		$this->_tx_prioridade	= $prioridade;
		$this->_tx_controller	= $controller;
		$this->_tx_tabela		= $tabela;
		$this->_tx_mensagem     = $msg;

		if( !is_null($cdProfissionalSemSessao) ){
			$this->_cd_profissional = $cdProfissionalSemSessao;
		}else{
            if(!isset($_SESSION['oasis_logged']) && !isset($_SESSION['oasis_pedido'])){
                $this->_cd_profissional = null;
            }elseif(!isset($_SESSION['oasis_logged']) && isset($_SESSION['oasis_pedido'])){
                $this->_cd_profissional = null;
            }else{
                $this->_cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];
            }
		}

		if($this->_tx_controller === 'error'){
			$this->_pastaLog = "logError";
		}
		
		$this->logBanco();
		$this->logTexto();
		$this->setEvents();
		$this->setPriority();
		$this->setMsg();
		$this->__destruct();
	}

	public function escreveLogError( $objException, $msg)
	{

		$arrFormat['cd_profissional'] = "%-15s";
		$arrFormat['timestamp'      ] = "%-25s";
		$arrFormat['line'           ] = "%-10s";
		$arrFormat['function'       ] = "%-38s";
		$arrFormat['class'          ] = "%-40s";
		$arrFormat['url'            ] = "%-80s";
		$arrFormat['file'           ] = "%-80s";
		$arrFormat['message'        ] = "%-50s";

		$pathLogError = $this->_path."logError".$this->_barra;
		$this->criaDiretorio($pathLogError);

		//Concatenando o diretorio de pastas para o arquivo do Log
		$pathLogError = $pathLogError.date('Y').$this->_barra.date('m').$this->_barra.date('Y_m').".log";

		//cria o arquivo de log
		$writerTxt = new Base_Log_Writer_Stream($pathLogError, 'a', true);

		//formata o conteúdo do aquivo de log
		$formatter = new Base_Log_Formatter_Simple($arrFormat);
		$writerTxt->setFormatter($formatter);

		$this->addWriter($writerTxt);

        $cd_profissional = null;
        if(array_key_exists('oasis_logged', $_SESSION)){
            $cd_profissional = $_SESSION['oasis_logged'][0]['cd_profissional'];
        }

		//seta os parâmetros que serão escritos no log
		$this->setEventItem('cd_profissional', $cd_profissional);
		$this->setEventItem('timestamp'      , date ('Y-m-d H:i:s', time()));
		$this->setEventItem('file'           , trim($objException[4]['file']));
		$this->setEventItem('line'           , trim($objException[4]['line']));
		$this->setEventItem('url'            , trim($_SERVER['REQUEST_URI']));
		$this->setEventItem('function'       , trim($objException[4]['function']));
		$this->setEventItem('class'          , trim($objException[4]['class']));

		$this->log($msg, Zend_Log::ERR);
		$this->__destruct();
	}

	/**
	 * Seta as prioridade
	 */
	private function setPriority()
	{
		//Adicionando as prioridades.
		$this->addPriority('salvar',8);
		$this->addPriority('alterar',9);
		$this->addPriority('excluir',10);
	}

	/**
	 * Escreve as mensagens de log
	 */
	private function setMsg()
	{
		switch($this->_tx_prioridade){
			case 8:
				$this->salvar(Base_Util::getTranslator('L_VIEW_INCLUSAO_REGISTRO').': '.$this->_tx_mensagem);
				break;
			case 9:
				$this->alterar(Base_Util::getTranslator('L_VIEW_ALTERACAO_REGISTRO').': '.$this->_tx_mensagem);
				break;
			case 10:
				$this->excluir(Base_Util::getTranslator('L_VIEW_EXCLUSAO_REGISTRO').': '.$this->_tx_mensagem);
				break;
			default:
				$this->log($this->_tx_mensagem, $this->_tx_prioridade);
		}
	}

	/**
	 * Seta os Eventos a serem registrados no log
	 */
	private function setEvents()
	{
		$this->setEventItem('cd_profissional'	, $this->_cd_profissional);
		$this->setEventItem('table_name'		, $this->_tx_tabela );
		$this->setEventItem('controller_name'	, $this->_tx_controller);
		$this->setEventItem('timestamp'			, date ('Y-m-d H:i:s', time()));
		$this->setEventItem('remote_addr'		, $this->getRealIpAddr());
		$this->setEventItem('remote_host'		, gethostbyaddr($_SERVER['REMOTE_ADDR']));
	}

	/**
	 * Recupera o nurero do IP de quem gerou o registro de log
	 *
	 * @return <string> Numero do IP do acesso
	 */
	private function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];  
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   //to check ip is pass from proxy 
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		}else{  
			$ip = $_SERVER['REMOTE_ADDR']; 
		}
		return $ip;
	}

	/**
	 * Geristro o log no banco de dados
	 */
	private function logBanco( )
	{
		$columnMapping = array( 'cd_log'		 =>'cd_log',
								'cd_profissional'=>'cd_profissional',
		 						'dt_ocorrencia'	 =>'timestamp',
		 						'tx_msg_log'	 =>'message', 
		 						'ni_prioridade'	 =>'priority',
 								'tx_tabela'		 =>'table_name',
 								'tx_controller'	 =>'controller_name',
 								'tx_ip'	 		 =>'remote_addr',
 								'tx_host' 		 =>'remote_host');
		
		$tableName = KT_S_LOG;
        if(trim(K_SCHEMA) !== ""){
           $tableName = K_SCHEMA . "." . $tableName;
        }
        $writerDb = new Zend_Log_Writer_Db(Zend_Registry::get('db'), $tableName, $columnMapping);
		$this->addWriter($writerDb);
		
		//recupera o código para o registro de log
		$obj    = new Log();
		$cd_log = $obj->getNextValueOfField('cd_log');
		$this->setEventItem('cd_log', $cd_log);
	}

	/**
	 * Registra o log em arquivo de texto
	 */
	private function logTexto()
	{
		$pathLogEvento = $this->_path."logEvento".$this->_barra;

		$this->criaDiretorio($pathLogEvento);

		//Concatenando o diretorio de pastas para o arquivo do Log
		$pathLogEvento = $pathLogEvento.date('Y').$this->_barra.date('m').$this->_barra.date('Y_m').".log";

		//cria o arquivo de log
		$writerTxt = new Base_Log_Writer_Stream($pathLogEvento);

		//formata o conteúdo do aquivo de log
		$formatter = new Base_Log_Formatter_Simple();
		$writerTxt->setFormatter($formatter);

		$this->addWriter($writerTxt);
	}

	private function criaDiretorio($pathLog)
	{
		chdir($pathLog);
		if(isset($_SERVER['WINDIR'])){
			$pathLog = str_replace("/","\\",$pathLog);
		}

		if(!file_exists($pathLog.date('Y'))){
			mkdir(date('Y'));
		}

		chdir($pathLog.date('Y').$this->_barra);
		if(!file_exists($pathLog.date('Y').$this->_barra.date('m'))){
			mkdir(date('m'));
		}
		chdir(SYSTEM_PATH_ABSOLUTE);
	}
}