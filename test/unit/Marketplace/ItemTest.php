<?php
declare(strict_types=1);

namespace test\unit\Marketplace;

use PHPUnit\Framework\TestCase;
use rollun\walmart\Marketplace\Authentication;
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
        // $nextCursor, $sku, $limit, $offset, $expectedCount
        return [
            [null, null, 20, 0, 20],
            [null, null, 3, 2, 3],
            [null, null, 1, 325, 1],
        ];
    }

    /**
     * @dataProvider getItemsProvider
     */
    public function testGetItems($nextCursor, $sku, $limit, $offset, $expectedCount)
    {
        $count = count((new Item())->getItems($nextCursor, $sku, $limit, $offset)['ItemResponse']);

        $this->assertEquals($expectedCount, $count);
    }
}
