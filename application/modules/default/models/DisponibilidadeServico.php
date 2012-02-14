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

class DisponibilidadeServico extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_DISPONIBILIDADE_SERVICO;
	protected $_primary  = array('cd_disponibilidade_servico', 'cd_objeto');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getAnaliseDisponibilidadeServico( $cd_objeto = null, $cd_analise = null, $isObjeto = false ){

		$select = $this->select()->setIntegrityCheck(false)
					   ->from(array('ds'=>$this->_name),
							  array('cd_disponibilidade_servico',
                                    'cd_objeto',
                                    'tx_analise_disp_servico',
                                    'ni_indice_disp_servico',
                                    'tx_parecer_disp_servico',
                                    'dt_inicio_analise_disp_servico',
                                    'dt_fim_analise_disp_servico'),
							  $this->_schema)
						->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                               "ds.cd_objeto = oc.cd_objeto",
                                'tx_objeto',
                                $this->_schema);
		$select->order("ds.dt_inicio_analise_disp_servico DESC");

		if( !is_null($cd_objeto))
			$select->where("ds.cd_objeto = ?", $cd_objeto, Zend_Db::INT_TYPE);
		if( !is_null($cd_analise))
			$select->where("ds.cd_disponibilidade_servico = ?", $cd_analise, Zend_Db::INT_TYPE);
		
		$retorno = $this->fetchAll($select);

		return ($isObjeto === false) ? $retorno->toArray() : $retorno;
	}
}