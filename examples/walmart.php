<?php

error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
$container->setService(rollun\logger\LifeCycleToken::class, \rollun\logger\LifeCycleToken::generateToken());

$walmart = $container->get(\rollun\Walmart\Walmart::class);

// get all items with them reports
$result = $walmart->getAllItems();

// get all active items with them reports
$result = $walmart->getActiveItems();

// get feed status
$feedId = '45F8FEA18E6E48209A570F3E03BEC254@AVMBCgA';
$result = $walmart->getFeedStatus($feedId);

// update quantity
$data = [
    // sku => quantity
    '1060040043' => 0,
];
$feedId = $walmart->updateQuantity($data);

// update price
$data = [
    // sku => price
    '1060040043' => 0,
];
$feedId = $walmart->updatePrice($data);

// get all orders
$result = $walmart->getAllOrders();

// get order
$result = $walmart->getOrder('112233');