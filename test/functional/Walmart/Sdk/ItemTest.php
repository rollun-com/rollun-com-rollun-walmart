<?php
declare(strict_types=1);

namespace test\functional\Walmart\Sdk;

use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Item;

/**
 * Class ItemTest
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class ItemTest extends ApiAbstractTest
{
    /**
     * @var Item
     */
    protected $api;

    protected function getApiClass(): string
    {
        return Item::class;
    }

    /**
     * Test for getItems method
     */

    public function testGetItems()
    {
        $items = $this->api->getItems();

        $this->assertNotEmpty($items['ItemResponse']);
        $this->assertArrayHasKey('sku', $items['ItemResponse'][0]);
    }
}
