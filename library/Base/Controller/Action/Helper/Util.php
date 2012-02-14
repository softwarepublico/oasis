<?php
class Base_Controller_Action_Helper_Util extends Zend_Controller_Action_Helper_Abstract
{

	public function validaValor($valor)
	{
		if(trim($valor)){
			$valor = str_ireplace(".","",$valor);
			$valor = str_ireplace(",",".",$valor);
		} else {
			$valor = null;
		}
		return $valor; 
	}
	
	/**
	 * Método que retona a descrição do número do mês
	 *
	 * @param int $codMesDesc
	 * @return desc_mes or arrMes
	 */	
	public function getMes($codMesDesc = null)
	{
		$arrMeses     = array();
		$arrMeses[0]  = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE_MES');
		$arrMeses[1]  = Base_Util::getTranslator('L_VIEW_COMBO_JANEIRO');
		$arrMeses[2]  = Base_Util::getTranslator('L_VIEW_COMBO_FEVEREIRO');
		$arrMeses[3]  = Base_Util::getTranslator('L_VIEW_COMBO_MARCO');
		$arrMeses[4]  = Base_Util::getTranslator('L_VIEW_COMBO_ABRIL');
		$arrMeses[5]  = Base_Util::getTranslator('L_VIEW_COMBO_MAIO');
		$arrMeses[6]  = Base_Util::getTranslator('L_VIEW_COMBO_JUNHO');
		$arrMeses[7]  = Base_Util::getTranslator('L_VIEW_COMBO_JULHO');
		$arrMeses[8]  = Base_Util::getTranslator('L_VIEW_COMBO_AGOSTO');
		$arrMeses[9]  = Base_Util::getTranslator('L_VIEW_COMBO_SETEMBRO');
		$arrMeses[10] = Base_Util::getTranslator('L_VIEW_COMBO_OUTUBRO');
		$arrMeses[11] = Base_Util::getTranslator('L_VIEW_COMBO_NOVEMBRO');
		$arrMeses[12] = Base_Util::getTranslator('L_VIEW_COMBO_DEZEMBRO');
		
		if(!is_null($codMesDesc)){
			return $arrMeses[$codMesDesc];
		} else {
			return $arrMeses;
		}
	}
	
	/**
	 * Método que retona a descrição do número do mês
	 *
	 * @param int $codMesDesc
	 * @return desc_mes or arrMes
	 */	
	public function getMesRes($codMesDesc = null)
	{
		$arrMeses     = array();
		$arrMeses[0]  = Base_Util::getTranslator('L_VIEW_COMBO_MES');
		$arrMeses[1]  = Base_Util::getTranslator('L_VIEW_COMBO_JAN');
		$arrMeses[2]  = Base_Util::getTranslator('L_VIEW_COMBO_FEV');
		$arrMeses[3]  = Base_Util::getTranslator('L_VIEW_COMBO_MAR');
		$arrMeses[4]  = Base_Util::getTranslator('L_VIEW_COMBO_ABR');
		$arrMeses[5]  = Base_Util::getTranslator('L_VIEW_COMBO_MAI');
		$arrMeses[6]  = Base_Util::getTranslator('L_VIEW_COMBO_JUN');
		$arrMeses[7]  = Base_Util::getTranslator('L_VIEW_COMBO_JUL');
		$arrMeses[8]  = Base_Util::getTranslator('L_VIEW_COMBO_AGO');
		$arrMeses[9]  = Base_Util::getTranslator('L_VIEW_COMBO_SET');
		$arrMeses[10] = Base_Util::getTranslator('L_VIEW_COMBO_OUT');
		$arrMeses[11] = Base_Util::getTranslator('L_VIEW_COMBO_NOV');
		$arrMeses[12] = Base_Util::getTranslator('L_VIEW_COMBO_DEZ');
		
		if(!is_null($codMesDesc)){
			return $arrMeses[$codMesDesc];
		} else {
			return $arrMeses;
		}
	}
	
