<?php


namespace test\unit\Walmart;


use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Feed;
use rollun\Walmart\Sdk\Inventory;
use rollun\Walmart\Sdk\Orders;
use rollun\Walmart\Sdk\Price;
use rollun\Walmart\Walmart;

class WalmartTest extends TestCase
{
    public function testGetAllOrders()
    {
        $orders = $this->createMock(Orders::class);
        $orders->expects($this->any())->method('getAll')->willReturnCallback(function(){
            return $this->orderData();
        });

        $walmart = new Walmart(['orders' => $orders]);
        $result = $walmart->getAllOrders();

        $this->assertCount(225, $result);
    }

    protected function orderData()
    {
        static $page = 0;
        $limit = 200;
        $total = 225;
        $current = ++$page * $limit;

        $nextCursor = sprintf(
            '?limit=%d&soIndex=%d&poIndex=%d'
            . '&hasMoreElements=true&partnerId=10001042097&sellerId=101022720'
            . '&createdStartDate=2020-04-08T12:10:10.733Z&createdEndDate=2020-10-05T12:09:50.656Z',
            $limit,
            $total,
            $current
        );

        return [
            'list' => [
                'meta' => [
                    'totalCount' => $total,
                    'limit' => $limit,
                    'nextCursor' => $current < $total ? $nextCursor : null
                ],
                'elements' => [
                    'order' =>
                        array_fill(
                            0,
                            $current < $total ? $limit : $total % $limit,
                            array_fill_keys(['purchaseOrderId'], (string) random_int(1000000000000, 9999999999999))
                        )
                ]
            ]
        ];
    }

    public function testGetFeedStatus()
    {
        $feedId = uniqid();
        $total = random_int(1, 3000);

        $feed = $this->createMock(Feed::class);
        $feed->expects($this->any())->method('getFeedStatus')->with($feedId)->willReturnCallback(
            function(string $feedId, bool $includeDetails = true, int $limit = 20, int $offset = 0) use ($total){
                static $counter = 0;
                $max = ++$counter < ceil($total / 1000) ? 1000 : $total % 1000;

                return [
                    'itemsReceived' => $total,
                    'offset' => $offset,
                    'limit' => $limit,
                    'itemDetails' => [
                        'itemIngestionStatus' => array_fill(0, $max, []),
                    ]
                ];
            }
        );

        $walmart = new Walmart(['feed' => $feed]);
        $response = $walmart->getFeedStatus($feedId);

        $this->assertCount($total, $response['itemDetails']['itemIngestionStatus']);
    }

    public function testUpdatePrice()
    {
        $data = [
            '12345' => 123.45,
        ];
        $requestData = [];

        $price = $this->createMock(Price::class);
        $price->expects($this->once())
            ->method('bulkUpdatePrice')
            ->willReturnCallback(function ($data) use (&$requestData) {
                $requestData = $data;

                return ['feedId' => uniqid('', true)];
            });

        $walmart = new Walmart(['price' => $price]);
        $walmart->updatePrice($data);

        $this->assertTrue(is_string($requestData['Price'][0]['sku']));
    }

    public function testUpdateQuantity()
    {
        $data = [
            '12345' => 5,
        ];
        $requestData = [];

        $inventory = $this->createMock(Inventory::class);
        $inventory->expects($this->once())
            ->method('bulkUpdateInventory')
            ->willReturnCallback(function ($data) use (&$requestData) {
                $requestData = $data;

                return ['feedId' => uniqid('', true)];
            });

        $walmart = new Walmart(['inventory' => $inventory]);
        $walmart->updateQuantity($data);

        $this->assertTrue(is_string($requestData['Inventory'][0]['sku']));
    }
}