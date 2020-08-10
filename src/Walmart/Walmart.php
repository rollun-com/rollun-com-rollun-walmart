<?php
declare(strict_types=1);

namespace rollun\Walmart;

use rollun\dic\InsideConstruct;
use rollun\Walmart\Sdk;

/**
 * Class Walmart
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Walmart
{
    /**
     * @var Sdk\Feed
     */
    protected $feed;

    /**
     * @var Sdk\Item
     */
    protected $item;

    /**
     * @var Sdk\Inventory
     */
    protected $inventory;

    /**
     * @var Sdk\Orders
     */
    protected $orders;

    /**
     * @var Sdk\Price
     */
    protected $price;

    /**
     * @var Sdk\Reports
     */
    protected $reports;

    /**
     * Walmart constructor.
     *
     * @param Sdk\Feed|null      $feed
     * @param Sdk\Item|null      $item
     * @param Sdk\Inventory|null $inventory
     * @param Sdk\Orders|null    $orders
     * @param Sdk\Price|null     $price
     * @param Sdk\Reports|null   $reports
     *
     * @throws \ReflectionException
     */
    public function __construct(Sdk\Feed $feed = null, Sdk\Item $item = null, Sdk\Inventory $inventory = null, Sdk\Orders $orders = null, Sdk\Price $price = null,
        Sdk\Reports $reports = null
    ) {
        InsideConstruct::init(
            [
                'feed'      => Sdk\Feed::class,
                'item'      => Sdk\Item::class,
                'inventory' => Sdk\Inventory::class,
                'orders'    => Sdk\Orders::class,
                'price'     => Sdk\Price::class,
                'reports'   => Sdk\Reports::class,
            ]
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function __wakeup()
    {
        InsideConstruct::initWakeup(
            [
                'feed'      => Sdk\Feed::class,
                'item'      => Sdk\Item::class,
                'inventory' => Sdk\Inventory::class,
                'orders'    => Sdk\Orders::class,
                'price'     => Sdk\Price::class,
                'reports'   => Sdk\Reports::class,
            ]
        );
    }

    /**
     * @param string $feedId
     *
     * @return array
     */
    public function getFeedStatus(string $feedId): array
    {
        return $this->feed->getFeedStatus($feedId, true, 1000, 0);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getActiveItems()
    {
        // prepare result
        $result = [];

        // get items
        $data['nextCursor'] = '';
        while (isset($data['nextCursor'])) {
            $data = $this->item->getItems(1000, $data['nextCursor'], Sdk\Item::LIFECYCLE_STATUS_ACTIVE, true);
            $result = array_merge($result, $data['ItemResponse']);
        }

        return $this->setDataFromReport($result);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllItems()
    {
        // prepare result
        $result = [];

        // get items
        $data['nextCursor'] = '';
        while (isset($data['nextCursor'])) {
            $data = $this->item->getItems(1000, $data['nextCursor']);
            $result = array_merge($result, $data['ItemResponse']);
        }

        return $this->setDataFromReport($result);
    }

    /**
     * Get all orders
     *
     * @return array
     */
    public function getAllOrders(): array
    {
        // prepare result
        $result = [];

        $totalCount = 1;
        $nextCursor = '';
        while ($totalCount > 0) {
            $data = $this->orders->getAll($nextCursor);
            $totalCount = $data['list']['meta']['totalCount'];
            $nextCursor = $data['list']['meta']['nextCursor'];
            $result = array_merge($result, $data['list']['elements']['order']);
        }

        return $result;
    }

    /**
     * Get order
     *
     * @param string $id
     *
     * @return array
     */
    public function getOrder(string $id): array
    {
        return $this->orders->getOrder($id);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function updateQuantity(array $data): string
    {
        // prepare request data
        $requestData = [
            'InventoryHeader' => [
                'version' => '1.4'
            ],
            'Inventory'       => []
        ];
        foreach ($data as $sku => $quantity) {
            $requestData['Inventory'][] = [
                'sku'      => $sku,
                'quantity' => [
                    'unit'   => 'EACH',
                    'amount' => $quantity
                ]
            ];
        }

        $response = $this->inventory->bulkUpdateInventory($requestData);
        if (empty($response['feedId'])) {
            throw new \Exception('No feedId in the response of bulkUpdateInventory');
        }

        return $response['feedId'];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function updatePrice(array $data): string
    {
        // prepare request data
        $requestData = [
            'PriceHeader' => [
                'version' => '1.7'
            ],
            'Price'       => []
        ];
        foreach ($data as $sku => $price) {
            $requestData['Price'][] = [
                'sku'     => $sku,
                'pricing' => [
                    [
                        'currentPriceType' => 'BASE',
                        'currentPrice'     => [
                            'currency' => 'USD',
                            'amount'   => $price
                        ]
                    ]
                ]
            ];
        }

        $response = $this->price->bulkUpdatePrice($requestData);
        if (empty($response['feedId'])) {
            throw new \Exception('No feedId in the response of bulkUpdatePrice');
        }

        return $response['feedId'];
    }

    /**
     * @param array $result
     *
     * @return array
     * @throws \Exception
     */
    protected function setDataFromReport(array $result): array
    {
        if (!empty($result)) {
            $report = $this->reports->getItemReport();
            if (!empty($report)) {
                // set count from report
                foreach ($result as $k => $row) {
                    foreach ($report as $reportRow) {
                        if ($row['sku'] == $reportRow['sku']) {
                            $result[$k]['report'] = $reportRow;
                            break 1;
                        }
                    }
                }
            }
        }

        return $result;
    }
}
