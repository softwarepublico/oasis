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

class RelatorioDiversoAtaDeReuniaoGeral extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;
	
	public function getDadosAtaReuniaoGeral($cd_objeto, $cd_reuniao_geral = null)
	{
        $objReuniaoGeral = new ReuniaoGeral();
        $select     = $objReuniaoGeral->select()
                                 ->setIntegrityCheck(false);

        $select->from(array('reun'=>KT_S_REUNIAO_GERAL),
                      array('cd_reuniao_geral',
                            'tx_pauta',
                            'tx_participantes',
                            'tx_ata',
                            'tx_local_reuniao',
                            'dt_reuniao_order'=>'dt_reuniao',
                            'dt_reuniao'=>new Zend_Db_Expr("{$this->to_char('reun.dt_reuniao', 'DD/MM/YYYY')}")),
                      $this->_schema);

        $select->join(array('obj'=>KT_S_OBJETO_CONTRATO),
                      '(reun.cd_objeto = obj.cd_objeto)',
                      array('cd_objeto',
                            'tx_objeto'),
                      $this->_schema);

        $select->where('reun.cd_objeto = ?', $cd_objeto, Zend_Db::INT_TYPE);

		if(!is_null($cd_reuniao_geral)){
            $select->where('cd_reuniao_geral = ?', $cd_reuniao_geral, Zend_Db::INT_TYPE);
		}

        $select->order('dt_reuniao_order');
        return $this->fetchAll($select)->toArray();
	}
}
