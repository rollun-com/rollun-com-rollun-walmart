<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

$sku = '1060040043';

$client = new \rollun\Walmart\Sdk\Inventory();

// get inventory
$result = $client->getInventory($sku);

// update inventory
$result = $client->updateInventory(
    [
        'sku'      => $sku,
        'quantity' => [
            'unit'   => 'EACH',
            'amount' => 0
        ]
    ]
);

// bulk update inventory
$result = $client->bulkUpdateInventory(
    [
        'InventoryHeader' => [
            'version' => '1.4'
        ],
        'Inventory'       => [
            [
                'sku'      => $sku,
                'quantity' => [
                    'unit'   => 'EACH',
                    'amount' => 0
                ]
            ]
        ]
    ]
);

echo '<pre>';
print_r($result);
die();