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
class DicionarioDeDadosController extends Base_Controller_Action
{
    private $_objConfigBanco;
    private $_objTabela;
    private $_objColuna;
    private $_objConexao;

    private $_cd_projeto;
    private $_schema;
    private $_adapter;

    public function init()
    {
        parent::init();
        $this->_objConfigBanco = new ConfigBancoDeDados($this->_request->getControllerName());
        $this->_objTabela      = new Tabela($this->_request->getControllerName());
        $this->_objColuna      = new Coluna($this->_request->getControllerName());
        $this->_objConexao     = new Base_Controller_Action_Helper_Conexao();
    }

    public function salvarDocumentacaoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $arrDados = $this->_request->getPost();

        $arrColumns = explode("|",$arrDados['columns_table']);
        $arrPrimary = explode("|",$arrDados['key_primary']);

        $arrTabela['tx_tabela']    = $arrDados['tx_tabelas'];
        $arrTabela['cd_projeto']   = $arrDados['cd_projeto'];
        $arrTabela['tx_descricao'] = $arrDados['tx_desc_tabelas'];

        $returnTable = $this->_objTabela->insertTable($arrTabela);
        if($returnTable) {
            $returnColumns = true;
            foreach($arrColumns as $value) {
            //retira o _col do value
                $column = str_ireplace("_col","",$value);
                if(array_key_exists($value,$arrDados)) {
                    $arrColuna['tx_tabela']            = $arrDados['tx_tabelas'];
                    $arrColuna['tx_coluna']            = $arrDados[$value];
                    $arrColuna['cd_projeto']           = $arrDados['cd_projeto'];
                    $arrColuna['tx_descricao']         = $arrDados[$column."_comentario"];

                    foreach ($arrPrimary as $valor) {
                        if($valor == $arrDados[$value]) {
                            $arrColuna['st_chave'] = "S";
                        }
                    }

                    $arrColuna['tx_tabela_referencia']  = $arrDados['tx_tabelas'];
                    $arrColuna['cd_projeto_referencia'] = $arrDados['cd_projeto'];
                    $returnColumns = $this->_objColuna->insertColumn($arrColuna);

                    if(!$returnColumns) {
                        echo 1;
                        break;
                    }
                }
            }
            if($returnColumns && $returnTable) {
                echo 2;
            }
        } else {
            echo 1;
        }
    }

    public function salvarConfiguracoesBancoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $arrDados = $this->_request->getPost();
        $return = $this->_objConfigBanco->salvarConfigBanco($arrDados);

        $msg = (!$return)? Base_Util::getTranslator('L_MSG_SUCESS_INCLUSAO_CONFIGURACAO_BANDO_DADOS'):Base_Util::getTranslator('L_MSG_ERRO_INCLUSAO_CONFIGURACAO_BANDO_DADOS');
        echo Zend_Json::encode(array('error'=>$return,'msg'=>$msg));
    }

    /**
     * Método que testa conexão com o banco de dados.
     * Utilizado na tela de Dicionário de Dados e no instalador,
     * verificando se os dados informados são mesmo de uma conexão
     * valida.
     * @param <type> $arrDataConection['tx_adapter']
     * @param <type> $arrDataConection['tx_host']
     * @param <type> $arrDataConection['tx_username']
     * @param <type> $arrDataConection['tx_username']
     * @param <type> $arrDataConection['tx_dbname']
     * @param <type> $arrDataConection['tx_port']
     * @param <type> $arrDataConection
     */
    public function getConexaoProjetoAction($cd_projeto_int=null)
    {
        if(is_null($cd_projeto_int)) {
            $this->_helper->viewRenderer->setNoRender(true);
            $this->_helper->layout->disableLayout();
            $this->_cd_projeto = $this->_request->getParam('cd_projeto');
        } else {
            $this->_cd_projeto = $cd_projeto_int;
        }
        
        $objDataConection = $this->_objConfigBanco->getConfigBancoDados($this->_cd_projeto);
        $arrDataConection = array();
        if(count($objDataConection) > 0) {
            $this->_schema    = $objDataConection->tx_schema;
            $this->_adapter   = $objDataConection->tx_adapter;
            $arrDataConection = $objDataConection->toArray();
        }

		$arrRetorno = $this->_objConexao->validaConexao($arrDataConection);
        
        if(is_null($cd_projeto_int)) {
            echo Zend_Json_Encoder::encode($arrRetorno);
        } else {
            return $arrRetorno;
        }
    }

    public function salvaTabelasColuna($connect,$schema,array $arrDados)
    {
        foreach($arrDados as $key=>$value) {
            $arrTabela['tx_tabela']  = $value['tabela'];
            $arrTabela['cd_projeto'] = $this->_cd_projeto;
            if($value['comentario'] != "") {
                $arrTabela['tx_descricao'] = $value['comentario'];
            }
            $resutTable = $this->_objTabela->insertTable($arrTabela);
            if($resutTable) {
                $arrColumns = $connect->describeTable($value['tabela'],$schema);

                foreach($arrColumns as $i=>$conteudo) {
                    $arrColuna['tx_tabela'];
                    $arrColuna['tx_coluna'];
                    $arrColuna['cd_projeto'];
                    $arrColuna['tx_descricao'];
                    $arrColuna['st_chave'];
                    $arrColuna['tx_tabela_referencia'];
                    $arrColuna['cd_projeto_referencia'];
                }
            }
        }
    }

    public function montaSelectTabelasAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $cd_projeto = $this->_request->getParam('cd_projeto');

        $arrRetorno = array('type'=>'','msg'=>'','error'=>false);
        //Chama método para abrir a conexão
        $arrConexao = $this->getConexaoProjetoAction($cd_projeto);

        if($arrConexao['error'] == true) {
            $arrRetorno = $arrConexao;
        } else {
            //Método para recuperar as tabelas
            $this->_objConexao->getListTable($this->_adapter, $this->_schema);
            
            $arrTable  = $this->_objConexao->getArrTable();
            $strOption = "<option checked=checked value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
            if(count($arrTable) > 0) {
                switch ($this->_adapter) {
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_POSTGRES:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_POSTGRES:
                        foreach($arrTable as $key=>$value) {
                            $strOption .= "<option value=\"{$value['tabela']}\" label=\"{$value['tabela']}\">{$value['tabela']}</option>";
                        }
                        break;
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_ORACLE:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE:
                        foreach($arrTable as $key=>$value) {
                            $strOption .= "<option value=\"{$value['TABELA']}\" label=\"{$value['TABELA']}\">{$value['TABELA']}</option>";
                        }
                        break;
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MSSQL:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL:
                        foreach($arrTable as $key=>$value) {
                            $strOption .= "<option value=\"{$value['tabela']}\" label=\"{$value['tabela']}\">{$value['tabela']}</option>";
                        }
                        break;
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MYSQL:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_MYSQL:
                        foreach($arrTable as $key=>$value) {
                            $strOption .= "<option value=\"{$value['tabela']}\" label=\"{$value['tabela']}\">{$value['tabela']}</option>";
                        }
                        break;
                }
            }
            $arrRetorno['tabelas'] = $strOption;
        }
        echo Zend_Json::encode($arrRetorno);
    }

    public function recuperaDadosTabelaColunaAction()
    {
        $this->_helper->layout->disableLayout();

        $cd_projeto = $this->_request->getParam('cd_projeto',0);
        $tx_schema  = $this->_request->getParam('tx_schema', 0);
        $tx_tabela  = $this->_request->getParam('tx_tabelas',0);

        $this->getConexaoProjetoAction($cd_projeto);
        if($tx_tabela != '0') {
            $arrDescribeTable        = $this->_objConexao->getConnection()->describeTable($tx_tabela,$tx_schema);
            $arrCommentsTableColumns = $this->_objConexao->getRecuperaComentario($this->_adapter,$tx_tabela,$tx_schema);

            //Campatibiliza os metadados do banco de dados com os comentarios
            //da tabela e das colunas
            $arrDadosTabelas = $this->_montaDadosTabelaComentario($arrDescribeTable, $arrCommentsTableColumns, $cd_projeto);
            //Recupera o cadastro dos comentarios no banco de dados do OASIS
            $arrDadosTabelas = $this->_recuperaColunaIncluidaAction($arrDadosTabelas);
        } else {
            $arrDadosTabelas[0]['msg'] = Base_Util::getTranslator('L_MSG_ALERT_SELECIONE_TABELA');
        }
        $this->view->arrDadosTabelas   = $arrDadosTabelas;
    }

    private function _montaDadosTabelaComentario(Array $arrDados1, Array $arrDados2, $cd_projeto)
    {
        $arrAux = array();
        $i = 0;

        if(count($arrDados1) > 0){

            foreach($arrDados1 as $key=>$value){
                $arrAux[$i]['st_chave']              = "";
                if($value['PRIMARY'] == 1){
                    $arrAux[$i]['st_chave']          = "S";
                }
                $arrAux[$i]['msg']                   = "";
                $arrAux[$i]['schema_name']           = $value['SCHEMA_NAME'];
                $arrAux[$i]['table_name']            = $value['TABLE_NAME'];
                $arrAux[$i]['column_name']           = $value['COLUMN_NAME'];
                $arrAux[$i]['cd_projeto']            = $cd_projeto;
                $arrAux[$i]['cd_projeto_referencia'] = $cd_projeto;
                $arrAux[$i]['description_table_bank']= "";
                $arrAux[$i]['description_column_bank'] = "";

                if(count($arrDados2) > 0){
                    switch ($this->_adapter) {
                        case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_POSTGRES:
                        case Base_Controller_Action_Helper_Conexao::ADAPTER_POSTGRES:
                            foreach($arrDados2 as $chave=>$valor){
                                if($value['TABLE_NAME'] == $valor['table_name']){
                                    if(empty($valor['column'])){
                                         $arrAux[$i]['description_table_bank'] = $valor['description'];
                                    } else {
                                        if($value['COLUMN_NAME'] == $valor['column']){
                                            $arrAux[$i]['description_column_bank'] = $valor['description'];
                                            break;
                                        }
                                    }
                                }
                            }
                            break;
                        case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_ORACLE:
                        case Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE:
                            foreach($arrDados2 as $chave=>$valor){
                                if($value['TABLE_NAME'] == $valor['TABLE_NAME']){
                                    if(empty($valor['COLUMN'])){
                                         $arrAux[$i]['description_table_bank'] = $valor['DESCRIPTION'];
                                    } else {
                                        if($value['COLUMN_NAME'] == $valor['COLUMN']){
                                            $arrAux[$i]['description_column_bank'] = $valor['DESCRIPTION'];
                                            break;
                                        }
                                    }
                                }
                            }
                            break;
                        case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MSSQL:
                        case Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL:
                            foreach($arrDados2 as $chave=>$valor){
                                if($value['TABLE_NAME'] == $valor['table_name']){
                                    if(empty($valor['column'])){
                                         $arrAux[$i]['description_table_bank'] = $valor['description'];
                                    } else {
                                        if($value['COLUMN_NAME'] == $valor['column']){
                                            $arrAux[$i]['description_column_bank'] = $valor['description'];
                                            break;
                                        }
                                    }
                                }
                            }
                            break;
                        case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MYSQL:
                        case Base_Controller_Action_Helper_Conexao::ADAPTER_MYSQL:
                            foreach($arrDados2 as $chave=>$valor){
                                if($value['TABLE_NAME'] == $valor['table_name']){
                                    if(empty($valor['column'])){
                                         $arrAux[$i]['description_table_bank'] = $valor['description'];
                                    } else {
                                        if($value['COLUMN_NAME'] == $valor['column']){
                                            $arrAux[$i]['description_column_bank'] = $valor['description'];
                                            break;
                                        }
                                    }
                                }
                            }
                            break;
                    }
                    
                }
                $i++;
            }
        } else {
            $arrAux[0]['msg'] = Base_Util::getTranslator('L_MSG_ALERT_TABELA_SEM_COLUNA');
        }
        return $arrAux;
    }

    private function _recuperaColunaIncluidaAction(array $arrDados)
    {
        $arrTabela = $this->_objTabela->getTabela($arrDados[0]['cd_projeto'], $arrDados[0]['table_name']);
        foreach($arrDados as $key=>$value) {
            $arrDados[$key]['description_column_registre'] = "";
            $arrDados[$key]['description_table_registre']  = "";
            $arrColunaCad = $this->_objColuna->recuperaDadosColuna($value['table_name'], $value['column_name'],$value['cd_projeto']);
            if(count($arrColunaCad) > 0 ) {
                $arrDados[$key]['description_column_registre'] = $arrColunaCad[0]['tx_descricao'];
            }
            if(count($arrTabela) >0){
                $arrDados[$key]['description_table_registre'] = $arrTabela[0]['tx_descricao'];
            }
            unset($arrColunaCad);
        }

        return $arrDados;
    }
}