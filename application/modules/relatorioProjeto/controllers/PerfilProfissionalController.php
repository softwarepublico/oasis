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

class RelatorioProjeto_PerfilProfissionalController extends Base_Controller_Action
{
    private $_objObjetoContrato;

    public function init()
    {
        parent::init();
        $this->_objObjetoContrato = new ObjetoContrato();
    }

    public function indexAction()
    {
        $this->view->headTitle(Base_Util::setTitle('L_TIT_REL_PERFIL_PROFISSIONAL'));

        $this->view->comboObjeto = $this->_objObjetoContrato->getObjetoContratoAtivo( null, true, false, true );
    }

    public function comboPerfilAction()
    {
   		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

        $objPerfil = new PerfilProfissional();
        
        $cd_objeto = $this->_request->getParam('cd_objeto');

        $arrPerfilProfissional = $objPerfil->getPerfilProfissional($cd_objeto,true,true);

        $strOptions = "";
		if(count($arrPerfilProfissional) > 0){
			foreach ($arrPerfilProfissional as $cd_perfil=>$tx_perfil) {
				$strOptions .= "<option label=\"{$tx_perfil}\" value=\"{$cd_perfil}\">{$tx_perfil}</option>";
			}
		}

		echo $strOptions;

    }

    public function relatorioPerfilProfissionalAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
        
        $objRelPerfilProfissional = new RelatorioPerfilProfissional();

        $cd_objeto = $this->_request->getParam('cd_objeto',null);
        $cd_perfil = $this->_request->getParam('cd_perfil',0);



        $cd_perfil = ($cd_perfil == 0)?null:$cd_perfil;
        $arrDados  = $objRelPerfilProfissional->getPerfilProfissional($cd_objeto, $cd_perfil);
        $arrObjeto = $this->_objObjetoContrato->getObjetoContratoAtivo(null, false, false, true, $cd_objeto, null);

        $arrDados['objeto'] = $arrObjeto[$cd_objeto];
        $this->_generate($arrDados);
    }

    private function _generate(Array $arrDados)
    {
        
        $objPdf = new Base_Tcpdf_Pdf();
        $objPdf->iniciaRelatorio(Base_Util::getTranslator('L_TIT_REL_PERFIL_PROFISSIONAL'), K_CREATOR_SYSTEM.', '.
                                                                                            Base_Util::getTranslator('L_TIT_REL_PERFIL_PROFISSIONAL').', '.
                                                                                            Base_Util::getTranslator('L_VIEW_RELATORIO').', '.
                                                                                            Base_Util::getTranslator('L_VIEW_PERFIL_PROFISSIONAL'));
        $objPdf->SetDisplayMode("real");
        // add a page
        $objPdf->AddPage();

        $objPdf->SetFont('helvetica', 'B', 8);
        $objPdf->Cell(11, 6, Base_Util::getTranslator('L_VIEW_OBJETO').":",'',0,'L');
        $objPdf->SetFont('helvetica', '', 8);
        $objPdf->Cell(20, 6, $arrDados['objeto'],'',0,'L');
        $objPdf->Ln(5);
        unset($arrDados['objeto']);
        
        $w = array(180,10);
        if(count($arrDados) > 0){
            $tx_profissional        = "";
            $tx_perfil_profissional = "";
            foreach($arrDados as $projeto=>$papeis){
                if($papeis['tx_perfil_profissional'] != $tx_perfil_profissional){
                    $objPdf->Ln(5);
                    $objPdf->SetFont('helvetica', 'B', 8);
                    $objPdf->Cell($w[1], 6, $papeis['tx_perfil_profissional'],'',0,'L');
                    $objPdf->Ln(8);
                }
                if($papeis['tx_profissional'] != $tx_profissional){
                    $objPdf->SetFont('helvetica', '', 8);
                    $objPdf->Cell ($w[1], 6, "",'',0,'L');
                    $objPdf->Cell ($w[0], 6, $papeis['tx_profissional'],'',0,'L');
                    $objPdf->Ln(5);
                }

                $tx_profissional        = $papeis['tx_profissional'];
                $tx_perfil_profissional = $papeis['tx_perfil_profissional'];
            }
        } else {
            $objPdf->writeHTML($objPdf->semRegistroParaConsulta(),true, 0, true, 0);
        }
        
        //Close and output PDF document
        $objPdf->Output('Relatorio_papel_profissional.pdf', 'I');


    }
}