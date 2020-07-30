<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

$sku = '1060040043';

$client = new \rollun\Walmart\Sdk\Price();

// update price
$result = $client->updateRegularPrice(
    [
        'sku'     => $sku,
        'pricing' => [
            [
                'currentPriceType'    => 'REDUCED',
                'currentPrice'        => [
                    'currency' => 'USD',
                    'amount'   => 10
                ],
                'comparisonPriceType' => 'BASE',
                'comparisonPrice'     => [
                    'currency' => 'USD',
                    'amount'   => 12
                ]
            ]
        ]
    ]
);

// bulk update prive
$result = $client->bulkUpdatePrice(
    [
        'PriceHeader' => [
            'version' => '1.7'
        ],
        'Price'       => [
            [
                'sku'     => $sku,
                'pricing' => [
                    [
                        'currentPriceType'    => 'REDUCED',
                        'currentPrice'        => [
                            'currency' => 'USD',
                            'amount'   => 10
                        ],
                        'comparisonPriceType' => 'BASE',
                        'comparisonPrice'     => [
                            'currency' => 'USD',
                            'amount'   => 12
                        ]
                    ]
                ]
            ]
        ]
    ]
);

echo '<pre>';
print_r($result);
die();