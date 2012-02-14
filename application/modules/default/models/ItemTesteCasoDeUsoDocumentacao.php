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

class ItemTesteCasoDeUsoDocumentacao extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_ITEM_TESTE_CASO_DE_USO_DOC;
	protected $_primary  = array("cd_arq_item_teste_caso_de_uso", "cd_item_teste", "cd_modulo", "cd_projeto", "cd_caso_de_uso", "dt_versao_caso_de_uso", "cd_item_teste_caso_de_uso");
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getArquivoDonwload($codigo)
	{
        $rs = $this->fetchAll(array("cd_arq_item_teste_caso_de_uso = ?" => $codigo))->toArray();
        $ext = explode('.',$rs[0]["tx_nome_arq_teste_caso_de_uso"]);
        $retorno['extensao'] = $ext[1];
        $retorno['nome'    ] = $rs[0]["tx_nome_arq_teste_caso_de_uso"];
        $retorno['caminho' ] = $rs[0]["tx_arq_item_teste_caso_de_uso"];

        return $retorno;
	}

	public function getGrid(array $params)
	{
        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('a'=>$this->_name),
                      array('cd_arquivo_item_teste_default'=>'cd_arq_item_teste_caso_de_uso',
//                            'data'=>new Zend_Db_Expr("TO_CHAR(TO_DATE(SUBSTR(a.tx_nome_arq_teste_caso_de_uso,1,14),'YYYYMMDDHH24MISS'),'DD/MM/YYYY')"),
                            'data'=>"tx_nome_arq_teste_caso_de_uso"),
                      $this->_schema);
        $select->join(array('b'=>KT_B_TIPO_DOCUMENTACAO),
                      '(a.cd_tipo_documentacao = b.cd_tipo_documentacao)',
                      'tx_tipo_documentacao',
                      $this->_schema);
        $select->where('cd_caso_de_uso = ?', $params['cd_caso_de_uso'], Zend_Db::INT_TYPE);
        $select->where('cd_projeto     = ?', $params['cd_projeto'], Zend_Db::INT_TYPE);
        $select->where('cd_modulo      = ?', $params['cd_modulo'], Zend_Db::INT_TYPE);
        $select->where('cd_item_teste_caso_de_uso = ?', $params['cd_item_teste_caso_de_uso'], Zend_Db::INT_TYPE);
        $select->where('dt_versao_caso_de_uso     = ?', $params['dt_versao_caso_de_uso']);
        $select->order('tx_tipo_documentacao');

        return $this->fetchAll($select)->toArray();

/*
        $sql = "select
                    b.tx_tipo_documentacao,
                    to_char(to_date(substr(a.tx_nome_arq_teste_caso_de_uso,1,14),'YYYYMMDDHH24MISS'),'DD/MM/YYYY') as data,
                    a.cd_arq_item_teste_caso_de_uso as cd_arquivo_item_teste_default
                from
                    {$this->_schema}.{$this->_name} a
                join
                    {$this->_schema}.".KT_B_TIPO_DOCUMENTACAO." b
                    on
                        a.cd_tipo_documentacao = b.cd_tipo_documentacao
                where
                    cd_caso_de_uso = {$params['cd_caso_de_uso']}
                and
                    cd_projeto = {$params['cd_projeto']}
                and
                    cd_modulo = {$params['cd_modulo']}
                and
                    dt_versao_caso_de_uso = '{$params['dt_versao_caso_de_uso']}'
                and
                    cd_item_teste_caso_de_uso = {$params['cd_item_teste_caso_de_uso']}
                order by
                    data";

 	    return $this->getDefaultAdapter()->fetchAll($sql);
 *
 */
	}
}