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

class CalculadoraMetricaController extends Base_Controller_Action
{

	private $objDefinicaoMetrica;
	private $objItemMetrica;
	private $objSubItemMetrica;
	private $objContratoDefinicaoMetrica;
	
	public function init()
	{
		parent::init();
		$this->objDefinicaoMetrica			= new DefinicaoMetrica($this->_request->getControllerName());
		$this->objItemMetrica				= new ItemMetrica($this->_request->getControllerName());
		$this->objSubItemMetrica			= new SubItemMetrica($this->_request->getControllerName());
		$this->objContratoDefinicaoMetrica	= new ContratoDefinicaoMetrica($this->_request->getControllerName());
		
	}

	public function indexAction()
    { $this->initView(); }

	/**
     * Método utilizado para recuperar as informações necessaria para montagem da tela de
     * insersão de dados para o cálculo da métrica
     */
	public function montaCamposCalculadoraAction()
	{
        $this->_helper->layout->disableLayout();

        $cd_definicao_metrica = (int)$this->_request->getParam('cd_definicao_metrica',0);

        //recupera as informações da Definição da Métrica
        $arrDefinicaoMetrica = $this->objDefinicaoMetrica->find($cd_definicao_metrica)->current()->toArray();
        //recupera a formula da metrica
        $formulaMetrica = $arrDefinicaoMetrica['tx_formula_metrica'];

        //recupera os itens da formula da metrica
        $arrItensMetrica = $this->objItemMetrica->getDadosItemMetrica(null, $cd_definicao_metrica);
        
        
        $arrRetorno = array();
        //percore todos os item cadastratos para a metrica
        foreach( $arrItensMetrica as $item ){
            //procura se a variavel do item existe na formula da métrica
            if( strstr($formulaMetrica, $item['tx_variavel_item_metrica']) ){
                //recupera os subitens não internos do item encontrado na formula
                $arrSubItens = $this->objSubItemMetrica->getSubItemMetrica($cd_definicao_metrica, $item['cd_item_metrica'], false);

                //percore todos os sub-itens verificando os que não são do tipo interno
                foreach( $arrSubItens as $subItem ){
                        $subItemItem['tx_sub_item_metrica'         ] = $subItem['tx_sub_item_metrica'];
                        //concatena a variavel, cd_sub_item e cd_item "VAR|2|4"
                        $subItemItem['tx_variavel_sub_item_metrica'] = $subItem['tx_variavel_sub_item_metrica']."|".$subItem['cd_sub_item_metrica']."|".$item['cd_item_metrica'];

                        //monta o array de retorno com os sub-itens não internos
                        $arrRetorno[$item['tx_variavel_item_metrica']][] = $subItemItem;
                }
            }
        }
        $this->view->res						= $arrRetorno;

		$arrDadosMetricaPadrao					= $this->objContratoDefinicaoMetrica->getSiglaUnidadePadraoMetrica($_SESSION['oasis_logged'][0]['cd_contrato']);
		$arrDadosDefinicaoMetrica				= $this->objDefinicaoMetrica->find($cd_definicao_metrica)->current();

		$this->view->unidadePadraoMetrica		= $arrDadosMetricaPadrao[0]['tx_sigla_metrica'];
		$this->view->tx_sigla_unidade_metrica	= $arrDadosDefinicaoMetrica->tx_sigla_unidade_metrica;
	}
}