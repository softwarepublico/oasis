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

class GerenciaQualidade extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_GERENCIA_QUALIDADE;
	protected $_primary  = array('cd_gerencia_qualidade','cd_projeto','cd_proposta');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getDadosGerenciaQualidade($cd_projeto, $cd_proposta, $cd_gerencia_qualidade = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('gq'=>$this->_name),
                      array('dt_auditoria_qualidade',
                            'cd_gerencia_qualidade',
                            'tx_fase_projeto'),
                      $this->_schema);
        $select->join(array('prof'=>KT_S_PROFISSIONAL),
                      '(gq.cd_profissional = prof.cd_profissional)',
                      array('cd_profissional','tx_profissional'),
                      $this->_schema);
        $select->where('cd_projeto  = ?',$cd_projeto,Zend_Db::INT_TYPE);
        $select->where('cd_proposta = ?',$cd_proposta,Zend_Db::INT_TYPE);
        $select->order('gq.dt_auditoria_qualidade DESC');

		if(!is_null($cd_gerencia_qualidade))
            $select->where('cd_gerencia_qualidade = ?',$cd_gerencia_qualidade,Zend_Db::INT_TYPE);

        return $this->fetchAll($select);
	}


	/**
	 * Método que salva as informações na tabela
	 * @autor: Wunilberto
	 * @since: 30/06/2009
	 */
	public function salvarGerenciaQualidade(array $arrGerenciaQualidade)
	{
		$erro = false;

		$novo = $this->createRow();
		$novo->cd_gerencia_qualidade  = $this->getNextValueOfField('cd_gerencia_qualidade');
	  	$novo->cd_projeto             = $arrGerenciaQualidade['cd_projeto'];
	  	$novo->cd_proposta            = $arrGerenciaQualidade['cd_proposta'];
	  	$novo->cd_profissional        = $arrGerenciaQualidade['cd_profissional_qualidade'];
	  	$novo->dt_auditoria_qualidade = (!empty ($arrGerenciaQualidade['dt_auditoria_qualidade'])) ? $arrGerenciaQualidade['dt_auditoria_qualidade'] : null;
	  	$novo->tx_fase_projeto        = $arrGerenciaQualidade['tx_fase_projeto'];
	  	
	  	if(!$novo->save()) {
	  		$erro = true;
	  	}
		return $erro;
	}
	
	/**
	 * Método que alterar as informações da tabela
	 * @autor: Wunilberto
	 * @since: 30/06/2009
	 */
	public function alteraGerenciaQualidade(array $arrGerenciaQualidade)
	{
		$erro = false;
		$where = array(
            "cd_gerencia_qualidade = ?" => $arrGerenciaQualidade['cd_gerencia_qualidade'],
            "cd_projeto = ?"            => $arrGerenciaQualidade['cd_projeto'],
            "cd_proposta = ?"           => $arrGerenciaQualidade['cd_proposta']
        );

		$arrUpdate['cd_profissional']        = $arrGerenciaQualidade['cd_profissional_qualidade'];
		$arrUpdate['dt_auditoria_qualidade'] = (!empty ($arrGerenciaQualidade['dt_auditoria_qualidade'])) ? $arrGerenciaQualidade['dt_auditoria_qualidade'] : null;
		$arrUpdate['tx_fase_projeto']        = $arrGerenciaQualidade['tx_fase_projeto'];
		unset($arrGerenciaQualidade);
		if(!$this->update($arrUpdate,$where)){
			$erro = true;
		}
		return $erro;
	}	

}