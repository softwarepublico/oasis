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

class CategoriaRisco extends Base_Db_Table_Abstract
{
	protected $_name     = KT_B_CATEGORIA_RISCO;
	protected $_primary  = 'cd_categoria_risco';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getCategoriaRisco($comSelecione = false)
    {
		$arrCategoriaRisco = array();
		if ($comSelecione === true)
			$arrCategoriaRisco[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		
		$select = $this->select()->order("tx_categoria_risco");
		$res    = $this->fetchAll($select);
		
		foreach ($res as  $valor)
			$arrCategoriaRisco[$valor->cd_categoria_risco] = $valor->tx_categoria_risco;

        return $arrCategoriaRisco;
	}
}