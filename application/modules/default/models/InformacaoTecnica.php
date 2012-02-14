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

class InformacaoTecnica extends Base_Db_Table_Abstract
{
	protected $_name     = KT_A_INFORMACAO_TECNICA;
	protected $_primary  = array('cd_projeto', 'cd_tipo_dado_tecnico');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function getDadosTecnicosInformacao($cd_projeto)
	{
        $select = $this->select()->setIntegrityCheck(false);

        $select->from(array('dt'=>KT_B_TIPO_DADO_TECNICO),
                      array('cd_tipo_dado_tecnico','tx_tipo_dado_tecnico'),
                      $this->_schema);
        $select->joinLeft(array('dados'=>$this->select()
                                              ->setIntegrityCheck(false)
                                              ->from(array('it'=>$this->_name),
                                                     array('cd_projeto','tx_conteudo_informacao_tecnica'),
                                                     $this->_schema)
                                              ->joinLeft(array('tip'=>KT_B_TIPO_DADO_TECNICO),
                                                         '(it.cd_tipo_dado_tecnico = tip.cd_tipo_dado_tecnico)',
                                                         'cd_tipo_dado_tecnico',
                                                         $this->_schema)
                                              ->where('it.cd_projeto = ?', $cd_projeto, Zend_Db::INT_TYPE)),
                          '(dt.cd_tipo_dado_tecnico = dados.cd_tipo_dado_tecnico)',
                          array('cd_projeto','tx_conteudo_informacao_tecnica'));

        return $this->fetchAll($select)->toArray();
	}
	
	public function salvaInformacaoTecnica($arrInformacaoTecnica)
	{
		foreach($arrInformacaoTecnica as $key=>$value){

			$select = $this->select()
                           ->where('cd_projeto = ?',$value['cd_projeto'], Zend_Db::INT_TYPE)
                           ->where('cd_tipo_dado_tecnico = ?',$value['cd_tipo_dado_tecnico'], Zend_Db::INT_TYPE);
            
			if($this->fetchAll($select)->valid()){
				if($value['tx_conteudo_informacao_tecnica'] != ""){
					$this->update($arrInformacaoTecnica[$key], array('cd_projeto = ?'=>$value['cd_projeto'], 'cd_tipo_dado_tecnico = ?'=>$value['cd_tipo_dado_tecnico']));
				} else {
					$this->delete(array('cd_projeto = ?'=>$value['cd_projeto'], 'cd_tipo_dado_tecnico = ?'=>$value['cd_tipo_dado_tecnico']));
				}
			} else {
				$novo                                 = $this->createRow();
				$novo->cd_projeto                     = $value['cd_projeto'];
				$novo->cd_tipo_dado_tecnico           = $value['cd_tipo_dado_tecnico'];
				$novo->tx_conteudo_informacao_tecnica = $value['tx_conteudo_informacao_tecnica'];
				$novo->save();
			}
		}
	}
}