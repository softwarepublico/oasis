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

class OcorrenciaAdministrativa extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_OCORRENCIA_ADMINISTRATIVA;
	protected $_primary  = array('dt_ocorrencia_administrativa', 'cd_evento', 'cd_contrato');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getDadosOcorrenciaAdministrativa($dt_ocorrencia_administrativa, $cd_evento, $cd_contrato)
	{
        $select = $this->select()
                       ->where('dt_ocorrencia_administrativa = ?', $dt_ocorrencia_administrativa)
                       ->where('cd_evento   = ?', $cd_evento, Zend_Db::INT_TYPE)
                       ->where('cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);

        return $this->fetchRow($select)->toArray();
	}
	
	
	public function salvarOcorrenciaAdministrativa(array $arrDados)
	{
		$novo    	   		                = $this->createRow();
		$novo->cd_evento 		            = $arrDados['cd_evento_ocorrencia'];
		$novo->cd_contrato 		            = $arrDados['cd_contrato_objeto'];
		$novo->tx_ocorrencia_administrativa = $arrDados['tx_ocorrencia_administrativa'];
		$novo->dt_ocorrencia_administrativa = new Zend_Db_Expr("{$this->to_timestamp("'{$arrDados['dt_ocorrencia_administrativa']}'", 'YYYY-MM-DD HH24:MI:SS')}");
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function alterarOcorrenciaAdministrativa(array $arrDados)
	{
		$where = array("cd_evento   = ?" => $arrDados['cd_evento_ocorrencia'],
                       "cd_contrato = ?" => $arrDados['cd_contrato_objeto'],
					   new Zend_Db_Expr("{$this->to_char('dt_ocorrencia_administrativa', 'DD/MM/YYYY HH24:MI:SS')} = '{$arrDados['dt_ocorrencia_administrativa']}'")
                       );
				  
		$row = array();
		$row['dt_ocorrencia_administrativa'] = new Zend_Db_Expr("{$this->to_timestamp("'{$arrDados['dt_ocorrencia_administrativa']}'", 'DD/MM/YYYY HH24:MI:SS')}");
		$row['cd_evento'                   ] = $arrDados['cd_evento_ocorrencia'];
		$row['cd_contrato'                 ] = $arrDados['cd_contrato_objeto'];
		$row['tx_ocorrencia_administrativa'] = $arrDados['tx_ocorrencia_administrativa'];

		if($this->update($row,$where)){
			return true;
		} else {
			return false;
		}
	}
	
	public function excluirOcorrenciaAdministrativa(array $arrDados)
	{
		$where = array("cd_evento   = ?" => $arrDados['cd_evento'],
                       "cd_contrato = ?" => $arrDados['cd_contrato'],
                       "dt_ocorrencia_administrativa = ?" => $arrDados['dt_ocorrencia_administrativa']);
		if($this->delete($where)){
			return true;
		} else {
			return false;
		}
	}	

    /**
     *
     * @param int $mes
     * @param int $ano
     * @param int $cd_contrato
     * @return Zend_Db_Table_RowSet
     */
	public function getListaOcorrenciaAdministrativa($mes, $ano, $cd_contrato = null)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('oa'=>$this->_name),
                      array('dt_ocorrencia_administrativa',
                            'cd_contrato',
                            'cd_evento'),
                      $this->_schema);
        $select->join(array('eve'=>KT_B_EVENTO),
                      '(oa.cd_evento = eve.cd_evento)',
                      'tx_evento',
                      $this->_schema);
        $select->join(array('con'=>KT_S_CONTRATO),
                      '(oa.cd_contrato = con.cd_contrato)',
                      'tx_numero_contrato',
                      $this->_schema);
        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(con.cd_contrato = obj.cd_contrato)',
                      'tx_objeto',
                      $this->_schema);
        $select->where(new Zend_Db_Expr("{$this->to_char('oa.dt_ocorrencia_administrativa', 'YYYY')} = '{$ano}'"));
        $select->where(new Zend_Db_Expr("{$this->to_char('oa.dt_ocorrencia_administrativa', 'MM')} = '{$mes}'"));

		if(!is_null($cd_contrato)){
            $select->where('oa.cd_contrato = ?', $cd_contrato, Zend_Db::INT_TYPE);
        }

        return $this->fetchAll($select);
	}
}