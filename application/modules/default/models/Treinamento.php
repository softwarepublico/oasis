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

class Treinamento extends Base_Db_Table_Abstract 
{
	protected $_name 	 = KT_B_TREINAMENTO;
	protected $_primary  = 'cd_treinamento';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function pesquisaProfissionalForaTreinamento($cd_objeto, $comAdm = false, $cd_treinamento)
	{
        $select   = $this->select()->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional','tx_profissional'),
                      $this->_schema);
        $select->join(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                      '(prof.cd_profissional = poc.cd_profissional)',
                      array(),
                      $this->_schema);

        $select->where('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->where('prof.st_inativo is null');
        $select->where('prof.cd_profissional NOT IN ?', $this->_montaSubSelectPesquisaProfissionalTreinamento($cd_treinamento));
        $select->where('poc.cd_perfil_profissional is not null');

		if(!$comAdm){
            $select->where('prof.cd_profissional <> 0');
		}
        $select->order('prof.tx_profissional');

        return $this->fetchAll($select);
	}

	public function pesquisaProfissionalNoTreinamento($cd_objeto, $comAdm = false, $cd_treinamento)
	{
        $select   = $this->select()->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional','tx_profissional'),
                      $this->_schema);
        $select->join(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                      '(prof.cd_profissional = poc.cd_profissional)',
                      array(),
                      $this->_schema);

        $select->where('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->where('prof.st_inativo is null');
        $select->where('prof.cd_profissional IN ?', $this->_montaSubSelectPesquisaProfissionalTreinamento($cd_treinamento));
        $select->where('poc.cd_perfil_profissional is not null');

		if(!$comAdm){
            $select->where('prof.cd_profissional <> 0');
		}
        $select->order('prof.tx_profissional');

        return $this->fetchAll($select);
	}

    private function _montaSubSelectPesquisaProfissionalTreinamento($cd_treinamento)
    {
        $objTable = new TreinamentoProfissional();
        $select = $objTable->select()
                           ->from(array('tp'=>KT_A_TREINAMENTO_PROFISSIONAL),
                                  array('cd_profissional'),
                                  $this->_schema)
                           ->where('tp.cd_profissional = prof.cd_profissional')
                           ->where('cd_treinamento = ?', $cd_treinamento, Zend_Db::INT_TYPE);

        return $select;
    }

	public function pesquisaProfissionalNoTreinamentoParaGrid($cd_objeto, $comAdm = false, $cd_treinamento)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('prof'=>KT_S_PROFISSIONAL),
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);
        $select->join(array('poc'=>KT_A_PROFISSIONAL_OBJETO_CONTRATO),
                      '(prof.cd_profissional = poc.cd_profissional)',
                      array(),
                      $this->_schema);
        $select->join(array('treinprof'=>KT_A_TREINAMENTO_PROFISSIONAL),
                      '(prof.cd_profissional = treinprof.cd_profissional)',
                      array('dt_treinamento_profissional'),
                      $this->_schema);
        $select->join(array('trein'=>$this->_name),
                      '(treinprof.cd_treinamento = trein.cd_treinamento)',
                      array('cd_treinamento',
                            'tx_treinamento'),
                      $this->_schema);
        $select->where('poc.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);
        $select->where('prof.st_inativo is null');
        $select->where('prof.cd_profissional IN ?',$this->_montaSubSelectPesquisaProfissionalTreinamento($cd_treinamento));
        $select->where('poc.cd_perfil_profissional is not null');

		if(!$comAdm){
            $select->where('prof.cd_profissional <> 0');
		}
        $select->order('prof.tx_profissional');

        return $this->fetchAll($select);
	}
	
	public function getTreinamento($comSelecione = false)
	{
        $select = $this->select()->order('tx_treinamento');
					
		if ($comSelecione === true) {
			$arrObjetoContrato[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}
		
		$res = $this->fetchAll($select);

		foreach ($res as $treinamento) {
			$arrObjetoContrato[$treinamento->cd_treinamento] = $treinamento->tx_treinamento;
		}
		return $arrObjetoContrato;
	}
	
}