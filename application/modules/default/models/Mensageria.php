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

class Mensageria extends Base_Db_Table_Abstract 
{
	protected $_name 	 = KT_S_MENSAGERIA;
	protected $_primary  = 'cd_mensageria';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getAllMensagens($mes, $ano, $comEstatistica = false)
	{
        $select = $this->select()
                       ->setIntegrityCheck(false);
        $select->from(array('m'=>$this->_name),
                      array('cd_mensageria',
                            'cd_objeto',
                            'cd_perfil',
                            'tx_mensagem',
                            'dt_postagem',
                            'dt_encerramento'),
                      $this->_schema);
        $select->join(array('oc'=>KT_S_OBJETO_CONTRATO),
                      '(m.cd_objeto = oc.cd_objeto)',
                      'tx_objeto',
                      $this->_schema);
        $select->joinLeft(array('p'=>KT_B_PERFIL),
                          '(m.cd_perfil = p.cd_perfil)',
                          array('tx_perfil'=>new Zend_Db_Expr("CASE WHEN p.tx_perfil IS NULL THEN '".Base_Util::getTranslator('L_SQL_TODOS')."' ELSE p.tx_perfil END")),
                          $this->_schema);
        $select->where(new Zend_Db_Expr("{$this->to_char('dt_postagem', 'MM')}   = ?"), $mes);
        $select->where(new Zend_Db_Expr("{$this->to_char('dt_postagem', 'YYYY')} = ?"), $ano);

		$res = $this->fetchAll($select)->toArray();

		if( $comEstatistica === true ){
			foreach( $res as $key=>$msg ){
				$estatistica				= $this->getDadosEstatisticosMensagem($msg['cd_mensageria']);
				$res[$key]['estatistica']	= $estatistica[0];
			}
		}
		return $res;
	}

	public function getDadosEstatisticosMensagem( $cd_mensageria )
	{
        $table = KT_A_PROFISSIONAL_MENSAGERIA;
        if(!is_null($this->_schema)){
            $table = $this->_schema.'.'.$table;
        }

        // Este sql não foi possível fazer com zend_select

		$sql = "SELECT
					enviadas,
					lidas,
					(lidas*100)/enviadas{$this->concat()}'%' as porcentagem_lidas
				FROM (
					SELECT (SELECT
							COUNT(*) as qtdLeitura
						FROM
							{$table}
						WHERE
							cd_mensageria = {$cd_mensageria}
						) AS enviadas,

					(SELECT
						COUNT(*)
					 FROM
						{$table}
					 WHERE
						cd_mensageria = {$cd_mensageria}
					 AND
						dt_leitura_mensagem IS NOT NULL
					) AS lidas
					  ) AS t";

		return $this->getDefaultAdapter()->fetchAll($sql);
	}

    /**
     * Método utilizado para recuperar os dados de um mensagem
     * 
     * @param int $cd_mensageria
     * @return Zend_Db_Table_Row
     */
	public function getMensagemEspecifica($cd_mensageria)
	{
        return $this->fetchRow($this->select()->where('cd_mensageria = ?', $cd_mensageria, Zend_Db::INT_TYPE));
	}

	public function getMsgObjetoPerfilPeriodo($cd_objeto, $cd_profissional, $isObjeto = false)
	{

		$timestamp = $this->to_timestamp("'".date("Y-m-d H:i:s")."'", 'YYYY-MM-DD HH24:MI:SS');

		$select = $this->select()
                       ->setIntegrityCheck(false);
		$select->from(array('msg'=>$this->_name),
                      array('msg.cd_mensageria','msg.tx_mensagem'),
                      $this->_schema)
               ->join(array('pm'=>KT_A_PROFISSIONAL_MENSAGERIA),
                      '(msg.cd_mensageria = pm.cd_mensageria)',
                      array(),
                      $this->_schema)
               ->where('msg.cd_objeto      = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('pm.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE)
               ->where('pm.dt_leitura_mensagem IS NULL')
               ->where("{$timestamp} BETWEEN msg.dt_postagem AND dt_encerramento")
               ->order('msg.cd_mensageria');

		$resultado = $this->fetchAll($select);

		if($isObjeto === false){
			$resultado = $resultado->toArray();
		}
		return $resultado;
	}

	public function getMsgLidaProfissional($cd_objeto, $cd_profissional, array $arrData, $isObjeto = false)
	{
		$select = $this->select()
                       ->setIntegrityCheck(false);
		$select->from(array('msg'=>$this->_name),
                      array('msg.cd_mensageria','msg.tx_mensagem'),
                      $this->_schema)
               ->join(array('pm'=>KT_A_PROFISSIONAL_MENSAGERIA),
                      '(msg.cd_mensageria = pm.cd_mensageria)',
                      'dt_leitura_mensagem',
                      $this->_schema)
               ->where('msg.cd_objeto      = ?', $cd_objeto, Zend_Db::INT_TYPE)
               ->where('pm.cd_profissional = ?', $cd_profissional, Zend_Db::INT_TYPE)
               ->where("msg.dt_postagem   >= ?", $arrData['dt_inicial']);

		if( !is_null($arrData['dt_final']) ){
			$select->where("msg.dt_postagem <= ?", $arrData['dt_final']);
		}
		
		$select->order('msg.cd_mensageria');

		$resultado = $this->fetchAll($select);

		if($isObjeto === false){
			$resultado = $resultado->toArray();
		}
		return $resultado;
	}
}