<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
$container->setService(rollun\logger\LifeCycleToken::class, \rollun\logger\LifeCycleToken::generateToken());

$client = $container->get(\rollun\Walmart\Sdk\Orders::class);

// get all orders (max 100 per one page)
$result = $client->getAll();

// get order by id
$result = $client->getOrder('4801218385418');

echo '<pre>';
print_r($result);
die();