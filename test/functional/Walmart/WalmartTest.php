<?php


namespace test\functional\Walmart;


use PHPUnit\Framework\TestCase;

class WalmartTest extends TestCase
{
    public function testGetAllOrders()
    {
        $walmart = new \rollun\Walmart\Walmart();
        $orders = $walmart->getAllOrders();

        $this->assertNotEmpty($orders);
    }
}