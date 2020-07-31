<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
$container->setService(rollun\logger\LifeCycleToken::class, \rollun\logger\LifeCycleToken::generateToken());

$client = $container->get(\rollun\Walmart\Sdk\Item::class);

$limit = 5;
$nextCursor = 'AoE/GjBUTFNXRVNVODM0VzBTRUxMRVJfT0ZGRVJBNUE1RDJEOTczNDI0M0ZFOTZDOEUzM0JCREZGRDRDRQ=='; // for pagination

// get items
$result = $client->getItems($limit, $nextCursor);

echo '<pre>';
print_r($result);
die();
