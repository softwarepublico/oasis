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

class DocumentacaoProjetoContinuo extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_DOCUMENTACAO_PROJETO_CONTINUO;
	protected $_primary  = array('dt_doc_projeto_continuo',
                                    'cd_projeto_continuado',
                                    'cd_objeto',
                                    'cd_tipo_documentacao');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getDadosDocumentacaoProjetoContinuo($arrParams)
	{
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('dpc'=>$this->_name),
                      array('cd_tipo_documentacao',
                            'cd_objeto',
                            'cd_projeto_continuado',
                            'tx_arq_doc_projeto_continuo',
                            'dt_doc_projeto_continuo',
                            'tx_nome_arquivo'),
                      $this->_schema);
        $select->join(array('tp'=>KT_B_TIPO_DOCUMENTACAO),
                      '(dpc.cd_tipo_documentacao = tp.cd_tipo_documentacao)',
                      'tx_tipo_documentacao',
                      $this->_schema);
        $select->where('cd_objeto = ?'            ,$arrParams['cd_objeto'], Zend_Db::INT_TYPE);
        $select->where('cd_projeto_continuado = ?',$arrParams['cd_projeto_continuo'], Zend_Db::INT_TYPE);
        $select->order('dt_doc_projeto_continuo');
        
        return $this->fetchAll($select)->toArray();
	}
	
	public function getDocumentoProjetoContinuo($data, $cd_projeto_continuado, $tipo)
	{
		$select = $this->select()
                       ->where('dt_doc_projeto_continuo = ?', $data)
                       ->where('cd_projeto_continuado   = ?', $cd_projeto_continuado, Zend_Db::INT_TYPE)
                       ->where('cd_tipo_documentacao    = ?', $tipo, Zend_Db::INT_TYPE);
										 
		return $this->fetchAll($select)->toArray();
	}
	
}