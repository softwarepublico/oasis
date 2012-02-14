<?php
class Base_Controller_Action_Helper_Upload extends Zend_Controller_Action_Helper_Abstract
{
    /**
     *
     * @param string $fileElementName name do input de upload
     * @param string &$nomeOriginal nome original do arquivo é devolvido por referencia
     * @param array  $arrTipoDocumento extenções dos tipos de documentos permitidos para o upload
     * @param integer $uploadByteSize tamanho maximo permitido para upload em BYTE
     * @return boolean
     * @throws Base_Exception_Alert | Base_Exception_Error
     */
    public function validaUpload( $fileElementName, &$nomeOriginal, &$extensao, array $arrTipoDocumento, $uploadByteSize = K_SIZE_10MB_EM_BYTE )
    {
        if(!empty($_FILES[$fileElementName]['error'])){
            switch($_FILES[$fileElementName]['error']){
                case '1': throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_TAMANHO_UTRAPASSA_DIRECTIVA_PHP'));   break;
                case '2': throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_TAMANHO_ULTRAPASSA_DIRECTIVA_HTML')); break;
                case '3': throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_ARQUIVO_PARCIALMENTE_CARREGADO'));    break;
                case '4': throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_NENHUM_ARQUIVO_CARREGADO'));          break;
                case '6': throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_FALTA_PASTA_NAO_POSSUI_PERMISSAO'));  break;
                case '7': throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_FALHA_ESCREVER_DISCO'));              break;
                case '8': throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_EXTENSAO_INCORRETA_NAO_PERMITIDA'));  break;
                case '999':
                default:
                    throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_NENHUM_CODIGO_ERRO_DISPONIVEL'));
                    break;
            }
        } elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_NENHUM_ARQUIVO_CARREGADO'));
        } elseif($_FILES[$fileElementName]['size'] > $uploadByteSize){
            throw new Base_Exception_Alert(Base_Util::getTranslator('L_MSG_ALERT_TAMANHO_SUPERIOR_XX_MB', (($uploadByteSize/1024)/1024) ));
        }

        $arrExtensao   = explode(".", $_FILES[$fileElementName]['name']);
        $totalExtensao = (count($arrExtensao)-1);
        $nomeOriginal  = $arrExtensao[0];
        $extensao      = $arrExtensao[$totalExtensao];
        unset($arrExtensao);

        $ePermitido = false;
        foreach ($arrTipoDocumento as  $tipo) {
            if (strtolower($extensao) == strtolower($tipo)) {
                $ePermitido = true;
                break;
            }
        }
        if (!$ePermitido) throw new Base_Exception_Error(Base_Util::getTranslator('L_MSG_ERRO_EXTENSAO_NAO_PERMITIDA'));
            
        return true;
    }

}

