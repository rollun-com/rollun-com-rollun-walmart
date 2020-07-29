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
        $data = (new Inventory())->getInventory('1235520056');

        $this->assertEquals(['sku', 'quantity'], array_keys($data));
    }
}
