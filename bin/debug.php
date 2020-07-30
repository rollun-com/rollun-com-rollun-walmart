<?php

use rollun\logger\LifeCycleToken;

error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

$lifeCycleToken = LifeCycleToken::generateToken();
$container->setService(LifeCycleToken::class, $lifeCycleToken);

//$result = (new \rollun\Walmart\Walmart())->getAllItems();
//$result = (new \rollun\Walmart\Walmart())->updatePrice(['1060040043' => 92.35]);
//$result = (new \rollun\Walmart\Walmart())->getFeedStatus('45F8FEA18E6E48209A570F3E03BEC254@AVMBCgA');

echo '<pre>';
print_r($result);
die();