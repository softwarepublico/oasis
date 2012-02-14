<?php
class Base_Controller_Action_Helper_ParcelaOrcamento
{
	private $_st_parcela_orcamento;
	private $_ni_porcentagem_parcela_orcamento;
	private $_objObjetoContrato;
	private $_objContrato;

	public function  __construct() {
		Zend_Loader::loadClass('ObjetoContrato', Base_Util::baseUrlModule('default', 'models'));
		Zend_Loader::loadClass('Contrato',       Base_Util::baseUrlModule('default', 'models'));
		$this->_objObjetoContrato = new ObjetoContrato();
		$this->_objContrato       = new Contrato();
	}

	public function setStParcelaOrcamento($st_parcela_orcamento)
	{
		$this->_st_parcela_orcamento = $st_parcela_orcamento;
	}

	public function getStParcelaOrcamento()
	{
		return $this->_st_parcela_orcamento;
	}

	public function setNiPorcentagemParcelaOrcamento($ni_porcentagem_parc_orcamento)
	{
		$this->_ni_porcentagem_parcela_orcamento = $ni_porcentagem_parc_orcamento;
	}

	public function getNiPorcentagemParcelaOrcamento()
	{
		return $this->_ni_porcentagem_parcela_orcamento;
	}
	
	public function verificaIndicadorParcelaOrcamento($cd_projeto)
	{
		$arrContrato         = $this->_objContrato->getDadosContratoAtivoObjetoTipoProjeto($cd_projeto);
		$arrObjetoContrato   = $this->_objObjetoContrato->fetchRow(array("cd_contrato = ?"=>$arrContrato["cd_contrato"]))->toArray();
		$arrParcelaOrcamento = $this->_objObjetoContrato->verificaFlagParcelaOrcamento($arrObjetoContrato['cd_objeto']);
		$this->setStParcelaOrcamento($arrParcelaOrcamento['st_parcela_orcamento']);
		$this->setNiPorcentagemParcelaOrcamento($arrParcelaOrcamento['ni_porcentagem_parc_orcamento']);
	}
}
