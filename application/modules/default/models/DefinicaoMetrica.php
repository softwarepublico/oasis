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

class DefinicaoMetrica extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_B_DEFINICAO_METRICA;
	protected $_primary  = 'cd_definicao_metrica';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getDefinicaoMetrica($comSelecione=false)
	{
		$select = $this->select()->order('tx_nome_metrica');

        $definicoes   = $this->fetchAll($select);

        $arrDefinicao = array();
        if($comSelecione === true){
            $arrDefinicao[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }

        foreach( $definicoes as $definicao ){
            $arrDefinicao[$definicao->cd_definicao_metrica] = $definicao->tx_nome_metrica;
        }
        return $arrDefinicao;
	}
	
    public function getComboDefinicaoMetrica($cd_contrato, $comSelecione=false )
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('dm'=>$this->_name), 
                      array('cd_definicao_metrica',
                            'tx_nome_metrica',
                            'tx_sigla_metrica'),
                      $this->_schema);
        $select->join(array('cdm'=>KT_A_CONTRATO_DEFINICAO_METRICA),
                      '(cdm.cd_definicao_metrica = dm.cd_definicao_metrica)',
                      array(),
                      $this->_schema);
        $select->where('cdm.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        $select->order('dm.tx_nome_metrica');

        $definicoes   = $this->fetchAll($select);
        $arrDefinicao = array();
        if($comSelecione === true){
            $arrDefinicao[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }

        foreach( $definicoes as $definicao ){
            $arrDefinicao[$definicao->cd_definicao_metrica] = $definicao->tx_nome_metrica." ({$definicao->tx_sigla_metrica})";
        }

        return $arrDefinicao;
    }

	public function getComboSiglaDefinicaoMetrica($comSelecione = false){

		$select = $this->select()
						->from($this, array('cd_definicao_metrica', 'tx_sigla_metrica'))
						->order('tx_sigla_metrica');

		$siglas = $this->fetchAll($select);

        $arrSigla = array();
		if($comSelecione === true){
            $arrSigla[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
        }

        foreach( $siglas as $sigla ){
            $arrSigla[$sigla->cd_definicao_metrica] = $sigla->tx_sigla_metrica;
        }
        return $arrSigla;
	}

}