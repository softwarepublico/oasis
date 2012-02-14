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

class PreProjeto extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_PRE_PROJETO;
	protected $_primary  = 'cd_pre_projeto';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getPreProjeto($comSelecione = false)
	{
		$arrPreProjetos = array();
		if ($comSelecione === true) {
			$arrPreProjetos[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$res = $this->fetchAll();

		foreach ($res as  $valor) {
			$arrPreProjetos[$valor->cd_pre_projeto] = $valor->tx_sigla_pre_projeto;
		}
		return $arrPreProjetos;
	}

	public function getListaPreProjeto($cd_contrato)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('pre'=>$this->_name),
                      array('cd_pre_projeto',
                            'tx_sigla_pre_projeto',
                            'tx_horas_estimadas',
                            'tx_escopo_pre_projeto'),
                      $this->_schema);
        $select->join(array('uni'=>KT_B_UNIDADE),
                      '(pre.cd_unidade  = uni.cd_unidade)',
                      'tx_sigla_unidade',
                      $this->_schema);
		$select->where('pre.cd_contrato  = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->order('tx_sigla_pre_projeto');

        return $this->fetchAll($select)->toArray();
	}
}