<?php
if(isset($_GET['teste']) && $_GET['teste'] == "S"){
	die('Sistema Temporariamente indisponível para atualização');
}
include '../application/Bootstrap.php';

$configSection = getenv('PLACES_CONFIG') ? getenv('PLACES_CONFIG') : 'local';

if('local' === $configSection)
    die('Contacte o administrador para configura&ccedil;&atilde;o do Enviroment');

$bootstrap = new Bootstrap($configSection);
$bootstrap->runApp();