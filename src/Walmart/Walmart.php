<?php
declare(strict_types=1);

namespace rollun\Walmart;

use rollun\Walmart\Sdk;

/**
 * Class Walmart
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Walmart
{
    /**
     * @param string $feedId
     *
     * @return array
     */
    public function getFeedStatus(string $feedId): array
    {
        return (new Sdk\Feed())->getFeedStatus($feedId, true, 1000, 0);
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
        $client = new Sdk\Item();
        $data['nextCursor'] = '';
        while (isset($data['nextCursor'])) {
            $data = $client->getItems(1000, $data['nextCursor'], Sdk\Item::LIFECYCLE_STATUS_ACTIVE, true);
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
        $client = new Sdk\Item();
        $data['nextCursor'] = '';
        while (isset($data['nextCursor'])) {
            $data = $client->getItems(1000, $data['nextCursor']);
            $result = array_merge($result, $data['ItemResponse']);
        }

        return $this->setDataFromReport($result);
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

        $response = (new Sdk\Inventory())->bulkUpdateInventory($requestData);
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

        $response = (new Sdk\Price())->bulkUpdatePrice($requestData);
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
            $report = (new Sdk\Reports())->getItemReport();
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
