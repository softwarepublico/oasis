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

class FaleConosco extends Base_Db_Table_Abstract
{
	protected $_name     = KT_S_FALE_CONOSCO;
	protected $_primary  = 'cd_fale_conosco';
	protected $_schema   = K_SCHEMA;
	protected $_sequence = false;

	public function salvarFaleConosco(array $arrFaleConosco)
	{
		$novo = $this->createRow();
		$novo->cd_fale_conosco = $this->getNextValueOfField('cd_fale_conosco');
		$novo->tx_nome         = $arrFaleConosco['tx_nome'];
		$novo->tx_email        = $arrFaleConosco['tx_email'];
		$novo->tx_assunto      = $arrFaleConosco['tx_assunto'];
		$novo->tx_mensagem     = $arrFaleConosco['tx_mensagem'];
		$novo->tx_resposta     = ( $arrFaleConosco['tx_resposta'] ) ? $arrFaleConosco['tx_resposta'] : null;
		$novo->st_respondida   = $arrFaleConosco['st_respondida'];
		$novo->dt_registro     = (!empty ($arrFaleConosco['dt_registro'])) ? $arrFaleConosco['dt_registro'] : null;
		
		if($novo->save()){
			return true;
		} else {
			return false;
		}
	}

    public function getMensagemFaleConosco($mes, $ano, $status)
    {
        $select = $this->select()
                       ->where(new zend_db_expr("{$this->to_char('dt_registro', 'MM/YYYY')} = '{$mes}/{$ano}'"));

        switch ($status){
            case 'A':
                $select->where('st_respondida = ?', 'N')
                       ->where('st_pendente IS NULL');
                break;
            case 'P':
                $select->where('st_respondida = ?', 'S')
                       ->where('st_pendente IS NOT NULL');
                break;
            case 'R':
                $select->where('st_respondida = ?', 'S')
                       ->where('st_pendente IS NULL');
                break;
        }

        return $this->fetchAll($select)->toArray();
    }
}