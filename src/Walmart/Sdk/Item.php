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
    public const LIFECYCLE_STATUS_ACTIVE = 'ACTIVE';
    public const LIFECYCLE_STATUS_ARCHIVED = 'ARCHIVED';
    public const LIFECYCLE_STATUS_RETIRED = 'RETIRED';

    public const PUBLISHED_STATUS_PUBLISHED = 'PUBLISHED';
    public const PUBLISHED_STATUS_UNPUBLISHED = 'UNPUBLISHED';

    public const DEFAULT_LIMIT = 20;

    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#getAllItems
     *
     * @param int       $limit
     * @param string    $nextCursor
     * @param string    $lifecycleStatus
     * @param bool|null $isPublished
     *
     * @return array
     */
    public function getItems(
        int $limit = self::DEFAULT_LIMIT,
        string $nextCursor = null,
        string $lifecycleStatus = null,
        string $publishedStatus = null
    ): array {
        $path = "items?limit=$limit";

        $nextCursor = $nextCursor ?: '*';
        $path .= "&nextCursor=$nextCursor";

        if ($lifecycleStatus) {
            $path .= "&lifecycleStatus=$lifecycleStatus";
        }

        if ($publishedStatus) {
            if (!in_array($publishedStatus, [self::PUBLISHED_STATUS_PUBLISHED, self::PUBLISHED_STATUS_UNPUBLISHED])) {
                throw new \Exception('Unsupported published status');
            }

            $path .= "&publishedStatus=$publishedStatus";
        }

        return $this->request($path);
    }

    public function getPaginatedItems(
        int $limit = 20,
        $offset = 0,
        $lifecycleStatus = null,
        $publishedStatus = null
    ): array {
        $path = "items?limit=$limit&offset=$offset";

        if ($lifecycleStatus !== null) {
            $path .= "&lifecycleStatus=$lifecycleStatus";
        }

        if ($publishedStatus !== null) {
            $path .= "&publishedStatus=$publishedStatus";
        }

        return $this->request($path);
    }

    public function searchItem($msin, $type = 'upc', $nextCursor = '')
    {
        $path = "items/walmart/search?" . $type . "=" . $msin;
        if (!empty($nextCursor)) {
            $path .= "&nextCursor=$nextCursor";
        }

        return $this->request($path);
    }

    public function deleteItem($sku)
    {
        $path = "items/$sku";
        $response = $this->request($path, 'DELETE');
        if (!empty($response['errors'])) {
            $this->logger->error(implode(' | ', $response['errors']));
            return false;
        }

        return true;
    }
}
