<?php

error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

$walmart = new \rollun\Walmart\Walmart();

// get all items with them reports
$result = $walmart->getAllItems();

// get all active items with them reports
$result = $walmart->getActiveItems();

// get feed status
$result = $walmart->getFeedStatus('45F8FEA18E6E48209A570F3E03BEC254@AVMBCgA');

// update quantity
$data = [
    // sku => quantity
    '1060040043' => 0,
];
$result = $walmart->updateQuantity($data);

// update price
$data = [
    // sku => price
    '1060040043' => 0,
];
$result = $walmart->updatePrice($data);