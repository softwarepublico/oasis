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

class TreeviewController extends Base_Controller_Action
{
    public function indexAction ()
    {
        $wikidata = array('Previsto' => array('Jun 2008' => 8829 ,   //TODO   e isso como faz???
        									  'Jul 2008' => 10747 , 
        									  'Ago 2008' => 10910 , 
        									  'Set 2008' => 11279 , 
        									  'Out 2008' => 6388 , 
        									  'Nov 2008' => 1140) , 
        			      'Contratado' => array('Jun 2008' => 10828 , 
        			      						'Jul 2008' => 10828 , 
        			      						'Ago 2008' => 10828 , 
        			      						'Set 2008' => 10828 , 
        			      						'Out 2008' => 10828 , 
        			      						'Nov 2008' => 10828) , 
        			      'Realizado' => array('Jun 2008' => 8829 , 
        			      					   'Jul 2008' => 10747 , 
        			      					   'Ago 2008' => 10910 , 
        			      					   'Set 2008' => 11279 , 
        			      					   'Out 2008' => 0 , 
        			      					   'Nov 2008' => 0));
        
        $graph = new ezcGraphBarChart();
        //$graph->title = 'Saldo Horas de Contrato/Mês';
        
        // Add data
         foreach ( $wikidata as $language => $data )
         {
             $graph->data[$language] = new ezcGraphArrayDataSet( $data );
         }
         
         $graph->data['Contratado']->displayType = ezcGraph::LINE;
         $graph->title->background = '#FFFFFF';
         $graph->data->background = '#FFFFFF';
         
         $graph->options->fillLines = 210;
         $graph->background->background = '#FFFFFF';
        
         $caminho = SYSTEM_PATH_ABSOLUTE . "/public/img/";
         $url     = SYSTEM_PATH . "/public/img/";
         
         
         $graph->render( 450, 250, "{$caminho}eh_nois.svg" );
         
         $this->view->imagem = "{$url}eh_nois.svg";
         
         
    }
}