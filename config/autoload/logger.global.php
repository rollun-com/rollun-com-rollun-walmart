<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

use Psr\Log\LoggerInterface;
use rollun\logger\Formatter\ContextToString;
use rollun\logger\Formatter\LogStashUdpFormatter;
use rollun\logger\Processor\ExceptionBacktrace;
use rollun\logger\Processor\IdMaker;
use rollun\logger\Processor\LifeCycleTokenInjector;
use rollun\logger\Writer\Udp;
use Zend\Log\Writer\Db as DbWriter;
use Zend\Log\Writer\Stream;

return [
    'log' => [
        LoggerInterface::class => [
            'processors' => [
                [
                    'name' => IdMaker::class,
                ],
                [
                    'name' => ExceptionBacktrace::class,
                ],
                [
                    'name' => LifeCycleTokenInjector::class,
                ],
            ],
           /* 'writers' => [
                [
                    'name' => DbWriter::class,
                    'options' => [
                        'db' => 'db',
                        'table' => 'logs',
                        'column' => [
                            'id' => 'id',
                            'timestamp' => 'timestamp',
                            'message' => 'message',
                            'level' => 'level',
                            'priority' => 'priority',
                            'context' => 'context',
                            'lifecycle_token' => 'lifecycle_token',
                            'parent_lifecycle_token' => 'parent_lifecycle_token',
                        ],
                        'filters' => [
                            [
                                'name' => 'priority',
                                'options' => [
                                    'operator' => getenv('APP_DEBUG') == 'true' ? '<=' : '<',
                                    'priority' => 7,
                                ],
                            ],
                        ],
                        'formatter' => [
                            'name' => ContextToString::class,
                            'options' => [
                                'dateTimeFormat' => DATE_RFC3339_EXTENDED
                            ]
                        ]
                    ],
                ],
            ],*/
        ],
    ],
];
