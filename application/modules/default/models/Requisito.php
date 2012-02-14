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

class Requisito extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_REQUISITO;
	protected $_primary  = array('cd_projeto','cd_requisito','dt_versao_requisito');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getRequisito( $cd_projeto , $st_fechamento_requisito=false )
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('req'=>$this->_name),
                      array('ni_ordem',
                            'cd_requisito',
                            'tx_requisito',
                            'tx_descricao_requisito',
                            'cd_projeto',
                            'ni_versao_requisito',
                            'dt_versao_requisito',
                            'st_tipo_requisito'=>new Zend_Db_Expr("CASE st_tipo_requisito WHEN 'N' THEN '".Base_Util::getTranslator('L_SQL_NAO_FUNCIONAL')."'
                                                                                                   ELSE '".Base_Util::getTranslator('L_SQL_FUNCIONAL'    )."' END"),
                            'st_requisito'=>new Zend_Db_Expr("CASE st_requisito
                                                                   WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_APROVADO'  )."'
                                                                   WHEN 'C' THEN '".Base_Util::getTranslator('L_SQL_COMPLETADO')."'
                                                                            ELSE '".Base_Util::getTranslator('L_SQL_SUBMETIDO' )."' END"),
                            'st_prioridade_requisito'=>new Zend_Db_Expr("CASE st_prioridade_requisito
                                                                              WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_ALTA' )."'
                                                                              WHEN 'B' THEN '".Base_Util::getTranslator('L_SQL_BAIXA')."'
                                                                                       ELSE '".Base_Util::getTranslator('L_SQL_MEDIA')."' END"),
                            'st_fechamento_requisito'=>new Zend_Db_Expr("CASE WHEN req.st_fechamento_requisito IS NULL THEN '".Base_Util::getTranslator('L_SQL_ABERTO')."'
                                                                                                                       ELSE '".Base_Util::getTranslator('L_SQL_FECHADO')."' END")),
                      $this->_schema);

        $subSelect = $this->select()
                          ->from($this,
                                 array('cd_requisito',
                                       'dt_versao_requisito'=>new Zend_Db_Expr('max(dt_versao_requisito)')))
                          ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                          ->group('cd_requisito');
        if( $st_fechamento_requisito ){
            $subSelect->where('st_fechamento_requisito IS NOT NULL');
        }

        $select->join(array('maxdat'=>$subSelect),
                      '(req.cd_requisito        = maxdat.cd_requisito) AND
                       (req.dt_versao_requisito = maxdat.dt_versao_requisito)',
                      array());
        $select->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        if( $st_fechamento_requisito ){
            $select->where('req.st_fechamento_requisito IS NOT NULL');
        }
        $select->order(array('req.ni_ordem',
                             'req.tx_requisito',
                             'st_tipo_requisito'));

        return $this->fetchAll($select)->toArray();
	}

	public function getRequisitoEspecifico( $cd_requisito, $ni_versao_requisito, $dt_versao_requisito=null, $cd_projeto = null )
	{
		$select = $this->select()
                       ->where('cd_requisito        = ?', $cd_requisito, Zend_Db::INT_TYPE)
                       ->where('ni_versao_requisito = ?', $ni_versao_requisito, Zend_Db::INT_TYPE);

		if(!is_null( $dt_versao_requisito )){
            $select->where('dt_versao_requisito = ?', $dt_versao_requisito);
		}
		if(!is_null( $cd_projeto )){
            $select->where('cd_projeto = ?', $cd_projeto);
		}

        return $this->fetchRow($select)->toArray();
	}

	public function getComboRequisito($cd_projeto, $comSelecione=false)
	{
		if($comSelecione === true){
			$arrRetorno[0] = Base_Util::getTranslator('L_VIEW_COMBO_SELECIONE');
		}

        $select = $this->select()
                       ->setIntegrityCheck(false);

        $select->from(array('req'=>$this->_name),
                      array('cd_requisito',
                            'tx_requisito' => new Zend_Db_Expr("tx_requisito {$this->concat()} ' (".Base_Util::getTranslator('L_SQL_VERSAO')." ' {$this->concat()} ni_versao_requisito {$this->concat()} ')'"),
                            'ni_versao_requisito',
                            'dt_versao_requisito'),
                      $this->_schema);
        $select->join(array('maxdat'=>$this->select()
                                           ->from($this,
                                                  array('cd_requisito',
                                                        'dt_versao_requisito'=>new Zend_Db_Expr('max(dt_versao_requisito)')))
                                           ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)
                                           ->group('cd_requisito')),
                      '(req.cd_requisito        = maxdat.cd_requisito) AND
                       (req.dt_versao_requisito = maxdat.dt_versao_requisito)',
                      array());

        $select->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);
        $select->order('tx_requisito');

        $rowSet = $this->fetchAll($select);
		foreach ($rowSet as $row) {
			$arrRetorno[$row->cd_requisito."|".$row->dt_versao_requisito."|".$row->ni_versao_requisito] = $row->tx_requisito;
		}

		return $arrRetorno;
	}

	public function getUltimaVersaoRequisito( $cd_projeto, $cd_requisito )
	{
        $select = $this->select()
                       ->from($this,
                              array('ni_versao_requisito' => new Zend_Db_Expr('max(ni_versao_requisito)'),
                                    'dt_versao_requisito' => new Zend_Db_Expr('max(dt_versao_requisito)')))
                       ->where('cd_requisito = ?', $cd_requisito, Zend_Db::INT_TYPE)
                       ->where('cd_projeto	 = ?', $cd_projeto,   Zend_Db::INT_TYPE);
        return $this->fetchAll($select)->toArray();
	}

	/**
	 * Método para recuperar o próximo nr de ordem para o requisito a ser inserido
	 * @param Integer $cd_projeto
	 * @return Integer
	 */
	public function getNextValueNiOrdemRequisito( $cd_projeto )
	{
        $select = $this->select()->setIntegrityCheck(false)
                       ->from($this,
                              array('ni_ordem' => new Zend_Db_Expr('max(ni_ordem)')))
                       ->where('cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE);

        $row = $this->fetchRow($select);

		$new = 0;
		if (is_null($row->ni_ordem)) {
			$new = 1;
		} else {
			$new = $row->ni_ordem + 1;
		}
		return $new;
	}

}