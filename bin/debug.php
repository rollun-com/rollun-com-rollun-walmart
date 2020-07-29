<?php

use rollun\logger\LifeCycleToken;

error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

$lifeCycleToken = LifeCycleToken::generateToken();
$container->setService(LifeCycleToken::class, $lifeCycleToken);

$adapter = new \rollun\Walmart\Adapter\Adapter();

$allItems = $adapter->getAllItems();

echo '<pre>';
print_r($allItems);
die();

echo 'Done !';
die();