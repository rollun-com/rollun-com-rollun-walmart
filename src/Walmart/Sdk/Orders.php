<?php
declare(strict_types=1);

namespace rollun\Walmart\Sdk;

/**
 * Class Orders
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Orders extends Base
{
    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#getAllOrders
     *
     * @param string $nextCursor
     *
     * @return array
     */
    public function getAll(string $nextCursor = ''): array
    {
        $path = "orders";
        if (!empty($nextCursor)) {
            $path .= "?nextCursor=$nextCursor";
        }

        return $this->request($path);
    }

    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#getAnOrder
     *
     * @param string $purchaseOrderId
     *
     * @return array
     */
    public function getOrder(string $purchaseOrderId): array
    {
        return $this->request("orders/$purchaseOrderId");
    }

    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#shippingNotificationsUpdates
     *
     * @param string $purchaseOrderId
     * @param array  $data
     *
     * @return array
     */
    public function shippingUpdate(string $purchaseOrderId, array $data): array
    {
        return $this->request("orders/$purchaseOrderId/shipping", 'POST', $data);
    }
}
