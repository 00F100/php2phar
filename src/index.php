<?php

require(__DIR__ . '/../vendor/autoload.php');

use PHP2Phar\PHP2Phar;

if (isset($argv)) {
	$php2phar= new PHP2Phar($argv);
	if($php2phar->isEnable()){
		$php2phar->execute();
	}
}

if (!isset($argv)) {
	debug($_SERVER);
}

// 

// $files = array(
// 	dirname(__FILE__) . '/',
// );

// build_phar('phpsize', $files, 'src/index.php', __DIR__ . '/dist/');