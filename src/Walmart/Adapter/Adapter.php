<?php
declare(strict_types=1);

namespace rollun\Walmart\Adapter;

use rollun\Walmart\Sdk;

/**
 * Class Adapter
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Adapter implements AdapterInterface
{
    /**
     * @inheritDoc
     */
    public function getActiveItems()
    {
    }

    /**
     * @inheritDoc
     */
    public function getAllItems()
    {
        // prepare result
        $result = [];

        // get items
        $client = new Sdk\Item();
        $data['nextCursor'] = '';
        while (isset($data['nextCursor'])) {
            $data = $client->getItems('', 1000, $data['nextCursor']);
            $result = array_merge($result, $data['ItemResponse']);
        }

        // get item report for quantity finding
        if (!empty($result)) {
            $report = (new Sdk\Reports())->getItemReport();
            if (!empty($report)) {
                // set count from report
                foreach ($result as $k => $row) {
                    $result[$k]['count'] = null;
                    foreach ($report as $reportRow) {
                        if ($row['sku'] == $reportRow['sku']) {
                            $result[$k]['count'] = $reportRow['inventory_count'];
                            break 1;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getLastUpdatedItems()
    {
    }

    /**
     * @inheritDoc
     */
    public function endItems($items)
    {
    }

    /**
     * @inheritDoc
     */
    public function createItems($items)
    {
    }

    /**
     * @inheritDoc
     */
    public function updateItems($items)
    {
    }
}
