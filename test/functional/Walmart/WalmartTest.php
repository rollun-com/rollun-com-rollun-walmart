<?php


namespace test\functional\Walmart;


use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Feed;
use rollun\Walmart\Sdk\Item;
use rollun\Walmart\Walmart;

class WalmartTest extends TestCase
{
    public function testGetAllOrders()
    {
        $walmart = getTestContainer()->get(Walmart::class);
        $orders = $walmart->getAllOrders();

        $this->assertNotEmpty($orders);
    }

    /*public function testGetFeedStatusMoreOneThousand()
    {
        $feedId = 'B796A677B8A940A2A8EDA79B005715FB@AUoBCgA';
        $walmart = new \rollun\Walmart\Walmart();
        $response = $walmart->getFeedStatus($feedId);

        $this->assertCount(1145, $response['itemDetails']['itemIngestionStatus']);
    }*/

    /*public function testItemsWithReport()
    {
        $walmart = new Walmart();
        $items = $walmart->getActiveItems();

        $this->assertNotEmpty($items[0]['report']);
        $this->assertNotEmpty($items[count($items) - 1]['report']);
    }*/
}