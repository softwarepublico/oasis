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

class RelatorioProjeto_DicionarioDeDadosController extends Base_Controller_Action 
{
	private $objContratoProjeto;
	private $_objConfigBanco;
	private $_arrTabela;
	private $_arrColuna;
	private $_arrProjeto;
	private $objPdf;
    private $_objTabela;
    private $_objColuna;
	private $_objProjeto;
	
	public function init()
	{
		parent::init();
		$this->objContratoProjeto = new ContratoProjeto($this->_request->getControllerName());
		$this->_objConfigBanco    = new ConfigBancoDeDados($this->_request->getControllerName());
		$this->_objTabela         = new Tabela($this->_request->getControllerName());
		$this->_objColuna         = new Coluna($this->_request->getControllerName());
		$this->_objProjeto        = new Projeto($this->_request->getControllerName());
		$this->objPdf			  = new Base_Tcpdf_Pdf('L');
	}
	
	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_DICIONARIO_DADOS'));
        
        $objContrato 		= new Contrato($this->_request->getControllerName());
		$cd_contrato 		= null;
		$comStatus			= true;
		
		if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		
		$this->view->arrContrato = $objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
    }

	public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->objContratoProjeto->listaProjetosContrato($cd_contrato, true);
		
		$options = '';
		
		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		
		echo $options;
	}
	
	public function dicionarioDeDadosAction()
	{
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
        
        $arrParam['cd_projeto']  = $this->_request->getParam('cd_projeto');
        $arrParam['cd_contrato'] = $this->_request->getParam('cd_contrato');

		$this->_arrProjeto = $this->_objProjeto->find($arrParam['cd_projeto'])->current()->toArray();

        //Código inserido para evitar timeout
		set_time_limit(120);

        $this->_generate($arrParam);
	}

    private function _generate(Array $arrParam)
    {
        $objConfigBancoDados = new ConfigBancoDeDados();
        $objTabela           = new Tabela();
        $objColuna           = new Coluna();

        $arrConexao = $objConfigBancoDados->fetchAll(array('cd_projeto = ?'=>$arrParam['cd_projeto']))->toArray();

        if(count($arrConexao) > 0){
			$this->_helper->conexao->setProjeto($arrParam['cd_projeto']);

			$arrRetorno = array('type'=>'','msg'=>'','error'=>false);
			//Chama método para abrir a conexão
			$arrConexao = $this->getConexaoProjetoAction($arrParam['cd_projeto']);

			if($arrConexao['error'] == true) {
				$arrRetorno = $arrConexao;
			} else {
				$this->getArrayTableColumn($arrParam['cd_projeto']);
			}
        }
//		$this->_geraRelatorio();
		$this->_geraRelatoriohtml();
	}

	private function _geraRelatorio()
	{
        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_DICIONARIO_DADOS'),
                             Base_Util::getTranslator('L_VIEW_ATIVIDADE'),
                             Base_Util::getTranslator('L_VIEW_ETAPA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );

		$this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_DICIONARIO_DADOS'), $arrKeywords);
		$this->objPdf->SetDisplayMode("real");
		$this->objPdf->AddPage();

		//cabeçalho
		$this->objPdf->SetFont('helvetica', 'B', 8);
		$this->objPdf->Cell(180,2, Base_Util::getTranslator('L_VIEW_PROJETO'),'',1);
		$this->objPdf->SetFont('helvetica', '', 8);
		$this->objPdf->Cell(180,6,$this->_arrProjeto['tx_sigla_projeto'],'',1);

		if(count($this->_arrTabela) > 0){

			$borderBotton = "";

			foreach($this->_arrTabela as $key=>$value){
                switch ($this->_adapter) {
                case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_POSTGRES:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_POSTGRES:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MSSQL:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MYSQL:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_MYSQL:
                    $table       = 'tabela';
                    $column_name = 'column_name';
                    $comentario  = 'comentario';
                    $data_type   = 'data_type';
                    $data_length = 'data_length';
                    $nullable      = 'nullable';
                    $comentario_banco = 'comentario_banco';
                    break;
                case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_ORACLE:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE:
                    $table       = 'TABELA';
                    $comentario  = 'COMENTARIO';
                    $column_name = 'COLUMN_NAME';
                    $data_type   = 'DATA_TYPE';
                    $data_length = 'DATA_LENGTH';
                    $nullable      = 'NULLABLE';
                    $comentario_banco = 'COMENTARIO_BANCO';
                    break;
            }

				$this->objPdf->SetFillColor(200,200,200);
				$this->objPdf->SetTextColor(0);

				$this->objPdf->SetFont('helvetica', 'B', 8);
				$this->objPdf->Cell(67, 6, Base_Util::getTranslator('L_VIEW_TABELA'),1,0,'C',1);
				$this->objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_COMENTARIO_BANCO'),1,0,'C',1);
				$this->objPdf->Cell(100, 6, Base_Util::getTranslator('L_VIEW_COMENTARIO_OASIS'),1,1,'C',1);


				$this->objPdf->SetFont('helvetica', 'B', 8);
				$this->objPdf->MultiCell(67,  6, $value[table],'','L',0,0);
				$this->objPdf->MultiCell(100, 6, $value[comentario],'','L',0,0);
				$this->objPdf->MultiCell(100, 6, $value['comentario_oasis'],'','L',0,1);

				$this->objPdf->SetFillColor(240,240,240);
				$this->objPdf->SetTextColor(0);

				$this->objPdf->Cell(67, 6, Base_Util::getTranslator('L_VIEW_COLUNA'),1,0,"C",1);
				$this->objPdf->Cell(40, 6, Base_Util::getTranslator('L_VIEW_TIPO_DADO'),1,0,"C",1);
				$this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_TAMANHO'),1,0,"C",1);
				$this->objPdf->Cell(20, 6, Base_Util::getTranslator('L_VIEW_NULO'),1,0,"C",1);
				$this->objPdf->Cell(60, 6, Base_Util::getTranslator('L_VIEW_COMENTARIO_BANCO'),1,0,'C',1);
				$this->objPdf->Cell(60, 6, Base_Util::getTranslator('L_VIEW_COMENTARIO_OASIS') ,1,1,'C',1);

				$this->objPdf->SetFillColor(Base_Tcpdf_Pdf::R_FILL, Base_Tcpdf_Pdf::G_FILL, Base_Tcpdf_Pdf::B_FILL);
				$this->objPdf->SetTextColor(0);
				$fill = 0;

				foreach( $this->_arrColuna[$value[$table]] as $rs ){

					$this->objPdf->SetFont('helvetica', '', 8);
					$this->objPdf->MultiCell(67, 6, "               {$rs[$column_name]}",'',"L",$fill,0);
					$this->objPdf->MultiCell(40, 6, "{$rs[$data_type]}",'',"L",$fill,0);
					$this->objPdf->MultiCell(20, 6, "{$rs[$data_length]}",'',"C",$fill,0);
					$this->objPdf->MultiCell(20, 6, "{$rs[$nullable]}",'',"C",$fill,0);
					$this->objPdf->MultiCell(60, 6, "{$rs[$comentario_banco]}",'','L',$fill,0);
					$this->objPdf->MultiCell(60, 6, "{$rs['comentario_oasis']}",'','L',$fill,1);
					$fill=!$fill;
				}
                
				$this->objPdf->Cell(267, 6, " " ,"B",1);
				$this->objPdf->Ln(6);
			}
			$this->objPdf->Ln(6);
			$this->objPdf->Cell(PDF_MARGIN_LEFT,6,"__");

			//Close and output PDF document
			$this->objPdf->Output('relatorio_dicionario_dados.pdf', 'I');
		}else{
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}
	}


    	private function _geraRelatoriohtml()
	{
        $arrKeywords = array(K_CREATOR_SYSTEM,
                             Base_Util::getTranslator('L_TIT_REL_DICIONARIO_DADOS'),
                             Base_Util::getTranslator('L_VIEW_ATIVIDADE'),
                             Base_Util::getTranslator('L_VIEW_ETAPA'),
                             Base_Util::getTranslator('L_VIEW_RELATORIO')
                            );

		$this->objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_DICIONARIO_DADOS'), $arrKeywords);
		$this->objPdf->SetDisplayMode("fullwidth");
		$this->objPdf->AddPage();

		//cabeçalho
		$this->objPdf->SetFont('helvetica', 'B', 8);
		$this->objPdf->Cell(20,2, Base_Util::getTranslator('L_VIEW_PROJETO').': ','',0);
		$this->objPdf->SetFont('helvetica', '', 8);
		$this->objPdf->Cell(160,6,$this->_arrProjeto['tx_sigla_projeto'].' - '.$this->_arrProjeto['tx_projeto'],'',1);
		
		if(count($this->_arrTabela) > 0){

			$borderBotton = "";

            $strTable = "";

          	$qtdFor		= 1;
			foreach($this->_arrTabela as $key=>$value){
                $strTable .= '<table cellpadding="3" cellspacing="0" bordercolor="#CCCCCC" border="1">';
                switch ($this->_adapter) {
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_POSTGRES:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_POSTGRES:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MSSQL:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MYSQL:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_MYSQL:
                        $table            = 'tabela';
                        $column_name      = 'column_name';
                        $comentario       = 'comentario';
                        $data_type        = 'data_type';
                        $data_length      = 'data_length';
                        $nullable         = 'nullable';
                        $comentario_banco = 'comentario_banco';
                        $comentario_oasis = 'comentario_oasis';
                        break;
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_ORACLE:
                    case Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE:
                        $table            = 'TABELA';
                        $comentario       = 'COMENTARIO';
                        $column_name      = 'COLUMN_NAME';
                        $data_type        = 'DATA_TYPE';
                        $data_length      = 'DATA_LENGTH';
                        $nullable         = 'NULLABLE';
                        $comentario_banco = 'COMENTARIO_BANCO';
                        $comentario_oasis = 'COMENTARIO_OASIS';
                        break;
                }
				//o segundo loop do foreach 1º foreach diz respeito aos requisitos não funcionais


                $strTable .= "<tr bgcolor= '#ff0000' >";
                $strTable .= '  <td width="200px" style="text-align:center;background-color:lightgrey;"><b>'.Base_Util::getTranslator('L_VIEW_TABELA').'</b></td>';
                $strTable .= '  <td width="279px" colspan="3" style="text-align:center;background-color:lightgrey;"><b>'.Base_Util::getTranslator('L_VIEW_COMENTARIO_BANCO').'</b></td>';
                $strTable .= '  <td width="279px" colspan="2" style="text-align:center;background-color:lightgrey;"><b>'.Base_Util::getTranslator('L_VIEW_COMENTARIO_OASIS').'</b></td>';
                $strTable .= '</tr>';

     
                $strTable .= '<tr>';
                $strTable .= '  <td width="200px" style="text-align:left;">'.$value[$table].'</td>';
                $strTable .= '  <td width="279px"  colspan="3"  style="text-align:left;">'.htmlspecialchars($value[$comentario]).'</td>';
                $strTable .= '  <td width="279px"  colspan="2" style="text-align:left;">'.htmlspecialchars($value['comentario_oasis']).'</td>';
                $strTable .= '</tr>';


                $strTable .= '<tr>';
                $strTable .= '  <td width="110px" style="text-align:center;background-color:#E3E4FA;"><b>'.Base_Util::getTranslator('L_VIEW_COLUNA').'</b></td>';
                $strTable .= '  <td width="90px" style="text-align:center;background-color:#E3E4FA;"><b>'.Base_Util::getTranslator('L_VIEW_TIPO_DADO').'</b></td>';
                $strTable .= '  <td width="60px" style="text-align:center;background-color:#E3E4FA;"><b>'.Base_Util::getTranslator('L_VIEW_TAMANHO').'</b></td>';
                $strTable .= '  <td width="40px" style="text-align:center;background-color:#E3E4FA;"><b>'.Base_Util::getTranslator('L_VIEW_NULO').'</b></td>';
                $strTable .= '  <td width="229px" style="text-align:center;background-color:#E3E4FA;"><b>'.Base_Util::getTranslator('L_VIEW_COMENTARIO_BANCO').'</b></td>';
                $strTable .= '  <td width="229px" style="text-align:center;background-color:#E3E4FA;"><b>'.Base_Util::getTranslator('L_VIEW_COMENTARIO_OASIS').'</b></td>';
                $strTable .= '</tr>';

				foreach( $this->_arrColuna[$value[$table]] as $rs ){

                    $strTable .= '<tr>';
                    $strTable .= '  <td width="110px" style="text-align:left;">'."$rs[$column_name]".'</td>';
                    $strTable .= '  <td width="90px" style="text-align:center;">'.$rs[$data_type].'</td>';
                    $strTable .= '  <td width="60px" style="text-align:center;">'.$rs[$data_length].'</td>';
                    $strTable .= '  <td width="40px" style="text-align:center;">'.$rs[$nullable].'</td>';
                    $strTable .= '  <td width="229px" style="text-align:left;">'.htmlspecialchars_decode($rs[$comentario_banco]).'</td>';
                    $strTable .= '  <td width="229px" style="text-align:left;">'.$rs['comentario_oasis'].'</td>';
                    $strTable .= '</tr>';
				
				}

                $strTable .= '</table>';
                $strTable .= '<br>';

			}
			$this->objPdf->writeHTML($strTable,true,0, true, 0);

            $this->objPdf->Ln(3);
			$this->objPdf->Cell(PDF_MARGIN_LEFT,6,"__");
		}else{
			$this->objPdf->writeHTML($this->objPdf->semRegistroParaConsulta(),true, 0, true, 0);
		}

    	//Close and output PDF document
        $this->objPdf->Output('relatorio_dicionario_dados.pdf', 'I');

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

		$arrRetorno = $this->_helper->conexao->validaConexao($arrDataConection);
        if(is_null($cd_projeto_int)) {
            echo Zend_Json_Encoder::encode($arrRetorno);
        } else {
            return $arrRetorno;
        }
    }

    private function getArrayTableColumn($cd_projeto)
    {
        $this->_helper->conexao->getListTable($this->_adapter,$this->_schema);
        $this->_arrTabela = $this->_helper->conexao->getArrTable();
        $i = 0;
        foreach ($this->_arrTabela as $table)
        {
            switch ($this->_adapter) {
                case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_POSTGRES:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_POSTGRES:
                    $arrCommentTabela = $this->_objTabela->getTabela($cd_projeto,$table['tabela']);
                    if (count($arrCommentTabela) > 0) {
                        $this->_arrTabela[$i]['comentario_oasis'] = $arrCommentTabela[0]['tx_descricao'];
                    }else{
                        $this->_arrTabela[$i]['comentario_oasis'] = "";
                    }
                    $this->_helper->conexao->getListColumn($this->_adapter,$this->_schema,$table['tabela']);
                    $this->_arrColuna[$table['tabela']] = $this->_helper->conexao->getArrColumn();
                    $j = 0;
                    foreach ($this->_arrColuna[$table['tabela']] as $column)
                    {
                        $arrCommentColumn = $this->_objColuna->recuperaDadosColuna($table['tabela'],$column['column_name'],$cd_projeto);

                        if (count($arrCommentColumn) > 0) {
                            $this->_arrColuna[$table['tabela']][$j]['comentario_oasis'] = $arrCommentColumn[0]['tx_descricao'];
                        }else{
                            $this->_arrColuna[$table['tabela']][$j]['comentario_oasis'] = "";
                        }
                        $j++;
                    }
                    $i++;
                    break;
                case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_ORACLE:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_ORACLE:
                    $arrCommentTabela = $this->_objTabela->getTabela($cd_projeto,$table['TABELA']);
                    if (count($arrCommentTabela) > 0) {
                        $this->_arrTabela[$i]['comentario_oasis'] = $arrCommentTabela[0]['tx_descricao'];
                    }else{
                        $this->_arrTabela[$i]['comentario_oasis'] = "";
                    }
                    $this->_helper->conexao->getListColumn($this->_adapter,$this->_schema,$table['TABELA']);
                    $this->_arrColuna[$table['TABELA']] = $this->_helper->conexao->getArrColumn();
                    $j = 0;
                    
                    foreach ($this->_arrColuna[$table['TABELA']] as $column)
                    {
                        $arrCommentColumn = $this->_objColuna->recuperaDadosColuna($table['TABELA'],$column['COLUMN_NAME'],$cd_projeto);

                        if (count($arrCommentColumn) > 0) {
                            $this->_arrColuna[$table['TABELA']][$j]['comentario_oasis'] = $arrCommentColumn[0]['tx_descricao'];
                        }else{
                            $this->_arrColuna[$table['TABELA']][$j]['comentario_oasis'] = "";
                        }
                        $j++;
                    }
                    $i++;
                    break;
                case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MSSQL:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_MSSQL:
                    $arrCommentTabela = $this->_objTabela->getTabela($cd_projeto,$table['tabela']);
                    if (count($arrCommentTabela) > 0) {
                        $this->_arrTabela[$i]['comentario_oasis'] = $arrCommentTabela[0]['tx_descricao'];
                    }else{
                        $this->_arrTabela[$i]['comentario_oasis'] = "";
                    }
                    $this->_helper->conexao->getListColumn($this->_adapter,$this->_schema,$table['tabela']);
                    $this->_arrColuna[$table['tabela']] = $this->_helper->conexao->getArrColumn();
                    $j = 0;
                    foreach ($this->_arrColuna[$table['tabela']] as $column)
                    {
                        $arrCommentColumn = $this->_objColuna->recuperaDadosColuna($table['tabela'],$column['column_name'],$cd_projeto);

                        if (count($arrCommentColumn) > 0) {
                            $this->_arrColuna[$table['tabela']][$j]['comentario_oasis'] = $arrCommentColumn[0]['tx_descricao'];
                        }else{
                            $this->_arrColuna[$table['tabela']][$j]['comentario_oasis'] = "";
                        }
                        $j++;
                    }
                    $i++;
                    break;
                case Base_Controller_Action_Helper_Conexao::ADAPTER_PDO_MYSQL:
                case Base_Controller_Action_Helper_Conexao::ADAPTER_MYSQL:
                    $arrCommentTabela = $this->_objTabela->getTabela($cd_projeto,$table['tabela']);
                    if (count($arrCommentTabela) > 0) {
                        $this->_arrTabela[$i]['comentario_oasis'] = $arrCommentTabela[0]['tx_descricao'];
                    }else{
                        $this->_arrTabela[$i]['comentario_oasis'] = "";
                    }
                    $this->_helper->conexao->getListColumn($this->_adapter,$this->_schema,$table['tabela']);
                    $this->_arrColuna[$table['tabela']] = $this->_helper->conexao->getArrColumn();
                    $j = 0;
                    foreach ($this->_arrColuna[$table['tabela']] as $column)
                    {
                        $arrCommentColumn = $this->_objColuna->recuperaDadosColuna($table['tabela'],$column['column_name'],$cd_projeto);

                        if (count($arrCommentColumn) > 0) {
                            $this->_arrColuna[$table['tabela']][$j]['comentario_oasis'] = $arrCommentColumn[0]['tx_descricao'];
                        }else{
                            $this->_arrColuna[$table['tabela']][$j]['comentario_oasis'] = "";
                        }
                        $j++;
                    }
                    $i++;
                    break;
            }
        }
    }
}