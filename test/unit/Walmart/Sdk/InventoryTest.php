<?php
declare(strict_types=1);

namespace test\unit\Walmart\Sdk;

use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Inventory;

/**
 * Class InventoryTest
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class InventoryTest extends TestCase
{
    /**
     * Test for getInventory method
     */
    public function testGetInventory()
    {
        // TODO Sku may be deleted
        $data = (new Inventory())->getInventory('1113A');

        $this->assertEquals(['sku', 'quantity'], array_keys($data));
    }
}
