<?php

use rollun\logger\LifeCycleToken;

error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

$lifeCycleToken = LifeCycleToken::generateToken();
$container->setService(LifeCycleToken::class, $lifeCycleToken);

$client = unserialize(serialize($container->get(\rollun\Walmart\Walmart::class)));
$result = $client->getAllItems();

echo '<pre>';
print_r($result);
die();