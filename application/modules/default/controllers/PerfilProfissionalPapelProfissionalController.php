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

class PerfilProfissionalPapelProfissionalController extends Base_Controller_Action
{
	private $objObjetoContrato;
	private $objPefilProfissional;
	private $objPerfilProfissionalPapelProfissional;
		
	public function init()
	{
		parent::init();
		
		$this->objObjetoContrato 	= new ObjetoContrato($this->_request->getControllerName());
		$this->objPefilProfissional = new PerfilProfissional($this->_request->getControllerName());
		$this->objPerfilProfissionalPapelProfissional = new PerfilProfissionalPapelProfissional($this->_request->getControllerName());
	}

	public function indexAction(){}

	public function getPerfilProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$cd_area_atuacao_ti = (int) $this->_request->getParam('cd_area_atuacao_ti', 0);
		
		$arrPerfilProfissional = $this->objPefilProfissional->getPerfilProfissionalAreaAtuacao( $cd_area_atuacao_ti, true );
		
		$strOption = '';
		
		foreach($arrPerfilProfissional as $cd_perfil=>$tx_perfil){
			$strOption .= "<option label=\"{$tx_perfil}\" value=\"{$cd_perfil}\">{$tx_perfil}</option>";
		}
		
		echo $strOption;
	}
	
	public function pesquisaPapelProfissionalAction()
	{

		// Como este metodo eh um metodo ajax, ele nao precisa renderizar com nenhum template e com nenhum layout.
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		// Recupera os parametros enviados por get
		$cd_area_atuacao_ti     = $this->_request->getParam('cd_area_atuacao_ti');
		$cd_perfil_profissional = $this->_request->getParam('cd_perfil_profissional');

		//  Caso tenha sido enviada a opcao selecione do combo. Apenas para garantir caso o js falhe nesta verificacao
		if ($cd_perfil_profissional == 0) {
			echo '';
		} else {

			$objPerfilProfissionalPapelProfissional = new PerfilProfissionalPapelProfissional($this->_request->getControllerName());

			// Recordset de papeis desempenhados pelo perfil profissionais que nao se encontram associados ao perfil
			$foraPerfilProfissional = $objPerfilProfissionalPapelProfissional->pesquisaPapelProfissionalForaPerfilProfissional($cd_area_atuacao_ti, $cd_perfil_profissional);
			
			// Recordset de papeis desempenhados pelo perfil profissionais que se encontram associados ao perfil
			$noPerfilProfissional   = $objPerfilProfissionalPapelProfissional->pesquisaPapelProfissionalNoPerfilProfissional($cd_area_atuacao_ti, $cd_perfil_profissional);

			/*
			 * Os procedimentos abaixo criam os options dos selects de acordo com o seu respectivo recordset. 
			 * Posteriormente eh criado um json que eh enviado ao client (javascript) que adiciona os options aos selects
			 */
			$arr1 = "";

			foreach ($foraPerfilProfissional as $fora) {
				$arr1 .= "<option value=\"{$fora['cd_papel_profissional']}\">{$fora['tx_papel_profissional']}</option>";
			}

			$arr2 = "";
			foreach ($noPerfilProfissional as $no) {
				$arr2 .= "<option value=\"{$no['cd_papel_profissional']}\">{$no['tx_papel_profissional']}</option>";
			}

			$retornaOsDois = array($arr1, $arr2);

			echo Zend_Json_Encoder::encode($retornaOsDois);
		}
	}
	
	public function associaPerfilProfissionalPapelProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post = $this->_request->getPost();
		
		$cd_perfil_profissional = $post['cd_perfil_profissional'];
		$papeis                 = Zend_Json_Decoder::decode($post['papeis']);
		
		foreach ($papeis as $papel) {
			$novo = $this->objPerfilProfissionalPapelProfissional->createRow();
			$novo->cd_perfil_profissional = $cd_perfil_profissional;
			$novo->cd_papel_profissional  = $papel;
			$novo->save();
		}
	}
	
	public function desassociaPerfilProfissionalPapelProfissionalAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();

		$post					= $this->_request->getPost();
		$cd_perfil_profissional = $post['cd_perfil_profissional'];
		$papeis					= Zend_Json_Decoder::decode($post['papeis']);
		
		foreach ($papeis as $papel) {
            $arrWhere = array();
			$arrWhere["cd_perfil_profissional = ?"] = $cd_perfil_profissional;
			$arrWhere["cd_papel_profissional  = ?"] = $papel;
			$this->objPerfilProfissionalPapelProfissional->delete($arrWhere);
		}
	}
	
}