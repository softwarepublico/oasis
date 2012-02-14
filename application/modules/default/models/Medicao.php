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

class Medicao extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_MEDICAO;
	protected $_primary  = 'cd_medicao';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getComboMedicoes($comSelecione = false)
	{
		$option = '';
		
		if($comSelecione === true){
			$option .= "<option value=\"0\">".Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE')."</option>";
		}
		
		$select = $this->select()->order("tx_medicao");
		$res = $this->fetchAll($select);
		
    	foreach ($res as $valor) {
			$option .= "<option value=\"{$valor->cd_medicao}\">{$valor->tx_medicao}</option>";
		}
		
		return $option;
	}
	
	public function getMedicoes($comSelecione = false)
	{
		$arrRetorno = array();
		
		if($comSelecione === true){
			$arrRetorno[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$res = $this->fetchAll($this->select()->order("tx_medicao"));
		
    	foreach ($res as $valor) {
			$arrRetorno[$valor->cd_medicao] = $valor->tx_medicao;
		}
		return $arrRetorno;
	}
	
	public function getAllMedicoes($cd_medicao=null)
	{
        $select = $this->select()
                       ->from($this,
                              array('cd_medicao',
                                    'tx_medicao',
                                    'tx_objetivo_medicao',
                                    'tx_procedimento_coleta',
                                    'tx_procedimento_analise',
                                    'st_nivel_medicao'=>new Zend_Db_Expr("CASE st_nivel_medicao WHEN 'E' THEN '".Base_Util::getTranslator('L_SQL_ESTRATEGICO')."'
                                                                                                         ELSE '".Base_Util::getTranslator('L_SQL_TECNICO')."' END") ))
                       ->order('cd_medicao');

        if(!is_null($cd_medicao)){
			$select->where('cd_medicao = ?', $cd_medicao, Zend_Db::INT_TYPE);
		}
		return $this->fetchAll($select)->toArray();
	}
	
}