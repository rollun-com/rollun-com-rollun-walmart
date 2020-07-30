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
    const LIFECYCLE_STATUS_ACTIVE = 'ACTIVE';
    const LIFECYCLE_STATUS_ARCHIVED = 'ARCHIVED';
    const LIFECYCLE_STATUS_RETIRED = 'RETIRED';

    /**
     * https://developer.walmart.com/#/apicenter/marketPlace/latest#getAllItems
     *
     * @param int       $limit
     * @param string    $nextCursor
     * @param string    $lifecycleStatus
     * @param bool|null $isPublished
     *
     * @return array
     * @throws \Exception
     */
    public function getItems(int $limit = 20, string $nextCursor = '', string $lifecycleStatus = self::LIFECYCLE_STATUS_ACTIVE, ?bool $isPublished = null): array
    {
        $path = "items?limit=$limit&lifecycleStatus=$lifecycleStatus";
        if (!empty($nextCursor)) {
            $path .= "&nextCursor=$nextCursor";
        }
        if ($isPublished !== null) {
            $publishedStatus = empty($isPublished) ? 'UNPUBLISHED' : 'PUBLISHED';
            $path .= "&publishedStatus=$publishedStatus";
        }

        return $this->request($path);
    }
}
