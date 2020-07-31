<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
$container->setService(rollun\logger\LifeCycleToken::class, \rollun\logger\LifeCycleToken::generateToken());

$client = $container->get(\rollun\Walmart\Sdk\Reports::class);

// get report
$result = $client->getItemReport();

echo '<pre>';
print_r($result);
die();