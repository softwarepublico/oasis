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

include_once 'Abstract.php';
/**
 * Classe para acompanhamento de proposta
 * 
 */
class AcompanhamentoProposta__NomeAlteradoParaProcurarOndeEUtilizada extends Base_Db_Table_Abstract
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Método que executa a query 
     *
     * @param ARRAY $params
     * @return ARRAY $arrResult
     */
    public function getPropostaProjeto( array $params )
    {
    	$sql = "SELECT
				    proj.tx_sigla_projeto,
				    prop.cd_proposta,
				    prop.cd_projeto,
				    (CASE WHEN prop.cd_proposta=1 THEN 'Novo' ELSE 'Evolutivo' END) as tipo,
				    (CASE WHEN prop.st_encerramento_proposta='S' THEN 'Finalizada' ELSE 'Em Andamento' END) as situacao,
				    datas.dt_inicio, 
				    datas.dt_fim 
				FROM
				    {$this->_schema}.".KT_S_PROPOSTA." AS prop
				join (
					SELECT  
						cd_projeto,
						cd_proposta,
						--to_char((min(periodo)),'MM/YYYY') as dt_inicio,
						--to_char((max(periodo)),'MM/YYYY') as dt_fim
						SUBSTRING(min(periodo),POSITION('/' in min(periodo))+1,4) || '/' || SUBSTRING(min(periodo),1,POSITION('/' in min(periodo))-1) as dt_inicio,
						SUBSTRING(max(periodo),POSITION('/' in max(periodo))+1,4) || '/' || SUBSTRING(max(periodo),1,POSITION('/' in max(periodo))-1) as dt_fim
					FROM	
					    (
						SELECT 
							cd_projeto, 
							cd_proposta,
							--to_date(NI_MES_PREVISAO_PARCELA||'/'||NI_ANO_PREVISAO_PARCELA,'MM/YYYY') as periodo
							(ni_ano_previsao_parcela ||'/'|| substring('00' || ni_mes_previsao_parcela,length('00' || ni_mes_previsao_parcela)-1,2)) as periodo
						FROM 
							 {$this->_schema}.".KT_S_PARCELA."
						WHERE 
							CD_PROJETO =  {$params['cd_projeto']} 
						and 
							NI_MES_PREVISAO_PARCELA is not null 
						and 
							NI_ANO_PREVISAO_PARCELA is not null
				
					     ) as dat
					     group by cd_projeto, cd_proposta
					) AS datas ON prop.cd_projeto = datas.cd_projeto
				JOIN 
				    {$this->_schema}.".KT_S_PROJETO." AS proj ON prop.cd_projeto = proj.cd_projeto
				WHERE
				    prop.cd_projeto = {$params['cd_projeto']}
				AND
				    prop.cd_objeto = {$params['cd_objeto']}    
				ORDER BY
				    tx_sigla_projeto,
				    cd_proposta
    	";
    	
        $arrResult = $this->db->fetchAll( $sql, $param );

        return $arrResult;
    }
    
public function getParcelaPropostaProjeto( array $params )
    {
    	$sql = "SELECT 
					st_pendente, st_homologacao_parcela, st_ativo, ni_parcela, 
					(NI_MES_PREVISAO_PARCELA||'/'||NI_ANO_PREVISAO_PARCELA)	as periodo
				FROM 
					 {$this->_schema}.".KT_S_PARCELA." as par
				JOIN
					(
					   SELECT 
						cd_parcela, st_homologacao_parcela, st_ativo, st_pendente 
					   FROM 
						 {$this->_schema}.".KT_S_PROCESSAMENTO_PARCELA."
					   WHERE 
						CD_PROJETO =  {$params['cd_projeto']}
					   AND 
						CD_PROPOSTA =  {$params['cd_proposta']}
					) AS situ  ON par.cd_parcela = situ.cd_parcela 
					
				WHERE 
					CD_PROJETO =  {$params['cd_projeto']}  
				AND 
					CD_PROPOSTA =  {$params['cd_proposta']}
					
				and 
					NI_MES_PREVISAO_PARCELA is not null 
				and 
					NI_ANO_PREVISAO_PARCELA is not null";
    	
        $arrResult = $this->db->fetchAll( $sql, $param );

        return $arrResult;
    }
}