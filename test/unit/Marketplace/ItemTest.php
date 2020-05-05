<?php
declare(strict_types=1);

namespace test\unit\Marketplace;

use PHPUnit\Framework\TestCase;
use rollun\walmart\Marketplace\Item;

/**
 * Class ItemTest
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class ItemTest extends TestCase
{
    /**
     * @return array[]
     */
    public function getItemsProvider()
    {
        // $sku, $limit, $offset, $nextCursor, $expectedCount
        return [
            ['', 3, 2, '*', 3],
            ['', 1, 325, '*', 1],
            ['1235520056', 20, 0, '*', 1],
        ];
    }

    /**
     * Test for getItems method
     *
     * @dataProvider getItemsProvider
     */
    public function testGetItems($sku, $limit, $offset, $nextCursor, $expectedCount)
    {
        $items = (new Item())->getItems($sku, $limit, $offset, $nextCursor);

        $inventory = [
            'sku'      => '1235520056',
            'quantity' => [
                'unit'   => 'EACH',
                'amount' => 3
            ]
        ];

        $this->assertEquals($expectedCount, count($items['ItemResponse']));
    }
}
