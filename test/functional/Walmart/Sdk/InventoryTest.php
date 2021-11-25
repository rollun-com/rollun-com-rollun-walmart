<?php
declare(strict_types=1);

namespace test\functional\Walmart\Sdk;

use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Inventory;
use rollun\Walmart\Sdk\Item;

/**
 * Class InventoryTest
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class InventoryTest extends ApiAbstractTest
{
    /**
     * @var Inventory
     */
    protected $api;

    protected function getApiClass(): string
    {
        return Inventory::class;
    }

    public function getExistingSku()
    {
        $api = getTestContainer()->get(Item::class);
        $items = $api->getItems(20);
        if (!empty($items['ItemResponse'][0])) {
            return $items['ItemResponse'][0]['sku'];
        }

        // TODO Default sku may be deleted
        return '1113A';
    }
    /**
     * Test for getInventory method
     */
    public function testGetInventory()
    {
        $sku = $this->getExistingSku();
        $data = $this->api->getInventory($sku);

        $this->assertEquals(['sku', 'quantity'], array_keys($data));
    }
}
