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

class ItemTeste extends Base_Db_Table_Abstract
{
	protected $_name    = KT_B_ITEM_TESTE;
	protected $_primary = 'cd_item_teste';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function verifyOrdemByTipo($ordem,$tipo,&$ni_ordem_item_teste=null)
	{
        $rs = $this->fetchAll(array("ni_ordem_item_teste = ?"=>$ordem," st_tipo_item_teste = ?"=>$tipo))->toArray();
        if( empty($rs) ){
            return true;
        } else {
           $ni_ordem_item_teste = $rs[0]['ni_ordem_item_teste'];
	       return false;
        }
	}
	public function getNextOrdemItemTestePorTipo($tipo)
	{
        return $this->getNextValueOfField('ni_ordem_item_teste',"st_tipo_item_teste = '{$tipo}'");
	}
}