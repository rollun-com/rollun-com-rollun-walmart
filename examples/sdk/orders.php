<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
$container->setService(rollun\logger\LifeCycleToken::class, \rollun\logger\LifeCycleToken::generateToken());

/** @var \rollun\Walmart\Sdk\Orders $client */
$client = $container->get(\rollun\Walmart\Sdk\Orders::class);

// get all orders (max 100 per one page)
$result = $client->getAll();

// get orders by created start date
$result = $client->getByCreatedStartDate(new \DateTime('01.08.2020'));

// get order by id
$result = $client->getOrder('4801218385418');

// **NOTE: shipDateTime must be in UTC. **
// **NOTE: Walmart Fulfilled orders can't be shipped or updated. **
$data = [
    'orderShipment' => [
        'processMode' => 'PARTIAL_UPDATE',
        'orderLines'  => [
            [
                'lineNumber'        => 1,
                'orderLineStatuses' => [
                    'orderLineStatus' => [
                        'orderLineStatus' => [
                            [
                                'status'         => 'Shipped',
                                'statusQuantity' => [
                                    'unitOfMeasurement' => 'EACH',
                                    'amount'            => 1
                                ],
                                'trackingInfo'   => [
                                    'shipDateTime'   => '1588276433000',
                                    'carrierName'    => [
                                        'otherCarrier' => null,
                                        'carrier'      => 'FedEx'
                                    ],
                                    'methodCode'     => 'Value',
                                    'trackingNumber' => '178528291246',
                                    'trackingURL'    => 'https://www.walmart.com/tracking?tracking_id=178528291246&order_id=4801218385418'
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ]
    ]
];

$result = $client->shippingUpdate('4801218385418', $data);