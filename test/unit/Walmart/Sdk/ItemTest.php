<?php
declare(strict_types=1);

namespace test\unit\Walmart\Sdk;

use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Item;

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
        // $limit, $expected
        return [
            [3, 3],
            [1, 1],
        ];
    }

    /**
     * Test for getItems method
     *
     * @param int $limit
     * @param int $expected
     *
     * @dataProvider getItemsProvider
     */

    public function testGetItems(int $limit, int $expected)
    {
        $items = (new Item())->getItems($limit);

        $this->assertEquals($expected, count($items['ItemResponse']));
    }
}
