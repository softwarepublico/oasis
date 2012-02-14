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

class RelatorioProjetoBaseline extends Base_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

    public function init()
    {
        parent::init();
        Zend_Loader::loadClass('ItemControleBaselineController',Base_Util::baseUrlModule('default', 'controllers'));
    }


	public function getDadosRequisito( $cd_projeto, $dt_baseline )
	{

        $objTable = new BaselineItemControle();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('bic'=>KT_A_BASELINE_ITEM_CONTROLE),
                      array('*',
//                            'dt_versao_item_conteudo'=>new Zend_Db_Expr("to_char(bic.dt_versao_item_controlado, 'DD/MM/YYYY HH24:MI:SS')")),
                            'dt_versao_item_conteudo'=>'dt_versao_item_controlado'),
                      $this->_schema);

        $select->join(array('req'=>KT_S_REQUISITO),
                      '(req.cd_requisito = bic.cd_item_controlado) and (req.dt_versao_requisito = bic.dt_versao_item_controlado)',
                      array('tx_conteudo'=>'tx_requisito',
                            'ni_versao_item_conteudo'=>'ni_versao_requisito'),
                      $this->_schema);

        $select->join(array('b'=>KT_S_BASELINE),
                      '(b.cd_projeto = bic.cd_projeto) and (b.dt_baseline = bic.dt_baseline)',
                      array(),
                      $this->_schema);

        $select->where('bic.cd_projeto = ?',
                       $cd_projeto,
                       Zend_Db::INT_TYPE);
        $select->where('bic.cd_item_controle_baseline = ?',
                       ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REQUISITO,
                       Zend_Db::INT_TYPE);
        $select->where(new Zend_Db_Expr("{$this->to_char('bic.dt_baseline', 'YYYY-MM-DD HH24:MI:SS')} = '{$dt_baseline}'"));

        $select->order('req.tx_requisito');

        return $objTable->fetchAll($select)->toArray();

	}
	
	public function getDadosRegraDeNegocio( $cd_projeto, $dt_baseline )
	{
        $objTable = new BaselineItemControle();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('bic'=>KT_A_BASELINE_ITEM_CONTROLE),
                      array('*',
                            'dt_versao_item_conteudo'=>'dt_versao_item_controlado'),
                      $this->_schema);

        $select->join(array('rn'=>KT_S_REGRA_NEGOCIO),
                      '(rn.cd_regra_negocio = bic.cd_item_controlado) and (rn.dt_regra_negocio = bic.dt_versao_item_controlado)',
                      array('tx_conteudo'=>'tx_regra_negocio',
                            'ni_versao_item_conteudo'=>'ni_versao_regra_negocio'),
                      $this->_schema);

        $select->join(array('b'=>KT_S_BASELINE),
                      '(b.cd_projeto = bic.cd_projeto) and (b.dt_baseline = bic.dt_baseline)',
                      array(),
                      $this->_schema);

        $select->where('bic.cd_projeto = ?',
                       $cd_projeto,
                       Zend_Db::INT_TYPE);
        $select->where('bic.cd_item_controle_baseline = ?',
                       ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_REGRA_NEGOCIO,
                       Zend_Db::INT_TYPE);
        $select->where(new Zend_Db_Expr("{$this->to_char('bic.dt_baseline', 'YYYY-MM-DD HH24:MI:SS')} = '{$dt_baseline}'"));

        $select->order('rn.tx_regra_negocio');

        return $objTable->fetchAll($select)->toArray();
	}
	
	public function getDadosCasoDeUso( $cd_projeto, $dt_baseline )
	{
        $objTable = new BaselineItemControle();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('bic'=>KT_A_BASELINE_ITEM_CONTROLE),
                      array('*',
                            'dt_versao_item_conteudo'=>'dt_versao_item_controlado'),
                      $this->_schema);

        $select->join(array('uc'=>KT_S_CASO_DE_USO),
                      '(uc.cd_caso_de_uso = bic.cd_item_controlado) and (uc.dt_versao_caso_de_uso = bic.dt_versao_item_controlado)',
                      array('tx_conteudo'=>'tx_caso_de_uso',
                            'ni_versao_item_conteudo'=>'ni_versao_caso_de_uso'),
                      $this->_schema);

        $select->join(array('b'=>KT_S_BASELINE),
                      '(b.cd_projeto = bic.cd_projeto) and (b.dt_baseline = bic.dt_baseline)',
                      array(),
                      $this->_schema);

        $select->where('bic.cd_projeto = ?',
                       $cd_projeto,
                       Zend_Db::INT_TYPE);
        $select->where('bic.cd_item_controle_baseline = ?',
                       ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_CASO_USO,
                       Zend_Db::INT_TYPE);
        $select->where(new Zend_Db_Expr("{$this->to_char('bic.dt_baseline', 'YYYY-MM-DD HH24:MI:SS')} = '{$dt_baseline}'"));

        $select->order('uc.tx_caso_de_uso');

        return $objTable->fetchAll($select)->toArray();
	}

	public function getDadosProposta( $cd_projeto, $dt_baseline )
	{
        $objTable = new BaselineItemControle();
        $select   = $objTable->select()
                             ->setIntegrityCheck(false);

        $select->from(array('bic'=>KT_A_BASELINE_ITEM_CONTROLE),
                      array('*',
                            'dt_versao_item_conteudo'=>'dt_versao_item_controlado'),
                      $this->_schema);

        $select->join(array('prop'=>KT_S_PROPOSTA),
                      '(prop.cd_projeto = bic.cd_projeto) and (prop.cd_proposta = bic.cd_item_controlado)',
                      array('tx_conteudo'=>new Zend_Db_Expr("'Proposta N. ' {$this->concat()} prop.cd_proposta")),
                      $this->_schema);

        $select->where('bic.cd_projeto = ?',
                       $cd_projeto,
                       Zend_Db::INT_TYPE);
        $select->where('bic.cd_item_controle_baseline = ?',
                       ItemControleBaselineController::K_ITEM_CONTROLE_BASELINE_PROPOSTA,
                       Zend_Db::INT_TYPE);
        $select->where(new Zend_Db_Expr("{$this->to_char('bic.dt_baseline', 'YYYY-MM-DD HH24:MI:SS')} = '{$dt_baseline}'"));

        $select->order(new Zend_Db_Expr("'Proposta N. ' {$this->concat()} prop.cd_proposta"));

        return $objTable->fetchAll($select)->toArray();
	}
}	