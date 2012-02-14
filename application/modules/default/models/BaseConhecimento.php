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

class BaseConhecimento extends Base_Db_Table_Abstract
{
	protected $_name    = KT_S_BASE_CONHECIMENTO;
	protected $_primary = array('cd_base_conhecimento', 'cd_area_conhecimento');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function salvarBaseConhecimento( array $arrBaseConhecimento)
    {
		$novo = $this->createRow();
		$novo->cd_base_conhecimento = $this->getNextValueOfField('cd_base_conhecimento');
		$novo->cd_area_conhecimento = $arrBaseConhecimento['cd_area_conhecimento'];
		$novo->tx_assunto           = $arrBaseConhecimento['tx_assunto'];
		$novo->tx_problema          = $arrBaseConhecimento['tx_problema'];
		$novo->tx_solucao           = $arrBaseConhecimento['tx_solucao'];
		//$novo->cd_profissional      = $arrBaseConhecimento['cd_profissional'];
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}
	
	public function getDadosBaseConhecimento($cd_area_conhecimento = null, $tx_assunto = null, $tipo_consulta = null)
    {
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('bc'=>$this->_name),
                      array('cd_base_conhecimento',
                            'cd_area_conhecimento',
                            'tx_assunto',
                            'tx_problema',
                            'tx_solucao'),
                      $this->_schema);
        $select->join(array('ac'=>KT_B_AREA_CONHECIMENTO),
                      '(bc.cd_area_conhecimento = ac.cd_area_conhecimento)',
                      'tx_area_conhecimento',
                      $this->_schema);
        $select->order('bc.tx_assunto');

		if(!is_null($cd_area_conhecimento)){
            $select->where('bc.cd_area_conhecimento = ?', $cd_area_conhecimento, Zend_Db::INT_TYPE);
		}

		if(!is_null($tx_assunto)){

			$arrAssunto  = explode(" ",$tx_assunto);

			foreach ($arrAssunto as $assunto){
				$arrTexto[] = " UPPER(bc.tx_assunto) LIKE '%".strtoupper($assunto)."%'";
			}

			$strTexto = implode(" {$tipo_consulta} ", $arrTexto);

            $select->where(new Zend_Db_Expr($strTexto));
		}

        return $this->fetchAll($select)->toArray();

/*
		$itens      = "";
		$strAssunto = "";
		$where      = "where ";
		if(!is_null($cd_area_conhecimento)){
			$itens[] = " bc.cd_area_conhecimento = {$cd_area_conhecimento}";
		}

		if(!is_null($tx_assunto)){

			$strAssunto .= " (";
			$arrAssunto  = explode(" ",$tx_assunto);

			foreach ($arrAssunto as $assunto){
				$arrTexto[] = " UPPER(bc.tx_assunto) LIKE '%".strtoupper($assunto)."%'";
			}

			$strTexto = implode(" {$tipo_consulta} ", $arrTexto);
			$strAssunto     .= $strTexto;
			$strAssunto     .= ")";

			$itens[] = $strAssunto;
		}


		if($itens == ""){
			$where = "";
		} else {
			$itens = implode(' and ', $itens);
			$where .= $itens;
		}

		$sql = " select
                       bc.cd_base_conhecimento,
				       bc.cd_area_conhecimento,
				       bc.tx_assunto,
				       bc.tx_problema,
				       bc.tx_solucao,
				       ac.tx_area_conhecimento
				from {$this->_schema}.{$this->_name}       as bc
				inner join {$this->_schema}.".KT_B_AREA_CONHECIMENTO." as ac ON (bc.cd_area_conhecimento = ac.cd_area_conhecimento)
				{$where}
				order by bc.tx_assunto ASC";
*/
	}

	public function getSolucaoBaseConhecimento($cd_base_conhecimento)
    {
		$select = $this->select()
                       ->setIntegrityCheck(false);
		$select->from (array('bc'=>$this->_name),
                       array('ac.tx_area_conhecimento','bc.tx_assunto','bc.tx_problema','bc.tx_solucao'),
                       $this->_schema)
				->join(array('ac'=>KT_B_AREA_CONHECIMENTO),
                       'bc.cd_area_conhecimento = ac.cd_area_conhecimento',
                       array(),
                       $this->_schema)
				->where('bc.cd_base_conhecimento = ?', $cd_base_conhecimento, Zend_Db::INT_TYPE);

		return $this->fetchRow($select)->toArray();
	}
}