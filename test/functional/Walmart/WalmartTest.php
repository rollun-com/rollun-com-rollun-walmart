<?php


namespace test\functional\Walmart;


use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Feed;

class WalmartTest extends TestCase
{
    public function testGetAllOrders()
    {
        $walmart = new \rollun\Walmart\Walmart();
        $orders = $walmart->getAllOrders();

        $this->assertNotEmpty($orders);
    }

    public function testGetFeedStatusMoreOneThousand()
    {
        $feedId = 'B796A677B8A940A2A8EDA79B005715FB@AUoBCgA';
        $walmart = new \rollun\Walmart\Walmart();
        $response = $walmart->getFeedStatus($feedId);

        $this->assertCount(1145, $response['itemDetails']['itemIngestionStatus']);
    }
}