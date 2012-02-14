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

class DefinicaoProcesso extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_DEFINICAO_PROCESSO;
	protected $_primary  = array('cd_perfil', 'cd_funcionalidade', 'st_definicao_processo');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getListaFuncionalidadeDefinicaoProcesso($st_definicao_processo, $cd_perfil, $tipo)
	{
		$condicao = ($tipo == 0) ? "IS NULL" : "IS NOT NULL";

        $select = $this->select()->setIntegrityCheck(false);
        $select->from(array('f'=>KT_B_FUNCIONALIDADE),
                      array('cd_funcionalidade','tx_funcionalidade'),
                      $this->_schema);
        $select->joinLeft(array('dp'=>$this->select()
                                           ->from($this,'cd_funcionalidade')
                                           ->where('st_definicao_processo = ?', $st_definicao_processo)
                                           ->where('cd_perfil = ?', $cd_perfil, Zend_Db::INT_TYPE)),
                          '(f.cd_funcionalidade = dp.cd_funcionalidade)',
                          array());
		$select->where("dp.cd_funcionalidade {$condicao}");
		$select->where("st_funcionalidade = 'I'");
		$select->order('tx_codigo_funcionalidade','tx_funcionalidade');

        return $this->fetchAll($select)->toArray();
	}
}