<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

$feedId = '45F8FEA18E6E48209A570F3E03BEC254@AVMBCgA';

$client = new \rollun\Walmart\Sdk\Feed();
$result = $client->getFeedStatus($feedId, true, 20, 0);

echo '<pre>';
print_r($result);
die();