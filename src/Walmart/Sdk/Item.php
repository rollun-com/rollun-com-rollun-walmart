<?php
declare(strict_types=1);

namespace rollun\Walmart\Sdk;

/**
 * Class Item
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Item extends Base
{
    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#getAllItems
     *
     * @param string $sku
     * @param int    $limit
     * @param int    $offset
     * @param string $nextCursor
     *
     * @return array
     */
    public function getItems(string $sku = '', int $limit = 20, string $nextCursor = '', int $offset = 0): array
    {
        $path = "items?limit=$limit";
        if (!empty($sku)) {
            $path .= "&sku=$sku";
        }
        if (!empty($nextCursor)) {
            $path .= "&nextCursor=$nextCursor";
        } else {
            $path .= "&offset=$offset";
        }

        return $this->request($path);
    }
}
