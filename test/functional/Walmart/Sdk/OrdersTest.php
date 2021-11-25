<?php

namespace test\functional\Walmart\Sdk;

use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Orders;
use rollun\Walmart\Sdk\Price;
use rollun\Walmart\Walmart;
use rollun\Walmart\WalmartApiAbstractFactory;

class OrdersTest extends ApiAbstractTest
{
    /**
     * @var Orders
     */
    protected $api;

    protected function getApiClass(): string
    {
        return Orders::class;
    }

    public function testGetOrders()
    {
       $orders = $this->api->getOrders(20);

       $this->assertTrue(isset($orders['list']['elements']['order']));
    }

    public function testAknowledge()
    {
        $orders = $this->api->getOrders(1, (new \DateTime())->modify('-30 days'));
        $id = $orders['list']['elements']['order'][0]['purchaseOrderId'];
        $order = $this->api->aknowledge($id);

        $this->assertNotEmpty($order);
        $this->assertEquals($id, $order['order']['purchaseOrderId']);
    }
}