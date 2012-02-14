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

class RegraNegocio extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_REGRA_NEGOCIO;
	protected $_primary  = array('cd_regra_negocio','dt_regra_negocio','cd_projeto_regra_negocio');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getNextVersion($cd_regra_negocio)
	{
		$nextVersion = 1;
	    if( !empty($cd_regra_negocio) ){
            $arrWhere['cd_regra_negocio = ?'       ] = $cd_regra_negocio;
            $arrWhere['ni_versao_regra_negocio = ?'] = $this->select()
                                                            ->from($this, new Zend_Db_Expr('max(ni_versao_regra_negocio)'))
                                                            ->where('cd_regra_negocio         = ?', $cd_regra_negocio, Zend_Db::INT_TYPE);
		    $row = $this->fetchRow($arrWhere);
		    if( !is_null($row) ){
		        $version = (int) $row->ni_versao_regra_negocio;
                $nextVersion = $version + 1;
		    }
        }
		return $nextVersion;
	}

	public function getRegraNegocioWithLastVersion($cd_projeto_regra_negocio, $cd_regra_negocio=false, $st_fechamento_regra_negocio=false, $st_regra_negocio=false)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from($this,
                      array('cd_regra_negocio',
                            'dt_regra_negocio',
                            'cd_projeto_regra_negocio',
                            'tx_regra_negocio',
                            'tx_descricao_regra_negocio',
                            'st_regra_negocio',
                            'ni_versao_regra_negocio',
                            'st_fechamento_regra_negocio',
                            'dt_fechamento_regra_negocio',
                            'dt_regra_negocio_convert'=>new Zend_Db_Expr("{$this->to_char('dt_regra_negocio', 'DD/MM/YYYY HH24:MI:SS')}"),
                            'st_regra_negocio_desc'=>new Zend_Db_Expr("CASE st_regra_negocio WHEN 'A' THEN '".Base_Util::getTranslator('L_SQL_ATIVA')."'
                                                                                                      ELSE '".Base_Util::getTranslator('L_SQL_INATIVA')."' END"),
                            'st_fechamento_regra_negocio_desc'=>new Zend_Db_Expr("CASE WHEN st_fechamento_regra_negocio IS NULL THEN '".Base_Util::getTranslator('L_SQL_ABERTA')."'
                                                                                                                                ELSE '".Base_Util::getTranslator('L_SQL_FECHADA')."' END"),
                            'ni_ordem_regra_negocio'))
               ->order('tx_regra_negocio');

        $subSelect = $this->select()
                          ->from(array('aux'=>$this->_name),
                                 new Zend_Db_Expr('MAX(aux.ni_versao_regra_negocio)'),
                                 $this->_schema);

		if( !empty($cd_projeto_regra_negocio) ){
            $select->where('cd_projeto_regra_negocio = ?', $cd_projeto_regra_negocio, Zend_Db::INT_TYPE);

            $subSelect->where('aux.cd_projeto_regra_negocio = ?', $cd_projeto_regra_negocio, Zend_Db::INT_TYPE);
            $subSelect->where("aux.cd_regra_negocio = {$this->_name}.cd_regra_negocio");

    		if( $cd_regra_negocio ){
                $select->where('cd_regra_negocio = ?', $cd_regra_negocio, Zend_Db::INT_TYPE);
                $subSelect->where('aux.cd_regra_negocio = ?', $cd_regra_negocio, Zend_Db::INT_TYPE);
            }
    		if( $st_fechamento_regra_negocio ){
    		    if( $st_fechamento_regra_negocio === 'S' ){
                    $select->where('st_fechamento_regra_negocio = ?', 'S');
                    $subSelect->where('aux.st_fechamento_regra_negocio = ?', 'S');
                } else {
                    $select->where('st_fechamento_regra_negocio IS NULL');
                    $subSelect->where('aux.st_fechamento_regra_negocio IS NULL');
                }
    		}
    		if( $st_regra_negocio ){
                $select->where('st_regra_negocio = ?', $st_regra_negocio);
                $subSelect->where('aux.st_regra_negocio = ?', $st_regra_negocio);
    		}
            $select->where('ni_versao_regra_negocio = ?', $subSelect);

            return $this->fetchAll($select)->toArray();
        } else {
            return Base_Util::getTranslator('L_MSG_ERRO_SEM_CODIGO_PROJETO');
        }
	}

    /**
     * Método utilizado para atualizar o status e a descrição dar regra de negócio
     *
     * @param array $arrUpdate
     * @param int $cd_regra_negocio
     * @param int $cd_projeto_regra_negocio
     * @return int
     */
    public function atualizaRegraNegocio(array $arrUpdate, $cd_regra_negocio, $cd_projeto_regra_negocio )
    {
        $arrWhere['cd_regra_negocio = ?'        ] = $cd_regra_negocio;
        $arrWhere['cd_projeto_regra_negocio = ?'] = $cd_projeto_regra_negocio;
        $arrWhere['dt_regra_negocio = ?'        ] = $this->select()
                                                         ->from($this, new Zend_Db_Expr('max(dt_regra_negocio)'))
                                                         ->where('cd_regra_negocio         = ?', $cd_regra_negocio, Zend_Db::INT_TYPE)
                                                         ->where('cd_projeto_regra_negocio = ?', $cd_projeto_regra_negocio, Zend_Db::INT_TYPE);

        return $this->update($arrUpdate, $arrWhere);
    }

	public function getUltimaVersaoRegraNegocio( $cd_regra_negocio , $cd_projeto_regra_negocio=false)
	{
        $select = $this->select()
                       ->from($this,
                              array('cd_regra_negocio',
                                    'dt_regra_negocio'=>new Zend_Db_Expr('max(dt_regra_negocio)')))
                       ->where('cd_regra_negocio = ?', $cd_regra_negocio, Zend_Db::INT_TYPE)
                       ->group('cd_regra_negocio');

        if( $cd_projeto_regra_negocio ){
            $select->where('cd_projeto_regra_negocio = ?', $cd_projeto_regra_negocio, Zend_Db::INT_TYPE);
        }

        return $this->fetchAll($select)->toArray();
	}

	public function getRegraNegocioAtivaUltimaVersao($cd_projeto_regra_negocio)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('rn'=>$this->_name),
                      array('cd_regra_negocio',
                            'tx_regra_negocio',
                            'ni_versao_regra_negocio',
                            'st_fechamento_regra_negocio' => new Zend_Db_Expr("CASE WHEN rn.st_fechamento_regra_negocio IS NULL THEN '".Base_Util::getTranslator('L_SQL_ABERTA' )."'
                                                                                                                                ELSE '".Base_Util::getTranslator('L_SQL_FECHADA')."' END"),
                            'dt_regra_negocio'),
                      $this->_schema);
        $select->join(array('dados'=>$this->select()
                                          ->from($this,
                                                 array('cd_regra_negocio',
                                                       'dt_regra_negocio' => new Zend_Db_Expr('MAX(dt_regra_negocio)')))
                                          ->where('cd_projeto_regra_negocio = ?', $cd_projeto_regra_negocio, Zend_Db::INT_TYPE)
                                          ->where('st_regra_negocio			= ?', 'A')
                                          ->group('cd_regra_negocio')),
                      '(rn.cd_regra_negocio = dados.cd_regra_negocio) AND
		 			  (rn.dt_regra_negocio = dados.dt_regra_negocio)',
                      array());
        $select->where('rn.cd_projeto_regra_negocio = ?', $cd_projeto_regra_negocio, Zend_Db::INT_TYPE);
        $select->where('rn.st_regra_negocio			= ?', 'A');
        $select->order('rn.tx_regra_negocio');

        return $this->fetchAll($select)->toArray();
	}

	/**
	 * Método para recuperar o próximo nr de ordem para a regra de negocio a ser inserida
	 * @param integer $cd_projeto
	 * @return integer
	 */
	public function getNextValueNiOrdemRegraDeNegocio( $cd_projeto )
	{
        $select = $this->select()
                       ->from($this,
                              array('ni_ordem_regra_negocio'=> new Zend_Db_Expr('MAX(ni_ordem_regra_negocio)')))
                       ->where('cd_projeto_regra_negocio = ?', $cd_projeto, Zend_Db::INT_TYPE);

		$row = $this->fetchRow($select);

		$new = 0;
		if (is_null($row->ni_ordem_regra_negocio)) {
			$new = 1;
		} else {
			$new = $row->ni_ordem_regra_negocio + 1;
		}
		return $new;
	}
}