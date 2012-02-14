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

class Grafico_Abstract
{
    protected $db;
    public $arrMes = array();
	
    public function __construct()
    {
        $this->db = Zend_Registry::get('db');
		$this->arrMes[0]  = Base_Util::getTranslator('L_VIEW_COMBO_MES');
        $this->arrMes[1]  = Base_Util::getTranslator('L_VIEW_COMBO_JAN');
        $this->arrMes[2]  = Base_Util::getTranslator('L_VIEW_COMBO_FEV');
        $this->arrMes[3]  = Base_Util::getTranslator('L_VIEW_COMBO_MAR');
        $this->arrMes[4]  = Base_Util::getTranslator('L_VIEW_COMBO_ABR');
        $this->arrMes[5]  = Base_Util::getTranslator('L_VIEW_COMBO_MAI');
        $this->arrMes[6]  = Base_Util::getTranslator('L_VIEW_COMBO_JUN');
        $this->arrMes[7]  = Base_Util::getTranslator('L_VIEW_COMBO_JUL');
        $this->arrMes[8]  = Base_Util::getTranslator('L_VIEW_COMBO_AGO');
        $this->arrMes[9]  = Base_Util::getTranslator('L_VIEW_COMBO_SET');
        $this->arrMes[10] = Base_Util::getTranslator('L_VIEW_COMBO_OUT');
        $this->arrMes[11] = Base_Util::getTranslator('L_VIEW_COMBO_NOV');
        $this->arrMes[12] = Base_Util::getTranslator('L_VIEW_COMBO_DEZ');
    }
}