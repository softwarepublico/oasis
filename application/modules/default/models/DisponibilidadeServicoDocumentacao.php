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

class DisponibilidadeServicoDocumentacao extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_DISPONIBILIDADE_SERVICO_DOC;
	protected $_primary  = array('cd_disponibilidade_servico', 'cd_objeto', 'cd_tipo_documentacao', 'dt_doc_disponibilidade_servico');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getDadosDocumentacaoDisponibilidadeServico($cd_disponibilidade_servico, $cd_objeto, $isObjeto = false){

		$select = $this->select()->setIntegrityCheck(false)
					   ->from(array('dsd'=>$this->_name),
                              array('*'),
                              $this->_schema)
					   ->join(array('td'=>KT_B_TIPO_DOCUMENTACAO), 
									'dsd.cd_tipo_documentacao = td.cd_tipo_documentacao',
							  'tx_tipo_documentacao',
							  $this->_schema)
					   ->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                              'dsd.cd_objeto = oc.cd_objeto',
                               'tx_objeto',
                               $this->_schema)
					   ->where("dsd.cd_disponibilidade_servico = ?", $cd_disponibilidade_servico, Zend_Db::INT_TYPE)
					   ->where("dsd.cd_objeto                  = ?", $cd_objeto, Zend_Db::INT_TYPE)
					   ->order('dsd.tx_arquivo_disp_servico DESC');

		$result = $this->fetchAll($select);

		if($isObjeto === false){
			$result = $result->toArray();
		}
		return $result;
	}

}