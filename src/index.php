<?php

require(__DIR__ . '/../vendor/autoload.php');

use PHP2Phar\PHP2Phar;

if (isset($argv)) {
	$php2phar= new PHP2Phar($argv);
	if($php2phar->isEnable()){
		$php2phar->execute();
	}
}