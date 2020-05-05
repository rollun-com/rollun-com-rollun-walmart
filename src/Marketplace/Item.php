<?php
declare(strict_types=1);

namespace rollun\walmart\Marketplace;

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
    public function getItems(string $sku = '', int $limit = 20, int $offset = 0, string $nextCursor = '*'): array
    {
        return $this->request("items?offset=$offset&limit=$limit&nextCursor=$nextCursor&sku=$sku");
    }
}
