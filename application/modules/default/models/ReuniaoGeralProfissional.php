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

class ReuniaoGeralProfissional extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_A_REUNIAO_GERAL_PROFISSIONAL;
	protected $_primary  = array('cd_reuniao_geral','cd_objeto','cd_profissional');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function pesquisaProfissionalForaReuniaoGeral($cd_reuniao_geral)
	{
        $objTable = new Profissional();

        $select = $objTable->select()->setIntegrityCheck(false);
        $select->from(KT_S_PROFISSIONAL,
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);

        $select->where('cd_profissional NOT IN ? ', $this->_montaSubSelectPesquisaProfissional($cd_reuniao_geral));

        $select->where('st_inativo is null');
        $select->where('cd_profissional <> 0');
        $select->order('tx_profissional');

        return $this->fetchAll($select)->toArray();
	}

	public function pesquisaProfissionalNoReuniaoGeral($cd_reuniao_geral)
	{
        $objTable = new Profissional();

        $select = $objTable->select()->setIntegrityCheck(false);
        $select->from(KT_S_PROFISSIONAL,
                      array('cd_profissional',
                            'tx_profissional'),
                      $this->_schema);

        $select->where('cd_profissional IN ? ', $this->_montaSubSelectPesquisaProfissional($cd_reuniao_geral));
        $select->where('st_inativo is null');
        $select->order('tx_profissional');

        return $this->fetchAll($select)->toArray();
	}

    private function _montaSubSelectPesquisaProfissional($cd_reuniao_geral)
    {
        $select = $this->select()
                       ->from($this,'cd_profissional')
                       ->where('cd_reuniao_geral = ?', $cd_reuniao_geral, Zend_Db::INT_TYPE);

        return $select;
    }

}