	public function getPrioridade()
	{
		$arrPrioridade = array();
		$arrPrioridade[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		$arrPrioridade['A'] = Base_Util::getTranslator('L_VIEW_COMBO_ALTISSIMA');
		$arrPrioridade['L'] = Base_Util::getTranslator('L_VIEW_COMBO_ALTA');
		$arrPrioridade['M'] = Base_Util::getTranslator('L_VIEW_COMBO_MEDIA');
		$arrPrioridade['B'] = Base_Util::getTranslator('L_VIEW_COMBO_BAIXA');
		
		return $arrPrioridade;
	}

	public function comboGroup($arrComboGroup)
	{
		if(count($arrComboGroup) > 0){
			$strOption  = "<option selected=\"selected\" label=\"".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."\" value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
			$title      = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
			foreach($arrComboGroup as $group){
				if(array_key_exists('title', $group)){
					$title = $value['title'];
				}
				$strOption .= "<optgroup label=\"{$title}\">";
				foreach($group as $key=>$value){
					$strOption .= "<option label=\"{$value}\" value=\"{$key}\">{$value}</option>";
				}
			}
			$strOption .= "</optgroup>";
		}
		return $strOption;
	}

    /**
     * Método que realiza um filtro nos dados informados para autenticação
     * e caso possua alguma coisa errada a pagina e redirecionada para
     * realização dos loguins novamente
     * @param Array $formData
     * @return Array $formData
     */
    public static function validateInformation($formData, $param1, $param2)
    {
        //Realiza um filtro nos dados recebidos do post
        $filter              = new Zend_Filter_StripTags();
        $formData[$param1] = $filter->filter($formData[$param1]);
        if (strpos($formData[$param1], "@") === false) {
            $formData[$param1] = $formData[$param1] . K_DOMINIO_EMAIL_PADRAO;
        }
        if(Zend_Validate::is($formData[$param1],'EmailAddress')){
            $formData[$param2] = $filter->filter($formData[$param2]);
            return $formData;
        } else {
            $formData = array();
            return $formData;
        }
    }

    /**
     * Método que cria a pesquisa das informações no banco de dados para
     * recuperar os dados do usuário caso exista.
     *
     * Método utilizado pelos controller Auth e PedidoSolicitacao
     * 
     * @param Array $formData
     * @return object Zend_Auth_Adapter_DbTable
     */
    public static function getAuthAdapter($formData, $tableName, $identityColumn, $credentialColumn, $treatment = "MD5")
    {
        //Obtém o adaptador de banco de dados a partir do registro
        $dbAdapter = Zend_Registry::get('db');

        //Configura informações específicas do banco de dados
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter->setTableName($tableName)
                    ->setIdentityColumn($identityColumn)
                    ->setCredentialColumn($credentialColumn)
                    ->setCredentialTreatment("{$treatment}(?)");
                    
        //Aumenta a segurança da senha adicionado texto salt
        $config = Zend_Registry::get('banco');
//        $salt = $config->auth->salt;
//        $password = $salt.$formData['senha'];
        $password = $formData['senha'];
        //Configura dados para autenticação
        $authAdapter->setIdentity($formData['usuario']);
        $authAdapter->setCredential($password);

        return $authAdapter;
    }
    
    /**
     * Recupera os dados do profissional no oasis atraves do email institucional
     * obtido no ldap
     * 
     * @param stdClass $accountObject
     * @param string $tableClassName
     * @param string $tableColumnToFind
     * @param mixed $tableColumnsOmit
     * @return array 
     */
    public function getUserAuthLdap( stdClass $accountObject, $tableClassName='Profissional',$tableColumnToFind='tx_email_institucional', $tableColumnsOmit='tx_senha' )
    {
        $return = array(
            'error'=>false,
            'error_type'=>null,
            'row'=>null
        );
        
        $mail = mb_strtolower($accountObject->mail);
        
        $dominioMailLdap = trim(substr($mail, strpos( $mail, '@' ))); 
        
        if( K_DOMINIO_EMAIL_PADRAO !== $dominioMailLdap){
            $return['error'    ] = true;
            $return['error_msg'] = Base_Util::getTranslator('L_MSG_ALERT_DOMINIO_EMAIL_LDAP_NAO_CONFERE');
        }else{
            $table = new $tableClassName();
            $where = array(
                $tableColumnToFind.' = ?' => $mail
            );
            $rowset = $table->fetchAll( $where );
            if($rowset->valid()){
                $arrUser = $rowset->current()->toArray();
                
                $arrColumnsOmit = (array) $tableColumnsOmit;
                foreach( $arrColumnsOmit as $key=>$value){
                    unset($arrUser[$value]);
                }
                $return['row'] = (object) $arrUser;
            }else{
                $return['error'     ] = true;
                $return['error_type'] = 1;
                $return['error_msg' ] = Base_Util::getTranslator('L_MSG_ALERT_USUARIO_LDAP_NAO_CADASTRO');
            }
        }
        return $return;
    }
    
    

    /**
     * Método que calcula a porcentagem de tempo de resposta
     * da demanda, compara com a tabela b_status_atendimento
     * e retorna a descrição do tempo de resposta e a cor para
     * utilização na bolinha que indica o tempo restante para
     * resposta da demanda.
     * 
     * Método utilizado pelos controller SolicitacaoTipoDemanda
     *
     * @param Array $arrTempoResposta array com os dados da tabela b_status_atendimento
     * @param int   $maxPrazo máximo prazo de execução obtido dos níveis de serviço designados
     * @param date  $hi data de leitura da solicitação
     * @return Array $arr
     */
    public static function getTempoResposta($arrTempoResposta,$maxPrazo,$hi){
        $ha = date('Y-m-d H:i:s');

        $objDateDiff = new Util_Datediff($hi,$ha,'h');
        $diff        = $objDateDiff->datediff();

        if ($diff < 0) {
             $diff = 0;
        }
        $percentExec = round(($diff * 100)/$maxPrazo);

        foreach ($arrTempoResposta as $tr){
            if (is_null($hi)) {
                $arr['tempo_resposta'] = Base_Util::getTranslator('L_VIEW_DEMANDA_NAO_LIDA');
                $arr['cor']            = "FFFFFF";
                return $arr;
            }
            if ($percentExec >= $tr['ni_percent_tempo_resposta_ini'] && $percentExec <= $tr['ni_percent_tempo_resposta_fim']) {
                $arr['tempo_resposta'] = $tr['tx_status_atendimento'];
                $arr['cor']            = $tr['tx_rgb_status_atendimento'];
                return $arr;
            }
            $arr['tempo_resposta'] = $tr['tx_status_atendimento'];
            $arr['cor']            = $tr['tx_rgb_status_atendimento'];
        }

        return $arr;
    }
}