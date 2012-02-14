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

class RelatorioProjetoRegistroHistorico extends Zend_Db_Table_Abstract
{
	protected $_schema = K_SCHEMA;

	public function getProjetoMensal(array $arrParam)
	{
        $objTable = new Projeto();

        $select = $objTable->select()
                           ->distinct()
                           ->setIntegrityCheck(false);

        $select->from(array('parc'=>KT_S_PARCELA),
                      array('cd_projeto',
                            'cd_proposta',
                            'ni_horas_parcela'),
                      $this->_schema);
        $select->join(array('proj'=>KT_S_PROJETO),
                      '(parc.cd_projeto = proj.cd_projeto)',
                      array('tx_sigla_projeto'),
                      $this->_schema);
        $select->join(array('contproj'=>KT_A_CONTRATO_PROJETO),
                      '(proj.cd_projeto = contproj.cd_projeto)',
                      array(),
                      $this->_schema);
        $select->where('contproj.cd_contrato    = ?',$arrParam['cd_contrato'],Zend_Db::INT_TYPE);
        $select->where('ni_ano_previsao_parcela = ?',$arrParam['ano'],Zend_Db::INT_TYPE);
        $select->where("ni_mes_previsao_parcela between {$arrParam['mes']} and {$arrParam['mes']}");

        $select->order('tx_sigla_projeto');
        
        return $objTable->fetchAll($select);
	}	
	
	public function getQuantHistorico(array $arrParam)
	{
        $objTable = new Historico();
        $select   = $objTable->select();

        $select->from(array('hist'=>KT_S_HISTORICO),
                      array('cd_profissional',
                            'quant_projeto'=>new Zend_Db_Expr('count(hist.cd_projeto)')),
                      $this->_schema);

        $select->where('hist.cd_projeto  = ?',$arrParam['cd_projeto'],Zend_Db::INT_TYPE);
        $select->where('hist.cd_proposta = ?',$arrParam['cd_proposta'],Zend_Db::INT_TYPE);
        $select->where("'{$arrParam['dtMesInicio']}' BETWEEN hist.dt_inicio_historico AND hist.dt_fim_historico OR hist.dt_fim_historico between '{$arrParam['dtMesInicio']}' and '{$arrParam['dtMesFim']}'");
        $select->group('cd_profissional');

        $arrQuantHistorico  = $objTable->fetchAll($select)->toArray();
        $quant_profissional = count($arrQuantHistorico);
        if($quant_profissional > 0){
            $quant_registro = 0;
            foreach($arrQuantHistorico as $key=>$value){
                $quant_registro += $value['quant_projeto'];
            }
            $arrQuantHistorico['quant_registro'] = $quant_registro;
        }else{
            $arrQuantHistorico['quant_registro'] = 0;
        }
        $arrQuantHistorico['quant_profissional'] = $quant_profissional;
        return $arrQuantHistorico;
	}
}