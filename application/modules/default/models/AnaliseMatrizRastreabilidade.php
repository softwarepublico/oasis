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

class AnaliseMatrizRastreabilidade  extends Base_Db_Table_Abstract
{
	protected $_name 	 = KT_S_ANALISE_MATRIZ_RASTREAB;
	protected $_primary  = array('cd_analise_matriz_rastreab', 'cd_projeto', 'st_matriz_rastreabilidade');
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;
	
	public function getVersaoAbertaAnaliseMatrizRastreabilidade( $cd_projeto, $st_matriz_rastreabilidade )
	{
		$select = $this->select()
					   ->where("cd_projeto = ?", $cd_projeto)	
					   ->where("st_matriz_rastreabilidade = '{$st_matriz_rastreabilidade}'")
					   ->where("st_fechamento is null");

		return $this->fetchAll($select)->toArray();
	}
	
	public function fechaAnaliseMatrizRastreablidade( array $arrUpdate, array $arrCondicoes )
	{
        $where = array(
                'cd_analise_matriz_rastreab = ?'=>$arrCondicoes['cd_analise_matriz_rastreab'],
                'cd_projeto = ?'                =>$arrCondicoes['cd_projeto'],
                'st_matriz_rastreabilidade = ?' =>$arrCondicoes['st_matriz_rastreabilidade'],
                'dt_analise_matriz_rastreab = ?'=>$arrCondicoes['dt_analise_matriz_rastreab']
        );

		if( $this->update($arrUpdate, $where) ){
			return true;
		}else{
			return false;
		}
	}
	
}