<?php
function print_array($mixed,$die = false){

	echo "<pre>";
    print_r($mixed);
    echo "</pre>";
	if($die)
		die();
}

$loader = require_once  __DIR__ .'/vendor/autoload.php';


use Hardel\Core\Application;
use Hardel\Core\Config;
use Hardel\Core\Controller;

$app = new Application();

$app->bind('config',function($app){
	$conf = new Config();
	$conf->ip = '191.168.1.1';
	$conf->HostName = 'provadelleProve';

	return $conf;
},'Hardel\Core\Config');

$app->bind('controller','Hardel\Core\Controller');

$controller = $app->make('controller');

//print_array($container);
print_array($controller->getConfig()->ip);
print_array($controller->getConfig()->HostName);

