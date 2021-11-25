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
    public const DEFAULT_PER_PAGE = 200;

    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#getAllOrders
     *
     * @param string $nextCursor
     *
     * @return array
     */
    public function getAll(?string $nextCursor = ''): array
    {
        $path = "orders";
        if (!empty($nextCursor)) {
            $path .= $nextCursor;
        } else {
            $path .= '?limit=' . self::DEFAULT_PER_PAGE;
        }

        return $this->request($path);
    }

    public function getOrders(
        int $limit = self::DEFAULT_PER_PAGE,
        \DateTime $startDate = null,
        \DateTime $endDate = null
    ): array {
        $path = "orders?limit=$limit";

        if ($startDate) {
            $path .= "&createdStartDate=" . $startDate->format('Y-m-d');
        }

        if ($endDate) {
            $path .= "&createdEndDate=" . $endDate->format('Y-m-d');
        }

        return $this->request($path);
    }

    /**
     * @param \DateTime $createdStartDate
     *
     * @return array
     */
    public function getByCreatedStartDate(\DateTime $createdStartDate): array
    {
        return $this->request("orders?createdStartDate=" . $createdStartDate->format('Y-m-d'));
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

    public function aknowledge($orderId)
    {
        return $this->request("orders/$orderId/acknowledge", 'POST');
    }
}
