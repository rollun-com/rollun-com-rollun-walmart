<?php
declare(strict_types=1);

namespace rollun\Walmart\Sdk;

/**
 * Class Price
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Price extends Base
{
    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#priceManagement
     *
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function updateRegularPrice(array $data): array
    {
        return $this->request('price', 'PUT', $data);
    }

    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#updateBulkPrices
     *
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function bulkUpdatePrice(array $data): array
    {
        return $this->request('feeds?feedType=price', 'POST', $data);
    }
}
