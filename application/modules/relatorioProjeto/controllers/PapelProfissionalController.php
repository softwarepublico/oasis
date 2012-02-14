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

class RelatorioProjeto_PapelProfissionalController extends Base_Controller_Action
{
	private $_objContrato;
	private $_objContratoProjeto;
	private $_objPapelProfissional;
	private $_objPdf;
	private $_arrDadosImpress;

	public function init()
	{
		parent::init();
		$this->_objContrato           = new Contrato();
        $this->_objContratoProjeto    = new ContratoProjeto();
        $this->_objPapelProfissional  = new PapelProfissional();
        $this->_objPdf                = new Base_Tcpdf_Pdf();
	}

	public function indexAction()
	{
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PAPEL_PROFISSIONAL'));
		$cd_contrato        = null;
        $comStatus          = true;
        
        if( is_null($_SESSION['oasis_logged'][0]['st_dados_todos_contratos']) ){
			$cd_contrato = $_SESSION['oasis_logged'][0]['cd_contrato'];
			$comStatus	 = false;
		}
		$this->view->arrContrato = $this->_objContrato->getContratoPorTipoDeObjeto(true, 'P', $cd_contrato, $comStatus);
	}

    public function pesquisaProjetoAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrProjetos = $this->_objContratoProjeto->listaProjetosContrato($cd_contrato, true, true);

		$options = '';

		foreach( $arrProjetos as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $options;
	}

    public function pesquisaPapelAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$cd_contrato = (int) $this->_request->getParam("cd_contrato", 0);
		$arrPapel    = $this->_objPapelProfissional->getPapelProfissionalPeloContrato($cd_contrato, true, true);

		$options = '';

		foreach( $arrPapel as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}

		echo $options;
	}

	public function papelProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $params = $this->_request->getParams();

        $objRelatorioProjetoPapelProfissional = new RelatorioProjetoPapelProfissional();
        $arrDados = $objRelatorioProjetoPapelProfissional->relPapelProfissional($params);

        $cd_projeto            = 0;
        $cd_papel_profissional = 0;
        $arrDadosAux           = array();
        $arrProfissional       = array();
        foreach($arrDados as $key=>$value){
            if($cd_projeto != $value->cd_projeto){
                $arrProfissional = array();
                $arrDadosAux[$value->tx_sigla_projeto] = array();
            }
            if($cd_papel_profissional != $value->cd_papel_profissional){
                $arrProfissional = array();
                $arrDadosAux[$value->tx_sigla_projeto][$value->tx_papel_profissional] = array();
            }
            $arrProfissional[$value->cd_profissional] = $value->tx_profissional;
            $arrDadosAux[$value->tx_sigla_projeto][$value->tx_papel_profissional] = $arrProfissional;

            
            $cd_papel_profissional = $value->cd_papel_profissional;
            $cd_projeto            = $value->cd_projeto;
        }

        $this->_arrDadosImpress = $arrDadosAux;
        $this->_geraRelatorio();
    }

    private function _geraRelatorio()
    {
        $this->_objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_PAPEL_PROFISSIONAL'), K_CREATOR_SYSTEM.', '.
                                                                                                  Base_Util::getTranslator('L_TIT_REL_PAPEL_PROFISSIONAL').', '.
                                                                                                  Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                                                                                  Base_Util::getTranslator('L_VIEW_PAPEL_PROFISSIONAL'));
        $this->_objPdf->SetDisplayMode("real");
        // add a page
        $this->_objPdf->AddPage();

        $w = array(180,10,170,15,165);

        if($this->_arrDadosImpress){

            foreach($this->_arrDadosImpress as $projeto=>$papeis){

                $this->_objPdf->SetFont('helvetica', 'B', 8);
                $this->_objPdf->Cell ($w[1], 6, Base_Util::getTranslator('L_VIEW_SIGLA').":",'',0,'L');
                $this->_objPdf->SetFont('helvetica', '', 8);
                $this->_objPdf->Cell($w[2], 6, $projeto,'',1,'L');

                foreach($papeis as $papel=>$profissionais){
                    $this->_objPdf->SetFont('helvetica', 'B', 8);
                    $this->_objPdf->Cell ($w[1], 6, "-",'',0,'R');
                    $this->_objPdf->Cell($w[2], 6, $papel,'',1,'L');
                    $this->_objPdf->SetFont('helvetica', '', 8);

                    foreach($profissionais as $profissional){
                        $this->_objPdf->Cell ($w[3], 6, "-",'',0,'R');
                        $this->_objPdf->Cell($w[4], 6, $profissional,'',1,'L');
                    }
                }
            }
        }else{
            $this->_objPdf->writeHTML($this->_objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        //Close and output PDF document
        $this->_objPdf->Output('Relatorio_papel_profissional.pdf', 'I');
    }
}