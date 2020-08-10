<?php
declare(strict_types=1);

namespace rollun\Walmart;

use rollun\Walmart\Sdk;

/**
 * Class ConfigProvider
 *
 * @author    r.ratsun <r.ratsun.rollun@gmail.com>
 *
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license   LICENSE.md New BSD License
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'invokables' => [
                    Sdk\Feed::class      => Sdk\Feed::class,
                    Sdk\Inventory::class => Sdk\Inventory::class,
                    Sdk\Item::class      => Sdk\Item::class,
                    Sdk\Orders::class    => Sdk\Orders::class,
                    Sdk\Price::class     => Sdk\Price::class,
                    Sdk\Reports::class   => Sdk\Reports::class,
                    Walmart::class       => Walmart::class,
                ],
            ],
        ];
    }
}
