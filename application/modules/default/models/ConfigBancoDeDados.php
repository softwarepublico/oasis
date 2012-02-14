<?php
/**
 * @Copyright Copyright 2006, 2007, 2008, 2009 MDIC - Ministério do Desenvolvimento, da Industria e do Comércio Exterior, Brasil.
 * @tutorial  Este arquivo é parte do programa OASIS - Sistema de Gestão de Demanda, Projetos e Serviços de TI.
 *			  O OASIS é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença
 *			  Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença,
 *			  ou (na sua opnião) qualquer versão.
 *			  Este programa é distribuido na esperança que possa ser util, mas SEM NENHUMA GARANTIA;
 *			  sem uma garantia implicita de ADEQUAÇÂO a qualquer MERCADO ou APLICAÇÃO EM PARTICULAR.
 *			  Veja a Licença Pública Geral GNU para maiores detalhes.
 *			  Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt",
 *			  junto com este programa, se não, escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St,
 *			  Fifth Floor, Boston, MA 02110-1301 USA.
 */

class ConfigBancoDeDados extends Base_Db_Table_Abstract
{
    protected $_name     = KT_S_CONFIG_BANCO_DE_DADOS;
    protected $_primary  = 'cd_projeto';
    protected $_schema   = K_SCHEMA;
    protected $_sequence = false;

    public function getConfigBancoDados($cd_projeto = null)
    {
        return $this->find($cd_projeto)->current();
    }

    public function salvarConfigBanco(Array $arrDados)
    {
        $objProjeto = $this->find($arrDados['cd_projeto'])->current();

        if(!is_null($objProjeto)) {
            return $this->alterarConfiguracoes($arrDados);
        } else {
            $novo               = $this->createRow();
            $novo->cd_projeto   = $arrDados['cd_projeto'];
            $novo->tx_adapter   = $arrDados['tx_adapter'];
            $novo->tx_host      = $arrDados['tx_host'];
            $novo->tx_dbname    = $arrDados['tx_dbname'];
            $novo->tx_username  = $arrDados['tx_username'];
            $novo->tx_password  = base64_encode($arrDados['tx_password']);
            $novo->tx_schema    = $arrDados['tx_schema'];
            $novo->tx_port      = $arrDados['tx_port'];
            if(!$novo->save()) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function alterarConfiguracoes(Array $arrDados)
    {
        $arrUpdate['cd_projeto'	] = $arrDados['cd_projeto'];
        $arrUpdate['tx_adapter'	] = $arrDados['tx_adapter'];
        $arrUpdate['tx_host'	] = $arrDados['tx_host'];
        $arrUpdate['tx_dbname'	] = $arrDados['tx_dbname'];
        $arrUpdate['tx_username'] = $arrDados['tx_username'];
        $arrUpdate['tx_password'] = base64_encode($arrDados['tx_password']);
        $arrUpdate['tx_schema'	] = $arrDados['tx_schema'];
        $arrUpdate['tx_port'	] = $arrDados['tx_port'];

        if(!$this->update($arrUpdate,array('cd_projeto = ?'=>$arrDados['cd_projeto']))) {
            return true;
        } else {
            return false;
        }
    }
}